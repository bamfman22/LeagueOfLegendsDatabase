<!DOCTYPE html>
<html>
<title>Sample Queries</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="icon" href="images/icon.jpg">
<style>
body {font-family: "Lato", sans-serif}

</style>
<body>

<!-- Navbar -->
<div class="w3-top">
  <div class="w3-bar w3-black w3-card">
    <h1 class="w3-padding-large">League of Legends DB - 2016</h1>
  </div>
  <div class="w3-bar w3-black w3-card">
    <a class="w3-bar-item w3-button w3-padding-large w3-hide-medium w3-hide-large w3-right" href="javascript:void(0)" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="index.html" class="w3-bar-item w3-button w3-padding-large">HOME</a>
    <a href="about.html" class="w3-bar-item w3-button w3-padding-large w3-hide-small">ABOUT</a>
    <a href="search.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small">SEARCH</a>
    <a href="adhoc_search.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small">AD-HOC QUERY</a>
    <a href="sample.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small">SAMPLE QUERIES</a>
  </div>
</div>

<!-- Navbar on small screens -->
<div id="navDemo" class="w3-bar-block w3-black w3-hide w3-hide-large w3-hide-medium w3-top" style="margin-top:46px">
  <a href="about.html" class="w3-bar-item w3-button w3-padding-large">ABOUT</a>
  <a href="search.php" class="w3-bar-item w3-button w3-padding-large">SEARCH</a>
  <a href="adhoc_search.php" class="w3-bar-item w3-button w3-padding-large">AD-HOC QUERY</a>
  <a href="sample.php" class="w3-bar-item w3-button w3-padding-large">SAMPLE QUERIES</a>
</div>
    
<!-- Page content -->
<div class="w3-container w3-padding-64 w3-center w3-opacity w3-light-grey w3-xlarge" style="max-width:2000px;margin-top:200px">
    <h1> Sample Queries</h1>
    <br>
    <ol>
<?php
        echo "<li><a href=\"adhoc_search.php?query=".htmlspecialchars("SELECT Name, PlayerID FROM Players;")."\">SELECT Name, PlayerID FROM Players;</a></li>";
        echo "<li><a href=\"adhoc_search.php?query=".htmlspecialchars("SELECT ChampionName,COUNT(*) as count FROM Used GROUP BY ChampionName ORDER BY count DESC;")."\">SELECT ChampionName,COUNT(*) as count FROM Used GROUP BY ChampionName ORDER BY count DESC;></li>";
        echo "<li><a href=\"adhoc_search.php?query=".htmlspecialchars("SELECT Title, Place, Amount FROM PrizePool WHERE Amount > 100000 AND Place = 1;")."\">SELECT Title, Place, Amount FROM PrizePool WHERE Amount > 100000 AND Place = 1;</a></li>";
        echo "<li><a href=\"adhoc_search.php?query=".htmlspecialchars("SELECT Title FROM PrizePool WHERE Amount >= (SELECT max(Amount) FROM PrizePool);")."\">SELECT Title FROM PrizePool WHERE Amount >= (SELECT max(Amount) FROM PrizePool);</a></li>";
        echo "<li><a href=\"adhoc_search.php?query=".htmlspecialchars("SELECT COUNT(Name), Region FROM Teams GROUP BY Region;")."\">SELECT COUNT(Name), Region FROM Teams GROUP BY Region;</a></li>";
        echo "<li><a href=\"adhoc_search.php?query=".htmlspecialchars("SELECT DISTINCT Role FROM Players;")."\">SELECT DISTINCT Role FROM Players;</a></li>";
?>
    </ol>
</div>
<!-- Footer -->
<footer class="w3-container w3-padding-64 w3-center w3-opacity w3-light-grey w3-xlarge">
  <i class="fa fa-facebook-official w3-hover-opacity"></i>
  <i class="fa fa-instagram w3-hover-opacity"></i>
  <i class="fa fa-snapchat w3-hover-opacity"></i>
  <i class="fa fa-pinterest-p w3-hover-opacity"></i>
  <i class="fa fa-twitter w3-hover-opacity"></i>
  <i class="fa fa-linkedin w3-hover-opacity"></i>
  <p class="w3-medium">Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p></footer>

<script>
// Automatic Slideshow - change image every 4 seconds
var myIndex = 0;
carousel();

function carousel() {
    var i;
    var x = document.getElementsByClassName("mySlides");
    for (i = 0; i < x.length; i++) {
       x[i].style.display = "none";  
    }
    myIndex++;
    if (myIndex > x.length) {myIndex = 1}    
    x[myIndex-1].style.display = "block";  
    setTimeout(carousel, 10000);    
}

// Used to toggle the menu on small screens when clicking on the menu button
function myFunction() {
    var x = document.getElementById("navDemo");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else { 
        x.className = x.className.replace(" w3-show", "");
    }
}
</script>

</body>
</html>