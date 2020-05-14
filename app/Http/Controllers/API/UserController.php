<?php
namespace App\Http\Controllers\API;

use App\Article;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Role;
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
class UserController extends Controller {

public $successStatus = 200;
/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(){ 
        if(Auth::attempt(['telephone' => request('telephone'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $token =  $user->createToken('MyApp')->accessToken; 
            return response()->json( $token); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'prenom' => 'required', 
            'nom' => 'required', 
            'telephone' => 'required', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $input['role']="user";
        $user = User::create($input); 
        
        $success['token'] =  $user->createToken('MyApp')->accessToken; 
        $success['nom'] =  $user->nom;
        return response()->json(['success'=>$success], $this->successStatus); 

    }
    public function adminregister(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'prenom' => 'required', 
            'nom' => 'required', 
            'telephone' => 'required', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $input['role']="admin";
        $user = User::create($input); 
        
        $success['token'] =  $user->createToken('MyApp')->accessToken; 
        $success['nom'] =  $user->nom;
        return response()->json(['success'=>$success], $this->successStatus); 

    }
/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this->successStatus); 
    } 
    public function liste(){
        $article= User::where('role','=', 'admin')->get();
        return response()->json($article); 

    }
  /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
 public function updateadmin(Request $req){
   
    $user =User::find($req->input('id'));  
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
    public function updatearticle(Request $req){
   
        $article =Article::find($req->input('id'));  
            if($req->input('titre')){
                $article->Titre=$req->input('titre');}
                if($req->input('prix')){
                    $article->Prix=$req->input('prix');}
                if($req->input('taille')){
                    $article->Taille=$req->input('taille');
                }
                if($req->input('description')){
                    $article->Description=$req->input('description');
                  }
                  if($req->input('couleur')){
                    $article->Couleur=$req->input('couleur');
                  }
             
                  if($req->input('condition')){
                    $article->Condition=$req->input('condition');
                  }
                  if($req->input('genre')){
                    $article->Genre=$req->input('genre');
                  }
                  $article->save();
                  return response("succés", 200)
                  ->header('Content-Type', 'application/json');
        }
}
 