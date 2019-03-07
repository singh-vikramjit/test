<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadMediaRequest;
use Illuminate\Support\Facades\Storage;
use App\Components\UploadMedia;
use App\Images;

class UploadMediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $images = Images::all();
        return view('home', compact('images'));
    }

    public function store(UploadMediaRequest $request)
    {
        $upload_media = new UploadMedia();
        $filename = $upload_media->uploadImage($request->file('image'));
        $orignal_file_url = $upload_media->fileUrl($filename);
        Images::create(['filename' => $filename, 'scale_size' => $request->scale]);
        return view('uploadFile', compact(['orignal_file_url']));
        /*$scaled_filename = $upload_media->scaleImage($filename, $request->scale);
        $scaled_file_url = $upload_media->fileUrl($scaled_filename);
        return view('uploadFile', compact(['orignal_file_url','scaled_file_url']));*/
    }

    public function create()
    {
        return view('uploadFile');
    }
}