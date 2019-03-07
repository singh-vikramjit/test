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
        $orignalFileUrl = $scaledFileUrl = '';
        if (request()->isMethod('post')) {
            request()->validate([
                                    'image' => 'required|image',
                                    'scale' => 'required|numeric|min:9|max:2000',
                                ]);
            $filename = $this->uploadImage(request()->file('image'));
            $orignalFileUrl = $this->fileUrl($filename);
            $scaledFileName = $this->scaleImage($filename, request()->scale);
            $scaledFileUrl = $this->fileUrl($scaledFileName);
        }
        return view('uploadFile', compact(['orignalFileUrl','scaledFileUrl']));
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
        $scaledFileName = $scale.'_'.$filename;
        $scaledFilePath = public_path('uploads/'.$scaledFileName);
        exec('ffmpeg -i '.public_path('uploads/'.$filename).' -vf scale='.$scale.':-1 '.$scaledFilePath);
        return $scaledFileName;
    }
}