<!DOCTYPE html>
<html lang="es">

<head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>MVC</title>
      <link href="/public/css/bootstrap-5.3.0-alpha1-dist/css/bootstrap.css" rel="stylesheet">
      <script src="/public/js/index.js"></script>
</head>

<body>
      <header class="p-3 text-bg-dark">
            <div class="container">
                  <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                              <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                                    <use xlink:href="#bootstrap"></use>
                              </svg>
                        </a>
                        <ul class="nav col-12 col-lg-auto me-lg-auto mb-1 justify-content-center mb-md-1">
                              <?php
                              if (array_key_exists("user", $data)) {
                                    $display = "<li><a href='/user/index' class='nav-link px-1 text-secondary'>Home</a></li>";
                                    echo $display;
                              } else {
                                    $display = "<li><a href='/' class='nav-link px-1 text-secondary'>Home</a></li>";
                                    echo $display;
                              }
                              ?>
                        </ul>
                        <div class="text-end">
                              <?php
                              if (array_key_exists("user", $data)) {
                                    $display =
                                          "<a type='button' class='btn btn-outline-light me-2' href='/index/logout'>Logout</a>
                                                <a type='button' class='btn btn-warning' href='/index/dashboard'>Dashboard</a>";
                                    echo $display;

                              } else {
                                    $display =
                                          "<a type='button' class='btn btn-outline-light me-2' href='/index/login'>Login</a>
                                          <a type='button' class='btn btn-warning' href='/index/register'>Register</a>";
                                    echo $display;
                              }
                              ?>
                        </div>
                  </div>
            </div>
      </header>