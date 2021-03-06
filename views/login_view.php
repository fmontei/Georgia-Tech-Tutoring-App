<?php
  session_start();
?>

<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>Georgia Tech Tutors Login</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <?php include_once('templates/nav_template.php'); ?>
  <div class="container-fluid">
    <div class="jumbotron" style="width: 100%; height: 400px; background-color: rgb(3, 48, 81); color: rgb(204, 153, 0)">
      <div class="row">
        <div class="col col-md-12">
          <div class="row">
            <div class="col col-md-7 col-md-push-3">
              <h1>Georgia Tech Tutors Project</h1>
              <p>To get started, please login below.</p>
            </div>
          </div>
          <div class="row">
            <div class="col col-md-7 col-md-push-3">
              <button type="button" class="btn btn-default btn-lg"
                      data-toggle="modal" data-target="#myModal" role="button">
                Login
              </button>
            </div>
          </div>
          <br />
          <br />
          <div class="row">
            <div class="col col-md-12 text-center">
              <?php if (isset($_SESSION['loginError'])) { ?>
                <div class="alert alert-danger" style="display: inline-block">
                  <span class="glyphicon glyphicon-alert"></span>&nbsp;<?php echo $_SESSION['loginError']; ?>
                </div>
              <?php } ?>
            </div>
          </div>
        </div> <!-- ./col col-md-12 -->
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title" id="myModalLabel">Georgia Tech Tutors Login</h4>
        </div>
        <form action="../php/login.php" method="post" accept-charset="UTF-8">
          <div class="modal-body">
            <label>Username:</label>
            <?php include('templates/login_select_template.html'); ?>
            <div class="alert alert-info">
              <span class="glyphicon glyphicon-info"></span>&nbsp;The login credentials have been pre-filled above to make using this app easier.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" name="loginButton" class="btn btn-primary">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="../js/jquery.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  
  <script>
    $(document).ready(function() {
      $('.prefilled-login-select').each(function() {
        var select = $(this);
        var firstOption = select.find('option:first'),
            usernameInput = select.parent().find('.user-gtid-input'),
            passwordInput = select.parent().find('.password-input');
        var username = firstOption.attr('data-username'),
            password = firstOption.attr('data-password');
        usernameInput.val(username);
        passwordInput.val(password);
        select.change(function() {
          username = $(this).find(':selected').attr('data-username');
          password = $(this).find(':selected').attr('data-password');
          usernameInput.val(username);
          passwordInput.val(password);
        });
      });
    });
  </script>
</body>
</html>