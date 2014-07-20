<%@ page import="model.User" %>
<%  User currentUser = (User) session.getAttribute("currentUser");
    if (currentUser == null) response.sendRedirect("jsp/login.html");
    final String userType = currentUser != null ? currentUser.getType() : "unknown";
%>

<!DOCTYPE html>
<html>
  <head lang="en">
    <meta charset="UTF-8">
    <title>Georgia Tech Tutors Menu</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/style.css" rel="stylesheet" type="text/css" />

    <script>
      alert(<%=userType%>);
    </script>
  </head>
  <body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Georgia Tech Tutors</a>
        </div>
        <ul class="nav navbar-nav navbar-right">
          <li><a>Welcome</a></li>
        </ul>
      </div>
    </div>

    </div>
    <div class="about">
      <div class="well">
        <legend>About</legend>
        Project by CS 4400 Group 16<br /><br />
        Group Members:
        <ul>
          <li>
            Quentin Fruhauf
          </li>
          <li>
            Felipe Monteiro
          </li>
          <li>
            Li Zheng
          </li>
        </ul>
      </div>
    </div>

    <div class="container-fluid">
      <div class="inner">
        <div class="row">
          <h1>Academic Year 2014</h1>
          <ul class="pagination">
            <li><a href="login.jsp">&laquo; Back to Login</a></li>
          </ul>
        </div>
        <%  if (userType.equals("student") || userType.equals("tutor")) { %>
        <div class="row">
          <h3>Student Options</h3>
          <ul class="pagination">
            <li><a href="tutor_search.jsp">Search/Schedule Tutor</a></li>
            <li><a href="../jsp/student_eval.html">Rate a Tutor</a></li>
          </ul>
        </div>
        <%  } %>
        <%  if (userType.equals("tutor")) { %>
        <div class="row">
          <h3>Tutor Options</h3>
          <ul class="pagination">
            <li><a href="../jsp/application.html">Apply</a></li>
            <li><a href="../jsp/tutor_schedule.html">Find My Schedule</a></li>
          </ul>
        </div>
        <%  } else if (userType.equals("professor")) { %>
        <div class="row">
          <h3>Professor Options</h3>
          <ul class="pagination">
            <li><a href="../jsp/professor_eval.html">Add Recommendation</a></li>
          </ul>
          <%  } else if (userType.equals("admin")) { %>
          <h3>Administrator Options</h3>
          <ul class="pagination">
            <li><a href="../jsp/course_list.html">Tutor Course List</a></li>
            <li><a href="../jsp/tutor_summary.html">Tutor Summary Data</a></li>
          </ul>
          <%  } %>
        </div>
      </div>
    </div>

    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>