<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadMediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orignal_file_url = $scaled_file_url = '';
        if (request()->isMethod('post')) {
            request()->validate([
                                    'image' => 'required|image',
                                    'scale' => 'required|numeric|min:9|max:2000',
                                ]);
            $filename = $this->uploadImage(request()->file('image'));
            $orignal_file_url = $this->fileUrl($filename);
            $scaled_filename = $this->scaleImage($filename, request()->scale);
            $scaled_file_url = $this->fileUrl($scaled_filename);
        }
        return view('uploadFile', compact(['orignal_file_url','scaled_file_url']));
    }

    private function uploadImage($image){
        $filename = rand() . '_' . $image->getClientOriginalName();
        Storage::putFileAs('', $image, $filename);
        return $filename;
    }

    private function fileUrl($filename){
        return Storage::url($filename);
    }

    private function scaleImage($filename, $scale){
        $scaled_filename = $scale.'_'.$filename;
        $scaled_file_path = public_path('uploads/'.$scaled_filename);
        exec('ffmpeg -i '.public_path('uploads/'.$filename).' -vf scale='.$scale.':-1 '.$scaled_file_path);
        return $scaled_filename;
    }
}