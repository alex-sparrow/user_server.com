<?php

class ApiController extends Controller {

	public function actionCreatePin() {
		//get Parameteres
		$req    = Yii::app()->request;
		$answ  = new ServAnswer();
		$error = new MyError();
		$s_id   = $req->getParam( 'sessionid' );
		$m_name = $req->getParam( 'name' );
		$m_lat  = $req->getParam( 'lat' );
		$m_lng  = $req->getParam( 'lng' );
		if ( empty( $m_name ) || empty( $s_id ) || empty( $m_lat ) || empty( $m_lng ) ) {
			$error->Raise(1,'Not enough parametres');
		}//check that parameteres not empty

		//checking Session
		$c_ses = new NSession();
		$u_id  = $c_ses->check( $s_id );   //check session key
		if ( empty( $u_id ) ) {
			$error->Raise(11,'Incorrect Session ID');
		}

		//pin creation
		$latlng = array( 'lat' => $m_lat, 'lng' => $m_lng );

		$pin          = new Marker();
		$pin->name    = $m_name;
		$pin->latlng  = $latlng;
		$pin->user_id = $u_id * 1;

		$pin->save();


		//create JSON data
		$s_data = array( 'session_id' => $c_ses->get_key() );
		$answ->Show($s_data);

	}

	public function actionGetPinList() {
		$req   = Yii::app()->request;
		$answ  = new ServAnswer();
		$error = new MyError();

		$s_id = $req->getParam( 'sessionid' );

		if ( empty( $s_id ) ) {
			$error->Raise(10,'Empty Session Id');
		}//check that parameteres not empty

		//checking Session
		$c_ses = new NSession();
		$u_id  = $c_ses->check( $s_id );   //check session key
		if ( empty( $u_id ) ) {
			$error->Raise(11,'Incorrect Session ID');
		}


		$markers = Marker::model()->limitted( $u_id, $req->getParam( 'limit' ), $req->getParam( 'offset' ) )->findAll();
		//create JSON data
		$s_data = array( 'session_id' => $c_ses->get_key(), 'markers' => $markers );

		$answ->Show($s_data);
	}

	public function actionIndex() {
		$this->actionLogin();
	}

	public function actionLogin() {
		$answ  = new ServAnswer();
		$error = new MyError();
		$req      = Yii::app()->request;
		$login    = $req->getParam( 'email' );
		$password = $req->getParam( 'password' );

		if ( empty( $login ) || empty( $password ) ) {
			$error->Raise(1,'Not enough parametres');
		}//check that parameteres are not empty

		if ( strlen( $password ) < 6 ) {
			$error->Raise(2,'Too short password');
		} //check password length

		$user = User::model()->find( 'login=:login AND password=:password', array( ':login' => $login, ':password' => md5( $password ) ) );//check if user exists
		if ( empty( $user ) ) {
			$error->Raise(3,'Login or password is incorrect');
		}

		//set session
		$c_ses = new NSession();
		$c_ses->set( $user->id );

		//create JSON data
		$s_data = array( 'session_id' => $c_ses->get_key() );
		$answ->Show($s_data);
	}


	public function actionSignUp() {
		$req      = Yii::app()->request;
		$answ  = new ServAnswer();
		$error = new MyError();
		$login    = $req->getParam( 'email' );
		$password = $req->getParam( 'password' );
		$fname    = $req->getParam( 'fname' );
		$lname    = $req->getParam( 'lname' );

		if ( empty( $login ) || empty( $password ) || empty( $fname ) || empty( $lname ) ) {
			$error->Raise(1,'Not enough parametres');
		}//check that parameteres not empty

		if ( strlen( $password ) < 6 ) {
			$error->Raise(2,'Too short password');
		} //check password length

		$user = User::model()->find( 'login=:login', array( ':login' => $login ) );//check if user exists
		if ( !empty( $user ) ) {
			$error->Raise(3,'Login or password is incorrect');
		}

		//save user
		$user = new User();

		$user->login    = $login;
		$user->password = md5( $password );
		$user->fname    = $fname;
		$user->lname    = $lname;

		$user->save();

		//set session
		$c_ses = new NSession();
		$c_ses->set( $user->id );

		//create JSON data
		$s_data = array( 'session_id' => $c_ses->get_key() );
		$answ->Show($s_data);
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/

}