<?php
include 'partials/header.tpl.php';
?>
<main>
  <section class="container">
    <h2> Library </h2>
    <?php
    if ($data["library"]) {
      $display =
        "<div class='col-md-12'>
              <div class='h-100 p-5 text-bg-dark rounded-3'>
                <h2> Welcome Home " . $data["library"]->getName() . "</h2>
                <p> Browse books through our application, rent the book and enjoy reading. </p>
                <button class='btn btn-outline-light' type='button' href='#'> Rent </button>
              </div>
            </div>";
      echo $display;
    } else {
      $display =
        "<div class='col-md-12'>
              <div class='h-100 p-5 text-bg-dark rounded-3'>
                <h2>  Upps! Something went wrong :( </h2>
                <p> Anything to display. </p>
                <button class='btn btn-outline-light' type='button' href='#'> Start </button>
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