<?php
// Include your database connection file
require_once 'config.php';

$match_details = null;

if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $id = trim($_GET['id']);
    
    // Prepare a select statement
    $sql = "SELECT id, opponent, match_date, match_time, location FROM matches WHERE id = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = $id;
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) == 1) {
                $match_details = mysqli_fetch_array($result, MYSQLI_ASSOC);
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Details</title>
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
<body class="p-8">
    <div class="max-w-3xl mx-auto">
        <header class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-teal-400 mb-2">Match Details</h1>
                <p class="text-lg text-gray-400">Detailed information about the upcoming match.</p>
            </div>
            <a href="match_schedule.php" class="bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                Back to Schedule
            </a>
        </header>

        <main class="card p-8">
            <?php if ($match_details): ?>
                <h2 class="text-3xl font-bold mb-4">GBFC vs. <?php echo htmlspecialchars($match_details['opponent']); ?></h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-lg">
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-400">Date:</span>
                        <span class="text-white font-semibold"><?php echo htmlspecialchars($match_details['match_date']); ?></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-400">Time:</span>
                        <span class="text-white font-semibold"><?php echo htmlspecialchars($match_details['match_time']); ?></span>
                    </div>
                    <div class="col-span-1 md:col-span-2 flex items-center space-x-2">
                        <span class="text-gray-400">Location:</span>
                        <span class="text-white font-semibold"><?php echo htmlspecialchars($match_details['location']); ?></span>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center">
                    <p class="text-lg text-red-400">No match found with that ID.</p>
                    <p class="mt-4 text-gray-400">Please return to the <a href="match_schedule.php" class="text-teal-400 hover:underline">Match Schedule</a> page.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>

