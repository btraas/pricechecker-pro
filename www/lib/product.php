<?php

Class Product { // {{{

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

        $field = 'upc';
        $input = getNumeric($input);

        $q = "SELECT * FROM products where $field = ?";
        $this->data = runQ($q, array($input))[0];

    } // }}}


	public function create($newData)
	{
		if(!empty($data)) return;
		
	 	$data = array();
        $data[] = getPrintable($newData['title']);
        $data[] = getNumeric($newData['upc']);
        $data[] = getPrintable($newData['description']);
        $data[] = getNumeric($newData['brand_id']);


        $q = "INSERT INTO products(title, upc, description, brand_id) VALUES(?, ?, ?, ?)";
        $r = runQ($q, $data);

        $this->load($newData['upc']);
        return $r;


		
	}


} // }}}

?>
