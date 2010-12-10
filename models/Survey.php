<?php

class Survey_Model extends Model {
	
	/**
	 * Add a vote in a survey
	 *
	 * @param int $id	Id of the survey
	 * @param array $id	Ids of the answers
	 * @param string $username	User name
	 * @return int	Id of the corresponding post
	 */
	public function vote($id, $votes, $username){
		if(count($votes) == 0)
			throw new Exception('You should give at least one answer');
		
		$surveys = $this->createQuery()->select($id);
		if(!isset($surveys[0]))
			throw new Exception('Survey not found');
		$survey = $surveys[0];
		unset($surveys);
		
		if($survey['multiple'] != '1' && count($votes) != 1)
			throw new Exception('You must choose exactly one answer');
		
		if(strtotime($survey['date_end']) < time())
			throw new Exception('The survey is closed');
		
		$post_model = new Post_Model();
		$post = $post_model->getRawPost((int) $survey['post_id']);
		
		$answers = DB::createQuery('survey_answers')
			->fields('id', 'votes')
			->where(array('survey_id'	=> $survey['id']))
			->select();
		foreach($answers as $answer){
			$answer['votes'] = $answer['votes']=='' ? array() : json_decode($answer['votes'], true);
			if(in_array($username, $answer['votes']) && !in_array((int) $answer['id'], $votes)){
				array_splice($answer['votes'], array_search($username, $answer['votes']), 1);
				$weight = -1;
			}else if(!in_array($username, $answer['votes']) && in_array((int) $answer['id'], $votes)){
				$answer['votes'][] = $username;
				$weight = 1;
			}else{
				continue;
			}
			DB::createQuery('survey_answers')
				->set(array(
					'votes'	=> json_encode($answer['votes']),
					'nb_votes = nb_votes'.($weight==1 ? '+1' : '-1')
				))
				->update((int) $answer['id']);
		}
		
		Post_Model::clearCache();
		return (int) $post['id'];
	}
	
}
