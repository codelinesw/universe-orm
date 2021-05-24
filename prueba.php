<?php 


// Include router class
include('Config/Route.php');

// Add base route (startpage)
Route::add('/',function(){
    echo 'Welcome :-)';
});

// Simple test route that simulates static html file
Route::add('/test.html',function(){
    echo 'Hello from test.html';
});

// Post route example
Route::add('/contact-form',function(){
    echo '<form method="post"><input type="text" name="test" /><input type="submit" value="send" /></form>';
},'get');

// Post route example
Route::add('/contact-form',function(){
    echo 'Hey! The form has been sent:<br/>';
    print_r($_POST);
},'post');

// Accept only numbers as parameter. Other characters will result in a 404 error
Route::add('/foo/([0-9]*)/bar',function($var1){
    echo $var1.' is a great number!';
});

Route::run('/');

// function validateUser($username){
//     $newusername = explode('$',$username);
//     array_shift($newusername);
//     $user = "";
//     for($i = 0; $i < sizeof($newusername); $i++){
//         $user .= substr($newusername[$i],0, 1);
//     }
//     return $user;
// }

// if(!isset($_SERVER['PHP_AUTH_USER'])){
//     header("WWW-Authenticate: Basic realm=\"Private Area\"");
//     header("HTTP/1.0 401 Unauthorized");
//     header("Content-type: application/json");
//     echo json_encode(array("message" => "Authorization has been denied for this request"));
// }else{
//     if((validateUser($_SERVER["PHP_AUTH_USER"]) == "wondershopUser" && ($_SERVER["PHP_AUTH_PW"] == "123"))){
//         echo "The request is posible ";
//     }else{
//         header("WWW-Authenticate: Basic realm=\"Private Area\"");
//         header("HTTP/1.0 401 Unauthorized");
//         header("Content-type: application/json");        
//         echo json_encode(array("message" => "Authentication invalid"));
//     }
// }

// require_once('Users.php');

// $Users = new Users();
// $Users->set('name','Jhon Denver');
// $Users->set('lastName','Murillo Mendez');
// $Users->add();


?>