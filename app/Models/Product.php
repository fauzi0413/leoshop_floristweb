<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Tentukan kolom yang bisa diisi mass-assignment
    protected $fillable = [
        'title',
        'category',
        'price',
        'image',
        'description',
        'category_id',
    ];

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
