<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	$page = new Page(); //inicia o construct, por padrao vai rodar o header

	$page->setTpl("index"); //carrega o conteudo na pasta view (body)

});
		//roda o destruct -> vai carregar o footer

$app->get("/sistema", function() {

	User::verifyLogin();
    
	$page = new PageAdmin(); 

	$page->setTpl("index"); 

});

$app->get("/sistema/login", function() {
    
	$page = new PageAdmin([
			"header"=>false,
			"footer"=>false

	]); 

	$page->setTpl("login"); 

});

$app->post("/sistema/login", function() {
    
	
	User::login($_POST["login"], $_POST["password"]);
	
	header("Location: /sistema");
	exit;


});

$app->get("/sistema/logout", function() {
    
	
	User::logout();
	
	header("Location: /sistema/login");
	exit;

});	

$app->get("/sistema/users", function() {

    User::verifyLogin();
	
	$users = User::listAll();

	$page = new PageAdmin();

	$page->setTpl("users", array (
			"users"=>$users
	));


});	

$app->get("/sistema/users/create", function() {

    User::verifyLogin();
	
	$page = new PageAdmin();

	$page->setTpl("users-create");


});	

$app->get("/sistema/users/:iduser/delete", function($iduser) {

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location:/sistema/users");
	exit;
	



});	


$app->get("/sistema/users/:iduser", function($iduser) {

    User::verifyLogin();

    $user = new User();

    $user->get((int)$iduser);
	
	$page = new PageAdmin();

	$page->setTpl("users-update", array ( 
		"user"=>$user->getValues()
	));


});	

$app->post("/sistema/users/create", function() {

	User::verifyLogin();

	$user = new User ();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->setData($_POST);

	$user->save();

	header("Location: /sistema/users");
	exit;

});	


$app->post("/sistema/users/:iduser", function($iduser) {

	User::verifyLogin();

	$user = new User ();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->get((int)$iduser);
 	
 	$user->setData($_POST);

 	$user->update(); 


	header("Location: /sistema/users");
	exit;


});	





$app->run();  //

 ?>