<?php
// Include your database connection file
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .hover\:bg-teal-600:hover {
            background-color: #319795;
        }
        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .hover\:scale-105:hover {
            transform: scale(1.05);
        }
        .transition-transform {
            transition-property: transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>
</head>
<body class="p-8">
    <div class="max-w-7xl mx-auto">
        <header class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold text-teal-400 mb-2">GBFC Admin Dashboard</h1>
            <p class="text-lg text-gray-400">Manage your football club with ease.</p>

        
        </header>
        

        <main class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Player Management Card -->
            <a href="player_management.php" class="card p-6 flex flex-col items-center justify-center text-center transition-transform transform hover:scale-105 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-teal-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15M17.25 10.5H6.75A2.25 2.25 0 014.5 8.25V6a2.25 2.25 0 012.25-2.25h10.5A2.25 2.25 0 0119.5 6v2.25a2.25 2.25 0 01-2.25 2.25zM12 18.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" />
                </svg>
                <h2 class="text-xl font-semibold mb-1">Player Management</h2>
                <p class="text-sm text-gray-400">Add, edit, and view player profiles.</p>
            </a>

            <!-- Other Management Cards (Team, Match, etc.) -->
            <!-- These are placeholders and will need their href attributes updated to point to the correct files -->
              <a href="match_schedule.php" class="card p-6 flex flex-col items-center justify-center text-center transition-transform transform hover:scale-105 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-teal-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15M17.25 10.5H6.75A2.25 2.25 0 014.5 8.25V6a2.25 2.25 0 012.25-2.25h10.5A2.25 2.25 0 0119.5 6v2.25a2.25 2.25 0 01-2.25 2.25zM12 18.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" />
                </svg>
                <h2 class="text-xl font-semibold mb-1">Match Schedule</h2>
                <p class="text-sm text-gray-400">Plan and manage upcoming games.</p>
            </a>
           
           
            <div class="card p-6 flex flex-col items-center justify-center text-center shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-teal-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5a2 2 0 00-2 2v6a2 2 0 002 2h14a2 2 0 002-2v-6a2 2 0 00-2-2zM8 11V9a4 4 0 014-4h0a4 4 0 014 4v2m-4 9a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
                <h2 class="text-xl font-semibold mb-1">Squad & Strategy</h2>
                <p class="text-sm text-gray-400">Manage team lineups and tactics.</p>
            </div>
            
             <a href="training.php" class="card p-6 flex flex-col items-center justify-center text-center transition-transform transform hover:scale-105 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-teal-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15M17.25 10.5H6.75A2.25 2.25 0 014.5 8.25V6a2.25 2.25 0 012.25-2.25h10.5A2.25 2.25 0 0119.5 6v2.25a2.25 2.25 0 01-2.25 2.25zM12 18.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" />
                </svg>
                <h2 class="text-xl font-semibold mb-1">Training Schedule</h2>
                <p class="text-sm text-gray-400">Training/Practice days.</p>
            </a>

           

            <?php
            // Example of dynamic data
            $sql_players = "SELECT COUNT(*) AS total_players FROM players";
            $result_players = mysqli_query($link, $sql_players);
            $row_players = mysqli_fetch_assoc($result_players);
            $total_players = $row_players['total_players'];
            ?>

            <!-- Data Display Cards 
            <div class="card p-6 flex flex-col items-center justify-center text-center shadow-lg">
                <h2 class="text-4xl font-extrabold text-white"><?php echo $total_players; ?></h2>
                <p class="text-sm text-gray-400">Total Players</p>
            </div>
            
            <div class="card p-6 flex flex-col items-center justify-center text-center shadow-lg">
                <h2 class="text-4xl font-extrabold text-white">4</h2>
                <p class="text-sm text-gray-400">Matches this Month</p>
            </div>
            
            <div class="card p-6 flex flex-col items-center justify-center text-center shadow-lg">
                <h2 class="text-4xl font-extrabold text-white">6</h2>
                <p class="text-sm text-gray-400">Upcoming Events</p> 
            </div> -->
        </main>
    </div>
</body>
</html>
