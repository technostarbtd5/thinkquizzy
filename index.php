<!DOCTYPE html>
<html lang="en" dir="ltr">
  <?php
    // If this is a valid category, include it in the title!

    $servernameDB = 'localhost';
    $usernameDB = 'root';
    $passwordDB = '';
    $nameDB = 'ThinkQuizzyDB';

    $conn = mysqli_connect($servernameDB, $usernameDB, $passwordDB, $nameDB);

    if($conn) {
      if(isset($_GET['sort'])) {
        // Get category from identifying name
        $sort = mysqli_real_escape_string($conn, $_GET['sort']);
        //echo htmlspecialchars($sort);
        $sql = "SELECT * FROM categories WHERE identifying_name='" . $sort ."';";
        //echo htmlspecialchars($sql);
        $result = mysqli_query($conn, $sql);

        // Now check whether you have the quiz category.
        if($result && mysqli_num_rows($result) > 0) {
          $titleTag = htmlspecialchars(mysqli_fetch_assoc($result)["name"]) . " - ";
        }
      }
    }
    include("resources/header.php");
  ?>
  <div class="gridcontainer">
    <div class="column">

        <?php

          $sql = "";
          if(isset($_GET['sort'])) {
            // Get category from identifying name
            $sort = mysqli_real_escape_string($conn, $_GET['sort']);
            //echo htmlspecialchars($sort);
            $sql = "SELECT * FROM categories WHERE identifying_name='" . $sort ."';";
            //echo htmlspecialchars($sql);
            $result = mysqli_query($conn, $sql);

            // Now check whether you have the quiz category.
            if($result && mysqli_num_rows($result) > 0) {
              //echo "Found quiz category!";
              // You do! Select all quizzes matching this category.
              $categoryID = mysqli_fetch_assoc($result)["id"];
              $sql = "SELECT * FROM quizzes WHERE id IN (SELECT quiz_id FROM quiz_categories WHERE category_id='$categoryID') LIMIT 20";
            } else {
              //echo "Quiz category not found.";
              // You do not. Act as if this was just the normal homepage.
              $sql = "SELECT * FROM quizzes LIMIT 20";
            }
          } else {
            // Normal homepage.
            $sql = "SELECT * FROM quizzes LIMIT 20";
          }

          // Execute the selection query.
          $result = mysqli_query($conn, $sql);
          if($result && mysqli_num_rows($result) > 0) {
            // Output items as a series of linking rows.
            while($row = mysqli_fetch_assoc($result)) {
              echo '
                <a class="quizItem" href="quiz.php?id=' . htmlspecialchars($row["id"]) . '">
                  <div class="quizItemImage">
                    <img src="resources/quiz_icons/'. htmlspecialchars($row['icon_small']). '" width="150" height="150" />
                  </div>
                  <div class="quizItemInfo">
                    <div class="quizItemTitle">
                      '. htmlspecialchars($row['title']) .'
                    </div>
                    <div class="quizItemDescription">
                      '. htmlspecialchars($row['description']) .'
                    </div>
                  </div>
                </a>
              ';
            }
          } else {
            // Send back an error message just in case.
            echo '
              <div class="quizItem quizItemInfo">
                Your search has not returned any results!
              </div>
            ';
          }

        ?>

    </div>

    <div class="column">
      <div id="sidebar">
        <div id="sidebarInner">

        <?php

          // for top quizzes
          $sql = "SELECT * FROM quizzes ORDER BY views DESC LIMIT 5";
          // Execute the selection query.
          $result = mysqli_query($conn, $sql);
          $listNum = 1;
          if($result && mysqli_num_rows($result) > 0) {
            // Output items as a series of linking rows.
            echo '
                  <div id="sidebarTitle">
                    Top ThinkQuizzy Quizzes:
                  </div>
            ';
            while($row = mysqli_fetch_assoc($result)) {
              echo '
                    <p>
                    <a class="sidebarQuiz" href="quiz.php?id=' . htmlspecialchars($row["id"]) . '">
                      '. $listNum . ". " . htmlspecialchars($row['title']) .'</br>Views: ' . htmlspecialchars($row['views']) . '
                    </a>
                    </p>
              ';
              $listNum++;
            }
          } else {
            // Send back an error message just in case.
            echo '
              <div class="quizItem quizItemInfo">
                Your quiz search has not returned any results!
              </div>
            ';
          }

          // for top categories
          $sql = "SELECT * FROM categories ORDER BY num_quizzes_with_category DESC LIMIT 5";
          // Execute the selection query.
          $result = mysqli_query($conn, $sql);
          $listNum = 1;
          if($result && mysqli_num_rows($result) > 0) {
            // Output items as a series of linking rows.
            echo '
                  </br></br>
                  <div id="sidebarTitle">
                    Top ThinkQuizzy Categories:
                  </div>
            ';
            while($row = mysqli_fetch_assoc($result)) {
              echo '
                    <p>
                    <a class="sidebarQuiz" href="index.php?sort=' . htmlspecialchars($row["identifying_name"])
                      . '">'. $listNum . ". " . htmlspecialchars($row['name']) .'</br>Total Quizzes: '
                      . htmlspecialchars($row['num_quizzes_with_category']) . '
                    </a>
                    </p>
              ';
              $listNum++;
            }
          } else {
            // Send back an error message just in case.
            echo '
              <div class="quizItem quizItemInfo">
                Your quiz search has not returned any results!
              </div>
            ';
          }

        ?>
          
        </div>

      </div>
    </div>

  </div>
  <?php include("resources/footer.php"); ?>
</html>
