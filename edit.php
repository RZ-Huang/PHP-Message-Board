<?php 
  require_once('./conn.php'); 
  include_once('./utils.php');

  if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM RZ_comments WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
    }
  } 
?>

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

<body id="home">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="fixed-nav">
  <a class="navbar-brand" href="#home">留言板</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <li class="nav-item ">
        <a class="nav-link" href="./handle_logout.php">登出</a>
      </li>
    </ul>
  </div>
  </nav>
  <div class="container">
    <div class="alert alert-primary" role="alert">
      ＊本站為練習用網站，因教學用途刻意忽略資安的實作，註冊時請勿使用任何真實的帳號或密碼
     </div>
    <h2 class="card-body">編輯留言</h2>
    <div class="input-comment">
      <form method='POST' class="main-form" action="./handle_edit.php">
        <div class="name">
          <?php 
            echo "「" . gethtmlspecialchars(getNickname($conn)) . "」 您好！請在下方更改您的留言" ;
          ?>
        </div>
        <div class="text">
          <textarea name="text" rows='10' cols='60' class="main-text"><?php echo $row['content']; ?></textarea>
        </div>
          <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
          <button type="submit" class="btn btn-outline-primary btn-lg btn-block" id="main-submit">提交留言</button>
      </form>
    </div>
    <h2 class="card-body">訪客留言</h2>
    <div class="comments">
      <?php
        renderAllComments($conn); 
      ?>
  </div>
</body>
</html>