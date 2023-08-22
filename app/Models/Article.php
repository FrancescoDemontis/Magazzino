<?php

namespace App\Models;

use App\Models\ArticleRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'content',
        'description',
        'img',
        'price',
        'common',
        'category_id'
    ];

    public function requests()
    {
        return $this->hasMany(ArticleRequest::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    


}
