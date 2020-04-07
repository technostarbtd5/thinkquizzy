<!DOCTYPE html>
<html lang="en" dir="ltr">
  <?php
    // TEMPORARY naming convention; replace with SQL query to get quiz name
    $titleTag = isset($_GET['name']) ? htmlspecialchars($_GET['name']) . " - " : "";
    include("resources/header.php");
  ?>
  <div style="padding: 8em">
    Content belongs here!
    <br />
    <br />

    <a class="pageButton" href="results.php">Submit</a>
  </div>
  <?php include("resources/footer.php"); ?>
</html>
