<?php
include 'partials/header.tpl.php';
?>

<main>
  <br>
  <section class="container">
    <h3>INDEX</h3>
    <?php
    if (array_key_exists("form", $data)) {
      echo $data["form"];
    } else {
      $data =
        "<div class='col-md-12'>
        <div class='h-100 p-5 text-bg-dark rounded-3'>
          <h2> Welcome Home </h2>
          <p> Browse books through our application, rent the book and enjoy reading. </p>
          <a type='button' class='btn btn-outline-light me-1' href='/index/library_read'>Explore Books</a>
        </div>
      </div>";
      echo $data;
    }
    ?>
  </section>
  <br>
</main>

<?php
include 'partials/footer.tpl.php';
?>