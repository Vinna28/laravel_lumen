<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('api/ads','AdsController@index');
 
$router->get('api/ads/{id}','AdsController@getAds');

$router->get('api/ads/s/pending','AdsController@getPendingAds');
 
$router->post('api/ads','AdsController@saveAds');
 
$router->put('api/ads/{id}','AdsController@updateAds');
 
$router->delete('api/ads/{id}','AdsController@deleteAds');

//Pakde

//Pakpo
$router->put('api/ads/approve/{id}','AdsController@approveAds');

$router->put('api/ads/vc/{id}', 'AdsController@updateVcAds');