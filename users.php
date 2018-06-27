<?php
class Users{
	private static $pdo;
	private static $getUserQuery = "select * from users where username = ?";
	private static $addUserQuery = "insert into users(email, username, password) values(?,?,?)";
	private static $getUserNotesQuery = "select * from notes where userId=? order by id desc";
	private static $addUserNoteQuery = "insert into notes(userId, title, text) values(?,?,?)";
	private static function execute($query, $args){
		$stmt = self::$pdo->prepare($query);
		$stmt->execute($args);
		return $stmt;
	}
	public static function init($pdo){
		self::$pdo = $pdo;
	}
	public static function get($username){
		return self::execute(self::$getUserQuery, [$username])->fetch();
	}
	public static function get_user_notes($userId){
		return self::execute(self::$getUserNotesQuery, [$userId])->fetchAll();
	}
	public static function add($email, $username, $password){
		try{
			self::execute(self::$addUserQuery, [$email, $username, $password]);
			return true;
		}
		catch(Exception $e){ 
			return false; 
		}
	}
	public static function add_user_note($userId, $title, $text){
		try{
			self::execute(self::$addUserNoteQuery, [$userId, $title, $text]);
			return true;
		}
		catch(Exception $ex){
			return false;
		}
	}
	public static function login(
		$username, 
		$password
	){
		$user = Users::get($username);
		if($user){
			if(password_verify($password, $user['password']))
				return $user;
		}
		return false;
	}
	public static function register($email, $username, $password){
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			return -1;
		if(!preg_match('/^([a-z]+[0-9]*){6,}$/', $username))
			return -2;
		if(!preg_match('/^[a-zA-z0-9#\$\^+=!*()@%&]{8,}$/', $password))
			return -3;
		$res = Users::add(
			$email,
			$username, 
			password_hash($password, PASSWORD_DEFAULT)
		);
		if(!$res)
			return -4;
		return 1;
	}
	
}
