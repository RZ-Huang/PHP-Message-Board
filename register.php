<?php require_once('./conn.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Comments</title>
  <link rel="stylesheet" href="./style.css">
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"> </script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="fixed-nav">
    <a class="navbar-brand" href="./index.php">留言板</a>
  </nav>
  <div class="container">
    <div class="alert alert-primary" role="alert">
      ＊本站為練習用網站，因教學用途刻意忽略資安的實作，註冊時請勿使用任何真實的帳號或密碼
     </div>
    <h2 class="card-body">註冊帳號</h2>
    <div class="input-comment">
      <form method='POST' action="./handle_register.php">
        <div class="form-group">
          <label for="formGroupExampleInput">使用者帳號</label>
          <input type="text" class="form-control" id="formGroupExampleInput" name="username" placeholder="輸入帳號">
        </div>
        <div class="form-group">
          <label for="formGroupExampleInput2">使用者密碼</label>
          <input type="password" class="form-control" id="formGroupExampleInput2" name="password" placeholder="輸入密碼">
        </div>
        <div class="form-group">
          <label for="formGroupExampleInput3">使用者暱稱</label>
          <input type="text" class="form-control" id="formGroupExampleInput3" name="nickname" placeholder="輸入暱稱">
        </div>
        <button type="submit" class="btn btn-outline-primary btn-lg btn-block">註冊</button>
      </form>
    </div>
  </div>
</body>
</html>