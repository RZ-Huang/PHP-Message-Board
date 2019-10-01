<?php
  include_once('./utils.php');
  require_once('./conn.php');

  if(!isset($_SESSION)) { 
    session_start(); 
  } 
  
  if(empty($_POST['id']) || empty($_POST['text'])) {
    header('Location: ./login.php');
    exit('edit failed');
  }

  $id = $_POST['id'];
  $content = $_POST['text']; 

  $stmt = $conn->prepare("UPDATE RZ_comments SET content = ? WHERE id = ? and username = ?");
  $stmt->bind_param("sss", $content, $id, $_SESSION['username']);
  
  if ($stmt->execute()) {
    header('Location: ./login.php');
  } else {
    die('Failed: ' . $conn->error);
  }

?>