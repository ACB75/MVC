<?php
include 'partials/header.tpl.php';
?>
<main>
  <section class="container">
    <h2> USER </h2>
    <?php
    if (key_exists("user", $data)) {
      $display =
        "<div class='col-md-12'>
              <div class='h-100 p-5 text-bg-dark rounded-3'>
                <h2> Welcome Home " . $data["user"]->getName() . "</h2>
                <p> Browse books through our application, rent the book and enjoy reading. </p>
                <a type='button' class='btn btn-outline-light me-1' href='/index/user_x_library'>Active Books</a>
                </div>
              </div>";

      echo $display;

    } else {
      $display =
        "<div class='col-md-12'>
              <div class='h-100 p-5 text-bg-dark rounded-3'>
                <h2>  Upps! Something went wrong :( </h2>
                <p> Anything to display. </p>
                <a class='btn btn-outline-light me-1' type='button' href='/index/library_read'> Explore Books </a>
              </div>
            </div>";
      echo $display;
    }
    ?>
  </section>
</main>

<?php
include 'partials/footer.tpl.php';
?>