<?php

	class Config{

	    private $db_host = '127.0.0.1';
	    private $db_user = 'root';
	    private $db_name   = 'babli';
	    private $db_pass = '';
	    private $conn;

		public function __construct() {

		   $this -> conn = new PDO("mysql:host=".$this -> db_host.";dbname=".$this -> db_name, $this -> db_user, $this -> db_pass);

		}

		public function insertData($name,$email,$password){

		   $uid = uniqid('', true);
		   $hash = $this->getHash($password);
		   $encrypted_password = $hash["encrypted"];
		   $salt = $hash["salt"];

		   $sql = 'INSERT INTO users SET uid =:uid,name =:name,
		    email =:email,password =:password,salt =:salt,last_login = NOW()';

		   $query = $this ->conn ->prepare($sql);
		   $query->execute(array('uid' => $uid, ':name' => $name, ':email' => $email,
		     ':password' => $encrypted_password, ':salt' => $salt));

		    if ($query) {

		        return true;

		    } else {

		        return false;

		    }
		}

		public function checkLogin($email, $password) {

		    $sql = 'SELECT * FROM users WHERE email = :email';
		    $query = $this -> conn -> prepare($sql);
		    $query -> execute(array(':email' => $email));
		    $data = $query -> fetchObject();
		    $salt = $data -> salt;
		    $db_encrypted_password = $data -> encrypted_password;

		    if ($this -> verifyHash($password.$salt,$db_encrypted_password) ) {

		        $user["name"] = $data -> name;
		        $user["email"] = $data -> email;
		        $user["uid"] = $data -> uid;
		        return $user;

		    } else {

		        return false;
		    }
		}

		public function changePassword($email, $password){

		    $hash = $this -> getHash($password);
		    $encrypted_password = $hash["encrypted"];
		    $salt = $hash["salt"];

		    $sql = 'UPDATE users SET password = :password, salt = :salt WHERE email = :email';
		    $query = $this -> conn -> prepare($sql);
		    $query -> execute(array(':email' => $email, ':password' => $encrypted_password, ':salt' => $salt));

		    if ($query) {

		        return true;

		    } else {

		        return false;

		    }
		}

		public function checkUserExist($email){

		    $sql = 'SELECT COUNT(*) from users WHERE email =:email';
		    $query = $this -> conn -> prepare($sql);
		    $query -> execute(array('email' => $email));

		    if($query){

		        $row_count = $query -> fetchColumn();

		        if ($row_count == 0){

		            return false;

		        } else {

		            return true;

		        }
		    } else {

		        return false;
		    }
		}

		public function getHash($password) {

		     $salt = sha1(rand());
		     $salt = substr($salt, 0, 10);
		     $encrypted = password_hash($password.$salt, PASSWORD_DEFAULT);
		     $hash = array("salt" => $salt, "encrypted" => $encrypted);

		     return $hash;

		}

		public function verifyHash($password, $hash) {

		    return password_verify ($password, $hash);
		}
	}
?>