<?php
// Include config file
require_once "config.php";

// Check if id parameter is set
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Prepare a delete statement
    $sql = "DELETE FROM players WHERE id = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["id"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Records deleted successfully. Redirect to landing page
            header("location: player_management.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
} else {
    // If id isn't set, redirect to player management page
    header("location: player_management.php");
    exit();
}
?>
