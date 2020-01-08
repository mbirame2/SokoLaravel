<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class triagearticles extends Model
{
    protected $table="triagesarticle";

 
  
    protected $fillable = [
        'categorie', 'sscategorie','article','article_id','categorie_id','sscategorie_id'
    ];
    public function categorie(){
        return $this->belongsTo('App\categorie');
    }
    public function sscategorie(){
        return $this->belongsTo('App\sscategorie');
    }
    public function article(){
        return $this->belongsTo('App\Article');
    }
}
