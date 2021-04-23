<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CulinaryImage extends Model
{
    protected $table = "culinary_images";
    protected $primaryKey = 'culinary_image_id';
    public $timestamps = false;
    protected $fillable = ['culinary_id','title','image_url','created_at','modified_at'];

    public function culinary()
    {
        return $this->belongsTo(Culinary::Class,'culinary_id');
    }
    
}
