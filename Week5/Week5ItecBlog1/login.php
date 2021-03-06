<?php
include 'includes/header.php';
$errors = [];

if(isset($_POST['login'])) {
  $username = $_POST['name'];
  $password = $_POST['password'];

  // #1 check that username exists, get row if it does
  $sql = "SELECT * FROM users WHERE user_name = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  if($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if(password_verify($password, $row['user_hash'])) {
      // if passwords match login
      $_SESSION['loggedin'] = true;
      $_SESSION['username'] = $row['user_name'];
      $_SESSION['user_id'] = $row['ID'];
      header("Location: index.php?login=true");
      
    } else {
      $errorMsg = "Password incorrect!";
      $errors['login_password'] = $errorMsg;
    }
  } else {
    $errorMsg = "User not found!";
    $errors['login_username'] = $errorMsg;
  }


}


if(isset($_POST['create'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password1 = $_POST['password1'];
  $password2 = $_POST['password2'];

  // #1 check username is not empty and doesn't exist in the db already
  if(strlen($username) < 5) {
    $errorMsg = "Username must be more than 5 characters!";
    $errors['create_username'] = $errorMsg;
  } else {
    $sql = "SELECT * FROM users WHERE user_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $results = $stmt->get_result();
    // check num_rows to see if username is taken
    // throw error if num_rows == 1
    if($results->num_rows == 1) {
      $errorMsg = "This username is taken, please use another!";
      $errors['create_username'] = $errorMsg;
    }
  }
  // #2 check and validate user email
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errorMsg = "Email is invalid!";
    $errors['create_email'] = $errorMsg;
  }

  // #3 check that passwords match and are 5+ chars in length
  if(strlen($password1) < 5 || $password1 != $password2) {
    $errorMsg = "Password is too short or doesn't match!";
    $errors['create_password'] = $errorMsg;
  }
  // Check $errors[] array, if it is empty then create new user
  if(empty($errors)) {
    //create user-> hash the password and insert user into db
    $hash = password_hash($password1, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (user_name, user_email, user_hash) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hash);
    $stmt->execute();
    if($stmt->affected_rows == 1) {
      $_SESSION['loggedin'] = true;
      $_SESSION['username'] = $username;
      $_SESSION['user_id'] = $stmt->insert_id;
      header("Location: index.php?login=true");
    } else {
      // redirect or output an error msg
    }

  }
}
 ?>

 <div class="container mt-3">
    <?php if (isset($errorMsg)): ?>
      <div class="alert alert-danger" role="alert">
        <?php echo $errorMsg; ?>
      </div>
    <?php endif; ?>
   <div class="row">
     <div class="col-md-6">
       <h3>Create a new account</h3>
       <hr>
       <form class="" action="login.php" method="post">
         <label for="username">Username</label>
         <input type="text" name="username" class="form-control" placeholder="Input your username..." value="<?php if (isset($username)) {
           echo htmlspecialchars($username);}?>">
         <p class="error"><?php if(isset($errors['create_username'])) {echo $errors['create_username'];} ?></p>

         <label for="email">Email</label>
         <input type="email" name="email" class="form-control" placeholder="Input your username..." value="<?php if (isset($email)) { echo htmlspecialchars($email);} ?>">
         <p class="error"><?php if(isset($errors['create_email'])) {echo $errors['create_email'];} ?></p>

         <label for="password1">Password</label>
         <input type="password" name="password1" class="form-control" placeholder="Input your username..."value="">
         <p class="error"></p>
         <label for="password2">Confirm Password</label>
         <input type="password" name="password2" class="form-control" placeholder="Input your username..."value="">
         <p class="error"></p>
         <button type="submit" name="create" class="btn btn-outline-success">Create Account</button>
       </form>
     </div>
     <div class="col-md-6">
       <h3>Login</h3>
       <hr>
       <form class="" action="login.php" method="post">
         <label for="username">Username</label>
         <input type="text" name="name" class="form-control" placeholder="Input your username..." value="<?php if (isset($name)) { echo htmlspecialchars($name);} ?>">
         <p class="error"><?php if(isset($errors['login_username'])) {echo $errors['login_username'];} ?></p>
         <label for="password">Password</label>
         <input type="password" name="password" class="form-control" placeholder="Input your username..." value="">
         <p class="error"><?php if(isset($errors['login_password'])) {echo $errors['login_password'];} ?></p>
         <button type="submit" name="login" class="btn btn-outline-primary">Login</button>
       </form>
     </div>
   </div>
 </div>

 <?php
 $adminpass = "itec2021";
 echo $adminpass . "<br>";
 echo password_hash($adminpass, PASSWORD_DEFAULT);
 include 'includes/footer.php';
  ?>
