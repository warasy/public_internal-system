<?php
	$res = "";
	require_once("z_function.php");
	$logic = new LOGIC();
	if(isset($_POST["calendar-update"])){ $logic->UpdateCalendar(); }
	$outUC = $_POST['id'];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="robots" content="noindex,nofollow">
	<title>予定更新フォーム　| 社内ツール</title>
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Quattrocento">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="js/min/main.js"></script>
</head>
<body>

<div id="top" class="wrap">
	<div class="contents">
		<h1><a href="./">NewStella Inc.</a></h1>

		<section class="out-update-calendar">
			<h2>Update</h2>
<?php echo $logic->SelectCalendarUpdateOut("$outUC"); ?>
		</section><!-- //Task -->

	</div><!-- //contents -->
</div><!-- //wrap -->
</body>
</html>