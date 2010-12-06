<?php
/**
 * Form Exception class
 */

class FormException extends Exception {
	
	protected $error;
	
	public function __construct($error){
		$this->error = $error;
	}
	
	public function setError($error){
		$this->error = $error;
	}
	
	public function getError(){
		return $this->error;
	}
	
}
