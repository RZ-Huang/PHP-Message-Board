<?php
  include_once('./utils.php');
  require_once('./conn.php');

  if(!isset($_SESSION)) { 
    session_start(); 
  } 

  if(empty($_POST['content'])) {
    header('Location: ./login.php');
    exit('add failed');
  }

  $content = gethtmlspecialchars($_POST['content']);

  $nickName =  gethtmlspecialchars(getNickname($conn));
  $sessionUsername = $_SESSION['username'];
  

  $stmt = $conn->prepare("INSERT INTO RZ_comments(username,name,content) VALUE(?, ?, ?)");
  $stmt->bind_param("sss", $sessionUsername, $nickName, $content);
  

  if ($stmt->execute()) {
    $sqlNewComment = "SELECT * FROM RZ_comments ORDER BY created_at DESC LIMIT 1";
    $stmtNewComment = $conn->prepare($sqlNewComment);
    $stmtNewComment->execute();
    $resultNewComment = $stmtNewComment->get_result();
    if ($resultNewComment->num_rows > 0) {
      $rowNewComment = $resultNewComment->fetch_assoc();  
    }
    
    $createdTime = $rowNewComment['created_at'];
    $username = $rowNewComment['username'];
    $id = $rowNewComment['id'];

    echo json_encode(array(
      'result' => 'success',
      'message' => 'successfully added',
      'nickname' => $nickName,
      'content' => $content,
      'id' => $id,
      'createdTime' => $createdTime,
      'username' => $username,
      'sessionUsername' => $sessionUsername,
    ));
  } else {
    echo json_encode(array(
      'result' => 'fail',
      'message' => 'add failed'
    ));
  }
?>
