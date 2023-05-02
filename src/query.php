<?php
// Database information
$host = "sysmysql8.auburn.edu";
$username = "mem0250";
$password = "********";
$dbname = "mem0250db";

// Connect to database
global $host, $username, $password, $dbname;
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    echo "<div style='color: white; font-size: xx-large; text-align: center;'>" . mysqli_connect_error() . "</div>";
    die();
}

error_reporting(0);
?>

<!-- HTML and link stylesheet for pretty page :) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Mary Mitchell's (mem0250) Query Page</title>
    <link rel="stylesheet" href="style.css" type="text/css" media="all" />
</head>
<body>
    <div>
        <h1>
            Bookstore Database Query Page
        </h1>
    </div>
    <!-- Button for page navigation -->
    <div style=" text-align: center">
        <button class="indexPage" onclick="location.href='index.php';">Back to Home</button>
    </div>
    <!-- Form for inputting SQL queries -->
    <div style="text-align: center;">
        <form class="queryClass" method="POST" action="query.php">
            <label for="queryBox">Enter a SQL statement to query the database:</label>
            <textarea id="queryBox" name="query"><?= stripslashes($_POST["query"])?></textarea> <br />
            <input id="runQuery" type="submit" value="Submit"/>
        </form>
    </div>
    <!-- Interpret SQL input -->
    <?php
    // Make sure SQL query is allowable
    if(isset($_POST["query"])) {
        $query = strtolower(stripcslashes($_POST["query"]));
        $drop = "drop";
        if(stripos($query, $drop) !== false) { // DROP not allowed
            echo "<div style='color: white; font-size: xx-large; text-align: center;'>" . "DROP statements are not allowed for this DBMS." . "</div>";
            die();
        } else {
            try {
                $return = mysqli_query($conn, $query);
                if ($return == false) {
                    echo "<div style='color: white; font-size: xx-large; text-align: center;'>" . "Something was wrong with your query. Make sure it meets SQL syntax and is correctly referencing the database schema." . "</div>";
                    die();
                }
            } catch (Exception $exception) {
                echo "<div style='color: white; font-size: xx-large; text-align: center;'>" . "Something was wrong with your query. Make sure it meets SQL syntax and is correctly referencing the database schema." . "</div>";
                die();
            }
            if(stripos($query, "create") !== false) { // Return a statement depending on the type of request
                echo "<div style='color: white; font-size: xx-large; text-align: center;'>" . "Table Created" . "</div>";
            } elseif(stripos($query, "update") !== false){
                echo "<div style='color: white; font-size: xx-large; text-align: center;'>" . "Table Updated" . "</div>";
            } elseif(stripos($query, "delete") !== false){
                echo "<div style='color: white; font-size: xx-large; text-align: center;'>" . "Delete Statement Successful" . "</div>";
            } elseif(stripos($query, "insert") !== false){
                echo "<div style='color: white; font-size: xx-large; text-align: center;'>" . "Insert Statement Successful" . "</div>";
            } else {
                echo "<div style='color: white; font-size: xx-large; text-align: center;'>" . "Statement was Successful" . "</div>";
            }
    ?>
    <table>
        <thead>
        <?php
        if(stripos($query, "select") !== false) {
            $columnCount = mysqli_num_fields($return);
            $rowCount = mysqli_num_rows($return);
            echo "<tr>";
            // Get the names of the column fields
            for ($i = 0; $i < $columnCount; $i++) {
                $columnName = mysqli_fetch_field_direct($return, $i);
                echo "<th>" . $columnName->name . "</th>";
            }
            echo "</tr>";
        ?>
        </thead>
    <?php
            $rows = array();
            // Get each row
            while($row = mysqli_fetch_assoc($return)) {
                $rows[] = $row;
            }
            // Get the cells for each row
            foreach($rows as $r) {
                echo "<tr>";
                foreach($r as $cell) {
                    echo "<td>" . $cell . "</td>";
                }
                echo "</tr>";
            }
            mysqli_free_result($return);
        }
    ?>
    </table>
<?php
        }
    }
?>
</body>
</html>
<?php
// Close the connection to the database
mysqli_close($conn);
?>