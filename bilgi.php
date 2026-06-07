
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
<html>
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" type="text/css" href="style.css">
  <style>.about {
  background: ;
  padding: 80px 0;
}

.about-container {
  width: 85%;
  margin: auto;
  display: flex;
  align-items: center;
  gap: 60px;
}

.about-image img {
  width: 100%;
  max-width: 500px;
  border-radius: 10px;
}

.about-content {
  max-width: 500px;
}

.about-content h2 {
  font-size: 40px;
  color: #8b5e3c;
  margin-bottom: 20px;
  line-height: 1.3;
}

.about-content p {
  font-size: 15px;
  color: #7a6a5f;
  line-height: 1.7;
  margin-bottom: 15px;
}

.btn {
  display: inline-block;
  background: #8b5e3c;
  color: #fff;
  padding: 12px 25px;
  border-radius: 5px;
  text-decoration: none;
  transition: 0.3s;
}

.btn:hover {
  background: #6f472d;
}




.features {
  background: ;
  padding: 70px 0;
  text-align: center;
}

.features-container {
  width: 85%;
  margin: auto;
  display: flex;
  justify-content: space-between;
  gap: 30px;
  flex-wrap: wrap;
}

.feature-box {
  flex: 1;
  min-width: 220px;
  max-width: 250px;
  margin: auto;
}

.feature-box img {
  width: 50px;
  margin-bottom: 15px;
  opacity: 0.8;
}

.feature-box h3 {
  font-size: 16px;
  color: #8b5e3c;
  margin-bottom: 10px;
  letter-spacing: 1px;
}

.feature-box p {
  font-size: 13px;
  color: #7a6a5f;
  line-height: 1.6;
}

.navbar-brand-logo {
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
    

}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

   <title></title>
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
 <div class="nav-buttons">
     <a href="admin/admin_login.php" class="a">  <button>Admin Girişi</button></a> 
        
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
            <a href="profile.php" class="btnn">profile</a>
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
</header>

<br> <br> <br> <br> <br> 

<section class="about">
  <div class="about-container">
    
    <!-- Sol taraf (görsel) -->
    <div class="about-image">
      <img src="kafegörsel.png" alt="XYZ Coffee">
    </div>

    <!-- Sağ taraf (yazı) -->
    <div class="about-content">
      <h2>XYZ Coffee<br>Hakkımızda</h2>

      <p>
        XYZ Coffee, antik Mısır konsepti ve özenle hazırlanan kahveleriyle
        2024 yılında kurulmuştur. Misafirlerimize sadece kahve sunmakla
        kalmayıp, her yudumda eşsiz bir atmosfer ve deneyim yaşatmayı hedefliyoruz.
      </p>

      <p>
        Kaliteli kahve çekirdekleri ve özel tariflerle hazırlanan içeceklerimiz,
        damaklarda iz bırakırken modern ve zarif dekorasyonumuzla
        misafirlerimize keyifli bir mola sunuyor.
      </p>

      <a href="#" class="btn">Ailemize Katıl</a>
    </div>

  </div>
</section>
<section class="features">
  <div class="features-container">

    <div class="feature-box">
      <img src="coffee_9954891.png" alt="">
      <h3>KAHVE</h3>
      <p>XYZ, her yudumda eşsiz bir deneyim sunan kahve lezzetleriyle fark yaratıyor.</p>
    </div>

    <div class="feature-box">
      <img src="rice_8717383.png" alt="">
      <h3>LEZZET</h3>
      <p>XYZ, her lokmada unutulmaz bir lezzet sunan özel tatlılarıyla damaklarda iz bırakıyor.</p>
    </div>

    <div class="feature-box">
      <img src="table_2470384.png" alt="">
      <h3>KONSEPT</h3>
      <p>Şubelerimiz hem göz zevkinize hitap edecek hem de ruhunuzu dinlendirecek şekilde tasarlandı.</p>
    </div>

   

  </div>
</section>
<center><footer>Bu Site Niğde Bor Meslek Yüksekokulu öğrencileri : Resul Kuşcu , Erdem Akkoyun , Mehmet Can Gündüz , Helin Gültekin tarafından hazırlanmştır.</footer></center>
</body>
</html>