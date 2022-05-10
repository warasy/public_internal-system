<?php
	$res = "";
	require_once("z_function.php");
	$logic = new LOGIC();
	if(isset($_POST["task-insert"])){	$logic->InsertTask(); } 	//Task insert
	if(isset($_POST["task-delete"])){ $logic->DeleteTask(); }	 	//Task delete
	if(isset($_POST["memo-insert"])){ $logic->InsertMemo(); } 	//Memo insert
	if(isset($_POST["memo-delete"])){ $logic->DeleteMemo(); } 	//Memo delete
	if(isset($_POST["calendar-insert"])){ $logic->InsertCalendar(); } //Calendar insert
	if(isset($_POST["calendar-delete"])){ $logic->DeleteCalendar(); } //Calendar delete
	if(isset($_POST["calendar-update"])){ $logic->UpdateCalendar(); } //Calendar update
	$Y = date("Y");
	$m = date('m', strtotime(date("Y-m-1") . "-2month"));
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="robots" content="noindex,nofollow">
	<meta http-equiv="refresh" content="7200">
	<title>社内ツール ver1</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Quattrocento">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="js/min/main.js"></script>
</head>
<body>

<div id="top" class="wrap">
	<div class="contents">
	<h1><a href="./">NewStella Inc.</a></h1>


	<div class="flex">
		<section id="ch" class="calendar-hidden">
			<div id="ajax-calendar" class="calendar">
<?php
				//月を切り替える際のラジオボタン。
				$i=1;
				for($i; $i<25; $i++){
					if($i==3){ //現在の月をデフォルトでチェックしておく。
						echo "<input type=\"radio\" name=\"switch\" id=\"tab-{$i}\" checked>\n";
					}else{
						echo "<input type=\"radio\" name=\"switch\" id=\"tab-{$i}\">\n";
					}
				}
?>

				<ul class="tablink">
<?php
				//カレンダーの上に配置している月別ボタン。
					$i=0;
					for($i; $i<24; $i++){
						$cm = $m + $i;
						$tabNum = $i+1;
						if($cm>12 && $cm<25){
							$cm = $cm - 12;
						}elseif($cm>24){
							$cm = $cm - 24;
						}
						echo "<li><label for=\"tab-{$tabNum}\"><span>{$cm}月</span></label></li>\n";
					}
?>
				</ul>

				<div class="flex">
<?php
					//カレンダー本体
					$i = -2;
					$monthClass = 1;
					while($i<22){
						$Y = date('Y', strtotime(date("Y-m-1") . "+{$i}month"));
						$m = date('m', strtotime(date("Y-m-1") . "+{$i}month"));
						echo "<section class=\"calendar-inner month" .$monthClass .'">';
						echo $logic->EchoCalendar($Y, $m);
						$i++; $monthClass++;
						echo "</section><!-- //calendar-inner -->\n";
					}
?>
				</div><!-- //flex -->
			</div><!-- //ajax-calendar  *And*  calendar -->
		</section><!-- //Calendar-hidden -->
<?php echo $logic->EchoCalendarForm(); ?>
	</div><!-- //flex -->

	<div class="flex" style="margin-bottom:60px;">
		<section class="task">
			<h2>Tasks</h2>
			<div id="ajax-task">
				<?php echo $logic->SelectTask(); ?>
			</div>
			<?php echo $logic->SelectTaskForm(); ?>
		</section><!-- //Task -->

		<section class="links">
			<h2>Links</h2>
			<ul class="list-none">
				<li>・<a href="https://newstella.co.jp/" target="_blank">株式会社ニューステラ</a></li>
				<li>・<a href="./free-calendar.php" target="_blank">特定の月のカレンダー表示</a></li>
				<li>・<a href="https://www.google.co.jp/" target="_blank">日常的に使うWebサイトのリンク</a></li>
				<li>・<a href="https://www.google.co.jp/" target="_blank">日常的に使うWebサイトのリンク</a></li>
				<li>・<a href="https://www.google.co.jp/" target="_blank">日常的に使うWebサイトのリンク</a></li>
				<li>・<a href="https://www.google.co.jp/" target="_blank">日常的に使うWebサイトのリンク</a></li>
				<li>・<a href="https://www.google.co.jp/" target="_blank">日常的に使うWebサイトのリンク</a></li>
				<li>・<a href="https://www.google.co.jp/" target="_blank">日常的に使うWebサイトのリンク</a></li>
			</ul>
		</section><!-- //Tool -->
	</div><!-- //flex -->

	<section class="memo">
		<h2>Memo</h2>
		<?php echo $logic->SelectMemo(); ?>
	</section><!-- Memo -->

	<p class="page-top">
		<a href="#top" class="top"></a>
		<a href="#mf" class="bottom"></a>
	</p>

	</div><!-- //contents -->
</div><!-- //wrap -->
</body>
</html>