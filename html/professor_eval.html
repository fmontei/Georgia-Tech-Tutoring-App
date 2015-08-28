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
  <?php include_once('templates/nav-template.html'); ?>
  <div class="inner">
    <h1>Tutor Evaluation by Professor</h1><br />
    <div class="container-fluid">
      <div class="row">
        <div class="col col-md-10" style="margin-left: 15px">
        <?php
           $error = "";
           if (array_key_exists("recommendation_error", $_SESSION)) {
             $error = $_SESSION["recommendation_error"];
           }
           if ($error !== "") { ?>
            <div class="row">
              <div class="alert alert-danger" style="display: inline-block">
                <?php echo $error; ?>
              </div>
            </div>
        <?php } ?>
        </div>
      </div>
      <form action="../php/professor_recommend.php" action="get" id="form_submit">
        <div class="row">
          <div class="col col-md-12" style="width: 900px">
            <div class="form-inline">
              <div class="form-group">
                <span style="font-size: 18px">Student GTID</span>&nbsp;&nbsp;
                <input type="text" class="form-control"
                       id="gtid_input"
                       name="tutorGTIDSelection"
                       placeholder="e.g. 000000000"
                       maxlength="9" required="required"
                       title="Please enter a valid GTID."
                       style="width: 180px" />
              </div>
            </div>
          </div>
        </div><br />
        <div class="row">
          <div class="col-md-5">
            <h4>Descriptive Evaluation</h4>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <textarea id="desc_eval" name="desc_eval" class="form-control" required
                      maxlength="1000" rows=5 cols=10
                      style="width: 100%; overflow: auto" ></textarea>
          </div>
        </div><br />
        <div class="row">
          <div class="col col-md-12">
            <div class="alert alert-info" style="display: inline-block">
              <b>Note:</b> your descriptive evaluation is limited to 1,000 characters.
            </div>
          </div>
        </div>
        <div class="row">
          <hr style="margin-left: 15px" />
          <div class="col-md-5">
            <h4>Numeric Evaluation</h4>
          </div>
        </div><br />
        <div class="row">
          <div class="col-md-12">
            <div class="well" style="display: inline-block">
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
        <input type="hidden" id="final_num_eval_input"
               name="final_num_eval_input" value="" />
      </form>
      <div class="row" style="margin-bottom: 50px">
        <div class="col col-md-12">
          <div class="form-inline">
              <div class="form-group">
                <button id="submit_btn" class="btn btn-primary"
                        name="submit_recommendation"
                        style="width: 60px">OK</button>
                  <a class="btn btn-danger form-control" role="button"
                     href="../php/professor_recommend.php?clear_recommendation"
                          style="color: white">
                    Cancel
                  </a>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../js/jquery.min.js"></script>
  <script src="../js/jquery-ui.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      var availableTutorIDs = <?php echo json_encode($_SESSION['availableTutorIDs']); ?>;
      $('#gtid_input').autocomplete({
        source: availableTutorIDs,
        autoFocus: true,
        minLength: 1,
        select: function(event, ui) {
          $.ajax({
            type: 'GET',
            url: '../php/professor_recommend.php?get_recommendation',
            data: {tutorID: ui.item.value},
            success: function(resp) {
              var respObj = JSON.parse(resp);
              $('#desc_eval').val(respObj.desc_evaluation);
              $('.num_eval_input').each(function() {
                $(this).prop('checked', false);  
              });
              $('#final_num_eval_input').val('');
              $('.num_eval_input').each(function() {
                var checkbox = $(this);
                if (checkbox.val() === respObj.num_evaluation) {
                  checkbox.prop('checked', true);
                  $('#final_num_eval_input').val(respObj.num_evaluation);
                  return;
                }
              });
            }
          });     
        }
      });
    });
    
    var num_eval_inputs = document.getElementsByClassName("num_eval_input");
    var final_num_eval_input = document.getElementById("final_num_eval_input");
    for (var i = 0; i < num_eval_inputs.length; i++) {
      num_eval_inputs[i].addEventListener("click", function() {
        final_num_eval_input.value = this.value;
        var swap = {"1": "4", "2": "3", "3": 2, "4": 1};
        var clickedBoxIndex = swap[this.value];
        for (var j = 1; j <= num_eval_inputs.length; j++) {
          if (j != clickedBoxIndex) num_eval_inputs[j - 1].checked = false;
        }
      });
    }

    var submit_btn = document.getElementById("submit_btn");
    submit_btn.addEventListener("click", function() {
      var desc_eval = document.getElementById("desc_eval");
      var num_eval = document.getElementById("final_num_eval_input");
      var gtid_input = document.getElementById("gtid_input");
      if (gtid_input.value === "") {
        alert("You must enter a GTID first.");
        return;
      } else if (desc_eval.value === "") {
        alert("You must enter a descriptive evaluation.");
        return;
      } else if (num_eval.value === "") {
        alert("You must select a numeric evaluation.");
        return;
      } else {
        var form_submit = document.getElementById("form_submit");
        form_submit.submit();
      }
    });
  </script>
</body>
</html>