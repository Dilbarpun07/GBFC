<?php
// Include your database connection file
require_once 'config.php';

// Define variables and initialize with empty values
$name = $position = $jersey_number = "";
$name_err = $position_err = $jersey_number_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Check input errors before inserting in database
    if (empty($name_err) && empty($position_err) && empty($jersey_number_err)) {
        $sql = "INSERT INTO players (player_name, position, jersey_number) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_position, $param_jersey_number);

            // Set parameters
            $param_name = $name;
            $param_position = $position;
            $param_jersey_number = $jersey_number;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to player management page
                header("location: player_management.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Player</title>
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
            <h1 class="text-3xl font-extrabold text-center text-teal-400 mb-6">Add New Player</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                        Add Player
                    </button>
                    <a href="player_management.php" class="inline-block text-gray-400 hover:text-gray-300">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
