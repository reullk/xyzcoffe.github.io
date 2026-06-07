
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

<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    

.kutu {
    
    border-radius: 35px;
    position: relative;
    overflow: hidden;
    width: 68px;
    max-width: 50%;
    min-height: 480px;
  min-width:370px;
}

h2 {
  font-size:2rem;
  margin-bottom:1rem;
}
.form-kutu {
  display:flex;
}

.sol-kutu {
  flex:1;
  height:480px;
  background-color:$teal;
}
.sag-kutu {
  display:flex;
  flex:1;
  height:100%;
  background-color: $white;
  justify-content:flex-start; /* center yerine */
  align-items:flex-start;     /* right yerine */
}

.sol-kutu{
  display:flex;
  flex:1;
  height:480px;
  justify-content:center;
  align-items:center;
    color:$white;
}

.sol-kutu  p {
  font-size:0.9rem;
}
.sag-in-kutu {
  width:100%;        /* 70% yerine */
  height:80%;
  text-align:left;   /* right yerine */
  margin-left: 0;    /* emin olmak için */
}

.sol-in-kutu {
  height:50%;
  width:80%;
  text-align:right;
  line-height:22px;
}

input, textarea {
    background-color: #eee;
    border: none;
    padding: 12px 15px;
    margin: 8px 0;
    width: 50%;
  font-size:0.8rem;
  border-radius: 10px;
}

input:focus, textarea:focus{
  outline:1px solid $teal;
}

button {
    border-radius: 20px;
    border: 1px solid brown;
  background: linear-gradient(90deg,#1a0f05,#3b1f0c);
    color: #FFFFFF;
    font-size: 12px;
    font-weight: bold;
    padding: 12px 45px;
    letter-spacing: 1px;
    text-transform: uppercase;
    transition: transform 80ms ease-in;
  cursor:pointer;
}

button:hover {
    transition: all .25s ease-in-out;
  background-color: transparent;
}

@media only screen and (min-width: 600px) {
  .sm {
    display:none;  
  }
}

form p {
  text-align:left;
}







.contact {
  background:  ;
  padding: 80px 0;
  text-align: left;
}

.contact h2 {
  color: #8b5e3c;
  margin-bottom: 30px;
  font-size: 24px;
}

.contact-card {
  width: 500px;
  max-width: 90%;
  margin: left;
  background: none;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  text-align: left;
}

.contact-card h3 {
  color: #8b5e3c;
  font-size: 18px;
  margin-bottom: 25px;
}

.contact-item {
  display: flex;
  gap: 15px;
  margin-bottom: 20px;
}

.contact-item .icon {
  font-size: 18px;
  color: #8b5e3c;
}

.contact-item strong {
  display: block;
  font-size: 14px;
  color: #7a6a5f;
}

.contact-item p {
  font-size: 14px;
  color: #a08f84;
  margin: 3px 0 0;
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


</head>
<body>
<?php  include 'components/user_header.php'; ?>
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

    <div class="nav-buttons">
     <a href="admin/admin_login.php" class="a">  <button>Admin Girişi</button></a> 
        
    </div>
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
</header>
<br>
<section class="contact">

    <div class="sag-kutu">
      <div class="sag-in-kutu">
        <form action="#">
            <h2 >İstek /Öneri/ Şikayet , İletişim Numaraları</h2>
      

           
    
          <input type="text" placeholder="İsim"  />
      <input type="email" placeholder="E-mail *" />
            <input type="number" placeholder="Telefon" />
          <textarea rows="4" placeholder="Mesajınız"></textarea> <br>
           
            <button>Gönder</button>
        </form>
      
      </div> 


  <div class="contact-card">
    <h3>XYZ KAFE RESTORAN<br>İŞLETMELERİ LİMİTED ŞİRKETİ</h3>

    <div class="contact-item">
      <span class="icon">📍</span>
      <div>
        <strong>Adres</strong>
        <p>HALKAPINAR MAH. 1203/1 SK. NO:5 - 7 İÇ KAPI NO: 164 KONAK / İZMİR</p>
      </div>
    </div>

    <div class="contact-item">
      <span class="icon">✉️</span>
      <div>
        <strong>Kep</strong>
        <p>xyzkafe@hs03.kep.tr</p>
      </div>
    </div>

    <div class="contact-item">
      <span class="icon">📄</span>
      <div>
        <strong>Mersis No</strong>
        <p>021136316400001</p>
      </div>
    </div>

    <div class="contact-item">
      <span class="icon">📧</span>
      <div>
        <strong>Email</strong>
        <p>info@xyzcoffeeroastery.com</p>
      </div>
    </div>

  </div>


</section>

</body>
</html>