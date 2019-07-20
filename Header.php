<?php 
	if(session_status() == PHP_SESSION_NONE) 
		session_start(); 
	
	function retain($dataName){ //gets previous value
		if(isset($_POST['submitted']))
			if(!empty($_POST[$dataName]))
				print $_POST[$dataName];
	}
	
	//month array
	$monthArray = array(
		"1" => "January",
		"2" => "February",
		"3" => "March",
		"4" => "April",
		"5" => "May",
		"6" => "June",
		"7" => "July",
		"8" => "August",
		"9" => "September",
		"10" => "October",
		"11" => "November",
		"12" => "December"
	);
	
	//categories available
	$categoryArray = array("General Discussion", "Website Support and Bugs");
?>
		<style>
			<?php include 'style.css'; ?>
		</style>
		<script>
			//get time
			function startTime(){
				var today = new Date();
				var hour = today.getHours();
				var min = today.getMinutes();
				var sec = today.getSeconds();
				min = checkTime(min);
				sec = checkTime(sec);
				document.getElementById('time').innerHTML = hour+":"+min+":"+sec;
				var t = setTimeout(startTime, 500);
			}
			function checkTime(i) {
				if (i < 10)
					i = "0"+i;
				return i;
			}
		</script>
	</head>
	<body onload="startTime()">
		<div>
			<h1>The Vrok Forum</h1>
			<h3>The Melting Pot for Everyone</h3>
			<?php
				if(isset($_SESSION['user_name']))
					print "<h4>Welcome, ".$_SESSION['user_name'].".</h4>";
				else
					print "<h4>Register now to join the Melting Pot!</h4>";
			?>
		</div>
		<div align="right" id="time">
			
		</div>
		<div>
			<ul>
				<li><a href="Home.php">Home</a></li>
				<li><a href="CreateThread.php">Create new thread</a></li>
				<li>
					<form action="Search.php">
						<input type="text" name="search" placeholder="Search...">
					</form>
				</li>
				<?php
					if(!isset($_SESSION['user_name'])){
						print "<li style=\"float:right\"><a href=\"Login.php\">Log in</a></li>
							<li style=\"float:right\"><a href=\"Register.php\">Register</a></li>";
					}
					else {
						print "<li style=\"float:right\"><a href=\"Logout.php\">Log out</a></li>";
						print "<li style=\"float:right\"><a href=\"Account.php\">Account</a></li>";
					}
				?>
			</ul>
		</div>