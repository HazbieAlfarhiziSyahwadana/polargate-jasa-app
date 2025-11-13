<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileHelper
{
    /**
     * Upload single file
     */
    public static function uploadFile($file, $folder, $oldFile = null)
    {
        if (!$file) {
            return null;
        }

        try {
            // Hapus file lama jika ada
            if ($oldFile) {
                self::deleteFile($folder, $oldFile);
            }

            // Generate nama file unik
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            // Buat folder jika belum ada
            $uploadPath = public_path('uploads/' . $folder);
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Pindahkan file ke folder public/uploads/{folder}
            $file->move($uploadPath, $fileName);

            return $fileName;
            
        } catch (\Exception $e) {
            \Log::error('Error upload file: ' . $e->getMessage());
            throw new \Exception('Gagal mengupload file: ' . $e->getMessage());
        }
    }

    /**
     * Upload multiple files
     */
    public static function uploadMultipleFiles($files, $folder)
    {
        $uploadedFiles = [];

        if (!$files) {
            return $uploadedFiles;
        }

        try {
            // Buat folder jika belum ada
            $uploadPath = public_path('uploads/' . $folder);
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            foreach ($files as $file) {
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $fileName);
                $uploadedFiles[] = $fileName;
            }

            return $uploadedFiles;
            
        } catch (\Exception $e) {
            // Hapus file yang sudah terupload jika ada error
            foreach ($uploadedFiles as $uploadedFile) {
                self::deleteFile($folder, $uploadedFile);
            }
            
            \Log::error('Error upload multiple files: ' . $e->getMessage());
            throw new \Exception('Gagal mengupload file: ' . $e->getMessage());
        }
    }

    /**
     * Delete file
     */
    public static function deleteFile($folder, $fileName)
    {
        try {
            $filePath = public_path('uploads/' . $folder . '/' . $fileName);
            
            if (file_exists($filePath)) {
                unlink($filePath);
                return true;
            }

            return false;
            
        } catch (\Exception $e) {
            \Log::error('Error delete file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete multiple files
     */
    public static function deleteMultipleFiles($folder, $fileNames)
    {
        if (!is_array($fileNames)) {
            return false;
        }

        try {
            foreach ($fileNames as $fileName) {
                self::deleteFile($folder, $fileName);
            }

            return true;
            
        } catch (\Exception $e) {
            \Log::error('Error delete multiple files: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get file URL
     */
    public static function getFileUrl($folder, $fileName)
    {
        if (!$fileName) {
            return null;
        }

        return asset('uploads/' . $folder . '/' . $fileName);
    }

    /**
     * Check if file exists
     */
    public static function fileExists($folder, $fileName)
    {
        if (!$fileName) {
            return false;
        }

        $filePath = public_path('uploads/' . $folder . '/' . $fileName);
        return file_exists($filePath);
    }

    /**
     * Validate image file
     */
    public static function validateImage($file, $maxSize = 2048) // 2MB default
    {
        if (!$file) {
            return [
                'valid' => false,
                'message' => 'File tidak ditemukan'
            ];
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedExtensions)) {
            return [
                'valid' => false,
                'message' => 'Format file tidak valid. Hanya menerima: ' . implode(', ', $allowedExtensions)
            ];
        }

        // Convert KB to bytes
        $maxSizeBytes = $maxSize * 1024;
        if ($file->getSize() > $maxSizeBytes) {
            return [
                'valid' => false,
                'message' => 'Ukuran file maksimal ' . $maxSize . 'KB'
            ];
        }

        return ['valid' => true, 'message' => 'File valid'];
    }

    /**
     * Validate document file
     */
    public static function validateDocument($file, $maxSize = 10240) // 10MB default
    {
        if (!$file) {
            return [
                'valid' => false,
                'message' => 'File tidak ditemukan'
            ];
        }

        $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedExtensions)) {
            return [
                'valid' => false,
                'message' => 'Format file tidak valid. Hanya menerima: ' . implode(', ', $allowedExtensions)
            ];
        }

        // Convert KB to bytes
        $maxSizeBytes = $maxSize * 1024;
        if ($file->getSize() > $maxSizeBytes) {
            return [
                'valid' => false,
                'message' => 'Ukuran file maksimal ' . ($maxSize / 1024) . 'MB'
            ];
        }

        return ['valid' => true, 'message' => 'File valid'];
    }

    /**
     * Validate video file
     */
    public static function validateVideo($file, $maxSize = 102400) // 100MB default
    {
        if (!$file) {
            return [
                'valid' => false,
                'message' => 'File tidak ditemukan'
            ];
        }

        $allowedExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedExtensions)) {
            return [
                'valid' => false,
                'message' => 'Format file tidak valid. Hanya menerima: ' . implode(', ', $allowedExtensions)
            ];
        }

        // Convert KB to bytes
        $maxSizeBytes = $maxSize * 1024;
        if ($file->getSize() > $maxSizeBytes) {
            return [
                'valid' => false,
                'message' => 'Ukuran file maksimal ' . ($maxSize / 1024) . 'MB'
            ];
        }

        return ['valid' => true, 'message' => 'File valid'];
    }

    /**
     * Get file size in human readable format
     */
    public static function getFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Get actual file size from uploaded file
     */
    public static function getUploadedFileSize($folder, $fileName)
    {
        if (!$fileName) {
            return null;
        }

        $filePath = public_path('uploads/' . $folder . '/' . $fileName);
        
        if (!file_exists($filePath)) {
            return null;
        }

        $bytes = filesize($filePath);
        return self::getFileSize($bytes);
    }

    /**
     * Move file from one folder to another
     */
    public static function moveFile($oldFolder, $newFolder, $fileName)
    {
        try {
            $oldPath = public_path('uploads/' . $oldFolder . '/' . $fileName);
            $newPath = public_path('uploads/' . $newFolder . '/' . $fileName);

            if (!file_exists($oldPath)) {
                return false;
            }

            // Buat folder baru jika belum ada
            $newFolderPath = public_path('uploads/' . $newFolder);
            if (!file_exists($newFolderPath)) {
                mkdir($newFolderPath, 0755, true);
            }

            // Pindahkan file
            return rename($oldPath, $newPath);
            
        } catch (\Exception $e) {
            \Log::error('Error move file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Copy file to another folder
     */
    public static function copyFile($sourceFolder, $destFolder, $fileName, $newFileName = null)
    {
        try {
            $sourcePath = public_path('uploads/' . $sourceFolder . '/' . $fileName);
            $destFileName = $newFileName ?? $fileName;
            $destPath = public_path('uploads/' . $destFolder . '/' . $destFileName);

            if (!file_exists($sourcePath)) {
                return false;
            }

            // Buat folder tujuan jika belum ada
            $destFolderPath = public_path('uploads/' . $destFolder);
            if (!file_exists($destFolderPath)) {
                mkdir($destFolderPath, 0755, true);
            }

            // Copy file
            return copy($sourcePath, $destPath);
            
        } catch (\Exception $e) {
            \Log::error('Error copy file: ' . $e->getMessage());
            return false;
        }
    }
}