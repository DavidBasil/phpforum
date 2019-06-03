<?php 
include('includes/header.php');
// check for thread id
$tid = FALSE;
if(isset($_GET['tid']) && filter_var($_GET['tid'], FILTER_VALIDATE_INT,
	array('min_range' => 1))){
	$tid = $_GET['tid'];
	// convert the date if the user is logged in
	if(isset($_SESSION['user_tz'])){
		$posted = "convert_tz(p.posted_on, 'utc', '{$_SESSION['user_tz']}')";
	} else {
		$posted = 'p.posted_on';
	}
	$q = "select t.subject, p.message, username, date_format($posted, '%e-%b-%y %l:%i')
	as posted from threads as t left join posts as p using(thread_id) inner join
	users as u on p.user_id = u.user_id where t.thread_id = $tid order by p.posted_on asc";
	$r = mysqli_query($dbc, $q);
	if(!(mysqli_num_rows($r) > 0)){
		$tid = FALSE; // invalid thread id
	}
}

if($tid){
	$printed = FALSE;
	while($messages = mysqli_fetch_array($r, MYSQLI_ASSOC)){
		if(!$printed){
			echo "<h2>{$messages['subject']}</h2>\n";
			$printed = TRUE;
		}
		echo "<p>{$messages['username']} ({$messages['posted']})<br>
		{$messages['message']}</p><br>\n";
	}
	include('includes/post_form.php');
} else {
	echo '<p class="bg-danger">This page has been accessed in error.</p>';
}

include('includes/footer.php');
?>
