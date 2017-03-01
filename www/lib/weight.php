<?php

Class Weight 
{
	private static $units = array();

	public $weight_kg;
	
	public static function init() // {{{
	{
		$units_q = runQ("SELECT * FROM weight_units ORDER BY weight_unit_id", []);
		foreach($units_q AS $u)
		{
		    self::$units[$u['weight_unit_id']] = $u;
		}

	} // }}}

	public function __construct($weight_kg) // {{{
	{
		$this->weight_kg = $weight_kg;
	} // }}}
	public function convert($unit_id, $raw=false, $round=2.5) // {{{
	{
		if(empty($unit_id)) throw new Exception("Empty unit_id!");

		$unit = $raw ? "" : self::$units[$unit_id]['identifier'];

		/*
		logger("units:");
		logger(self::$units);

		logger("unit $unit_id: ");
		logger(self::$units[$unit_id]);
		logger("weight kg: $this->weight_kg to $unit ");
		*/

		// testing 
		/*
		logger(self::roundTo(11, 2.5));
		logger(self::roundTo(12, 2.5));
		logger(self::roundTo(13, 2.5));
		logger(self::roundTo(14, 2.5));
		*/

		return self::roundTo($this->weight_kg * self::$units[$unit_id]['amount_per_kg'], $round) . $unit;

	} // }}}


	private static function roundTo($input, $round) // {{{
	{
		return round($input / $round) * $round;
	} // }}}

}


Weight::init();

?>
