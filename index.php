<?php 

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	$page = new Page(); //inicia o construct, por padrao vai rodar o header

	$page->setTpl("index"); //carrega o conteudo na pasta view (body)

});
		//roda o destruct -> vai carregar o footer

$app->get('/sistema', function() {
    
	$page = new PageAdmin(); //inicia o construct, por padrao vai rodar o header

	$page->setTpl("index"); //carrega o conteudo na pasta view (body)

});


$app->run();  //

 ?>