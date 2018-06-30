<?php

namespace Hcode\Model;

use \Hcode\DB\Sql; 
use \Hcode\Model; 

class User extends Model  {

	const SESSION = "User";

	public static function login ($login, $password)

{

	$sql =  new Sql();

	$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
		":LOGIN"=>$login
	));


	if (count($results) === 0)
	{

		throw new \Exception("Usuário inexisteste ou senha inválida.");
	}

	$data = $results[0];

	if (password_verify($password, $data["despassword"]) === true)
	{

		$user = new User ();
		$user->setData($data);


		$_SESSION[User::SESSION] = $user->getValues();

		return $user;

		
	} else {

		throw new \Exception("Usuario inexisteste ou senha inválida.");

	}

}


	public static function verifyLogin($inadmin = true) // verifica se o usuario ta logado no /sistema
{

		if (
			!isset($_SESSION[User::SESSION]) //se nao for definida 
			||
			!$_SESSION[User::SESSION] // se a sessao for falsa 
			||
			!(int)$_SESSION[User::SESSION]['iduser'] > 0  // verifica o id do usuario dentro da Sessao.
			||
			(bool)$_SESSION[User::SESSION]['inadmin'] !== $inadmin // verifica se o usuario realmente esta logado no /sistema


		) {

			header("Location: /sistema/login");
			exit;

		}

	}

	public static function logout()
	{

		$_SESSION[User::SESSION] = NULL ; 

	}


	public static function listAll()
	{

		$sql = new Sql();
		return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");

	}

		public function save()
	{

		$sql = new Sql();

		$results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", 
			array(
				":desperson"=>$this->getdesperson(), 
				":deslogin"=>$this->getdeslogin(),
				":despassword"=>$this->getdespassword(),
				":desemail"=>$this->getdesemail(),
				":nrphone"=>$this->getnrphone(),
				":inadmin"=>$this->getinadmin()

		));


		$this->setData($results[0]);

	}

	public function get($iduser)

{

	$sql = new Sql();
	$results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(
		":iduser"=>$iduser
	));

$this->setData($results[0]);


}

public function update()

{


		$sql = new Sql();

		$results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", 
			array(
				":iduser"=>$this->getiduser(),
				":desperson"=>$this->getdesperson(), 
				":deslogin"=>$this->getdeslogin(),
				":despassword"=>$this->getdespassword(),
				":desemail"=>$this->getdesemail(),
				":nrphone"=>$this->getnrphone(),
				":inadmin"=>$this->getinadmin()

		));


		$this->setData($results[0]);

}


public function delete()
{

	$sql = new Sql();

	$sql->query("CALL sp_users_delete(:iduser)", array(
		":iduser"=>$this->getiduser()
	));
}



}


?>