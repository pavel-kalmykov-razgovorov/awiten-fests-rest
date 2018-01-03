<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['name', 'path', 'permalink', 'festival_id'];

    public function getRouteKey()
    {
        return $this->permalink;
    }

    public function festival()
    {
        return $this->belongsTo('App\Festival', 'festival_id');
    }
}
