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
                $display .= "<a type='button' class='btn btn-warning me-1' href='/index/user_x_library/isbn/".$lib->getIsbn()."'> ALQUILAR </a>";
                if ($data["user"]->getName() === 'master')
                  $display .= "<a type='button' class='btn btn-warning me-1' href='/index/library_update/isbn/" . $lib->getIsbn() . "'> Update </a>
                <a type='button' class='btn btn-warning me-1' href='/index/library_delete/isbn/" . $lib->getIsbn() . "'> Delete </a>";
              }
              $display .= "</div> </div></br>";
            }
          }
        } else {
          $display = "<div class='col-md-12'>
            <div class='h-100 p-5 text-bg-dark rounded-3'>
             <h2> " . $data["library"]->getAuthor() . "</h2>
            <p> " . $data["library"]->getIsbn() . " </p> 
            <p> " . $data["library"]->getTitle() . " </p>
            <p> " . $data["library"]->getYear() . " </p>";
          if (key_exists("user", $data)) {
            if ($data["user"] != null) {
              $display .= "<a type='button' class='btn btn-warning me-1' href='/index/user_x_library/isbn/".$data["library"]->getIsbn()."'> ALQUILAR </a>";
              if ($data["user"]->getName() === 'master')
                $display .= "<a type='button' class='btn btn-warning me-1' href='/index/library_update/isbn/" . $data["library"]->getIsbn() . "'> Update </a>
                <a type='button' class='btn btn-warning me-1' href='/index/library_delete/isbn/" . $data["library"]->getIsbn() . "'> Delete </a>";
            }
            $display .= "</div> </div></br>";
          }
        }
        echo $display;
      } else {
        $display =
          "<div class='col-md-12'>
        <div class='h-100 p-5 text-bg-dark rounded-3'>
        <h2>  Upps! Something went wrong :( </h2>
        <p> Anything to display. </p>
        <a class='btn btn-outline-light me-1' type='button' href='/index'> Home </a>
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