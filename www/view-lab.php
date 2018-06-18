<?php

if (!isset($_GET["resource"])){
	echo "<p>Resource not specified. You might be requesting one of the following:</p><ul>";
	foreach(scandir("resources") as $name){
		if (strpos($name,".lab.")!==false){
			echo "<li><a href='view-lab?resource=".urlencode("resources/".$name)."'>".$name."</a></li>";
		}
	}
	echo "</ul>";
	die();
}

?>

<head>
	<script src="https://cdn.rawgit.com/showdownjs/showdown/1.8.6/dist/showdown.min.js"></script>
	<link rel="stylesheet" href="css/view-lab.css">
</head>
<body>
	<!-- lab HTML gets written here -->
</body>
<?php
$resource=$_GET["resource"];
error_reporting(0);
$md=file_get_contents("https://rawgit.com/ninofiliu/edu/master/www/".$resource);
error_reporting(E_ERROR|E_WARNING|E_PARSE);
if (!$md){
  $md=file_get_contents($resource);
}
?>
<script>
  var resource=<?php echo json_encode($resource); ?>;
  var md=<?php echo json_encode($md); ?>;

	var conv=new showdown.Converter({tables: true});
	var html=conv.makeHtml(md);
	document.write(html);
</script>