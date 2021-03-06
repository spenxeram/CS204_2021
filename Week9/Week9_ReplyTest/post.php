<?php
include_once 'config.php';
include 'func/postmanager.php';
include 'classes/Comment.php';
include 'classes/Reply.php';
include 'includes/header.php';
if(isset($_GET['id'])) {
  $post = getPost($_GET['id'], $conn);
  $theid = $_GET['id'];
  $comments = new Comment($theid, $conn);
  $comments->getComments();
  $replies = new Reply($theid, $conn);
  $replies->getReplies();

}



 ?>
 <hr>
 <div class="container post">
   <div class="row">
     <?php if ($post == false): ?>
       <h2 class="display-4">404 Post Not Found!</h2>
      </div>
     <?php else: ?>
       <div class="col-md-8 offset-md-2">
          <img src="<?php echo $post['post_img']; ?>" class="img-fluid" alt="">
          <h2 class="font-weight-light mt-4"><?php echo htmlspecialchars($post['post_title']); ?></h2>
          <h5><em><?php echo htmlspecialchars($post['date_created']); ?>
          </em></h5>
           <p><?php echo htmlspecialchars($post['post_body']); ?></p>
       </div>
     </div> <!-- end of post row -->

     <!-- comment row -->
      <hr>
      <h3 class="display-4 mt-3 mb-3">Comments</h3>
      <hr>

      <div class="row comment-form">
       <div class="col-md-8 form">
         <?php if ($_SESSION['loggedin']): ?>
           <form class="comment-form" method="POST" action="func/ajaxmanager">
             <textarea name="comment-text" class="form-control" rows="4" cols="80"></textarea>
             <input type="hidden" name="id" value="<?php echo htmlspecialchars($_SERVER['QUERY_STRING']); ?>">
             <button type="submit" name="comment-submit" class="comment-submit btn btn-outline-success mt-2"><i class="far fa-comment"></i> Add Comment</button>
           </form>

         <?php else: ?>
           <h3>Please login to comment!</h3>
           <a href="login.php"><button type="button" class="btn btn-primary btn-lg">Login</button></a>
         <?php endif; ?>
       </div>
       </div>
       <div class="row comments">
       <?php
        $comments->outputComments($replies);
        ?>
        </div>

     <?php endif; ?>

 </div>

 <hr>
 <?php
 var_dump($replies);
 include 'includes/footer.php';
  ?>
