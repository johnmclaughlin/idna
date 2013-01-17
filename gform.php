<?php
if(isset($_POST)){
print_r($_POST);
	$answers = $_POST['responses'];
	$pid = $_POST['pid'];
    $time = time();
    $action = $_POST['action'];

// Make a MySQL Connection
mysql_connect("internal-db.s130259.gridserver.com", "db130259", "BL167382h") or die(mysql_error());
mysql_select_db("db130259_idna") or die(mysql_error());

	foreach($answers as $key => $answer):

		$check = mysql_query("SELECT * FROM $action WHERE (pid LIKE '$pid' AND q = $key);") 
		or die(mysql_error());
		
		if(mysql_num_rows($check) > 0){
			$pids = mysql_query("
				UPDATE $action SET response = '$answer' WHERE (pid LIKE '$pid' AND q = $key);
			") or die(mysql_error());
		} else {
			$pids = mysql_query("
				INSERT INTO $action(pid, q, response) VALUES ('$pid', $key, '$answer');
			") or die(mysql_error());
		}
	endforeach;

} else {
	echo 'Error - no POST received';
}

?>
