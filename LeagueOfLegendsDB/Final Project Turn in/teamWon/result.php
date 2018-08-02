<!DOCTYPE html>
<html>
<?php
    if(!isset($_GET['id']) && !isset($_GET['type'])) {
        echo '<script type="text/javascript">
           window.location = "search.php";
      </script>';
        die();
    }
    
    $name = htmlspecialchars($_GET['id']);
    
    echo "<title>$name</title>";
?>
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
<div class="w3-content" style="max-width:2000px;margin-top:175px">
    
<?php
    $hn = "localhost";
    $un = "root";
    $pw = "";
    $db = "teamwonleaguedb"; 
    
    $name = htmlspecialchars($_GET['id']);
    $type = htmlspecialchars($_GET['type']);
    
    echo<<<_END
    <div class ="w3-center w3-container w3-light-grey w3-container w3-margin width:60%">
        <h2><i>$type</i></h2>
        <h1><b>$name</b></h1>
    </div>
    <div class =" w3-container w3-light-grey w3-container w3-margin width:60%">
_END;
    
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);
    mysqli_set_charset($conn, "utf8");
    
    if($type == "Team")
        team($name, $conn);
    else if($type == "Player")
        player($name, $conn);
    else if($type == "Champion")
        champion($name, $conn);
    else if($type == "League")
        league($name, $conn);
    
    echo "</div>";
    
    function champion($name, $conn) {
        echo<<<_END
        <div class ="w3-center w3-light-grey w3-container w3-margin width:60%">
            <table class="w3-table-all w3-center">
                <thead class=>
                    <tr class="w3-light-grey">   
_END;
        echo "\t\t\t<th>Roles</th>\n";
        echo <<< _END
                    </tr>
                </thead>
_END;
        $query = "SELECT * FROM champions where Name = \"$name\";";
        $result = mysqli_query($conn, $query);
        $lineBreak = "<br>";
        $numRows = mysqli_num_rows($result);
        $numFields = mysqli_num_fields($result);
        $fields = mysqli_fetch_fields($result);
        
        for($i = 0; $i < $numRows; $i++) {
            $row = mysqli_fetch_array($result);
            if($row[1] == "1")
                echo "\t\t<tr>\n\t\t\t<td>Melee</td>\n\t\t</tr>";
            if($row[2] == "1")
                echo "\t\t<tr>\n\t\t\t<td>Ranged</td>\n\t\t</tr>";
            if($row[3] == "1")
                echo "\t\t<tr>\n\t\t\t<td>Stealth</td>\n\t\t</tr>";
        }
        
        echo <<< ENDER
        </table>
        </div>
ENDER;
        $championsQ = "SELECT PlayerID,COUNT(*) as count FROM Used WHERE ChampionName = \"$name\" GROUP BY PlayerID ORDER BY count DESC;";
        $championsR = mysqli_query($conn, $championsQ);
        $lineBreak = "<br>";
        $numRows = mysqli_num_rows($championsR);
        $numFields = mysqli_num_fields($championsR);
        $fields = mysqli_fetch_fields($championsR);
        
         echo<<<_END
        <div class ="w3-center w3-light-grey w3-container w3-margin width:60%">
            <table class="w3-table-all w3-center">
                <thead>
                    <tr class="w3-light-grey">   
_END;
        echo "\t\t\t<th>Player</th>\n";
        echo "\t\t\t<th>Times used</th>\n";
        echo <<< _END
                    </tr>
                </thead>
_END;
        
        for($i = 0; $i < $numRows; $i++) 
        {
            $row = mysqli_fetch_array($championsR);
            echo "\t\t<tr>\n";
            
            echo  "\t\t\t<td><a href=\"result.php?id=" . htmlspecialchars($row[0]) . "&type=Player\">" . $row[0] . "</a></td>\n";
            echo  "\t\t\t<td>" . $row[1] . "</td>\n";   
            echo "\t\t</tr>";
        }
        
        echo <<< ENDER
        </table>
        </div>
ENDER;
        
    }
    
    function league($name, $conn) {
        $teams = "SELECT TeamName AS \"Team\" FROM teamin WHERE TournamentName = \"$name\"";
        $teamsR = mysqli_query($conn, $teams);
        $winner = "SELECT Winner FROM prizepool WHERE Title=\"$name\" AND Place = 1";
        $winner = mysqli_query($conn, $winner);
        $winner = mysqli_fetch_array($winner)[0];
        echo<<<_END
            <div class ="w3-center w3-light-grey w3-container w3-margin width:60%">
            <h4><b>Winner:</b></h4>
_END;
        echo "<h4><b>$winner</b></h4></div>";
            
        echo<<<_END
            <div class ="w3-left w3-light-grey w3-container w3-margin width:60%">
            <h4><b>Teams in League</b></h4>
            <table class="w3-table-all w3-center">
                <thead>
                    <tr class="w3-light-grey">   
_END;
        $numRows = mysqli_num_rows($teamsR);
        $numFields = mysqli_num_fields($teamsR);
        $fields = mysqli_fetch_fields($teamsR);
        for($j = 0; $j < $numFields; $j++) {
            echo "\t\t\t<th>" . $fields[$j]->name . "</th>\n";
        }
        


        echo <<< _END
                    </tr>
                </thead>
_END;
    
        for($i = 0; $i < $numRows; $i++) 
        {
            $row = mysqli_fetch_array($teamsR);
            echo "\t\t<tr>\n";
            
            echo  "\t\t\t<td><a href=\"result.php?id=" . htmlspecialchars($row[0]) . "&type=Team\">" . $row[0] . "</a></td>\n";

            echo "\t\t</tr>";
        }
    
        echo <<< ENDER
        </table>
        </div>
ENDER;
        
        echo<<<_END
            <div class ="w3-right w3-light-grey w3-container w3-margin width:60%">
            <h4><b>Teams and Prizes</b></h4>
            <table class="w3-table-all w3-center">
                <thead>
                    <tr class="w3-light-grey">   
_END;
        $teams = "SELECT Winner, Place, Amount FROM prizepool WHERE Title = \"$name\"";
        $teamsR = mysqli_query($conn, $teams);
        $numRows = mysqli_num_rows($teamsR);
        $numFields = mysqli_num_fields($teamsR);
        $fields = mysqli_fetch_fields($teamsR);
        for($j = 0; $j < $numFields; $j++) {
            echo "\t\t\t<th>" . $fields[$j]->name . "</th>\n";
        }
        


        echo <<< _END
                    </tr>
                </thead>
_END;
    
        for($i = 0; $i < $numRows; $i++) 
        {
            $row = mysqli_fetch_array($teamsR);
            echo "\t\t<tr>\n";
            
            echo  "\t\t\t<td><a href=\"result.php?id=" . htmlspecialchars($row[0]) . "&type=Team\">" . $row[0] . "</a></td>\n";
            echo "\t\t\t<td>$row[1]</a></td>\n";
            echo "\t\t\t<td>$row[2]</a></td>\n";

            echo "\t\t</tr>";
        }
    
        echo <<< ENDER
        </table>
        </div>
ENDER;
        
    }
    
    function player($name, $conn) {
        $playerQ = "Select * from players where PlayerID =\"$name\";";
        $championsQ = "SELECT ChampionName,COUNT(*) as count FROM Used WHERE PlayerID = \"$name\" GROUP BY ChampionName ORDER BY count DESC;";
        $teamQ = "SELECT TeamName FROM Have WHERE PlayerID = \"$name\";";
        
        
        $result = mysqli_query($conn, $playerQ);
        $row = mysqli_fetch_array($result);
        $realName = $row[1];
        $country = $row[3];
        $role = $row[2];

        $championsR = mysqli_query($conn, $championsQ);
        
        $result = mysqli_query($conn, $teamQ);
        $row = mysqli_fetch_array($result);
        $team = $row[0];
        
        $regionQ = "SELECT Region FROM teams Where Name = \"$team\"";
        $result = mysqli_query($conn, $regionQ);
        $row = mysqli_fetch_array($result);
        $region = $row[0];
        
        echo "<div class =\"w3-left w3-light-grey w3-container w3-margin width:60%\"><pre><i>Name:&#9;</i><b>$realName</b><br><i>Country:&#9;</i><b>$country</b><br><i>Team:&#9;</i><a href=\"result.php?id=" . htmlspecialchars($team) . "&type=Team\"><b>$team</b></a><br><i>Region:&#9;</i><b>$region</b></pre></div>";
        
        echo<<<_END
        <div class ="w3-right w3-light-grey w3-container w3-margin width:60%">
            <table class="w3-table-all w3-center">
                <thead>
                    <tr class="w3-light-grey">   
_END;
        $lineBreak = "<br>";
        $numRows = mysqli_num_rows($championsR);
        $numFields = mysqli_num_fields($championsR);
        $fields = mysqli_fetch_fields($championsR);
        
        echo "\t\t\t<th>Champion</th>\n";
        echo "\t\t\t<th>Times used</th>\n";
        echo <<< _END
                    </tr>
                </thead>
_END;
        
        for($i = 0; $i < $numRows; $i++) 
        {
            $row = mysqli_fetch_array($championsR);
            echo "\t\t<tr>\n";
            
            echo  "\t\t\t<td><a href=\"result.php?id=" . htmlspecialchars($row[0]) . "&type=Champion\">" . $row[0] . "</a></td>\n";
            echo  "\t\t\t<td>" . $row[1] . "</td>\n";   
            echo "\t\t</tr>";
        }
        
        echo <<< ENDER
        </table>
        </div>
ENDER;
    }
    
    //Add Leagues Played
    function team($name, $conn){
        $players = "SELECT h.PlayerID, p.Role FROM Have h, Players p WHERE h.TeamName = \"$name\" AND p.PlayerID = h.PlayerID;";
        $region = "SELECT Region FROM teams Where Name = \"$name\"";
        $tournaments = "SELECT TournamentName as \"League\" FROM teamin where TeamName=\"$name\"";
        
        $tournamentsR = mysqli_query($conn, $tournaments);
        
        $result = mysqli_query($conn, $players);
        if($result == false) {
            printf("Query failed!: %s\n", $conn->error);
        }
        
        $lineBreak = "<br>";
        //FORMAT!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $numRows = mysqli_num_rows($result);
        $numFields = mysqli_num_fields($result);
        $fields = mysqli_fetch_fields($result);
        $regionResult = mysqli_fetch_array(mysqli_query($conn, $region))[0];
        echo <<< _END
        <h5 class="w3-center"><i>Region:</i></h5>
        <h4 class="w3-center"><b>$regionResult</b></h4>
        <div id="results" class ="w3-left w3-light-grey w3-container w3-margin width:60%">
            <h4><b>Team Players</b></h4>
            <table class="w3-table-all w3-center">
                <thead>
                    <tr class="w3-light-grey">   
_END;
      
        for($j = 0; $j < $numFields; $j++) {
            echo "\t\t\t<th>" . $fields[$j]->name . "</th>\n";
        }
        


        echo <<< _END
                    </tr>
                </thead>
_END;
    
        for($i = 0; $i < $numRows; $i++) 
        {
            $row = mysqli_fetch_array($result);
            echo "\t\t<tr>\n";
            
            echo  "\t\t\t<td><a href=\"result.php?id=" . htmlspecialchars($row[0]) . "&type=Player\">" . $row[0] . "</a></td>\n";
            echo  "\t\t\t<td>" . $row[1] . "</td>\n";       

            echo "\t\t</tr>";
        }
    
        echo <<< ENDER
        </table>
        </div>
        <div class ="w3-right w3-light-grey w3-container w3-margin width:60%">
            <h4><b>Tournaments Won</b></h4>
ENDER;
        $won = "SELECT TournamentName as \"Tournament\", TournamentDate as \"Date\" FROM win WHERE TeamName = \"$name\"";
        $prize = "SELECT Amount FROM prizepool WHERE Place = 1 AND Title = \"";
        echo <<< _END
        <table class="w3-table-all w3-center">
                <thead>
                    <tr class="w3-light-grey">
_END;
        $total = 0;
        $result = mysqli_query($conn, $won);
        if($result == false)
            printf("Query failed!: %s\n", $conn->error);
        else{
        
            $lineBreak = "<br>";
            //FORMAT!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            $numRows = mysqli_num_rows($result);
            $numFields = mysqli_num_fields($result);
            $fields = mysqli_fetch_fields($result);

            for($j = 0; $j < $numFields; $j++) {
                echo "\t\t\t<th>" . $fields[$j]->name . "</th>\n";
            }
            
            echo "\t\t\t<th>Amount Won</th>\n";
            
            echo <<< _END
                        </tr>
                    </thead>
_END;
    
            for($i = 0; $i < $numRows; $i++) 
            {
                $row = mysqli_fetch_array($result);
                echo "\t\t<tr>\n";

                echo  "\t\t\t<td><a href=\"result.php?id=" . htmlspecialchars($row[0]) . "&type=League\">" . $row[0] . "</a></td>\n";
                echo  "\t\t\t<td>" . $row[1] . "</td>\n";         
                
                $thisPrize = $prize . $row[0] . "\";";
                $prizeResult = mysqli_query($conn, $thisPrize);
                $prizeRow = mysqli_fetch_array($prizeResult);
                echo  "\t\t\t<td>$" . $prizeRow[0] . "</td>\n"; 
                
                $total += $prizeRow[0];
                echo "\t\t</tr>";
            }
            
            echo "</table><h5><b>Total won: $$total</b></h5></div>";
        }
        echo<<<_END
            <div class ="w3-center w3-light-grey w3-container w3-margin width:60%">
            <h4><b>Leagues Played</b></h4>
            <table class="w3-table-all w3-center">
                <thead>
                    <tr class="w3-light-grey">   
_END;
        $numRows = mysqli_num_rows($tournamentsR);
        $numFields = mysqli_num_fields($tournamentsR);
        $fields = mysqli_fetch_fields($tournamentsR);
        for($j = 0; $j < $numFields; $j++) {
            echo "\t\t\t<th>" . $fields[$j]->name . "</th>\n";
        }
        


        echo <<< _END
                    </tr>
                </thead>
_END;
    
        for($i = 0; $i < $numRows; $i++) 
        {
            $row = mysqli_fetch_array($tournamentsR);
            echo "\t\t<tr>\n";
            
            echo  "\t\t\t<td><a href=\"result.php?id=" . htmlspecialchars($row[0]) . "&type=League\">" . $row[0] . "</a></td>\n";

            echo "\t\t</tr>";
        }
    
        echo <<< ENDER
        </table>
        </div>
ENDER;
    
        return true;
    }
?>
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