<?php
require "vendor/autoload.php";

// require 'Slim/Slim.php';
// \Slim\Slim::registerAutoloader();

$h = new Helper();

$app = new \Slim\Slim();

$app->get('/', function() {
  echo "this is a test for slim framework, it works! Don't forget to add .htaccess in the app root.";
});

$app->get('/hello/:name', function($name) {
  echo "Hello, $name";
});


$app->get('/sendStat/:username/:stat_name/:stat_value', 'sendStat');
$app->get('/getLeaderboard/:stat_name', 'getLeaderboard');
$app->get('/getStats/:username', 'getStats');

$app->run();


function sendStat($user, $name, $value) {
 $h->get_total($user);
}


function getLeaderboard($name) {
}

function getStats($user) {
}


class Helper
{
  var $adb;
  public function __construct() {
	$this->adb = array(
	  'host' => 'localhost',
	  'user' => 'rest',
	  'pass' => 'ldrly.com',
	  'db'   => 'rest'
	);
	$link = mysql_pconnect($this->adb['host'], $this->adb['user'], $this->adb['pass']) or die(mysql_error());
	mysql_select_db($this->adb['db'], $link);
	mysql_query("SET NAMES 'utf8'", $link);
  }

  public function get_total($user) {
	  $sql = "select count(*) from restful where lower(username)='" . mysql_real_escape_string(trim(strtolower($user)))."'";
	  $result = mysql_query($sql);
	  $num = mysql_fetch_row($result);
	  mysql_free_result($result);
	  return $num[0];
  }

  public function set_user($username, $stat_name, $stat_value) {
    $user = mysql_real_escape_string(trim(strtolower($username)));
    $name = mysql_real_escape_string(trim(strtolower($stat_name)));
    $value = int($stat_name);
    $sql = "insert into restful(username, stat_name, stat_value) values " . 
	 "('".$user."', '".$name."', ".$value." ON DUPLICATE KEY UPDATE stat_value=stat_value+".$value;

	$sql = "UPDATE restful SET stat_name='" . $name."', stat_value=" . $value . 
	  " WHERE username='".$user."'";

	mysql_query($sql);
  }

  public function get_leaderboard($stat) {
    $ary = array();
	$sql = "select * from restful where lower(stat_name)='" .
	    mysql_real_escape_string(trim(strtolower($stat)))."' order by stat_value desc";
	$res = mysql_query($sql);
	while($row = mysql_fetch_assoc($res)) {
	  array_push($ary, $row);
	}
	mysql_free_result($res);
	return $ary;
  }

  public function get_stats($user) {
    $ary = array();
	$sql = "select * from restful where lower(username)='" .
	  mysql_real_escape_string(trim(strtolower($user)))."' order by updated desc";
	$res = mysql_query($sql);
	while($row = mysql_fetch_assoc($res)) {
	  array_push($ary, $row);
	}
	mysql_free_result($res);
	return json_encode($ary);
  }

}
