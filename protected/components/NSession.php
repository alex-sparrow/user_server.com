<?php

class NSession {
	private $s_key;//session key

	public function __construct() {

	}

	//checks if session key is exist
	public function check( $s_key ) {
		$c_ses = SessionId::model()->findActive( $s_key )->find();
		if ( empty( $c_ses ) ) {
			return false;
		} //exit if no session
		$this->s_key = $c_ses->session_key;

		return $c_ses->user_id;
	}

	//set up new or update existing session key
	public function set( $u_id ) {
		$c_ses = SessionId::model()->find( 'user_id=:user_id', array( ':user_id' => $u_id ) );//check if key exists
		if ( empty( $c_ses ) ) {
			$c_ses = new SessionId();
		}   //set if no session

		$this->s_key        = $this->gen_key();
		$c_ses->session_key = $this->s_key;
        $c_ses->exp_date    = strtotime( '+ 1 day' );
        $c_ses->user_id     = $u_id;
        $c_ses->save();
		Yii::log("errors saving SomeModel: " . var_export($c_ses->getErrors(), true), CLogger::LEVEL_WARNING, __METHOD__);
        return $this->s_key;
    }

	//get session key
	function get_key() {
		return $this->s_key;
	}

	//generate new session key
	private function gen_key() {
		$time   = time();
		$random = rand();

		return hash( 'sha256', $time . $random, false );
	}
}