<!DOCTYPE html>
<html lang="en" dir="ltr">
  <?php
    // TEMPORARY naming convention; replace with SQL query to get quiz name
    $titleTag = isset($_GET['name']) ? htmlspecialchars($_GET['name']) . " - " : "";
    include("resources/header.php");
  ?>
  <div style="padding: 8em">
    <!--Content belongs here!-->
    <?php
      // First, get questions and answers from database.
      if(isset($_GET['id'])) {
        // Verify real quiz
        $sql = 'SELECT * FROM quizzes WHERE id=' . mysqli_real_escape_string($conn, $_GET['id']) . ' LIMIT 1;';
        $result = mysqli_query($conn, $sql);
        if($result && mysqli_num_rows($result) > 0) {
          //$titleTag = htmlspecialchars(mysqli_fetch_assoc($result)["name"]) . " - ";

          // Select question information
          $sql = 'SELECT * FROM questions WHERE quizid=' . mysqli_real_escape_string($conn, $_GET['id']) . ';';
          $result = mysqli_query($conn, $sql);
          $num_questions = mysqli_num_rows($result);
          if($result && $num_questions > 0) {
            $scores = array();
            while($question = mysqli_fetch_assoc($result)) {
              // Get answers for each question and compare to input answer
              if(isset($_POST[$question['questionid']])) {
                $sql = 'SELECT * FROM answers WHERE questionid=' . mysqli_real_escape_string($conn, $question['questionid']) . ';';
                $result2 = mysqli_query($conn, $sql);
                if($result2 && mysqli_num_rows($result2) > 0) {
                  // Loop through answers and see if any match $_POST[$question['questionid']]
                  $foundanswer = false;
                  while($answer = mysqli_fetch_assoc($result2)) {

                    if($_POST[$question['questionid']] == $answer['answerid']) {
                      $foundanswer = true;
                      // Decode JSON and apply weights to $scores
                      $weights = json_decode($answer['weight']);
                      if (json_last_error() === JSON_ERROR_NONE) {
                        // JSON is valid
                        // Loop through all weights and apply them to their corresponding values in "scores"
                        foreach($weights as $type => $weight) {
                          if(is_numeric($weight)) {
                            if(isset($scores[$type])) {
                              $scores[$type] += $weight;
                            } else {
                              $scores[$type] = $weight;
                            }
                          } else {
                            // ERROR: weight must be a number!
                            echo "ERROR: Answer weight is not a number! Contact administrator to fix.";
                          }
                        }

                      } else {
                        // ERROR: invalid answer weights syntax!
                        echo "ERROR: Answer weight is not JSON! Contact administrator to fix.";
                      }
                      break;
                    }
                  }
                  if(!$foundanswer) {
                    // Question was filled out with incorrect number.
                    // TODO: Return user to page with answers saved
                  }
                } else {
                  // question lacks associated answers
                  echo "ERROR: Failed to find associated answers for question " . htmlspecialchars($question['questionid']) . ": \"" . htmlspecialchars($question["questiontext"]) . "\"! Error " . mysqli_error($conn) . ", sql: " . htmlspecialchars($sql);
                }
              } else {
                // ERROR: post request does not feature this question
                echo "WARNING: Question " . htmlspecialchars($question['questionid']) . ": \"" . htmlspecialchars($question["questiontext"]) . "\" missing from the submission! Error " . mysqli_error($conn);
                // TODO: Return user to page with answers saved
              }
            }


            // Evaluate $scores
            // Currently just supports correctness quizzes
            // TODO: Extend evaluation to support multiple potential results
            /*foreach ($scores as $type => $score) {

            }*/


            // Hardcoded selector, replace with SQL query of "rubric" DATABASE
            $mode = "MULTIPLE_CHOICE";
            if($mode == "MULTIPLE_CHOICE") {
              echo '
              <div class="resultText">
                You got...<br />
                <div class="resultTextScore">
                  ' . $scores['correct'] . ' / ' . $num_questions . '
                </div>
              </div>
              ';
            }


          } else {
            // No questions associated with quiz
            echo "ERROR: Quiz lacks questions!";
          }

        } else {
          // id does not match any known quizzes
          echo "ERROR 404: Quiz not found!";
        }
      } else {
        // No id
        echo "ERROR: Must include an ID!";
      }
    ?>
    </br>
    </br>

    <a class="pageButton" href="quiz.php">&#129028; Return</a>

    <a class="pageButton" href="index.php">&#129093; Home</a>

  </div>
  <?php include("resources/footer.php"); ?>
</html>
