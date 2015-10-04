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
      <h1>Tutor Evaluation by Student</h1><br />
        <div class="container-fluid">
        <div class="row">
          <div class="col-md-8">
            <h4>Course List</h4>
            <div class="alert alert-warning">
              <b>Note:</b> You can select only one course at a time from the list below.<br />
              Tutors who tutored you in more than one course will appear multiple times.
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <table class="table table-bordered" id="tutorResultTable"
                   style="min-width: 400px; max-width: 600px; overflow: auto">
              <thead>
                <tr>
                  <td>Tutor Name</td>
                  <td>School</td>
                  <td>Course Number</td>
                </tr>
              </thead>
              <tbody>
                <?php
                  $courseRatingArray = null;
                  if (array_key_exists("courseRatingArray", $_SESSION)) {
                    $courseRatingArray = $_SESSION["courseRatingArray"];
                  }
                  if ($courseRatingArray != null) {
                    foreach ($courseRatingArray as $courseResult) { ?>
                      <tr class="tutorResultRow">
                        <td style="display: none"><?php echo $courseResult["TutorGTID"]; ?></td>
                        <td><?php echo $courseResult["TutorName"]; ?></td>
                        <td><?php echo $courseResult["School"]; ?></td>
                        <td><?php echo $courseResult["CourseNumber"]; ?></td>
                      </tr>
                  <?php } ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div><br />
        <form id="studentEvalForm" action="../php/student_eval.php" method="get">
          <div class="row">
            <div class="col-md-5">
              <h4>Descriptive Evaluation</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <textarea name="desc_eval" id="desc_eval" rows=5 cols=10 maxlength=1000
                        required style="width: 100%; overflow: auto"></textarea>
            </div>
          </div>
          <div class="row">
            <div class="col col-md-6">
              <div class="alert alert-warning">
                <b>Note:</b> your descriptive evaluation is limited to 1,000 characters.
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-5">
              <h4>Numeric Evaluation</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="well" style="width: 100%">
                <div class="checkbox">
                  <label>
                    <input class="num_eval_input" type="checkbox" value="4">
                    4 Highly Recommend
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input class="num_eval_input" type="checkbox" value="3">
                    3 Recommend
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input class="num_eval_input" type="checkbox" value="2">
                    2 Recommend With Reservations
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input class="num_eval_input" type="checkbox" value="1">
                    1 Do Not Recommend
                  </label>
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" name="submit_review" />
          <input type="hidden" id="tutorGTIDSelection" name="tutorGTIDSelection" />
          <input type="hidden" id="tutorNameSelection" name="tutorNameSelection" />
          <input type="hidden" id="tutorSchoolSelection" name="tutorSchoolSelection" />
          <input type="hidden" id="tutorCourseSelection" name="tutorCourseSelection" />
          <input type="hidden" id="final_num_eval_input" name="final_num_eval_input" />
        </form>
        <div class="row">
          <div class="col col-md-7">
            <ul class="pagination">
              <li><a href="javascript:void(0)" id="submitFormBtn"
                     style="margin-right: 15px">Submit Review</a></li>
              <li><a href="menu_view.php">&laquo; Back to Menu</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    
    <script type="text/javascript">
      var tutorResultRows = document.getElementsByClassName("tutorResultRow");
      for (var i = 0; i < tutorResultRows.length; i++) {
        tutorResultRows[i].addEventListener("click", function () {
          for (var i = 0; i < tutorResultRows.length; i++) {
            if (tutorResultRows[i] == this) {
              $(tutorResultRows[i]).addClass("activeRow");
              var table = $("#tutorResultTable")[0];
              var tutor_gtid = table.rows[i + 1].cells[0].innerHTML;
              var name = table.rows[i + 1].cells[1].innerHTML;
              var school = table.rows[i + 1].cells[2].innerHTML;
              var courseNum = table.rows[i + 1].cells[3].innerHTML;
              $("#tutorGTIDSelection").val(tutor_gtid);
              $("#tutorNameSelection").val(name);
              $("#tutorSchoolSelection").val(school);
              $("#tutorCourseSelection").val(courseNum);
            }
            else {
              $(tutorResultRows[i]).removeClass("activeRow");
            }
          }
        });
      }
      var submitFormBtn = document.getElementById("submitFormBtn");
      submitFormBtn.addEventListener("click", function () {
        if (!document.getElementById("tutorGTIDSelection").value) {
          alert("Please select a row from the table. If none are shown, then" +
                  " you are not eligible to review a tutor.");
        } else if (!document.getElementById("final_num_eval_input").value) {
          alert("Please select a numeric evaluation for the selected tutor.");
        } else if (!document.getElementById("desc_eval").value) {
          alert("Please enter a descriptive evaluation for the selected tutor.");
        } else {
          var form = document.getElementById("studentEvalForm");
          form.submit();
        }
      });
      var num_eval_inputs = document.getElementsByClassName("num_eval_input");
      var final_num_eval_input = document.getElementById("final_num_eval_input");
      for (i = 0; i < num_eval_inputs.length; i++) {
        num_eval_inputs[i].addEventListener("click", function() {
          final_num_eval_input.value = this.value;
          var swap = {"1": "4", "2": "3", "3": 2, "4": 1};
          var clickedBoxIndex = swap[this.value];
          for (var j = 1; j <= num_eval_inputs.length; j++) {
            if (j != clickedBoxIndex) num_eval_inputs[j - 1].checked = false;
          }
        });
      }
    </script>
  </body>
</html>