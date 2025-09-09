<?php
// Start the session at the very top of the page
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: player_login.php");
    exit;
}

// Include your database connection file
require_once 'config.php';

// Fetch the current player's information
$player_id = $_SESSION["id"];
$player_name = $_SESSION["player_name"];

// Handle RSVP form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['training_id']) && isset($_POST['status'])) {
    $training_id = $_POST['training_id'];
    $status = $_POST['status'];
    
    // Check if an RSVP for this player and training session already exists
    $sql_check = "SELECT id FROM training_rsvps WHERE training_session_id = ? AND player_id = ?";
    if ($stmt_check = mysqli_prepare($link, $sql_check)) {
        mysqli_stmt_bind_param($stmt_check, "ii", $training_id, $player_id);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);
        
        if (mysqli_stmt_num_rows($stmt_check) == 1) {
            // Update the existing RSVP
            $sql_update = "UPDATE training_rsvps SET status = ? WHERE training_session_id = ? AND player_id = ?";
            if ($stmt_update = mysqli_prepare($link, $sql_update)) {
                mysqli_stmt_bind_param($stmt_update, "sii", $status, $training_id, $player_id);
                mysqli_stmt_execute($stmt_update);
                mysqli_stmt_close($stmt_update);
            }
        } else {
            // Insert a new RSVP
            $sql_insert = "INSERT INTO training_rsvps (training_session_id, player_id, status) VALUES (?, ?, ?)";
            if ($stmt_insert = mysqli_prepare($link, $sql_insert)) {
                mysqli_stmt_bind_param($stmt_insert, "iis", $training_id, $player_id, $status);
                mysqli_stmt_execute($stmt_insert);
                mysqli_stmt_close($stmt_insert);
            }
        }
        mysqli_stmt_close($stmt_check);
    }
}

// Fetch all upcoming training sessions
$sql = "SELECT id, training_date, training_time, location FROM training_sessions WHERE training_date >= CURDATE() ORDER BY training_date ASC";
$result = mysqli_query($link, $sql);
$upcoming_sessions = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch the player's current RSVP status for all sessions
$player_rsvps = [];
$sql_rsvps = "SELECT training_session_id, status FROM training_rsvps WHERE player_id = ?";
if ($stmt_rsvps = mysqli_prepare($link, $sql_rsvps)) {
    mysqli_stmt_bind_param($stmt_rsvps, "i", $player_id);
    mysqli_stmt_execute($stmt_rsvps);
    mysqli_stmt_bind_result($stmt_rsvps, $training_id, $status);
    while (mysqli_stmt_fetch($stmt_rsvps)) {
        $player_rsvps[$training_id] = $status;
    }
    mysqli_stmt_close($stmt_rsvps);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Dashboard</title>
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
    </style>
</head>
<body class="p-8">
    <div class="max-w-7xl mx-auto">
        <header class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-teal-400 mb-2">Welcome, <?php echo htmlspecialchars($player_name); ?>!</h1>
                <p class="text-lg text-gray-400">Manage your schedule and RSVP for upcoming events.</p>
            </div>
            <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                Logout
            </a>
        </header>

        <main class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (count($upcoming_sessions) > 0): ?>
                <?php foreach ($upcoming_sessions as $session): ?>
                    <div class="card p-6 flex flex-col justify-between shadow-lg">
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-2"><?php echo date("F j, Y", strtotime($session['training_date'])); ?></h2>
                            <p class="text-gray-400 text-sm mb-4">
                                <?php echo htmlspecialchars($session['location']); ?> • <?php echo date("h:i A", strtotime($session['training_time'])); ?>
                            </p>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-semibold text-teal-400 mb-2">Your RSVP</h3>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="flex flex-col space-y-2">
                                <input type="hidden" name="training_id" value="<?php echo $session['id']; ?>">
                                <button type="submit" name="status" value="in" class="w-full text-center py-2 rounded-lg font-semibold transition duration-300 ease-in-out
                                    <?php echo (isset($player_rsvps[$session['id']]) && $player_rsvps[$session['id']] == 'in') ? 'bg-green-600 text-white' : 'bg-gray-600 text-gray-200 hover:bg-green-500'; ?>">
                                    Attending
                                </button>
                                <button type="submit" name="status" value="out" class="w-full text-center py-2 rounded-lg font-semibold transition duration-300 ease-in-out
                                    <?php echo (isset($player_rsvps[$session['id']]) && $player_rsvps[$session['id']] == 'out') ? 'bg-red-600 text-white' : 'bg-gray-600 text-gray-200 hover:bg-red-500'; ?>">
                                    Not Attending
                                </button>
                                <button type="submit" name="status" value="maybe" class="w-full text-center py-2 rounded-lg font-semibold transition duration-300 ease-in-out
                                    <?php echo (isset($player_rsvps[$session['id']]) && $player_rsvps[$session['id']] == 'maybe') ? 'bg-yellow-600 text-white' : 'bg-gray-600 text-gray-200 hover:bg-yellow-500'; ?>">
                                    Maybe
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card p-6 col-span-3 text-center">
                    <p class="text-gray-400 text-lg">No upcoming training sessions scheduled.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
