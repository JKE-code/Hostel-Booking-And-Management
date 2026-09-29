<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentStorageService
{
    /**
     * Store a student verification document or photo.
     * Currently stores to Laravel's public storage disk.
     * Ready for Cloudinary drop-in: when Cloudinary keys are provided,
     * this method can route to Cloudinary without modifying controllers or views.
     *
     * @param UploadedFile $file
     * @param string $documentType e.g., 'id_proof', 'admission_letter', 'medical_cert', 'photo'
     * @param string $rollNumber
     * @return string Public URL of the uploaded document
     */
    public function storeDocument(UploadedFile $file, string $documentType, string $rollNumber): string
    {
        // If Cloudinary is configured in the future:
        if (config('services.cloudinary.enabled', false) && config('services.cloudinary.cloud_name')) {
            // Future Cloudinary integration:
            // $result = $file->storeOnCloudinary("hitam/students/{$rollNumber}/{$documentType}");
            // return $result->getSecurePath();
        }

        // Default: High-reliability local public storage
        $cleanRoll = Str::slug($rollNumber);
        $filename = $documentType . '_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $directory = "documents/students/{$cleanRoll}";

        $path = $file->storeAs($directory, $filename, 'public');

        return Storage::url($path);
    }

    /**
     * Delete an existing document file if present.
     */
    public function deleteDocument(?string $url): void
    {
        if (! $url) {
            return;
        }

        // If local storage URL:
        if (str_starts_with($url, '/storage/')) {
            $relativePath = str_replace('/storage/', '', $url);
            Storage::disk('public')->delete($relativePath);
        }
    }
}
