<?php

// UPDATE KNOWLEDGE
if (isset($_GET["update"])){
  $vocab=json_decode($_POST["vocab"]);
  $txt="";
  foreach($vocab as $v){
    foreach($v as $e){
      $txt.=$e."\n";
    }
  }
  var_dump($txt);
  file_put_contents($_GET["resource"], $txt);
  header("Location: quizz.vocab.php");
  die();
}

// RECOMMEND RESOURCES IN CASE NONE IS SPECIFIED
if (!isset($_GET["resource"])){
  echo "no resource specified<br>";
  foreach(scandir("./") as $name){
    if (substr($name,0,5)=="vocab"){
      echo "<a href='quizz.vocab.php?resource=".$name."'>".$name."</a><br>";
    }
  }
  die();
}
?>

<head>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Slab">
  <style>
    body {
      margin: 1em;
      font-family: "Roboto Slab", serif;
      font-weight: bold;
    }
    #quizz {
      border-collapse: collapse;
      margin: auto;
    }
    #quizz td {
      font-size: 1.5em;
    }
    .english {
      text-align: right;
      padding-right: 0.5em;
    }
    .german {
      font-size: 1em;
      background-color: #ccc;
      border: none;
      padding: 0.3em;
      outline: none;
      font-family: "Roboto Slab", serif;
    }
    .answer {
      padding-left: 0.5em;
    }

    #update, #reinit {
      font-family: "Roboto Slab", serif;
      font-size: 2em;
      background-color: white;
      padding: 0.3em;
      display: block;
      width: 100%;
      text-align: center;
      margin: 1em 0;
      border: 1px solid black;
    }
    #update:hover, #reinit:hover {
      cursor: pointer;
      background-color: #ccc;
    }
  </style>
</head>
<body>
  <table id="quizz"></table>
  <button id="update">Update knowledge</button>
  <button id="reinit">Reinitialize knowledge</button>
</body>
<script>
  // ELEMENTS CREATION
  var txt=<?php echo json_encode(file_get_contents($_GET["resource"])); ?>;

  var resource=<?php echo json_encode($_GET["resource"]); ?>;
  txt=txt.split("\r\n").join("\n");
  txt=txt.split("\n");
  var vocab=[];
  for (var i=0; i<txt.length-1; i+=4){
    vocab.push({
      english: txt[i],
      german: txt[i+1],
      tests: +txt[i+2],
      passed: +txt[i+3]
    });
  }
  vocab.sort(function(a,b){
    return (a.passed/a.tests)-(b.passed/b.tests);
  });
  vocab.forEach(function(v,i){
    $("#quizz").append(
      $("<tr>")
      .append(
        $("<td>")
        .html(v.english)
        .addClass("english")
      )
      .append(
        $("<td>")
        .append(
          $("<input>")
          .addClass("german")
          .attr("data-english",v.english)
          .attr("data-german",v.german)
          .attr("data-index",i)
        )
      )
      .append(
        $("<td>")
        .addClass("answer")
      )
    )
  });

  // GAME
  $(".german").first().focus();
  $(".german").on("keydown",function(evt){
    if (evt.key=="Enter"){
      var index=+this.dataset.index;
      var english=this.dataset.english;
      var german=this.dataset.german;
      var guess=this.value;

      // html correction display
      if (guess==german){
        $(this).css("background-color","#3f3");
        vocab[index].passed++;
      } else {
        $(this).css("background-color","#f33");
        var answerElt=$($(this).parent().parent().children()[2]);
        corr=german.split(" ");
        guess=guess.split(" ");
        for (var i=0; i<corr.length; i++){
          if (i>=guess.length){
            answerElt.append(
              $("<span>")
              .html(corr[i])
              .css("color","#f33")
            );
          } else {
            if (corr[i]==guess[i]){
              answerElt.append(
                $("<span>")
                .html(corr[i])
              );
            } else {
              answerElt.append(
                $("<span>")
                .html(corr[i])
                .css("color","#f33")
              );
            }
          }
          answerElt.html(answerElt.html()+" ");
        }
      }
      vocab[index].tests++;

      // focus on the next input
      $($(".german").get(Math.min(index+1,vocab.length-1))).focus();
    }
  });

  // UPDATE KNOWLEDGE
  function send(){
    var form=$("<form>")
      .attr("action","quizz.vocab.php?update=true&resource="+resource)
      .attr("method","post")
      .append($("<input>")
        .attr("name","vocab")
        .attr("value",JSON.stringify(vocab))
      );
    $(document.body).append(form);
    form.submit();    
  }
  $("#update").on("click",send);

  // REINIT KNOWLEDGE
  function shuffle(a) {
      var j, x, i;
      for (i = a.length - 1; i > 0; i--) {
          j = Math.floor(Math.random() * (i + 1));
          x = a[i];
          a[i] = a[j];
          a[j] = x;
      }
      return a;
  }
  $("#reinit").on("click",function(){
    vocab=vocab.map(function(v){return {
      english: v.english,
      german: v.german,
      tests: 1,
      passed: 0
    }});
    shuffle(vocab);
    send();
  });
</script>