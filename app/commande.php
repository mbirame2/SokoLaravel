<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class commande extends Model
{
    protected $table="commande";

 
  
    protected $fillable = [
        'adresse', 'mode_paiment','statut_commande'
    ];
}
