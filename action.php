<?php
session_start();
const ACTION_LOGIN = 1,
	  ACTION_REGISTER = 2,
	  ACTION_GETPOSTS = 3,
	  ACTION_ADDPOST = 4,
	  ACTION_LOGOUT = 5
	  ;

// do some checks
if(!isset($_POST) || !isset($_POST['action'])) die();
$action = $_POST['action'];

if($action == ACTION_LOGIN){ // login
	if(!isset($_POST['username']) || !isset($_POST['password']))
		die();
	$username = $_POST['username'];
	$password = $_POST['password'];
}
else if($action == ACTION_REGISTER){ // register
	if(!isset($_POST['email'])
	|| !isset($_POST['username'])
	|| !isset($_POST['password'])
	)
		die();
	$email = $_POST['email'];
	$username = $_POST['username'];
	$password = $_POST['password'];
}
else if($action == ACTION_GETPOSTS){
	if(!isset($_SESSION) || !isset($_SESSION['userId']))
		die('-400');
}
else if($action == ACTION_ADDPOST){
	if(!isset($_SESSION) || !isset($_SESSION['userId']))
		die('-400');
	if(!isset($_POST['title']) || !isset($_POST['text']))
		die();
	
	$note_title = $_POST['title'];
	$note_text = $_POST['text'];
}
else if($action == ACTION_LOGOUT){
	session_destroy();
	die('1');
}
else
	die();

require_once('./users.php');

$host = 'localhost';
$db   = 'homework';
$dbuser = 'root';
$dbpass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$dbopts = [
	PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $dbuser, $dbpass, $dbopts);
Users::init($pdo);

if($action == ACTION_LOGIN){
	$user = Users::login($username, $password);
	if($user){
		if(!isset($_SESSION))
			session_start();
		$_SESSION['userId'] = $user['id'];
		$_SESSION['email'] = $user['email'];
		$_SESSION['username'] = $user['username'];
		die('1');
	}
	die('-1');
}
else if($action == ACTION_REGISTER){
	die((string)Users::register(
		$email,
		$username,
		$password
	));
}
else if($action == ACTION_GETPOSTS){
	die(json_encode(Users::get_user_notes($_SESSION['userId'])));
}
else if($action == ACTION_ADDPOST){
	//echo (strlen($note_title) > 1);
	if(strlen($note_title) < 1)
		die('-1');
	if(strlen($note_text) < 1)
		die('-2');
	$res = Users::add_user_note(
		$_SESSION['userId'],
		$note_title,
		$note_text
	);
	if($res)
		die('1');
	die('-1');
}