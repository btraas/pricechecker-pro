<?php

Class User
{

	public $data;

	public function __construct($input) // {{{
	{
		$field = is_numeric($input) ? 'user_id' : 'email';
		$input = is_numeric($input) ? getNumeric($input) : getEmail($input);

		//logger("new User($input)");
		$q = "SELECT * FROM users where $field = ?";
		
		//logger($q);

		$this->data = runQ($q, array($input))[0];
		//logger($this->data);
	 } // }}}


	public function __get($key) // {{{
	{
		if($key == 'data') return $this->data;

		else return $this->data[$key];

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
		logger($r);
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

    	return runQ($q, $data);

	} // }}}


	public function log($date, $week, $session, $exercises) // {{{
	{

		$date = date('Y-m-d', (strtotime($date)));

		runQ("BEGIN");

		$q = "DELETE FROM user_log WHERE user_id = ? AND date = ?";
		if(runQ($q, [$this->user_id, $date]) === FALSE)
			MySQL::fail("Unable to overwrite exiting exercises for $this->name on $date");


		foreach($exercises AS $ex) {


			if(is_object($ex))
			{
				/*
				$e = [];
				$e['sets']		= $ex->sets;
				$e['id']		= $ex->id;
				$e['reps_min']	= $ex->reps_min;
				$e['reps_max']  = $ex->reps_max;
				$e['sets_goal'] = $ex->sets_goal;
				$e['']
				throw new Exception("not yet implemented");
				*/

				$e = $ex->data;
				print_r($e);
			}
			else $e = $ex;


			if(empty($e['sets'])) $e['sets'] = array();

			$num_sets = count($e['sets']);


			$reps = implode(',', $e['sets']);

			$q = "INSERT INTO user_log 
					(   user_id, exercise_id, date, sets, reps, weight_kg,
                        reps_min, reps_max, sets_goal, last_amrap,
						session, week) VALUES
                    (   ?,?,?,?,?,?,
						?,?,?,?,
						?,?)";
			$data =     [$this->user_id, $e['id'], $date, $num_sets, $reps, 0,
                    $e['reps_min'], $e['reps_max'], $e['sets_goal'], $e['last_amrap'],
					$session, $week];



			if(runQ($q, $data) === FALSE) MySQL::fail("Unable to add exercise $e[name]..");
		}

    runQ("COMMIT");

	} // }}}

	public function getLog($date, $week=null, $session=null) // {{{
	{
		// null null true indicates all exercises from set
		$ex = (new UserSingleLog($this, $date))->getExercises(null, null, true);
		if(!empty($ex)) return $ex;


		if($week != null || $session != null) throw new Exception("use of old getExercises...");


		return [];

	} // }}}


	public function getProgram() // {{{
	{
		return new UserProgram($this->user_id);
	} // }}}

	public function getExercises($week, $session) // {{{
	{


		$ex = $this->getProgram()->getExercises($week, $session);
		if(!empty($ex)) return $ex;

		return [];

		//header("location:/workout/new?date=$date");

	} // }}}

	public function newLog($date, $week=null, $session=null) // {{{
	{
		if(empty($this->getLog($date))) 
		{

			$program = $this->getProgram();

			if($week == null || $session == null)
			{
				$program->getNextWeekSession($week, $session);
			}

			$this->log($date, $week, $session, $this->getExercises($week, $session));
		}
	} // }}}


	public function saveProgram() // {{{
	{
		throw new Exception("not yet implemented!");
	} // }}}

} 

?>
