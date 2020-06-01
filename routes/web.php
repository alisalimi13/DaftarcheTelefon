<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('daftarchetelefon');
});

Route::post('/request', 'DaftarcheTelefon@requestProcess');

Route::get('/fill', 'DaftarcheTelefon@fill');

Route::get('/photos/contacts/{id}/img.jpg', 'DaftarcheTelefon@getContactPhoto');
Route::get('/photos/groups/{id}/img.jpg', 'DaftarcheTelefon@getGroupPhoto');

//Amin
Route::get('/contacts.csv', 'DaftarcheTelefon@exportCSV');
Route::get('/contacts.json', 'DaftarcheTelefon@exportJSON');