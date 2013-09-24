<?php
require "vendor/autoload.php";

$app = new \Slim\Slim();
$app->config(array(
  'debug' => true,
  'templates.path' => './templates'
));

$h = new Helper();

//1. homepage, show all:
$app->get('/', function() use ($app) {
  global $h;
  $all = $h->get_all();
  //echo "<pre>"; print_r($all); echo "</pre>";
  $app->render('list.php', array('all'=>$all));
});

/**
http://dev.slimframework.com/phpdocs/classes/Slim.Http.Request.html
if($app->request->isPost()) {
  //$app->request->post, $app->request->params() not work!
  $obj = json_decode($app->request->getBody());
  echo "<pre>"; print_r($obj->username); echo "</pre>";
  $app->post('/sendStat', 'sendStat');
  $app->get('/name/:stat_name/', function($name) use ($app) { global $h; $app->render('', array());
}
*/

//2. sendStat
$app->post('/sendStat', function() use ($app) {
  $t = $app->request->getBody();
  if((is_string($t) && (is_object(json_decode($t)) || is_array(json_decode($t))))) {
    $t = json_decode($t);
    $user = $t->username;
    $name = $t->stat_name;
    $value = $t->stat_value;
	$html = false;
  }
  else {
    $user = $app->request->post('username');
    $name = $app->request->post('stat_name');
    $value = $app->request->post('stat_value');
	$html = true;
  }

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
  $ret = $h->set_user($user, $name, $value);
  if ($html) {
    $all = $h->get_all();
    $app->render('list.php', array('all'=>$all));
  }
  else {
    echo $ret; //json.
  }
});


// 3. getLeaderboard
$app->get('/getLeaderboard/:stat_name', function($name) use($app) {
  if(empty($name)) {
    echo '{"error":"getLeaderboard needs 1 parameter - [ stat_name ] is empty."}';
    return false;
  }
  global $h;
  $html = false;
  if(preg_match("/-html$/", $name)) {
    $name = preg_replace("/-html$/", '', $name);
	$html = true;
  }
  $ret = $h->get_leaderboard($name);
  if ($html) {
    $app->render('list.php', array('all'=>$ret));
  }
  else {
    echo $ret; //json.
  }
});

// 4. getStats
$app->get('/getStats/:username', function($user) use ($app) {
  if(empty($user)) {
    echo '{"error":"getStats needs 1 parameter - [ username ] is empty."}';
    return false;
  }
  global $h;
  $html = false;
  if(preg_match("/-html$/", $user)) {
    $user = preg_replace("/-html$/", '', $user);
	$html = true;
  }
  $ret = $h->get_stats($user);
  if ($html) {
    $app->render('list.php', array('all'=>$ret));
  }
  else {
    echo $ret; //json.
  }
});

$app->get('/new', function() use ($app) {
  $app->render('new.php');
});
$app->get('/editStat/:id', function($id) use ($app) {
  global $h;
  $ret = $h->get_1($id);
  $app->render('edit.php', array('r'=>$ret));
});


$app->get('/delete/:id', function($id) use ($app) {
  global $h;
  $h->delete_1($id);
});

// 5. not found.
$app->notFound(function() use ($app) {
  echo '{"message": {"error":"Wrong parameter, can not be identified."}}';
  $app->render('404.html');
});


$app->run();
exit;

//////////////////////////////

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
    $value = intval($stat_value);

	 // add unique index: username + stat_name
    $sql = "insert into restapi(username, stat_name, stat_value) values " . 
	 "('".$user."', '".$name."', ".$value.") ON DUPLICATE KEY UPDATE stat_value=".$value;
	// $sql = "UPDATE restapi SET stat_name='" . $name."', stat_value=" . $value .  " WHERE username='".$user."'";

	if(mysql_query($sql)) {
	  return '{"message": {"success":' . $user.$name.$value. '}}';
	}
	else {
	  return '{"message": {"error":'. mysql_error(). '}}';
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
	  return '{"message":{"text": "No corresponging record." }}';
	}
	else {
	  return json_encode($ary);
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
	  return '{"message":{"text": "No corresponging record." }}';
	}
	else {
	  return json_encode($ary);
	}
  }

  public function get_all() {
    $ary = array();
	$sql = "SELECT * FROM restapi ORDER BY updated DESC, username limit 0, 10";
	$res = mysql_query($sql);
	while($row = mysql_fetch_assoc($res)) {
	  array_push($ary, $row);
	}
	mysql_free_result($res);
    return json_encode($ary);
  }

  public function get_1($id) {
    $ary = array();
	$sql = "SELECT * FROM restapi where id=".$id;
	$res = mysql_query($sql);
	$row = mysql_fetch_assoc($res);
	mysql_free_result($res);
    return $row;
  }

  public function delete_1($id) {
    $ary = array();
	$sql = "DELETE FROM restapi where id=".$id;
	if(mysql_query($sql)) {
	  echo "Done";
	}
  }
}
