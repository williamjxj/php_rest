<?php
require "vendor/autoload.php";

// require 'Slim/Slim.php';
// \Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->config(array(
  'debug' => true,
  'templates.path' => './templates'
));

echo $app->config['templates.path'];

$h = new Helper();

$app->get('/', function() {
  global $h;
  echo "slim framework works!\n";
  echo "Total record is [" . $h->get_total() . "]\n";
});


// http://dev.slimframework.com/phpdocs/classes/Slim.Http.Request.html
/*
if($app->request->isPost()) {
  //$app->request->post, $app->request->params() not work!
  $obj = json_decode($app->request->getBody());
  echo "<pre>"; print_r($obj->username); echo "</pre>";
  $app->post('/sendStat', 'sendStat');
}
*/

$app->post('/sendStat', function() use ($app) {
   echo "AAAAAAAAAAAAAA";
});

$app->get('/getLeaderboard/:stat_name', 'getLeaderboard');

$app->get('/getStats/:username', 'getStats');

$app->notFound(function() use ($app) {
  echo '{"message": {"error":"Wrong parameter, can not be identified."}}';
  $app->render('404.html');
});


$app->run();


function sendStat() {
  echo "BBBBBBBBBBBBBBBB";
  return false;

  $req = \Slim\Http\Request();
  $t = json_decode($req->getBody());
  $user = $t->username;
  $name = $t->stat_name;
  $value = $t->stat_value;

  echo "AAAAAAAAAAAAAAAAA\n"; return false;

  // validate from server-side, for curl/web-service access.
 if(empty($user)) {
   echo '{"error":"sendStat need 3 post parameters - the first [ username ] is empty."}';
   return false;
 }
 if(empty($name)) {
   echo '{"error":"sendStat need 3 post parameters - the second [ stat_name ] is empty."}';
   return false;
 }
 if(empty($value)) {
   echo '{"error":"sendStat need 3 post parameters - the third [ stat_value ] is empty."}';
   return false;
 }
 global $h;
 $h->set_user($user, $name, $value);
}


function getLeaderboard($name) {
  if(empty($name)) {
    echo '{"error":"getLeaderboard needs 1 parameter - [ stat_name ] is empty."}';
    return false;
  }
  global $h;
  $h->get_leaderboard($name);
}

function getStats($user) {
  if(empty($user)) {
    echo '{"error":"getStats needs 1 parameter - [ username ] is empty."}';
    return false;
  }
  global $h;
  $h->get_stats($user);
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

  public function get_total($user='') {
      if(!empty($user)) {
	  	$u = mysql_real_escape_string(strtolower($user));
	    $sql = "select count(*) from restapi where lower(username)='" . $u ."'";
	  }
	  else {
	    $sql = "select count(*) from restapi"; 
	  }
	  $result = mysql_query($sql);
	  $num = mysql_fetch_row($result);
	  mysql_free_result($result);
	  return $num[0];
  }

  public function set_user($username, $stat_name, $stat_value) {
    $user = mysql_real_escape_string(strtolower($username));
    $name = mysql_real_escape_string(strtolower($stat_name));
    $value = int($stat_name);
	 // add unique index: username + stat_name
    $sql = "insert into restapi(username, stat_name, stat_value) values " . 
	 "('".$user."', '".$name."', ".$value." ON DUPLICATE KEY UPDATE stat_value=stat_value+".$value;
	// $sql = "UPDATE restapi SET stat_name='" . $name."', stat_value=" . $value .  " WHERE username='".$user."'";

	echo $sql . "\n";
	return false;

	if(mysql_query($sql)) {
	  echo '{"message": {"success":' . $user.$name.$value. '}}';
	}
	else {
	  echo '{"message": {"error":'. mysql_error(). '}}';
	}
  }

  public function get_leaderboard($stat) {
    $ary = array();
	$sql = "select * from restapi where lower(stat_name)='" .
	    mysql_real_escape_string(strtolower($stat))."' order by stat_value desc";
	$res = mysql_query($sql);
	while($row = mysql_fetch_assoc($res)) {
	  array_push($ary, $row);
	}
	mysql_free_result($res);
	if(empty($ary)) {
	  echo '{"message":{"text": "No corresponging record." }}';
	}
	else {
	  echo json_encode($ary);
	}
  }

  public function get_stats($user) {
    $ary = array();
	$sql = "select * from restapi where lower(username)='" .
	  mysql_real_escape_string(strtolower($user))."' order by stat_value desc";
	$res = mysql_query($sql);
	while($row = mysql_fetch_assoc($res)) {
	  array_push($ary, $row);
	}
	mysql_free_result($res);

	if(empty($ary)) {
	  echo '{"message":{"text": "No corresponging record." }}';
	}
	else {
	  echo json_encode($ary);
	}
  }

}
