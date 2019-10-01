<?php
  require_once('./conn.php');
  if(!isset($_SESSION)) { 
    session_start(); 
  } 
  if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['nickname'])) {
?>
    <a href="./register.php">返回註冊頁面</a>
    <br>
<?php
    die('有空格尚未填入，請填入正確的格式');
  }

  $username = $_POST['username'];
  $password = $_POST['password'];
  $nickname = $_POST['nickname'];
  $passwordHash = password_hash($password, PASSWORD_DEFAULT);
  
  $stmt = $conn->prepare("INSERT INTO RZ_users(username, password, nickname) VALUE(?, ?, ?)");
  $stmt->bind_param("sss", $username, $passwordHash, $nickname);

  if ($stmt->execute()) {  
    $_SESSION['username'] = $username;
    header('Location: ./index.php');
  } else {
?>
    <a href="./register.php">返回註冊頁面</a>
    <br>
<?php
    die('Failed: ' . $conn->error);
  }

?>