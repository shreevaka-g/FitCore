<?php

namespace domain\Services;

use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function store($image, $folderName)
    {

        $fileName = time() . '_' . uniqid() . '.' . $image->extension();

        $imagePath = Storage::disk('public')->putFileAs($folderName, $image, $fileName);

        return $imagePath;
    }

    public function delete($path)
    {
        Storage::disk('public')->delete($path);
    }
}
