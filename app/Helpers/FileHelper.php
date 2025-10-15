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

        // Hapus file lama jika ada
        if ($oldFile) {
            self::deleteFile($folder, $oldFile);
        }

        // Generate nama file unik
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        // Pindahkan file ke folder public/uploads/{folder}
        $file->move(public_path('uploads/' . $folder), $fileName);

        return $fileName;
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

        foreach ($files as $file) {
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/' . $folder), $fileName);
            $uploadedFiles[] = $fileName;
        }

        return $uploadedFiles;
    }

    /**
     * Delete file
     */
    public static function deleteFile($folder, $fileName)
    {
        $filePath = public_path('uploads/' . $folder . '/' . $fileName);
        
        if (file_exists($filePath)) {
            unlink($filePath);
            return true;
        }

        return false;
    }

    /**
     * Delete multiple files
     */
    public static function deleteMultipleFiles($folder, $fileNames)
    {
        if (!is_array($fileNames)) {
            return false;
        }

        foreach ($fileNames as $fileName) {
            self::deleteFile($folder, $fileName);
        }

        return true;
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
     * Validate image file
     */
    public static function validateImage($file, $maxSize = 2048) // 2MB default
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedExtensions)) {
            return [
                'valid' => false,
                'message' => 'Format file tidak valid. Hanya menerima: ' . implode(', ', $allowedExtensions)
            ];
        }

        if ($file->getSize() > ($maxSize * 1024)) {
            return [
                'valid' => false,
                'message' => 'Ukuran file maksimal ' . $maxSize . 'KB'
            ];
        }

        return ['valid' => true];
    }

    /**
     * Validate document file
     */
    public static function validateDocument($file, $maxSize = 10240) // 10MB default
    {
        $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedExtensions)) {
            return [
                'valid' => false,
                'message' => 'Format file tidak valid. Hanya menerima: ' . implode(', ', $allowedExtensions)
            ];
        }

        if ($file->getSize() > ($maxSize * 1024)) {
            return [
                'valid' => false,
                'message' => 'Ukuran file maksimal ' . ($maxSize / 1024) . 'MB'
            ];
        }

        return ['valid' => true];
    }

    /**
     * Validate video file
     */
    public static function validateVideo($file, $maxSize = 102400) // 100MB default
    {
        $allowedExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedExtensions)) {
            return [
                'valid' => false,
                'message' => 'Format file tidak valid. Hanya menerima: ' . implode(', ', $allowedExtensions)
            ];
        }

        if ($file->getSize() > ($maxSize * 1024)) {
            return [
                'valid' => false,
                'message' => 'Ukuran file maksimal ' . ($maxSize / 1024) . 'MB'
            ];
        }

        return ['valid' => true];
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
}