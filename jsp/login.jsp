<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>Georgia Tech Tutors Login</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">
              Georgia Tech Tutors Project
            </a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                <a class="dropdown-toggle" href="#" data-toggle="dropdown">Login<strong class="caret"></strong></a>
                <div class="dropdown-menu" style="padding: 15px;">
                  <!--Login form-->
                  <form action="/4400Project" method="post" accept-charset="UTF-8">
                    <input id="user_gtid" style="margin-bottom: 15px;" type="text" name="user_gtid" size="30" placeholder="GTID" required="required"/>
                    <input id="user_password" style="margin-bottom: 15px;" type="password" name="password" size="30" placeholder="Password" required="required"/>
                    <input class="btn btn-primary" style="clear: left; width: 100%; height: 32px; font-size: 13px;" type="submit" name="loginButton" value="Login" />
                  </form>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div><br /><br />

  <div class="container jumbotron"
       style="width: 100%; padding: 10px; height: 300px; background-color: rgb(3, 48, 81); color: rgb(204, 153, 0)">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-2" style="margin-right: 50px"></div>
        <div class="col-md-8">
          <div style="margin-top: 50px">
            <h1>Georgia Tech Tutors Project</h1>
            <p>To get started, please login below.</p>
            <div style="display: inline-block">
              <div class="btn-group" style="width: 900px">
                <button type="button" class="btn btn-default btn-lg"
                        data-toggle="modal" data-target="#myModal" role="button">
                  Login
                </button>
                <%  if (session.getAttribute("loginError") != null) { %>
                <div class="custom-alert">
                  <span><%=session.getAttribute("loginError").toString()%></span>
                </div>
                <%  } %>
              </div>
            </div>
          </div>
        </div>
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
        <form action="/4400Project" method="post" accept-charset="UTF-8">
          <div class="modal-body">
            <input style="margin-bottom: 15px;" type="text" name="user_gtid" size="30" placeholder="GTID" required="required"/><br />
            <input style="margin-bottom: 15px;" type="password" name="password" size="30" placeholder="Password" required="required"/>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" name="loginButton" class="btn btn-primary">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="http://code.jquery.com/jquery-latest.js"></script>
  <script src="../js/bootstrap.min.js"></script>
</body>
</html>