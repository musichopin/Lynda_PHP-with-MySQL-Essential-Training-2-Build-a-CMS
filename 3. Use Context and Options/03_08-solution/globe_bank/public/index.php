<?php require_once('../private/initialize.php'); ?>

<?php

$preview = false;//if true shows hidden content if we get directed from pages/show.php
if(isset($_GET['preview'])) {
  // TODO: previewing should require admin to be logged in
  $preview = $_GET['preview'] == 'true' ? true : false;
}
$visible = !$preview;

if(isset($_GET['id'])) {
  $page_id = $_GET['id'];
  // against insecure direct object reference (IDOR) on url, we passed array
  $page = find_page_by_id($page_id, ['visible' => $visible]);
  if(!$page) { // empty record is totally empty      
    redirect_to(url_for('/index.php'));
  }
  $subject_id = $page['subject_id'];
  $subject = find_subject_by_id($subject_id, ['visible' => $visible]); //against IDOR
  if(!$subject) {
    redirect_to(url_for('/index.php'));
  }
} elseif(isset($_GET['subject_id'])) { // alt1
  $subject_id = $_GET['subject_id'];
  $subject = find_subject_by_id($subject_id, ['visible' => $visible]); //against IDOR
  if(!$subject) {
    redirect_to(url_for('/index.php'));
  }
  $page_set = find_pages_by_subject_id($subject_id, ['visible' => $visible]);
  $page = mysqli_fetch_assoc($page_set); // displays 1st visible page
  mysqli_free_result($page_set);
  if(!$page) {
    redirect_to(url_for('/index.php'));
  }
  $page_id = $page['id'];
} else {
  // nothing selected; show the homepage
}

?>

<?php include(SHARED_PATH . '/public_header.php'); ?>

<div id="main">

  <?php include(SHARED_PATH . '/public_navigation.php'); ?>

  <div id="page">

    <?php
      if(isset($page)) {
        // content is shown as html but still get the html escape
        $allowed_tags = '<div><img><h1><h2><p><br><strong><em><ul><li>'; // whitelist
        echo strip_tags($page['content'], $allowed_tags);
//strip_tags() cud be pass to nl2br() if we didnt use html tags but \n character on html
      } else {
        // Show the homepage
        // The homepage content could:
        // * be static content (here or in a shared file)
        // * show the first page from the nav
        // * be in the database but add code to hide in the nav
        include(SHARED_PATH . '/static_homepage.php');
      }
    ?>

  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>

<!-- alt1: if we used commented out technique on public_navigation.php (to skip hidden subjects/pages), we wud have to modify this page to skip likely invisible 1st page:

} elseif(isset($_GET['subject_id'])) {
  $subject_id = $_GET['subject_id'];
  $page_set = find_pages_by_subject_id($subject_id);
  
  while($page = mysqli_fetch_assoc($page_set)) {
    if($page['visible']) {
      $page_id = $page['id'];
      break;
    }
  }  
  
  mysqli_free_result($page_set); 

  if(!$page) {
    redirect_to(url_for('/index.php'));
  } 

} else {-->