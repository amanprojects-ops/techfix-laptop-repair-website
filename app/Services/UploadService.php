<?php

namespace App\Services;

class UploadService
{
    private string $uploadDir;
    private int    $maxSize;
    private array  $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
    private array  $allowedExts  = ['jpg', 'jpeg', 'png', 'webp'];

    public function __construct()
    {
        $this->uploadDir = BASE_PATH . '/storage/uploads/repair-images/';
        $this->maxSize   = (int)($_ENV['UPLOAD_MAX_SIZE'] ?? 5242880); // 5 MB

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    /**
     * Upload a repair image.
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

        // Verify actual MIME type from file content (not just header)
        $finfo    = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        if (!in_array($mimeType, $this->allowedMimes, true)) {
            throw new \RuntimeException('File content does not match an allowed image type.');
        }

        // Generate safe UUID filename
        $uuid     = $this->generateUUID();
        $filename = $uuid . '.' . $ext;
        $destPath = $this->uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            throw new \RuntimeException('Failed to save uploaded file.');
        }

        // Return relative path stored in DB
        return 'repair-images/' . $filename;
    }

    public function deleteFile(string $relativePath): void
    {
        $fullPath = BASE_PATH . '/storage/uploads/' . $relativePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    private function generateUUID(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
