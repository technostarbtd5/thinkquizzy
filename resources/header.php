<?php
  // Include this file in front of all rendered php pages.
  // Set $titleTag to any special title you want the page to have.
  if(!isset($titleTag)) {
    $titleTag = "";
  }
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

    </div>


    <script>
      $(document).ready(function () {
        $("#headerBar").html(
          `<a href="index.php" id="logo" class="left">
            ThinkQuizzy
          </a>`
        );
      });
    </script>
    <div id="menuBar">
    </div>
    <div class="moreBar" id="moreBar" hidden>
    </div>
    <script>
    $(document).ready(function () {
      // Menu bar creation script.

      const primaryKeywords = ["New", "People", "Places", "Pandemic"];
      const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);
      const homepageLink = "index.php";
      //console.log(window.location.pathname);
      //console.log(window.location.search);
      //console.log(urlParams.has("sort"));

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
      const extraCategories = ["Pets", "Celebrities", "History", "Food", "Movies", "Books", "Music", "Math", "Science", "Language"];
      extraCategories.forEach((item, i) => {
        moreHTML += `<a class="moreBarOption" id="${item.toLowerCase()}_button" href="index.php?sort=${item.toLowerCase()}">
          ${item}
        </a>`
      });


      $("#menuBar").html(
        innerHTML
      ).promise().done(function() {
        $("#more_button").mouseenter(function() {
          $("#moreBar").show(0);
        }).mouseleave(function() {
          if($("#moreBar:hover").length == 0) {
            $("#moreBar").hide(0);
          }
        });
        $("#moreBar").mouseleave(function() {
          $("#moreBar").hide(0);
        }).html();
      });
      $("#moreBar").html(
        moreHTML
      ).hide(0);
    });
    </script>
    <div id="body">
