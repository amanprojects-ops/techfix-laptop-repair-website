<?php
/**
 * TechFix Laptop Repair Management System
 * Database Migration CLI Runner
 *
 * Usage:
 *   php database/migrate.php          (Run pending migrations)
 *   php database/migrate.php --seed   (Run pending migrations + seed default data)
 *   php database/migrate.php --fresh  (Wipe database, re-run all migrations & seeders)
 *   php database/migrate.php --status (Show migration status)
 */

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

// Autoload
$autoloader = BASE_PATH . '/vendor/autoload.php';
if (!file_exists($autoloader)) {
    fwrite(STDERR, "[ERROR] Composer dependencies not found. Run 'composer install' first.\n");
    exit(1);
}
require $autoloader;

// Load .env
if (file_exists(BASE_PATH . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->safeLoad();
}

$dbConfig = require BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/app/helpers.php';

class MigrationRunner
{
    private PDO $pdo;
    private array $config;
    private string $migrationsDir;
    private bool $isFresh = false;
    private bool $shouldSeed = false;
    private bool $showStatus = false;

    public function __construct(array $config, array $argv)
    {
        $this->config = $config;
        $this->migrationsDir = BASE_PATH . '/database/migrations';

        // Parse CLI flags
        foreach ($argv as $arg) {
            if ($arg === '--fresh')  $this->isFresh = true;
            if ($arg === '--seed')   $this->shouldSeed = true;
            if ($arg === '--status') $this->showStatus = true;
        }

        $this->connect();
    }

    private function connect(): void
    {
        $serverDsn = sprintf(
            'mysql:host=%s;port=%s;charset=%s',
            $this->config['host'],
            $this->config['port'],
            $this->config['charset']
        );

        try {
            $this->pdo = new PDO($serverDsn, $this->config['user'], $this->config['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);

            // Ensure database exists
            $dbName = preg_replace('/[^a-zA-Z0-9_]/', '', $this->config['name']);
            $this->pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->pdo->exec("USE `{$dbName}`");

        } catch (PDOException $e) {
            $this->error("Database connection failed: " . $e->getMessage());
            exit(1);
        }
    }

    public function run(): void
    {
        $this->header();

        $this->ensureMigrationsTable();

        if ($this->showStatus) {
            $this->renderStatus();
            return;
        }

        if ($this->isFresh) {
            $this->wipeDatabase();
        }

        $this->runPendingMigrations();

        if ($this->shouldSeed || $this->isFresh) {
            $this->runSeeders();
        }

        $this->footer();
    }

    private function ensureMigrationsTable(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS `migrations` (
              `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
              `migration`   VARCHAR(255) NOT NULL,
              `batch`       INT UNSIGNED NOT NULL,
              `executed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              UNIQUE KEY `uq_migration_name` (`migration`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    private function wipeDatabase(): void
    {
        $this->info("Wiping existing database tables (--fresh)...");

        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

        $stmt = $this->pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            $this->pdo->exec("DROP TABLE IF EXISTS `{$table}`");
            echo "  - Dropped table: {$table}\n";
        }

        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        $this->ensureMigrationsTable();
        $this->success("Database wiped successfully!\n");
    }

    private function runPendingMigrations(): void
    {
        $allFiles = $this->getMigrationFiles();
        $executed = $this->getExecutedMigrations();

        $pending = [];
        foreach ($allFiles as $file) {
            $basename = basename($file);
            if (!isset($executed[$basename])) {
                $pending[] = $file;
            }
        }

        if (empty($pending)) {
            $this->info("Nothing to migrate. Database is already up to date.");
            return;
        }

        $batch = $this->getNextBatchNumber();
        $this->info("Running " . count($pending) . " pending migration(s) [Batch {$batch}]...");

        foreach ($pending as $file) {
            $basename = basename($file);
            $startTime = microtime(true);

            echo "  > Migrating: {$basename} ... ";

            try {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if ($ext === 'sql') {
                    $this->executeSqlFile($file);
                } elseif ($ext === 'php') {
                    require $file;
                }

                $stmt = $this->pdo->prepare("INSERT INTO `migrations` (`migration`, `batch`) VALUES (:migration, :batch)");
                $stmt->execute([
                    ':migration' => $basename,
                    ':batch'     => $batch,
                ]);

                $duration = round((microtime(true) - $startTime) * 1000, 2);
                echo "[\033[32mDONE\033[0m] ({$duration}ms)\n";

            } catch (\Throwable $e) {
                echo "[\033[31mFAILED\033[0m]\n";
                $this->error("Migration failed in file: {$basename}\n  Error: " . $e->getMessage());
                exit(1);
            }
        }

        $this->success("All migrations completed successfully!\n");
    }

    private function executeSqlFile(string $filePath): void
    {
        $sql = file_get_contents($filePath);
        if ($sql === false) {
            throw new RuntimeException("Could not read SQL file: {$filePath}");
        }

        // Execute raw SQL file with multi-query support
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
        $this->pdo->exec($sql);
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    }

    private function runSeeders(): void
    {
        echo "\n";
        $this->info("Running Database Seeders...");
        require_once BASE_PATH . '/database/seed.php';
        $seeder = new DatabaseSeeder($this->pdo, []);
        $seeder->run();
    }

    private function renderStatus(): void
    {
        $allFiles = $this->getMigrationFiles();
        $executed = $this->getExecutedMigrations();

        echo sprintf("%-40s | %-10s | %-8s | %-20s\n", "Migration File", "Status", "Batch", "Executed At");
        echo str_repeat("-", 86) . "\n";

        foreach ($allFiles as $file) {
            $basename = basename($file);
            if (isset($executed[$basename])) {
                $status = "\033[32mRan\033[0m";
                $batch  = (string)$executed[$basename]['batch'];
                $time   = $executed[$basename]['executed_at'];
            } else {
                $status = "\033[33mPending\033[0m";
                $batch  = "-";
                $time   = "-";
            }

            echo sprintf("%-40s | %-19s | %-8s | %-20s\n", $basename, $status, $batch, $time);
        }
        echo "\n";
    }

    private function getMigrationFiles(): array
    {
        if (!is_dir($this->migrationsDir)) {
            mkdir($this->migrationsDir, 0755, true);
        }

        $files = glob($this->migrationsDir . '/*.{sql,php}', GLOB_BRACE);
        sort($files);
        return $files ?: [];
    }

    private function getExecutedMigrations(): array
    {
        try {
            $stmt = $this->pdo->query("SELECT `migration`, `batch`, `executed_at` FROM `migrations`");
            $rows = $stmt->fetchAll();
            $result = [];
            foreach ($rows as $row) {
                $result[$row['migration']] = $row;
            }
            return $result;
        } catch (\Throwable) {
            return [];
        }
    }

    private function getNextBatchNumber(): int
    {
        $stmt = $this->pdo->query("SELECT MAX(`batch`) as max_batch FROM `migrations`");
        $row = $stmt->fetch();
        return (int)($row['max_batch'] ?? 0) + 1;
    }

    private function header(): void
    {
        echo "====================================================\n";
        echo "  TechFix - Database Migration & Seeding Engine\n";
        echo "====================================================\n";
        echo "  DB Host : {$this->config['host']}:{$this->config['port']}\n";
        echo "  DB Name : {$this->config['name']}\n";
        echo "  DB User : {$this->config['user']}\n";
        echo "----------------------------------------------------\n";
    }

    private function footer(): void
    {
        echo "====================================================\n";
        echo "  Ready! Admin URL : " . ($_ENV['APP_URL'] ?? 'http://localhost:8000') . "/admin/login\n";
        echo "====================================================\n";
    }

    private function info(string $msg): void
    {
        echo "\033[36mℹ " . $msg . "\033[0m\n";
    }

    private function success(string $msg): void
    {
        echo "\033[32m✔ " . $msg . "\033[0m\n";
    }

    private function error(string $msg): void
    {
        echo "\033[31m✖ [ERROR] " . $msg . "\033[0m\n";
    }
}

// Run
$runner = new MigrationRunner($dbConfig, $argv);
$runner->run();
