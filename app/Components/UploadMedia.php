<?php

namespace App\Components;
use Illuminate\Support\Facades\Storage;

class UploadMedia
{
    public function __construct(){
        //
    }

    /**
     * upload image to default Storage disk
     */
    public function uploadImage($image){
        $filename = rand() . '_' . $image->getClientOriginalName();
        Storage::putFileAs('', $image, $filename);
        return $filename;
    }

    /**
     * get image URL from default Storage disk
     */
    public function fileUrl($filename){
        return Storage::url($filename);
    }

    /**
     * scale the given image to given scale size
     */
    public function scaleImage($filename, $scale){
        $scaled_filename = $scale.'_'.$filename;
        $scaled_file_path = public_path('uploads/'.$scaled_filename);
        $file_path = public_path('uploads/'.$filename);
        exec('ffmpeg -i '.$file_path.' -vf scale='.$scale.':-1 '.$scaled_file_path);
        return $scaled_filename;
    }
}