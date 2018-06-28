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

$app->get('/sistema', function() {

	User::verifyLogin();
    
	$page = new PageAdmin(); 

	$page->setTpl("index"); 

});

$app->get('/sistema/login', function() {
    
	$page = new PageAdmin([
			"header"=>false,
			"footer"=>false

	]); 

	$page->setTpl("login"); 

});

$app->post('/sistema/login', function() {
    
	
	User::login($_POST["login"], $_POST["password"]);
	
	header("Location: /sistema");
	exit;


});

$app->get('/sistema/logout', function() {
    
	
	User::logout();
	
	header("Location: /sistema/login");
	exit;

});	

$app->run();  //

 ?>