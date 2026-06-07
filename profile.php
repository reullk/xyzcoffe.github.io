
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

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>/* Ana Konteynır */
.user-details {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 5rem 2rem;
    min-height: 80vh; /* Sayfada dikeyde ortalı durması için */
}

/* Profil Kartı */
.user-details .user {
    background-color: none;
    color: whitesmoke;
    border: 1px solid white;
    border-radius: .5rem;
    padding: 2rem;
    width: 45rem; /* Kart genişliği */
    text-align: left; /* Metinler sola dayalı */
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
}

/* Profil Resmi (İkonu) */
.user-details .user .profile-img {
    height: 15rem; 
    width: 15rem;
    object-fit: contain;
    margin-bottom: 2rem;
    display: block;
    margin-left: auto;
    margin-right: auto;
}

/* Bilgi Satırları */
.user-details .user p {
    font-size: 1.8rem;
    color: #333;
    margin-bottom: 1.5rem;  color: whitesmoke;
    line-height: 1.5;
    display: flex;
    align-items: center;
}

/* İkonlar */
.user-details .user p i {
    margin-right: 1.5rem;  color: whitesmoke;
    color: #666;
    font-size: 2rem;
    width: 2.5rem;
    text-align: center;
}

/* Buton Tasarımı */
.user-details .user .btn {
    display: block;
    width: fit-content;
    margin: 1rem 0 2.5rem 0;
    background-color: darkgreen;
    color: #fff;
    font-size: 1.7rem;
    padding: 1rem 3rem;
    cursor: pointer;
    text-transform: capitalize;
    text-decoration: none;
    transition: .2s linear;
}

.user-details .user .btn:hover {
    background-color: #704712;
    color: #fff;
}

/* Adres kısmı için özel boşluk */
.user-details .user .address {
    margin-top: 1rem;
    border-top: 1px solid #eee;
    padding-top: 1.5rem;
}</style>
<link rel="stylesheet" type="text/css" href="style.css">
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

<section class="user-details">
   <div class="user">
      <img src="user-icon.png" alt="" class="profile-img">
      
      <p><i class="fas fa-user"></i><span><?= $fetch_profile['name']; ?></span></p>
      <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number']; ?></span></p>
      <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email']; ?></span></p>
      
      <a href="profil-güncel.php" class="btn">Bilgilerinizi güncellemek için tıklayın</a>

      <p class="address">
         <i class="fas fa-map-marker-alt"></i>
         <span><?php if($fetch_profile['address'] == ''){echo 'Lütfen adres ekleyin!';}else{echo $fetch_profile['address'];} ?></span>
      </p>
      
      <a href="adres-güncel.php" class="btn">Adresiniz güncelleyin</a>
   </div>
</section>




</body>
</html>