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
use File;
use Facade\FlareClient\Http\Response;
use Paydunya\Checkout\CheckoutInvoice;
use Illuminate\Support\Facades\Auth; 

class SokoController extends Controller
{
    function liste(){
        return User::all();
    }
  public function ventearticle(Request $req){
      $validator = Validator::make($req->all(), [ 
        'taille' => 'required', 
        'titre' => 'required', 
        'prix' => 'required', 
        'imagename'=> 'required',
        'description' => 'required', 
        'couleur' => 'required', 
    ]); 

if ($validator->fails()) { 

        return response()->json(['error'=>$validator->errors()], 401);            
    }else{
     // var_dump($req->all());die();
  //    if($req->hasFile('imagename')){
     //  
        $article= new Article;
        $article->Taille=$req->input('taille');
        $article->Titre=$req->input('titre');
        $article->Prix=$req->input('prix');
        $article->Description=$req->input('description');
        $article->Couleur=$req->input('couleur');
        $article->Genre=$req->input('genre');
        $article->Condition=$req->input('condition');
        $article->Disponible="oui";
        $vente=new vente();
        $vente->user()->associate(auth('api')->user());



         $req->file('imagename')->move(public_path('storage'),$req->file('imagename')->getClientOriginalName());
        $article->Imagename=$req->file('imagename')->getClientOriginalName();


        if($req->hasFile('imagename1') ){
          $req->file('imagename1')->move(public_path('storage'),$req->file('imagename1')->getClientOriginalName());
        $article->Imagename1=$req->file('imagename1')->getClientOriginalName();
        }
        if($req->hasFile('imagename2')){
          $req->file('imagename2')->move(public_path('storage'),$req->file('imagename2')->getClientOriginalName());
          $article->Imagename2=$req->file('imagename2')->getClientOriginalName();
        }
        if($req->hasFile('imagename3')){
          $req->file('imagename3')->move(public_path('storage'),$req->file('imagename3')->getClientOriginalName());
          $article->Imagename3=$req->file('imagename3')->getClientOriginalName();  
        }
      

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
        }
    
  }
  function manam ($filename)
  {
      
      $path = public_path('storage/' . $filename);
  
  //    if (!File::exists($path)) {
       //   abort(404);
      //}
  
   //   $file = File::get($path);
    //  $type = File::mimeType($path);
  
     // $response = Response($file, 200);
      //  $response->header("Content-Type", $type);
  
     // return response($path);
      $file = File::get($path);
    //  Storage::disk('local')->get('marquee.json');
      $response = Response($file, 200);
      $response->header('Content-Type', 'image/jpeg');
      return $response;
  
    //  return $path;
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
      $invoice = new CheckoutInvoice();

      \Paydunya\Setup::setMasterKey("pApwUxLn-U8Kh-doFk-mWbL-HlBbJKfV1VUC");
      \Paydunya\Setup::setPublicKey("live_public_ZCd8Pgz2hUqonLso2xyaZq9XuDd");
      \Paydunya\Setup::setPrivateKey("live_private_M4aLh2KvunkpSEpHz7bbUOh4Gkp");
      \Paydunya\Setup::setToken("eK1BofuJC0TIKRF2zdtE");
      \Paydunya\Setup::setMode("live");

      \Paydunya\Checkout\Store::setName("SOKO Dakar"); // Seul le nom est requis
      \Paydunya\Checkout\Store::setPhoneNumber("786087832");
      \Paydunya\Checkout\Store::setWebsiteUrl("https://www.sokodakar.com/");

      \Paydunya\Checkout\Store::setCallbackUrl("http://www.sokodakar.com/success");
      $invoice->setReturnUrl("https://www.sokodakar.com/success");
      $invoice->setCancelUrl("https://www.sokodakar.com/finaliser_commande");

      //$com=new commande();
     // $com->adresse=$req->input('adresse');
   //   $com->mode_paiment=$req->input('modpai');
      //$com->statut_commande='en cours';
    //$com->save();
      
      foreach ($req->product as $flight) {
       
       $article = Article::where('id',$flight)->first();
       
       
       $invoice->addItem($article->Titre, 1,$article->Prix , $article->Prix);
      }
$invoice->addTax("Livraison", 1500);
     $invoice->setTotalAmount($req->total+1500);
     if($invoice->create()) {
  
   
         return response($invoice->getInvoiceUrl(), 200)
         ->header('Content-Type', 'application/json');
     }else{
         echo $invoice->response_text;
     }
}

public function pay(Request $req){
 //A insérer dans le fichier du code source qui doit effectuer l'action

// PayDunya rajoutera automatiquement le token de la facture sous forme de QUERYSTRING "token"
// si vous avez configuré un "return_url" ou "cancel_url".
// Récupérez donc le token en pur PHP via $_GET['token']
//$token = $_GET['token'];
//$token = $_GET['token'];
//
$invoice = new CheckoutInvoice();
\Paydunya\Setup::setMasterKey("pApwUxLn-U8Kh-doFk-mWbL-HlBbJKfV1VUC");
\Paydunya\Setup::setPublicKey("live_public_ZCd8Pgz2hUqonLso2xyaZq9XuDd");
\Paydunya\Setup::setPrivateKey("live_private_M4aLh2KvunkpSEpHz7bbUOh4Gkp");
\Paydunya\Setup::setToken("eK1BofuJC0TIKRF2zdtE");
\Paydunya\Setup::setMode("live");
if ($invoice->confirm($req->token)) {
 
  if($invoice->getStatus()=="completed"){
    
    $com=new commande();
    $com->adresse=$req->input('adresse');
    $com->mode_paiment="paydunya";
     $com->statut_commande='en cours';
   $com->save();
    foreach ($req->product as $flight) {
    
     $article = Article::where('id',$flight)->first();
      $article->Disponible="non";
     $article->save();
     $achat = new achat();
     $achat->user()->associate(auth('api')->user());
     $achat->article()->associate($article);
     $achat->commande()->associate($com);
        achat::create($achat);
     $achat->save();
    }
   
  
   }
// Récupérer le statut du paiement
// Le statut du paiement peut être soit completed, pending, cancelled
return  response([$invoice,$com->id], 200)
->header('Content-Type', 'application/json');


}else{

  return  response([$invoice->getStatus(),$invoice->response_text,$invoice->response_code] , 200)
  ->header('Content-Type', 'application/json');
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
      $article = triagearticles::with(['article', 'categorie','sscategorie'])->latest()->get();
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

public function search(Request $req){
  $name=$req->name;

 $post= triagearticles::with(['article', 'categorie','sscategorie'])->whereHas('article', function ($query) use ($name) {
   
    $query->where('Titre', 'like', '%' .$name . '%' ); 
  })->orwhereHas('categorie', function ($query) use ($name) {
    $query->where('name', 'like', '%' .$name . '%' ); 
  })->orwhereHas('sscategorie', function ($query) use ($name) {
    $query->where('name',  'like', '%' .$name . '%' ); 
  })->get();
//  var_dump(count($post));die();
  if(count($post)>0){
    return response($post, 200)
    ->header('Content-Type', 'application/json');
  }else {
    return response('erreur');
  }
 
}
public function allfemme(){
  $article = triagearticles::with(['article', 'categorie','sscategorie'])->whereHas('article', function ($query) {
    $query->where('Genre', 'Femme');
})->get();
  return response($article, 200)
  ->header('Content-Type', 'application/json');
}
  

}
