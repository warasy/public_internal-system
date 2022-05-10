<?php
require_once("z_DB.php");
mb_regex_encoding("UTF-8");

class LOGIC extends DB{
  /////////////////////////////Task/////////////////////////////
 	// Task insert
	public function InsertTask(){
		$sql = "INSERT INTO task VALUES(null,?,?,?,?)";
		$array = array($_POST["Date"],date("H:i:s"),$_POST["Priority"],$_POST["Text"]);
		parent::executeSQL($sql,$array);
	}

	// Task delete
	public function DeleteTask(){
		$sql = "DELETE FROM task WHERE ID=?";
		$array = array($_POST["id"]);
		parent::executeSQL($sql,$array);
    }

	// Task select
	public function SelectTask(){
		$sql = "SELECT * FROM task ORDER BY Priority,Date DESC,Time DESC";
		$pdoRes = parent::executeSQL($sql,null);
		$taskMain = "<table>\n<tr class=\"b\"><td>Date</td><td>★</td><td>Text</td><td>Delete</td></tr>\n";
		while($row = $pdoRes->fetch(PDO::FETCH_ASSOC)){
			if($row['Priority'] == 0){
				$taskMain .= "<tr class=\"top-task\">";
			}else{
				$taskMain .= "<tr>";
			}
			$taskMain .= "<td>{$row['Date']}</td><td>{$row['Priority']}</td><td>"
						.mb_ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\" target=\"_blank\">\\0</a>", nl2br($row["Text"])) ."</td>\n";
			$taskMain .= <<<eof
			<td class="delete-td"><form method="post" action="">
				<input type="hidden" name="id" value="{$row['ID']}">
				<input type="submit" name="task-delete" value="削除" class="btn" onClick="return CheckDelete()">
			</form></td>
eof;
			$taskMain .= "</tr>\n";
		}
		return $taskMain ."</table>";
	}

	// Task select form
	public function SelectTaskForm(){
		$date = date("Y-m-d");
		$taskForm = <<<eof
		<div class="task-form">
			<form action="" method="post" class="flex">
				<div class="left">
					<label>Date　<input type="date" name="Date" size="20" value="{$date}" class="input-text" required></label><br>
					<label>Priority　<input type="number" name="Priority" size="20" value="2" class="input-text" required></label><br>
				</div>
				<label>Text　<textarea name="Text" class="textarea" required></textarea></label>
				<input type="submit" name="task-insert" value="追加" class="btn">
			</form>
		</div><!-- //task-form -->
eof;
		return $taskForm ."\n";
	}


  /////////////////////////////Memo/////////////////////////////
	// Memo insert
	public function InsertMemo(){
		$sql = "INSERT INTO memo VALUES(null,?,?,?)";
		$array = array($_POST["Date"],$_POST["User"],$_POST["Text"]);
		parent::executeSQL($sql,$array);
	}

	// Memo delete
	public function DeleteMemo(){
		$sql = "DELETE FROM memo WHERE ID=?";
		$array = array($_POST["id"]);
		parent::executeSQL($sql,$array);
	}

	// Memo select
	public function SelectMemo(){
		$sql = "SELECT * FROM memo ORDER BY Date";
		$pdoRes = parent::executeSQL($sql,null);
		$res = "<table>\n<tr class=\"b\"><td>Date</td><td>User</td><td>Text</td><td>Delete</td></tr>\n";
		while($row = $pdoRes->fetch(PDO::FETCH_ASSOC)){
			$res .= "<tr><td>{$row['Date']}</td><td>{$row['User']}</td><td>"
					.mb_ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\" target=\"_blank\">\\0</a>", nl2br($row["Text"])) ."</td>\n";
			$res .= <<<eof
			<td class="delete-td"><form method="post" action="">
				<input type="hidden" name="id" value="{$row['ID']}">
				<input type="submit" name="memo-delete" value="削除" class="btn" onClick="return CheckDelete()">
			</form></td>
eof;
			$res .= "</tr>\n";
		}
		$date = date("Y-m-d");
		$res .= <<<eof
		</table>

		<div id="mf" class="memo-form">
			<h2>Memo Form</h2>
			<form action="" method="post" class="flex">
				<div class="left">
					<label>Date　<input type="date" name="Date" size="20" value="{$date}" class="input-text" required></label><br>
					User　<select name="User" class="select">
						<option value="Inoue">Inoue</option>
						<option value="Takeyama">Takeyama</option>
					</select>
				</div>
				<label>Text　<textarea name="Text" class="textarea" required></textarea></label>
				<input type="submit" name="memo-insert" value="メモる" class="btn">
			</form>
		</div><!-- //memo-form -->
