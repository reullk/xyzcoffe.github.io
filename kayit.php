<?php

include 'connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
   $select_user->execute([$email, $number]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'E-posta adresi veya numara zaten mevcut!
';
   }else{
      if($pass != $cpass){
         $message[] = 'Parola eşleşmedi!
';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password) VALUES(?,?,?,?)");
         $insert_user->execute([$name, $email, $number, $cpass]);
         $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
         $select_user->execute([$email, $pass]);
         $row = $select_user->fetch(PDO::FETCH_ASSOC);
         if($select_user->rowCount() > 0){
            $_SESSION['user_id'] = $row['id'];
            header('location:a.php');
            exit;
         }
      }
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

</style>

</head>
<body>
<header>
    <div class="logo">XYZ</div>
    <nav>
        <a href="a.php" class="b">Ana sayfa</a>
        <a href="menu.php"class="b">Menu</a>
        <a href="bilgi.php"class="b">Bilgi</a>
        <a href="iletisim.php"class="b">İletişim</a>
       
    </nav>



    <div class="nav-buttons">
     <a href="admin/admin_login.php" class="a">  <button>Admin Girişi</button></a> 
        
    </div>
</header>

<div class="login-box">
    <h2>KAYIT YAPIN</h2>
<div class="login-box">
    <h2>KAYIT YAPIN</h2>

    <!-- HATA MESAJLARINI GÖSTERMEK İÇİN BU KISM -->
    <?php
    if(isset($message)){
       foreach($message as $msg){
          echo '
          <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border: 1px solid #f5c6cb; font-size: 14px;">
             <span>'.$msg.'</span>
          </div>
          ';
       }
    }
    ?>
  
    <form action="" method="POST">
      <input type="text" name="name"placeholder="İsim giriniz" maxlength="50"  >
        <input type="email" name="email"placeholder="e-postanı giriniz" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" >

        <input type="number" name="number"placeholder="Numara giriniz"  min="0" max="9999999999" maxlength="10" >

        <input type="password" name="pass" required placeholder="şifrenizi giriniz" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="cpass" required placeholder="şifrenizi doğrulayınız" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="submit" value="Kayıt olun" name="submit" class="buton">
    </form>

    <p>Zaten bir hesabınız var mı? <a href="giris.php">hemen giriş yapın</a></p>
</div>


</body>
</html>
