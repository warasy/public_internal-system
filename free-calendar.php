<?php
	$res = "";
	require_once("z_function.php");
	$logic = new LOGIC();
	if(isset($_POST["calendar-insert"])){ $logic->InsertCalendar(); } //Calendar insert
	if(isset($_POST["calendar-delete"])){ $logic->DeleteCalendar(); } //Calendar delete
	if(isset($_POST["calendar-update"])){ $logic->UpdateCalendar(); } //Calendar update
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="robots" content="noindex,nofollow">
	<meta http-equiv="refresh" content="7200">
	<title>過去や未来のカレンダー抽出</title>
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Quattrocento">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="js/min/main.js"></script>
</head>
<body>

<div id="top" class="wrap">
	<div class="contents">
	<h1><a href="./">Free Calendar</a></h1>

	<div class="flex">
		<section id="ch" class="calendar-hidden">
			<div id="ajax-calendar" class="calendar">
<?php
				$i=1;
				for($i; $i<12; $i++){
					if($i==6){
						echo "<input type=\"radio\" name=\"switch\" id=\"tab-{$i}\" checked>\n";
					}else{
						echo "<input type=\"radio\" name=\"switch\" id=\"tab-{$i}\">\n";
					}
				}
?>

				<ul class="tablink">
<?php
	if(isset($_POST["calendar-read"])){
					$Y = date($_POST["DateYear"]);
					$m = date('m', strtotime(date("Y-" .$_POST["DateMonth"] ."-1") . "-5month"));
					$i=0;
					for($i; $i<11; $i++){
						$cm = $m + $i;
						$tabNum = $i+1;
						if($cm>12){
							$cm = $cm - 12;
						}
						echo "<li><label for=\"tab-{$tabNum}\"><span>{$cm}月</span></label></li>\n";
					}
	}
?>
				</ul>

				<div class="flex">
<?php
	if(isset($_POST["calendar-read"])){
					$i = -5;
					$monthClass = 1;
					while($i<7){
						$Y = date('Y', strtotime(date($_POST["DateYear"] ."-" .$_POST["DateMonth"] ."-1") . "+{$i}month"));
						$m = date('m', strtotime(date("Y-" .$_POST["DateMonth"] ."-1") . "+{$i}month"));
						echo "<section class=\"calendar-inner month" .$monthClass .'">';
						echo $logic->EchoCalendar($Y, $m);
						$i++; $monthClass++;
						echo "</section><!-- //calendar-inner -->\n";
					}
	}
?>
				</div><!-- //flex -->
			</div><!-- //ajax-calendar  *And*  calendar -->
		</section><!-- //Calendar-hidden -->

		<aside id="cf" class="calendar-form">
			<h2>Read Calendar</h2>
			<form action="" method="post">
				<label><input type="text" id="DateYear" name="DateYear" size="20" value="<?php echo date("Y"); ?>" class="input-text" style="width:50px;float:none;" required>年</label>
				<label><input type="text" id="DateMonth" name="DateMonth" size="20" value="<?php echo date("m"); ?>" class="input-text" style="width:50px;float:none;" required>月</label>
				<input type="submit" name="calendar-read" value="送信" class="btn">
			</form>
		</aside><!-- //calendar-form -->

	</div><!-- //flex -->

	</div><!-- //contents -->
</div><!-- //wrap -->
</body>
</html>