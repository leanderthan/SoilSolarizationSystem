<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link href="Content/bootstrap.min.css" rel="stylesheet" />
		<script src="Scripts/jquery-1.10.2.min.js"></script>
		<script src="Scripts/bootstrap.min.js"></script>
        <title>SSS | History</title>
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
					    <a href="postsoil.php">
						<button type="button" class="btn btn-link">POST-SOLARIZATION</button>
					    </a>
				    </td>
				</tr>
			</table>
				<br />
			<center>
			<div id="pres">
				<b><u>HISTORY</u></b>
				<br />
				<a href="archive.php">
			    <button type="button" class="btn btn-link">VIEW ARCHIVE</button>
			</a>
	
<?php
include ('dbcon.php');
$sql = "SELECT * FROM solsys ORDER BY recordDate ASC";
$result = mysqli_query($conn, $sql);
$resultCount = mysqli_num_rows($result);
$error = "No records found";
$post = "POST";
if($resultCount==0){ ?>              
                <center><i><?= $error ?></i></center>
      <?php       
            }else{
		$qry = "SELECT * FROM solsys WHERE recordType ='$post'";
		$res = mysqli_query($conn, $qry);
		$resCt = mysqli_num_rows($res);
		if($resCt==0){
		    ?>
		    <br>
			<table border="1" cellpadding="10">
					<tr>
						<th>#</th>
						<th>DATETIME</th>
						<th>PRE/DUR/POST</th>
						<th>MOISTURE (%)</th>
						<th>TEMPERATURE (C)</th>
						<th>SOIL PH</th>
						<th>STATUS</th>
						<th>SOIL EC (m S/cm)</th>
						<th>SALINITY CLASS</th>
						<th>REMARKS</th>
					</tr>
			<?php
			$i=0;
			while($row = $result->fetch_assoc()){

      ?>              
			<tr>
			<td><?= $i?></td>
			<td><?= $row['recordDate']?></td>
			<td><?= $row['recordType']?></td>
			<td><?= $row['moisture']?></td>
			<td><?= $row['temperature']?></td>
			<td><?= $row['ph']?></td>
			<td><?= $row['soilStatus']?></td>
			<td><?= $row['ec']?></td>
			<td><?= $row['salClass']?></td>
			<td><?= $row['remarks']?></td>
			</tr>
		    <?php
		    $i++;
		    } 
		}else if ($resCt !=0){
		?>
		<a href="add.php">
			<button type="button" class="btn btn-link">ARCHIVE RECORDS</button>
			</a>
		<br>
			
				<table border="1" cellpadding="10">
					<tr>
						<th>#</th>
						<th>DATETIME</th>
						<th>PRE/DUR/POST</th>
						<th>MOISTURE (%)</th>
						<th>TEMPERATURE (C)</th>
						<th>SOIL PH</th>
						<th>STATUS</th>
						<th>SOIL EC (m S/cm)</th>
						<th>SALINITY CLASS</th>
						<th>REMARKS</th>
					</tr>
					<?php
		$i=0;
                while($row = $result->fetch_assoc()){

      ?>              
                <tr>
		    <td><?= $i?></td>
                    <td><?= $row['recordDate']?></td>
                    <td><?= $row['recordType']?></td>
                    <td><?= $row['moisture']?></td>
                    <td><?= $row['temperature']?></td>
                    <td><?= $row['ph']?></td>
                    <td><?= $row['soilStatus']?></td>
                    <td><?= $row['ec']?></td>
		    <td><?= $row['salClass']?></td>
		    <td><?= $row['remarks']?></td>
                </tr>
      <?php       
               $i++;
	        }
            }
	}
    
      ?>
				</table>
			</div>
			</center>
		</div>
<br /><br />
    </body>
</html>
