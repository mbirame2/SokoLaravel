<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vente extends Model
{
    protected $table="vente";

 
  
    protected $fillable = [
        'user', 'article'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function article(){
        return $this->belongsTo('App\Article');
    }
}
