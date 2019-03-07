<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Components\UploadMedia;

class Images extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filename', 'scale_size', 'scaled_filename'
    ];

    protected $appends = ['file_url','scaled_file_url'];

    public function getFileUrlAttribute()
    {
        return $this->attributes['file_url'] = (new UploadMedia)->fileUrl($this->attributes['filename']);
    }

    public function getScaledFileUrlAttribute()
    {
        $attributes = $this->attributes;
        $scaled_filename = $attributes['scaled_filename'];
        return $attributes['scaled_file_url'] = ($scaled_filename) ? (new UploadMedia)->fileUrl($scaled_filename) : '';
    }
}