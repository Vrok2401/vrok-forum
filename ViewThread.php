<?php
	function verify($dataName,$textout){ //checks if all fields are empty
		if(isset($_POST['submitted'])){
			if(empty($_POST[$dataName])){
				if($textout)
					print"<p class=\"error\">Please type in something for your comment</p>";
				
				return false;
			}
			else
				return true;
		}
	}
	
	if(isset($_POST['submitted']))
		if(verify('comment',false))
			if($dbc = mysqli_connect('localhost','root',''))
				if(mysqli_select_db($dbc,'forumdb')){
					session_start();
					$threadNo = $_GET['thread_no'];
					$comment = nl2br($_POST['comment']);
					$category = $_POST['category'];
					$username = $_SESSION['user_name'];
					$query = "INSERT INTO commenttable(thread_no,comment_message,thread_category,user_name,date_posted) VALUES($threadNo,'$comment','$category','$username',NOW())";
					
					if(!mysqli_query($dbc,$query))
						print "<p>There was an error in posting your comment.</p>";
				}
				else
					print "<p class=\"error\">The database is currently unavailable.</p>";
			else
				print "<p class=\"error\">The server is currently unavailable.</p>";
?>
<html>
	<head>
		<title>Thread</title>
	<?php require 'Header.php'; ?>
		<?php
			if($dbc = mysqli_connect('localhost','root',''))
				if(mysqli_select_db($dbc,'forumdb')){
					$threadNo = $_GET['thread_no'];
					$query = "SELECT * FROM threadtable WHERE thread_no = $threadNo";
				
					if(mysqli_query($dbc,$query))
						if($row = mysqli_fetch_array(mysqli_query($dbc,$query))){
							print "<h2>".$row['thread_title']."</h2>";
							print "<h3>".$categoryArray[$row['thread_category']]."</h3>";?>
							<table id="message">
								<?php
								print "<tr><th>#1. ".$row['user_name']."\t".$row['date_posted']."</th></tr>
									<tr><td>".$row['thread_message']."</td></tr>";
							
								$commentFind = mysqli_query($dbc,"SELECT * FROM commenttable WHERE thread_no=$threadNo");
						
								if(mysqli_num_rows($commentFind)){
									$i = 2;
									while($comments=mysqli_fetch_array($commentFind)){
										$commentNo = $i;
										print "<tr><th>#$i. ".$comments['user_name']."\t".$comments['date_posted']."</th></tr>
											<tr><td>".$comments['comment_message']."</td></tr>";
										$i++;
									}
								}
							?></table><br><?php
							if(isset($_SESSION['user_name'])){ ?>
								<form id="details" action="<?php print "ViewThread.php?thread_no=$threadNo"; ?>" method="post">
									<label>Comment</label><br>
									<textarea name="comment" value="<?php retain('comment'); ?>"  cols="40" rows="5"></textarea>
									<?php verify('comment',true); ?><br>
									<input type="submit" name="submit" value="Post Thread"/>
									<input type="hidden" name="submitted" value="true"/>
									<input type="hidden" name="category" value="<?php print $row['thread_category']; ?>"/>
								</form>
						<?php
							}
							else
								print "<p>Want to post a comment? <a href=\"Login.php\">Log in</a> or <a href=\"Register.php\">register</a> to start posting.</p>";
						}
						else
							print "<p class=\"error\">The thread is either unavailable or is deleted.</p>";
				}
				else
					print "<p class=\"error\">The database is currently unavailable.</p>";
			else
				print "<p class=\"error\">The server is currently unavailable.</p>";
		?>
		
	</body>
</html>