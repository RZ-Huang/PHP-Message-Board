<?php
  require_once('./conn.php');
  if(!isset($_SESSION)) { 
    session_start(); 
  } 
  
  // 跳脫字元
  function gethtmlspecialchars($str) {
    return htmlspecialchars($str, ENT_QUOTES , 'utf-8');
  }

  // 根據 session 取得登入的使用者暱稱
  function getNickname($conn) {
  
    $stmtNickname = $conn->prepare("SELECT nickname FROM RZ_users WHERE username = ? ");
    $stmtNickname->bind_param('s', $_SESSION['username']);
    $stmtNickname->execute();
    $resultNickname= $stmtNickname->get_result();
    
    $rowNickname = '';
    if ($resultNickname->num_rows > 0) {
      $rowNickname = $resultNickname->fetch_assoc()['nickname'];
    }

    return $rowNickname;
  }

  // 登入後渲染所有的留言內容
  function renderAllComments($conn){
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
            if($rowPageComments['username'] ===  $_SESSION['username']) {
              echo "  <a href='./edit.php?id=$rowPageComments[id]' class='edit-comment'>編輯</a>";
              echo "  <button class='delete-comment' data-id='$rowPageComments[id]'>刪除</button>";
            }
            
            echo "  </div>";
            echo "  <div class='sub-comments'>";
        
          if ($resultAllSubComments->num_rows > 0  ) {
            while($rowAllSubComments = $resultAllSubComments->fetch_assoc()) {
              if ($rowPageComments['id'] === $rowAllSubComments['comment_id']) { 
                if ($rowAllSubComments['username'] === $rowPageComments['username']) {
                  echo "<div class='sub-comment pop'>";
                } else {
                  echo "<div class='sub-comment'>";
                }
                echo "  <h1>". gethtmlspecialchars($rowAllSubComments['nickname']) . "</h1>";
                echo "  <h2>". gethtmlspecialchars($rowAllSubComments['content']) . "</h2>";
                echo "  <p>". gethtmlspecialchars($rowAllSubComments['created_at']) . "</p>";
                if($rowAllSubComments['username'] ===  $_SESSION['username']) {
                  echo "  <a href='./sub_edit.php?id=$rowAllSubComments[id]' class='edit-comment'>編輯</a>";
                  echo "  <button class='delete-sub-comment' data-id='$rowAllSubComments[id]'>刪除</button>";        
                }           
                echo "</div>";
              }
            }   
            $resultAllSubComments->data_seek(0);
            
          }
          
               echo "<h1>回覆留言</h1>";
               echo "<form method='POST' class='sub-form' >";
               echo "  <div class='text'>";
               echo "    <textarea name='text' rows='10' cols='50' class='sub-text'></textarea>";
               echo "  </div>";               
               echo "  <button type='submit' class='btn btn-outline-primary btn-lg btn-block' id='sub-submit'>提交留言</button>";
    

                 echo "<input type='hidden' name='comment-id' value='$rowPageComments[id]' >";
                  

               echo "</form>";
               echo "</div>";
            echo "</div>";
          }
            echo "<div class='pages'>";
            for($i=1 ;$i <=$AllPages; $i++){
                echo "<a href='./login.php?page=$i' class='page'>" . $i . "</a>";         
            }
              echo "</div>";

            if (isset($_GET['page'])) {
              echo "<div class='view-pages'>";
              echo "第 " . $_GET['page'] . "/" . $AllPages . " 頁";
              echo "</div>";
            } else {
              echo "<div class='view-pages'>";
              echo "第 " . 1 . "/" . $AllPages . " 頁";
              echo "</div>";
            }
        } else {
          echo "failed";
        }
      
    return $AllPages;
  } 
?>


