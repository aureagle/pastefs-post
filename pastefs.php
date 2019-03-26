<?php

/**
Author: Aurangzeb <aureagle@gmail.com>
License: GNU General Public License

*/

class PasteFS {
	private $params = [];
	private $pastefs_url = 'https://www.pastefs.com';
	private $num_attachments = 0;

	/**
	construct the PasteFS post
	@param api_key (string) api_key for posting content to pastefs
	*/
	public function __construct( $api_key ) {
		$this->params = [
		'api_key' => $api_key,
		'submit' => 'true',
		'paste_type' => 'text',
		'post_text' => '',
		'content_rating' => 'family_safe',
		'visibility' => 'public',
		'recaptcha' => 'false',
		];
	}

	/**
	set content rating of the paste
	@param rating (string) can be one of 'nsfw' and 'family_safe'
	@return true on success, false otherwise
	*/
	public function setContentRating( $rating ) {
		if( !in_array( $rating, ['nsfw', 'family_safe'] )) return false;
		$this->params['content_rating'] = $rating;
		return true;
	}

	/**
	sets visibility of the post
	@param visibility (string) can be one of 'public', 'unlisted' and 'private'
	@return true of value successfully set, false on error
	*/
	public function setVisibility( $visibility ) {
		if( !in_array( $visibility, ['private', 'unlisted', 'public' ] )) return false;
		$this->params['visibility'] = $visibility;
		return true;
	}

	/**
	sets text to be posted
	@param text (string/binary) text to be posted
	*/
	public function setText( $text ) {
		$this->params['post_text'] = $text;
	}

	/**
	add attachment to the post
	@param filepath (string) path to file to be posted
	@param mime (string) mime type of the file ex: video/mp4
	*/
	public function addAttachment( $filepath, $mime ) {
		$this->params["filecontent[{$this->num_attachments}]"] = curl_file_create( $filepath );
		$this->params["filecontent[{$this->num_attachments}]"]->mime = $mime;
		$this->num_attachments ++;
	}

	/**
	post the paste to the server with all text and added attachments
	@return see sendViaCurl
	*/
	public function post() {
		return $this->sendViaCurl();
	}

	/**
	post the paste to the server with all text and added attachments
	@return see sendViaCurl
	*/
	public function send() {
		return $this->sendViaCurl();
	}

	/**
	function to post the paste using curl, this should be the last function that is called after creating the paste
	posts to server via curl
	@return response from the server on success, FALSE on failure
	*/
	private function sendViaCurl() {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_HTTPHEADER, [
			'Content-Type' => 'multipart/form-data'
		]);
		curl_setopt( $ch, CURLOPT_URL, $this->pastefs_url . '/submit/submits/api_submit.php' );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $this->params );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

		$result = curl_exec( $ch );
		curl_close( $ch );
		return $result;
	}
}