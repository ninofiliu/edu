<?php

if (!isset($_GET["resource"])){
	$rr=(array)json_decode(file_get_contents("resources.json"));
	echo "<p>Resource not specified. You might be requesting one of the following:</p><ul>";
	foreach($rr as $r){
		if ($r->type=="lab"){
			echo "<li><a href='view-lab.php?resource=".urlencode($r->source)."'>".$r->name."</a></li>";
		}
	}
	echo "</ul>";
	die();
}

$source=$_GET["resource"];

?>

<head>

</head>
<body>

</body>
<script>
	var source=<?php echo json_encode($source); ?>;
	console.log(source);
</script>