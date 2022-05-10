<?php
	$res = "";
	require_once("z_function.php");
	$logic = new LOGIC();
	$Y = date("Y");
	$m = date('m', strtotime(date("Y-m-1") . "-2month"));
?>

<?php
				$i=1;
				for($i; $i<14; $i++){
					if($i==3){
						echo "<input type=\"radio\" name=\"switch\" id=\"tab-{$i}\" checked>\n";
					}else{
						echo "<input type=\"radio\" name=\"switch\" id=\"tab-{$i}\">\n";
					}
				}
?>

				<ul class="tablink">
<?php
					$i=0;
					for($i; $i<13; $i++){
						$cm = $m + $i;
						$tabNum = $i+1;
						if($cm>12){
							$cm = $cm - 12;
						}
						echo "<li><label for=\"tab-{$tabNum}\"><span>{$cm}月</span></label></li>\n";
					}
?>
				</ul>

				<div class="flex">
<?php
					$i = -1;
					$monthClass = 1;
					while($i<12){
						echo "<section class=\"calendar-inner month" .$monthClass .'">';
						echo $logic->EchoCalendar($Y, $m);
						$Y = date('Y', strtotime(date("Y-m-1") . "+{$i}month"));
						$m = date('m', strtotime(date("Y-m-1") . "+{$i}month"));
						$i++; $monthClass++;
						echo "</section><!-- //calendar-inner -->\n";
					}
?>
				</div><!-- //flex -->