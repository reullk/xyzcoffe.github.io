<?php
// Session her zaman en üstte başlatılmalı
session_start();
include 'connect.php';

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $email = trim($_POST['email']);
   // PDO kullandığın için FILTER_SANITIZE_STRING'e gerek yok, kaldırıldı.
   
   $pass = sha1($_POST['pass']); 
   // NOT: kayit.php sayfasında da şifreyi sha1 ile kaydettiğinden emin ol!

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   
   if($select_user->rowCount() > 0){
      $row = $select_user->fetch(PDO::FETCH_ASSOC);
      $_SESSION['user_id'] = $row['id'];
      header('location:a.php');
      exit();
   }else{
      $message[] = 'Yanlış e-posta adresi veya şifre girdiniz!';
   }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>XYZ Coffee </title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="style.css">

<style>
.login-box {
    width: 400px;
    margin: 80px auto;
    padding: 30px;
    background: #f3f3f3;
    border: 1px solid #333;
    text-align: center;
}

.login-box h2 {
    color: #6f4e37;
    margin-bottom: 25px;
}

.login-box input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #333;
    font-size: 14px;
    box-sizing: border-box; /* Taşımları önlemek için */
}

.login-box .buton{
    margin-top: 15px;
    padding: 10px 25px;
    background-color: #542607;
    color: white;
    border: none;
    cursor: pointer;
    font-weight: bold;
}

.login-box p {
    margin-top: 20px;
    color: #555;
}

.login-box a {
    color: black;
    text-decoration: none;
    font-weight: bold;
}

/* Hata mesajı için stil */
.error-msg {
    background-color: #f8d7da;
    color: #721c24;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #f5c6cb;
    border-radius: 5px;
    font-size: 14px;
}
</style>

</head>
<body>
<header>
    <div class="logo">XYZ</div>
    <nav>
        <a href="a.php" class="b">Ana sayfa</a>
        <a href="menu.php" class="b">Menu</a>
        <a href="bilgi.php" class="b">Bilgi</a>
        <a href="iletisim.php" class="b">İletişim</a>
       
    </nav>

    <div class="nav-buttons">
     <a href="admin/admin_login.php" class="a"> <button>Admin Girişi</button></a> 
    </div>
</header>

<div class="login-box">
    <h2>GİRİŞ YAPIN</h2>

    <?php
    if(isset($message)){
       foreach($message as $msg){
          echo '<div class="error-msg">'.$msg.'</div>';
       }
    }
    ?>

    <form method="POST" action="">
        <input type="email" name="email" placeholder="e-postanı giriniz" maxlength="50" required>
        <input type="password" name="pass" placeholder="şifrenizi giriniz" maxlength="50" required>
        <input type="submit" value="Giriş Yapın" name="submit" class="buton">
    </form>
    
    <p>Hesabınız yok mu? <a href="kayit.php">hemen kayıt olun</a></p>
</div>

</body>
</html>