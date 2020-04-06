<?php
  // Include this file in front of all rendered php pages.
  // Set $titleTag to any special title you want the page to have.
  if(!isset($titleTag)) {
    $titleTag = "";
  }

  // Important database values.

  $servernameDB = 'localhost';
  $usernameDB = 'root';
  $passwordDB = '';
  $nameDB = 'ThinkQuizzyDB';

  // Create or load SQL SQL Database

  $conn = mysqli_connect($servernameDB, $usernameDB, $passwordDB);
  if(!$conn) {
    echo "Unable to establish SQL connection!";
    die('Could not connect.');
  }

  // Make ThinkQuizzyDB the current Database
  $db_selected = mysqli_select_db($conn, $nameDB);

  $initDummyQuizzes = array();

  // Create if the database doesn't exist
  if(!$db_selected) {
    $sql = "CREATE DATABASE $nameDB";
    if(mysqli_query($conn, $sql)) {
      echo "Database created successfully<br/>";

      // Create some dummy quizzes, as the database should be empty right now.
      $initDummyQuizzes[] = 'INSERT INTO quizzes (title, description, icon_small, icon_large, author) VALUES ("What ice cream flavor are you?", "Based on your personality traits, we\'ll match you with the ice cream flavor you are most similar to!", "icecream.png", "icecream_large.png", "Techno")';
      $initDummyQuizzes[] = ' INSERT INTO quizzes (title, description, icon_small, icon_large, author) VALUES ("We\'ll pick your next vacation based on your favorite actors!", "We all need vacation inspiration, right?", "yosemite.png", "yosemite_large.png", "Techno")';
      $initDummyQuizzes[] = ' INSERT INTO quiz_categories VALUES (1, 1)';
      $initDummyQuizzes[] = ' INSERT INTO quiz_categories VALUES (2, 2)';
      $initDummyQuizzes[] = ' INSERT INTO quiz_categories VALUES (2, 3)';
      $initDummyQuizzes[] = ' INSERT INTO categories (name, identifying_name, description) VALUES ("Food", "food", "Bite-size quizzes about your favorite dishes to please your taste buds")';
      $initDummyQuizzes[] = ' INSERT INTO categories (name, identifying_name, description) VALUES ("Places", "places", "How well do you know your planet?")';
      $initDummyQuizzes[] = ' INSERT INTO categories (name, identifying_name, description) VALUES ("People", "people", "Stars aren\'t just in the sky.")';

      /* Copy-pasteable form:
      INSERT INTO quizzes (title, description, icon_small, icon_large, author)
      VALUES ("What ice cream flavor are you?",
      "Based on your personality traits, we'll match you with the ice cream flavor you are most similar to!",
      "icecream.png", "icecream_large.png", "Techno");
      INSERT INTO quizzes (title, description, icon_small, icon_large, author)
      VALUES ("We'll pick your next vacation based on your favorite actors!",
      "We all need vacation inspiration, right?",
      "yosemite.png", "yosemite_large.png", "Techno");
      INSERT INTO quiz_categories
      VALUES (1, 1);
      INSERT INTO quiz_categories
      VALUES (2, 2);
      INSERT INTO quiz_categories
      VALUES (2, 3);
      INSERT INTO categories (name, identifying_name, description)
      VALUES ("Food", "food", "Bite-size quizzes about your favorite dishes to please your taste buds");
      INSERT INTO categories (name, identifying_name, description)
      VALUES ("Places", "places", "How well do you know your planet?");
      INSERT INTO categories (name, identifying_name, description)
      VALUES ("People", "people", "Stars aren't just in the sky.");
      */


    } else {
      echo "Error creating database.";
      die('Could not create db.');
    }
  } else {
    //echo "Database already exists";
  }

  // Update connection to reflect ThinkQuizzy database.
  $conn = mysqli_connect($servernameDB, $usernameDB, $passwordDB, $nameDB);

  if($conn) {

    // Create table for storing quizzes
    $sql = "CREATE TABLE quizzes (
      id INT AUTO_INCREMENT PRIMARY KEY,
      title VARCHAR(400),
      description VARCHAR(30000),
      icon_small VARCHAR(2000),
      icon_large VARCHAR(2000),
      author VARCHAR(400),
      views INT DEFAULT 0,
      recent_views INT DEFAULT 0,
      reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    /* Sample query:
    INSERT INTO quizzes (title, description, icon_small, icon_large, author)
    VALUES ("What ice cream flavor are you?",
    "Based on your personality traits, we'll match you with the ice cream flavor you are most similar to!",
    "icecream.png", "icecream_large.png", "Alden");
    INSERT INTO quizzes (title, description, icon_small, icon_large, author)
    VALUES ("We'll pick your next vacation based on your favorite actors!",
    "We all need vacation inspiration, right?",
    "yosemite.png", "yosemite_large.png", "Alden");
    */

    if (mysqli_query($conn, $sql)) {
        //echo "Table MyGuests created successfully";
    } else {
        //echo "Error creating table: " . mysqli_error($conn);
    }

    // Create table for storing which quizzes have which categories
    $sql = "CREATE TABLE quiz_categories (
      quiz_id INT,
      category_id INT
    )";

    if (mysqli_query($conn, $sql)) {
        //echo "Table MyGuests created successfully";
    } else {
        //echo "Error creating table: " . mysqli_error($conn);
    }

    /* Sample query:
    INSERT INTO quiz_categories
    VALUES (1, 1);
    INSERT INTO quiz_categories
    VALUES (2, 2);
    INSERT INTO quiz_categories
    VALUES (2, 3);
    */

    // Create table for categories and their descriptions.
    $sql = "CREATE TABLE categories (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(400),
      identifying_name VARCHAR(400),
      description VARCHAR(30000),
      num_quizzes_with_category INT DEFAULT 0
    )";

    if (mysqli_query($conn, $sql)) {
        //echo "Table MyGuests created successfully";
    } else {
        //echo "Error creating table: " . mysqli_error($conn);
    }

    /* Sample query:
    INSERT INTO categories (name, identifying_name, description)
    VALUES ("Food", "food", "Bite-size quizzes about your favorite dishes to please your taste buds");
    INSERT INTO categories (name, identifying_name, description)
    VALUES ("Places", "places", "How well do you know your planet?");
    INSERT INTO categories (name, identifying_name, description)
    VALUES ("People", "people", "Stars aren't just in the sky.");
    */

    if(count($initDummyQuizzes) > 0) {
      foreach($initDummyQuizzes as $initDummyQuiz) {
        if (mysqli_query($conn, $initDummyQuiz)) {
            //echo "Added dummy quiz";
        } else {
            //echo "Failed to add dummy quiz<br />$initDummyQuiz";
        }
      }
    }


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
      const extraCategories = ["Pets", "Celebrities", "History", "Food", "Movies", "Books", "Music", "Math", "Science", "Language"];

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
