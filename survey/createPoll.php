<?php
	require 'DBHandler.php';

	//validate post variable
	if( !isset($_POST['question'])	|| 
		!isset($_POST['answers']) 	||
		$_POST['question'] == ""	||
		count($_POST['answers']) < 2 		||
		in_array("false", array_map( 	function( $item ){ return ( $item == "" || strlen($item) > 30 ) ? "false" : $item;},
										$_POST['answers']
									) 
		)
	){ exit; }
	
	//create unique poll link
	$pollLink = uniqid();
	
	//try to create poll in Database 
	if( !DBHandler::insertPoll($_POST['question'], $_POST['answers'], $pollLink) ){ echo "Error"; exit; }
	echo $pollLink;

?>