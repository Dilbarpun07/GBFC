<?php
// Include your database connection file
require_once 'config.php';

// Define variables and initialize with empty values
$name = $position = $jersey_number = "";
$name_err = $position_err = $jersey_number_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate player ID
    if (!isset($_POST["id"]) || empty(trim($_POST["id"]))) {
        echo "Invalid player ID.";
        exit();
    }
    $id = trim($_POST["id"]);

    // Validate name
    if (empty(trim($_POST["player_name"]))) {
        $name_err = "Please enter a player name.";
    } else {
        $name = trim($_POST["player_name"]);
    }

    // Validate position
    if (empty(trim($_POST["position"]))) {
        $position_err = "Please enter a position.";
    } else {
        $position = trim($_POST["position"]);
    }

    // Validate jersey number
    if (empty(trim($_POST["jersey_number"]))) {
        $jersey_number_err = "Please enter a jersey number.";
    } else {
        $jersey_number = trim($_POST["jersey_number"]);
    }

    // Check input errors before updating in database
    if (empty($name_err) && empty($position_err) && empty($jersey_number_err)) {
        $sql = "UPDATE players SET player_name=?, position=?, jersey_number=? WHERE id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_name, $param_position, $param_jersey_number, $param_id);

            // Set parameters
            $param_name = $name;
            $param_position = $position;
            $param_jersey_number = $jersey_number;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to player management page
                header("location: player_management.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id = trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM players WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    // Fetch result row as an associative array. Since the result set
                    // contains only one row, we don't need to use a while loop
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field values
                    $name = $row["player_name"];
                    $position = $row["position"];
                    $jersey_number = $row["jersey_number"];
                } else {
                    // URL doesn't contain a valid id. Redirect to error page
                    header("location: player_management.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: player_management.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Player</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #1a202c;
            color: #e2e8f0;
        }
        .card {
            background-color: #2d3748;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .input-field {
            background-color: #4b5563;
            border: 1px solid #6b7280;
            color: white;
        }
        .text-teal-400 {
            color: #4fd1c5;
        }
        .bg-teal-500 {
            background-color: #4fd1c5;
        }
        .hover\:bg-teal-600:hover {
            background-color: #38b2ac;
        }
        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-xl">
        <div class="card p-8 shadow-lg">
            <h1 class="text-3xl font-extrabold text-center text-teal-400 mb-6">Edit Player Details</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                <div class="mb-4">
                    <label for="player_name" class="block text-gray-400 text-sm font-semibold mb-2">Player Name</label>
                    <input type="text" name="player_name" id="player_name" class="input-field w-full px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 <?php echo (!empty($name_err)) ? 'border-red-500' : ''; ?>" value="<?php echo $name; ?>">
                    <span class="text-red-500 text-xs"><?php echo $name_err; ?></span>
                </div>
                <div class="mb-4">
                    <label for="position" class="block text-gray-400 text-sm font-semibold mb-2">Position</label>
                    <input type="text" name="position" id="position" class="input-field w-full px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 <?php echo (!empty($position_err)) ? 'border-red-500' : ''; ?>" value="<?php echo $position; ?>">
                    <span class="text-red-500 text-xs"><?php echo $position_err; ?></span>
                </div>
                <div class="mb-6">
                    <label for="jersey_number" class="block text-gray-400 text-sm font-semibold mb-2">Jersey Number</label>
                    <input type="text" name="jersey_number" id="jersey_number" class="input-field w-full px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 <?php echo (!empty($jersey_number_err)) ? 'border-red-500' : ''; ?>" value="<?php echo $jersey_number; ?>">
                    <span class="text-red-500 text-xs"><?php echo $jersey_number_err; ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-300 ease-in-out">
                        Update Player
                    </button>
                    <a href="player_management.php" class="inline-block text-gray-400 hover:text-gray-300">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

