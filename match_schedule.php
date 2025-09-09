<?php
// Include your database connection file
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Schedule</title>
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
<body class="p-8">
    <div class="max-w-7xl mx-auto">
        <header class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-teal-400 mb-2">Match Schedule</h1>
                <p class="text-lg text-gray-400">View upcoming matches.</p>
            </div>
            <div class="flex space-x-4">
                <a href="add_match.php" class="bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                    Add New Event
                </a>
                <a href="admin_template.php" class="bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                    Back to Dashboard
                </a>
            </div>
        </header>

        <main class="grid grid-cols-1 gap-8">
            <!-- Match Schedule Display -->
            <div class="lg:col-span-3 card p-6">
                <h2 class="text-2xl font-semibold mb-4">Upcoming Matches</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    // Note: You need a 'matches' table in your database for this to work.
                    $sql = "SELECT id, opponent, match_date, match_time, location FROM matches ORDER BY match_date ASC";
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            $row_number = 1;
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<a href='view_match.php?id=" . $row['id'] . "' class='block card p-6 hover:bg-gray-700 transition duration-300 ease-in-out'>";
                                echo "<div class='flex justify-between items-start mb-2'>";
                                echo "<h3 class='text-xl font-bold'>GBFC vs. " . htmlspecialchars($row['opponent']) . "</h3>";
                                echo "<span class='text-gray-400 text-sm'>#" . $row_number++ . "</span>";
                                echo "</div>";
                                echo "<p class='text-gray-400 text-sm mb-1'>Date: <span class='text-white'>" . htmlspecialchars($row['match_date']) . "</span></p>";
                                echo "<p class='text-gray-400 text-sm mb-1'>Time: <span class='text-white'>" . htmlspecialchars($row['match_time']) . "</span></p>";
                                echo "<p class='text-gray-400 text-sm'>Location: <span class='text-white'>" . htmlspecialchars($row['location']) . "</span></p>";
                                echo "</a>";
                            }
                            mysqli_free_result($result);
                        } else {
                            echo "<div class='card p-6 col-span-full text-center'>";
                            echo "<p class='text-lg'>No upcoming matches scheduled.</p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<div class='card p-6 col-span-full text-center'>";
                        echo "ERROR: Could not execute $sql. " . mysqli_error($link);
                        echo "</div>";
                    }
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
