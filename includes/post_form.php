<?php 
// redirect is accessed directly
if(!isset($words)){
	header("Location: ../index.php");
	exit();
}

// display form if the user is logged in
if(isset($_SESSION['user_id'])){
	echo '<form action="post.php" method="post" accept-charset="utf-8">';
	if(isset($tid) && $tid){
		echo '<h3>'.$words['post_a_reply'].'</h3>';
		echo '<input type="hidden" name="tid" value="'.$tid.'">';
	} else {
		echo '<h3>'.$words['new_thread'].'</h3>';
		echo '<div class="form-group">
			<label for="subject">'.$words['subject'].'</label>
			<input type="text" name="subject" class="form-control" size="60" maxlength="100"';
		if(isset($subject)){
			echo "value=\"$subject\"";
		}
		echo '></div>';
	}
	echo '<div class="form-group">
		<label for="subject">'.$words['body'].'</label>
		<textarea name="body" class="form-control" rows="10" cols="60">';
	if(isset($body)){
		echo $body;
	}
	echo '</textarea>';
	echo '<input type="submit" name="submit" class="form-control" value="'.$words['submit'].'"></form>';

} else {
	echo '<p class="bg-warning">You must be logged in to post messages.</p>';
}


?>
