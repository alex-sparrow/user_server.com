<?php

class MyError extends ServAnswer {
	public function __construct($type = 'json' ){
		parent::__construct($type);
	}
	public function Raise( $code, $txt ) {
		if ( empty( $code ) || empty( $txt ) ) {
			false;
		}
		$error_ar = array( 'code' => $code, 'msg' => $txt );
		$this->Show( $error_ar );
		exit();
	}
} 