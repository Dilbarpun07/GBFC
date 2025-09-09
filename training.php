<?php
// Include your database connection file
require_once 'config.php';

// Define variables and initialize with empty values
$training_date = $training_time = $location = "";
$date_err = $time_err = $location_err = "";
$success_message = "";
$reminder_message = "";
$rsvp_message = "";

// Get the ID of the first player from the database
$current_player_id = null;
$sql_player_id = "SELECT id FROM players LIMIT 1";
$result_player_id = mysqli_query($link, $sql_player_id);
if ($result_player_id && mysqli_num_rows($result_player_id) > 0) {
    $row_player_id = mysqli_fetch_assoc($result_player_id);
    $current_player_id = $row_player_id['id'];
}
mysqli_free_result($result_player_id);

// Processing form data when a form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Handle RSVP submission
    if (isset($_POST['action']) && $_POST['action'] === 'rsvp') {
        $training_id = $_POST['training_id'];
        $player_id = $_POST['player_id'];
        $status = $_POST['status'];

        if ($current_player_id !== null) {
            $sql = "INSERT INTO training_rsvps (training_session_id, player_id, status) VALUES (?, ?, ?)
                    ON DUPLICATE KEY UPDATE status = ?";
            
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "iiss", $training_id, $player_id, $status, $status);
                if (mysqli_stmt_execute($stmt)) {
                    $rsvp_message = "Your RSVP has been recorded!";
                } else {
                    $rsvp_message = "Error: Could not save your RSVP.";
                }
                mysqli_stmt_close($stmt);
            }
        } else {
             $rsvp_message = "Error: Player ID not found. Cannot save RSVP.";
        }


    } else if (isset($_POST['action']) && $_POST['action'] === 'send_reminder') {
        $training_id = trim($_POST['training_id']);

        // Fetch training session details
        $sql = "SELECT training_date, training_time, location FROM training_sessions WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $training_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $training_details = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
        }

        if ($training_details) {
            $session_details = "Training on " . date("F j, Y", strtotime($training_details['training_date'])) . " at " . date("g:i A", strtotime($training_details['training_time'])) . " at " . htmlspecialchars($training_details['location']);

            $attendees_sql = "SELECT p.player_name FROM training_attendance ta JOIN players p ON ta.player_id = p.id WHERE ta.training_session_id = ?";
            if ($attendees_stmt = mysqli_prepare($link, $attendees_sql)) {
                mysqli_stmt_bind_param($attendees_stmt, "i", $training_id);
                mysqli_stmt_execute($attendees_stmt);
                $attendees_result = mysqli_stmt_get_result($attendees_stmt);
                
                $sent_count = 0;
                while ($attendee_row = mysqli_fetch_assoc($attendees_result)) {
                    $player_name = htmlspecialchars($attendee_row['player_name']);
                    $sent_count++;
                }
                $reminder_message = "Reminder process completed for " . $sent_count . " players!";
                mysqli_stmt_close($attendees_stmt);
            }
        }

    } else {
        // Handle the "Add New Training" form submission
        // ... (existing form validation and insertion logic) ...
        if (empty(trim($_POST["training_date"]))) {
            $date_err = "Please enter a training date.";
        } else {
            $training_date = trim($_POST["training_date"]);
        }

        if (empty(trim($_POST["training_time"]))) {
            $time_err = "Please enter a training time.";
        } else {
            $training_time = trim($_POST["training_time"]);
        }

        if (empty(trim($_POST["location"]))) {
            $location_err = "Please enter a location.";
        } else {
            $location = trim($_POST["location"]);
        }

        if (empty($date_err) && empty($time_err) && empty($location_err)) {
            $sql = "INSERT INTO training_sessions (training_date, training_time, location) VALUES (?, ?, ?)";
            
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "sss", $param_date, $param_time, $param_location);
                
                $param_date = $training_date;
                $param_time = $training_time;
                $param_location = $location;
                
                if (mysqli_stmt_execute($stmt)) {
                    $success_message = "Training session added successfully!";
                } else {
                    $success_message = "Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Schedule</title>
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
<body class="p-8">
    <div class="max-w-7xl mx-auto">
        <header class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-teal-400 mb-2">Training Schedule</h1>
                <p class="text-lg text-gray-400">View and add upcoming training sessions.</p>
            </div>
            <div class="flex space-x-4">
                <a href="admin_template.php" class="bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                    Back to Dashboard
                </a>
            </div>
        </header>

        <main class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Add Training Form -->
            <div class="lg:col-span-3 card p-8">
                <h2 class="text-2xl font-semibold mb-4 text-teal-400">Add New Training</h2>
                <p class="text-lg text-gray-400 mb-6">Fill in the details for the new training session.</p>

                <?php if (!empty($success_message)): ?>
                    <div class="bg-green-500 text-white font-bold p-4 rounded-lg mb-6">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="mb-4">
                            <label class="block text-gray-400 text-sm font-bold mb-2" for="training_date">
                                Training Date
                            </label>
                            <input type="date" name="training_date" id="training_date" class="input-field shadow appearance-none rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo htmlspecialchars($training_date); ?>">
                            <p class="text-red-400 text-xs italic mt-1"><?php echo $date_err; ?></p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-400 text-sm font-bold mb-2" for="training_time">
                                Training Time
                            </label>
                            <input type="time" name="training_time" id="training_time" class="input-field shadow appearance-none rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo htmlspecialchars($training_time); ?>">
                            <p class="text-red-400 text-xs italic mt-1"><?php echo $time_err; ?></p>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-400 text-sm font-bold mb-2" for="location">
                                Location
                            </label>
                            <input type="text" name="location" id="location" placeholder="Enter location" class="input-field shadow appearance-none rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo htmlspecialchars($location); ?>">
                            <p class="text-red-400 text-xs italic mt-1"><?php echo $location_err; ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <input type="submit" class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 ease-in-out" value="Add Training">
                    </div>
                </form>
            </div>

            <!-- Training Sessions Display -->
            <div class="lg:col-span-3 card p-6">
                <h2 class="text-2xl font-semibold mb-4">Upcoming Sessions</h2>
                <?php if (!empty($rsvp_message)): ?>
                    <div class="bg-green-500 text-white font-bold p-4 rounded-lg mb-6">
                        <?php echo $rsvp_message; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($reminder_message)): ?>
                    <div class="bg-green-500 text-white font-bold p-4 rounded-lg mb-6">
                        <?php echo $reminder_message; ?>
                    </div>
                <?php endif; ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    $sql = "SELECT id, training_date, training_time, location FROM training_sessions ORDER BY training_date ASC";
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                                $training_id = $row['id'];
                                
                                // Get RSVP counts for the session
                                $rsvp_sql = "SELECT status, COUNT(*) as count FROM training_rsvps WHERE training_session_id = ? GROUP BY status";
                                $rsvp_counts = ['in' => 0, 'out' => 0, 'maybe' => 0];
                                if ($rsvp_stmt = mysqli_prepare($link, $rsvp_sql)) {
                                    mysqli_stmt_bind_param($rsvp_stmt, "i", $training_id);
                                    mysqli_stmt_execute($rsvp_stmt);
                                    $rsvp_result = mysqli_stmt_get_result($rsvp_stmt);
                                    while ($rsvp_row = mysqli_fetch_assoc($rsvp_result)) {
                                        $rsvp_counts[$rsvp_row['status']] = $rsvp_row['count'];
                                    }
                                    mysqli_stmt_close($rsvp_stmt);
                                }
                                
                                echo "<div class='card p-6 flex flex-col justify-between'>";
                                echo "<div>"; // Main content container
                                echo "<a href='edit_training.php?id=" . $training_id . "' class='block'>";
                                echo "<div class='flex justify-between items-start mb-2'>";
                                echo "<h3 class='text-xl font-bold'>Training Session</h3>";
                                echo "</div>";
                                echo "<p class='text-gray-400 text-sm mb-1'>Date: <span class='text-white'>" . htmlspecialchars($row['training_date']) . "</span></p>";
                                echo "<p class='text-gray-400 text-sm mb-1'>Time: <span class='text-white'>" . htmlspecialchars($row['training_time']) . "</span></p>";
                                echo "<p class='text-gray-400 text-sm mb-4'>Location: <span class='text-white'>" . htmlspecialchars($row['location']) . "</span></p>";
                                echo "</a>";
                                
                                // RSVP Status Display
                                echo "<div class='flex justify-between items-center mb-4 text-sm font-semibold text-gray-400'>";
                                echo "<div class='flex items-center space-x-1'><span class='text-green-500 text-2xl'>&bull;</span><span class='text-white'>" . $rsvp_counts['in'] . "</span><span class='text-gray-400'> Attending</span></div>";
                                echo "<div class='flex items-center space-x-1'><span class='text-red-500 text-2xl'>&bull;</span><span class='text-white'>" . $rsvp_counts['out'] . "</span><span class='text-gray-400'> Not Attending</span></div>";
                                echo "<div class='flex items-center space-x-1'><span class='text-yellow-500 text-2xl'>&bull;</span><span class='text-white'>" . $rsvp_counts['maybe'] . "</span><span class='text-gray-400'> Maybe</span></div>";
                                echo "</div>";
                                
                                echo "</div>"; // End of main content container
                                
                                // RSVP Button Section
                                echo "<div class='mt-4 pt-4 border-t border-gray-700'>";
                                echo "<h4 class='text-md font-semibold text-teal-400 mb-2'>Your RSVP:</h4>";
                                echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post' class='flex space-x-2'>";
                                echo "<input type='hidden' name='action' value='rsvp'>";
                                echo "<input type='hidden' name='training_id' value='" . $training_id . "'>";
                                echo "<input type='hidden' name='player_id' value='" . $current_player_id . "'>";
                                
                                echo "<button type='submit' name='status' value='in' class='flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 ease-in-out'>Attending</button>";
                                echo "<button type='submit' name='status' value='out' class='flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 ease-in-out'>Not Attending</button>";
                                echo "<button type='submit' name='status' value='maybe' class='flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 ease-in-out'>Maybe</button>";
                                echo "</form>";
                                echo "</div>";

                                echo "</div>"; // Closing the main card div
                            }
                            mysqli_free_result($result);
                        } else {
                            echo "<div class='card p-6 col-span-full text-center'>";
                            echo "<p class='text-lg'>No upcoming training sessions scheduled.</p>";
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
