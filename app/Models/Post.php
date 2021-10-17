<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use HasFactory;
    use Sluggable;

    //this means u can fill these fields in the  post table
    protected $fillable = ['title','slug', 'description', 'image_path', 'user_id'];
//then go back to the post controller to create a post

    //one post belongs to one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //return array
    public function sluggable():array
    {
        return [
            'slug'=> [
                'source'=> 'title'
            ]
        ];
    }
}
