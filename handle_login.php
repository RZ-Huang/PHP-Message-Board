<?php
  require_once('./conn.php');
  if(!isset($_SESSION)) { 
    session_start(); 
  } 
  
  if(empty($_POST['username']) || empty($_POST['password'])) {
?>
    <a href="./index.php">返回登入頁面</a>
    <br>
<?php
    die('有空格尚未填入，請填入正確的格式');
  }
  
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  $stmt = $conn->prepare("SELECT * FROM RZ_users WHERE username=?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result =$stmt->get_result();
  if ($result->num_rows > 0) {
    $row =$result->fetch_assoc();
  }
  
  $passwordHash = $row['password'];
  
  if($row && password_verify($password, $passwordHash)) {
    $_SESSION['username'] = $username;
    header('Location: ./login.php');
  } else {
    echo "Login Failed";
?>
    <br>
    <a href="./index.php">返回登入頁面</a>
<?php
  }
?>
