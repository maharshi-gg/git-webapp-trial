<?php
    include_once "header.php";
?>
<br><br><br>
<?php
    include_once "base.php";
    $title = "Home";

    if(empty($_SESSION['LoggedIn']) && empty($_SESSION['Username'])):
      echo "Empty Session";
?>

        <h2>Your list awaits...</h2>
        <form method="post" action="login.php" name="loginform" id="loginform">
            <div>
                <input type="text" name="username" id="username" />
                <label for="username">Email</label>
                <br /><br />
                <input type="password" name="password" id="password" />
                <label for="password">Password</label>
                <br /><br />
                <input type="submit" name="login" id="login" value="Login" class="button" />
            </div>
        </form><br /><br />
        <p><a href="password.php">Did you forget your password?</a></p>
<?php
    endif; //end the previous if condition

    if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username'])):
      echo "HEYA";
?>
        <p>You are currently <strong>logged in.</strong></p>
        <p><a href="logout.php">Log out</a></p>
<?php
    elseif(!empty($_POST['username']) && !empty($_POST['password'])):
      include_once 'inc/class.users.inc.php';
      $users = new ColoredListsUsers($db);
      if($users->accountLogin()===TRUE):
        echo "<meta http-equiv='refresh' content='0;/'>";
        echo "HELLO";
      else:
?>
 
        <h2>Login Failed&mdash;Try Again?</h2>
        <form method="post" action="login.php" name="loginform" id="loginform">
            <div>
                <input type="text" name="username" id="username" />
                <label for="username">Email</label>
                <br /><br />
                <input type="password" name="password" id="password" />
                <label for="password">Password</label>
                <br /><br />
                <input type="submit" name="login" id="login" value="Login" class="button" />
            </div>
        </form>
        <p><a href="password.php">Did you forget your password?</a></p>
<?php
        endif;
    else:
?>
 
<?php
    endif;
  endif;
?>
