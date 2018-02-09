<html>

  <head>

    <title>Followers ranking - My Steemit Friends</title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

    <script src="jquery/jquery-3.2.1.min.js"></script>

    <script src="extensions/popper.min.js"></script>

    <script src="bootstrap/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="style.css?2">

    <style>

	.alignright {
   		text-align: right;
	}

    a.page-link{

 

    color:blue !important;

    }

    a.page-link:visited {

      color:blue !important;

    }

    ul {

    margin:0.5rem;

    }
		
    ul.navbar-nav {

    margin:0px;

    }

    li {     

    

   

    }

    

    a.btn-info, a.btn-info:visited, a.btn-primary, a.btn-primary:visited {

    color:white !important;

    } 

    a.btn-light {

      color:blue !important;

    }

    a.btn-light:visited {

      color:blue !important;

    }

    

    </style>

  </head>

  <body class="bg-4">  
    
   
<nav id="mynav" class="navbar navbar-expand-sm navbar-dark">
  <span class="navbar-brand mb-0 h1"><a href="http://steemit.com/@magicmonk"><img src="images/magicmonkhead.png" width="64px">@magicmonk</a></span>

    <a class="btn btn-lg btn-primary navbutton nounderline"  href="index.php">Upvote Stats</a>
    <a class="btn btn-lg btn-success navbutton nounderline"  href="conversation.php">Conversations</a>
    <div class="btn-group navbutton" id="rankingbtn">
    <button type="button" class="btn btn-lg btn-info dropdown-toggle " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width:10rem">Rankings</button>
    <div class="dropdown-menu">
    	<a class="dropdown-item" href="followers.php">Followers</a>
			<a class="dropdown-item" href="effectiveSP.php">Effective SP</a>
			<a class="dropdown-item" href="reputation.php">Reputation</a>	     
    </div>
  </div><!-- /btn-group -->
    <a class="btn btn-lg btn-danger navbutton nounderline"  href="upvotelist.php">$ Calculator</a>
</nav>        
    

    <div class="container-fluid bg-4 text-center" style="max-width:1000px;">

    <div class="row">

     <div class="col">

     

<h1>Steemit Reputation Ranking</h1>       

<br>

<div style="border:5px solid white;padding:10px;">

<h3>Search Username for Ranking</h3>


<form>Steemit UserName: <input id="username" type="text" size="15"></form>

<button type="button" onclick="loadDoc()">Show Ranking (wait a few seconds)</button><br><br>

<div id="ranking"></div>

</div>

<br><br>

<div style="border:5px solid white;padding:10px;">

<h3>Select page number</h3><br>


<?php
include 'steemSQLconnect2.php';		


$numberofpages=7;

$numberofrows=10;

$pagesize=50;

if ($_GET["page"]) {

$page = $_GET["page"];

} else {$page=1;}





if ($_GET["highlight"]) {

$highlight = strtolower($_GET["highlight"]);

}









// where to start the results

$offset = ($page - 1) * $pagesize;





echo '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center">';



if ($page>=4) {

for ($x=$page-3;$x<=$page+3;$x++) {      

      if ($x==$page) {

        echo '<li class="page-item active"><a class="page-link" href="reputation.php?page='.$x.'">'.$x.'</a></li>';

      } else {

        echo '<li class="page-item"><a class="page-link" href="reputation.php?page='.$x.'">'.$x.'</a></li>';

      }  

  }

}  else {

  for ($x=1;$x<=$numberofpages;$x++) {      

      if ($x==$page) {

        echo '<li class="page-item active"><a class="page-link" href="reputation.php?page='.$x.'">'.$x.'</a></li>';

      } else {

        echo '<li class="page-item"><a class="page-link" href="reputation.php?page='.$x.'">'.$x.'</a></li>';

      }  

  }    

} 

echo '</ul></nav><br>';

if ($page>1) {

echo '<a href="reputation.php?page='.($page-1).'" class="btn btn-light" role="button">Previous Page</a> ';

}

