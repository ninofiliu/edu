<?php

if (!isset($_GET["resource"])){
  echo "<p>Resource not specified. You might be requesting one of the following:</p><ul>";
  foreach(scandir("resources") as $name){
    if (strpos($name,".notes.")!==false){
      echo "<li><a href='view-notes?resource=".urlencode("resources/".$name)."'>".$name."</a></li>";
    }
  }
  echo "</ul>";
  die();
}

?>

<head>
  <script src="https://cdn.rawgit.com/showdownjs/showdown/1.8.6/dist/showdown.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto"></head>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans"></head>
  <link rel="stylesheet" href="css/view-notes.css">
</head>
<body>
  <div id="left">
    <div id="left-scroll">
      <!-- MENU -->
    </div>
  </div>
  <div id="right">
    <div id="right-scroll">
      <!-- COURSE -->
    </div>
  </div>
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

  // COURSE HTML CREATION

  var html=(new showdown.Converter({tables: true})).makeHtml(md);
  $("#right-scroll").html(html);

  // MENU HTML CREATION

  var titles=md.split("\n")
    .filter(function(line){return line.indexOf("#")==0;})
    .map(function(title){return {
      hNumber: title.split(" ")[0].length,
      value: title.split(" ").slice(1).join(" ")
    }});
  
  titles.forEach(function(title,index){
    if (title.hNumber>1){
      $("#left-scroll").append(
        $("<h"+title.hNumber+">")
        .html(title.value)
        .addClass("title")
        //.attr("data-index",index) # menu method 1
      );
    }
  });

  // MENU HTML INTERACTIONS

  $(".title").on("click",function(){
    // var targetElt1=$($("#right h1, #right h2, #right h3, #right h4").get(this.dataset.index)).first(); # menu method 1
    var targetElt2=$(
      "#"+this.innerHTML
      .split(" ").join("")
      .split("/").join("")
      .toLowerCase()
    ).first();

    $("#right").get(0).scrollTo({
      top: targetElt2.offset().top-$("#right-scroll").offset().top,
      behavior: "smooth"
    });
  });
</script>