<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'lead', 'body', 'permalink', 'festival_id'];

    public function getRouteKey()
    {
        return $this->permalink;
    }

    public function festival()
    {
        return $this->belongsTo('App\Festival', 'festival_id');
    }
}
