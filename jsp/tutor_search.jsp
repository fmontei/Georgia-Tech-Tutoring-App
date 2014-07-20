<%@ page import="model.Course" %>
<%@ page import="java.util.List" %>
<%@ page import="java.util.ArrayList" %>
<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>Georgia Tech Tutors Tutor Search</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <div class="container-fluid">
    <div class="inner">
      <div class="row">
        <h1>Tutor Search</h1>
        <ul class="pagination">
          <li><a href="menu.jsp">&laquo; Back to Main Menu</a></li>
        </ul>
      </div>
      <form action="/4400Project" method="post">
        <div class="row">
          <div class="col col-md-2">
            <h4>Course</h4>
          </div>
          <div class="col col-md-7">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>School</th>
                  <th>Number</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><input class="form-control" type="text" name="schoolName"
                             required="required" /></td>
                  <td><input class="form-control" name="courseNumber"
                             pattern="^[0-9]{4,4}$"
                             title="Please enter a four-digit course number."
                             required="required" /></td>
                </tr>
              </tbody>
              <tfoot>

              </tfoot>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="alert alert-danger" style="display: inline-block">
            Availability. Note -- tutor sessions can only be scheduled
            for 1 hour per week for a given course.
          </div>
        </div><br />
        <div class="row">
          <div class="col col-md-7 col-md-push-2">
            <table class="table table-bordered">
              <thead>
              <tr>
                <th>Day</th>
                <th>Time</th>
              </tr>
              </thead>
              <tbody id="dayTimeTable">
                <tr>
                  <td>
                    <select name="preferredDay" required="required">
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
                    <input class="form-control" name="preferredTime" type="time"
                           required="required" />
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
            <input class="btn btn-default" type="submit" name="submitDayTimeBtn" value="OK" />
          </div>
        </div>
      </form>
      <br />
      <div class="row">
        <div class="col-md-12">
          <h4 style="margin-left: 40%">Available Tutors</h4>
          <table class="table table-bordered" style="width: 100%">
            <thead>
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
            <tbody>

            </tbody>
            <tfoot>

            </tfoot>
          </table>
        </div>
      </div><br />
      <div class="row">
        <div class="col-md-7">
          <div class="alert alert-danger" style="display: inline-block">
            Note: Only 1 box under Select column may be checked.
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="btn-group">
            <div class="btn btn-default" data-toggle="modal" data-target="#tutorModal" role="button">
              Schedule a Tutor
            </div>
            <div class="btn btn-default" style="margin-left: 50px">
              <a href="menu.jsp">Cancel</a>
            </div>
          </div>
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
          <h4 class="modal-title" id="myModalLabel">Select your Tutor for CS 4400</h4>
        </div>
        <form action="" method="post" accept-charset="UTF-8">
          <table class="table table-bordered" style="width: 100%; overflow: auto">
            <thead>
            <tr>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email</th>
              <th>Day</th>
              <th>Time</th>
              <th>Select</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>

            </tfoot>
          </table>
          <div class="modal-footer">
            <button type="submit" name="loginButton" class="btn btn-primary">OK</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="http://code.jquery.com/jquery-latest.js"></script>
  <script src="../js/bootstrap.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      var newDayTimeBtn = document.getElementById("newDayTimeBtn");
      newDayTimeBtn.addEventListener("click", function() {
        var dayTimeTable = $("#dayTimeTable");
        var rowCount = $("#dayTimeTable tr").length;
        dayTimeTable.append('<tr><td>' +
                '<select name="preferredDay" required="required">' +
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
                '<td><input class="form-control" name="preferredTime" type="time" ' +
                'required="required" />' +
                '</td></tr>)');
      });
      var removeDayTimeBtn = document.getElementById("removeDayTimeBtn");
      removeDayTimeBtn.addEventListener("click", function() {
        var rowCount = $("#dayTimeTable tr").length;
        if (rowCount > 1) {
          $("#dayTimeTable tr").last().remove();
        }
      });
    });
  </script>
  </body>
</html>