<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = ['name', 'soundcloud', 'website', 'country', 'permalink', 'pathProfile', 'pathHeader', 'manager_id'];

    public function getRouteKey()
    {
        return $this->permalink;
    }

    public function manager()
    {
        return $this->belongsTo('App\User', 'manager_id');
    }
}
