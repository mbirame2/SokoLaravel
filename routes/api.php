<?php

//use Symfony\Component\Routing\Route;

use Illuminate\Support\Facades\Request;
//use Illuminate\Routing\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::group(['middleware' => 'auth:api'], function(){ 
Route::post('details', 'API\UserController@details');
});
Route::post('ventearticle','SokoController@ventearticle');
Route::post('commande','SokoController@commande');

Route::get('onevente/{id}','SokoController@onevente');
Route::get('allhomme','SokoController@allhomme');
Route::get('allfemme','SokoController@allfemme');
Route::get('allcat','SokoController@allcat');
Route::get('allsscat','SokoController@allsscat');

Route::get('allachat','SokoController@allachat');
Route::get('allnew','SokoController@allnew');
Route::get('allvente','SokoController@allvente');
Route::get('getuser','SokoController@getUser');
Route::get('categorie/{name}','SokoController@triagecategorie');
Route::get('sscategorie/{name}','SokoController@triagesscategorie');