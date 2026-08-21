<?php

declare(strict_types=1);

namespace App\Services;

class UploadService
{
    private string $storageDir;
    private string $publicDir;
    private string $brandingStorageDir;
    private string $brandingPublicDir;
    private int    $maxSize;
    private array  $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
    private array  $allowedExts  = ['jpg', 'jpeg', 'png', 'webp'];

    private array  $brandingAllowedMimes = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/svg+xml',
        'image/x-icon',
        'image/vnd.microsoft.icon',
    ];
    private array  $brandingAllowedExts  = ['jpg', 'jpeg', 'png', 'webp', 'svg', 'ico'];

    public function __construct()
    {
        $this->storageDir         = BASE_PATH . '/storage/uploads/repair-images/';
        $this->publicDir          = BASE_PATH . '/public/uploads/repair-images/';
        $this->brandingStorageDir = BASE_PATH . '/storage/uploads/branding/';
        $this->brandingPublicDir  = BASE_PATH . '/public/uploads/branding/';
        $this->maxSize            = (int)($_ENV['UPLOAD_MAX_SIZE'] ?? 5242880); // 5 MB

        $dirs = [
            $this->storageDir,
            $this->publicDir,
            $this->brandingStorageDir,
            $this->brandingPublicDir,
        ];

        foreach ($dirs as $d) {
            if (!is_dir($d)) {
                @mkdir($d, 0755, true);
            }
        }
    }

    /**
     * Upload a repair image.
     * Saves to public and storage directories.
     * Returns relative path on success, throws on failure.
     */
    public function uploadRepairImage(array $file): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload failed. Error code: ' . $file['error']);
        }

        if ($file['size'] > $this->maxSize) {
            throw new \RuntimeException('File too large. Maximum size is 5 MB.');
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowedExts, true)) {
            throw new \RuntimeException('Invalid file type. Only JPG, PNG, and WebP are allowed.');
        }

        // Verify actual MIME type from file content
        $finfo    = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        if (!in_array($mimeType, $this->allowedMimes, true)) {
            throw new \RuntimeException('File content does not match an allowed image type.');
        }

        // Generate safe UUID filename
        $uuid     = $this->generateUUID();
        $filename = $uuid . '.' . $ext;
        $destPublic  = $this->publicDir . $filename;
        $destStorage = $this->storageDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPublic)) {
            throw new \RuntimeException('Failed to save uploaded file.');
        }

        // Keep storage copy in sync
        @copy($destPublic, $destStorage);

        // Return relative path stored in DB
        return 'repair-images/' . $filename;
    }

    /**
     * Upload a site branding asset (logo, dark logo, favicon, touch icon, og image).
     * Validates MIME type, file extension, max 2MB size, and sanitizes SVG against XSS.
     * Returns public-relative path (e.g. 'uploads/branding/site_logo_xxxx.png').
     */
    public function uploadBrandingAsset(array $file, string $assetType = 'logo'): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors = [
                UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize directive.',
                UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE directive.',
                UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially uploaded.',
                UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.',
            ];
            throw new \RuntimeException($errors[$file['error']] ?? ('Upload failed with error code ' . $file['error']));
        }

        $maxBrandingSize = 2097152; // 2 MB
        if ($file['size'] > $maxBrandingSize) {
            throw new \RuntimeException('Branding asset too large. Maximum allowed size is 2 MB.');
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $this->brandingAllowedExts, true)) {
            throw new \RuntimeException('Invalid file format. Allowed formats: PNG, JPG, WebP, SVG, ICO.');
        }

        // Validate MIME type
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = (string)$finfo->file($file['tmp_name']);

        // Handle SVG and ICO MIME variations
        $isSvg = ($ext === 'svg' || str_contains($mimeType, 'svg') || str_contains($mimeType, 'xml'));
        $isIco = ($ext === 'ico' || str_contains($mimeType, 'vnd.microsoft.icon') || str_contains($mimeType, 'x-icon') || $mimeType === 'image/x-ico' || $mimeType === 'image/ico');

        if (!$isSvg && !$isIco && !in_array($mimeType, $this->brandingAllowedMimes, true)) {
            throw new \RuntimeException("Invalid image MIME type: {$mimeType}.");
        }

        // Sanitize SVG if uploaded
        if ($isSvg) {
            $this->sanitizeSvg($file['tmp_name']);
        }

        // Safe sanitized prefix
        $safePrefix = preg_replace('/[^a-z0-9_-]/', '', strtolower($assetType)) ?: 'branding';
        $uniqueSuffix = bin2hex(random_bytes(6));
        $filename = "{$safePrefix}_{$uniqueSuffix}.{$ext}";

        $destPublic  = $this->brandingPublicDir . $filename;
        $destStorage = $this->brandingStorageDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPublic)) {
            throw new \RuntimeException('Failed to move uploaded branding asset.');
        }

        @copy($destPublic, $destStorage);

        return 'uploads/branding/' . $filename;
    }

    /**
     * Delete previous branding asset file safely
     */
    public function deleteBrandingAsset(?string $relativePath): void
    {
        if (empty($relativePath)) {
            return;
        }

        // Security check: ensure path is inside uploads/branding/ and prevent traversal
        $clean = ltrim($relativePath, '/\\');
        if (!str_starts_with($clean, 'uploads/branding/')) {
            return;
        }

        $filename = basename($clean);
        $publicFile  = $this->brandingPublicDir . $filename;
        $storageFile = $this->brandingStorageDir . $filename;

        if (file_exists($publicFile)) {
            @unlink($publicFile);
        }
        if (file_exists($storageFile)) {
            @unlink($storageFile);
        }
    }

    /**
     * Sanitize SVG against XSS vectors (<script>, onload, javascript: urls)
     */
    private function sanitizeSvg(string $filePath): void
    {
        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new \RuntimeException('Could not read SVG file content.');
        }

        // Basic check that it starts or contains <svg
        if (!stripos($content, '<svg')) {
            throw new \RuntimeException('Uploaded file does not appear to be a valid SVG.');
        }

        // Remove dangerous tags and attributes
        $patterns = [
            '/<script\b[^>]*>(.*?)<\/script>/is',
            '/<foreignObject\b[^>]*>(.*?)<\/foreignObject>/is',
            '/on[a-z]+\s*=\s*(".*?"|\'.*?\'|[^\s>]+)/i',
            '/href\s*=\s*("javascript:[^"]*"|\'javascript:[^\']*\'|javascript:[^\s>]+)/i',
            '/xlink:href\s*=\s*("javascript:[^"]*"|\'javascript:[^\']*\'|javascript:[^\s>]+)/i',
        ];

        $cleaned = preg_replace($patterns, '', $content);
        if ($cleaned === null) {
            throw new \RuntimeException('SVG sanitization error.');
        }

        file_put_contents($filePath, $cleaned);
    }

    private function generateUUID(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
