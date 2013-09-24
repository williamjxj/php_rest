<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>REST API - sendStat, getLeaderboard, getStats</title>
<base href="/php_rest/">
<meta name="description" content="William Jiang on 09-22,2013">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
</head>
<body>
<h1 align="center">REST API - PHP Slim + MySQL + jQuery </h1>
<div class="navbar">
  <div class="navbar-inner">
    <div class="container"> <a class="brand" href="/php_rest/">PHP + Slim Framework + MySQL + jQuery REST APIs</a> </div>
  </div>
</div>
<div class="container">
  <ul class="breadcrumb">
    <li><a href="/php_rest/">Home</a> <span class="divider">/</span></li>
    <li class="active">Create New<span class="divider">/</span></li>
	<li><div id="msg" class="alert" style="display:none;"></div></li>
  </ul>
  <div class="row">
    <div class="span12">
      <form action="sendStat" method="POST" id="new-form" class="form-horizontal">
        <div class="control-group">
          <label for="username" class="control-label">Username</label>
          <div class="controls">
            <input name="username" id="username" type="text" value="" />
          </div>
        </div>
        <div class="control-group">
          <label for="stat_name" class="control-label">Stat name</label>
          <div class="controls">
            <input name="stat_name" id="stat_name" type="text" value="" />
          </div>
        </div>
        <div class="control-group">
          <label for="stat_value" class="control-label">Stat value</label>
          <div class="controls">
            <input name="stat_value" id="stat_value" type="text" value="" />
          </div>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> Create rest</button>
          or <a href="/php_rest" class="btn">Cancel</a> </div>
      </form>
    </div>
  </div>
  <hr />
  <footer>
    <p>&copy; William Jiang, 2013</p>
  </footer>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
 $(function() {
   $('table.table-striped').on('click', 'a.rest', function(e) {
     e.preventDefault();
     e.stopPropagation();
     var t=e.target.href;
     if(! /\-html$/.test(t)) {
       t += '-html';
     }
     document.location.href=t;
     return false;
   });
   $('table.table-striped').on('click', 'tr>td:nth-child(2)', function(e) {
     e.preventDefault();
     e.stopPropagation();
     console.log(e.target.href);
     return false;
   });
   $('form#new-form').submit(function() {
     var msg;
     if($('#username').val()=='') {
	   msg = '<strong>Please input username.</strong>';
	   $('#username').focus();
	   $('#msg').html(msg).fadeIn(200);
	   return false;
	 }
     if($('#stat_name').val()=='') {
	   msg = '<strong>Please input stat_name.</strong>';
	   $('#stat_name').focus();
	   $('#msg').html(msg).fadeIn(200);
	   return false;
	 }
     if($('#stat_value').val()=='') {
	   msg = '<strong>Please input stat_value.</strong>';
	   $('#stat_value').focus();
	   $('#msg').html(msg).fadeIn(200);
	   return false;
	 }
	 return true;
   });
 });
</script>
</body>
