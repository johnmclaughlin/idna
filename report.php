<?php

mysql_connect("internal-db.s130259.gridserver.com", "db130259", "BL167382h") or die(mysql_error());
mysql_select_db("db130259_idna") or die(mysql_error());

$pid = $_POST['pid']; // GET PID FROM POST

//$pid = 'cb76d0974d189683ef98390a70e430dc';

$cat_avgs = mysql_query("
    SELECT results.pid, 
        wp_postmeta.post_id, 
        wp_postmeta.meta_value AS question_number, 
        wp_terms.`name`, 
    ROUND(AVG(results.response), 1) AS score
    FROM wp_postmeta INNER JOIN wp_posts ON wp_postmeta.post_id = wp_posts.ID
        INNER JOIN wp_term_relationships ON wp_posts.ID = wp_term_relationships.object_id
        INNER JOIN wp_terms ON wp_term_relationships.term_taxonomy_id = wp_terms.term_id
        INNER JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id
        INNER JOIN results ON wp_postmeta.meta_value = results.q
    WHERE wp_postmeta.meta_key = 'question_system_id' AND wp_posts.post_status = 'publish' AND wp_term_taxonomy.parent != 0 AND results.response != 0 and results.pid = '$pid'
    GROUP BY wp_terms.`name`
") or die(mysql_error());

$check = mysql_query("SELECT * FROM reports WHERE pid = '$pid'");
if(mysql_num_rows($check) > 0) {
    echo 'This PID already exists in the report database<br />';
    } else {
        $setup = mysql_query("INSERT INTO reports (pid) VALUES ('$pid')") or die(mysql_error());
    }

while($cat_avg = mysql_fetch_array( $cat_avgs )) {
    $score = $cat_avg['score'];
    switch ($cat_avg['name']) {
	case 'Analyzing':
	    $update = mysql_query("UPDATE reports SET c1 = $score WHERE pid = '$pid'") or die(mysql_error());
	    break;
	case 'Associating':
	    $update = mysql_query("UPDATE reports SET b5 = $score WHERE pid = '$pid'") or die(mysql_error());
	    break;
	case 'Challenging the Status Quo':
	    $update = mysql_query("UPDATE reports SET a1 = $score WHERE pid = '$pid'") or die(mysql_error());
	    break;
	case 'Creative Confidence':
	    $update = mysql_query("UPDATE reports SET a3 = $score WHERE pid = '$pid'") or die(mysql_error());
	    break;
	case 'Detail Oriented':
	    $update = mysql_query("UPDATE reports SET c3 = $score WHERE pid = '$pid'") or die(mysql_error());
	    break;
	case 'Experimenting':
	    $update = mysql_query("UPDATE reports SET b4 = $score WHERE pid = '$pid'") or die(mysql_error());
	    break;
	case 'Networking':
	    $update = mysql_query("UPDATE reports SET b3 = $score WHERE pid = '$pid'") or die(mysql_error());
	    break;
	case 'Observing':
	    $update = mysql_query("UPDATE reports SET b2 = $score WHERE pid = '$pid'") or die(mysql_error());
	    break;
	case 'Planning':
	    $update = mysql_query("UPDATE reports SET c2 = $score WHERE pid = '$pid'") or die(mysql_error());
	    break;
	case 'Questioning':
	    $update = mysql_query("UPDATE reports SET b1 = $score WHERE pid = '$pid'") or die(mysql_error());
	    break;
	case 'Risk Taking':
	    $update = mysql_query("UPDATE reports SET a2 = $score WHERE pid = '$pid'") or die(mysql_error());
	    break;
	case 'Self Disciplined':
	    $update = mysql_query("UPDATE reports SET c4 = $score WHERE pid = '$pid'") or die(mysql_error());
	    break;
    }
}

// echo 'Skill averages inserted<br />';

$avg_a = mysql_query("SELECT a1, a2, a3 FROM reports WHERE pid = '$pid'");
$avg_b = mysql_query("SELECT b1, b2, b3, b4, b5 FROM reports WHERE pid = '$pid'");
$avg_c = mysql_query("SELECT c1, c2, c3 FROM reports WHERE pid = '$pid'");

while($avg = mysql_fetch_array( $avg_a )) {
    $a = number_format(($avg['a1'] + $avg['a2'] + $avg['a3'])/3, 1);
    $update = mysql_query("UPDATE reports SET a = $a WHERE pid = '$pid'") or die(mysql_error());
    echo 'Innovation Score inserted<br />';
}

while($avg = mysql_fetch_array( $avg_b )) {
    $b = number_format(($avg['b1'] + $avg['b2'] + $avg['b3'] + $avg['b4'] + $avg['b5'])/5, 1);
    $update = mysql_query("UPDATE reports SET b = $b WHERE pid = '$pid'") or die(mysql_error());
    echo 'Discovery Score inserted: ' . $b . '<br />';
}

while($avg = mysql_fetch_array( $avg_c )) {
    $c = number_format(($avg['c1'] + $avg['c2'] + $avg['c3'] + $avg['c4'])/4, 1);
    $update = mysql_query("UPDATE reports SET c = $c WHERE pid = '$pid'") or die(mysql_error());
    echo 'Delivery Score inserted: ' . $c . '<br />';
}


$avg_a = mysql_query("SELECT
	ROUND(AVG(reports.a), 1) AS a_avg, 
	ROUND(AVG(reports.a1), 1) AS a1_avg, 
	ROUND(AVG(reports.a2), 1) AS a2_avg, 
	ROUND(AVG(reports.a3), 1) AS a3_avg
	FROM reports
	WHERE a_percentile > 75
	");

$avg_b = mysql_query("SELECT
	ROUND(AVG(reports.b), 1) AS b_avg, 
	ROUND(AVG(reports.b1), 1) AS b1_avg, 
	ROUND(AVG(reports.b2), 1) AS b2_avg, 
	ROUND(AVG(reports.b3), 1) AS b3_avg,
	ROUND(AVG(reports.b4), 1) AS b4_avg,
	ROUND(AVG(reports.b5), 1) AS b5_avg
	FROM reports
	WHERE a_percentile > 75
	");

$avg_c = mysql_query("SELECT
	ROUND(AVG(reports.c), 1) AS c_avg, 
	ROUND(AVG(reports.c1), 1) AS c1_avg, 
	ROUND(AVG(reports.c2), 1) AS c2_avg, 
	ROUND(AVG(reports.c3), 1) AS c3_avg,
	ROUND(AVG(reports.c4), 1) AS c4_avg
	FROM reports
	WHERE a_percentile > 75
	");

while($x = mysql_fetch_array( $avg_a )) {
    $update = mysql_query("UPDATE reports SET
			  a_avg = ".$x['a_avg'].",
			  a1_avg = ".$x['a1_avg'].",
			  a2_avg = ".$x['a2_avg'].",
			  a3_avg = ".$x['a3_avg']."
			  WHERE pid = '$pid'") or die(mysql_error());
}

while($x = mysql_fetch_array( $avg_b )) {
    $update = mysql_query("UPDATE reports SET
			  b_avg = ".$x['b_avg'].",
			  b1_avg = ".$x['b1_avg'].",
			  b2_avg = ".$x['b2_avg'].",
			  b3_avg = ".$x['b3_avg'].",
			  b4_avg = ".$x['b4_avg'].",
			  b5_avg = ".$x['b5_avg']."
			  WHERE pid = '$pid'") or die(mysql_error());
}

while($x = mysql_fetch_array( $avg_c )) {
    $update = mysql_query("UPDATE reports SET
			  c_avg = ".$x['c_avg'].",
			  c1_avg = ".$x['c1_avg'].",
			  c2_avg = ".$x['c2_avg'].",
			  c3_avg = ".$x['c3_avg'].",
			  c4_avg = ".$x['c4_avg']."
			  WHERE pid = '$pid'") or die(mysql_error());
}

// echo 'Overall Score Averages inserted<br />';

$innovation_x = mysql_query("SELECT a FROM reports ORDER BY a ASC");
$innovation = array();
while($inno_x = mysql_fetch_array( $innovation_x )) {
    array_push($innovation, $inno_x['a']);
}

$delivery_x = mysql_query("SELECT c FROM reports ORDER BY c ASC");
$delivery = array();
while($del_x = mysql_fetch_array( $delivery_x )) {
    array_push($delivery, $del_x['c']);
}

$discovery_y = mysql_query("SELECT b FROM reports ORDER BY b ASC");
$discovery = array();
while($dis_y = mysql_fetch_array( $discovery_y )) {
    array_push($discovery, $dis_y['b']);
}

$get_cat_scores = mysql_query("SELECT a, b, c FROM reports WHERE pid = '$pid'");
    while($get = mysql_fetch_array( $get_cat_scores )) {
	$a = $get['a'];
	$b = $get['b'];
	$c = $get['c'];
    }

$lz = (end(array_keys($innovation, $a)) + 1); // calculate position in array
$z = number_format(($lz/count($innovation))*100, 0); // calculate percentile - DELIVERY
$update = mysql_query("UPDATE reports SET a_percentile = $z WHERE pid = '$pid'") or die(mysql_error());

$lx = (end(array_keys($delivery, $c)) + 1); // calculate position in array
$x = number_format(($lx/count($delivery))*100, 0); // calculate percentile - DELIVERY
$update = mysql_query("UPDATE reports SET c_percentile = $x WHERE pid = '$pid'") or die(mysql_error());

$ly = (end(array_keys($discovery, $b)) + 1); // calculate position in array
$y = number_format(($ly/count($discovery))*100, 0);  // calculate percentile - DISCOVERY
$update = mysql_query("UPDATE reports SET b_percentile = $y WHERE pid = '$pid'") or die(mysql_error());

// echo 'Delivery Score: ' . $c . '<br />';
// echo 'Discovery Score: ' . $b . '<br />';
//echo 'a: ' . $a . '<br />';
//echo 'Lz: ' . $lz . '<br />';
// echo 'Lx: ' . $lx . '<br />';
// echo 'Ly: ' . $ly . '<br />';
/*
$get_b = mysql_query("SELECT b_percentile, c_percentile FROM reports WHERE pid = '$pid'");
    while($get = mysql_fetch_array( $get_b )) {
	$y = $get['b_percentile'];
	$x = $get['c_percentile'];
    }
*/
//echo 'Innovation Percentile: ' . $z . '<br />';
// echo 'Delivery Percentile: ' . $x . '<br />';
// echo 'Discovery Percentile: ' . $y . '<br />';

$theta = rad2deg(atan2($y,$x));

if ($theta <= 18) { $update = mysql_query("UPDATE reports SET profile_id = 1 WHERE pid = '$pid'") or die(mysql_error()); }
if ($theta > 18 && $theta <= 36) { $update = mysql_query("UPDATE reports SET profile_id = 2 WHERE pid = '$pid'") or die(mysql_error()); }
if ($theta > 36 && $theta <= 54) { $update = mysql_query("UPDATE reports SET profile_id = 3 WHERE pid = '$pid'") or die(mysql_error()); }
if ($theta > 54 && $theta <= 72) { $update = mysql_query("UPDATE reports SET profile_id = 4 WHERE pid = '$pid'") or die(mysql_error()); }
if ($theta > 72 && $theta <= 90) { $update = mysql_query("UPDATE reports SET profile_id = 5 WHERE pid = '$pid'") or die(mysql_error()); }

// echo "&Theta;: " . number_format($theta, 2) . "&deg;<br />________________________________________<br />";

function fetch_demo($group, $q, $i){  
    global $rows;  
    $avg = mysql_query("SELECT
	ROUND(AVG(reports.a), 1) as d_a, 
	ROUND(AVG(reports.b), 1) as d_b, 
	ROUND(AVG(reports.c), 1) as d_c, 
	ROUND(AVG(reports.a1), 1) as d_a1, 
	ROUND(AVG(reports.a2), 1) as d_a2, 
	ROUND(AVG(reports.a3), 1) as d_a3, 
	ROUND(AVG(reports.b1), 1) as d_b1, 
	ROUND(AVG(reports.b2), 1) as d_b2, 
	ROUND(AVG(reports.b3), 1) as d_b3, 
	ROUND(AVG(reports.b4), 1) as d_b4, 
	ROUND(AVG(reports.b5), 1) as d_b5, 
	ROUND(AVG(reports.c1), 1) as d_c1, 
	ROUND(AVG(reports.c2), 1) as d_c2, 
	ROUND(AVG(reports.c3), 1) as d_c3, 
	ROUND(AVG(reports.c4), 1) as d_c4
        FROM preassessment INNER JOIN `pre-assess_options` ON preassessment.response = `pre-assess_options`.`option`
	INNER JOIN reports ON reports.pid = preassessment.pid
        WHERE `pre-assess_options`.`group` = '$group' and preassessment.q = $q
	"); 
    while($r = mysql_fetch_assoc($avg)) {
    $rows[$i] = $r;
    } 
}

$rows = array();
fetch_demo('a1', 3, 0);
fetch_demo('a2', 3, 1);
fetch_demo('a3', 3, 2);
fetch_demo('a4', 3, 3);
fetch_demo('a5', 3, 4);
fetch_demo('a6', 3, 5);
fetch_demo('g1', 1, 6);
fetch_demo('g2', 1, 7);
fetch_demo('g3', 1, 8);
fetch_demo('g4', 1, 9);
fetch_demo('g5', 1, 10);
fetch_demo('g6', 1, 11);
fetch_demo('i1', 14, 12);
fetch_demo('i2', 14, 13);
fetch_demo('i3', 14, 14);
fetch_demo('i4', 14, 15);
fetch_demo('i5', 14, 16);
fetch_demo('i6', 14, 17);
fetch_demo('i7', 14, 18);
fetch_demo('i8', 14, 19);
fetch_demo('i9', 14, 20);
fetch_demo('i10', 14, 21);
fetch_demo('i11', 14, 22);
fetch_demo('i12', 14, 23);
fetch_demo('i13', 14, 24);
fetch_demo('i14', 14, 25);
fetch_demo('i15', 14, 26);
fetch_demo('i16', 14, 27);
fetch_demo('i17', 14, 28);
fetch_demo('i18', 14, 29);
fetch_demo('i19', 14, 30);
fetch_demo('i20', 14, 31);

$encode = json_encode($rows);

$update = mysql_query("UPDATE reports SET demo = '$encode' WHERE pid = '$pid'") or die(mysql_error());

echo "success";

?>