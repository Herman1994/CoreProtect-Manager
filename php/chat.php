<?php
include 'msql.php';
$query = "SELECT * FROM chat ORDER BY id DESC";
$run   = $conn->query($query);
while ($row = $run->fetch_array()){
?>
<div id="chat_data">
				<span style="color:green;"><?= $row['name'];
?>:</span>
				<span style="color:red;"><?= $row['msg'];
?></span>
				<span style="float:right;"><?= $row['date'];
?></span>


			</div>
<?php }?>