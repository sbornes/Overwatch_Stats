<html>
<!-- https://github.com/SunDwarf/OWAPI -->
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="css/horizBarChart.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jstimezonedetect/1.0.4/jstz.min.js"></script>
  <script type="text/javascript" src="js/canvasjs.min.js"></script>
  <script src="js/main.js"></script>

</head>

<body>

<!-- Return to Top -->
<a href="javascript:" id="return-to-top"><i class="icon-chevron-up"></i></a>

<div style='overflow:hidden;'><div class="background"></div></div>

<div class="container">
  <img class="logo" src="content/logo2.png">
  <div class="input-group">
    <div id="custom-search-input">
      <div id="formAlert" class="alert alert-warning hide">
        <strong>Warning!</strong> asdsad.
      </div>

      <div class="input-group col-md-12">
        <input id="search-txt" type="text" class="form-control input-lg" placeholder="Search for Players...." />
        <span class="input-group-btn">
          <button id="search-btn" class="btn btn-info btn-lg" type="button">
            <i class="glyphicon glyphicon-search"></i>
          </button>
        </span>
      </div>
    </div>
  </div>
</div>

   

<div class='content'> 
  <div id="fade"></div>
  <div id="loader"></div>
  <div class="spinner">
    <div class="rect1"></div>
    <div class="rect2"></div>
    <div class="rect3"></div>
    <div class="rect4"></div>
    <div class="rect5"></div>
  </div>

  <div class="response1"></div>
  <div class="response2"></div>
  <div id="topheroes" class="response3" style="width: 100%;background: #404040;"></div>
  <div id="comphistory" class="response4" style="display: none;">
    <h1 class="competitive_history">Competitive History</h1>
      <div id="chartContainer" style="height: 300px; width: 50%; margin: auto;"></div>
  </div>
</div>


</body>

</html>