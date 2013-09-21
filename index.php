<?php
require "./vender/autoload.php";


$app = new \Slim\Slim();

$app->get('/hello/:name', function($name) {
  echo "Hello, $name";
});


$app->get('/sendStat/:username/:stat_name/:stat_value', 'sendStat');
$app->get('/getLeaderboard/:stat_name', 'getLeaderboard');
$app->get('/getStats/:username', 'getStats');

$app->run();


function sendStat() {
}


function getLeaderboard() {
}

function getStats() {
}
