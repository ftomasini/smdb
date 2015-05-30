<?php

class auth {
	
	public static function get_user_id($username){
		$res = env::$db->query_params("SELECT id FROM users.t_user WHERE username=$1", array($username));
		if (!$res) return false;
		return env::$db->fetch_1();
	}
	
}

?>