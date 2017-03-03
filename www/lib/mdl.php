<?php

/*

	Author	Brayden Traas

	PHP OO UI library based on MDL (https://getmdl.io).

	Can be used to create consistent MDL colors, buttons, themes etc.


	Element usage example: 
	
	// Create Element: 
	$element = new MDL\NumberInput('dom-id-here');
	
	// Set HTML attributes (overrides defaults)
	$element->value = '1234';
	$element->style = 'width: 100%;';
	$element->onkeyup = "if (event.keyCode == 13) alert('enter')";

	// Generate HTML
	echo $element->html;


	// May be some specific values 
	$btn = new MDL\Button('go');
	$btn->text = "GO";			// Sets the <button></button> element's inner HTML


*/

Namespace MDL;

$COLORS = array();
foreach(file('lib/mdl-colors.csv') AS $str)
{
	$color = str_getcsv($str);
	$COLORS[$color[0]] = $color[1];
}


Class Color // {{{
{
	public $base;

	public $name;
	public $value;

	public function __construct($name) // {{{
	{
		Global $COLORS;

		$name = strtolower($name);

		$this->base = substr( $name, 0, strrpos( $name, '-' ) );
		if(empty(getNumeric($name))) $this->base = $name; // if no numbers, base = name


		$this->name = $name;
		$this->value = $COLORS[$name];

	} // }}}


	public function hex() // {{{
	{
		return $this->value;
	} // }}}

	public function shade($int) // {{{
	{
		return new Color($this->base."-$int");
	} // }}}

} // }}}
Class Theme  // {{{
{
	public $color_main;
	public $color_accent;

	public function __construct($main, $accent) // {{{
	{
		$this->color_main	= $main;
		$this->color_accent	= $accent;

	} // }}}

	public function stylesheet() // {{{
	{
		$pre  = "https://code.getmdl.io/1.2.1/material.";
		$post = ".min.css";

		return $pre . str_replace("-", "_", $this->color_main->base) ."-". str_replace("-", "_", $this->color_accent->base) . $post;

	} // }}}

} // }}}

Abstract Class Element  // {{{
{
	/* public $id; */
	private $class;

	protected $attr = [];
	/* private $html; */

	public function __construct($id) // {{{
    {
        $this->attr['id'] = $id;

    } // }}}


	public function __get($name) {
		
		//if($name=='id')		return $this->attr['id'];
		if($name=='html')	return $this->generate();
		if($name=='class')	return $this->getClass();

		return $this->attr[$name];
	}

	public function __set($name, $val) {
		$this->attr[$name] = $val;
	}

	public function addClass($name) {
		$this->class .= " $name";
	}

	// Override this to force permanent class names on objects
	protected function getClass() // {{{
	{
		return $this->class;
	} // }}}

	final protected function attr() // {{{
	{
		$attr = "";
		foreach($this->attr AS $key => $val) {
			$attr .= "$key=\"$val\" ";
		}
		return $attr;
	} // }}}

	public static function JS($code) // {{{
	{
		return <<<EOF
<script>
$code			
</script>
EOF;
	} // }}}

	abstract protected function generate();


} // }}}
Class Button extends Element // {{{
{
	public $icon;
	public $text;

	public $disabled = false;

	public function __construct($id) // {{{
	{
		parent::__construct($id);

		// default but can be overriden
		$this->addClass("mdl-button--raised");

	} //}}}

	// have permanent classes that the user can't remove via $button->class...
	protected function getClass() // {{{
	{
		return "mdl-button mdl-js-button " . parent::getClass();
	} // }}}

	protected function generate() // {{{
	{
		$disabled = $this->disabled ? "disabled" : "";
		$class = $this->getClass();

		return <<<EOF
<button class='$class' {$this->attr()} $disabled>
	<i class='material-icons'>$this->icon</i>
	$this->text
</button>
EOF;

	} // }}}


} // }}} 


Class FabButton extends Button // {{{
{
	public function __construct($id) // {{{
	{
		parent::__construct($id);
		$this->addClass("mdl-button--accent mdl-button--raised");
	} // }}}

	protected function getClass() // {{{
    {
		return "mdl-button--fab " . parent::getClass();
	} // }}}

} // }}}

Abstract Class Input extends Element // {{{
{
	//protected $pattern;
	public $input_error;
	
	public $before;
	public $after;

	public function __construct($id) // {{{
	{
		parent::__construct($id);
		$this->attr['placeholder'] = "";
		$this->type = 'text';

	} // }}}

	protected function getClass() // {{{
	{
		return parent::getClass() . " mdl-textfield__input";
	} // }}}

	protected function generate() 
	{
		$class = $this->getClass();

		return <<<EOF

<table style='display: inline-block'><tr>
	<td>$this->before</td>
	<td><div class='mdl-textfield mdl-js-textfield' style='$this->style'>
		<input class='$class' 
			{$this->attr()}
			>		

		<label class='mdl-textfield__label'
			for='$this->id'>$this->placeholder</label>
		<span class='mdl-textfield__error'>$this->input_error</span>

	</div></td>
	<td>$this->after</td>
</tr></table>

EOF;
	}

} //  }}}
Class NumberInput extends Input // {{{
{

	public function __construct($id)
	{
		parent::__construct($id);
		$this->type					= 'number';
		$this->pattern				= "-?[0-9]*(\.[0-9]+)?";
		//$this->attrplaceholder		= "Number...";
		$this->input_error			= "Not a number!";
		$this->style				= "width: 50px";
	} 
} // }}}

Class Snackbar extends Element // {{{
{

	protected function generate() // {{{
	{
		return <<<EOF
<div id="$this->id" class="$this->class">
	<div class="mdl-snackbar__text"></div>
	<button class="mdl-snackbar__action" type="button"></button>
</div>
EOF;
	} // }}}

	protected function getClass() // {{{
	{
		return parent::getClass() . " mdl-js-snackbar mdl-snackbar mdl-color--red-900 mdl-shadow--4dp";
	} // }}}


} // }}}

?>
