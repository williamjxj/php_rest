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
        <div class="container">
            
 <ul class="breadcrumb">
    <li><a href="/php_rest/">Home</a> <span class="divider">/</span></li>
    <li class="active"><a href="index.php">list all</a> <span class="divider">/</span></li>
</ul>

<?php
  $no = 1; $all = json_decode($all);
?>

<div class="row">
    <div class="span12">
        <p><a href="new" class="btn btn-primary"><i class="icon-plus icon-white"></i> New rest</a>. <span class="alert">For performance reason, <a href="/php_rest/">default list 20 items</a>, use <a href="getAll">getAll</a> to list all record.</span></p>
    </div>
</div>

<div class="row">
    <div class="span12">
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Stat Name</th>
                    <th>Stat Value</th>
                    <th>Updated</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
			<?php foreach ($all as $k => $row) { ?>
                <tr>
                    <td><?php echo $no ++;?></td>
                    <td><strong><a href="editStat/<?=$row->id;?>">#<?=$row->id?></a></strong></td>
                    <td><strong><a class="rest" href="getStats/<?=$row->username;?>"><?=$row->username;?></a></strong></td>
                    <td><strong><a class="rest" href="getLeaderboard/<?=$row->stat_name;?>"><?=$row->stat_name;?></a></strong></td>
                    <td><strong><?=$row->stat_value;?></strong></td>
                    <td> <?=$row->updated;?> </td>
					<td><a href="editStat/<?=$row->id;?>" class="btn btn-mini"><i class="icon-edit"></i> Edit</a> <a href="delete/<?=$row->id;?>" class="btn btn-mini btn-danger delete" data-remote="true" data-method="delete" data-jsonp="(function (u) {location.href = u;})"><i class="icon-remove icon-white"></i> Delete</a> </td>
                </tr>
			  <?php } ?>
            </tbody>
        </table>
        
    </div>
</div>
<hr />
<footer>
	<p>&copy; William Jiang, 2013</p>
</footer>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>-->
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
   $('a.delete').click(function(e) {
     var that = $(this)
     e.preventDefault();
     e.stopPropagation();
	 //alert(e.target.href); //http://dixitruth.com/php_rest/delete/1002
	 $.get(e.target.href, function(data) {
	   if(data == 'Done') {
	     that.closest('tr').fadeOut(500);
	   }
	 });
	 return false;
   });
   /*
   $('table.table-striped').on('click', 'tr>td:nth-child(2)', function(e) {
     e.preventDefault();
     e.stopPropagation();
	 console.log(e.target.href);
	 return false;
   });
   */
 });
</script>
</body>
</html>
