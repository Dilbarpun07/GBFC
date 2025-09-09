<?php
// Start the session at the very top of the page
session_start();

// Check if the user is already logged in, if yes then redirect them to player dashboard
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: player_dashboard.php");
    exit;
}

// Include your database connection file
require_once 'config.php';

// Define variables and initialize with empty values
$player_name = $jersey_number = "";
$login_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if player_name is empty
    if (empty(trim($_POST["player_name"]))) {
        $login_err = "Please enter your player name.";
    } else {
        $player_name = trim($_POST["player_name"]);
    }

    // Check if jersey_number is empty
    if (empty(trim($_POST["jersey_number"]))) {
        if (!empty($login_err)) {
            $login_err .= " And your jersey number.";
        } else {
            $login_err = "Please enter your jersey number.";
        }
    } else {
        $jersey_number = trim($_POST["jersey_number"]);
    }

    // Validate credentials
    if (empty($login_err)) {
        $sql = "SELECT id, player_name FROM players WHERE player_name = ? AND jersey_number = ?";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $param_name, $param_number);
            
            $param_name = $player_name;
            $param_number = $jersey_number;
            
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $player_name_db);
                    mysqli_stmt_fetch($stmt);
                    
                    // Session variables
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $id;
                    $_SESSION["player_name"] = $player_name_db;
                    
                    // Redirect to player dashboard page
                    header("location: player_dashboard.php");
                } else {
                    $login_err = "Invalid player name or jersey number.";
                }
            } else {
                $login_err = "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Login</title>
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
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="card w-full max-w-sm p-8">
        <h2 class="text-2xl font-semibold mb-6 text-center text-teal-400">Player Login</h2>
        
        <?php if (!empty($login_err)): ?>
            <div class="bg-red-500 text-white font-bold p-4 rounded-lg mb-6 text-center">
                <?php echo $login_err; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-4">
                <label class="block text-gray-400 text-sm font-bold mb-2" for="player_name">
                    Player Name
                </label>
                <input type="text" name="player_name" id="player_name" placeholder="Enter your name" class="input-field shadow appearance-none rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo htmlspecialchars($player_name); ?>">
            </div>
            <div class="mb-6">
                <label class="block text-gray-400 text-sm font-bold mb-2" for="jersey_number">
                    Jersey Number
                </label>
                <input type="number" name="jersey_number" id="jersey_number" placeholder="Enter your jersey number" class="input-field shadow appearance-none rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo htmlspecialchars($jersey_number); ?>">
            </div>
            <div class="flex items-center justify-between">
                <input type="submit" class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 ease-in-out w-full" value="Login">
            </div>
        </form>
    </div>
</body>
</html>
