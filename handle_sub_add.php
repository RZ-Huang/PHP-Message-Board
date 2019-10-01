<?php
  include_once('./utils.php');
  require_once('./conn.php');
  if(!isset($_SESSION)) { 
    session_start(); 
  } 
  

  if(empty($_POST['content']) || empty($_POST['commentID'])) {
    header('Location: ./login.php');
    exit('add failed');
  }

  $content = gethtmlspecialchars($_POST['content']);
  $commentId = gethtmlspecialchars($_POST['commentID']);

  $nickName =  gethtmlspecialchars(getNickname($conn));
  $sessionUsername = $_SESSION['username'];

  $stmt = $conn->prepare("INSERT INTO RZ_sub_comments(comment_id, username, nickname, content) VALUE(?, ?, ?, ?)");
  $stmt->bind_param("ssss", $commentId, $sessionUsername, $nickName, $content);
  
  if ($stmt->execute()) {
    $sqlNewSubComment = "SELECT * FROM RZ_sub_comments ORDER BY created_at DESC LIMIT 1";
    $stmtNewSubComment = $conn->prepare($sqlNewSubComment);
    $stmtNewSubComment->execute();
    $resultNewSubComment = $stmtNewSubComment->get_result();
    if ($resultNewSubComment->num_rows > 0) {
      $rowNewSubComment = $resultNewSubComment->fetch_assoc();  
    }

    $createdTime = $rowNewSubComment['created_at'];
    $username = $rowNewSubComment['username'];
    $subCommentId = $rowNewSubComment['id'];


    $sqlMainUsername = "SELECT username FROM RZ_comments WHERE id = ?";
    $stmtMainUsername = $conn->prepare($sqlMainUsername);
    $stmtMainUsername->bind_param('s',$commentId);
    $stmtMainUsername->execute();
    $resultMainUsername = $stmtMainUsername->get_result();
    if($resultMainUsername->num_rows > 0) {
      $rowMainUsername = $resultMainUsername->fetch_assoc();
    }
    
    $mainCommentUsername = $rowMainUsername['username'];

    echo json_encode(array(
      'result' => 'success',
      'message' => 'successfully added',
      'content' => $content,
      'commentId' => $commentId,
      'subCommentId' => $subCommentId,
      'nickname' => $nickName,
      'createdTime' => $createdTime,
      'username' => $username,
      'mainCommentUsername' => $mainCommentUsername,
      'sessionUsername' => $sessionUsername,
    ));

  } else {
    echo json_encode(array(
      'result' => 'fail',
      'message' => 'add failed'
    ));
  }

?>