   
<?php

include("../connect.php");


session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_admin = $conn->prepare("DELETE FROM `admin` WHERE id = ?");
   $delete_admin->execute([$delete_id]);
   header('location:admin_accounts.php');
}


?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>XYZ Coffee </title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="../style.css">
<link rel="stylesheet" type="text/css" href="../adminpanel_style.css">
</head>
<body>

<header>
    <div class="logo">XYZ</div>
    <nav>
        <a href="yönetici.php" class="b">Gösterge paneli</a>
        <a href="ürünekle.php"class="b">Ürün Ekle </a>
        <a href="yönetici.php"class="b">ana-sayfa</a>
        
       
    </nav>
    <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>
<section>
 <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p> Hoş Geldiniz : <?= $fetch_profile['name']; ?></p>
         <a href="update_profile.php" class="">Profili Güncelle</a>
         <div class="flex-btn">
            <a href="admin_login.php" class="option-btn" style="background: green;">Giriş yap </a>
            <a href="register_admin.php" class="option-btn"  style="background: green;"> Kayıt ol tekrardan</a>
         </div>
         <a href="../components/admin_logout.php" onclick="return confirm('Başarıyla çıkış yaptınız');" class="delete-btn">Çıkış Yap</a>
      </div>

</section>
</header>
<br>
<section class="accounts">

   <h1 class="heading">Admin hesapları</h1>

   <div class="box-container">

   <div class="box">
      <p>Yeni Admin ekle</p>
      <a href="admin-güncelle.php" class="option-btn">Kayıt</a>
   </div>

   <?php
      $select_account = $conn->prepare("SELECT * FROM `admin`");
      $select_account->execute();
      if($select_account->rowCount() > 0){
         while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <p> admin id : <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> Kullanıcı Adı : <span><?= $fetch_accounts['name']; ?></span> </p>
      <div class="flex-btn">
         <a href="siteadmin.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Hesap Silinsin mi?');">Sil</a>
         <?php
            if($fetch_accounts['id'] == $admin_id){
               echo '<a href="admin-güncelle.php" class="option-btn">Güncelle</a>';
            }
         ?>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">hesap mevcut değil</p>';
   }
   ?>

   </div>

</section>




</body>
</html>