<?php
// namespace core;

// use App\Autoloader; inutile car tu charges manuellement avec un require_once plus bas

define('DS', DIRECTORY_SEPARATOR); // meilleur portabilité sur les différents systeme.
define('ROOT', substr(dirname(__FILE__), 0, -4)); // pour se simplifier la vie

session_start();

require_once 'autoloader.php';
Autoloader::register();

var_dump(ROOT);




		// try{
			// $pdo = new PDO('sqlite:'.dirname(__FILE__).'/database.sqlite');
			// $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
		// } catch(Exception $e) {
			// echo "Impossible d'accéder à la base de données SQLite : ".$e->getMessage();
			// die();
		// }
		
		// print_r ($pdo);

// use Tester\Test; // cheimin vers notre classe Test
// $test = new Test();





$contrib = new m\contributor(15);
// $contrib = m\contributor::fetch(0);

print_r ($contrib);