<?php include 'includes/header.php';

if(isset($_POST['submit'])) {
  $title = $_POST['title'];
  $body = $_POST['body'];
  $user_id = $_SESSION['user_id'];

  if($title != '' && $body != '') {
    $sql = "INSERT INTO posts (post_title, post_body, post_author) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $body, $user_id);
    $stmt->execute();
    if($stmt->affected_rows == 1) {
      $location = "Location: post.php?id=".$stmt->insert_id . "&created=true";
      header($location);
    }

  } else {
    $errorMsg = "Make sure to input conent in both the title and post!";
  }
}

?>

<div class="container">
  <div class="row">
    <?php if ($_SESSION['loggedin'] == false): ?>
      <div class="col-md-6 offset-md-3">
        <h2 class="display-4 mt-5">Please Login~</h2>
        <p>You need to be logged in to create a post!</p>
        <button type="button" class="btn btn-primary btn-block"><a href="login.php"><i class="fas fa-door"></i> Login</a></button>
      </div>
    <?php else: ?>
      <div class="col-md-6 offset-md-3">
        <h2 class="display-4 mt-5">Create a post</h2>
        <form class="" action="create.php" method="post">
          <label for="title">Post Title</label>
          <input type="text" name="title" value="" placeholder="Add a title to your post..." class="form-control">
          <label for="body">Post Content</label>
          <textarea name="body" rows="8" cols="80" class="form-control"></textarea>
          <button type="submit" name="submit" class="mt-4 btn btn-primary btn-block"> <i class="fas fa-pen"></i> Create Post</button>
        </form>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
