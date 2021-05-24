<?php 

require_once('Config/Route.php');

// Include router class
use Config\Route;



// Add base route (startpage)
Route::add('/Users/list','post');
Route::add('/Users/saludar','post');

Route::add(
    '/Stores/list/{number}',
    'post',
    'application/json'
);


Route::add(
    '/Stores/add',
    'post',
    'application/json'
);

Route::add(
    '/Stores/update',
    'post',
    'application/json'
);

Route::add(
    '/Stores/delete',
    'post',
    'application/json'
);

Route::load();


?>