<?php

// connect to SteemSQL database
include 'steemSQLconnect2.php';

// Make your model available
include 'models/articlelistmodel.php';

// Get values from URL  
// voter details stripped from URL
if (isset($_GET["voter"])) {
  $voter = rtrim($_GET["voter"]);
} else {
  $voter=NULL;
}     

// check if date has been entered (submitted via form)
if (isset($_GET["date"])) {
  $date = $_GET["date"];
} else {
  // set date variable for SQL query.
  $date = date("Y-m-d", strtotime("-1 months"));
}     

// check if to date has been entered (submitted via form)
if (isset($_GET["toDate"])) {
  $todate = $_GET["toDate"];
} else { 
  $todate = date("Y-m-d", strtotime("+1 day")); 
}     
if (isset($_GET["mode"])) {
  $mode = $_GET["mode"];
}             

// retrieve choice for whether to include articles only or to include comments as well.
if (isset($_GET["Articlesonly"])) {
  $articlesonly = $_GET["Articlesonly"];
} else {
  $articlesonly = 1;
} 

// retrieve the tag input box value.
if (isset($_GET["tag"])) {
  $tag =$_GET["tag"];
  $tag = trim($tag);
  $tag = explode(" ", $tag)	;	
} else {
  $tag=NULL;
}

// retrieve the title input box value.
if (isset($_GET["title"])) {
  $title=$_GET["title"];
} else {
  $title=NULL;
}

// create an instance
$articlelistmodel = new articlelistmodel($conn);
if (isset($voter)&&$mode=="upvote") {
  // get list of results
  $results = $articlelistmodel -> gethistory($date,$todate,$voter,$articlesonly,$tag,$title);
} elseif ((isset($voter)&&$mode=="written") || (isset($tag)&&$mode=="written")) {
  $results = $articlelistmodel -> getwritten($date,$todate,$voter,$articlesonly,$tag,$title); 
}

// Show the view
include 'views/articlelistview.php';
$articlelistmodel -> close_connection();
?>
