<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{

    protected $table = 'news';

    protected $fillable = ['name', 'alias', 'short_story', 'cat_id', 'full_story','small_img', 'big_img'];

    public function user(){

        return $this->belongsTo('App\User');
    }


    public function category(){

        return $this->belongsTo('App\Category', 'cat_id');
    }

    public function comments(){

        return $this->hasMany('App\Comment');
    }
}
