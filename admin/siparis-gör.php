   
<?php

include("../connect.php");


session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');

};

if(isset($_POST['update_payment'])){

   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_status->execute([$payment_status, $order_id]);
   $message[] = 'payment status updated!';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:siparis-gör.php');
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
<section class="placed-orders">

   <h1 class="heading">verilen siparişler</h1>

   <div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>Kullanıcı id : <span><?= $fetch_orders['user_id']; ?></span> </p>
      <p> Siparis Tarihi:: <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> isim : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> mail : <span><?= $fetch_orders['email']; ?></span> </p>
      <p> numara : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> adres : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> toplam ürünler : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p>toplam fiyat : <span>$<?= $fetch_orders['total_price']; ?>/-</span> </p>
      <p> ödeme yöntemi : <span><?= $fetch_orders['method']; ?></span> </p>
      <form action="" method="POST">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="payment_status" class="drop-down">
            <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
            <option value="Yolda">sipariş yolda</option>
            <option value="Teslim Edildi">Teslim Edili</option>
         </select>
         <div class="flex-btn">
            <input type="submit" value="Güncelle" class="btn" name="update_payment">
            <a href="siparis-gör.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('bu sipariş silinsin mi?');">Sil</a>
         </div>
      </form>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">Henüz sipariş verilmedi!</p>';
   }
   ?>

   </div>

</section>




</body>
</html>