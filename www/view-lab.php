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
	<style>
		body {
			font-family: "Segoe UI",sans-serif;
			width: 800px;
			margin: auto;
			padding-bottom: 3em;
			border-bottom: 3em solid #123;
		}
		h1 {
			font-size: 3em;
			background-color: #123;
			padding: 0.5em;
			text-align: center;
			color: white;
		}
		h2 {
			font-size: 2em;
			background-color: #246;
			border-top: 0.4em solid #123;
			color: white;
			padding: 0.4em;
		}
		h3 {
			font-size: 1.5em;
			background-color: #369;
			border-top: 0.4em solid #246;
			color: white;
			padding: 0.4em;
		}
		h4 {
			font-size: 1.5em;
			color: #123;
			border-top: 0.3em solid #123;
		}
		pre {
			background-color: #ddf;
			padding: 0.5em;
		}
		table {
			border-collapse: collapse;
		}
		td, th {
			border: 1px solid #bbb;
			padding: 0.3em;
		}
		img {
			width: 100%;
		}
	</style>

</head>
<body>
	<!-- lab HTML gets written here -->
</body>
<?php
$resource=$_GET["resource"];
if (!$md=file_get_contents("https://rawgit.com/ninofiliu/edu/master/www/".$resource)){
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