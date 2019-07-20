<?php
	function verify($dataName,$textout){ //checks if all fields are empty
		if(isset($_POST['submitted'])){
			if(empty($_POST[$dataName])){
				if($textout){
					switch($dataName){
						case "email": print "<p class=\"error\">Please enter your e-mail</p>"; break;
						case "user_name": print "<p class=\"error\">Please enter your username</p>"; break;
						case "password": print "<p class=\"error\">Please enter your password</p>"; break;
					}
				}
				return false;
			}
			else
				if(!($dataName == "password"))
					return (findDuplicate($dataName,$textout));
		}
	}
	
	function findDuplicate($dataName, $textout){ //check if the username or e-mail already exists in the table
		if($dbc = mysqli_connect('localhost','root',''))
			if(mysqli_select_db($dbc,'forumdb')){
				$value = $_POST[$dataName];
				switch($dataName){
					case "email":
						$name = "e-mail";
						$query = "SELECT * FROM usertable WHERE user_email = '$value'";
						break;
					case "user_name":
						$name = "username";
						$query = "SELECT * FROM usertable WHERE user_name = '$value'";
						break;
					default: return false;
				}
				
				if(mysqli_num_rows(mysqli_query($dbc,$query))){
					if($textout)
						print "<p class=\"error\">This $name is already used</p>";
					return true;
				}
				else
					return false;
			}
	}
	
	function verifyDate($textout){ //verifies birth date
		if(isset($_POST['submitted'])){
			$datevalid = true;
			
			$month = $_POST['month'];
								
			switch($month){
				case 4: case 6: case 9: case 11: $maxday = 30; break;
				case 2:
					if($_POST['year'] % 4 == 0)
						$maxday = 29;
					else
						$maxday = 28;
					break;
				default: $maxday = 31;
			}
			if($_POST['day'] > $maxday){
				if($textout)
					print "<p class=\"error\">The date selected is invalid.</p>";
				return false;
			}
			else
				return true;
		}
	}

	if(isset($_POST['submitted']))
		if(verify('user_name',false) && verify('email',false) && verify('password',false) && verifyDate(false))
			if($dbc = mysqli_connect('localhost','root',''))
				if(mysqli_select_db($dbc,'forumdb')){
					$username = $_POST['user_name'];
					$email = $_POST['email'];
					$password = $_POST['password'];
					$birthdate = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'];
					
					$query = "INSERT INTO usertable(user_name,user_email,password,user_birthdate,user_date_created) VALUES('$username','$email','$password','$birthdate',NOW())";
					
					if(mysqli_query($dbc,$query)){
						session_start(); //starts session that verifies registration complete
						$_SESSION['registerSuccess'] = "true";
						header('Location: Login.php');
					}
				}
				else
					print "<p class=\"error\">The database is currently unavailable.</p>";
			else
				print "<p class=\"error\">The server is currently unavailable.</p>";
		
?>

<html>
	<head>
		<title>Register</title>
	<?php require 'Header.php'; ?>
		<h2>Register</h2>
		<div>
			<form id="details" action="Register.php" method="post">
				<label for="user_name">Username</label><br>
				<input type="text" name="user_name" value="<?php retain('user_name'); ?>">
				<?php verify('user_name',true); ?><br>
			
				<label for="email">E-mail</label><br>
				<input type="text" name="email" value="<?php retain('email'); ?>">
				<?php verify('email',true); ?><br>
			
				<label for="password">Password</label><br>
				<input type="password" name="password">
				<?php verify('password',true); ?><br>
				
				<label>Month</label><br>
				<select name="month">
					<?php
					foreach($monthArray as $m => $monthValue){
						echo "<option value=\"$m\"";
						if(isset($_POST['category']))
							if($_POST['category'] == $m)
								echo " selected=\"selected\"";
						echo ">$monthValue</option>";
					}
						for($m=1;$m<=12;$m++){
							echo "<option value='$m'";
							if(isset($_POST['month']))
								if($_POST['month'] == $m)
									echo " selected=\"selected\"";
							echo ">$monthArray[$m]</option>";
						}
					?>
				</select><br>
			
				<label>Birth date</label><br>
				<select name="day">
					<?php
						for($d=1;$d<=31;$d++){
							echo "<option value='$d'";
							if(isset($_POST['day']))
								if($_POST['day'] == $d)
									echo " selected=\"selected\"";
							echo ">$d</option>";
						}
					?>
				</select><br>
				
				<label>Birth year</label><br>
				<select name="year">
					<?php
						for($y=2019;$y>=1919;$y--){
							echo "<option value='$y'";
							if(isset($_POST['year']))
								if($_POST['year'] == $y)
									echo " selected=\"selected\"";
							echo ">$y</option>";
						}
					?>
				</select>
				<?php verifyDate(true); ?>
				
				<input type="hidden" name="submitted" value="true">
				<p><input type="submit" value="Submit"></p>
			</form>
		</div>
	</body>
</html>