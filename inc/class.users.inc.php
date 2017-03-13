<?php
class ColoredListsUsers
{
      /* Testing for parenthesis balance...

      /**
       * The database object
       *
       * @var object
       */
  private $_db;
      /**
       * Checks for a database object and creates one if none is found
       *
       * @param object $db
       * @return void
       */

  /* parenthesis check */
  public function __construct($db=NULL)
  {
    if(!is_object($db)) {
      $dsn = "mysql:host=".DB_HOST.";dbname".DB_NAME;
      $this->_db = $db;
    }
    else {
        $this->_db = $db;
    }
  }
  /*
  * End of Checks
  */
      /**
       * Checks and inserts a new account email into the database
       *
       * @return string    a message indicating the action status
       */

  /* parenthesis check */
  //public $stmt;
  public function createAccount()
  {
    echo "inside createAccount function";
    $u = trim($_POST['username']);
    $v = sha1(time());
    $sql = "SELECT COUNT(Username) AS theCount
    FROM users
    WHERE Username=:email";
    if($stmt = $this->_db->prepare($sql)) {
      $stmt->bindParam(":email",$u,PDO::PARAM_STR);
      $stmt->execute();
      $row=$stmt->fetch();
              if($row['theCount']!=0) {
            return "<h2> ERROR </h2>"
                  ."<p> Sorry, that email is already in use. "
                  ."Please try again </p>";
            }
            if($this->sendVerificationEmail($u,$v))
            return "Mail sent";
            if(!$this->sendVerificationEmail($u,$v)) {
                    echo "<h2> ERROR </h2>"
                    ."<p> There was an error sending your "
                    ."verification mail. Please "
                    ."<a href=\"mailto:help@coloredlists.com\">Contact "
                    ."us</a> for support. We apologize for the "
                    ."inconvenience </p>";
            }
    $stmt->closeCursor();
    }
    // Do something with the password yaar. add it someplace.
    echo "inserting sql start";
    $pass = $_POST['password'];
    $sql2 = "INSERT INTO users(Username, ver_code, Password)
    VALUES(:email, :ver, :pass)";
    if($stmt = $this->_db->prepare($sql2)) {
      echo "Inside the if clause";
            $stmt->bindParam(":email", $u, PDO::PARAM_STR);
            $stmt->bindParam(":ver", $v, PDO::PARAM_STR);
            $stmt->bindParam(":pass", $pass, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
            $userID = $this->_db->lastInsertId();
            $url = dechex($userID);
              // If the UserID was successfully retrieved, create a default list
            $sql3 = "INSERT INTO lists (UserID, ListURL)
            VALUES ($userID, $url)";
            if(!$this->_db->query($sql3)) {
                    return "<h2> Error </h2>"
                    . "<p> Your account was created, but "
                    . "creating your first list failed. </p>";
            }
            else {
                    return "<h2> Success! </h2>"
                    . "<p> Your account was successfully "
                    . "created with the username <strong>$u</strong>."
                    . " Check your email!";
            }
          }
          else {
                    return "<h2> Error </h2><p> Couldn't insert the "
                    . "user information into the database. </p>";
          }

  }
  /*
  * End of Checks
  */
      /**
       * Sends an email to a user with a link to verify their new account
       *
       * @param string $email    The user's email address
       * @param string $ver    The random verification code for the user
       * @return boolean        TRUE on successful send and FALSE on failure
       */

  /* parenthesis check */
  private function sendVerificationEmail($email, $ver)
  {
        $e = sha1($email); // For verification purposes
        $to = trim($email);
        $subject = "[Colored Lists] Please Verify Your Account";
    //$headers = <<< _MESSAGE
    //From: Colored Lists <maharshig.parekh2014@gmail.com>
    //Content-Type: text/plain;
//_MESSAGE;
    $msg = <<< _EMAIL
    You have a new account at Colored Lists!
    To get started, please activate your account, then choose a
    password by following the link below.
    Your Username: $email
    Activate your account: http://coloredlists.com/accountverify.php?v=$ver&e=$e
    If you have any questions, please contact help@coloredlists.com.
    --
    Thanks!
    Team
    www.ColoredLists.com
_EMAIL;
$headers =  'MIME-Version: 1.0' . "\r\n";
$headers .= 'From: CL <maharshiwikihow@gmail.com>' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";


        return mail($to, $subject, $msg, $headers);
  }
  /*
  * End of Checks
  */
      /**
       * Checks credentials and verifies a user account
       *
       * @return array    an array containing a status code and status message
       */

  /* parenthesis check */
  public function verifyAccount()
  {
    $sql = "SELECT Username
                  FROM users
                  WHERE ver_code=:ver
                  AND SHA1(Username)=:user
                  AND verified=0";
  if($stmt = $this->_db->prepare($sql))
  {
    $stmt->bindParam(':ver',$_GET['v'],PDO::PARAM_STR);
    $stmt->bindParam(':user',$_GET['e'], PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
            if(isset($row['Username']))
            {
              // Logs the user in if verification is successful
              $_SESSION['Username'] = $row['Username'];
              $_SESSION['LoggedIn'] = 1;
            }
            else
            {
              return array(4, "<h2>Verification Error</h2>n"
              . "<p>This account has already been verified. "
              . "Did you <a href=\"/password.php\"> forget "
              . "your password? </a>");
            }
    $stmt->closeCursor();
    // No error message is required if verification is successful
    return array(0, NULL);
  }
  else
  {
    return array(2, "<h2>Error</h2>n<p>Database error.</p>");
  }
  }
  /*
  * End of Checks
  */
      /**
       * Changes the user's password
       *
       * @return boolean    TRUE on success and FALSE on failure
       */
  /* parenthesis check */
  public function updatePassword()
  {
    if(!(isset($_POST['p']) && isset($_POST['r']) && $_POST['p']==$_POST['r']))
  {
    return false;
  }
  else
  {
    $sql = "UPDATE users
                    SET Password=MD5(:pass), verified=1
                    WHERE ver_code=:ver
                    LIMIT 1";
    try
    {$stmt = $this->_db->prepare($sql);
      $stmt->bindParam(":pass", $_POST['p'], PDO::PARAM_STR);
      $stmt->bindParam(":ver", $_POST['v'], PDO::PARAM_STR);
      $stmt->execute();
      $stmt->closeCursor();
      return TRUE;
    }
    catch(PDOException $e)
    {
      return FALSE;
    }
  }
}
  /*
  * End of Checks
  */
      /**
       * Checks credentials and logs in the user
       *
       * @return boolean    TRUE on success and FALSE on failure
       */

  /*parenthesis check */
  public function accountLogin()
  {

/*
    $sql = "SELECT * FROM `users`";
    $stmt = $this->_db>execute($sql);
    echo $stmt;
*/
$db2 = new PDO('mysql:host=localhost;dbname=cl_db;charset=utf8mb4', 'root', '');
$user1 = $_POST['username'];
$pass1 = $_POST['password'];
/*
    $sql = "SELECT Username
                  FROM users WHERE Username='{$_POST['username']}' AND Password='{$_POST['password']}'";
*/
  $sql = "SELECT Username FROM users WHERE Username=? AND Password=?";
  try
  {
    $stmt = $db2->prepare($sql);

    echo " ".$user1. " ".$pass1;

    $stmt->bindParam(1, $user1, PDO::PARAM_STR);
    $stmt->bindParam(2, $pass1, PDO::PARAM_STR);
    $stmt->execute();
    /*
    $count=0;
    //$stmt->execute(array(':user' => $user1, ':pass' => $pass1));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $count = $count + 1;
    }
    */
    if(!$stmt->rowCount())
    {
    echo " - not recognised - ";
    echo $count;
    return FALSE;
    }
    else
  {
    $_SESSION['Username'] = htmlentities($_POST['username'], ENT_QUOTES);
    $_SESSION['LoggedIn'] = 1;
    return TRUE;
  }
  }
  catch(PDOException $e)
  {
    echo "some error";
    return FALSE;
  }
}
  /*
  * End of Checks
  */
      /**
       * Retrieves the ID and verification code for a user
       *
       * @return mixed    an array of info or FALSE on failure
       */

  /* parenthesis check */
  public function retrieveAccountInfo()
  {
    $sql = "SELECT UserID, ver_code
                  FROM users
                  WHERE Username=:user";
  try
  {
    $stmt = $this->_db->prepare($sql);
    $stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    $stmt->closeCursor();
    return array($row['UserID'], $row['ver_code']);
  }
  catch(PDOException $e)
  {
    return FALSE;
  }
}
  /*
  * End of Checks
  */
      /**
       * Changes a user's email address
       *
       * @return boolean    TRUE on success and FALSE on failure
       */

  /* parenthesis check */
  public function updateEmail()
  {
    $sql = "UPDATE users
                  SET Username=:email
                  WHERE UserID=:user
                  LIMIT 1";
  try
  {
    $stmt = $this->_db->prepare($sql);
    $stmt->bindParam(':email', $_POST['username'], PDO::PARAM_STR);
    $stmt->bindParam(':user', $_POST['userid'], PDO::PARAM_INT);
    $stmt->execute();
    $stmt->closeCursor();
    // Updates the session variable
    $_SESSION['Username'] = htmlentities($_POST['username'], ENT_QUOTES);
    return TRUE;
  }
  catch(PDOException $e)
  {
    return FALSE;
  }
}
  /*
  * End of updateEmail-Check

  */
} ?>
