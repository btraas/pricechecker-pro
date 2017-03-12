<?php

Class Brand { // {{{

	public $data;

	public function __construct($input) {
		$this->load($input);
	}

	public function __get($key) // {{{
    {
        if($key == 'data') return $this->data;

        else return $this->data[$key];

    } // }}}


	private function load($input) // {{{
    {

		$field = is_numeric($input) ? 'brand_id' : 'name';
		$input = is_numeric($input) ? getNumeric($input) : getPrintable($input);



        $q = "SELECT * FROM brands where $field = ?";
        $this->data = @runQ($q, array($input))[0];

    } // }}}


	public function create($newData)
	{
		if(!empty($data)) return;
		
	 	$data = array();
        $data[] = getPrintable($newData['logo_url']);
        $data[] = getPrintable($newData['name']);


        $q = "INSERT INTO brands(logo_url, name) VALUES(?, ?)";
        $r = runQ($q, $data);

        $this->load($newData['name']);
        return $r;


		
	}


} // }}}

?>
