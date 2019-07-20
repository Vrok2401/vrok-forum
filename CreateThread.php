<?php
	function verify($dataName,$textout){ //checks if all fields are empty
		if(isset($_POST['submitted'])){
			if(empty($_POST[$dataName])){
				if($textout){
					switch($dataName){
						case "title": print"<p class=\"error\">Please enter a title for your thread</p>"; break;
						case "entry": print"<p class=\"error\">Please type in something for your thread</p>"; break;
					}
				}
				return false;
			}
			else
				return true;
		}
	}
	
	if(isset($_POST['submitted']))
		if(verify('title',false) && verify('entry',false))
			if($dbc = mysqli_connect('localhost','root',''))
				if(mysqli_select_db($dbc,'forumdb')){
					session_start();
					$title = $_POST['title'];
					$entry = nl2br($_POST['entry']);
					$category = $_POST['category'];
					$username = $_SESSION['user_name'];
					$query = "INSERT INTO threadtable(thread_title,thread_message,thread_category,user_name,date_posted) VALUES('$title','$entry','$category','$username',NOW())";
					
					if(mysqli_query($dbc,$query)){
						$query = "SELECT * FROM threadtable";
						$rowNo = mysqli_num_rows(mysqli_query($dbc,$query));
						header('Location: ViewThread.php?'.http_build_query(array('thread_no' => $rowNo)));
					}
					else
						print "<p class=\"error\">There was something wrong with creating the thread</p>";
				}
				else
					print "<p class=\"error\">The database is currently unavailable.</p>";
			else
				print "<p class=\"error\">The server is currently unavailable.</p>";
?>
<html>
	<head>
		<title>Create New Thread</title>
	<?php require 'Header.php'; 
		if(!isset($_SESSION['user_name'])){
			session_start(); //starts session that says the user is not logged in
			$_SESSION['notRegistered'] = "true";
			header('Location: Login.php');
		}
	?>
		<h2>Create Thread</h2>
		<form id="details" action="CreateThread.php" method="post">
			<label>Thread Title</label><br>
			<input type="text" name="title" value="<?php retain('title'); ?>" size="40" maxsize="100"/>
			<?php verify('title',true); ?><br>
			
			<label>Thread Message</label><br>
			<textarea name="entry"  cols="40" rows="15"><?php retain('entry'); ?></textarea>
			<?php verify('entry',true); ?><br>
			
			<label>Category</label><br>
			<select name="category">
				<?php
					foreach($categoryArray as $i => $category){
						echo "<option value=\"$i\"";
						if(isset($_POST['category']))
							if($_POST['category'] == $i)
								echo " selected=\"selected\"";
						echo ">$category</option>";
					}
				?>
			</select><br>
			<p><input type="submit" name="submit" value="Post Thread"/></p>
			<input type="hidden" name="submitted" value="true"/>
		</form>
	</body>
</html>