echo '<a href="reputation.php?page='.($page+1).'" class="btn btn-light" role="button">Next Page</a><br><br>'; 



echo '<form action="reputation.php" method="get">Go To Page Number <input type="text" name="page" size="5"> <input type="submit" value="Go"></form><br></div>';



    
    $sql = "
	
	;With q1 as
(
SELECT name, cast(log10(reputation)*9 - 56 as decimal(4,2)) as rep
FROM Accounts
ORDER BY rep DESC
OFFSET ".$offset." ROWS
FETCH NEXT ".$pagesize." ROWS ONLY
), 

q2 as
(
select follower as name, count(*) AS Following 
from Followers (NOLOCK)
group by follower
), 

q3 as 
(
select Following as name, count(*) AS Followers 
from Followers (NOLOCK)
group by following
)

select q1.name AS UserName, q1.rep as Reputation, q3.Followers, q2.Following
from q1 
LEFT JOIN q2 ON q1.name=q2.name
LEFT JOIN q3 ON q1.name=q3.name
order by q1.rep DESC";
 

    // execute the query. Store the results in sth variable.

    $sth = $conn->prepare($sql);

    $sth->execute();

    echo '</div><div class="col">';

echo '<table id="bigtable" class="table table-sm" style="background-color:#0f4880;border:5px solid white">';

    

echo '<thead class="thead-default mobile"><tr><th style="text-align: center;">Rank</th><th>UserName</th><th class="alignright">Reputation</th><th class="alignright">Followers</th><th class="alignright">Following</th></tr></thead>';

    // print the results. If successful, magicmonk will be printed on page.

    $rank=$pagesize*($page-1)+1;

    while ($row = $sth->fetch(PDO::FETCH_NUM)) { 

	$steemitUser=$row[0];
	$followers=$row[2];
	$following=$row[3];
	$reputation=$row[1];
		
      echo '<tr><td style="text-align: center;">';

      echo $rank;

      $rank++;

      echo "</td><td>";

	  	
      if ($steemitUser==$highlight) {

      echo '<span style="background-color:red">';

      } 

      echo '<a tabindex="0" data-trigger="click" data-toggle="popover" data-content="<p><a class=\'nounderline btn btn-primary\' href=\'http://steemit.com/@'.$steemitUser.'/\'>Steemit Profile</p><a class=\'nounderline btn btn-info\' href=\'index.php?User='.$steemitUser.'\'>MSF Profile</a>">'.$steemitUser.'</a>';

      if ($steemitUser==$highlight) {

      echo '</span>';

      } 

          	echo "</td><td class='alignright'>";

          echo number_format($reputation,2);

          echo "</td><td class='alignright'>";

          echo $followers;
		
		 echo "</td><td class='alignright'>";

        if ($following) {echo $following;} else {echo "0";}
		
	

          echo "</td></tr>";

          

        }

        echo "</table>";



// terminate connectiion

unset($conn); unset($sth);

  

?>  



</div>

</div>

</div>

</body>



<script>



$(function(){

    // Enables popover with html content

    $("[data-toggle=popover]").popover(

    {

    html:true

    }

    );

  

  

  // code for making sure only 1 pop over is open, the others close.

  

  $("[data-toggle=popover]").on('click', function (e) {

        $("[data-toggle=popover]").not(this).popover('hide');

    });

  

  

    

});



function loadDoc() {

  document.getElementById("ranking").innerHTML = "Loading..";

  var username;

  username =  document.getElementById("username").value;

  username = username.replace("@","");

  var xhttp = new XMLHttpRequest();

  xhttp.onreadystatechange = function() {

    if (this.readyState == 4 && this.status == 200) {

      document.getElementById("ranking").innerHTML = this.responseText;

    }

  };

  xhttp.open("GET", "get_reputation_rank.php?SteemitUser=" + username, true);

  xhttp.send();

}

</script>







</html>