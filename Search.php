<html>
	<head>
		<title>The Vrok Forum</title>
	<?php require 'Header.php'; ?>
		<?php
			if($dbc = mysqli_connect('localhost','root',''))
				if(mysqli_select_db($dbc,'forumdb')){
					if((!empty($_GET['search'])) || (isset($_GET['category']) && $_GET['category'] != -1) || (!empty($_GET['author']))){
						$keyword = $_GET['search'];
						$searchquery = "SELECT * FROM threadtable WHERE thread_title LIKE '%$keyword%'";
						
						if(isset($_GET['category']))
							if($_GET['category'] != -1)
								$searchquery .= " AND thread_category=".$_GET['category'];
						
						if(isset($_GET['author']))
							if(!empty($_GET['author']))
								$searchquery .= " AND user_name='".$_GET['author']."'";
						
						$searchquery .= " ORDER BY date_posted DESC";
					
						$query = mysqli_query($dbc,$searchquery);
						if(mysqli_num_rows($query)){ ?>
							<table id="menu">
								<tr>
									<th>Title</th>
									<th>Category</th>
									<th>Comments</th>
									<th>Author</th>
									<th>Date created</th>
								</tr>
							<?php
								while($threads=mysqli_fetch_array($query)){
									$getComments = mysqli_query($dbc,"SELECT * FROM commenttable WHERE thread_no=".$threads['thread_no']);
									$commentNo = mysqli_num_rows($getComments);
									print "<tr>
										<td><a href=\"ViewThread.php?thread_no=".$threads['thread_no']."\">".$threads['thread_title']."</a></td>
										<td><a href=\"Search.php?search=&category=".$threads['thread_category']."\">".$categoryArray[$threads['thread_category']]."</a></td>
										<td align=\"right\">$commentNo</td>
										<td>".$threads['user_name']."</td>
										<td>".$threads['date_posted']."</td>
									</tr>";
								}
							?>
						</table> <?php
						}
						else
							print "<p>No thread with these results is found.</p>";
					}
					else
						print "<p>Please enter something to search for threads.</p>";
					?>
					<form action="Search.php" method="get">
						<h2>Advanced search</h2>
						<p>Thread title: <input type="text" name="search" value="<?php print $_GET['search']; ?>"></p>
						<p>Category: <select name="category">
							<option value="-1">Select category</option>
							<?php
								foreach($categoryArray as $i => $category){
									echo "<option value=\"$i\"";
									if(isset($_GET['category']))
										if($_GET['category'] == $i)
											echo " selected=\"selected\"";
									echo ">$category</option>";
								}
							?>
							</select>
						</p>
						<p>Author: <input type="text" name="author" value="<?php retain('author'); ?>"></p>
						<input type="submit" name="submit" value="Search"/>
						<input type="hidden" name="submitted" value="true"/>
					</form>
					<?php
				}
				else
					print "<p class=\"error\">The database is currently unavailable.</p>";
			else
				print "<p class=\"error\">The server is currently unavailable.</p>";
		?>
	</body>
</html>