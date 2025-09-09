<?php
// Include your database connection file
require_once 'config.php';

// Define variables and initialize with empty values
$training_date = $training_time = $location = "";
$date_err = $time_err = $location_err = "";
$success_message = "";
$player_list = [];
$attendees = [];
$not_attending = [];
$maybe = [];

// Get the training session ID from the URL
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $training_id = trim($_GET["id"]);
    
    // Prepare a select statement to get training details
    $sql = "SELECT training_date, training_time, location FROM training_sessions WHERE id = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = $training_id;
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $training_date = $row["training_date"];
                $training_time = $row["training_time"];
                $location = $row["location"];
            } else{
                // URL doesn't contain valid id. Redirect to training page
                header("location: training.php");
                exit();
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    }
    
    // Fetch all players and their RSVP status for the session
    $sql_players = "SELECT p.id, p.player_name, COALESCE(tr.status, 'none') AS status FROM players p LEFT JOIN training_rsvps tr ON p.id = tr.player_id AND tr.training_session_id = ?";
    if ($stmt_players = mysqli_prepare($link, $sql_players)) {
        mysqli_stmt_bind_param($stmt_players, "i", $training_id);
        if (mysqli_stmt_execute($stmt_players)) {
            $result_players = mysqli_stmt_get_result($stmt_players);
            while ($row_player = mysqli_fetch_assoc($result_players)) {
                $player_list[] = $row_player;
                if ($row_player['status'] === 'in') {
                    $attendees[] = $row_player['player_name'];
                } else if ($row_player['status'] === 'out') {
                    $not_attending[] = $row_player['player_name'];
                } else if ($row_player['status'] === 'maybe') {
                    $maybe[] = $row_player['player_name'];
                }
            }
        }
        mysqli_stmt_close($stmt_players);
    }

} else{
    // URL doesn't contain id parameter. Redirect to training page
    header("location: training.php");
    exit();
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // ... (existing form validation and update logic) ...
    $input_date = trim($_POST["training_date"]);
    $input_time = trim($_POST["training_time"]);
    $input_location = trim($_POST["location"]);

    if(empty($input_date)){
        $date_err = "Please enter a training date.";
    } else {
        $training_date = $input_date;
    }

    if(empty($input_time)){
        $time_err = "Please enter a training time.";
    } else {
        $training_time = $input_time;
    }

    if(empty($input_location)){
        $location_err = "Please enter a location.";
    } else {
        $location = $input_location;
    }

    if(empty($date_err) && empty($time_err) && empty($location_err)){
        $sql = "UPDATE training_sessions SET training_date=?, training_time=?, location=? WHERE id=?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sssi", $param_date, $param_time, $param_location, $param_id);
            $param_date = $training_date;
            $param_time = $training_time;
            $param_location = $location;
            $param_id = $training_id;
            
            if(mysqli_stmt_execute($stmt)){
                $success_message = "Training session updated successfully!";
            } else{
                $success_message = "Something went wrong. Please try again later.";
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
    <title>Edit Training</title>
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
                <h1 class="text-4xl md:text-5xl font-extrabold text-teal-400 mb-2">Edit Training Session</h1>
                <p class="text-lg text-gray-400">Update the details for this session.</p>
            </div>
            <div class="flex space-x-4">
                <a href="training.php" class="bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                    Back to Schedule
                </a>
            </div>
        </header>

        <main class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Edit Training Form -->
            <div class="lg:col-span-2 card p-8">
                <h2 class="text-2xl font-semibold mb-4 text-teal-400">Session Details</h2>
                <?php if (!empty($success_message)): ?>
                    <div class="bg-green-500 text-white font-bold p-4 rounded-lg mb-6">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $training_id; ?>" method="post">
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
                    <div class="flex items-center justify-between mt-4">
                        <input type="submit" class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 ease-in-out" value="Update Training">
                    </div>
                </form>
            </div>

            <!-- Attendees List -->
            <div class="lg:col-span-1 card p-8">
                <h2 class="text-2xl font-semibold mb-4 text-teal-400">Confirmed Attendees</h2>
                <ul class="space-y-2 text-white">
                    <?php if (count($attendees) > 0): ?>
                        <?php foreach ($attendees as $player): ?>
                            <li class="p-3 bg-gray-700 rounded-lg shadow-md flex justify-between items-center">
                                <span><?php echo htmlspecialchars($player); ?></span>
                                <span class="text-xs font-semibold text-green-400">Attending</span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="p-3 text-gray-400 text-center">No players have confirmed attendance yet.</li>
                    <?php endif; ?>
                </ul>

                <h2 class="text-2xl font-semibold mt-8 mb-4 text-teal-400">RSVP Status</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex flex-col items-center bg-green-900 p-4 rounded-lg">
                        <span class="text-3xl font-bold text-green-400"><?php echo count($attendees); ?></span>
                        <span class="text-sm text-gray-300">Attending</span>
                    </div>
                    <div class="flex flex-col items-center bg-red-900 p-4 rounded-lg">
                        <span class="text-3xl font-bold text-red-400"><?php echo count($not_attending); ?></span>
                        <span class="text-sm text-gray-300">Not Attending</span>
                    </div>
                    <div class="flex flex-col items-center bg-yellow-900 p-4 rounded-lg">
                        <span class="text-3xl font-bold text-yellow-400"><?php echo count($maybe); ?></span>
                        <span class="text-sm text-gray-300">Maybe</span>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
