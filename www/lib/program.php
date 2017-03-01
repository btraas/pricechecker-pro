<?php


// UserProgram and PublicProgram are quite different.. Just can be displayed and represented similarly.

Abstract Class Program  // {{{
{

	protected $id;
	protected $data = [];
	protected $exercises = [];


	public function __construct($id) // {{{
	{
		$this->id = $id;
		$this->refresh();
	} // }}}

		
	// {{{ Get & set
    public function __get($property)
    {
		if($property == 'id') return $this->id;
		if($property == 'exercises') return $this->exercises;

        if (array_key_exists($property, $this->data))
        {
            return $this->data[$property];
        }
    }

    public function __set($property, $value)
    {
		if($property == 'exercises') $this->exercises = $value;

        if (array_key_exists($property, $this->data))
        {
            $this->data[$property] = getPrintable($value);
        }

        return $this;
    } // }}}

	public final function getExercises($week, $session, $all=false) // {{{
	{
		//logger("getting exercises: ");
		//logger($this->exercises);
		$ex = array();
		if(empty($this->exercises)) return array();
		foreach($this->exercises AS $e) // {{{
		{
			if($all || ($e['week'] == $week && $e['session'] == $session))
				$ex[] = $this->getExercise($e);
		} // }}}

		return $ex;

	} // }}}

	public static abstract function getExercise($e);

	public abstract function refresh();
	public abstract function save();

} //  }}}

Class PublicProgram extends Program // {{{
{
	
	public static function getExercise($e) { return new Exercise($e); }
	public function refresh() // {{{
	{

		$q = "SELECT * FROM programs WHERE program_id = ?";

		// logger("REFRESHING $q, [$this->id]");

		$this->data = @runQ($q, [$this->id])[0];
		if(empty($this->data)) throw new Exception("Unknown program #$this->id");

		$this->exercises = runQ(
					"SELECT pe.*, e.name, e.equipment_id, e.default_weight_kg,
                            e2.name AS alternate_name,
                            e2.equipment_id AS alternate_equipment_id,
                            e3.name AS alternate_2_name,
                            e3.equipment_id AS alternate_2_equipment_id
						
						FROM program_exercises pe
						INNER JOIN exercises e ON pe.exercise_id = e.exercise_id
						LEFT JOIN exercises e2 ON pe.alternate_exercise = e2.exercise_id
						LEFT JOIN exercises e3 ON pe.alternate_exercise_2 = e3.exercise_id

						WHERE pe.program_id = ?", [$this->id]);

		// logger("data:");
		// logger($this->data);

	} // }}}
	public function save() // {{{
	{
		throw new Exception("Unsupported function: ".__CLASS__."::".__FUNCTION__."()");
	} // }}}


} // }}}

Class UserProgram extends Program // {{{
{


	public static function getExercise($e) // {{{
	{
		return new UserProgramExercise($e);
	} // }}}
	
	public function refresh() // {{{
	{

		$userdate = empty($_COOKIE['timezone']) ? date('Y-m-d') : getDateInTimezone(@$_COOKIE['timezone']);

		$q = "SELECT * FROM users WHERE user_id = ?";
		$this->data = @runQ($q, [$this->id])[0];
		
		$q = "SELECT up.*,  
							max.sessions, max.weeks,
							e.name, e.equipment_id, e.default_weight_kg,
							e2.name AS alternate_name, 
							e2.equipment_id AS alternate_equipment_id,
							e3.name AS alternate_2_name,
							e3.equipment_id AS alternate_2_equipment_id
						
							
							,
							previous.weight_kg AS previous_weight_kg, 
							previous.reps AS previous_reps,
							previous.sets AS previous_sets,
							previous.session AS previous_session,
							previous.week AS previous_week

					FROM user_program up 
					
					LEFT JOIN (
					
						SELECT user_id, exercise_id, weight_kg, reps, sets, session, week
                            FROM user_log WHERE date < ? ORDER BY date DESC LIMIT 1
					
					) AS previous ON up.user_id = previous.user_id AND up.exercise_id = previous.exercise_id

					LEFT JOIN ( SELECT user_id, MAX(session) AS sessions, MAX(week) AS weeks 
								FROM user_program GROUP BY user_id )  
						AS max ON up.user_id = max.user_id

					INNER JOIN exercises e ON up.exercise_id = e.exercise_id 
					LEFT JOIN exercises e2 ON up.alternate_exercise = e2.exercise_id
					LEFT JOIN exercises e3 ON up.alternate_exercise_2 = e3.exercise_id
					
					WHERE 

						up.user_id = ?";
	
		$this->exercises = runQ($q, [$userdate, $this->id]);

		$this->data['weeks']		= $this->exercises[0]['weeks'];
		$this->data['sessions']		= $this->exercises[0]['sessions'];

		$this->data['previous_week']        = $this->exercises[0]['previous_week'];
		$this->data['previous_session']     = $this->exercises[0]['previous_session'];




	} // }}}
	public function save() // {{{
	{
		logger("saving userprogram!!");
		runQ("BEGIN", array());


		if(runQ("DELETE FROM user_program WHERE user_id = ?", [$this->id]) === FALSE)
			return false;

		foreach($this->exercises AS $ex) 
		{
			$q = "INSERT INTO user_program (user_id, exercise_id, session, sets, 
					reps_min, reps_max, last_amrap, session_progress_kg,
					week, optional, alternate_exercise, alternate_exercise_2) VALUES
					
					(	?, ?, ?, ?,
						?, ?, ?, ?,
						?, ?, ?, ?)";

			$data = [
						$this->id,
						$ex['exercise_id'], 
						$ex['session'], 
						$ex['sets'],
						$ex['reps_min'],
						$ex['reps_max'],
						$ex['last_amrap'],
						$ex['session_progress_kg'],
						$ex['week'],
						$ex['optional'],
						$ex['alternate_exercise'],
						$ex['alternate_exercise_2']
					];

			if(runQ($q, $data) === FALSE) return false;

		}


		runQ("COMMIT", array());

	} // }}}

	public function getNextWeekSession(&$week, &$session) // {{{
	{
		$week = $this->previous_week;
		$session = $this->previous_session;

		$this->increment($week, $session);

	} // }}}

	private function increment(&$week, &$session) // {{{
	{
		if($session == $this->sessions) $session = 1;
		else $session++;

		if($week == $this->weeks) $week = 1;
		else $week++;

	} // }}}

	public function getNextExercises() // {{{
	{
		$week		= $this->previous_week;
		$session	= $this->previous_session;
		$this->increment($week, $session);

		return $this->getExercises($week, $session);

	} // }}}

} // }}}

