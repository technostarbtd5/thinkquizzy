<!DOCTYPE html>
<html lang="en" dir="ltr">
<!-- <head>
	<title>Quiz Page</title>
</head> -->
  <?php

  	// Connect to the database
  	include("resources/header.php");
	?>



	<?php


  // Get the data we need from the SQL database
  $_QuizId = htmlspecialchars($_GET["id"]);

  $sql = "SELECT * FROM quizzes WHERE id = " . $_QuizId;
  $_Quiz = mysqli_query($conn, $sql);
  $_QuizArray = mysqli_fetch_assoc($_Quiz);

  $sql = "SELECT * FROM questions WHERE quizid = " .mysqli_real_escape_string($conn, $_QuizId);
  $_Questions = mysqli_query($conn, $sql);
    if (isset($_Questions) == FALSE) {
  	echo '$_Questions null';
  };

  $_QuestionsArray = mysqli_fetch_assoc($_Questions);
  if (isset($_QuestionsArray["questionid"]) == FALSE) {
  	echo 'its null';
  };
  $questionids = $_QuestionsArray["questionid"];

  $sql = "SELECT * FROM answers WHERE questionid IN (SELECT questionid FROM questions WHERE quizid ='$questionids')";
  $_Answers = mysqli_query($conn, $sql);

  ?>
  <div id="quizTitle"><?php echo htmlspecialchars($_QuizArray['title']);?></div>
  <div id="quizDesc"><?php echo htmlspecialchars($_QuizArray['description']);?></div>
		<script>
			function validateQuizSubmit() {
        let isValid = true;
        $(".quizQuestion").find("input").each(function(i) {
          let hasMatch = false;
          let val = $(this).val();
          $(this).parent().find(".answerchoice").each(function(j) {
            if($(this).data("answerid") == val) {
              hasMatch = true;
            }
          });
          if(!hasMatch) {
            isValid = false;
            $("#quizErrorAlerts").html("Please answer all questions!");
          }
        });
        return isValid;
      }
		</script>

      <?php
      echo '<form  id="quizForm" method="post" action="results.php?id='.$_QuizId.'" onsubmit="return validateQuizSubmit();">';
      // Loop through the questions, for each one echo the questiontext
      $sql = "SELECT * FROM questions WHERE quizid = " .mysqli_real_escape_string($conn, $_QuizId);
      $_Questions = mysqli_query($conn, $sql);
        if (isset($_Questions) == FALSE) {
        echo '$_Questions null';
      };
      	while($row = mysqli_fetch_assoc($_Questions)) {
      		echo '<div class="quizQuestion">';
      		echo '<div class="questiontext" >'. htmlspecialchars($row["questiontext"]) .'</div>';
      		echo '<img class="quizQuestionImage" width="500" src="resources/quiz_icons/' . $row["image"];
      		echo '">';
					echo '<input type="hidden" id="'.htmlspecialchars($row["questionid"]).'" name="'.htmlspecialchars($row["questionid"]).'" value="" />';
      		// Get the answers for the current question
      		$_answers = $conn->query("SELECT * FROM answers WHERE questionid =" . $row["questionid"]);

      		// Use JQuery to bind the click event??

      		//echo '<div>';
      		while($_row = mysqli_fetch_assoc($_answers)) {
		  			// Each answer choice for a question has the same name so they all act as a single input, meaning the user can only select one answer at a time
		  			echo '<div class="answerchoice" data-questionid="'.htmlspecialchars($row["questionid"]).'" data-answerid="'.htmlspecialchars($_row["answerid"]).'">'.htmlspecialchars($_row["answertext"])."</div>";

		  		}

      		echo '</div>';
      		echo '<br>';
      	}
        echo '<div id="quizErrorAlerts"></div>';
      	echo '<input class="submitbutton pageButton" type="submit" value="Submit Quiz" />'
      ?>

    </form>
    <br />
    <br />


  <!-- </div> -->
  <?php include("resources/footer.php"); ?>
</html>

<script>
  function refreshAnswerChoices() {
    $(".quizQuestion").find("input").each(function(i) {
      let activeAnswer = $(this).val();
      $(this).parent().find(".answerchoice").each(function(j) {
        //console.log("refreshing for activeAnswer " + activeAnswer + " versus " + $(this).data("answerid"));
        if($(this).data("answerid") == activeAnswer) {
          $(this).addClass("answerSelected");
        } else {
          $(this).removeClass("answerSelected");
        }
      })
    })
  }

  $(document).ready(function() {
  	//find all the answerchoices, make them into form items

  refreshAnswerChoices();

    $(".answerchoice").click(function() {
    	//alert("Answer clicked");
      console.log($(this).data("questionid") + ", " + $(this).data("answerid"))
      //console.log($(this).parent().find("#" + $(this).data("questionid")).eq(0));
    	$(this).parent().find("#" + $(this).data("questionid")).eq(0).val($(this).data("answerid"));
      console.log($(this).parent().find("#" + $(this).data("questionid")).eq(0).val());
      refreshAnswerChoices();
    });

  });
</script>
