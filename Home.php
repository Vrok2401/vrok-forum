<html>
	<head>
		<title>The Vrok Forum</title>
	<?php require 'Header.php'; ?>
		<?php
			if($dbc = mysqli_connect('localhost','root',''))
				if(mysqli_select_db($dbc,'forumdb')){
					$query = mysqli_query($dbc,"SELECT * FROM threadtable ORDER BY date_posted DESC");
					if(mysqli_num_rows($query)){
				?>
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
					</table>
				<?php
					}
					else
						print "<p>This forum is empty.</p>";
				}
				else
					print "<p class=\"error\">The database is currently unavailable.</p>";
			else
				print "<p class=\"error\">The server is currently unavailable.</p>";
		?>
	</body>
</html>