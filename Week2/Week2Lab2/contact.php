<?php
include 'includes/header.php';
 ?>
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15678.822607365857!2d106.68809554999999!3d10.757153399999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f1c06f4e1dd%3A0x43900f1d4539a3d!2sVietnam%20National%20University%20Ho%20Chi%20Minh%20City%20-%20University%20of%20Science!5e0!3m2!1sen!2s!4v1619158708340!5m2!1sen!2s" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>

<div class="container">
  <hr>
  <h2>Contact</h2>
  <hr>
  <div class="row col-2">
    <img src="https://www.imt-soft.com/Cms_Data/Contents/IMT_Data/Folders/Partners/~contents/DKU2CQF62TKMBK4K/Logo-H-KHTN-002-.png" style="border-radius: 25px;" alt="">
    <div class="contact-form">

      <?php
        if(empty($_POST)) {
          include 'includes/form.php';
        } else {
          $name = htmlspecialchars($_POST['name']);
          $email = htmlspecialchars($_POST['email']);
          echo "<h1>Thank you for your message, {$name}!<h1>";
          echo "<h3>We will get back to you at {$email} as soon as possible</h3>";
          if(isset($_POST['subscribe'])) {
            echo "<p>Thank you for joining our newsletter!</p>";
          }
        }

        var_dump($_POST);
        if(!empty($_POST)) {
          $name = htmlspecialchars($_POST['name']);
          if(empty($_POST['email'])) {
            echo "Email empty!";
          }
        } else {
          echo "Name not set";
        }
        $errors = [
          "email" => false,
          "name" => true
        ];
        var_dump($errors);
        function updateErr(&$errors) {
          $errors['email'] = true;
          var_dump($errors);
        }
        updateErr($errors);
        var_dump($errors);
        if(isset($name)) {
          echo "Name is set to: {$name}!";
          var_dump($name);
        } else {
          echo "Name has not been set.";
        }
       ?>
    </div>
  </div>

</div>
<?php
include 'includes/footer.php';
 ?>
