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
        $resultquiz = mysqli_query($conn, $sql);
        if($resultquiz && mysqli_num_rows($resultquiz) > 0) {
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
            $quizrow = mysqli_fetch_assoc($resultquiz);
            $mode = $quizrow["scoring_mode"];
            if($mode == "MULTIPLE_CHOICE_CORRECTNESS") {
              // This should only have 'correct' as a parameter.
              echo '
              <div class="resultText">
                You got...<br />
                <div class="resultTextScore">
                  ' . $scores['correct'] . ' / ' . $num_questions . '
                </div>
              </div>
              ';
              $sql = 'SELECT * FROM results WHERE quizid=' . mysqli_real_escape_string($conn, $_GET['id']) . ' AND weight_category="correct"';

              // Maybe "result" wasn't the best naming convention here.
              $resultresults = mysqli_query($conn, $sql);
              if($resultresults && mysqli_num_rows($resultresults) > 0) {
                $closestScore = PHP_INT_MIN;
                $toOutput = "";
                while($resultresult = mysqli_fetch_assoc($resultresults)) {
                  if($resultresult["threshold"] >= $closestScore && $resultresult["threshold"] <= $scores['correct']) {
                    $closestScore = $resultresult["threshold"];
                    $toOutput = '
                    <div class="resultItemImage">
                      <img src="resources/quiz_icons/'. htmlspecialchars($resultresult["image"]). '" width="600" />
                    </div>
                    <div class="resultItemInfo">
                      <div class="resultItemTitle">
                        '. htmlspecialchars($resultresult['resultTitle']) .'
                      </div>
                      <div class="resultItemText">
                        '. htmlspecialchars($resultresult['resultText']) .'
                      </div>
                    </div>
                    ';
                  }
                }
                echo $toOutput;
              } else {
                // No results found. Assume this quiz didn't assign any.
              }
            } else {
              // Default mode is 'MULTIPLE_CHOICE_MOST_LIKE'
              // First, find highest score
              $highestscoretype = "";
              $highestscorevalue = PHP_INT_MIN;
              foreach ($scores as $type => $score) {
                if($score >= $highestscorevalue) {
                  $highestscoretype = $type;
                  $highestscorevalue = $score;
                }
              }

              echo '
              <div class="resultText">
                You got...<br />
              </div>
              ';


              // Now loop through potential results and assign the closest fit to this one.
              $sql = 'SELECT * FROM results WHERE quizid=' . mysqli_real_escape_string($conn, $_GET['id']) . ' AND weight_category=' . mysqli_real_escape_string($conn, $type);
              $resultresults = mysqli_query($conn, $sql);
              if($resultresults && mysqli_num_rows($resultresults) > 0) {
                $closestScore = PHP_INT_MIN;
                $toOutput = "";
                while($resultresult = mysqli_fetch_assoc($resultresults)) {
                  if($resultresult["threshold"] >= $closestScore && $resultresult["threshold"] <= $highestscorevalue) {
                    $closestScore = $resultresult["threshold"];
                    $toOutput = '
                    <div class="resultItemImage">
                      <img src="resources/quiz_icons/'. htmlspecialchars($resultresult["image"]). '" width="600" />
                    </div>
                    <div class="resultItemInfo">
                      <div class="resultItemTitle">
                        '. htmlspecialchars($resultresult['resultTitle']) .'
                      </div>
                      <div class="resultItemText">
                        '. htmlspecialchars($resultresult['resultText']) .'
                      </div>
                    </div>
                    ';
                  }
                }
                echo $toOutput;
              } else {
                // No results found. Assume this quiz didn't assign any for that weight category.
                echo '
                <div class="resultText">
                  Nothing! You didn&apos;t match any quiz results.
                </div>
                ';
              }
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
