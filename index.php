<?php 
  include_once('./utils.php');
  require_once('./conn.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Comments</title>
  <link rel="stylesheet" href="./style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
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
          <a class="nav-link" href="./register.php">註冊</a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="container">
    <h2 class="card-body">登入帳號</h2>
    <div class="input-comment">
      <form method='POST' action="./handle_login.php">
        <div class="form-group">
          <label for="formGroupExampleInput">使用者帳號</label>
          <input type="text" class="form-control" id="formGroupExampleInput" name="username" placeholder="輸入帳號">
        </div>
        <div class="form-group">
          <label for="formGroupExampleInput2">使用者密碼</label>
          <input type="password" class="form-control" id="formGroupExampleInput2" name="password" placeholder="輸入密碼">
        </div>
        <button type="submit" class="btn btn-outline-primary btn-lg btn-block">登入</button>
      </form>
    </div>
    <h2 class="card-body">訪客留言</h2>
    <div class="comments">
      <?php
        $sqlAllComments = "SELECT * FROM RZ_comments ORDER BY created_at DESC";
        $resultAllComments = $conn->query($sqlAllComments);
        $AllCommentsCount = $resultAllComments->num_rows;
        $AllPages = ceil($AllCommentsCount/20);
        $page = '';
        
        if(!isset($_GET['page'])) {
          $page = 1;
        } else {
          $page = intval($_GET['page']);
        }
        $pageBeginningComment = ($page-1)*20;
        $sqlPageComments = "SELECT * FROM RZ_comments ORDER BY created_at DESC LIMIT $pageBeginningComment,20";
        $resultPageComments = $conn->query($sqlPageComments);

        $sqlAllSubComments = "SELECT * FROM RZ_sub_comments ORDER BY created_at DESC";
        $resultAllSubComments = $conn->query($sqlAllSubComments);

        if ($resultPageComments->num_rows > 0) {
          while($rowPageComments = $resultPageComments->fetch_assoc()) {
            echo "<div class='comment'>";
            echo "  <div class='main-comment'>";
            echo "    <h1>". gethtmlspecialchars($rowPageComments['name']) ."</h1>";
            echo "    <h2>". gethtmlspecialchars($rowPageComments['content']) ."</h2>";
            echo "    <p class='createdTime-comment'>" . gethtmlspecialchars($rowPageComments['created_at']) . "</p>";
            echo "  </div>";
        ?>
              <div class="sub-comments">           
        <?php 
          
          if ($resultAllSubComments->num_rows > 0  ) {
            while($rowAllSubComments = $resultAllSubComments->fetch_assoc()) {
              if ($rowPageComments['id'] === $rowAllSubComments['comment_id']) { 
                if ($rowAllSubComments['username'] === $rowPageComments['name']) {
                  echo "<div class='sub-comment pop'>";
                } else {
                  echo "<div class='sub-comment'>";
                }
                echo "  <h1>". gethtmlspecialchars($rowAllSubComments['username']) . "</h1>";
                echo "  <h2>". gethtmlspecialchars($rowAllSubComments['content']) . "</h2>";
                echo "  <p>". gethtmlspecialchars($rowAllSubComments['created_at']) . "</p>"; 
                echo "</div>";
              }
            }   
            $resultAllSubComments->data_seek(0);
            
          }
        ?>             
              </div>
          </div>
        <?php
          }
        }

        echo "<div class='pages'>";
        for($i=1 ;$i <=$AllPages; $i++){
          echo "<a href='?page=$i' class='page'>" . $i . "</a>";         
        }
          echo "</div>";
          
        if (isset($_GET['page'])) {
          echo "<div class='view-pages'>";
          echo "第 " . $_GET['page'] . "/" . $AllPages . " 頁";
          echo "</div>";
        } else if($AllPages == 0) {
          echo "<div class='view-pages'>";
          echo "第 " . 0 . "/" . $AllPages . " 頁";
          echo "</div>";
        } else {
          echo "<div class='view-pages'>";
          echo "第 " . 1 . "/" . $AllPages . " 頁";
          echo "</div>";
        }
      ?>
  </div>
</body>
</html>