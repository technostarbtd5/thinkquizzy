<!DOCTYPE html>
<html lang="en" dir="ltr">
  <?php
    // TEMPORARY naming convention; replace with SQL query to get quiz name
    $titleTag = "Quiz Builder Submit - ";
    include("resources/header.php");
  ?>

  <div>

    <?php
      // Handle form submission.
      include("resources/dbconnect.php");


      if($conn) {
        $formValid = true;
        $sql = "";
        $quizMode = "MULTIPLE_CHOICE_MOST_LIKE";

        // Check initial quiz metadata
        if(isset($_POST["builderQuizTitle"]) && isset($_POST["builderQuizDesc"]) && isset($_POST["builderQuizAuthor"]) && isset($_POST["builderQuizModeOutput"])) {
          if($_POST["builderQuizModeOutput"] == "builderModeCorrect") {
            $quizMode = "MULTIPLE_CHOICE_CORRECTNESS";
          }
          $sql .= 'INSERT INTO quizzes (title, description, icon_small, icon_large, author, scoring_mode) VALUES ("'. mysqli_real_escape_string($conn, $_POST["builderQuizTitle"]) .'", "' . mysqli_real_escape_string($conn, $_POST["builderQuizDesc"]) . '", "yosemite.png", "yosemite_large.png", "' . mysqli_real_escape_string($conn, $_POST["builderQuizAuthor"]) . '", "' . $quizMode . '");';
          echo htmlspecialchars($sql);
        } else {
          $formValid = false;
        }

        foreach ($_POST as $key => $value) {
          echo htmlspecialchars("$key, $value") . "<br />";
        }

        // Validate quiz questions and answers

        // Also count total number of answers
        $answerCount = 0;
        $i = 0;
        while(isset($_POST["builderQuizQuestion_" . $i . "_Title"])) {
          echo "Found question " . "builderQuizQuestion_" . $i . "_Title" . "<br />";
          $j = 0;
          while(isset($_POST["builderQuizQuestion_" . $i . "_Answer_" . $j . "_Text"])) {
            echo "Found answer " . "builderQuizQuestion_" . $i . "_Answer_" . $j . "_Text" . "<br />";
            $j++;
            $answerCount++;
          }
          if($j == 0) {
            // ERROR: Question must have associated answers!
            $formValid = false;
          }
          $i++;
        }
        echo "Found $answerCount answers.<br />";
        if($i == 0) {
          // ERROR: Quiz must have associated questions!
          $formValid = false;
        }
        echo "All questions have answers? " . ($formValid ? "Yes<br />" : "No<br />");

        // Find Weights
        $weights = array("correct");
        foreach ($_POST as $key => $value) {
          if(substr($key, 0, strlen("builderQuizWeightsItem_")) == "builderQuizWeightsItem_" && strlen($key) > strlen("builderQuizWeightsItem_")) {
            $temp = substr($key, strlen("builderQuizWeightsItem_"));
            $weights[] = substr($temp, 0, strlen($temp) - strlen("_Title"));
          }
        }
        print_r($weights);
        echo "<br />";
        // Ensure all answers have all weights

        foreach ($weights as $weight) {
          // For each weight, check all answers have this
          for($i = 0; $i < $answerCount; $i++) {
            if(!isset($_POST["builderQuizAnswer_" . $i . "_Weight_" . $weight])) {
              // Weight does not exist for answer i!
              echo htmlspecialchars("ERROR: Answer $i does not have weight $weight!<br />");
              $formValid = false;
            }
          }
        }
        echo "Weights analyzed<br />";

        // Form should be valid at this point.


        // Execute SQL!
        // $formValid = false;
        if($formValid) {
          $resultQuizCreate = mysqli_query($conn, $sql);

          if($resultQuizCreate) {
            echo "Successfully executed command: " . htmlspecialchars($sql) . "<br />";
            $quiz_id = mysqli_insert_id($conn);
            echo '<div id="quizid" data-quizid="'.$quiz_id.'">Quiz ID is '.$quiz_id.'</div>';
            $i = 0;
            $answerNumber = 0;
            while(isset($_POST["builderQuizQuestion_" . $i . "_Title"])) {
              $sql = 'INSERT INTO questions (quizid, questiontext, image) VALUES (' . $quiz_id . ', "' . mysqli_real_escape_string($conn, $_POST["builderQuizQuestion_" . $i . "_Title"]) . '", "yosemite_large.png")';
              $resultQuestionCreate = mysqli_query($conn, $sql);
              if($resultQuestionCreate) {
                echo "Successfully executed command: " . htmlspecialchars($sql) . "<br />";
                $questionID = mysqli_insert_id($conn);
                echo "QuestionID is: " . $questionID . "<br />";
                $j = 0;
                while(isset($_POST["builderQuizQuestion_" . $i . "_Answer_" . $j . "_Text"])) {
                  // Convert weights to json
                  $weightsJSON = "{";
                  foreach ($weights as $weight) {
                    // Returns 0 if you submit a non-int string
                    $weightVal = intval($_POST["builderQuizAnswer_" . $answerNumber . "_Weight_" . $weight]);
                    // convert $weightVal to 1 for correctness quizzes if a non-1 value was submitted for some reason
                    if($quizMode == "MULTIPLE_CHOICE_CORRECTNESS") {
                      if($weightVal != 0 && $weightVal != 1) {
                        $weightVal = 1;
                      }
                    }
                    $weightsJSON .= '"' . $weight . '": ' . $weightVal . ', ';
                  }
                  $weightsJSON = mysqli_real_escape_string($conn, substr($weightsJSON, 0, strlen($weightsJSON) - 2) . "}");
                  $sql = 'INSERT INTO answers (questionid, optionnumber, answertext, weight) VALUES (' . $questionID . ', ' . $j . ', "' . mysqli_real_escape_string($conn, $_POST["builderQuizQuestion_" . $i . "_Answer_" . $j . "_Text"]) . '", "' . $weightsJSON . '")';
                  $resultAnswerCreate = mysqli_query($conn, $sql);
                  if($resultAnswerCreate) {
                    echo "Successfully executed command: " . htmlspecialchars($sql) . "<br />";
                  } else {
                    echo "Error inserting quiz: " . mysqli_error($conn) . "<br />";
                    echo "Failed to execute command: " . htmlspecialchars($sql) . "<br />";
                  }
                  $answerNumber++;
                  $j++;
                }
              } else {
                echo "Error inserting question: " . mysqli_error($conn) . "<br />";
                echo "Failed to execute command: " . htmlspecialchars($sql) . "<br />";
              }
              $i++;
            }
          } else {
            echo "Error inserting quiz: " . mysqli_error($conn) . "<br />";
            echo "Failed to execute command: " . htmlspecialchars($sql) . "<br />";
          }
        } else {
          echo "Form not valid!<br />";
        }



      }
    ?>

  </div>
  <div style="padding: 8em">Rerouting in 5 seconds...</div>
  <script>
    setTimeout(function() {$(location).attr('href', "quiz.php?id=" + $("#quizid").data("quizid"))}, 5000);
  </script>
  <?php include("resources/footer.php"); ?>
</html>
