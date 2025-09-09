<?php
// Include your database connection file
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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
        .input-field {
            background-color: #2c3748;
            border: 1px solid #4a5568;
            color: white;
        }
    </style>
</head>
<body class="p-8 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="card p-8 rounded-lg shadow-xl text-center">
            <h1 class="text-3xl md:text-4xl font-extrabold text-teal-400 mb-2">Admin Login</h1>
            <p class="text-gray-400 mb-6">Please log in to manage the club.</p>

            <form action="admin_template.php" method="POST" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-left mb-1">Username</label>
                    <input type="text" id="username" name="username" required class="input-field w-full p-3 rounded-md focus:outline-none focus:ring focus:ring-teal-500">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-left mb-1">Password</label>
                    <input type="password" id="password" name="password" required class="input-field w-full p-3 rounded-md focus:outline-none focus:ring focus:ring-teal-500">
                </div>
                <button type="submit" class="w-full bg-teal-500 hover:bg-teal-600 text-white font-semibold py-3 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                    Log In
                </button>
            </form>
            <a href="index.php" class="inline-block mt-4 text-sm text-gray-400 hover:text-teal-400 transition duration-300 ease-in-out">
                Back to Homepage
            </a>
        </div>
    </div>
</body>
</html>
