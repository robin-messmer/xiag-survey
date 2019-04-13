# xiag-survey

Installation instructions: 

1. Download and install XAMPP: https://www.apachefriends.org/download.html
2. Download this repository and move folder "survey" into folder "htdocs"
3. Navigate to localhost/phpmyadmin and execute the SQL script "createDB.sql" residing in "survey" folder
   In case you use an existing Database setup, make sure the DB-Login-String in file "DBHandler.php" matches your configuration.
4. Finish, navigate to localhost/survey/ to create a new poll


System requirements:
  - The latest XAMPP
  - Windows 7 and higher, Linux, Mac OSX
  - Web Browser


Used technologies in this application: 
  - HTML, CSS, JS, jQuery, Vue.js
  - PHP, MariaDB
  - Pusher
