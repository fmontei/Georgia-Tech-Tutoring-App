<?php
  session_start();
?>

<!DOCTYPE html>
<html>
  <head lang="en">
    <meta charset="UTF-8">
    <title>Find Tutor Schedule</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/style.css" rel="stylesheet" type="text/css" />
    <link href="../css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <?php include_once('templates/nav_template.php'); ?>
    <div class="inner">
      <div class="container-fluid">
        <div class="row">
          <div class="col col-sm-9 col-md-8">
            <h1>Look Up Tutor Schedule</h1>
          </div>
          <div class="col col-sm-8 col-md-4">
            <ul class="pagination" style="position: relative; bottom: -8px;">
              <li><a href="../php/find_schedule.php?clear_results">&laquo;&nbsp;Back to Menu</a></li>
            </ul>
          </div>
        </div><br />
        <form action = "../php/find_schedule.php" method="post" class="form-inline" role="form">
          <div class="row">
            <div class="col col-md-12" style="width: 930px">
              <?php if (array_key_exists("userType", $_SESSION)) { ?>
                <?php if ($_SESSION["userType"] == "admin") { ?>
                  <span style="font-size: 16px">Enter Tutor GTID</span>&nbsp;&nbsp;
                  <input id="admin-tutor-id-input" name="tutor_id" class="form-control" type="text" placeholder="e.g. 000000000" />
                  <input class="form-control" name="find_schedule" type="submit" value="OK" />
                <?php } else if ($_SESSION["userType"] == "tutor") { ?>
                  <span style="font-size: 16px">Enter Tutor GTID</span>&nbsp;&nbsp;
                  <select class="form-control" name="tutor_id">
                    <option><?php echo $_SESSION["user_gtid"]; ?></option>
                  </select>
                  <input class="form-control" name="find_schedule" type="submit" value="OK" />
                  <div class="alert alert-warning pull-right"
                       style="display: inline-block;">
                    Press 'OK' to retrieve your schedule.<br />
                    Note: Your GTID has been already selected for you.
                  </div>
                <?php } ?>
              <?php } ?>
            </div>
          </div>
        </form>
        <div class="well" style="margin-top: 50px; width: 900px; min-height: 400px">
          <div align="center">
            <?php
              $tutor_schedule_name = "";
              if (array_key_exists("tutor_schedule_name", $_SESSION)) {
                $tutor_schedule_name = $_SESSION["tutor_schedule_name"];
              }
              if ($tutor_schedule_name == "") { ?>
                <h3>Tutor Schedule</h3>
              <?php } else { ?>
                <h3>Tutor Schedule for
                  <b><?php echo $_SESSION["tutor_schedule_name"]; ?></b>
                </h3>
              <?php } ?>
          </div>
          <div class="row">
            <div class="col-md-12">
              <table class="table table-bordered" style="margin-top: 30px; overflow: auto">
                <thead>
                  <tr>
                    <td>Day</td>
                    <td>Time</td>
                    <td>First Name</td>
                    <td>Last Name</td>
                    <td style="max-width: 100px; overflow-x: auto">Email</td>
                    <td>Course</td>
                  </tr>
                </thead>
                <tbody>
                  <?php
                      $resultCount = $tutorResults = null;
                      if (array_key_exists("tutor_schedule", $_SESSION)) {
                        $tutorResults = $_SESSION["tutor_schedule"];
                      }
                      if ($tutorResults != null) {
                        foreach ($tutorResults as $results) { ?>
                        <tr class="tutorResultRow">
                          <td><?php echo $results["Day"] ?></td>
                          <td><?php echo $results["Time"] ?></td>
                          <td><?php echo $results["First"] ?></td>
                          <td><?php echo $results["Last"] ?></td>
                          <td><?php echo $results["Email"] ?></td>
                          <td><?php echo $results["Course"] ?></td>
                          <td style="display: none"><?php echo $results["GTID_Tutor"] ?></td>
                        </tr>
                      <?php } ?>
                    <?php } ?>
                </tbody>
                <tfoot>

                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    
    <script>
      $(document).ready(function() {
        var availableTutorIDs = <?php echo json_encode($_SESSION['availableTutorIDs']); ?>;
        $('#admin-tutor-id-input').autocomplete({
          source: availableTutorIDs,
          autoFocus: true,
          minLength: 1
        });
      });
    </script>
  </body>
</html>
