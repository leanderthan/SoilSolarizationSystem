<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link href="Content/bootstrap.min.css" rel="stylesheet" />
		<script src="Scripts/jquery-1.10.2.min.js"></script>
		<script src="Scripts/bootstrap.min.js"></script>
        <title>SSS | Post</title>
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
					    <a href="during.php">
						<button type="button" class="btn btn-link">DURING SOLARIZATION</button>
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
			<center>
			<div>
				<b><u>POST-SOLARIZATION RECORD</u></b>
				<br />
<?php 
include ('dbcon.php');
$str = "POST";
$sql = "SELECT * FROM solsys WHERE recordType = '$str' ORDER BY recordDate DESC LIMIT 1";
$result = mysqli_query($conn, $sql);
$resultCount = mysqli_num_rows($result);
$error = "No entry found. Please perform recording.";

if($resultCount==0){ ?>              
                <center><i><?= $error ?></i></center>
      <?php       
            }else{
                while($row = $result->fetch_assoc()){
      ?>   	
      			<center><i><?= $row['recordDate'] ?></i></center>
				<br />
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
					<tr>
						<td rowspan="3"><img src="chemical.png" height="100" width="65" class="img-fluid"/></td>
						<th>SOIL REACTION (pH)</th>
						<td rowspan="3"><img src="soil.png" height="100" width="100" class="img-fluid"/></td>
						<th>SOIL SALINITY (EC)</th>
					</tr>
					<tr>
						<td style="color: #8B0000; ">pH: <b><?= $row['ph']?></b></td>
						<td style="color: #8B0000;">EC (m S/cm): <b><?= $row['ec']?></b></td>
					</tr>
					<tr>
						<td>Status: <b><?= $row['soilStatus']?></b></td>
						<td>Class: <b><?= $row['salClass']?></b></td>
					</tr>
				</table>
				<br />
				<?php 
					if ((($row['ph'] >= 5.5) && ($row['ph'] <= 7.0)) && (($row['ec'] >= 1.0) && ($row['ec'] <= 3.0))){
				?>
					<center><b style="color: #8B0000;"><i><u>Soil is IN OPTIMUM PH/EC RANGE and might be GOOD for planting.</u></i></b></center>
				<?php
					} else if ((($row['ph'] < 5.5) || ($row['ph'] > 7.0)) || (($row['ec'] < 1.0) || ($row['ec'] > 3.0))){
				?>
					<center><i><b style="color: #8B0000;"><u>Soil is NOT IN OPTIMUM PH/EC RANGE and might be POOR for planting.</u></b><br />
					<i>Optimum pH: 5.50 - 7.00<br />
					Optimum EC: 1 - 3 mS/cm</i>
					</center>
			<?php 
					}
				}
			}
			?>
				<br />
				</div>
			</center>
		</div>
    </body>
</html>
