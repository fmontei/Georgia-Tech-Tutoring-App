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
  <link href="../css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <?php include_once('templates/nav_template.php'); ?>
  <div class="container-fluid" style="margin-bottom: 100px">
    <div class="inner">
      <div class="row">
        <?php
          $error = "";
          if (array_key_exists("redundant_time_error", $_SESSION)) {
            $error = $_SESSION["redundant_time_error"];
          }
          if ($error !== "") { ?>
          <div class="alert alert-danger" style="display: inline-block">
            <?php echo $error; ?>
          </div>
          <?php } ?>
        <h1>Tutor Search</h1>
        <ul class="pagination">
          <li><a href="menu.html">&laquo; Back to Main Menu</a></li>
        </ul>
      </div>
      <form action="../php/tutor_search.php" method="get">
        <div class="row">
          <div class="col col-md-2">
            <h4>Course</h4>
          </div>
          <div class="col col-md-7">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>School</th>
                  <th class="hidden-col">Number</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <select class="form-control" 
                            id="school-name-select"
                            name="schoolName" 
                            type="text" 
                            required="required">
                      <option></option>
                    </select>
                  </td>
                  <td class="hidden-col">
                    <select class="form-control" 
                            id="course-number-select" 
                            name="courseNumber"
                            pattern="^[0-9]{4,4}$"
                            title="Please enter a four-digit course number."
                            required="required">
                    </select>
                  </td>
                </tr>
              </tbody>
              <tfoot>

              </tfoot>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="alert alert-info" style="display: inline-block; margin-left: 15px">
            Availability. Note -- tutor sessions can only be scheduled
            for 1 hour per week for a given course.
          </div>
        </div><br />
        <div class="row">
          <div class="col col-md-7 col-md-push-2">
            <table class="table table-bordered">
              <thead>
              <tr>
                <th style="width: 50%">Day</th>
                <th>Time</th>
              </tr>
              </thead>
              <tbody id="dayTimeTable">
                <tr>
                  <td>
                    <select name="preferredDay" class="form-control" required="required">
                      <option selected></option>
                      <option>Monday</option>
                      <option>Tuesday</option>
                      <option>Wednesday</option>
                      <option>Thursday</option>
                      <option>Friday</option>
                      <option>Saturday</option>
                      <option>Sunday</option>
                    </select>
                  </td>
                  <td>
                    <select name="preferredTime" class="form-control"
                            required="required">
                      <option value="9am">9:00 AM</option>
                      <option value="10am">10:00 AM</option>
                      <option value="11am">11:00 AM</option>
                      <option value="12pm">12:00 AM</option>
                      <option value="1pm">1:00 PM</option>
                      <option value="2pm">2:00 PM</option>
                      <option value="3pm">3:00 PM</option>
                      <option value="4pm">4:00 PM</option>
                    </select>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="btn-group" style="width: 100%">
              <div class="btn btn-default" id="newDayTimeBtn">
                Add New Day/Time
              </div>
              <div class="btn btn-default" id="removeDayTimeBtn" style="float: right">
                Remove Day/Time
              </div>
            </div>
          </div>
        </div>
        <div class="row" style="margin-top: 20px">
          <div class="col-md-2">
            <input class="btn btn-primary" type="submit" name="submitDayTimeBtn" value="Search" />
          </div>
        </div>
      </form>
      <br />
      <div class="row">
        <div class="col-md-12" style="min-width: 900px">
          <hr />
          <h2 align="center">Available Tutors</h2>
          <table class="table table-bordered">
            <thead class="alert alert-success">
              <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Avg Prof Rating</th>
                <th># Professors</th>
                <th>Avg Student Rating</th>
                <th># Students</th>
              </tr>
            </thead>
            <tbody style="overflow-x: auto">
              <?php
                $courseResults = null;
                if (array_key_exists("courseSearchResults", $_SESSION)) {
                  $courseResults = $_SESSION["courseSearchResults"];
                }
                if ($courseResults != null) {
                  foreach ($courseResults as $results) { ?>
                  <tr>
                    <td><?php echo $results["FirstName"] ?></td>
                    <td><?php echo $results["LastName"] ?></td>
                    <td><?php echo $results["Email"] ?></td>
                    <td><?php echo $results["Avg_Prof_Rating"] ?></td>
                    <td><?php echo $results["Num_Professors"] ?></td>
                    <td><?php echo $results["Avg_Student_Rating"] ?></td>
                    <td><?php echo $results["Num_Students"] ?></td>
                  </tr>
                <?php } ?>
              <?php } ?>
            </tbody>
            <tfoot>

            </tfoot>
          </table>
        </div>
      </div>
      <div class="row" style="width: 900px">
        <div class="col-md-6">
          <div class="btn-group">
            <div class="btn btn-primary"
                 data-toggle="modal" data-target="#tutorModal" role="button"
                 style="border-radius: 4px">
              Schedule a Tutor
            </div>
            <a class="btn btn-danger" href="menu.html"
               style="margin-left: 20px; border-radius: 4px">Cancel</a>
          </div>
        </div>
        <div class="col col-md-6">
          <?php
            if (array_key_exists("tutorSearchResults", $_SESSION)) { ?>
              <div class="alert alert-info" style="display: inline-block">
                <p>
                  Note: Click on 'Schedule a Tutor' to see all the available time slots.
                </p>
              </div>
            <?php } ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="tutorModal" tabindex="-1" role="dialog"
       aria-labelledby="tutorModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 65%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">
            Select your Tutor for
            <?php echo $_SESSION["school"] . " " . $_SESSION["courseNumber"]; ?>
          </h4>
        </div>
        <form action="../php/tutor_select.php" method="get" accept-charset="UTF-8"
                onsubmit="if (!confirm('Do you really want to add the selected row to your' +
                                  ' tutor schedule?')) { return false; }">
          <table class="table table-bordered" id="tutorResultTable"
                 style="width: 90%; overflow: auto; margin-left: 5%; margin-top: 2%">
            <thead class="alert alert-success">
              <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Day</th>
                <th>Time</th>
              </tr>
            </thead>
            <tbody>
            <?php
              $resultCount = $tutorResults = null;
              if (array_key_exists("tutorSearchResults", $_SESSION)) {
                $tutorResults = $_SESSION["tutorSearchResults"];
              }
              if ($tutorResults != null) {
                foreach ($tutorResults as $results) { ?>
                <tr class="tutorResultRow">
                  <td><?php echo $results["First"] ?></td>
                  <td><?php echo $results["Last"] ?></td>
                  <td><?php echo $results["Email"] ?></td>
                  <td><?php echo $results["Day"] ?></td>
                  <td><?php echo $results["Time"] ?></td>
                  <td style="display: none"><?php echo $results["GTID_Tutor"] ?></td>
                </tr>
              <?php } ?>
            <?php } ?>
            </tbody>
          </table>
          <div class="row">
            <div class="col-md-7">
              <div class="alert alert-warning" style="display: inline-block; margin-left: 9%">
                Note: Only 1 row in the above table may be selected.
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="tutorSelectButton" class="btn btn-primary">
              OK
            </button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
          <input type="hidden" id="tutorGTIDSelection" name="tutorGTIDSelection" />
          <input type="hidden" id="tutorDaySelection" name="tutorDaySelection" />
          <input type="hidden" id="tutorTimeSelection" name="tutorTimeSelection" />
        </form>
      </div>
    </div>
  </div>

  <script src="../js/jquery.min.js"></script>
  <script src="../js/jquery-ui.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      var availableSchools = <?php echo json_encode($_SESSION['availableSchools']); ?>;
      var availableCourses = <?php echo json_encode($_SESSION['availableCourses']); ?>;
      var relevantCourses = [];
      
      availableSchools.forEach(function(school, i) {
        $('#school-name-select').append('<option>' + school + '</option>');  
      });
      
      /* Add autocomplete functionality to input fields */
      $('#school-name-select').change(function() {
        var selectedValue = $(this).find(':selected').val();
        $('.hidden-col').show(); 
        relevantCourses.length = 0;
        $('#course-number-select').find('option').remove();
        availableCourses.forEach(function(schoolCourse, i) {
          if (schoolCourse.school === selectedValue) {
            relevantCourses.push(schoolCourse.course);  
          }
        });
        relevantCourses.forEach(function(course, i) {
          $('#course-number-select').append('<option>' + course + '</option>');      
        });
      });
      
      var newDayTimeBtn = document.getElementById("newDayTimeBtn");
        newDayTimeBtn.addEventListener("click", function() {
          var dayTimeTable = $("#dayTimeTable");
          dayTimeTable.append('<tr><td>' +
                  '<select name="preferredDay" class="form-control" required="required">' +
                  '<option selected></option>' +
                  '<option>Monday</option>' +
                  '<option>Tuesday</option>' +
                  '<option>Wednesday</option>' +
                  '<option>Thursday</option>' +
                  '<option>Friday</option>' +
                  '<option>Saturday</option>' +
                  '<option>Sunday</option>' +
                  '</select>' +
                  '</td>' +
                  '<td>' +
                  '<select name="preferredTime" class="form-control" required="required">' +
                  '<option value="9am">9:00 AM</option>' +
                  '<option value="10am">10:00 AM</option>' +
                  '<option value="11am">11:00 AM</option>' +
                  '<option value="12pm">12:00 AM</option>' +
                  '<option value="1pm">1:00 PM</option>' +
                  '<option value="2pm">2:00 PM</option>' +
                  '<option value="3pm">3:00 PM</option>' +
                  '<option value="4pm">4:00 PM</option>' +
                  '</select>' +
                  '</td></tr>)');
      });

      var removeDayTimeBtn = document.getElementById("removeDayTimeBtn");
      removeDayTimeBtn.addEventListener("click", function() {
        var rowCount = $("#dayTimeTable tr").length;
        if (rowCount > 1) {
          $("#dayTimeTable tr").last().remove();
        }
      });

      var tutorResultRows = document.getElementsByClassName("tutorResultRow");
      for (var i = 0; i < tutorResultRows.length; i++) {
        tutorResultRows[i].addEventListener("click", function () {
          for (var i = 0; i < tutorResultRows.length; i++) {
            if (tutorResultRows[i] == this) {
              $(tutorResultRows[i]).addClass("activeRow");
              var table = $("#tutorResultTable")[0];
              var tutor_gtid = table.rows[i + 1].cells[5].innerHTML;
              var day = table.rows[i + 1].cells[3].innerHTML;
              var time = table.rows[i + 1].cells[4].innerHTML;
              $("#tutorGTIDSelection").val(tutor_gtid);
              $("#tutorDaySelection").val(day);
              $("#tutorTimeSelection").val(time);
            }
            else {
              $(tutorResultRows[i]).removeClass("activeRow");
            }
          }
        });
      }
    });
  </script>
  </body>
</html>