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
?>

<!-- HTML and link stylesheet for pretty page :) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Mary Mitchell's (mem0250) Table Page</title>
    <link rel="stylesheet" href="style.css" type="text/css" media="all" />
</head>
<body>
    <div>
        <h1>
            Bookstore Database Tables
        </h1>
    </div>
    <!-- Button for page navigation -->
    <div style="text-align: center">
        <button class="indexPage" onclick="location.href='index.php';">Back to Home</button>
    </div>
    <!-- Get the names of each table in the database -->
    <?php
    $query = "SHOW TABLES FROM mem0250db";
    $tables= mysqli_query($conn, $query);
    // Loop through each table in the database
    while($table = mysqli_fetch_array($tables)) {
        echo "<h2>" . $table[0] . "</h2>";
    ?>
        <table>
            <thead>
                <!-- For the current table print the column fields -->
                <?php
                $getTable = "SELECT * FROM " . $table[0];
                $getTableQuery = mysqli_query($conn, $getTable);
                $columnCount = mysqli_num_fields($getTableQuery);
                $rowCount = mysqli_num_rows($getTableQuery);
                echo "<tr>";
                // Get the names of the column fields
                for($i = 0; $i < $columnCount; $i++) {
                    $columnName = mysqli_fetch_field_direct($getTableQuery, $i);
                    echo "<th>" . $columnName->name . "</th>";
                }
                echo "</tr>";
                ?>
            </thead>
            <!-- For the current table print the cells -->
            <?php
            $rows = array();
            // Get each row
            while($row = mysqli_fetch_assoc($getTableQuery)) {
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
            mysqli_free_result($getTableQuery);
            ?>
            <br />
        </table>
<?php
    }
?>
</body>
</html>
<?php
// Close the connection to the database
mysqli_close($conn);
?>