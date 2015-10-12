<!--
PHPMYSQL version 1.0
Author : Heley Chen
Date : 2015-10-12
MIT License
-->
<?php
//数据库配置
//主机名
define("SERVERNAME", "localhost");
//MySQL用户名
define("USERNAME", "root");
//MySQL密码
define("PASSWORD", "");
//数据库
define("DATABASE", "test");
//端口
define("PORT", "3306");
//PHPMYSQL Libs
class connection {
	var $conn;
	var $charset = "utf8";
	var $sql;
	//构造：连接数据库
	function connection($sn, $un, $pwd, $db, $p) {
		$this -> conn = new mysqli($sn, $un, $pwd, $db, $p);
		if ($this -> conn -> connect_error) {
			die("Connection failed: " . $this -> conn -> connect_error);
		}
		$this -> conn -> set_charset($this -> charset);
		$this -> sql = "";
	}

	//析构：关闭数据库
	function close() {
		$this -> conn -> close();
	}

	//查询
	function query() {
		//$res = mysqli_multi_query($this -> conn, $this -> sql);
		$res = $this -> conn -> query($this -> sql);
		$sql = "";
		return res2arr($res);
	}

	//插入
	function insert($table_name, $columns, $values) {
		$this -> sql .= "INSERT INTO " . $table_name . " (";
		for ($i = 0, $len = count($columns); $i < $len; $i++) {
			if ($i != 0)
				$this -> sql .= ",";
			$this -> sql .= "`" . $columns[$i] . "`";
		}
		$this -> sql .= ") VALUES (";
		for ($i = 0, $len = count($values); $i < $len; $i++) {
			if ($i != 0)
				$this -> sql .= ",";
			$this -> sql .= "'" . $values[$i] . "'";
		}
		$this -> sql .= ");";
		return $this;
	}

	//读取
	function select($table_name, $columns, $where = "1") {
		$this -> sql .= "SELECT ";
		for ($i = 0, $len = count($columns); $i < $len; $i++) {
			if ($i != 0)
				$this -> sql .= ",";
			$this -> sql .= $columns[$i];
		}
		$this -> sql .= " FROM " . $table_name . " WHERE " . $where . ";";
		return $this;
	}

	//修改
	//*忽略values多于columns的部分
	function update($table_name, $columns, $values, $where) {
		$this -> sql .= "UPDATE " . $table_name . " SET ";
		for ($i = 0, $len = count($columns); $i < $len; $i++) {
			if ($i != 0)
				$this -> sql .= ",";
			$this -> sql .= $columns[$i] . "=" . $values[$i];
		}
		$this -> sql .= " WHERE " . $where . ";";
		return $this;
	}

	//删除
	function delete($table_name, $where) {
		$this -> sql .= "DELETE FROM " . $table_name . " WHERE " . $where . ";";
		return $this;
	}

}

//SQL 结果转化为二维数组
function res2arr($result) {
	$res = array();
	if ($result -> num_rows > 0) {
		// 输出每行数据
		while ($row = $result -> fetch_assoc()) {
			array_push($res, $row);
		}
	}
	return $res;
}
?>