<?php

namespace App\Components;
use Illuminate\Support\Facades\Storage;

class UploadMedia
{
    public function __construct(){
        //
    }

    /**
     * upload image object to default Storage disk
     */
    public function uploadImageObject($image){
        $filename = rand() . '_' . $image->getClientOriginalName();
        Storage::put($filename, fopen($image, 'r+'), 'public');
        return $filename;
    }

    /**
     * upload image vai path to default Storage disk
     */
    public function uploadImagePath($image, $image_name = null){
        if (empty($image_name)) {
            $image_name = end(explode('/', $image));
        }
        Storage::put($image_name, fopen($image, 'r+'), 'public');
        return $image_name;
    }

    /**
     * get image URL from default Storage disk
     */
    public function fileUrl($filename){
        return Storage::url($filename);
    }

    /**
     * get image URL from default Storage disk
     */
    public function getFile($filename){
        return Storage::get($filename);
    }

    /**
     * scale the given image to given scale size
     */
    public function scaleImage($filename, $scale){
        $scaled_filename = $scale.'_'.$filename;
        $scaled_file_path = public_path('uploads/'.$scaled_filename);
        $file_path = $this->fileUrl($filename);
        exec('ffmpeg -i '.$file_path.' -vf scale='.$scale.':-1 '.$scaled_file_path);
        return $this->uploadImagePath($scaled_file_path, $scaled_filename);
    }
}