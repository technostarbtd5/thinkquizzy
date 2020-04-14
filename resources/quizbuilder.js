// Add buttons to modify form elements
var activeMode = "builderModeCorrect";
var weights = ["correct"];
$(document).ready(function() {
  $("#builderQuizAddQuestion").click(addQuestion);
  addQuestion();
  $(".builderQuizModeButton").each(function() {
    $(this).click(function() {
      activeModeSwap($(this).attr("id"));
    });
    if ($(this).attr("id") == activeMode) {
      $(this).addClass("builderQuizModeButtonActive");
    } else {
      $(this).removeClass("builderQuizModeButtonActive");
    }
  });
  refreshAll();
});

// Unified function to add a question to quizbuilder DOM
function addQuestion() {
  let questionCount = $("#builderQuizQuestions").children().length;
  console.log(questionCount);
  $("#builderQuizQuestions").append(
    `<div id="builderQuizQuestion_${questionCount}" class="builderQuizQuestion">
      <div class="builderQuizQuestionInfo" id="builderQuizQuestion_${questionCount}_Info">
        <label for="builderQuizQuestion_${questionCount}_Title" id="builderQuizQuestion_${questionCount}_Label" class="builderQuizQuestionInfoNumber">Question ${questionCount +
      1}: </label>
        <input type="text" id="builderQuizQuestion_${questionCount}_Title" name="builderQuizQuestion_${questionCount}_Title" />
        <div id="builderQuizQuestion_${questionCount}_Remove" class="builderQuizQuestionRemove">-</div>
      </div>
      <ul>
        <li class="builderQuizOption">
          <label for="builderQuizQuestion_${questionCount}_Answer_0_Text" id="builderQuizQuestion_${questionCount}_Answer_0_Text_Label">Option 1: </label>
          <input type="text" id="builderQuizQuestion_${questionCount}_Answer_0_Text" name="builderQuizQuestion_${questionCount}_Answer_0_Text" />
          <span class="builderQuizAnswerRemove" id="builderQuizQuestion_${questionCount}_Answer_0_Remove">-</span>
          <span class="builderQuizAnswerScoreButton" id="builderQuizQuestion_${questionCount}_Answer_0_ScoreButton">&#10004;</span>
          <span></span>
          <div class="builderQuizAnswerWeights" id="builderQuizQuestion_${questionCount}_Answer_0_Weights"></div>
        </li>
      </ul>
      <div class="builderQuizQuestionAddAnswer" id="builderQuizQuestion_${questionCount}_AddAnswer">
      Add Answer
      </div>
    </div>`
  );

  questionCount = $("#builderQuizQuestions").children().length - 1;
  $(`#builderQuizQuestion_${questionCount}_Remove`).click(removeQuestion);

  // Make sure weights are hidden on question creation!
  $(`#builderQuizQuestion_${questionCount}_Answer_0_Weights`).hide(0);
  refreshAll();
}

// function to remove question
function removeQuestion() {
  console.log("Removing question");
  $.when(
    $(this)
      .parent()
      .parent()
      .remove()
  ).then(function() {
    // re-ID questions
    $("#builderQuizQuestions")
      .children()
      .each(function(i) {
        // Store form inputs!
        let data = [];
        $(this)
          .find("input")
          .each(function(j) {
            //console.log($(this).val());
            data.push($(this).val());
          });

        // Update question IDs
        let html = $(this).html();
        let split = html.split("_");
        for (var j = 1; j < split.length; j++) {
          // update Question_id
          //console.log(split[j - 1]);
          //console.log(typeof split[j - 1]);
          if (
            typeof split[j - 1] === "string" &&
            split[j - 1].substr(-1 * "Question".length) == "Question"
          ) {
            split[j] = i;
          }
        }
        let joined = split.join("_");
        //console.log(joined);
        split = joined.split(" ");
        for (var j = 1; j < split.length; j++) {
          // update Question_id
          //console.log(split[j - 1]);
          //console.log(typeof split[j - 1]);
          if (
            typeof split[j - 1] === "string" &&
            split[j - 1].substr(-1 * "Question".length) == "Question" &&
            typeof split[j] === "string" &&
            split[j].substr(-1) == ":"
          ) {
            split[j] = `${i + 1}:`;
          }
        }
        joined = split.join(" ");
        $(this).html(joined);
        $(this).attr("id", i);

        // restore data
        $(this)
          .find("input")
          .each(function(j) {
            $(this).val(data[j]);
          });
      });
    refreshAll();
  });
}

