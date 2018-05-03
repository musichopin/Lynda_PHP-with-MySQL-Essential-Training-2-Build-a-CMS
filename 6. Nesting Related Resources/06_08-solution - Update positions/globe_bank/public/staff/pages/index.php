<?php require_once('../../../private/initialize.php'); ?>

<?php

  require_login();

// its good practice to always have index.php file in every folder  
  redirect_to(url_for('/staff/index.php'));

?>
