<?php

class Search_Model extends Model {
	
	private $client;
	
	/**
	 * Inits the ElasticSearch API class
	 */
	public function __construct(){
		require_once APP_DIR.'classes/elasticsearch/ElasticSearchClient.php';
		$transport = new ElasticSearchTransportHTTP(Config::$ELASTICSEARCH['host'], Config::$ELASTICSEARCH['port']);
		$this->client = new ElasticSearchClient($transport, Config::$ELASTICSEARCH['index'], null);
	}
	
	
	/**
	 * Add or update a document in the search index
	 *
	 * @param array $data	Content (fields) of the document
	 * @param string $type	Type of document
	 * @param string $id	Id of the document (auto generated if not set)
	 */
	public function index($data, $type, $id=null){
		$this->client->setType($type);
		$this->client->index($data, $id);
	}
	
	
	/**
	 * Search posts, groups, and students
	 *
	 * @param string $query				Keywords
	 * @param string|array $type		Type(s) of document
	 * @param int $limit				Number of document returned
	 * @param boolean $only_official	If true, only official content (official post, no students)
	 * @param boolean $show_private		If true, includes private posts
	 * @return array	Documents
	 */
	public function search($query, $type=null, $limit=10, $only_official=true, $show_private=false){
		if(!isset($type)){
			$type = array('post', 'group');
			if(!$only_official)
				$type[] = 'student';
		}
		
		$this->client->setType($type);
		
		$query = self::sanitize($query);
		
		if($only_official)
			$query .= ' AND NOT official:false';
		if(!$show_private)
			$query .= ' AND NOT private:true';
		
		$results = $this->client->search(array(
			'query'	=> array(
				'field'	=> array(
					'_all'	=> $query
				)
			),
			'from'	=> 0,
			'size'	=> $limit
		));
		
		return isset($results['hits']) && $results['hits']['hits']
			? $results['hits']['hits']
			: array();
	}
	
	
	/**
	 * Auto complete posts' messages, groups' names, and students' names
	 *
	 * @param string $query				Keywords
	 * @param string|array $type		Type(s) of document
	 * @param int $limit				Number of document returned
	 * @param boolean $only_official	If true, only official content (official post, no students)
	 * @param boolean $show_private		If true, includes private posts
	 * @return array	Documents
	 */
	public function autocomplete($query, $type=null, $limit=10, $only_official=true, $show_private=false){
		if(!isset($type)){
			$type = array('post', 'group');
			if(!$only_official)
				$type[] = 'student';
		}
		
		$this->client->setType($type);
		
		$query = self::important(self::sanitize($query)).'*';
		
		if($only_official)
			$query .= ' AND NOT official:false';
		if(!$show_private)
			$query .= ' AND NOT private:true';
		
		$results = $this->client->search(array(
			'query'	=> array(
				'field'	=> array(
					'_all'	=> $query
				)
			),
			'from'	=> 0,
			'size'	=> $limit
		));
		
		return isset($results['hits']) && $results['hits']['hits']
			? $results['hits']['hits']
			: array();
	}
	
	
	/**
	 * Delete an index, a type, or a document
	 *
	 * @param string|array $type	Type(s) of document
	 * @param int $id				Id of the document
	 */
	public function delete($type=null, $id=null){
		$this->client->setType($type);
		$this->client->delete($id);
	}
	
	
	/**
	 * Modify a text for the search
	 *
	 * @param string $txt	Text
	 * @return string		Modified text
	 */
	public static function sanitize($txt){
		$txt = Text::removeAccents($txt);
		$txt = preg_replace('#[\x00-\x2F\x3A-\x3F\x5B-\x60\x7B-\x7F\xEF]#', ' ', $txt);
		$txt = preg_replace('# +#', ' ', $txt);
		$txt = trim($txt);
		return $txt;
	}
	
	
	/**
	 * Make all the words of a query important
	 *
	 * @param string $txt	Query
	 * @return string		Modified Query
	 */
	private static function important($txt){
		$txt = preg_replace('#(^| +)#', '$1+', $txt);
		return $txt;
	}
	
}
