<?php

class PostComment_Model extends Model {
	
	/**
	 * Add a comment to a post
	 *
	 * @param int $post_id	Id of the post
	 * @param int $user_id	Id of the user
	 * @param string $message	Content of the comment
	 * @return int	Id of the new comment
	 */
	public function add($post_id, $user_id, $message){
		$id = $this->createQuery()
			->set(array(
				'post_id'	=> $post_id,
				'user_id'	=> $user_id,
				'message'	=> $message,
				'time'		=> time()
			))
			->insert();
		
		Post_Model::clearCache();
		return $id;
	}
	
}
