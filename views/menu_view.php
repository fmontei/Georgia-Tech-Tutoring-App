<?php
  session_start();
?>

<!DOCTYPE html>
<html>
  <head lang="en">
    <meta charset="UTF-8">
    <title>Georgia Tech Tutors Menu</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/style.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <?php include_once('templates/nav_template.php'); ?>

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
          <a class="btn btn-default" href="../php/logout.php">&laquo;&nbsp;Back to Login</a>
        </div>
        <?php if ($gradType == 'undergrad' or
          ($gradType == 'undergrad' and $userType == 'tutor')) { ?>
        <div class="row">
          <h3>Student Options</h3>
          <ul class="pagination">
            <li><a href="../php/tutor_search.php?init">Search/Schedule Tutor</a></li>
            <li><a href="../php/student_eval.php?populate_table">Rate a Tutor</a></li>
          </ul>
        </div>
        <?php } ?>
        <?php if ($userType == 'tutor' or $userType == 'admin') { ?>
        <div class="row">
          <h3>Tutor Options</h3>
          <ul class="pagination">
            <?php if ($userType == 'tutor') { ?>
              <li><a href="../views/application_view.php">Apply</a></li>
            <?php } ?>
            <?php if ($userType == 'tutor') { ?>
              <li><a href="../views/tutor_schedule_view.php">Find My Schedule</a></li>
            <?php } ?>
            <?php if ($userType == 'admin') { ?>
              <li><a href="../php/tutor_schedule.php">Find a Tutor's Schedule</a></li>
            <?php } ?>
          </ul>
        </div>
        <?php } ?>
        <?php if ($userType == 'professor') { ?>
        <div class="row">
          <h3>Professor Options</h3>
          <ul class="pagination">
            <li><a href="../php/professor_recommend.php?fetch_tutors">Add Recommendation</a></li>
          </ul>
        </div>
        <?php } ?>
        <?php if ($userType == 'admin') { ?>
        <div class="row">
          <h3>Administrator Options</h3>
          <ul class="pagination">
            <li><a href="../views/admin_course_list_view.php">Tutor Course List</a></li>
            <li><a href="admin_summary_view.php">Tutor Summary Data</a></li>
          </ul>
        </div>
        <?php } ?>
      </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>