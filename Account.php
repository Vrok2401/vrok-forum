<html>
	<head>
		<title>The Vrok Forum</title>
	<?php require 'Header.php'; ?>
		<?php
			if($dbc = mysqli_connect('localhost','root',''))
				if(mysqli_select_db($dbc,'forumdb')){
					if(isset($_SESSION['user_name'])){
						$query = mysqli_query($dbc,"SELECT * FROM usertable WHERE user_name='".$_SESSION['user_name']."'");
						if(mysqli_num_rows($query)){
							$user=mysqli_fetch_array($query);
							$threadQuery = mysqli_query($dbc,"SELECT * FROM threadtable WHERE user_name='".$_SESSION['user_name']."'");
							$threadCount = mysqli_num_rows($threadQuery);
							$commentQuery = mysqli_query($dbc,"SELECT * FROM commenttable WHERE user_name='".$_SESSION['user_name']."'");
							$commentCount = mysqli_num_rows($commentQuery);
							$dateAttr = explode("-",$user['user_birthdate']);
							$month = $monthArray[(int)$dateAttr[1]];
							print "<div>
								<h2>".$_SESSION['user_name']."</h2>
								<h4>".$_SESSION['email']."</h4>
								<p>Birth date: $dateAttr[2] $month $dateAttr[0]</p>
								<p>Threads posted: $threadCount</p>
								<p>Comments posted: $commentCount</p>
							</div>";
						}
						else
							print "<p class=\"error\">Either the user is missing or this page is entered by accident.</p>";
					}
					else
						print "<p class=\"error\">Either the user is missing or this page is entered by accident.</p>";
				}
				else
					print "<p class=\"error\">The database is currently unavailable.</p>";
			else
				print "<p class=\"error\">The server is currently unavailable.</p>";
		?>
	</body>
</html>