<?php

$conn = new PDO( 'mysql:host=localhost;dbname=survey', 'root', '' );
$conn->query("SET CHARACTER SET utf8;");

class DBHandler 
{	
	public static function insertPoll($question, $answers, $link)
	{
		global $conn;
		
		try {
			$conn->beginTransaction();
			$statement = $conn->prepare("insert into question ( question ) values ( :question ) ");
			$statement->execute( array( ':question' => $question ) );
			$lastid = $conn->lastInsertId();

			$statement = $conn->prepare("insert into poll ( link, question_id ) values ( :link, :questionId ) ");
			$statement->execute( array( ':link' => $link, ':questionId' => $lastid ) );
			$lastid = $conn->lastInsertId();
			
			$statement = $conn->prepare("insert into answer ( answer, poll_id ) values ( :answer, :pollId ) ");
		
			for( $i = 0; $i < count($answers); $i++ ){
				$statement->execute( array( ':answer' => $answers[$i], ':pollId' => $lastid ) );
			}
			
			$conn->commit();
		}catch (Exception $e){
			$conn->rollback();
			return false;
		}
		
		return true;
	}
	
	
	public static function linkValid($link)
	{
		global $conn;

		$statement = $conn->prepare("select count(id) as amnt from poll where link = :link");
		$statement->execute( array( ':link' => $link ) );
		return ( $statement->fetch()["amnt"] != 0 ) ? true : false;
	}
	
	public static function answerValid($link, $answer)
	{
		global $conn;

		$statement = $conn->prepare("select count(id) as amnt from answer where answer = :answer and poll_id = (
										select id from poll where link = :link
									)");
		$statement->execute( array( ':answer' => $answer, ':link' => $link ) );
		return ( $statement->fetch()["amnt"] != 0 ) ? true : false;
	}
	
	
	public static function readPoll($link)
	{
		global $conn;

		$statement = $conn->prepare("select question, answer_res from polls where link = :link");
		$statement->execute( array( ':link' => $link ) );
		return $statement->fetch();
	}
	
	public static function insertAnswer($link, $answer, $name)
	{
		global $conn;

		$statement = $conn->prepare("insert into result ( name, answer_id ) values (	:name, 
																						(
																							select id from answer where answer = :answer and poll_id = (
																								select id from poll where link = :link
																							)
																						)
																					)
														");
		$statement->execute( array( ':name' 	=> $name, 
									':answer'	=> $answer,
									':link'		=> $link
								) 
		);
	}
	
	public static function insertUser($link, $ip, $agent)
	{
		global $conn;

		$statement = $conn->prepare("insert into voter ( voter_ip, voter_user_agent, link_id ) values (	:ip, :ua, 
																						(
																							select id from poll where link = :link
																							)
																						)
									");
		$statement->execute( array( ':ip' 	=> $ip, 
									':ua'	=> $agent,
									':link'	=> $link
								) 
		);
	}
	
	public static function hasVoted($link, $ip, $agent)
	{
		global $conn;

		$statement = $conn->prepare("select count(id) as amnt from voter where voter_ip = :ip 
																	and voter_user_agent = :ua 
																	and link_id = ( select id from poll where link = :link )
									");
		$statement->execute( array( ':ip' 	=> $ip, 
									':ua'	=> $agent,
									':link'	=> $link
								) 
		);
		
		return ( $statement->fetch()["amnt"] != 0 ) ? true : false;
	}
}

?>