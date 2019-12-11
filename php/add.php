<?php

include ('dbcon.php');
$pre = "PRE";
$dur = "DUR";
$post = "POST";

$sql = "INSERT archive SELECT * FROM solsys";
$conn->query($sql);

$query = "DELETE FROM solsys WHERE recordType = '$pre'";
$conn->query($query);

$qry = "DELETE FROM solsys WHERE recordType = '$dur'";
$conn->query($qry);

$str = "DELETE FROM solsys WHERE recordType = '$post'";
$conn->query($str);

header("location: history.php");
?>
