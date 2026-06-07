   
<?php

include("../connect.php");


session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');

};

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
        <a href=""class="b">Bilgi</a>
        <a href=""class="b">İletişim</a>
        <a href=""class="b">register</a>
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
<section class="dashboard">

   <h1 class="heading" style="color:whitesmoke;">Gösterge paneli</h1>

   <div class="box-container">

   <div class="box" style="background:none; border-color:whitesmoke ;">
      <h3 style="color:whitesmoke;">HOŞGELDİN!</h3>
      <p><?= $fetch_profile['name']; ?></p>
      <a href="admin-güncelle.php" class="btn">Profili Güncelle</a>
   </div>

   <div class="box" style="background:none; border-color:whitesmoke ;">
      <?php
         $total_pendings = 0;
         $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_pendings->execute(['pending']);
         while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
            $total_pendings += $fetch_pendings['total_price'];
         }
      ?>
      <h3 style="color:whitesmoke;"><span>$</span><?= $total_pendings; ?><span>/-</span></h3>
      <p>Toplam bekleyen siparişler</p>
      <a href="siparis-gör.php" class="btn">Siparişleri gör</a>
 </div>

   

   <div class="box" style="background:none; border-color:whitesmoke ;">
      <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         $numbers_of_products = $select_products->rowCount();
      ?>
      <h3 style="color: whitesmoke;"><?= $numbers_of_products; ?></h3>
      <p>Eklenen ürünler</p>
      <a href="ürünekle.php" class="btn">Ürünleri Gör </a>
   </div>

   <div class="box" style="background:none; border-color:whitesmoke ;">
      <?php
         $select_users = $conn->prepare("SELECT * FROM `users`");
         $select_users->execute();
         $numbers_of_users = $select_users->rowCount();
      ?>
      <h3 style="color:whitesmoke;"><?= $numbers_of_users; ?></h3>
      <p>Sitedeki Kullanıcılar</p>
      <a href="sitekullanici.php" class="btn">Kullanıcıları Gör</a>
   </div>

   <div class="box" style="background:none; border-color:whitesmoke ;">
      <?php
         $select_admins = $conn->prepare("SELECT * FROM `admin`");
         $select_admins->execute();
         $numbers_of_admins = $select_admins->rowCount();
      ?>
      <h3 style="color:whitesmoke;"><?= $numbers_of_admins; ?></h3>
      <p>Adminler</p>
      <a href="siteadmin.php" class="btn">Adminleri gör</a>
   </div>

 

   </div>

</section>




</body>
</html>