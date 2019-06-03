<?php 
include('includes/header.php');

// if user is logged in and has a time zone,
// convert date and time
if(isset($_SESSION['user_tz'])){
	$first = "convert_tz(p.posted_on, 'UTC', '{$_SESSION['user_tz']}')";
	$last = "convert_tz(p.posted_on, 'UTC', '{$_SESSION['user_tz']}')";
} else {
	$first = 'p.posted_on';
	$last = 'p.posted_on';
}

// retrieve all threads
$q = "select t.thread_id, t.subject, username, count(post_id) - 1 as responses,
max(date_format($last, '%e-%b-%y %l:%i %p')) as last, 
min(date_format($first, '%e-%b-%y %l:%i %p')) as first from threads
as t inner join posts as p using(thread_id) 
inner join users as u on t.user_id = u.user_id where t.lang_id = {$_SESSION['lid']}
group by (p.thread_id) order by last desc";
$r = mysqli_query($dbc, $q);
if(mysqli_num_rows($r) > 0){
	// create a table
	echo '<table class="table table-striped">
		<thead>
			<tr>
				<th>'.$words['subject'].'</th>
				<th>'.$words['posted_by'].'</th>
				<th>'.$words['posted_on'].'</th>
				<th>'.$words['replies'].'</th>
				<th>'.$words['latest_reply'].'</th>
			</tr>
		</thead>
		<tbody>';
	// get each thread
while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
	echo '<tr>
		<td><a href="read.php?tid='.$row['thread_id'].'">'.$row['subject'].'</a></td>
		<td>'.$row['username'].'</td>
		<td>'.$row['first'].'</td>
		<td>'.$row['responses'].'</td>
		<td>'.$row['last'].'</td>
	</tr>';
	}
	echo '</tbody></table>';
} else {
	echo '<p>There are currently no messages in this forum.</p>';
}

include('includes/footer.php');
?>
