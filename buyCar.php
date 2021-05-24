<!DOCTYPE html>
<html>
	<head>
		<title>Car Purchase</title>
	</head>
	<body>
		<?php
			include "include/dbconnect.inc.php";
			if(session_id() == ''){
    			session_start();
			}
			$user = $_SESSION["userID"];
			$checkQ = "SELECT userID FROM carpurchases WHERE userID=?";

        $stmt = $conn->prepare($checkQ);
			$stmt->bind_param("s", $user);
			$stmt->execute();
			if($stmt->get_result()->num_rows == 0){
			
			
			if(isset($_POST['carChoice'])){
				$car = $_POST["car"];
				if(!isset($_POST["upgrE"])){
					$eng = False;
				} else{
					$eng = True;
				}
				if(!isset($_POST["upgrS"])){
					$ssp = False;
				} else {
					$ssp = True;
				}
				if(!isset($_POST["upgrT"])){
					$trn = False;
				} else {
					$trn = True;
				}
				if(!isset($_POST["upgrB"])){
					$brk = False;
				} else {
					$brk = True;
				}

				#var_dump($car);
				#var_dump($eng);
				#var_dump($ssp);
				#var_dump($trn);
				#var_dump($brk);

				$q = "SELECT carsID, carYear, carHorsepower, carBasePrice FROM cars WHERE carName=?";

				$stmt = $conn->prepare($q);
				$stmt->bind_param("s", $car);
				$stmt->execute();
				$result = $stmt->get_result();

				if($result->num_rows > 0){
					$x = $result->fetch_assoc();
					#var_dump($x);
				}

				$q2 = "SELECT upgradePrice FROM upgrades WHERE upgradesID = ?";
				

				$total = $x["carBasePrice"];

				if($eng){
					$uid = $x["carsID"];
					$uid .= "eng";
					$stmt = $conn->prepare($q2);
					$stmt->bind_param("s", $uid);
					$stmt->execute();
					$result = $stmt->get_result();
					$a = $result->fetch_assoc();
					$total = $total + $a["upgradePrice"];
					$eng = 1;
				}
				if($ssp){
					$uid = $x["carsID"];
					$uid .= "ssp";
					$stmt = $conn->prepare($q2);
					$stmt->bind_param("s", $uid);
					$stmt->execute();
					$result = $stmt->get_result();
					$a = $result->fetch_assoc();
					$total = $total + $a["upgradePrice"];
					$ssp = 1;
				}
				if($trn){
					$uid = $x["carsID"];
					$uid .= "trn";
					$stmt = $conn->prepare($q2);
					$stmt->bind_param("s", $uid);
					$stmt->execute();
					$result = $stmt->get_result();
					$a = $result->fetch_assoc();
					$total = $total + $a["upgradePrice"];
					$trn = 1;
				}
				if($brk){
					$uid = $x["carsID"];
					$uid .= "brk";
					$stmt = $conn->prepare($q2);
					$stmt->bind_param("s", $uid);
					$stmt->execute();
					$result = $stmt->get_result();
					$a = $result->fetch_assoc();
					$total = $total + $a["upgradePrice"];
					$brk = 1;
				}

				$carC = $x["carsID"];
				

				$insertQ = "INSERT INTO carPurchases (userID, carsID, carPrice) VALUES (?, ?, ?)";
				$stmt2 = $conn->prepare($insertQ);
				$stmt2->bind_param("ssi", $user, $carC, $total);
				$stmt2->execute();
				#var_dump($eng);

				$pullQ = "SELECT userID, carsID, carPrice FROM carpurchases WHERE userID =?";
				$stmt = $conn->prepare($pullQ);
				$stmt->bind_param("s", $user);
				$stmt->execute();
				$result = $stmt->get_result()->fetch_assoc();
				echo "<br>";
				foreach($result as $r){
					echo "<td>$r</td>";
				}
			}
		} else{
			echo "<h2>You already purchased a car..</h2>";
		}
		?>
		<h3> Select a Car: </h3>
		<form method="post">
			AMG-GT Starting<input type="radio" id="car" name="car" value="GT"> <br>
			Fully Loaded Maybach<input type="radio" id="car" name="car" value="S-Class"> <br>
			S63 Coup<input type="radio" id="car" name="car" value="C-Class"> <br>
			G63<input type="radio" id="car" name="car" value="GLA"> <br>
			CLS53<input type="radio" id="car" name="car" value="GLE"> <br>
			AMG-GT 63s 4-Door<input type="radio" id="car" name="car" value="CLA"> <br> <br> <br>
			<h3> Select Upgrades: </h3>
			Engine: <input type="checkbox" id="upgrE" name="upgrE" value=True> <br>
			Suspension: <input type="checkbox" id="upgrS" name="upgrS" value=True> <br>
			Transmission: <input type="checkbox" id="upgrT" name="upgrT" value=True> <br> 
			Brakes: <input type="checkbox" id="upgrB" name="upgrB" value=True> <br>
			<br> <br> <br>
			<input type="submit" name="carChoice" value="Submit">
		</form>
	</body>