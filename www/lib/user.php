<?php

require_once('lib/common/user.php');
require_once('lib/product.php');

Class User extends AbstractUser {

	public function trackUPC($upc) {
	
		return runQ("INSERT INTO user_track (user_id, upc) VALUES(?, ?)", 
			array($this->user_id, $upc));
	
	}

}
