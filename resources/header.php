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
      $initDummyQuizzes[] = ' INSERT INTO quizzes (title, description, icon_small, icon_large, author, scoring_mode) VALUES ("Can you identify these commonly mislabeled states?", "Not even all Americans can do this.", "statesquiz.png", "statesquiz_large.png", "Techno", "MULTIPLE_CHOICE_CORRECTNESS")';
      $initDummyQuizzes[] = ' INSERT INTO quiz_categories VALUES (1, 1)';
      $initDummyQuizzes[] = ' INSERT INTO quiz_categories VALUES (2, 2)';
      $initDummyQuizzes[] = ' INSERT INTO quiz_categories VALUES (2, 3)';
      $initDummyQuizzes[] = ' INSERT INTO quiz_categories VALUES (3, 2)';
      $initDummyQuizzes[] = ' INSERT INTO categories (name, identifying_name, description) VALUES ("Food", "food", "Bite-size quizzes about your favorite dishes to please your taste buds")';
      $initDummyQuizzes[] = ' INSERT INTO categories (name, identifying_name, description) VALUES ("Places", "places", "How well do you know your planet?")';
      $initDummyQuizzes[] = ' INSERT INTO categories (name, identifying_name, description) VALUES ("People", "people", "Stars aren\'t just in the sky.")';
      $initDummyQuizzes[] = ' INSERT INTO categories (name, identifying_name, description) VALUES ("Personality", "personality", "Learn more about yourself!")';

      // Test correctness quiz
      $initDummyQuizzes[] = 'INSERT INTO questions (quizid, questiontext, image) VALUES (3, "First, the northeast. What\'s the state on the left of this pair?", "states1.png")';
      $initDummyQuizzes[] = 'INSERT INTO questions (quizid, questiontext, image) VALUES (3, "Now we head to Appalachia. Which state is this?", "states2.png")';
      $initDummyQuizzes[] = 'INSERT INTO questions (quizid, questiontext, image) VALUES (3, "Onto the deep south! What state does the arrow point to?", "states3.png")';
      $initDummyQuizzes[] = 'INSERT INTO questions (quizid, questiontext, image) VALUES (3, "Those out west may be familar, but can you tell which state is colored blue here?", "states4.png")';
      $initDummyQuizzes[] = 'INSERT INTO questions (quizid, questiontext, image) VALUES (3, "Let\'s increase the difficulty. What boxy state does the arrow point to?", "states5.png")';
      $initDummyQuizzes[] = 'INSERT INTO questions (quizid, questiontext, image) VALUES (3, "If you guess the state in tan, you really know your US geography.", "states6.png")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (1, -1, "Massachusetts", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (1, -1, "Connecticut", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (1, -1, "Vermont", "{\"correct\": 1}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (1, -1, "New Hampshire", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (2, -1, "West Virginia", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (2, -1, "Kentucky", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (2, -1, "Tennessee", "{\"correct\": 1}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (2, -1, "Arkansas", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (3, -1, "Louisiana", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (3, -1, "Alabama", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (3, -1, "Georgia", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (3, -1, "Mississippi", "{\"correct\": 1}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (4, -1, "Oregon", "{\"correct\": 1}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (4, -1, "Washington", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (4, -1, "Idaho", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (4, -1, "California", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (5, -1, "Utah", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (5, -1, "Wyoming", "{\"correct\": 1}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (5, -1, "Colorado", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (5, -1, "Montana", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (6, -1, "Kansas", "{\"correct\": 1}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (6, -1, "Nebraska", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (6, -1, "South Dakota", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (6, -1, "Iowa", "{\"correct\": 0}")';
      $initDummyQuizzes[] = 'INSERT INTO results (quizid, image, resultTitle, resultText, weight_category, threshold) VALUES (3, "statesresult1.png", "Out of states", "You\'re probably not from any of these areas, are you?", "correct", 0)';
      $initDummyQuizzes[] = 'INSERT INTO results (quizid, image, resultTitle, resultText, weight_category, threshold) VALUES (3, "statesresult2.png", "Typical American", "You know some of these areas, but not all.", "correct", 2)';
      $initDummyQuizzes[] = 'INSERT INTO results (quizid, image, resultTitle, resultText, weight_category, threshold) VALUES (3, "statesresult3.png", "Looked at a map", "Geography\'s not too difficult for you, but there\'s room for improvement!", "correct", 4)';
      $initDummyQuizzes[] = 'INSERT INTO results (quizid, image, resultTitle, resultText, weight_category, threshold) VALUES (3, "statesresult4.png", "Geography Geek", "Congrats, you actually know some tricky parts of the US map!", "correct", 6)';

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
      scoring_mode VARCHAR(200) DEFAULT 'MULTIPLE_CHOICE_MOST_LIKE',
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

    // Create tables for questions and answers
    $sql = "CREATE TABLE questions (
      questionid INT AUTO_INCREMENT PRIMARY KEY,
      quizid INT,
      questiontext VARCHAR(30000),
      image VARCHAR(2000)
      );";

    if (mysqli_query($conn, $sql)) {
        //echo "Table questions created successfully";
    } else {
        //echo "Error creating table: " . mysqli_error($conn);
    }

    // Set optionnumber to -1 to indicate random order.
    // weight should be in the following format: '{"categoryname": number, "categoryname2": number}' (aka json string)
    $sql = "CREATE TABLE answers (
      answerid INT AUTO_INCREMENT PRIMARY KEY,
      questionid INT,
      optionnumber INT,
      answertext VARCHAR(30000),
      weight VARCHAR(10000)
    );";

    if (mysqli_query($conn, $sql)) {
        //echo "Table answers created successfully";
    } else {
        //echo "Error creating table: " . mysqli_error($conn);
    }


    // Create table for potential quiz results
    // weight_category should correspond to the categoryname you want this to be associated with
    // threshold is the minimum threshold for this result to display. Use when assigning multiple results to the same weight.
    $sql = "CREATE TABLE results (
      resultid INT AUTO_INCREMENT PRIMARY KEY,
      quizid INT,
      image VARCHAR(2000),
      resultTitle VARCHAR(2000),
      resultText VARCHAR(30000),
      weight_category VARCHAR(2000),
      threshold INT DEFAULT 0
    )";

    if (mysqli_query($conn, $sql)) {
        //echo "Table answers created successfully";
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
            echo "Failed to add dummy quiz<br />$initDummyQuiz<br />"  . mysqli_error($conn) . "<br />";
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
