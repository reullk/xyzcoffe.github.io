
<?php

include 'connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>XYZ Coffee </title>
<style>.navbar-brand-logo {
    display: inline-flex;
    align-items: center;
    gap: 10px; /* Logo ile XYZ yazısı arasındaki boşluk */
    text-decoration: none;
    color: #ffffff; 
    font-weight: bold;
}

.nav-logo-img {
    height: 250px; 
    width: auto;  
    object-fit: contain;
    

}</style>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<header>
  <a href="a.php" class="navbar-brand-logo">
    <img src="XYZ.png" alt="XYZ Coffee Logo" class="nav-logo-img">
  <div class="logo">XYZ</div>
</a>
    
    <nav>
        <a href="a.php" class="b">Ana sayfa</a>
        <a href="menu.php"class="b">Menu</a>
        <a href="bilgi.php"class="b">Bilgi</a>
        <a href="iletisim.php"class="b">İletişim</a>
      
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

<section class="hero">
    <div class="hero-text">
        <h1>ZYZ COFFEE</h1>
        <h2>Firmamız Self servistir</h2>
        <p>XYZ, her lokmada unutulmaz bir lezzet sunan özel tatlılarıyla damaklarda iz bırakıyor.</p>
      <a href="menu.php">  <button>Menü için tıklayın.</button></a>
    </div>

    <div class="hero-img">
        <img src="ChatGPT Image 3 Mar 2026 20_46_22.png" alt="coffee">
    </div>
</section>
<center><footer>Bu Site Niğde Bor Meslek Yüksekokulu öğrencileri : Resul Kuşcu , Erdem Akkoyun , Mehmet Can Gündüz , Helin Gültekin tarafından hazırlanmştır.</footer></center>
</body>
</html>