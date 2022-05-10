<?php
class DB{
	private $USER = "{DBユーザー名}";
	private $PW = "{DBパスワード}";
	private $dnsinfo = "mysql:dbname={DB名};host={ホスト名};charset=utf8";
	private function Connectdb(){
		try{
			$pdo = new PDO($this->dnsinfo,$this->USER,$this->PW);
			return $pdo;
		}catch(Exception $e){
			return $e;
		}
	}

	public function executeSQL($sql,$array){
		try{
			if(!$pdo = $this->Connectdb()) return false;
			$stmt = $pdo->prepare($sql);
			$stmt->execute($array);
			return $stmt;
		}catch(Exception $e){
			return false;
		}
	}
}
?> 