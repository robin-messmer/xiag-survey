<?php
	require 'DBHandler.php';
	require __DIR__ . '/vendor/autoload.php';

	//validate user input
	if( !isset($_POST['name']) 										||
		!isset($_POST['answer']) 									||
		!isset($_POST['link']) 										||
		strlen($_POST['name']) < 2 									||
		strlen($_POST['name']) > 60 								||
		!DBHandler::linkValid(	 $_POST['link'] )					||
		!DBHandler::answerValid( $_POST['link'], $_POST['answer'] )
	){
		exit;
	}

	//insert User's choice
	DBHandler::insertAnswer( $_POST['link'], $_POST['answer'], $_POST['name'] );
	
	//log user's IP and User Agent in order to prevent double voting in same browser
	DBHandler::insertUser( $_POST['link'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'] );
	
	
	/************Update Result table on Client Side********/
	$options = array(
		'cluster' => 'eu',
		'useTLS' => true
	);
	
	$pusher = new Pusher\Pusher(
		'aa6d547d659f02669d6c',
		'8f5e7fbae11a266c9339',
		'758228',
		$options
	);

	$data['message'] = array( $_POST['answer'], $_POST['name'] );
	$pusher->trigger($_POST['link'], 'event', $data);
	/**************************""""""""""""""""""""""""""""""*/
?>