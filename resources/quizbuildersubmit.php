<?php
  // Handle form submission.
  include("dbconnect.php");
  if($conn) {
    $formValid = true;
    $sql = "";

    // Check initial quiz metadata
    if(isset($_POST["builderQuizTitle"]) && isset($_POST["builderQuizDesc"]) && isset($_POST["builderQuizAuthor"])) {

      $sql .= 'INSERT INTO quizzes (title, description, icon_small, icon_large, author) VALUES ("'. mysqli_real_escape_string($conn, $_POST["builderQuizTitle"]) .'", "' . mysqli_real_escape_string($conn, $_POST["builderQuizDesc"]) . '", "yosemite.png", "yosemite_large.png", "' . mysqli_real_escape_string($conn, $_POST["builderQuizAuthor"]) . '");';
      echo htmlspecialchars($sql);
    } else {
      $formValid = false;
    }

    // Validate quiz questions and answers
    $i = 0;
    while(isset($_POST["builderQuizQuestion_" . $i . "_Title"])) {
      $j = 0;
      while(isset($_POST["builderQuizQuestion_" . $i . "_Answer_" . $j . "_Text"])) {
        $j++;
      }
      if($j == 0) {
        // ERROR: Question must have associated answers!
        $formValid = false;
      }
    }
    if($i == 0) {
      // ERROR: Quiz must have associated questions!
      $formValid = false;
    }

    // Execute SQL!
    $formValid = false;
    if($formValid) {
      $resultQuizCreate = mysqli_query($conn, $sql);
      if($resultQuizCreate) {
        $quiz_id = mysqli_insert_id($conn);
        $i = 0;
        while(isset($_POST["builderQuizQuestion_" . $i . "_Title"])) {
          $sql = 'INSERT INTO questions (quizid, questiontext, image) VALUES (' . $quiz_id . ', "' . mysqli_real_escape_string($conn, $_POST["builderQuizQuestion_" . $i . "_Title"]) . '", "yosemite_large.png")';
        }
      }
    }



  }
?>
