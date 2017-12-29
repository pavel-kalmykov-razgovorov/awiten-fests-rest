<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Festival extends Model
{
    protected $fillable = ['name', 'pathLogo', 'pathCartel', 'location', 'province', 'date', 'permalink', 'promoter_id'];

    public function getRouteKey()
    {
        return $this->permalink;
    }
}