eof;
		return $res ."\n";
	}

  /////////////////////////////CalendarForm/////////////////////////////
	// Calendar insert
	public function InsertCalendar(){
		$sql = "INSERT INTO calendar VALUES(null,?,?,?,?,?,?,?)";
		$array = array(
					$_POST["User"],
					$_POST["DateStart"],
					$_POST["DateEnd"],
					null,
					null,
					$_POST["Title"],
					$_POST["Text"]
				);
		parent::executeSQL($sql,$array);
	}

	// Calendar delete
	public function DeleteCalendar(){
		$sql = "DELETE FROM calendar WHERE ID=?";
		$array = array($_POST["id"]);
		parent::executeSQL($sql,$array);
	}

	// Calendar update
	public function UpdateCalendar(){
		$sql = "UPDATE calendar SET User=?,DateStart=?,DateEnd=?,TimeStart=?,TimeEnd=?,Title=?,Text=? WHERE ID=?";
		$array = array(
					$_POST["user"],
					$_POST["date-start"],
					$_POST["date-end"],
					null,
					null,
					$_POST["title"],
					$_POST["text"],
					$_POST["id"]
				);
		parent::executeSQL($sql,$array);
	}


	// Schedule in Calendar
	private function SelectSchedule($argument1,$argument2,$argument3){
		$today = $argument1;
		$todayMonth = date("m", strtotime($argument3));
		$sql = "SELECT ID, User, DateStart, DateEnd, Title, Text FROM calendar WHERE DateStart IN('$today') ORDER BY DateStart";
		$pdoRes = parent::executeSQL($sql,null);
		$schedule = "";
		$i = 0;

		while($row = $pdoRes->fetch(PDO::FETCH_ASSOC)){
			if($schedule==$row["DateStart"]){
				$i++;
			}else{
				$i = 0;
			}
			$schedule = $row["DateStart"];
			$res[$schedule]["id$i"] = $row["ID"];
			$res[$schedule]["title$i"] = $row["Title"];
			$res[$schedule]["date-start$i"] = $row["DateStart"];
			$res[$schedule]["date-end$i"] = $row["DateEnd"];
			$res[$schedule]["user$i"] = $row["User"];
			$res[$schedule]["text$i"] = $row["Text"];
		}
		$i = 0;

		/********************************************************************************
			▲ここまででcalendarテーブルに登録されている予定全てを多次元配列$resに代入した
			▼ここからSelectScheduleを呼び出した際の引数をもとに予定があるかどうかの確認、あれば随時代入しreturn
		********************************************************************************/

		if(isset($res[$today]["title$i"])){
			$i = 0;
			$daySchedule = "";
			$week1 = date("w", strtotime($res[$today]["date-start$i"]));
			$week2 = date("w", strtotime($res[$today]["date-end$i"]));

			while(isset($res[$today]["title$i"])){
				$dateStartID = "{$res[$today]["date-start$i"]}-{$i}";
				$id = $res[$today]["id$i"];
				$title = $res[$today]["title$i"];
				$dateStart = date("Y-m-d", strtotime($res[$today]["date-start$i"]));
				$dateEnd = date("Y-m-d", strtotime($res[$today]["date-end$i"]));
				$user = $res[$today]["user$i"];

				$text = mb_ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\" target=\"_blank\">\\0</a>", nl2br($res[$today]["text$i"])); 
				$text1 = $res[$today]["text$i"];

				$scheduleMain = <<<eof
								<input type="checkbox" name="ds{$todayMonth}-{$dateStartID}" id="ds{$todayMonth}-{$dateStartID}">
								<label for="ds{$todayMonth}-{$dateStartID}" class="overlay"></label>
								<div class="schedule-content">
									<h3>Schedule details</h3>
									<table>
										<tr><th class="title">Title</th><td>{$title}</td></tr>
										<tr><th class="user">User</th><td>{$user}</td></tr>
										<tr><th class="date-start">Start</th><td>{$dateStart}</td></tr>
										<tr><th class="date-end">End</th><td>{$dateEnd}</td></tr>
										<tr><th class="text">Text</th><td>{$text}</td></tr>
									</table>
									<div class="edit-button">
										<form method="post" action="">
											<input type="hidden" name="id" value="{$id}">
											<input type="submit" name="calendar-delete" value="削除" class="btn" onClick="return CheckDelete()">
										</form>
										<form method="post" action="out-update-calendar.php">
											<input type="hidden" name="id" value="{$id}">
											<input type="submit" name="out-update-calendar" class="btn" value="更新">
										</form>
									</div>
								</div>
eof;
				if($dateStart == $dateEnd){
					$daySchedule .= <<<eof
									<label for="ds{$todayMonth}-{$dateStartID}" class="open">
										<span class="schedule-about non-difference {$user}">{$title}</span>
									</label>
									{$scheduleMain}
eof;
				}else{
					$difference = round((strtotime($res[$today]["date-end$i"]) - strtotime($res[$today]["date-start$i"])) / (60*60*24));
					$weekInt = $week1 + $difference;
					$saturdayDifference = 6 - $week1;
					$sundayDifference = $weekInt - 6;
					$lastdayWeek = date("w", strtotime($argument2));

					if($weekInt>6 && ceil(date("j", strtotime($today))/7)<5 || $weekInt>6 && $lastdayWeek != 6){
						$daySchedule .= <<<eof
									<label for="ds{$todayMonth}-{$dateStartID}" class="open">
										<span class="schedule-about difference{$saturdayDifference} saturday-difference{$saturdayDifference} {$user}">{$title}</span>
										<i class="sunday-difference{$sundayDifference} sunday-start{$week1} {$user}"><span class="bar1"></span><span class="bar2"></span></i>
									</label>
									{$scheduleMain}
eof;
					}else{
						$daySchedule .= <<<eof
									<label for="ds{$todayMonth}-{$dateStartID}" class="open">
										<span class="schedule-about difference{$difference} {$user}">{$title}</span>
									</label>
									{$scheduleMain}
eof;
					}
				}
			$i++;
			}
			return $daySchedule;
		}else{
			return false;
		}
	}


  /////////////////////////////Calendar HTML output/////////////////////////////
	public function EchoCalendar($Y, $m){
		$year = $Y;
		$month = $m;
		$day = date("j");
		$month0 = $month-1;
		$month1 = $month+1;
		$monthPrev = date("m", strtotime("$year-$month0-$day"));
		$monthNext = date("m", strtotime("$year-$month1-$day"));
		$lastDay = date("j", mktime(0, 0, 0, $month + 1, 0, $year));
		$lastDayPrev = date("j", mktime(0, 0, 0, $month, 0, $year));
		$lastDayNext = date("j", mktime(0, 0, 0, $month + 2, 0, $year));
		$weekFirst = date("w", mktime(0, 0, 0, $month, 0, $year));
		$weekLast = date("w", mktime(0, 0, 0, $month, $lastDay, $year));
		$calendar = array();
		$j = 0;
		
		// カレンダー出力ループ
		for ($i = 1; $i < $lastDay + 1; $i++) {
			$week = date("w", mktime(0, 0, 0, $month, $i, $year));
			$i = sprintf("%02d",$i);

		 	if ($i == 1) {
				$prevDay = $lastDayPrev-$week+1;

				for ($s = 1; $s <= $week; $s++) {
					$calendar[$j]["day"] = $prevDay;
					$prevMonth = sprintf("%02d",$month-1);

					if($month==1){
						$days = date($year - 1 ."-12-$prevDay");
					}else{
						$days = date("$year-$prevMonth-$prevDay");
					}
					$calendar[$j]["schedule"] = $this->SelectSchedule($days,null,$days);
				  	$j++; $prevDay++;
				}
			}

			// 今月1日～最終日までの日付を配列変数$calendarにセット
			$calendar[$j]["day"] = $i;
			$days = date("$year-$month-$i");
			$lastday = date("$year-$month-$lastDay");
			$calendar[$j]["schedule"] = $this->SelectSchedule($days,$lastday,null);
			$j++;

			// 月末日以降の来月初旬の範囲
			if ($i == $lastDay) {
				$nextDay = 1;
	 			for ($e = 1; $e <= 6 - $week; $e++){
					$nextDay = sprintf("%02d",$nextDay);
					$calendar[$j]["day"] = $nextDay;
					$nextMonth = sprintf("%02d",$month+1);

					if($month==12){
						$days = date($year + 1 ."-01-$nextDay");
					}else{
						$days = date("$year-$nextMonth-$nextDay");
					}
					$calendar[$j]["schedule"] = $this->SelectSchedule($days,null,$days);
					$j++; $nextDay++;
				}
			}
		}

		echo "\n" ."<h2>Calendar (" .$year ."/" .$month .")</h2>
			<table>
				<tr class=\"day-head\"><th>日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th>土</th></tr>
				<tr>";
					$cnt1 = 0;
					$cnt2 = 0;
					$timestamp = strtotime("now");
					$today = date("Y-m-d", $timestamp);
					foreach($calendar as $key => $value){
					$todayCompare = "{$year}-{$month}-{$value['day']}";
						$cnt1++; $cnt2++;
						if($cnt2 < $weekFirst+2 && $weekFirst != 6){
							if($month==1){
								echo "<td id=" .($year - 1) ."-{$monthPrev}-{$value["day"]}-prev\" class=\"prev\">" .$value["day"] ."<br>" .$value["schedule"] ."</td>";
							}else{
								echo "<td id=\"{$year}-{$monthPrev}-{$value["day"]}-prev\" class=\"prev\">" .$value["day"] ."<br>" .$value["schedule"] ."</td>";
							}
						}elseif($weekFirst == 6 && $cnt2 > $lastDay){
							if($month==12){
								echo "<td id=" .($year + 1) ."-{$monthNext}-{$value["day"]}-next\" class=\"next\">" .$value["day"] ."<br>" .$value["schedule"] ."</td>";
							}else{
								echo "<td id=\"{$year}-{$monthNext}-{$value["day"]}-next\" class=\"next\">" .$value["day"] ."<br>" .$value["schedule"] ."</td>";
							}
						}elseif($weekFirst != 6 && $cnt2 > $lastDay+$weekFirst+1){
							if($month==12){
								echo "<td id=" .($year + 1) ."-{$monthNext}-{$value["day"]}-next\" class=\"next\">" .$value["day"] ."<br>" .$value["schedule"] ."</td>";
							}else{
								echo "<td id=\"{$year}-{$monthNext}-{$value["day"]}-next\" class=\"next\">" .$value["day"] ."<br>" .$value["schedule"] ."</td>";
							}
						}elseif($todayCompare == $today){
							echo "<td id=\"{$year}-{$month}-{$value["day"]}-today\" class=\"today\">" .$value["day"] ."<br>" .$value["schedule"] ."</td>";
						}else{
							echo "<td id=\"{$year}-{$month}-{$value["day"]}\">" .$value["day"] ."<br>" .$value["schedule"] ."</td>";
						}
						if($cnt1 == 7 && $cnt2 < 35){
							echo "</tr>\n<tr>";
							$cnt1 = 0;
						}elseif($cnt1 == 7 && $lastDay > 29 && $weekLast < 2 && $weekFirst > 2){
							echo "</tr>\n<tr>";
						}
					}
				echo "</tr>
			</table>" ."\n";

	}

	public function EchoCalendarForm(){
		$date = date("Y-m-d");
		$res = <<<eof
	<aside id="cf" class="calendar-form">
		<h2>Calendar Form</h2>
		<form action="" method="post">
			<label><input type="date" id="DateStart" name="DateStart" size="20" value="{$date}" class="input-text" required></label><br>
			<label><input type="date" id="DateEnd" name="DateEnd" size="20" value="{$date}" class="input-text" required></label><br>
			<select id="User" name="User" class="select">
				<option value="Inoue">Inoue</option>
				<option value="Fujiwara">Fujiwara</option>
				<option value="Hayami">Hayami</option>
				<option value="Takeyama">Takeyama</option>
			</select>
			<label><input type="text" name="Title" size="20" placeholder="6文字以内で概要を記述　【例】帰省" value="" class="input-text" required></label><br>
			<label><textarea placeholder="ここに出発時間や帰ってくる大体の予定日・時間帯などを書いておく" name="Text" class="textarea"></textarea></label>
			<input type="submit" name="calendar-insert" value="送信" class="btn">
		</form>
	</aside><!-- //calendar-form -->
eof;
			return $res ."\n";
	}




  /******************************************************
		カレンダーの予定詳細から「」をクリックして更新フォームページに遷移した際に遷移先のページに表示するHTMLの生成
  ******************************************************/
  	public function SelectCalendarUpdateOut($outUC){
		$sql = "SELECT * FROM calendar WHERE ID=$outUC";
		$pdoRes = parent::executeSQL($sql,null);
		$res = "";
		while($row = $pdoRes->fetch(PDO::FETCH_ASSOC)){

			$selected_i = ""; $selected_f = "";	$selected_e = "";  $selected_s = "";
			if($row['User'] == "Inoue"){ $selected_i = " selected"; }
			if($row['User'] == "Fujiwara"){ $selected_f = " selected"; }
			if($row['User'] == "Hayami"){ $selected_e = " selected"; }
			if($row['User'] == "Takeyama"){ $selected_s = " selected"; }
			$res .= <<<eof
			<div class="update-form">
				<form action="index.php" method="post">
				<table>
					<tr><th class="title">Title</th><td><input type="text" name="title" value="{$row['Title']}" class="input-text"></td></tr>
					<tr><th class="user">User</th>
						<td><select name="user" class="input-text">
								<option value="Inoue"{$selected_i}>Inoue</option>
								<option value="Fujiwara"{$selected_f}>Fujiwara</option>
								<option value="Hayami"{$selected_e}>Hayami</option>
								<option value="Takeyama"{$selected_s}>Takeyama</option>
						</select></td>
					</tr>
					<tr><th class="date-start">Start</th><td><input type="date" name="date-start" value="{$row['DateStart']}" class="input-text"></td></tr>
					<tr><th class="date-end">End</th><td><input type="date" name="date-end" value="{$row['DateEnd']}" class="input-text"></td></tr>
					<tr><th class="text">Text</th><td><textarea type="text" name="text" class="textarea">{$row['Text']}</textarea></td></tr>
				</table>
				<input type="hidden" name="id" value="$outUC">
				<input type="submit" name="calendar-update" value="内容を変更する" class="btn">
				</form>
			</div>
eof;
		}
		return $res ."\n";
	}

}
?> 