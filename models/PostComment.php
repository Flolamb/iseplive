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
	public function add($post_id, $user_id, $message, $attachment_id=null){
		if(isset($attachment_id)){
			$attachment = DB::createQuery('attachments')->select($attachment_id);
			if(!isset($attachment[0]))
				throw new Exception('Attachment not found!');
		}else{
			$attachment_id = null;
		}
		
		$id = $this->createQuery()
			->set(array(
				'post_id'	=> $post_id,
				'user_id'	=> $user_id,
				'message'	=> $message,
				'time'		=> time(),
				'attachment_id'	=> $attachment_id
			))
			->insert();
		
		Post_Model::clearCache();
		return $id;
	}
	
	
	/**
	 * Returns the information of a comment
	 * 
	 * @param int $id	Id of the comment
	 */
	public function get($id){
		$comments = $this->createQuery()->select($id);
		if(!isset($comments[0]))
			throw new Exception('Comment not found');
		return $comments[0];
	}
	
	/**
	 * Delete a comment
	 * 
	 * @param int $id	Id of the comment
	 */
	public function delete($id){
		$id = $this->createQuery()->delete($id);
		Post_Model::clearCache();
	}
	
}
