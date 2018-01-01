<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = ['name', 'permalink'];

    public function getRouteKey()
    {
        return $this->permalink;
    }

    public function artists()
    {
        return $this->belongsToMany('App\Artist');
    }

    public function festivals()
    {
        return $this->belongsToMany('App\Festival');
    }
}
