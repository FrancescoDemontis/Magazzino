<?php

namespace App\Models;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'background_color', 'text_color'];
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
