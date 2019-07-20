<?php
	function verify($dataName){ //checks if all fields are empty
		if(isset($_POST['submitted'])){
			if(empty($_POST[$dataName])){
				switch($dataName){
					case "email": print "<p class=\"error\">Please enter your e-mail</p>"; break;
					case "password": print "<p class=\"error\">Please enter your password</p>"; break;
				}
				return false;
			}
			else
				if(!empty($_POST['email']))
					check($dataName);
		}
	}
	
	function check($dataName){ //checks if all fields matches the row in the user table
		$email = $_POST['email'];
		
		if($dbc = mysqli_connect('localhost','root',''))
			if(mysqli_select_db($dbc,'forumdb')){
				$query = "SELECT * FROM usertable WHERE user_email = '$email'";
				$find = mysqli_num_rows(mysqli_query($dbc,$query));
				
				switch($dataName){
					case "email": //check whether email exists in database
						$email = $_POST['email'];
						if(!$find)
							print "<p class=\"error\">This e-mail doesn't exist in the records</p>";
						break;
					case "password": //check whether password matches
						if($find){
							$row = mysqli_fetch_array(mysqli_query($dbc,$query));
							if($row['password'] == $_POST['password']){
								if(isset($_SESSION['registerSuccess']))
									unset($_SESSION['registerSuccess']);
								
								$_SESSION['email'] = $row['user_email'];
								$_SESSION['user_name'] = $row['user_name'];
								header('Location: Home.php');
							}
							else
								print "<p class=\"error\">Incorrect password</p>";
						}
						break;
				}
			}
	}
?>
<html>
	<head>
		<title>Login</title>
	<?php require 'Header.php'; ?>
		<h2>Log in</h2>
		<form id="details" action="Login.php" method="post">
			<label for="email">E-mail</label><br>
			<input type="text" name="email" value="<?php retain('email'); ?>">
			<?php verify('email'); ?><br>
			
			<label for="password">Password</label><br>
			<input type="password" name="password">
			<?php verify('password'); ?><br>
			
			<input type="hidden" name="submitted" value="true">
			<p><input type="submit" value="Submit"></p>
		</form><br>
		<?php
			if(isset($_SESSION['notRegistered'])){
				print "<p>Log in now to start posting.</p>";
				unset($_SESSION['notRegistered']);
			}
			if(isset($_SESSION['registerSuccess']))
				print "<p>Registration successful. Log in now to start using the forum!</p>"; //message upon registration complete
			else
				print "<p>New to this forum? Register <a href=\"Register.php\">here</a>.</p>"; //default message
		?>
	</body>
</html>