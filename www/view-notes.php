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
  <style>
    /* POSITION OF ELEMENTS */
    body {
      margin: 0;
      display: flex;
    }
    #left {
      height: 100vh;
      height: 100vh;
      overflow-y: scroll;
    }
    #left::-webkit-scrollbar { 
      display: none; 
    }
    #right {
      width: 100%;
      height: 90vh;
      overflow-y: scroll;
      padding: 5vh;
    }

    /* STYLING */

    :root {
      --lblue: #369;
      --blue: #246;
      --dblue: #123;
      --grey: #ccc;
    }
    body {
      font-family: "Open Sans", sans-serif;
    }
    #left {
      background-color: var(--lblue);
      color: white;
    }


    #left .title {
      font-weight: normal;
      transition: all 0.5s;
    }
    #left .title:hover {
      cursor: pointer;
      background-color: var(--dblue);
      transform: translateX(1em);
    }
    #left h2 {
      padding: .5em 0 .5em .5em;
      margin: 0;
      background-color: var(--blue);
    }
    #left h3 {
      padding: .2em 0 .2em 1em;
      margin: 0;
    }
    #left h4 {
      padding: 0em 0 0em 2em;
      margin: 0;
    }

    #right h1 {
      font-family: "Roboto", "Open Sans", sans-serif;
      color: white;
      background-color: var(--dblue);
      padding: .5em;
      font-size: 3.5em;
      margin: 0;
    }
    #right h2 {
      font-family: "Roboto", "Open Sans", sans-serif;
      color: white;
      background-color: var(--blue);
      padding: .5em;
      font-size: 2.5em;
      margin: 2em 0 0 0;
    }
    #right h3 {
      font-family: "Roboto", "Open Sans", sans-serif;
      color: var(--blue);
      font-size: 2.3em;
      border-top: .1em solid var(--blue);
      border-bottom: .1em solid var(--blue);
    }
    #right h4 {
      font-family: "Roboto", "Open Sans", sans-serif;
      color: var(--lblue);
      font-size: 1.3em;
    }

    #right table {
      border-collapse: collapse;
    }
    #right table td,
    #right table th {
      border: 1px solid var(--grey);
      padding: .3em;
    }

    #right pre {
      background-color: var(--grey);
      padding: 1em;
    }
  </style>
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
if (!$md=file_get_contents("https://rawgit.com/ninofiliu/edu/master/www/".$resource)){
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
    var targetElt2=$("#"+this.innerHTML.split(" ").join("").toLowerCase()).first();

    $("#right").get(0).scrollTo({
      top: targetElt2.offset().top-$("#right-scroll").offset().top,
      behavior: "smooth"
    });
  });
</script>