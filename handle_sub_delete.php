<?php
  include_once('./utils.php');
  require_once("./conn.php");
  if(!isset($_SESSION)) { 
    session_start(); 
  } 

  if(empty($_POST['id'])) {
    header('Location: ./login.php');
    exit('delete failed');
  }
  
  $id = $_POST['id'];

  $stmt = $conn->prepare("DELETE FROM RZ_sub_comments WHERE id = ? and username = ?");
  $stmt->bind_param("ss", $id, $_SESSION['username']);
  
  if($stmt->execute()) {
    echo json_encode(array(
      'result' => 'success',
      'message' => 'successfully deleted'
    ));
  } else {
    echo json_encode(array(
      'result' => 'fail',
      'message' => 'delete failed'
    ));
  }
?>