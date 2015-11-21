<?php
/**
 * Message - Clase PHP para usar mensajes de validacion en distintas idiomas
 * @author Santi Bartolome
 * @version 0.2 (2012_07_17)
 * @package Enfusion Theme 
 */

class Message{

	private $messages = array();
	
	public function __construct($lang=NULL, $message=NULL)
	{
		$this->messages[$lang] = $message;
		
		return true;
	}
	
	public function set_message($lang, $message)
	{
		$this->messages[$lang] = $message;
		
		return true;
	}
	
	public function get_message($lang)
	{
		return $this->messages[$lang];
	}

	public function print_message($lang)
	{
		echo $this->messages[$lang];
		
		return true;
	}


}

?>