function removeAnswer() {
  console.log("Removing answer");
  const ul = $(this)
    .parent()
    .parent();
  console.log(ul.html());
  $.when(
    $(this)
      .parent()
      .remove()
  ).then(function() {
    console.log("Answer removed, reordering options");
    console.log(ul.html());
    ul.children().each(function(i) {
      // Store form inputs!
      let data = [];
      $(this)
        .find("input")
        .each(function(j) {
          //console.log($(this).val());
          data.push($(this).val());
        });
      let html2 = $(this).html();
      console.log(html2);
      let split = html2.split("_");
      for (var j = 1; j < split.length; j++) {
        // update Answer_id
        //console.log(split[j - 1]);
        console.log(
          "Comparing " + split[j - 1] + " to Answer for string " + split[j]
        );
        //console.log(typeof split[j - 1]);
        if (
          typeof split[j - 1] === "string" &&
          split[j - 1].substr(-1 * "Answer".length) == "Answer"
        ) {
          split[j] = i;
        }
      }
      let joined = split.join("_");
      console.log(joined);
      split = joined.split(" ");
      for (var j = 1; j < split.length; j++) {
        // update Answer_id
        //console.log(split[j - 1]);
        //console.log(typeof split[j - 1]);
        if (
          typeof split[j - 1] === "string" &&
          split[j - 1].substr(-1 * "Option".length) == "Option" &&
          typeof split[j] === "string" &&
          split[j].substr(-1) == ":"
        ) {
          split[j] = `${i + 1}:`;
        }
      }
      joined = split.join(" ");
      $(this).html(joined);
      $(this).attr("id", i);

      // restore data
      $(this)
        .find("input")
        .each(function(j) {
          $(this).val(data[j]);
        });
    });
    refreshAll();
  });
}

function addAnswer() {
  let questionID = $(this)
    .parent()
    .attr("id")
    .split("_")[1];
  let answerCount = $(this)
    .parent()
    .find("ul")
    .children().length;
  let html = `<li  class="builderQuizOption">
    <label for="builderQuizQuestion_${questionID}_Answer_${answerCount}_Text" id="builderQuizQuestion_${questionID}_Answer_${answerCount}_Text_Label">Option ${answerCount +
    1}: </label>
    <input type="text" id="builderQuizQuestion_${questionID}_Answer_${answerCount}_Text" name="builderQuizQuestion_${questionID}_Answer_${answerCount}_Text" />
    <span class="builderQuizAnswerRemove" id="builderQuizQuestion_${questionID}_Answer_${answerCount}_Remove">-</span>
    <span class="builderQuizAnswerScoreButton" id="builderQuizQuestion_${questionID}_Answer_${answerCount}_ScoreButton">&#10004;</span>
    <span></span>
    <div class="builderQuizAnswerWeights" id="builderQuizQuestion_${questionID}_Answer_${answerCount}_Weights"></div>
  </li>`;
  //console.log(html);
  $(this)
    .parent()
    .find("ul")
    .eq(0)
    .append(html);
  $(`#builderQuizQuestion_${questionID}_Answer_${answerCount}_Weights`).hide(0);
  refreshAll();
}

function refreshAll() {
  $(".builderQuizQuestionAddAnswer")
    .unbind("click")
    .click(addAnswer);
  $(".builderQuizQuestionRemove")
    .unbind("click")
    .click(removeQuestion);
  $(".builderQuizAnswerRemove")
    .unbind("click")
    .click(removeAnswer);
  $("input:text")
    .unbind("click")
    .click(function() {
      $(this).css("background-color", "white");
    });
  $(".builderQuizAnswerScoreButton")
    .unbind("click")
    .click(scoreButtonClick);
  activeModeSwap(activeMode);
}

function validateQuizBuilder() {
  let validForm = true;
  let errorMessage = "";
  $("#builderQuizForm")
    .find("input:text")
    .each(function(i) {
      if (!$(this).val()) {
        $(this).css("background-color", "#ffb9b9");
        validForm = false;
        if (!errorMessage) {
          errorMessage += "ERROR: All form fields must have text!";
        }
      }
    });
  $("#builderFormErrors").html(errorMessage);
  return validForm;
}

// Not a security thing - this just helps out the front end so the user doesn't cause strange behavior.
// All form validation is repeated on the back end.
function sanitizeWeightName(name) {
  return name.replace(/_/g, "-").replace(/ /g, "-");
}

