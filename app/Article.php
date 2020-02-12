<?php

namespace App;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
   protected $table="article";

 
  
    protected $fillable = [
        'Taille', 'Titre', 'Prix','Description','Couleur','Condition','Disponible','Imagename','Imagename1','Imagename2','Genre','Imagename3'
    ];

}
