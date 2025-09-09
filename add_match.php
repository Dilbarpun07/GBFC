<?php
// Include your database connection file
require_once 'config.php';

// Define variables and initialize with empty values
$opponent = $match_date = $match_time = $location = "";
$opponent_err = $match_date_err = $match_time_err = $location_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate opponent
    if (empty(trim($_POST["opponent"]))) {
        $opponent_err = "Please enter an opponent name.";
    } else {
        $opponent = trim($_POST["opponent"]);
    }

    // Validate date
    if (empty(trim($_POST["match_date"]))) {
        $match_date_err = "Please enter a date.";
    } else {
        $match_date = trim($_POST["match_date"]);
    }

    // Validate time
    if (empty(trim($_POST["match_time"]))) {
        $match_time_err = "Please enter a time.";
    } else {
        $match_time = trim($_POST["match_time"]);
    }

    // Validate location
    if (empty(trim($_POST["location"]))) {
        $location_err = "Please enter a location.";
    } else {
        $location = trim($_POST["location"]);
    }

    // Check input errors before inserting in database
    if (empty($opponent_err) && empty($match_date_err) && empty($match_time_err) && empty($location_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO matches (opponent, match_date, match_time, location) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_opponent, $param_date, $param_time, $param_location);

            // Set parameters
            $param_opponent = $opponent;
            $param_date = $match_date;
            $param_time = $match_time;
            $param_location = $location;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to match schedule page
                header("location: match_schedule.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
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
    <title>Add New Match/Event</title>
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
            transition: all 0.3s ease;
        }
        .input-field:focus {
            outline: none;
            border-color: #4fd1c5;
            box-shadow: 0 0 0 3px rgba(79, 209, 197, 0.5);
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
    <div class="max-w-xl mx-auto">
        <header class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-teal-400 mb-2">Add New Match/Event</h1>
                <p class="text-lg text-gray-400">Enter event details below.</p>
            </div>
            <a href="match_schedule.php" class="bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                Back to Schedule
            </a>
        </header>

        <main class="card p-8">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-6">
                    <label for="opponent" class="block text-gray-300 text-sm font-semibold mb-2">Opponent</label>
                    <input type="text" name="opponent" id="opponent" class="w-full px-4 py-2 rounded-lg input-field" value="<?php echo htmlspecialchars($opponent); ?>" required>
                    <span class="text-red-500 text-sm mt-1 block"><?php echo $opponent_err; ?></span>
                </div>
                <div class="mb-6">
                    <label for="match_date" class="block text-gray-300 text-sm font-semibold mb-2">Date</label>
                    <input type="date" name="match_date" id="match_date" class="w-full px-4 py-2 rounded-lg input-field" value="<?php echo htmlspecialchars($match_date); ?>" required>
                    <span class="text-red-500 text-sm mt-1 block"><?php echo $match_date_err; ?></span>
                </div>
                <div class="mb-6">
                    <label for="match_time" class="block text-gray-300 text-sm font-semibold mb-2">Time</label>
                    <input type="time" name="match_time" id="match_time" class="w-full px-4 py-2 rounded-lg input-field" value="<?php echo htmlspecialchars($match_time); ?>" required>
                    <span class="text-red-500 text-sm mt-1 block"><?php echo $match_time_err; ?></span>
                </div>
                <div class="mb-6">
                    <label for="location" class="block text-gray-300 text-sm font-semibold mb-2">Location</label>
                    <input type="text" name="location" id="location" class="w-full px-4 py-2 rounded-lg input-field" value="<?php echo htmlspecialchars($location); ?>" required>
                    <span class="text-red-500 text-sm mt-1 block"><?php echo $location_err; ?></span>
                </div>
                <div class="flex justify-end">
                    <input type="submit" class="bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md cursor-pointer transition duration-300 ease-in-out" value="Add Event">
                </div>
            </form>
        </main>
    </div>
</body>
</html>

