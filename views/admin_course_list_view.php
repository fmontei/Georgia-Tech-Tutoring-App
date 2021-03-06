<?php
  session_start();
?>

<!DOCTYPE html>
<html>
  <head lang="en">
    <meta charset="UTF-8">
    <title>Tutor Course List for Academic Year 2014</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/style.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <?php include_once('templates/nav_template.php'); ?>
    <div class="inner">
      <div class="container-fluid">
        <h1 style="margin-bottom: 50px">Tutor Course List for Academic Year 2014</h1>
      </div>
      <div class="container-fluid">
        <div class="row">
          <form class="form-inline" id="admin_report_form_1" role="form"
                  action="../php/admin_course_list.php" method="get"
                  onsubmit="return verifyForm()">
            <div class="col-md-12" style="font-size: 16px; margin-left: 5px">
              <span style="margin-right: 20px"><b>Academic Year 2014</b></span>
              <div class="checkbox">
                <label>
                  <input name="fall_checkbox" id="fall_checkbox" type="checkbox" value="FALL">
                  Fall&nbsp;&nbsp;
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="spring_checkbox" id="spring_checkbox" type="checkbox" value="SPRING">
                  Spring&nbsp;&nbsp;
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="summer_checkbox" id="summer_checkbox" type="checkbox" value="SUMMER">
                  Summer&nbsp;&nbsp;
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input id="all_checkbox" type="checkbox" value="SUMMER">
                  All Semesters&nbsp;&nbsp;
                </label>
              </div>
              <input id="submitFormBtn" class="form-control" type="submit" value="OK"
                     style="margin-left: 20px" />
              <div class="alert alert-warning" style="margin-top: 20px; display: inline-block">
                Select a semester from above then click 'OK' to retrieve the relevant data.<br />
                To quickly select all the semesters, check 'All Semesters'.
              </div>
            </div>
          </form>
        </div>
        <div class="row">
          <div class="well col col-md-12" style="margin-top: 20px; margin-left: 20px; width: 120%;">
            <table class="table table-bordered" style="overflow: auto; width: 100%">
              <thead class="alert alert-success">
                <tr>
                  <td>Course</td>
                  <td>Semester</td>
                  <td># Students</td>
                  <td># Tutors</td>
                </tr>
              </thead>
              <tbody>
                <?php
                  $admin_course_list = array();
                  if (array_key_exists("admin_course_list", $_SESSION)) {
                    $admin_course_list = $_SESSION["admin_course_list"];
                  }
                  foreach ($admin_course_list as $row) { ?>
                    <tr>
                      <td><?php echo $row["Course"]; ?></td>
                      <?php if ($row["Semester"] === "Total") { ?>
                        <td style="background-color: cornflowerblue; color: white">
                          <?php echo $row["Semester"]; ?>
                        </td>
                      <?php } else if ($row["Semester"] === "Grand Total") { ?>
                        <td style="background-color: cadetblue; color: white">
                          <?php echo $row["Semester"]; ?>
                        </td>
                      <?php } else { ?>
                        <td><?php echo $row["Semester"]; ?></td>
                      <?php } ?>
                      <td><?php echo $row["NumStudent"]; ?></td>
                      <td><?php echo $row["NumTutor"]; ?></td>
                    </tr>
                  <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="col col-md-4" style="margin-left: 5px; margin-top: 20px">
            <ul class="pagination">
              <li><a href="../php/admin_course_list.php?clear_report">&laquo; Back to Menu</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>

    <script type="text/javascript">
      function verifyForm() {
        var fall_checkbox_val = document.getElementById("fall_checkbox").checked,
            spring_checkbox_val = document.getElementById("spring_checkbox").checked,
            summer_checkbox_val = document.getElementById("summer_checkbox").checked;
        if (!fall_checkbox_val && !spring_checkbox_val && !summer_checkbox_val) {
          alert("Please select at least one semester before clicking 'OK'.");
          return false;
        } else {
          return true;
        }
      }
      var all_checkbox = document.getElementById("all_checkbox");
      all_checkbox.addEventListener("change", function() {
        var checked = this.checked;
        var fall_checkbox = document.getElementById("fall_checkbox"),
            spring_checkbox = document.getElementById("spring_checkbox"),
            summer_checkbox = document.getElementById("summer_checkbox");
        if (checked) {
          fall_checkbox.checked = spring_checkbox.checked =
                  summer_checkbox.checked = true;
        } else {
          fall_checkbox.checked = spring_checkbox.checked =
                  summer_checkbox.checked = false;
        }
      });
    </script>
  </body>
</html>