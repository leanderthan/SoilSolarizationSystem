<!DOCTYPE html>
<!-- retrieve latest DUR entries-->
<html>
    <head>
        <meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link href="Content/bootstrap.min.css" rel="stylesheet" />
		<script src="Scripts/jquery-1.10.2.min.js"></script>
		<script src="Scripts/bootstrap.min.js"></script>
        <title>SSS | During</title>
    </head>
    <body>
		<div class="container">
			<br />
			<table>
				<tr>
					<td rowspan="3"><a href="index.php"><img src="logo3.png" height="120" width="220" class="img-fluid"/><a></td>
					<td>
					    <a href="presoil.php">
						<button type="button" class="btn btn-link">PRE-SOLARIZATION</button>
					    </a>
					</td>
				</tr>
				<tr>
				    <td>
					    <a href="postsoil.php">
						<button type="button" class="btn btn-link">POST-SOLARIZATION</button>
					    </a>
				    </td>
				</tr>
				<tr>
				    <td>
					    <a href="history.php">
						<button type="button" class="btn btn-link">HISTORY</button>
					    </a>
				    </td>
				</tr>
			</table>
				<br />
				
			<center>
			<div id="pres">
				<b><u>DURING SOLARIZATION RECORD</u></b>
				<br /><br />
<?php 
include ('dbcon.php');
$str = "DUR";
$sql = "SELECT recordDate, moisture, temperature FROM solsys WHERE recordType = '$str' ORDER BY recordDate DESC LIMIT 1";
$result = mysqli_query($conn, $sql);
$resultCount = mysqli_num_rows($result);
$error = "No entries found.";

if($resultCount==0){ ?>              
                <center><i><?= $error ?></i></center>
      <?php       
            }else{
                while($row = $result->fetch_assoc()){
      ?>       	
				<table border="1" cellpadding="10">
					<tr>
						<td rowspan="2"><img src="moist.png" height="100" width="65" class="img-fluid"/></td>
						<th>SOIL MOISTURE</th>
						<td rowspan="2"><img src="temperature.png" height="100" width="100" class="img-fluid"/></td>
						<th>SOIL TEMPERATURE</th>
					</tr>
					<tr>
						<td style="color: #8B0000;"><b><?= $row['moisture']?> %</b></td>
						<td style="color: #8B0000;"><b><?= $row['temperature']?> C</b></td>
					</tr>
				</table>
				<br />
				<p><i>As of <u><?= $row['recordDate']?></u></i></p> <!--date time nung entry-->
		<?php
				}
			}
		?>
		<br />
			</div>
			</center>
		</div>
    </body>
</html>
