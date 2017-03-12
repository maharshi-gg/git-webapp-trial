<?php
    //this is the base php file
 ?>
<?php
 // Set the error reporting level
error_reporting(E_ALL);
ini_set("display_errors", 1);
// Start a PHP session
session_start();
// Include site constants
include_once "inc\constant.inc.php";
// Create a database object
try {
  $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
  $db = new PDO($dsn, DB_USER, DB_PASS);
}
catch (PDOException $e) {
  echo 'Connection failed: ' . $e->getMessage();
  exit;
}
?>
<html>
<head>
  <title>Dummy Title</title>
</head>
<body>
  <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
     Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
</body>
</html>