Class UserSingleLog extends UserProgram // {{{
{
	private $date;

	public function __construct($user, $date) // {{{
	{
		$this->data = $user->data;
		$this->date = $date;
		parent::__construct($user->user_id);

	//	logger("constructed user log: $this->id, $date ");
	} // }}}

	public function refresh() // {{{
	{
		$date = date('Y-m-d', strtotime($this->date));

        $q = "SELECT up.*, e.name, e.equipment_id, e.default_weight_kg,
                    e2.name AS alternate_name,
                            e2.equipment_id AS alternate_equipment_id,
                            e3.name AS alternate_2_name,
                            e3.equipment_id AS alternate_2_equipment_id

                    FROM user_log up
                    INNER JOIN exercises e ON e.exercise_id = up.exercise_id
                    LEFT JOIN exercises e2 ON up.exercise_id = e2.exercise_id
                    LEFT JOIN exercises e3 ON up.exercise_id = e3.exercise_id

                    WHERE user_id = ? AND date = ?

                    ";

		$data = [$this->id, $date];
		$this->exercises = runQ($q, $data);

	//	logger($q . ", " . print_r($data, true));
	//	logger($this->exercises);

	} // }}}

	public function save() {}

} // }}}


Class Exercise // {{{
{
	public $data;
	
	public function __construct($array)
	{
		$this->data = $array;
		$this->_def('reps', '');
		$this->_def('id', $array['exercise_id']);
		$this->_def('sets_goal', $array['sets']);
	}

	private function _def($key, $val) // {{{
	{
		if(empty($this->data[$key])) $this->data[$key] = $val;
	} // }}}

	public function __get($property) // {{{
    {
		return $data[$property];
	} // }}}

	public function getDefaultWeight($unit_id=1) // {{{
	{
		return (new Weight($this->data['default_weight_kg']))->convert($unit_id);
	} // }}}

} // }}}

// Not very pretty, but gets the job done.
Class UserProgramExercise extends Exercise // {{{
{
	private $user;

	public function __construct($array) 
	{
		parent::__construct($array);
		if(empty($array['user_id'])) throw new Exception("user_id not provided!");
		$this->user = new User($array['user_id']);
	}

	public function __get($property) // {{{
	{
		if($property == 'weight') return $this->getWeight();
		return $this->data[$property];

	} // }}}


	private function getWeight() // {{{
	{
		require_once('lib/weight.php');
		if(empty($this->data['previous_weight_kg'])) return $this->getDefaultWeight($this->user->weight_unit_id);


		$weight = new Weight($this->data['previous_weight_kg']);
		return $weight->convert($this->user->weight_unit_id);

	} // }}}


} // }}}


















?>
