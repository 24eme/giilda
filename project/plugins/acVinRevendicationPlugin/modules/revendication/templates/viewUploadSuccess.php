<a href="<?php echo url_for('revendication/downloadCSV?md5='.$md5);?>">Download CSV</a>
<p>
<?php
if (!$csv->check()) {
	echo "<table>";
	foreach ($csv->getErrors() as $error) {
		echo "<tr><td>".$error['num_ligne']."</td><td>".$error['message']."</td></tr>";
	}
	echo "</table>";
}else{
	echo "Le fichier ne contient pas d'erreur";
}
?></p>
