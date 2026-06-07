<?php

include 'connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:a.php');
};

if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
   $message[] = 'sepetten ürün kaldırıldı!
';
}

if(isset($_POST['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   // header('location:cart.php');
   $message[] = 'Sepetteki tüm ürünler kaldırıldı!
';
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $message[] = 'sepet miktarı güncellendi';
}

$grand_total = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sepetin</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
<style>
   .products .box-container{
   display: grid;
   grid-template-columns: repeat(auto-fit, 33rem);
   justify-content: center;
   align-items: flex-start;
   gap:1.5rem;
}

.products .box-container .box{
   border:var(--border);
   padding:1.5rem;
   position: relative;
   overflow: hidden;
}

.products .box-container .box img{
   height: 25rem;
   width: 100%;
   object-fit: contain;
   margin-bottom: 1rem;
}

.products .box-container .box .fa-eye,
.products .box-container .box .fa-shopping-cart{
   position: absolute;
   top:1rem;
   height: 4.5rem;
   width: 4.5rem;
   line-height: 4.3rem;
   border:var(--border);
   background-color: var(--white);
   cursor: pointer;
   font-size: 2rem;
   color:var(--black);
   transition: .2s linear;
   text-align: center;
}

.products .box-container .box .fa-eye:hover,
.products .box-container .box .fa-shopping-cart:hover{
   background-color: var(--black);
   color:var(--white);
}

.products .box-container .box .fa-eye{
   left: -10rem;
}

.products .box-container .box .fa-shopping-cart{
   right: -10rem;
}

.products .box-container .box:hover .fa-eye{
   left: 1rem;
}

.products .box-container .box:hover .fa-shopping-cart{
   right: 1rem;
}

.products .box-container .box .cat{
   font-size: 1.8rem;
   color:var(--light-color);
}

.products .box-container .box .cat:hover{
   color:var(--black);
   text-decoration: underline;
}

.products .box-container .box .name{
   font-size: 2rem;
   color:var(--black);
   margin:1rem 0;
}

.products .box-container .box .flex{
   display: flex;
   align-items: center;
   gap:1rem;
   margin-top: 1.5rem;
}

.products .box-container .box .flex .price{
   margin-right: auto;
   font-size: 2.5rem;
   color:var(--black);
}

.products .box-container .box .flex .price span{
   color:var(--light-color);
   font-size: 1.8rem;
}

.products .box-container .box .flex .qty{
   padding:1rem;
   border:var(--border);
   font-size: 1.8rem;
   color:var(--black);
}

.products .box-container .box .flex .fa-edit{
   width: 5rem;
   background-color: var(--yellow);
   color:var(--black);
   cursor: pointer;
   font-size: 1.8rem;
   height: 4.5rem;
   border:var(--border);
}

.products .box-container .box .flex .fa-edit:hover{
   background-color: var(--black);
   color:var(--white);
}

.products .box-container .box .fa-times{
   position: absolute;
   top:1rem; right:1rem;
   background-color: var(--red);
   color:var(--white);
   border:var(--border);
   line-height:4rem;
   height: 4.3rem;
   width: 4.5rem;
   cursor: pointer;
   font-size: 2rem;
}

.products .box-container .box .fa-times:hover{
   background-color: var(--black);
   color:var(--white);
}

.products .box-container .box .sub-total{
   margin-top: 1rem;
   font-size: 1.8rem;
   color:var(--light-color);
}

.products .box-container .box .sub-total span{
   color:var(--red);
}

.products .more-btn{
   margin-top: 1rem;
   text-align: center;
}

.products .cart-total{
   display: flex;
   align-items: center;
   gap:1.5rem;
   flex-wrap: wrap;
   justify-content: space-between;
   margin-top: 3rem;
   border:var(--border);
   padding:1rem;
}

.products .cart-total p{
   font-size: 2.5rem;
   color:var(--light-color);
}

.products .cart-total p span{
   color:var(--red);
}

.products .cart-total .btn{
   margin-top: 0;
}
</style>
</head>
<body>
   
<header><div class="logo">XYZ</div>
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
   

<!-- shopping cart section starts  -->

<section class="products">
<br>
    <h1 class="title"> SEPETİN:</h1>

   <div class="box-container">

      <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
         
         <button type="submit" class="fas fa-times" name="delete" onclick="return confirm('bu öğe silinsin mi?');"></button>
         <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
         <div class="name"><?= $fetch_cart['name']; ?></div>
         <div class="flex">
            <div class="price"><span>$</span><?= $fetch_cart['price']; ?></div>
            <input type="number" name="qty" style="color: black;" class="qty" min="1" max="99" value="<?= $fetch_cart['quantity']; ?>" maxlength="2">
            <button type="submit" class="fas fa-edit" name="update_qty"></button>
         </div>
         <div class="sub-total"> Genel Toplam : <span>$<?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
      </form>
      <?php
               $grand_total += $sub_total;
            }
         }else{
            echo '<p class="empty">sepetiniz boş</p>';
         }
      ?>

   </div>
 
   <div class="cart-total">
      <p>Sepet Tutarı : <span>$<?= $grand_total; ?></span></p>
      <a href="öde.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Ödeme yapmak için tıklayın</a>
   </div>

   <div class="more-btn">
      <form action="" method="post">
         <button type="submit" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" name="delete_all" onclick="return confirm('Sepetteki tüm ürünleri silinsin mi?');">Sepetteki tüm ürünleri silinsin mi?</button>
      </form>
      <a href="menu.php" class="btn">Alışverişe devam edin</a>
   </div>

</section>

<!-- shopping cart section ends -->















<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>