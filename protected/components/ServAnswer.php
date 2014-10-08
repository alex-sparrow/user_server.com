<?php

class ServAnswer {
	private $AnswerType; //shows in which format answer should be translated
	private $header;

	public function __construct( $type = 'json' ) {
		$this->AnswerType = $type;

		switch ( $this->AnswerType ) {
			case 'json':
				$header = 'Content-Type: application/json';
			default:
				$header = 'Content-Type: application/json';
		}
	}

	//getting  answer
	public function Get( $data ) {
		switch ( $this->AnswerType ) {
			case 'json':
				return $this->GetJson( $data );
				break;
			default:
				return $this->GetJson( $data );
		}
	}

	//show answer
	public function Show( $data ) {
		//show equal header
		echo $this->header;
		echo $this->Get( $data );
		return true;
	}

	//json formating
	private function GetJson( $data ) {
		return CJSON::encode( $data );
	}
} 