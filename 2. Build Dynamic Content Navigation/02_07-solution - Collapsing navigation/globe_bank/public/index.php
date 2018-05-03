<!-- basic version of this file is available at ex 02_06 -->
<?php require_once('../private/initialize.php'); ?>
<?php 
if(isset($_GET['id'])) { // if we click page links
  $page_id = $_GET['id']; // used here and in public_navigation.php 
  $page = find_page_by_id($page_id); // used to show content here
  if(!$page) {//if id param is empty, 0 or beyond page number which makes $page blank
    redirect_to(url_for('/index.php'));
  }
  $subject_id = $page['subject_id']; // used in public_navigation.php
} elseif(isset($_GET['subject_id'])) { // if we click subject links
  $subject_id = $_GET['subject_id'];
  $page_set = find_pages_by_subject_id($subject_id);
  $page = mysqli_fetch_assoc($page_set); // opens 1st page of clicked subject
  mysqli_free_result($page_set);
  if(!$page) {
    redirect_to(url_for('/index.php'));
  }
  $page_id = $page['id'];
}

?>

<?php include(SHARED_PATH . '/public_header.php'); ?>

<div id="main">

  <?php include(SHARED_PATH . '/public_navigation.php'); ?>

  <div id="page">

    <?php
      if(isset($page)) { // if we clicked the nav links
        // show the page from the database
        // TODO add html escaping back in
        echo $page['content'];

      } else {
        // Show the homepage
        // The homepage content could:
        // * be static content (here or in a shared file) as in this example
        // * show the first page from the nav
        // * be in the database but add code to hide in the nav
        include(SHARED_PATH . '/static_homepage.php');
      }
    ?>

  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>
