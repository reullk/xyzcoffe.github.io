<?php

include 'connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:a.php');
   exit(); // Header yönlendirmesinden sonra kodun çalışmaya devam etmemesi için exit eklemek iyi bir pratiktir.
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);

   if(!empty($name)){
      $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $user_id]);
      $message[] = 'Kullanıcı adı güncellendi!';
   }

   if(!empty($email)){
      $select_email = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND id != ?"); // Kendi e-postasını istisna tutmak için 'AND id != ?' eklenebilir.
      $select_email->execute([$email, $user_id]);
      if($select_email->rowCount() > 0){
         $message[] = 'e-posta zaten alınmış!';
      }else{
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $user_id]);
         $message[] = 'E-posta adresi güncellendi!';
      }
   }

   if(!empty($number)){
      $select_number = $conn->prepare("SELECT * FROM `users` WHERE number = ? AND id != ?");
      $select_number->execute([$number, $user_id]);
      if($select_number->rowCount() > 0){
         $message[] = 'numara zaten alınmış!';
      }else{
         $update_number = $conn->prepare("UPDATE `users` SET number = ? WHERE id = ?");
         $update_number->execute([$number, $user_id]);
         $message[] = 'Telefon numarası güncellendi!';
      }
   }
   
   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $select_prev_pass = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
   $select_prev_pass->execute([$user_id]);
   $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   
   // Formdan gelen şifre alanları boş değilse şifre kontrolü yapalım
   if(!empty($_POST['old_pass']) || !empty($_POST['new_pass']) || !empty($_POST['confirm_pass'])){
       $old_pass = sha1($_POST['old_pass']);
       $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
       $new_pass = sha1($_POST['new_pass']);
       $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
       $confirm_pass = sha1($_POST['confirm_pass']);
       $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

       if($old_pass != $empty_pass){
          if($old_pass != $prev_pass){
             $message[] = 'Eski parola eşleşmedi!';
          }elseif($new_pass != $confirm_pass){
             $message[] = 'Parola eşleşmedi!';
          }else{
             if($new_pass != $empty_pass){
                $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
                $update_pass->execute([$confirm_pass, $user_id]);
                $message[] = 'şifre başarıyla güncellendi!';
             }else{
                $message[] = 'Lütfen yeni bir şifre girin!';
             }
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
<style>
.navbar-brand-logo {
    display: inline-flex;
    align-items: center;
    gap: 10px; 
    text-decoration: none;
    color: #ffffff; 
    font-weight: bold;
}

.nav-logo-img {
    height: 250px; 
    width: auto;  
    object-fit: contain;
}
</style>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" type="text/css" href="style.css">
<style>
/* Formun dış  kutusu ve konumlandırılması */
.update-profile-container {
    background-color: white;
    border: 2px solid #222222;
    padding: 40px 30px;
    width: 100%;
    max-width: 460px; 
    margin: 60px auto; 
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

/* Başlık Ayarı */
.update-profile-container h2 {
    font-family: 'Arial', sans-serif;
    font-size: 24px;
    font-weight: bold;
    color: #111111;
    margin-bottom: 25px;
    letter-spacing: 0.5px;
}

/* Girdi Alanlarının Kapsayıcısı */
.form-group {
    margin-bottom: 15px;
}

/* Input Alanları */
.form-group input {
    width: 100%;
    padding: 14px 15px;
    border: 1.5px solid #333333;
    font-size: 16px;
    color: #333333;
    background-color: none;
    outline: none;
    box-sizing: border-box; 
    transition: border-color 0.2s;
}

.form-group input::placeholder {
    color: #757575;
}

.form-group input:focus {
    border-color: #ffcc00; 
}

/* Sarı Renkli Güncelleme Butonu */
.btn-update {
    background-color: brown; 
    color: #111111;
    border: none;
    padding: 12px 35px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 10px;
    transition: background-color 0.2s, transform 0.1s;
}

.btn-update:hover {
    background-color: green;
}

.btn-update:active {
    transform: scale(0.98);
}

/* Bildirim Mesajları Stili */
.message-box {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 4px;
    font-family: 'Arial', sans-serif;
    font-size: 14px;
    text-align: left;
}
</style>
</head>
<body>

<header>
  <a href="a.php" class="navbar-brand-logo">
    <img src="XYZ.png" alt="XYZ Coffee Logo" class="nav-logo-img">
    <div class="logo">XYZ</div>
  </a>
    
    <nav>
        <a href="a.php" class="b">Ana sayfa</a>
        <a href="menu.php" class="b">Menu</a>
        <a href="bilgi.php" class="b">Bilgi</a>
        <a href="iletisim.php" class="b">İletişim</a>
    </nav>

<div class="icons">
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
         <a href="sepet.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
</div>

<div id="user-btn" class="fas fa-user"></div>
<div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p class="name"><?= $fetch_profile['name']; ?></p>
         <div class="flex">
            <a href="profile.php" class="btn">profile</a>
            <a href="components/user_logout.php" onclick="return confirm('');" class="delete-btn">ÇıkışYap</a>
         </div>
        
         <?php
            }else{
         ?>
            <p class="account"><p class="name">lütfen önce giriş yapın!</p>
            <a href="giris.php">Giriş yap </a> yada
            <a href="kayit.php">Kayıt ol </a>
         </p>  
         <?php
          }
         ?>
</div>

    <div class="nav-buttons">
     <a href="admin/admin_login.php" class="a">  <button>Admin Girişi</button></a> 
    </div>
</header>

<div class="update-profile-container">
    <h2>UPDATE PROFILE</h2>
    
    <?php
    if(isset($message)){
        foreach($message as $msg){
            echo '<div class="message-box">'.$msg.'</div>';
        }
    }
    ?>

    <form action="" method="POST"> 
        <div class="form-group">
            <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" class="box" maxlength="50">
        </div>
        <div class="form-group">
            <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>
        <div class="form-group">
            <input type="number" name="number" placeholder="<?= $fetch_profile['number']; ?>" class="box" min="0" max="9999999999" maxlength="10">
        </div>
        <div class="form-group">
            <input type="password" name="old_pass" placeholder="enter your old password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>
        <div class="form-group">
            <input type="password" name="new_pass" placeholder="enter your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>
        <div class="form-group">
            <input type="password" name="confirm_pass" placeholder="confirm your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>
        <button type="submit" name="submit" class="btn-update">Update Now</button>
    </form>
</div>

</body>
</html>