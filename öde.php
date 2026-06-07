<?php

include 'connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:a.php');
   exit();
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      if($address == ''){
         $message[] = 'Lütfen adresinizi ekleyin!';
      }else{
         
         $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
         $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart->execute([$user_id]);

         $message[] = 'sipariş başarıyla verildi!';
      }
      
   }else{
      $message[] = 'sepetiniz boş';
   }

}

?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>XYZ Coffee </title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" type="text/css" href="style.css">

<style>
/* Header Logo Ayarları */
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

/* --- YENİ ÇIKTI /- */

.checkout {
    padding: 40px 20px;
}

/* "ORDER SUMMARY" Büyük Başlığı */
.checkout .title {
    text-align: center;
    font-family: 'Arial', sans-serif;
    font-size: 32px;
    font-weight: bold;
    color: #ffffff; 
    text-transform: uppercase;
    margin-bottom: 25px;
    text-decoration: underline;
    text-underline-offset: 8px;
}

/* Beyaz Ana Form Kutusu */
.checkout form {
    background-color: #ffffff;
    border: 2px solid #222222;
    padding: 30px;
    width: 100%;
    max-width: 480px;
    margin: 0 auto;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

/* Küçük Bölüm Başlıkları */
.checkout form h3 {
    font-family: 'Arial', sans-serif;
    font-size: 22px;
    font-weight: bold;
    color: #222222;
    text-align: left;
    margin-bottom: 15px;
    text-transform: capitalize;
}

/* Sepet Kutusu (Koyu Gri/Siyah Bölüm) */
.checkout form .cart-items {
    background-color: #222222;
    padding: 20px;
    margin-bottom: 25px;
}

.checkout form .cart-items h3 {
    color: #ffffff;
    margin-top: 0;
    margin-bottom: 15px;
}

/* Sepet İçindeki Ürün Satırları */
.checkout form .cart-items p {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 16px;
    margin: 12px 0;
    color: #aaaaaa;
    font-family: 'Arial', sans-serif;
}

/* Ürün Fiyat Renkleri (Sarı) */
.checkout form .cart-items p .price {
    color: #ffcc29;
    font-weight: bold;
}

/* Grand Total Çizgisi */
.checkout form .cart-items .grand-total {
    background-color: #ffffff;
    padding: 10px 15px;
    margin-top: 15px;
    border: 1px solid #ccc;
}

.checkout form .cart-items .grand-total .name {
    color: #222222;
    font-weight: bold;
}

.checkout form .cart-items .grand-total .price {
    color: #e54b3b; 
    font-weight: bold;
}

/* Sarı Küçük Butonlar */
.checkout form .btn-yellow {
    display: inline-block;
    background-color: #ffcc29;
    color: #111111;
    text-decoration: none;
    padding: 8px 18px;
    font-size: 14px;
    font-weight: bold;
    border: none;
    cursor: pointer;
    margin-top: 5px;
    margin-bottom: 20px;
    transition: background 0.2s;
}

.checkout form .btn-yellow:hover {
    background-color: #e5b51d;
}

/* Kullanıcı ve Adres Bilgileri Metinleri */
.checkout form .user-info p {
    font-family: 'Arial', sans-serif;
    font-size: 16px;
    color: #333333;
    text-align: left;
    margin: 12px 0;
    display: flex;
    align-items: center;
}

.checkout form .user-info p i {
    color: #666666;
    margin-right: 12px;
    width: 20px;
    text-align: center;
}

/* Ödeme Yöntemi Seçim Kutusu (Select) */
.checkout form .payment-select {
    width: 100%;
    padding: 12px;
    border: 1.5px solid #333333;
    font-size: 16px;
    color: #333333;
    background-color: #ffffff;
    outline: none;
    margin-top: 15px;
    margin-bottom: 20px;
}

/* Kırmızı Sipariş Tamamlama Butonu */
.checkout form .btn-place-order {
    width: 100%;
    background-color: #e54b3b; 
    color: #ffffff;
    border: none;
    padding: 14px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    text-transform: capitalize;
    transition: background 0.2s;
}

.checkout form .btn-place-order:hover {
    background-color: #c63c2e;
}

.checkout form .btn-place-order.disabled {
    background-color: #cccccc !important;
    color: #666666 !important;
    cursor: not-allowed;
    pointer-events: none;
}

/* YENİ: Bildirim Mesajları Stili (Ekranda görünmesi için yeşil/mavi soft kutu) */
.message-box {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 4px;
    font-family: 'Arial', sans-serif;
    font-size: 15px;
    text-align: center;
    font-weight: bold;
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
        <a href="admin/admin_login.php" class="a"><button>Admin Girişi</button></a> 
    </div>
</header>

<section class="checkout">

   <h1 class="title">sipariş özeti</h1>

   <form action="" method="post">
      
      <?php
      if(isset($message)){
          foreach($message as $msg){
              echo '<div class="message-box">'.$msg.'</div>';
          }
      }
      ?>

      <div class="cart-items">
         <h3>cart items</h3>
         <?php
            $grand_total = 0;
            $cart_items = array(); 
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if($select_cart->rowCount() > 0){
               while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                  $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
                  $total_products = implode($cart_items);
                  $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
         ?>
         <p>
            <span class="name"><?= $fetch_cart['name']; ?></span>
            <span class="price">$<?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?></span>
         </p>
         <?php
               }
            }else{
               echo '<p class="empty">Sepetiniz boş!</p>';
            }
         ?>
         <p class="grand-total">
            <span class="name"> genel toplam :</span>
            <span class="price">$<?= $grand_total; ?></span>
         </p>
         <a href="cart.php" class="btn-yellow">Sepetiniz</a>
      </div>

      <input type="hidden" name="total_products" value="<?= isset($total_products) ? $total_products : ''; ?>">
      <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
      <input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
      <input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
      <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
      <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>">

      <div class="user-info">
         <h3>Bilgileriniz</h3>
         <p><i class="fas fa-user"></i><span><?= $fetch_profile['name'] ?></span></p>
         <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number'] ?></span></p>
         <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?></span></p>
         <a href="profil-güncel.php" class="btn-yellow">Bilgileri Güncelle</a>
         
         <h3> Teslimat Adresi</h3>
         <p><i class="fas fa-map-marker-alt"></i><span><?php if($fetch_profile['address'] == ''){echo 'Lütfen Adres giriniz';}else{echo $fetch_profile['address'];} ?></span></p>
         <a href="adres-güncel.php" class="btn-yellow">Adresi güncelle</a>
         
         <select name="method" class="payment-select" required>
            <option value="" disabled selected>ödeme yöntemini seç --</option>
            <option value="nakit">Kapıda Nakit</option>
            <option value="Kredi kartı">Kapıda kredi kartı</option>
            <option value="3d">3D security</option>
            <option value="paypal">paypal</option>
         </select>
         
         <input type="submit" value="Sipariş Ver" class="btn-place-order <?php if($fetch_profile['address'] == ''){echo 'disabled';} ?>" name="submit">
      </div>

   </form>
   
</section>

</body>
</html>