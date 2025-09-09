<?php
// Include your database connection file
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Management</title>
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
                <h1 class="text-4xl md:text-5xl font-extrabold text-teal-400 mb-2">Player Management</h1>
                <p class="text-lg text-gray-400">Manage all players in your club.</p>
            </div>
            <div class="flex space-x-4">
                <a href="admin_template.php" class="bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                    Back to Dashboard
                </a>
                <a href="add-player.php" class="bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                    Add New Player
                </a>
            </div>
        </header>

        <main class="grid grid-cols-1 gap-8">
            <!-- Player List Table -->
            <div class="lg:col-span-3 card p-6 overflow-x-auto">
                <h2 class="text-2xl font-semibold mb-4">Current Squad Roster</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left table-auto">
                        <thead>
                            <tr class="bg-gray-800 text-gray-300 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">ID</th>
                                <th class="py-3 px-6 text-left">Name</th>
                                <th class="py-3 px-6 text-left">Position</th>
                                <th class="py-3 px-6 text-left">Jersey Number</th>
                                <th class="py-3 px-6 text-center">Manage</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-200 text-sm font-light">
                            <?php
                            $sql = "SELECT id, player_name, position, jersey_number FROM players ORDER BY player_name ASC";
                            if ($result = mysqli_query($link, $sql)) {
                                if (mysqli_num_rows($result) > 0) {
                                    $row_number = 1; // Initialize a counter for the row number
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<tr class='border-b border-gray-700 hover:bg-gray-700'>";
                                        echo "<td class='py-3 px-6 text-left whitespace-nowrap'>" . $row_number++ . "</td>"; // Display the counter
                                        echo "<td class='py-3 px-6 text-left'>" . $row['player_name'] . "</td>";
                                        echo "<td class='py-3 px-6 text-left'>" . $row['position'] . "</td>";
                                        echo "<td class='py-3 px-6 text-left'>" . $row['jersey_number'] . "</td>";
                                        echo "<td class='py-3 px-6 text-center whitespace-nowrap'>";
                                        echo "<a href='edit-player.php?id=" . $row['id'] . "' class='text-teal-400 hover:text-teal-500 mr-4'>Edit</a>";
                                        echo "<a href='delete-player.php?id=" . $row['id'] . "' class='text-red-500 hover:text-red-600'>Delete</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    mysqli_free_result($result);
                                } else {
                                    echo "<tr><td colspan='5' class='py-3 px-6 text-center'>No players found.</td></tr>";
                                }
                            } else {
                                echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                            }
                            // Close connection
                            mysqli_close($link);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