function scoreButtonClick() {
  console.log("Score button clicked");
  if (activeMode == "builderModeSimilar") {
    let selected = false;
    // Calculate whether or not the button was sleected based on button visiblity
    $(this)
      .parent()
      .find(".builderQuizAnswerWeights")
      .each(function() {
        $(this).toggle(0);
        selected = $(this).is(":visible");
      });

    if (selected) {
      updateScoreButtonClasses(
        $(this),
        "builderQuizScoreButtonWeightsSelected"
      );
    } else {
      updateScoreButtonClasses(
        $(this),
        "builderQuizScoreButtonWeightsDeselected"
      );
    }
  } else {
    let selected = false;

    // Calculate whetehr or not button was selected based on "correct" weight value
    $(this)
      .parent()
      .find('*[data-weight="correct"]')
      .each(function() {
        console.log(
          "Clicked on correctness id = " +
            $(this).attr("id") +
            " with value " +
            $(this).val()
        );
        if ($(this).val() != "1") {
          $(this).val("1");
        } else {
          $(this).val("0");
        }
        console.log("Now has value " + $(this).val());
        selected = $(this).val() == 1;
      });
    if (selected) {
      updateScoreButtonClasses($(this), "builderQuizScoreButtonCheckSelected");
    } else {
      updateScoreButtonClasses(
        $(this),
        "builderQuizScoreButtonCheckDeselected"
      );
    }
  }
}

// Function to update classes whenever someone toggles a score button
function updateScoreButtonClasses(button, activeClass) {
  button.removeClass("builderQuizScoreButtonCheckSelected");
  button.removeClass("builderQuizScoreButtonCheckDeselected");
  button.removeClass("builderQuizScoreButtonWeightsSelected");
  button.removeClass("builderQuizScoreButtonWeightsDeselected");
  button.addClass(activeClass);
}

// Function to be called whenever a new "active mode" may be selected. Can also refresh all grading-related elements.
function activeModeSwap(newMode) {
  activeMode = newMode;
  $(".builderQuizModeButton").each(function() {
    if ($(this).attr("id") == activeMode) {
      $(this).addClass("builderQuizModeButtonActive");
    } else {
      $(this).removeClass("builderQuizModeButtonActive");
    }
  });
  $(".builderQuizAnswerWeights").each(function(i) {
    // Save field data
    let data = [];
    let visibility = $(this).is(":visible");

    $(this)
      .find("input")
      .each(function() {
        data.push($(this).val());
      });

    // Update weights portfolio.
    html = "";
    weights.forEach((item, j) => {
      //console.log(item);
      let defaultValue = "";
      if (item == "correct") {
        //console.log("has default value of 0");
        defaultValue = "0";
        if (data[j] === "") {
          data[j] = 0;
        }
      }
      html += `<div class="builderQuizAnswerWeightItem">
        <label for="builderQuizAnswer_${i}_Weight_${item}" id="builderQuizAnswer_${i}_WeightLabel_${item}">${item}: </label>
        <input type="text" id="builderQuizAnswer_${i}_Weight_${item}" name="builderQuizAnswer_${i}_Weight_${item}"  data-weight="${item}" value="${defaultValue}" />
      </div>`;
    });
    $(this).html(html);

    // $(this)
    //   .find('*[data-weight="correct"]')
    //   .each(function() {
    //     console.log(
    //       "Found input id = " +
    //         $(this).attr("id") +
    //         " with value " +
    //         $(this).val()
    //     );
    //     // $(this).val("0");
    //     // console.log("Now has value " + $(this).val());
    //   });

    // Restore data
    $(this)
      .find("input")
      .each(function(j) {
        $(this).val(data[j]);
      });
    if (visibility) {
      $(this).show(0);
    } else {
      $(this).hide(0);
    }
  });
  if (activeMode == "builderModeSimilar") {
    $(".builderQuizAnswerScoreButton").each(function() {
      $(this).html("Weights &#9878;");
      let selected = false;
      $(this)
        .parent()
        .find(".builderQuizAnswerWeights")
        .each(function() {
          selected = $(this).is(":visible");
        });

      updateScoreButtonClasses(
        $(this),
        selected
          ? "builderQuizScoreButtonWeightsSelected"
          : "builderQuizScoreButtonWeightsDeselected"
      );
    });
  } else {
    $(".builderQuizAnswerWeights").each(function() {
      $(this).hide(0);
    });
    $(".builderQuizAnswerScoreButton").each(function() {
      $(this).html("&#10004;");
      let selected = false;
      $(this)
        .parent()
        .find('*[data-weight="correct"]')
        .each(function() {
          selected = $(this).val() == "1";
        });
      updateScoreButtonClasses(
        $(this),
        selected
          ? "builderQuizScoreButtonCheckSelected"
          : "builderQuizScoreButtonCheckDeselected"
      );
    });
  }
}
