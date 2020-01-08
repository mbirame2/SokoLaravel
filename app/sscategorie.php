<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sscategorie extends Model
{
    protected $table="sscategorie";

    protected $fillable = [
        'name','created_at','updated_at'
    ];
}
