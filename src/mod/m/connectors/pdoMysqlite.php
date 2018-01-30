<?php
namespace m\connectors;

use \pdo;

class pdoMysqlite
{
	private $id;
	public $key;
	
	public function __construct()
	{
		try{
			$dsn = 'sqlite:'.dirname(__FILE__).'/database.sqlite';
			$pdo = new \PDO($dsn);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
		} catch(\Exception $e) {
			echo "Impossible d'accéder à la base de données SQLite : ".$e->getMessage();
			die();
		}
	}
}