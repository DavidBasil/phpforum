<?php 
include('includes/header.php');

// handle form
if($_SERVER['REQUEST_METHOD'] == 'POST'){

	// thread id
	if(isset($_POST['tid']) && filter_var($_POST['tid'], FILTER_VALIDATE_INT,
		array('min_range' => 1)))	{
		$tid = $_POST['tid'];
	} else {
		$tid = FALSE;
	}

	// provide subject if there's no thread id
	if(!$tid && empty($_POST['subject'])){
		$subject = FALSE;
		echo '<p class="bg-danger">Please enter a subject for this post.</p>';
	} elseif (!$tid && !empty($_POST['subject'])){
		$subject = htmlspecialchars(strip_tags($_POST['subject']));
	} else {
		$subject = TRUE;
	}

	// validate body
	if(!empty($_POST['body'])){
		$body = htmlentities($_POST['body']);
	} else {
		$body = FALSE;
		echo '<p class="bg-danger">Please enter a body for this post.</p>';
	}

	if($subject && $body){
		// add messag to db
		if(!$tid){
			$q = "insert into threads(lang_id, user_id, subject) values
				({$_SESSION['lid']}, {$_SESSION['user_id']}, '".mysqli_real_escape_string($dbc, $subject)."')";
			$r = mysqli_query($dbc, $q);
			if(mysqli_affected_rows($dbc) == 1){
				$tid = mysqli_insert_id($dbc);
			} else {
				echo '<p class="bg-danger">System Error!</p>';
			}
		}
		if($tid){
			$q = "insert into posts(thread_id, user_id, message, posted_on) values
				($tid, {$_SESSION['user_id']}, '".mysqli_real_escape_string($dbc, $body)."', utc_timestamp())";
			$r = mysqli_query($dbc, $q);
			if(mysqli_affected_rows($dbc) == 1){
				echo '<p class="bg-success">You post has been saved</p>';
			} else {
				echo '<p class="bg-danger">Your post could not be handled due to a system error.</p>';
			}
		}
	} else {
		include('includes/post_form.php');
	}
	
} else {
	include('includes/post_form.php');
}

include('includes/footer.php');
?>
