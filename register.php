<?php     

// connect to database
require_once 'connect.php';

$registered = false;
$alreadyRegistered = false;
$passwordDoesntMatch = false;

// check if user have inputed email & password
if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['passwordconfirmation'])) {
  if($_POST['password'] !== $_POST['passwordconfirmation']) {
    $passwordDoesntMatch = true;
  } else {
    $query = "SELECT * FROM users WHERE email=? AND password=? LIMIT 1";
    
    // Initializes a statement and returns an object for use with mysqli_stmt_prepare
    $stmt = mysqli_stmt_init($link);
    
    // Prepare an SQL statement for execution
    if(!mysqli_stmt_prepare($stmt, $query)) {
      echo "Failed to prepare statement" . PHP_EOL;
    } else {
      // Binds variables to a prepared statement as parameters
      // using this function will prevent SQL injection
      // http://php.net/manual/en/mysqli-stmt.bind-param.php
      // "s" corresponding variable has type string
      mysqli_stmt_bind_param($stmt, "ss", $_POST['email'], $_POST['password']);
  
      // Executes a prepared Query
      // http://php.net/manual/en/mysqli-stmt.execute.php
      mysqli_stmt_execute($stmt);
      
      // Gets a result set from a prepared statement
      // http://php.net/manual/en/mysqli-stmt.get-result.php
      $result = mysqli_stmt_get_result($stmt);
      
      // Gets the number of rows in a result
      // http://php.net/manual/en/mysqli-result.num-rows.php
      $matchUsersCount = mysqli_num_rows($result);
  
      $isEmailPasswordCorrect = $matchUsersCount > 0 ? true : false;
      if($isEmailPasswordCorrect) {
        // Fetch a result row as an associative
        // http://php.net/manual/en/mysqli-result.fetch-array.php
        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $alreadyRegistered = true;
      } else {
        $query = "INSERT INTO users (name, email, password) VALUES (?,?,?)";
        // Initializes a statement and returns an object for use with mysqli_stmt_prepare
        $stmt = mysqli_stmt_init($link);
        
        // Prepare an SQL statement for execution
        if(!mysqli_stmt_prepare($stmt, $query)) {
          echo "Failed to prepare statement" . PHP_EOL;
        } else {
          // Binds variables to a prepared statement as parameters
          // using this function will prevent SQL injection
          // http://php.net/manual/en/mysqli-stmt.bind-param.php
          // "s" corresponding variable has type string
          mysqli_stmt_bind_param($stmt, "sss", $_POST['name'], $_POST['email'], $_POST['password']);
      
          // Executes a prepared Query
          // http://php.net/manual/en/mysqli-stmt.execute.php
          mysqli_stmt_execute($stmt);
          
          if(mysqli_insert_id($link) > 0){
            $registered = true;
          } else {
            echo "Failed to insert row" . PHP_EOL;
          }
        }
      }
    }
    // Closes a prepared statement
    // http://php.net/manual/en/mysqli-stmt.close.php
    mysqli_stmt_close($stmt);
  }
}
// Closes a previously opened database connection
// http://php.net/manual/en/mysqli.close.php
mysqli_close($link);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <form class="form-signin" id="login" role="form" method="POST" action="register.php">
    <?php if ($registered): ?>
      <h4>Registered Successfull</h4>
    <?php elseif($alreadyRegistered): ?>
      <h4>Registered UnSucessfull (already registered)</h4>
    <?php elseif($passwordDoesntMatch): ?>
      <h4>Passwords does not match</h4>
    <?php else: ?>
      name: <input type="text" name="name"> 
      <br />
      email: <input type="email" name="email"> 
      <br />
      password: <input type="password" name="password">
      <br />
      password confirm: <input type="password" name="passwordconfirmation">
      <br />
      <button type="submit">submit</button>
    <?php endif; ?>
  </form>
</body>
</html>
