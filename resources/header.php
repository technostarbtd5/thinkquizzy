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
  <title><?php echo $titleTag;?>ThinkQuizzy</title>
  <link rel="stylesheet" href="resources/thinkQuizzy.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="resources/helpers.js"></script>
</head>
<body>
  <div id=main>
    <div id="headerBar">
      <a href="index.php" id="logo" class="left">
        ThinkQuizzy
      </a>
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
