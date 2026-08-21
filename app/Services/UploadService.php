<?php

namespace App\Services;

class UploadService
{
    private string $storageDir;
    private string $publicDir;
    private int    $maxSize;
    private array  $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
    private array  $allowedExts  = ['jpg', 'jpeg', 'png', 'webp'];

    public function __construct()
    {
        $this->storageDir = BASE_PATH . '/storage/uploads/repair-images/';
        $this->publicDir  = BASE_PATH . '/public/uploads/repair-images/';
        $this->maxSize    = (int)($_ENV['UPLOAD_MAX_SIZE'] ?? 5242880); // 5 MB

        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0755, true);
        }
        if (!is_dir($this->publicDir)) {
            mkdir($this->publicDir, 0755, true);
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

    private function generateUUID(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
