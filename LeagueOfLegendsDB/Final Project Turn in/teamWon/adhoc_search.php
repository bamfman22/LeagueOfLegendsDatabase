<!DOCTYPE html>
<html>
<title>Ad-hoc Query</title>
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
<div class="w3-content" style="max-width:2000px;margin-top:200px">
    <div id="search" class ="w3-center w3-light-grey w3-container w3-padding-64 w3-margin width:60%">
        <form class="w3-container w3-margin" action="adhoc_search.php" method="post">
            <b>Query:</b>
<?php
            if(isset($_GET['query'])) {
                $query = htmlspecialchars($_GET['query']);
                echo "<input type=\"text\" class=\"padding-left: 4em; w3-input w3-border w3-round-large w3-animate-input\" name=\"query\" placeholder=\"Enter query\" value=\"$query\"/>";
            }
            else {
                echo "<input type=\"text\" class=\"padding-left: 4em; w3-input w3-border w3-round-large w3-animate-input\" name=\"query\" placeholder=\"Enter query\"/>";
            }
?>
            <br><br>
            <input type="submit">         
        </form>
    </div>
 
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
    
<?php
    
//TO-DO convert to HTML combo

$hn = "localhost";
$un = "root";
$pw = "";
$db = "teamwonleaguedb";

if(isset($_POST['query'])) {
    $query = $_POST['query'];
    
    if($query !== "") {
        $conn = new mysqli($hn, $un, $pw, $db);
        if ($conn->connect_error) die($conn->connect_error);
        mysqli_set_charset($conn, "utf8");

        if(isTransaction($query))
            transact($query, $conn); 
        else
            query($query, $conn);

        mysqli_close($conn);
    }
}    



//TO-DO result formatting
function query($str, $link) {
    $result = mysqli_query($link, $str);
    
    //FORMAT!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    if($result == false) {
        printf("Query failed!: %s\n", $link->error);
        return false;
    }
    
    $lineBreak = "<br>";
    //FORMAT!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    $numRows = mysqli_num_rows($result);
    $numFields = mysqli_num_fields($result);
    $fields = mysqli_fetch_fields($result);
    
    echo <<< _END
    <div id="results" class ="w3-center w3-light-grey w3-container w3-container w3-padding-64 w3-margin width:60%">
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

        for($j = 0; $j < $numFields; $j++) 
        {
            echo  "\t\t\t<td>" . $row[$j] . "</td>\n";
        }
        
        echo "\t\t</tr>";
    }
    
    echo <<< ENDER
        </table>
    </div>
ENDER;
    
    return true;
}

//Tooltip formatting and result
function transact($str, $link) {
    mysqli_autocommit($link, false);
    if(mysqli_begin_transaction($link) == false
            || mysqli_multi_query ($link, $str) == false)
    {
        printf("Operation failed!: %s\n", $link->error);
        mysqli_rollback($link);
        return false;
    }
    
    do {
        /* store first result set */
        if ($result = $link->store_result()) {
                $lineBreak = "<br>";
                //FORMAT!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                $numRows = mysqli_num_rows($result);
                $numFields = mysqli_num_fields($result);
                $fields = mysqli_fetch_fields($result);

                echo <<< _END
                <div class ="w3-center w3-light-grey w3-container w3-container w3-padding-64 w3-margin width:60%">
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
                    $row = $result->fetch_row();
                    echo "\t\t<tr>\n";

                    for($j = 0; $j < $numFields; $j++) 
                    {
                        echo  "\t\t\t<td>" . $row[$j] . "</td>\n";
                    }

                    echo "\t\t</tr>";
                }

                echo <<< ENDER
                    </table>
                </div>
ENDER;
            $result->free();
        }
    } while ($link->next_result());
    
    if(mysqli_commit($link) == false) {
        printf("Operation failed!: %s\n", $link->error);
        mysqli_rollback($link);
        return false;
    }
    
    mysqli_autocommit($link, true);
    return true;
}

function isTransaction($str) {
    if(substr_count($str, ';') >= 2 || strpos(trim($str), ";") < strlen($str) - 1)
        return true;
    
    return false;
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
  <p class="w3-medium">Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p>
</footer>
</body>
</html>