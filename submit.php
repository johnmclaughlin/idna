<?php
if(isset($_POST)){

	$answers = $_POST['answer'];
	$pid = $_POST['pid'];
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];
    $time = time();

// Make a MySQL Connection
mysql_connect("internal-db.s130259.gridserver.com", "db130259", "BL167382h") or die(mysql_error());
mysql_select_db("db130259_idna") or die(mysql_error());

if($action == 'clear'){
	foreach($answers as $key => $answer):
		$check = mysql_query("SELECT * FROM results WHERE (pid LIKE '$pid' AND q = $key);") 
		or die(mysql_error());

		if(mysql_num_rows($check) > 0){
			$pids = mysql_query("
				DELETE FROM results WHERE (pid LIKE '$pid' AND q = $key);
			") or die(mysql_error());
		}
	endforeach;
} elseif($action == 'complete'){
	print_r($answers);
	foreach($answers as $key => $answer):

		$check = mysql_query("SELECT * FROM results WHERE (pid LIKE '$pid' AND q = $key);") 
		or die(mysql_error());
		
		if(mysql_num_rows($check) > 0){
			$pids = mysql_query("
				UPDATE results SET response = $answer WHERE (pid LIKE '$pid' AND q = $key);
			") or die(mysql_error());
		} else {
			$pids = mysql_query("
				INSERT INTO results(pid, q, response) VALUES ('$pid', $key, $answer);
			") or die(mysql_error());
		}
	endforeach;
	$assessmet_update = mysql_query("UPDATE assessments SET complete = $time WHERE pid LIKE '$pid';");

	// Post to calculation script
	$url = 'http://websitestaging.com/IDNA/report.php';
	//what post fields?
	$fields = array(
		'pid'=>$pid
	);

	//build the urlencoded data
	$postvars='';
	$sep='';
	foreach($fields as $key=>$value) 
	{ 
	$postvars.= $sep.urlencode($key).'='.urlencode($value); 
	$sep='&'; 
	}

	//open connection
	$ch = curl_init();
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_POST,count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
	//execute post
	$result = curl_exec($ch);
	//close connection
	curl_close($ch);
} else {

	foreach($answers as $key => $answer):

		$check = mysql_query("SELECT * FROM results WHERE (pid LIKE '$pid' AND q = $key);") 
		or die(mysql_error());
		
		if(mysql_num_rows($check) > 0){
			$pids = mysql_query("
				UPDATE results SET response = $answer WHERE (pid LIKE '$pid' AND q = $key);
			") or die(mysql_error());
		} else {
			$pids = mysql_query("
				INSERT INTO results(pid, q, response) VALUES ('$pid', $key, $answer);
			") or die(mysql_error());
		}
	endforeach;
}

} else {
	echo 'Error - no POST received';
}

?>
