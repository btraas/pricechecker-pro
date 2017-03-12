<?php


// A simple user Class

Abstract Class AbstractUser // {{{
{

	public $data;

	public function __construct($input) // {{{
	{
		$this->load($input);

	} // }}}


	public function __get($key) // {{{
	{
		if($key == 'data') return $this->data;

		else return $this->data[$key];

	} // }}}

	private function load($input) // {{{
	{

		$field = is_numeric($input) ? 'user_id' : 'email';
		$input = is_numeric($input) ? getNumeric($input) : getEmail($input);

		$q = "SELECT * FROM users where $field = ?";
		$this->data = runQ($q, array($input))[0];

	} // }}}

	public function create($userdata) // {{{
	{
		$data = array();
    	$data[] = getPrintable($userdata['email']);
    	$data[] = getPrintable($userdata['name']);
    	$data[] = getPrintable($userdata['picture']);
    	$data[] = getPrintable($userdata['locale']);


    	$q = "INSERT INTO users(email, name, picture, locale) VALUES(?, ?, ?, ?)";
    	$r = runQ($q, $data);

		$this->load($userdata['email']);
		return $r;

	} // }}}
	public function update($userdata) // {{{
	{
		$data = array();
    	$data[] = getPrintable($userdata['name']);
    	$data[] = getPrintable($userdata['picture']);
    	$data[] = getPrintable($userdata['locale']);
    	$data[] = getPrintable($userdata['email']);

    	$q = "UPDATE users SET  name = ?,
                            picture = ?,
                            locale = ?,
                            modified_timestamp = CURRENT_TIMESTAMP,
                            last_accessed = CURRENT_TIMESTAMP
                        WHERE email = ?";

    	$r = runQ($q, $data);

		$this->load($userdata['email']);
		return $r;

	} // }}}



} // }}}


?>
