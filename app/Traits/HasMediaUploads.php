<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait HasMediaUploads
{
    /**
     * Store new media image
     *
     * @param UploadedFile $imageFile
     * @param string $collection
     */
    public function storeMediaImage($imageFile, string $collection)
    {
        if (!empty($imageFile) && !empty($collection) && ($imageFile instanceof UploadedFile)) {
            $this->addMedia($imageFile)->toMediaCollection($collection);
        }

        return $this;
    }

    /**
     * Store new media images
     *
     * @param array $imageFiles
     * @param string $collection
     */
    public function storeMediaImages($imageFiles, string $collection)
    {
        if (!empty($imageFiles) && !empty($collection) && is_array($imageFiles)) {
            foreach ($imageFiles as $file) {

                if (isset($file['file']) && $file['file'] instanceof UploadedFile) {
                    $this->addMedia($file['file'])->toMediaCollection($collection);
                }
            }
        }

        return $this;
    }

    /**
     * Update media image
     * 
     * @param UploadedFile $imageFile
     * @param string $collection
     */
    public function updateMediaImage($imageFile, string $collection)
    {
        if (!empty($imageFile) && !empty($collection) && ($imageFile instanceof UploadedFile)) {
            $this->clearMediaCollection($collection);

            $this->addMedia($imageFile)->toMediaCollection($collection);

        } elseif ($imageFile === null) {
            $this->clearMediaCollection($collection);
        }

        return $this;
    }

    /**
     * Update media images
     * 
     * @param array $imageFiles
     * @param string $collection
     */
    public function updateMediaImages($imageFiles, string $collection)
    {
        if (!empty($imageFiles) && !empty($collection) && is_array($imageFiles)) {
            $data = $this->normalizeFiles($imageFiles);

            $files = $data['files'] ?? [];
            $existingIds = $data['existingIds'] ?? [];

            // Replace old images?
            if (empty($existingIds)) {
                $this->clearMediaCollection($collection);

            } else {
                $this->media()
                    ->where('collection_name', $collection)
                    ->whereNotIn('id', $existingIds)
                    ->delete();
            }

            if (!empty($files)) {
                foreach ($files as $file) {
                    $this->addMedia($file)->toMediaCollection($collection);
                }
            }

        } elseif ($imageFiles === null) {
            $this->clearMediaCollection($collection);
        }

        return $this;
    }

    /**
     * Normalize the input into an array of UploadedFile
     */
    private function normalizeFiles($filesRequest): array
    {
        $files = [];
        $existingIds = [];

        foreach ($filesRequest as $file) {
            if (isset($file['file']) && $file['file'] instanceof UploadedFile) {
                $files[] = $file['file'];
            } elseif (isset($file['id'])) {
                $existingIds[] = $file['id'];
            }
        }

        return [
            'files' => $files,
            'existingIds' => $existingIds,
        ];
    }
}
