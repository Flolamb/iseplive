<?php

class Page_Controller extends Controller {
	
	/**
	 * Contact page
	 */
	public function contact(){
		$this->setView('contact.php');
		$this->setTitle(__('PAGE_CONTACT_TITLE'));
	}
	
	/**
	 * Contact page
	 */
	public function legal(){
		$this->setView('legal.php');
		$this->setTitle(__('PAGE_LEGAL_TITLE'));
	}
	
	/**
	 * Page not found : 404 Error
	 */
	public function error404(){
		$this->setView('error404.php');
		$this->setTitle(__('PAGE_ERROR404_TITLE'));
		header('HTTP/1.1 404 Not found');
	}
	
	/*
	 * Error page (Exception thrown)
	 *
	 * @param Exception $e	Exception thrown and catch
	 */
	public function error(Exception $e){
		$this->setView('error.php');
		$this->setTitle(__('PAGE_ERROR_TITLE'));
		
		if(Config::DEBUG)
			$this->set('message', $e->getMessage());
	}
	
}
