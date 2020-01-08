<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class categorie extends Model
{
    protected $table="categorie";
    protected $fillable = [
        'name','created_at','updated_at'
    ];
}
