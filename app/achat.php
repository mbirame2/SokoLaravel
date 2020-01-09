<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class achat extends Model
{
    protected $table="achat";
    public $timestamps = false;

 
  
    protected $fillable = [
        'article', 'user','commande'
    ];
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function article(){
        return $this->belongsTo('App\Article');
    }
    public function commande(){
        return $this->belongsTo('App\commande');
    }
}
