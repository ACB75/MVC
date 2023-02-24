<?php
include 'partials/header.tpl.php';
?>
<main>
  <section class="container">
    <div class="col-md-4">
      <h2>UserLibrary</h2>
      <?php
      $display = "";
      if (key_exists("library", $data)) {
        if (is_array($data["library"])) {
          foreach ($data["library"] as $lib) {
            $display .= "<div class='col-md-12'>
            <div class='h-100 p-5 text-bg-dark rounded-3'>
            <h2> " . $lib->getAuthor() . "</h2>
            <p> " . $lib->getIsbn() . " </p> 
            <p> " . $lib->getTitle() . " </p>
            <p> " . $lib->getYear() . " </p>";
            if (key_exists("user", $data)) {
              if ($data["user"] != null) {
                $date = date("Y-m-d");
                $id = $data["user"]->getId();
                $isbn = $lib->getIsbn();
                $display .= "<a type='button' class='btn btn-warning me-1' href='/userlibrary/update/date/$date/user/$id/isbn/$isbn' >DEVOLVER</a>";
              }
            }
            $display .= "</div> </div></br>";
          }
        } else {
          if ($library != null) {
            $display = "<div class='col-md-12'>
            <div class='h-100 p-5 text-bg-dark rounded-3'>
              <h2> " . $library->getAuthor() . "</h2>
            <p> " . $library->getIsbn() . " </p> 
            <p> " . $library->getTitle() . " </p>
            <p> " . $library->getYear() . " </p>";
            if (key_exists("user", $data)) {
              if ($data["user"] != null) {
                $display .= "<a type='button' class='btn btn-warning me-1' href='/index/user_x_library/devolver/true'>DEVOLVER</a>";
              }
              $display .= "</div> </div></br>";
            }
          } else {
            $display .= "No data";
          }
        }
      } else {
        $display =
          "<div class='col-md-12'>
        <div class='h-100 p-5 text-bg-dark rounded-3'>
        <h2>  Upps! Something went wrong :( </h2>
        <p> Anything to display. </p>
        <a class='btn btn-outline-light me-1' type='button' href='/index'> Home </a>
        </div>
        </div>";
      }
      echo $display;
      ?>
    </div>
  </section>
</main>

<?php
include 'partials/footer.tpl.php';
?>