<?php

namespace App\Core;

class Request
{
    private array $get;
    private array $post;
    private array $server;
    private array $files;

    public function __construct()
    {
        $this->get    = $_GET;
        $this->post   = $_POST;
        $this->server = $_SERVER;
        $this->files  = $_FILES;
    }

    public function method(): string
    {
        $serverMethod = strtoupper((string)($this->server['REQUEST_METHOD'] ?? 'GET'));
        // Support _method override for PUT/DELETE from HTML forms
        $override = $this->post['_method'] ?? null;
        if ($serverMethod === 'POST' && in_array(strtoupper((string)($override ?? '')), ['PUT', 'DELETE', 'PATCH'])) {
            return strtoupper((string)$override);
        }
        return $serverMethod;
    }

    public function uri(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        // Strip script base directory if running in subdirectory (e.g. /test/aman-laptop-reparing/public/)
        $scriptName = $this->server['SCRIPT_NAME'] ?? '';
        $baseDir = str_replace('\\', '/', dirname($scriptName));
        if ($baseDir !== '/' && $baseDir !== '.' && $baseDir !== '' && str_starts_with($path, $baseDir)) {
            $path = substr($path, strlen($baseDir));
        }

        // Clean and normalize path
        $path = '/' . trim((string)$path, '/');
        return $path === '/' ? '/' : rtrim($path, '/');
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->clean($this->get[$key] ?? $default);
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return $this->clean($this->post[$key] ?? $default);
    }

    public function all(): array
    {
        return array_merge($this->get, $this->post);
    }

    public function has(string $key): bool
    {
        return isset($this->post[$key]) || isset($this->get[$key]);
    }

    public function file(string $key): array|null
    {
        return $this->files[$key] ?? null;
    }

    public function ip(): string
    {
        return $this->server['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function isPut(): bool
    {
        return $this->method() === 'PUT';
    }

    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    public function csrfToken(): string
    {
        return $this->post['csrf_token'] ?? $this->get['csrf_token'] ?? '';
    }

    /** Strip tags and trim — NOT for HTML output, use htmlspecialchars() in views */
    private function clean(mixed $value): mixed
    {
        if (is_string($value)) {
            return trim(strip_tags($value));
        }
        if (is_array($value)) {
            return array_map([$this, 'clean'], $value);
        }
        return $value;
    }
}
