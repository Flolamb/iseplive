<?php

class Category_Model extends Model {
	
	/**
	 * Contains all categories in an array if getAll method has already been called
	 * @var array
	 */
	public static $categories;
	
	/**
	 * Returns an array with all categories
	 *
	 * @return array
	 */
	public function getAll(){
		if(isset(self::$categories))
			return self::$categories;
		if($categories = Cache::read('categories'))
			return $categories;
		
		self::$categories = $this->createQuery()
			->fields('id', 'name', 'url_name')
			->order(array('order', 'ASC'))
			->select();
		Cache::write('categories', self::$categories, 3600);
		return self::$categories;
	}
	
}
