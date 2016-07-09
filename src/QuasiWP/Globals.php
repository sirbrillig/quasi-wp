<?php
define( 'WP_CONTENT_DIR', '.' );

class WP_Error {
	public function __construct( $code='', $message='', $data='' ) {
		$this->code = $code;
		if ( empty( $message ) ) {
			$message = $code;
		}
		$this->message = $message;
		$this->data = $data;
	}

	public function get_error_message() {
		return $this->message;
	}
}

class Mock_Theme {
	public function __construct( $exists ) {
		$this->exists = $exists;
	}
	public function exists() {
		return $this->exists;
	}
}
