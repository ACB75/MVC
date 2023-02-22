<?php
include 'partials/header.tpl.php';
?>
<main>
  <section class="container">
    <div class="col-md-4">
      <h2>Library</h2>
      <?php
      $display = "";
      if (key_exists("library", $data)) {
        foreach ($data["library"] as $lib) {
          $display .= "<div class='col-md-12'>
          <div class='h-100 p-5 text-bg-dark rounded-3'>
          <h2> Author " . $lib->getAuthor() . "</h2>
          <p> " . $lib->getTitle() . " </p>
          <p> " . $lib->getYear() . " </p>
          <p> " . $lib->getIsbn() . " </p> ";
          if (key_exists("user", $data)) {
            if ($data["user"] != null) {
              $display .= "<a type='button' class='btn btn-warning me-3' href='/index/library_create'>ALQUILAR</a>";
            if ($data["user"]->getName() === 'master')
              $display .= "<a type='button' class='btn btn-warning' href='/index/library_delete/isbn/".$lib->getIsbn()."'> Delete </a>";

          }
          $display .= "</div> </div>";
        }
        }

        echo $display;

      } else {
        $display =
          "<div class='col-md-12'>
        <div class='h-100 p-5 text-bg-dark rounded-3'>
        <h2>  Upps! Something went wrong :( </h2>
        <p> Anything to display. </p>
        <a class='btn btn-outline-light' type='button' href='/index'> Home </a>
        </div>
        </div>";
        echo $display;
      }
      ?>
    </div>
  </section>
</main>

<?php
include 'partials/footer.tpl.php';
?>