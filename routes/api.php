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

Route::post('login',array('middleware' => 'cors', 'uses' => 'API\UserController@login'));
Route::post('register', array('middleware' => 'cors', 'uses' =>'API\UserController@register'));
Route::post('updateadmin', array('middleware' => 'cors', 'uses' =>'API\UserController@updateadmin'));

Route::post('search', array('middleware' => 'cors', 'uses' =>'SokoController@search'));

Route::group(['middleware' => 'auth:api'], function(){ 
Route::post('details', 'API\UserController@details');
});
Route::post('ventearticle',array('middleware' => 'cors', 'uses' =>'SokoController@ventearticle'));
Route::post('commande',array('middleware' => 'cors', 'uses' =>'SokoController@commande'));
Route::post('update',array('middleware' => 'cors', 'uses' =>'SokoController@update'));
Route::post('pay',array('middleware' => 'cors', 'uses' =>'SokoController@pay'));
Route::post('adminregister',array('middleware' => 'cors', 'uses' =>'API\UserController@adminregister'));

Route::get('onevente/{id}',array('middleware' => 'cors', 'uses' =>'SokoController@onevente'));
Route::get('remove/{id}',array('middleware' => 'cors', 'uses' =>'SokoController@remove'));

Route::get('allhomme',array('middleware' => 'cors', 'uses' =>'SokoController@allhomme'));
Route::get('adminliste',array('middleware' => 'cors', 'uses' =>'API\UserController@liste'));

Route::get('allfemme',array('middleware' => 'cors', 'uses' =>'SokoController@allfemme'));
Route::get('allcat',array('middleware' => 'cors', 'uses' =>'SokoController@allcat'));
Route::get('allsscat',array('middleware' => 'cors', 'uses' =>'SokoController@allsscat'));
Route::get('images/{filename}',array('middleware' => 'cors', 'uses' =>'SokoController@manam'));

Route::get('allachatone',array('middleware' => 'cors', 'uses' =>'SokoController@allachatone'));
Route::get('allventeone',array('middleware' => 'cors', 'uses' =>'SokoController@allventeone'));
Route::get('alluser',array('middleware' => 'cors', 'uses' =>'SokoController@liste'));

Route::get('allachat',array('middleware' => 'cors', 'uses' =>'SokoController@allachat'));
Route::get('allnew',array('middleware' => 'cors', 'uses' => 'SokoController@allnew'));
Route::get('allvente',array('middleware' => 'cors', 'uses' =>'SokoController@allvente'));
Route::get('getuser',array('middleware' => 'cors', 'uses' =>'SokoController@getUser'));
Route::get('categorie/{name}',array('middleware' => 'cors', 'uses' =>'SokoController@triagecategorie'));
Route::get('sscategorie/{name}',array('middleware' => 'cors', 'uses' =>'SokoController@triagesscategorie'));