# GBFC
GBFC website IN PHP...

# GBFC - Football Club Management System

A comprehensive PHP-based web application for managing a football club (GBFC), featuring player management, match scheduling, training sessions, and administrative controls.

## 🎯 Features

- **Player Management**: Add, edit, and delete player profiles with detailed information
- **Match Scheduling**: Schedule and manage football matches with comprehensive details
- **Training Management**: Organize and track training sessions for the team
- **Dual Login System**: Separate login interfaces for administrators and players
- **Admin Dashboard**: Centralized control panel for managing all aspects of the club
- **Player Dashboard**: Personal dashboard for players to view schedules and information

## 🛠️ Technologies Used

- **Backend**: PHP
- **Database**: MySQL (assumed based on typical PHP web applications)
- **Frontend**: HTML, CSS, JavaScript
- **Server**: Apache (assumed based on typical PHP deployment)

## 📋 Prerequisites

- PHP 7.0 or higher
- MySQL Database
- Apache or Nginx web server
- Composer (if using additional PHP packages)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Dilbarpun07/GBFC.git
   cd GBFC
   ```

2. **Set up the database**
   - Create a MySQL database for the application
   - Import the database schema from `database.sql` (if available) or create tables based on the PHP code structure

3. **Configure the application**
   - Update the `config.php` file with your database credentials:
     ```php
     $servername = "localhost";
     $username = "your_db_username";
     $password = "your_db_password";
     $dbname = "your_database_name";
     ```

4. **Deploy to web server**
   - Place the files in your web server's document root (e.g., `/var/www/html/` for Apache)
   - Ensure proper file permissions are set

5. **Access the application**
   - Open your browser and navigate to `http://your-domain.com/GBFC/`

## 🔐 Usage

### For Administrators:
1. Navigate to `adminlogin.php` to access the admin panel
2. Log in with your admin credentials
3. Use the admin dashboard to manage players, matches, and training sessions

### For Players:
1. Navigate to `player_login.php` to access the player portal
2. Log in with your player credentials
3. Access your personal dashboard to view schedules and personal information

## 📁 File Structure

```
GBFC/
├── index.php              # Main entry point
├── config.php             # Database configuration
├── admin_template.php     # Admin interface template
├── add-player.php         # Add new players
├── edit-player.php        # Edit player information
├── delete-player.php      # Delete players
├── add_match.php          # Add match information
├── view_match.php         # View match details
├── match_schedule.php     # Match scheduling
├── adminlogin.php         # Admin login page
├── player_login.php       # Player login page
├── training.php           # Training management
├── edit_training.php      # Edit training sessions
├── logout.php             # Player logout
├── logout_admin.php       # Admin logout
├── player_dashboard.php   # Player dashboard
└── README.md              # Project documentation
```

## 🛡️ Security Features

- Session management for user authentication
- Input validation and sanitization
- Separate access levels for administrators and players

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 🐛 Bug Reports and Feature Requests

Please use the GitHub issue tracker to report bugs or request new features. Include detailed information about the issue and steps to reproduce it.

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 👨‍💻 Author

**Dilbarpun07**

## 🆘 Support

If you encounter any issues or have questions, feel free to open an issue in the GitHub repository.
