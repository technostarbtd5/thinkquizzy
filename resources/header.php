<?php
  // Include this file in front of all rendered php pages.
  // Set $titleTag to any special title you want the page to have.
  if(!isset($titleTag)) {
    $titleTag = "";
  }

  /*// Important database values.

  $servernameDB = 'localhost';
  $usernameDB = 'root';
  $passwordDB = '';
  $nameDB = 'ThinkQuizzyDB';

  // Create or load SQL SQL Database

  $conn = mysqli_connect($servernameDB, $usernameDB, $passwordDB);
  if(!$conn) {
    echo "Unable to establish SQL connection!";
    die('Could not connect.');
  }*/
  include("resources/dbconnect.php");

  // Make ThinkQuizzyDB the current Database


?>

<head>
  <meta charset="utf-8">
  <title><?php //echo $titleTag;?>ThinkQuizzy</title>
  
  <link rel="stylesheet" href="resources/jquery-ui.css">
  <link rel="stylesheet" href="https://jqueryui.com//resources/demos/style.css">
  <link rel="stylesheet" href="resources/thinkQuizzy.css" />
  <link rel="shortcut icon" href="resources/tqfavicon.png" type="image/x-icon">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="resources/helpers.js"></script>
</head>
<body>
  <div id=main>
    <div class="ui-widget" id="headerBar">
      <a href="index.php" id="logo" class="left">
        <div>
          ThinkQuizzy
        </div> <img src="resources/ThinkQuizzyLogo.png" height="48"/>
      </a>
      <a href="quizbuilder.php" id="quizBuilderLink" class="right">
        Quiz Builder
      </a>
      <input type="search" id="searchBar" name="search" placeholder="Search here" >
      <div id="hiddenJSON" style="display: none;">
        <?php
          // ALL OUTPUT FROM THIS PHP IS HIDDEN
          
          // get all categories and put the names in an array
          $qr = "SELECT * FROM `categories` WHERE 1";
          $res = mysqli_query($conn, $qr);
          $catrows = array();
          while($row = mysqli_fetch_assoc($res)) {
            // $rows['object_name'][] = $r;
            $string = 'Category | ' . $row['name'];
            array_push($catrows,$string);
          }
          
          // get all quizzes and
          // put the titles and descriptions in their own arrays
          $qr = "SELECT * FROM `quizzes` WHERE 1";
          $res = mysqli_query($conn, $qr);
          $quizrows = array();
          $descrows = array();
          while($row = mysqli_fetch_assoc($res)) {
            // $rows['object_name'][] = $r;
            $string = 'Quiz ' . $row['id'] . ' | ' . $row['title'] . " " . $row['description'];
            array_push($quizrows,$string);
            // array_push($descrows,$row['description']);
          }
          // put the categories, quizzes, and descriptions into an array as elements
          $all = array('cats' => $catrows, 'quizzes' => $quizrows, 'descs' => $descrows);
          // turn the above array into JSON format and echo it into this hidden <div>
          echo htmlspecialchars(json_encode($all));
          
          ?>
      </div>
      <script>
        // keeps the drop-down menu the same width as the search bar
        jQuery.ui.autocomplete.prototype._resizeMenu = function () {
          var ul = this.menu.element;
          ul.outerWidth(this.element.outerWidth());
        }
        // store the hidden JSON from the hidden <div> as a string
        
        searchData = $('#hiddenJSON').html();
        // array for storing the categories and quiz names and descriptions
        var src = [];
        
        // parse the JSON and put the values into src[]
        $.each($.parseJSON(searchData), function(i) {
          var temparr = this;
          for(i in temparr) {
            src.push(temparr[i]);
          }
        });
        
        // assigns the elements of src[] to be the autocomplete suggestions
        $( "#searchBar" ).autocomplete({
          source: src,
          select: function (e, ui) {
            var unsplitText = ui.item.label;
            var splitText = unsplitText.split(" ");
            if(splitText[0][0] == "C") {
              window.location.href = "index.php?sort=" + splitText[2].toLowerCase();
            } else {
              selectedQuiz = splitText[0].substring(5,splitText[0].length);
              window.location.href = "quiz.php?id=" + selectedQuiz;
            }
          }
        });
          
      </script>
      
    </div>
    <div id="menuBar">
    </div>
    <div class="moreBar" id="moreBar" hidden>
    </div>
    <script>
    $(document).ready(function () {
      // Menu bar creation script.

      const primaryKeywords = ["New", "People", "Places", "Pandemic"];

      // Variables to allow client to parse "GET" params
      const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);

      // Just a simple link to the homepage
      const homepageLink = "index.php";

      // First, make sure "Hot" links to just the homepage.
      let innerHTML = `<a class="menuButton ${urlParams.has('sort') || window.location.pathname.substr(-1 * homepageLink.length) != homepageLink ? '' : 'menuButtonActive'}" id="hot_button" href="index.php">
          Hot
        </a>`;

      // Now, add a menu button for each major category.
      primaryKeywords.forEach((item, i) => {
        innerHTML +=
          `<a class="menuButton ${urlParams.has('sort') && urlParams.get('sort') == item.toLowerCase() ? 'menuButtonActive' : ''}" id="${item.toLowerCase()}_button" href="index.php?sort=${item.toLowerCase()}">
              ${item}
            </a>`;
      });

      // Finally, add a div that can expand a more categories menu on hover.
      innerHTML +=
      `<div id="more_button" class="menuButton">
        More
      </div>`;

      // Add "More Bar" items as divs
      let moreHTML = "";

      // Replace with PHP SQL query to get top 25 categores
      const extraCategories = ["Pets", "Personality", "History", "Food", "Movies", "Books", "Music", "Math", "Science", "Language"];

      // Add extra categories as cells in the "More Bar" grid.
      extraCategories.forEach((item, i) => {
        moreHTML += `<a class="moreBarOption" id="${item.toLowerCase()}_button" href="index.php?sort=${item.toLowerCase()}">
          ${item}
        </a>`
      });

      // Update menu bar.
      $("#menuBar").html(
        innerHTML
      ).promise().done(function() {
        // Update more bar to work properly with mouseover
        $("#more_button").mouseenter(function() {
          $("#moreBar").show(0);
        }).mouseleave(function() {
          // Only hide if you didn't just move into the menu
          if($("#moreBar:hover").length == 0) {
            $("#moreBar").hide(0);
          }
        });
        $("#moreBar").mouseleave(function() {
          $("#moreBar").hide(0);
        }).html();
      });

      // Update more bar html
      $("#moreBar").html(
        moreHTML
      ).hide(0);
    });
    </script>
    <div id="body">
