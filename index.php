<?php
// Include your database connection file
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GBFC Football Club</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #1a202c; /* Dark background color */
            color: #e2e8f0; /* Light text color */
        }
        .card {
            background-color: #2d3748; /* Darker card color */
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .text-teal-400 {
            color: #4fd1c5;
        }
        .bg-teal-500 {
            background-color: #4fd1c5;
        }
    </style>
</head>
<body class="p-8">
    <div class="max-w-7xl mx-auto">
        <header class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold text-teal-400 mb-2">Welcome to GBFC!</h1>
            <p class="text-lg text-gray-400">The official site of the GBFC Football Club.</p>
            <div class="flex justify-center space-x-4 mt-4">
                <a href="adminlogin.php" class="inline-block bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                    Admin Login
                </a>
                <a href="player_login.php" class="inline-block bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                    Player Login
                </a>
            </div>
        </header>

        <main class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Dynamic stats from the database -->
            <?php
            // Example of dynamic data
            $sql_players = "SELECT COUNT(*) AS total_players FROM players";
            $result_players = mysqli_query($link, $sql_players);
            $row_players = mysqli_fetch_assoc($result_players);
            $total_players = $row_players['total_players'];

            $sql_matches = "SELECT COUNT(*) AS total_matches FROM matches";
            $result_matches = mysqli_query($link, $sql_matches);
            $row_matches = mysqli_fetch_assoc($result_matches);
            $total_matches = $row_matches['total_matches'];
            ?>
            
           

            <!-- Team Roster Section -->
           
            </div>
        </main>
    </div>
</body>
</html>
