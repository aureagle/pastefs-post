<?php

/**
Author: Aurangzeb <aureagle@gmail.com>
License: GNU General Public License

*/

class PasteFS {
	private $params = [];
	private $pastefs_url = 'https://www.pastefs.com';
	private $num_attachments = 0;

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

	public function setText( $text ) {
		$this->params['post_text'] = $text;
	}

	public function addAttachment( $filepath, $mime ) {
		$this->params["filecontent[{$this->num_attachments}]"] = curl_file_create( $filepath );
		$this->params["filecontent[{$this->num_attachments}]"]->mime = $mime;
		$this->num_attachments ++;
	}

	public function post() {
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