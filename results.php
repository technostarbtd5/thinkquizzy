<!DOCTYPE html>
<html lang="en" dir="ltr">
  <?php
    // TEMPORARY naming convention; replace with SQL query to get quiz name
    $titleTag = isset($_GET['name']) ? htmlspecialchars($_GET['name']) . " - " : "";
    include("resources/header.php");
  ?>
  <div style="padding: 8em">
    Content belongs here!
    </br>
    </br>
    <div class="pageButton">
      <a href="quiz.php">&#129028 Return</a>
    </div>
    <div class="pageButton">
      <a href="index.php">&#129093 Home</a>
    </div>
  </div>
  <?php include("resources/footer.php"); ?>
</html>
