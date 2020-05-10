<head>
  <title>AP Computer Science Visualizations</title>

  <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
    crossorigin="anonymous"></script>
  <script src="https://d3js.org/d3.v5.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.4/semantic.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.4/components/slider.min.js"></script>
<link rel='stylesheet' type='text/css' href='https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.4/components/slider.min.css>'>
<link rel='stylesheet' type='text/css' href='https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.4/semantic.min.css'>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js'></script>
  <link rel='stylesheet' type='text/css'
    href='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css'>
    <script src="js/graphs.js?<?php echo date('l jS \of F Y h:i:s A');?>"></script>
  <script src="js/app.js?<?php echo date('l jS \of F Y h:i:s A');?>"></script>
  <link rel='stylesheet' type='text/css' href='css/stylesheet.css?<?php echo date('l jS \of F Y h:i:s A');?>'>
</head>

<h3>AP CS - Wisconsin Region</h3>

<div class="ui grid">
  <div class="three wide column">
  <div class="ui vertical menu">
      <div class="item">
        <div class="header">Exams</div>
        <div class="menu">
          <a class="item" id="exams" href="/stats/index.php">Number of Exams Taken By Year</a>
          <a class="item" id="distro" href="/stats/scoredistro">Number of Exams By Score</a>
          <a class="item" id="byschooltyp" href="/stats/schooltyp">Number of Exams By Type</a>
        </div>
      </div>
      <div class="item">
        <div class="header">Scores</div>
        <div class="menu">
          <a class="item" id="avgperyear" href="/stats/avgperyear">Average Score Per Year</a>
        </div>
      </div>
    </div>
  </div>

  <div class="twelve wide column" style="margin-left: 30px">