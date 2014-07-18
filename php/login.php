<html>
<body>
  <?php
    $gtid = htmlspecialchars($_POST['user_gtid']);
    $email    = htmlspecialchars($_POST['password']);
  ?>

  <?php echo 'this is a simple string'; ?>
  Your GTID is: <?php echo $gtid; ?><br />
  Your password: <?php echo $password; ?><br /><br />
</body>
</html>