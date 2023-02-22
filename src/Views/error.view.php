<?php
include 'partials/header.tpl.php';
?>

<main>
      <section class="container">
            <h2> ERROR </h2>
            <?php
                  $display = "";

                  $display = ($data) ? "<div class='col-md-12'>
                                    <div class='h-100 p-5 text-bg-dark rounded-3'>
                                    <h3> Upps! Something went wrong :( </h3>
                                    <p> " . $data["error"] . "  </p>
                                    </div>
                              </div>" : "<div class='col-md-12'>
                                    <div class='h-100 p-5 text-bg-dark rounded-3'>
                                    <h3> Upps! Something went wrong :( </h3>
                                    <p> Unexpected error </p>
                                    </div>
                              </div>";

                  echo $display;
            ?>
</main>

<?php
include 'partials/footer.tpl.php';
?>