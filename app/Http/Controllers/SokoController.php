<?php

namespace App\Http\Controllers;

use App\achat;
use App\vente;
use Validator;
use Illuminate\Http\Request;
use App\User;
use App\Article;
use App\categorie;
use App\commande;
use App\sscategorie;
use App\triagearticles;
use Illuminate\Support\Facades\Auth; 

class SokoController extends Controller
{
    function liste(){
        return User::all();
    }
    function ventearticle(Request $req){
      $validator = Validator::make($req->all(), [ 
        'taille' => 'required', 
        'titre' => 'required', 
        'prix' => 'required', 
        'description' => 'required', 
        'couleur' => 'required', 
        'condition' => 'required', 
        'confid' => 'required', 
    ]);
if ($validator->fails()) { 
        return response()->json(['error'=>$validator->errors()], 401);            
    }else{
      if($req->hasFile('imagename')){
        $article= new Article;
        $article->Taille=$req->input('taille');
        $article->Titre=$req->input('titre');
        $article->Prix=$req->input('prix');
        $article->Description=$req->input('description');
        $article->Couleur=$req->input('couleur');
        $article->Genre=$req->input('genre');
        $article->Condition=$req->input('condition');
          $article->Confid=$req->input('confid');

        $article->Disponible="oui";
        $image = base64_encode(file_get_contents($req->file('imagename')));
          

            $article->Imagename=$image;
     
      
            $vente=new vente();
  
            $vente->user()->associate(auth('api')->user());

         $i=   categorie::where('name', $req->input('categorie'))->get();
         $ss= sscategorie::where('name', $req->input('sscategorie'))->get();
      
          foreach ($i as $flight) {
            $i=$flight;
          }
          foreach ($ss as $flight) {
            $ss=$flight;
          }
         $trie= new triagearticles();
       
         $trie->categorie()->associate($i);
         $trie->sscategorie()->associate($ss);
         $article->save();
         $trie->article()->associate($article);
            
          $vente->article()->associate($article);
          $vente->save();
           $trie->save();

        return response()->json("bien fait");
        }else{
          return response()->json(['error'=>$validator->errors()], 401);
        }
    }
  }

    public function allarticle()
    {
      $article = Article::all();
      return response()->json($article); 
    }
    public function allcat()
    {
      $article = categorie::all();
      return response()->json($article); 
    }
    public function allsscat()
    {
      $article = sscategorie::all();
      return response()->json($article); 
    }

  

    public function commande (Request $req){
      $com=new commande();
      $com->adresse=$req->input('adresse');
      $com->mode_paiment=$req->input('modpai');
      $com->statut_commande='en cours';
      $com->save();

      $achat= new achat();
    //  echo $req->product;
     // foreach ($req->product as $flight) {
    
       
        $achat->user()->associate(auth('api')->user());
        $article=new Article();
        $article=$req->product[0];
var_dump($article);die();
        $achat->article()->associate($article);
        $achat->commande()->associate($com);
        $achat->save();
   //    }
      return response()->json($req->product); 
    }
    public function allachat()
    {
      $article = achat::with(['article', 'user','commande'])->where('user_id', auth('api')->user()->id)->get();
      return response($article, 200)
      ->header('Content-Type', 'application/json');
    }

    public function allnew()
    {
      $article = triagearticles::with(['article', 'categorie','sscategorie'])->orderBy('created_at')->get();
      return response($article, 200)
      ->header('Content-Type', 'application/json');
    }

    public function onevente($id){
      $article = vente::with(['user'])->where('article_id',$id)->first();
      return response($article, 200)  ;
                }
    public function allvente()
    {
      $article = vente::with(['article', 'user'])->where('user_id', auth('api')->user()->id)->get();
      return response($article, 200)
                  ->header('Content-Type', 'application/json');
    }

 public function getUser(){
  return response(auth('api')->user(), 200)
  ->header('Content-Type', 'application/json');
 }
 public function allhomme(){
  $article = triagearticles::with(['article', 'categorie','sscategorie'])->whereHas('article', function ($query) {
    $query->where('Genre', 'Homme');
})->get();
  return response($article, 200)
  ->header('Content-Type', 'application/json');
}

public function allfemme(){
  $article = triagearticles::with(['article', 'categorie','sscategorie'])->whereHas('article', function ($query) {
    $query->where('Genre', 'Femme');
})->get();
  return response($article, 200)
  ->header('Content-Type', 'application/json');
}
  

}
