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
        $article->Disponible="oui";
        
        $image = base64_encode(file_get_contents($req->file('imagename')));
        $article->Imagename=$image;
        if($req->hasFile('imagename1')){
          $image1 = base64_encode(file_get_contents($req->file('imagename1')));
          $article->Imagename1=$image1;
        }
        if($req->hasFile('imagename2')){
          $image2 = base64_encode(file_get_contents($req->file('imagename2')));
          $article->Imagename2=$image2;

        }
        if($req->hasFile('imagename3')){
          $image3 = base64_encode(file_get_contents($req->file('imagename3')));
          $article->Imagename3=$image3;

        } 
     
      
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
      $invoice = new \Paydunya\Checkout\CheckoutInvoice();

      \Paydunya\Setup::setMasterKey("pApwUxLn-U8Kh-doFk-mWbL-HlBbJKfV1VUC");
      \Paydunya\Setup::setPublicKey("test_public_btfkB3P424IgQZrSraELQjci41k");
      \Paydunya\Setup::setPrivateKey("test_private_FMgAMFjgGAnFfyrPCm5j70oliOM");
      \Paydunya\Setup::setToken("CBFVDXcYTo7TMHUiDsiY");
      \Paydunya\Setup::setMode("test");

      \Paydunya\Checkout\Store::setName("SOKO Dakar"); // Seul le nom est requis
      \Paydunya\Checkout\Store::setPhoneNumber("786087832");
      \Paydunya\Checkout\Store::setWebsiteUrl("https://www.sokodakar.com");

      \Paydunya\Checkout\Store::setCallbackUrl("api.sokodakar.com/api/pay");


      //$com=new commande();
     // $com->adresse=$req->input('adresse');
   //   $com->mode_paiment=$req->input('modpai');
      //$com->statut_commande='en cours';
    //$com->save();
      
      foreach ($req->product as $flight) {
        $achat = new achat();
        $achat->user()->associate(auth('api')->user());
       $article = Article::where('id',$flight)->first();
        $article->Disponible="non";
     //   $article->save();

    //    $achat->article()->associate($article);
      //  $achat->commande()->associate($com);
        //$achat->save();
       
       
       $invoice->addItem($article->Titre, 1,$article->Prix , $article->Prix);
      }
      $invoice->addTax("TVA (15%)", $article->Prix*15/100);
$invoice->addTax("Livraison", 1000);
     // $invoice->setTotalAmount($req->total+$article->Prix*15/100+1000);
echo $invoice->getInvoiceUrl();
       if($invoice->create()) {
         header("Location: ".$invoice->getInvoiceUrl());
     }else{
         echo $invoice->response_text;
     }
     // return response()->json($req); 
    
  }
  public function api(){
  
      //Prenez votre MasterKey, hashez la et comparez le résultat au hash reçu par IPN
      if($_POST['data']['hash'] === hash('sha512', "VOTRE_CLE_PRINCIPALE")) {
    
        if ($_POST['data']['status'] == "completed") {
           echo $_POST['data'];
        }
    
        } else {
              die("Cette requête n'a pas été émise par PayDunya");
        }
    
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

    public function remove($id){
      triagearticles::where('article_id',$id)->delete();  
     
       vente::where('article_id',$id)->delete();
       Article::where('id',$id)->delete();
      return response("Supprimer avec succés", 200)  ;
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
/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
 public function update(Request $req){
   
  $user =User::find(auth('api')->user()->id);  
  if ( $req->input('password')) {
  if(Auth::attempt(['telephone' => auth('api')->user()->telephone, 'password' => $req->input('password')])){ 


      $user->password = bcrypt($req->input('cpassword'));
  }else{
    return response("unhotorisez", 401)
    ->header('Content-Type', 'application/json');
  }
}

if($req->input('prenom')){
  $user->prenom = $req->input('prenom');}
  if($req->input('nom')){
  $user->nom = $req->input('nom');}
  if($req->input('telephone')){
  $user->telephone = $req->input('telephone');
  }
  if($req->input('adresse')){
    $user->adresse = $req->input('adresse');
    }
    $user->save();
    return response("succés", 200)
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
