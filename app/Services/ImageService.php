<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;

class ImageService
{

    public function __construct()
    {
    }

    /**
     * 
     * @param $path Path to store the image at. (Storage Public Drive)
     * 
     * @param $image Image File
     * 
     * @return string $imageName Image Name
     */
    public function make(String $path, $image)
    {
        $imageName = uniqid() . '-' . $image->getClientOriginalName();
        $lastIndex = strlen($path) - 1;
        if ($path[$lastIndex] !== "/") {
            $path .= "/";
        }
        $fullPath = $path . $imageName;
        Storage::disk('public')->put($fullPath, file_get_contents($image));
        return $imageName;
    }

    /**
     * 
     * @param $path Path to the image to be deleted. (Storage Public Drive)
     * 
     * @param $imageName Name of the image to be deleted.
     * 
     * @return bool $bool
     */
    public function delete($path, $imageName)
    {
        $lastIndex = strlen($path) - 1;
        if ($path[$lastIndex] !== "/") {
            $path .= "/";
        }
        if (Storage::disk('public')->exists($path . $imageName)) {
            Storage::disk('public')->delete($path . $imageName);
        }
        return true;
    }
}
