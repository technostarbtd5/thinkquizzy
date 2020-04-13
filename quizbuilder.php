<!DOCTYPE html>
<html lang="en" dir="ltr">
  <?php
    $titleTag = "Quiz Builder - ";
    include("resources/header.php");
  ?>
  <div id="builderInfo">
    Build your new quiz here!
  </div>
  <div id="builderInfoDesc">

  </div>
  <form action="resources/quizbuildersubmit.php" method="post" id="builderQuizForm" onsubmit="return validateQuizBuilder();">
    <div id="builderQuizInfo">
      <div class="builderQuizInfoSegment">
        <label for="builderQuizTitle" class="builderQuizTitle">Quiz Title:</label>
        <input type="text" id="builderQuizTitle" name="builderQuizTitle" class="builderQuizTitle"/>
      </div>
      <div class="builderQuizInfoSegment">
        <label for="builderQuizDesc" class="builderQuizDesc">Quiz Description:</label>
        <input type="text" id="builderQuizDesc" name="builderQuizDesc" class="builderQuizDesc"/>
      </div>
      <div class="builderQuizInfoSegment">
        <label for="builderQuizAuthor" class="builderQuizDesc">Quiz Author:</label>
        <input type="text" id="builderQuizAuthor" name="builderQuizAuthor" class="builderQuizDesc"/>
      </div>
    </div>
    <div id="builderQuizMode" >
      <div class="builderQuizMode">
        Quiz Scoring:
      </div>
      <div class="builderQuizModeButton" id="builderModeCorrect">
        Correctness
      </div>
      <div class="builderQuizModeButton" id="builderModeSimilar">
        Most Similar
      </div>
    </div>
    <div id="builderQuizQuestions">
      <?php
        // Save form if submitted improperly
      ?>
      <!--
      <div id="builderQuizQuestion_0">
        <div class="builderQuizQuestionInfo" id="builderQuizQuestion_0_Info">
          Question 1:
        </div>
        <div>
          <label for="builderQuizQuestion_0_Title">Question Text:</label>
          <input type="text" id="builderQuizQuestion_0_Title" name="builderQuizQuestion_0_Title" />
        </div>
        <ul>
          <li>
            <label for="builderQuizQuestion_0_Answer_0">Option 1:</label>
            <input type="text" id="builderQuizQuestion_0_Answer_0" name="builderQuizQuestion_0_Answer_0" />
            <span class="builderQuizQuestionRemoveAnswer" id="builderQuizQuestion_0_RemoveAnswer_0">-</span>
          </li>
          <div class="builderQuizQuestionAddAnswer" id="builderQuizQuestion_0_AddAnswer">
            Add Answer
          </div>
        </ul>
      </div>
    -->

    </div>
    <div id="builderQuizAddQuestion">
      Add Question
    </div>
    <input type="submit" name="submit" value="Submit" class="pageButton"/>
    <div id="builderFormErrors">

    </div>

  </form>
  <script type="text/javascript" src="resources/quizbuilder.js"></script>

  <?php include("resources/footer.php"); ?>
</html>
