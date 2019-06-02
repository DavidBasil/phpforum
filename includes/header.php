<?php 
// set encoding
header('Content-Type: text/html; charset=UTF-8');

// start session
session_start();

// for testing purpose
$_SESSION['user_id'] = 1;
$_SESSION['user_tz'] = 'America/New_York';

// db connection
require('../mysqli_connect.php');

// check for a new lang id
// then store lang id in session
if(isset($_GET['lid']) && 
	filter_var($_GET['lid'], 
	FILTER_VALIDATE_INT, array('min_range' => 1))){
	$_SESSION['lid'] = $_GET['lid'];
} elseif(!isset($_SESSION['lid'])){
	$_SESSION['lid'] = 1; // default lang id
}

// get words for set language
$q = "select * from words where lang_id = {$_SESSION['lid']}";
$r = mysqli_query($dbc, $q);
if(mysqli_num_rows($r) == 0){
	// invalid lang id
	$_SESSION['lid'] = 1; // default lang id
	$q = "select * from words where lang_id = {$_SESSION['lid']}";
	$r = mysqli_query($dbc, $q);
}

// fetch results
$words = mysqli_fetch_array($r, MYSQLI_ASSOC);

// free the results
mysqli_free_result($r);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php echo $words['title'] ?></title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" type="text/css">
	<style type="text/css" media="screen">
	body {
		padding-top: 50px;
	}
	.starter-template {
		padding: 40px 15px;
		text-align: left;
	}
	</style>
</head>
<body>

	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<a href="index.php" class="navbar-brand">Site Name</a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
				<?php 
					// default links
					echo '<li><a href="index.php">'.$words['home'].'</a></li>
					<li><a href="forum.php">'.$words['forum_home'].'</a></li>';
// links based on login status
					if(isset($_SESSION['user_id'])){
						if(basename($_SERVER['PHP_SELF']) == 'forum.php'){
							echo '<li><a href="post.php">'.$words['new_thread'].'</a></li>';
						}
						echo '<li><a href="logout.php">'.$words['logout'].'</a></li>';
					} else {
						echo '<li><a href="register.php">'.$words['register'].'</a></li>
<li><a href="login.php">'.$words['login'].'</a></li>';
					}
					// retrieve all languages
					echo '<li class="dropdown">
									<a href="forum.php" class="dropdown-toggle" data-toggle="dropdown">
									'.$words['language'].'
									<span class="caret"></span></a>
									<ul class="dropdown-menu">';
					$q = "select lang_id, lang from languages order by lang_eng asc";
					$r = mysqli_query($dbc, $q);
					if(mysqli_num_rows($r) > 0){
						while($menu_row = mysqli_fetch_array($r, MYSQLI_NUM)){
							echo '<li><a href="forum.php?lid='.$menu_row[0].'">'.$menu_row[1].'</a></li>';
						}
					}
					mysqli_free_result($r);
				?>
									</ul></li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="container">
		<div class="starter-template">
