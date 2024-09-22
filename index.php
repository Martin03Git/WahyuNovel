<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WahyuNovel | Login</title>
  <!-- menghubungkan CSS Boostrap -->
  <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
    crossorigin="anonymous">
  <style>
    body {
      padding: 0;
    }

    div.container-fluid {
      height: 100vh;
      width: 100vw;
      background-color: #F5C518;
      color: #F5C518;
      display: flex;
      justify-content: center;
      align-items: center;
    }


    /* movie poster */
    div.movie-poster {
      padding: 0;
    }

    div.movie-poster img {
      width: 100%;
      padding: 0;
      border-radius: 5px 0 0 5px;
    }


    /* input section */
    form {
      background-color: #fff;
      width: 600px;
      border-radius: 5px;
      display: flex;
      align-items: center;
      box-shadow: 0px 0px 7px 0px #94874e;
    }

    h1 {
      text-align: center;
      font-weight: 700;
    }

    div.btn-wrap {
      display: flex;
      justify-content: center;
      color: #fff;
      font-weight: 700;
    }


    /* pesan ketika gagal login */
    .alert-wrap {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      height: 100vh;
      position: absolute;
      z-index: 999;
    }

    .alert{
      background: #e44e4e;
      color: white;
      padding: 10px;
      text-align: center;
      border:1px solid #b32929;
    }

  </style>
</head>
<body>
<!-- menghubungkan dengan Jquery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <div class="container-fluid">
    <?php 
      if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal' ) {
        echo "<script>alert('Username atau Password salah!!!');</script>";
    }
      
    ?>

    <form class="row" action="login_process.php" method="post">
      <div class="movie-poster col-6">
        <img src="images/Return of Mount Hua Sect.jpeg" alt="">
      </div>
      <div class="input-section col-6 p-2">
        <h1 class="mb-4">Login</h1>
        <div class="input-group flex-nowrap mb-2">
          <span class="input-group-text" id="addon-wrapping">@</span>
          <input 
            name="username" type="text" class="form-control" 
            placeholder="Username" aria-label="Username" 
            aria-describedby="addon-wrapping">
        </div>
        <div class="input-group flex-nowrap mb-3">
          <span class="input-group-text" id="addon-wrapping">
            <svg xmlns="http://www.w3.org/2000/svg" 
              width="16" height="16" fill="currentColor" 
              class="bi bi-lock" viewBox="0 0 16 16">
              <path 
                d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 
                2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1"/>
            </svg>
          </span>
          <input name="password" type="password" class="form-control" 
            placeholder="Password" aria-label="Password" 
            aria-describedby="addon-wrapping">
        </div>
        <small><b>Belum punya akun? Daftar <a href="register.php">disini</a></b></small>
        <div class="btn-wrap mt-2">
          <button type="submit" class="btn btn-warning">Login</button>
        </div>
      </div>
    </form>
  </div>

  <script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
    crossorigin="anonymous">
  </script>
  <script>
    $('.alert-wrap').on("click", function() { $(this).remove()});
  </script>
</body>
</html>