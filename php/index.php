<!--PAGE REDIRECTION-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link href="Content/bootstrap.min.css" rel="stylesheet" />
		<script src="Scripts/jquery-1.10.2.min.js"></script>
		<script src="Scripts/bootstrap.min.js"></script>
        <title>SSS | Home</title>
    </head>
    <body>
	<div class="container">
		<br /><br /><br />
		<center>
<?php
include ('dbcon.php');
$str = "DUR";
$sql = "SELECT * FROM solsys WHERE recordType = '$str'";
$result = mysqli_query($conn, $sql);
$resultCount = mysqli_num_rows($result);
if ($resultCount==0){
	$msg = "PRE";
	$qry = "SELECT * FROM solsys WHERE recordType = '$msg'";
	$rt = mysqli_query($conn, $qry);
	$rtCt = mysqli_num_rows($rt);
	if ($rtCt==0){
		$week = "PRE-SOLARIZATION";
		$mess = "Not yet started. Insert sensors and press middle button.";
	} else {
		$week = "DURING SOLARIZATION Week #1";
		$mess = "Ongoing";
	}
} else if ($resultCount<28 && $resultCount != 0) {
	$week = "DURING SOLARIZATION Week #1";
	$mess = "Ongoing";
} else if ($resultCount>=28 && $resultCount <56) {
	$week = "DURING SOLARIZATION Week #2";
	$mess = "Ongoing";
} else if ($resultCount>=56) {
	$msg1 = "POST";
	$que = "SELECT * FROM solsys WHERE recordType = '$msg1'";
	$res = mysqli_query($conn, $que);
	$resCt = mysqli_num_rows($res);
	if ($resCt==0){
		$week = "POST-SOLARIZATION";
		$mess = "Remove mulch, insert PH/EC sensors, and press middle button.";
	} else {
		$week = "SOLARIZATION IS DONE";
		$mess = "Archive records to start solarizing again.";
	}
} 
?>
			<img src="logo3.png" height="120" width="220" class="img-fluid"/>
			
			<br /><br />
			<b><?= $week ?></b><br />
			<i><?= $mess ?></i><br />
			<br />
			<a href="presoil.php"><button type="button" class="btn btn-link">PRE-SOLARIZATION</button></a>
			<br />
			<a href="during.php"><button type="button" class="btn btn-link">DURING SOLARIZATION</button></a>
			<br />
			<a href="postsoil.php"><button type="button" class="btn btn-link">POST-SOLARIZATION</button></a>
			<br />
			<a href="history.php"><button type="button" class="btn btn-link">HISTORY</button></a>
			<br /><br /><br />
			<sub><a href="manual.pdf" target=_blank>USER GUIDE</a></sub>
		</center>
	</div>
    </body>
</html>
