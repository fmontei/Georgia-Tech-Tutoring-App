<?php
  session_start();
?>

<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>Georgia Tech Tutors Tutor Search</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <?php include_once('templates/nav_template.php'); ?>
  <div class="inner">
    <div class="container-fluid" style="width: 800px; margin-bottom: 50px">
      <h1>Georgia Tech Tutor Application</h1>

      <div class="row">
        <div class="col col-md-6">
          <div class="alert alert-warning" style="margin-top: 20px; display: inline-block">
            To auto-populate the fields below, click on 'Populate':<br/><br />
            <form action="../php/tutor_application.php" method="get">
              <input class="btn btn-warning" name="populate_form" type="submit" value="Populate" />
            </form>
          </div>
        </div>
        <div class="col col-md-6" style="margin-top: 20px">
          <div class="alert alert-danger">
            <p><b>Note:</b> All tutors are successful undergraduate or graduate students
            who have made a <b>grade of "A"</b> in the course(s) they tutor and
            have a <b>minimum overall GPA of 3.0.</b></p>
          </div>
        </div>
      </div>
      <ul class="nav nav-tabs" role="tablist" style="margin-top: 20px">
        <li class="active" id="personalInfoTab"><a href="#">Personal Information</a></li>
        <li id="courseTab"><a href="#">Courses for Tutoring</a></li>
        <li id="timeTab"><a href="#">Available Days/Times</a></li>
      </ul>

      <?php
        $tutor_app_info = array();
        if (array_key_exists("tutor_app_info", $_SESSION)) {
          $tutor_app_info = $_SESSION["tutor_app_info"]; ?>
      <?php } ?>
      <form class="form-inline" id="tutor_app_form" role="form"
            action="../php/tutor_application.php?submit_tutor_app" method="get">
        <input type="hidden" name="submit_tutor_app" />
        <div class="well col col-md-12" id="personalInfoWell" style="border: 1px rgb(204, 204, 204) solid">
          <div class="form-group" style="width: 100%; display: inline-block">
            <div class="row" >
              <div style="float: left">
                <div class="col-md-2" style="width: 150px;">
                  <span style="font-size: 16px">Georgia Tech ID</span>
                </div>
                <div class="col-md-3" style="width: 200px; margin-left: -20px">
                  <?php $gtid = isset($tutor_app_info["GTID"]) ? $tutor_app_info["GTID"] : ""; ?>
                  <input type="text" class="form-control" placeholder="Georgia Tech ID"
                         value="<?php echo $gtid; ?>" name="gtid" id="gtid"
                         pattern="[0-9]{9,9}" maxlength="9" />
                </div>
              </div>
            </div>
          </div><br /><br />
          <div class="form-group" style="width: 100%; display: inline-block">
            <div class="row" >
              <div style="float: left">
                <div class="col-md-2" style="width: 130px;">
                  <span style="font-size: 16px">First Name</span>
                </div>
                <div class="col-md-3" style="width: 200px">
                  <?php $first_name = isset($tutor_app_info["FirstName"]) ? $tutor_app_info["FirstName"] : ""; ?>
                  <input type="text" class="form-control" placeholder="First Name" name="first_name" id="first_name"
                         value="<?php echo $first_name; ?>" />
                </div>
              </div>
              <div style="float: right">
                <div class="col-md-2" style="width: 130px;">
                  <span style="font-size: 16px">Last Name</span>
                </div>
                <div class="col-md-3" style="width: 200px; margin-right: 10px">
                  <?php $last_name = isset($tutor_app_info["LastName"]) ? $tutor_app_info["LastName"] : ""; ?>
                  <input type="text" class="form-control" placeholder="Last Name" name="last_name" id="last_name"
                         value="<?php echo $last_name; ?>" />
                </div>
              </div>
            </div>
          </div><br /><br />
          <div class="form-group" style="width: 100%; display: inline-block">
            <div class="row" >
              <div style="float: left">
                <div class="col-md-2" style="width: 130px;">
                  <span style="font-size: 16px">Email</span>
                </div>
                <div class="col-md-3" style="width: 200px">
                  <?php $email = isset($tutor_app_info["Email"]) ? $tutor_app_info["Email"] : ""; ?>
                  <input type="text" class="form-control" placeholder="Email" name="email" id="email"
                         value="<?php echo $email; ?>" />
                </div>
              </div>
              <div style="float: right">
                <div class="col-md-2" style="width: 130px;">
                  <span style="font-size: 16px">Telephone</span>
                </div>
                <div class="col-md-3" style="width: 200px; margin-right: 10px">
                  <?php $phone = isset($tutor_app_info["Phone"]) ? $tutor_app_info["Phone"] : ""; ?>
                  <input type="text" class="form-control" placeholder="Telephone" name="phone" id="phone"
                         value="<?php echo $phone; ?>" />
                </div>
              </div>
            </div>
          </div><br /><br />
          <div class="form-group" style="width: 100%; display: inline-block">
            <div class="row" >
              <div style="float: left">
                <div class="col-md-2" style="width: 130px;">
                  <span style="font-size: 16px">GPA</span>
                </div>
                <div class="col-md-3" style="width: 200px">
                  <?php $gpa = isset($tutor_app_info["GPA"]) ? $tutor_app_info["GPA"] : ""; ?>
                  <input type="number" min="0" max="4" step="0.1" name="gpa" id="gpa"
                         class="form-control" placeholder="GPA"
                         value="<?php echo $gpa; ?>" />
                </div>
              </div>
              <div style="float: right;">
                <div class="col-md-2" style="width: 200px; margin-right: 10px">
                  <div class="checkbox">
                    <label>
                      <?php $grad_status = isset($tutor_app_info["GradType"]) ? $tutor_app_info["GradType"] : "";
                      if ($grad_status === "undergrad") { ?>
                        <input type="checkbox" name="grad_status" value="Undergraduate" checked>
                        Undergraduate
                      <?php } else { ?>
                        <input type="checkbox" name="grad_status" value="Undergraduate">
                        Undergraduate
                      <?php } ?>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group" style="width: 100%; display: inline-block">
            <div class="row" >
              <div style="float: right; margin-right: 10px">
                <div class="col-md-2" style="width: 200px;">
                  <div class="checkbox">
                    <label>
                      <?php $grad_status = isset($tutor_app_info["GradType"]) ? $tutor_app_info["GradType"] : "";
                      if ($grad_status === "grad") { ?>
                        <input type="checkbox" name="grad_status" value="Graduate" checked>
                        Graduate
                      <?php } else { ?>
                        <input type="checkbox" name="grad_status" value="Graduate">
                        Graduate
                      <?php } ?>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="well col col-md-12" id="courseWell" style="border: 1px rgb(204, 204, 204) solid; display: none">
          <table class="table table-bordered" id="tutor_app_table" style="overflow: auto">
            <thead>
              <tr>
                <td></td>
                <td>School</td>
                <td>Number</td>
                <td>GTA</td>
              </tr>
            </thead>
            <tbody>
              <?php
                $tutor_course_info = array();
                $id_count = 0;
                if (array_key_exists("tutor_course_info", $_SESSION)) {
                  $tutor_course_info = $_SESSION["tutor_course_info"];
                }
                foreach ($tutor_course_info as $course) { ?>
                  <tr class="tutor_app_table_row">
                    <td class="tutor_app_table_cell" style="max-width: 50px">
                      <button class="btn btn-default" onclick="return false;">Select</button>
                    </td>
                    <td><?php echo $course["School"]; ?></td>
                    <td><?php echo $course["Number"]; ?></td>
                    <td>
                      <div class="checkbox">
                        <?php if ($course["GTA"] !== "0") { ?>
                          <input type="checkbox" name="gta" value="true" checked>
                        <?php } else { ?>
                          <input type="checkbox" name="gta" value="true">
                        <?php } ?>
                      </div>
                    </td>
                  </tr>
                  <input type="hidden"
                         name="tutor_course_input"
                         id="tutor_course_input_<?php echo $id_count; ?>"
                         value = "" />
                  <?php $id_count++; ?>
                <?php } ?>
            </tbody>
          </table>
        </div>

        <div class="well col col-md-12" id="timeWell" style="border: 1px rgb(204, 204, 204) solid; display: none">
          <div class="alert alert-info" id="alert_timeslot" style="display: none;">

          </div>
          <?php
            $weekdays = array("1" => "Monday", "2" => "Tuesday", "3" => "Wednesday",
                          "4" => "Thursday", "5" => "Friday");
            foreach($weekdays as $weekday => $day) { ?>
            <div style="margin-bottom: 10px">
              <legend style="font-size: 16px;"><i><?php echo $day; ?></i></legend>
              <label>
                <div class="checkbox">
                  <input class="day_time" name="day_time" type="checkbox"
                         value="<?php echo $day; ?> 9am">
                  9am
                </div>
              </label>
              <label style="margin-left: 5px">
                <div class="checkbox">
                  <input class="day_time" name="day_time" type="checkbox"
                         value="<?php echo $day; ?> 10am">
                  10am
                </div>
              </label>
              <label style="margin-left: 5px">
                <div class="checkbox">
                  <input class="day_time" name="day_time" type="checkbox"
                         value="<?php echo $day; ?> 11am">
                  11am
                </div>
              </label>
              <label style="margin-left: 5px">
                <div class="checkbox">
                  <input class="day_time" name="day_time" type="checkbox"
                         value="<?php echo $day; ?> 12pm">
                  12pm
                </div>
              </label>
              <label style="margin-left: 5px">
                <div class="checkbox">
                  <input class="day_time" name="day_time" type="checkbox"
                         value="<?php echo $day; ?> 1pm">
                  1pm
                </div>
              </label>
              <label style="margin-left: 5px">
                <div class="checkbox">
                  <input class="day_time" name="day_time" type="checkbox"
                         value="<?php echo $day; ?> 2pm">
                  2pm
                </div>
              </label>
              <label style="margin-left: 5px">
                <div class="checkbox">
                  <input class="day_time" name="day_time" type="checkbox"
                         value="<?php echo $day; ?> 3pm">
                  3pm
                </div>
              </label>
              <label style="margin-left: 5px">
                <div class="checkbox">
                  <input class="day_time" name="day_time" type="checkbox"
                         value="<?php echo $day; ?> 4pm">
                  4pm
                </div>
              </label>
            </div>
          <?php } ?>
        </div>
      </form>
      <div class="row">
        <div class="col-md-6">
          <div class="form-inline" role="form">
            <div class="form-group">
              <button type="submit" class="btn btn-default" onclick="verify_form_inputs()">
                OK
              </button>
            </div>
            <div class="form-group">
              <form action="../php/tutor_application.php" method="get">
                <input name="clear_form" class="btn btn-danger" type="submit" value="Cancel" />
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../js/jquery.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>

  <script type="text/javascript">
    var personalInfoTab = document.getElementById("personalInfoTab");
    var personalInfoWell = document.getElementById("personalInfoWell");
    var courseTab = document.getElementById("courseTab");
    var courseWell = document.getElementById("courseWell");
    var timeTab = document.getElementById("timeTab");
    var timeWell = document.getElementById("timeWell");
    var num_selected = 0;

    personalInfoTab.addEventListener("click", function() {
      courseWell.style.display = "none";
      timeWell.style.display = "none";
      personalInfoWell.style.display = "";
      $(courseTab).removeClass("active");
      $(timeTab).removeClass("active");
      $(personalInfoTab).addClass("active");
    });

    courseTab.addEventListener("click", function() {
      personalInfoWell.style.display = "none";
      timeWell.style.display = "none";
      courseWell.style.display = "";
      $(personalInfoTab).removeClass("active");
      $(timeTab).removeClass("active");
      $(courseTab).addClass("active");
    });

    timeTab.addEventListener("click", function() {
      personalInfoWell.style.display = "none";
      courseWell.style.display = "none";
      timeWell.style.display = "";
      $(personalInfoTab).removeClass("active");
      $(courseTab).removeClass("active");
      $(timeTab).addClass("active");
    });

    function verify_form_inputs() {
      var primary_attribute_empty = false;
      $("#gtid, #first_name, #last_name, #email, #phone, #gpa").each(function() {
        if ($(this).val() === "") {
          alert(this.getAttribute("placeholder") + " must not be left empty.");
          primary_attribute_empty = true;
        }
      });
      if (primary_attribute_empty) return;
      var day_time_inputs = document.getElementsByClassName("day_time");
      var num_checked = 0;
      for (var i = 0; i < day_time_inputs.length; i++) {
        if (day_time_inputs[i].checked) {
          num_checked++;
        }
      }
      if (num_checked < 5) {
        alert("You must check 5 time slots to satisfy the requirement " +
                " of having 5 hours of availability per week. Select your " +
                " time slots in the Available Days/Times tab.");
        return;
      }
      var is_checked = false;
      var tutorResultRows = document.getElementsByClassName("tutor_app_table_row");
      for (i = 0; i < tutorResultRows.length; i++) {
        if ($(tutorResultRows[i]).hasClass("activeRow")) {
          is_checked = true;
          break;
        }
      }
      if (is_checked) {
        document.getElementById("tutor_app_form").submit();
      } else {
        alert("You must select at least one course from the table before" +
                " submitting your application.");
      }
    }

    var tutorResultCells = document.getElementsByClassName("tutor_app_table_cell");
    for (var i = 0; i < tutorResultCells.length; i++) {
      tutorResultCells[i].addEventListener("click", function () {
        for (var i = 0; i < tutorResultCells.length; i++) {
          var tableRow = this.parentNode;
          if (tutorResultCells[i] == this) {
            if (!$(tableRow).hasClass("activeRow")) {
              $(tableRow).addClass("activeRow");
              var hidden_input = $("#tutor_course_input_" + i);
              hidden_input.val(i);
            } else {
              $(tableRow).removeClass("activeRow");
              hidden_input = $("#tutor_course_input_" + i);
              hidden_input.val("");
            }
          }
        }
        var alert_timeslot = $("#alert_timeslot");
        $(alert_timeslot).css("display", "inline-block");
        $(alert_timeslot).text("");
        $(alert_timeslot).append("You have selected a " +
                                " course. So you must select 5 tutor time slots, " +
                                " because you must guarantee 5 hours of " +
                                " availability per week.");
      });
    }
  </script>
</body>