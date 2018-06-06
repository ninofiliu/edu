<?php
$resources=[];
foreach (scandir("resources") as $name) {
  if (strpos($name,".notes.")>0 || strpos($name,".lab.")>0){
    $resources[]=$name;
  }
}
?>


<head>
    <title>Support</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald">
    <link rel="icon" href="favicon.png">
    <link rel="stylesheet" href="css/index.css">
    <meta property="og:title" content="Support">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://ninofiliu.fr/edu/">
    <meta property="og:image" content="https://ninofiliu.fr/edu/cover.jpg">
    <meta property="og:description" content="Educational support brought to you with <3 by Nino Filiu">
    <meta property="fb:app_id" content="175353863095368">
</head>
<body>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '175353863095368',
          xfbml      : true,
          version    : 'v2.12'
        });

        FB.AppEvents.logPageView();

      };

      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "https://connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
    </script>
    
    <header>
        <h1>/EDU</h1>
        <p>Educational support brought to you with &hearts;by Nino Filiu</p>
    </header>
    
    <div class="subheader left">
        <p>MENU</p>
        <div id="menu-root">
          <!-- INSERTED: MENU -->
        </div>
    </div><div class="subheader right">
        <p>ABOUT</p>
        <p>
            Nino Filiu is 20-year-old student from Telecom ParisTech. He is currently studying communication system security at Eurecom. Here people can find useful ressources he wrote based on the courses he followed.
        </p>
        <p><a href="https://github.com/ninofiliu">GitHub</a><br><a href="https://www.linkedin.com/in/nino-filiu/">LinkedIn</a><br><a href="https://stackoverflow.com/users/8186898/nino-filiu">Stack Overflow</a><br><a href="https://www.facebook.com/nino.filiu">Facebook</a><br><a href="https://www.instagram.com/ssttaacckkyy/">Instagram</a></p>
    </div>
    
    <div id="main">
        <!-- INSERTED: COURSES -->
    </div>
    
    <footer>
        <p><a href="https://github.com/ninofiliu">GitHub</a> - <a href="https://www.linkedin.com/in/nino-filiu/">LinkedIn</a> - <a href="https://stackoverflow.com/users/8186898/nino-filiu">Stack Overflow</a> - <a href="https://www.facebook.com/nino.filiu">Facebook</a> - <a href="https://www.instagram.com/ssttaacckkyy/">Instagram</a></p>
        <p><i>Last update: June, 5th 9:30PM</i></p>
    </footer>
    
</body>

<script>
  var resources=<?php echo file_get_contents("resources.json"); ?>;
  var schools=[];
  resources.forEach(function(c){
    if (!schools.includes(c.school)){
      schools.push(c.school);
    }
  });

  // INSERTED: MENU
  schools.forEach(function(s){
    $("#menu-root").append(
      $("<p>").append(
        $("<a>")
        .html(s.toUpperCase())
        .attr("href","#"+s.split(" ").join(""))
      )
    );
    resources.forEach(function(c){
      if (c.school==s){
        $("#menu-root").find("p").last()
        .append($("<br>"))
        .append($("<span>").html(c.name));
      }
    });
  });

  // INSERTED: COURSE
  schools.forEach(function(s){
    $("#main").append(
      $("<a>")
      .attr("name",s.split(" ").join(""))
      .append(
        $("<h1>")
        .html(s)
      )
    );
    resources.forEach(function(c){
      if (c.school==s){
        $("#main").append($("<h2>").html(c.name));
        $("#main").append($("<p>").html(c.desc));
        c.resources.forEach(function(r){
          $("#main").append(
            $("<a>")
            .html(r.desc+" &raquo;")
            .attr("href",r.src.indexOf(".notes.")>0 ? "view-notes?resource=resources%2F"+r.src : "view-lab?resource=resources%2F"+r.src)
          );
        });
      }
    });
  })
</script>
