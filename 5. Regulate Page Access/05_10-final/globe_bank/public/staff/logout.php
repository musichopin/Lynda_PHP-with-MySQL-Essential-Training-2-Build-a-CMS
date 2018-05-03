<?php
require_once('../../private/initialize.php');
// calling require_login() here and restricting this page is optional

log_out_admin();
redirect_to(url_for('/staff/login.php'));

?>
