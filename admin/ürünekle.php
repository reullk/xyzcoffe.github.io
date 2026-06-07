
<?php

include("../connect.php");


session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');

};


$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'Ürün adı zaten mevcut';
   }else{
      if($image_size > 2000000){
         $message[] = '
Görüntü boyutu çok büyük.!!';
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);

         $insert_product = $conn->prepare("INSERT INTO `products`(name, category, price, image) VALUES(?,?,?,?)");
         $insert_product->execute([$name, $category, $price, $image]);

         $message[] = 'yeni ürün eklendi';
      }

   }

}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:ürünekle.php');

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
        <a href="ürünekle.php"class="b">Ürün EKle </a>
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


<section class="add-products" style="background:">

   <form  style=" border:none ;
   background:none ;" action="" method="POST" enctype="multipart/form-data">
      <h3>Ürün Ekle </h3>
      <input type="text" required placeholder="Ürün adını girin" name="name" maxlength="100" class="box">
      <input type="number" min="0" max="9999999999" required placeholder="Ürün fiyatını girin" name="price" onkeypress="if(this.value.length == 10) return false;" class="box">
      <select name="category" class="box" required>
         <option value="" disabled selected>Kategori Seçin --</option>
         <option value="main dish">Mocktail</option>
         <option value="fast food">Soğuk İçicek</option>
         <option value="drinks">Sıcak içicek</option>
         <option value="desserts">Tatlı</option>
      </select>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
      <input type="submit" value="EKLE" name="add_product" class="btn">
   </form>

</section>

<section class="show-products" style="padding-top: 0;">

   <div class="box-container">

   <?php
      $show_products = $conn->prepare("SELECT * FROM `products`");
      $show_products->execute();
      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="flex">
         <div class="price"><span>TL</span><?= $fetch_products['price']; ?><span>/-</span></div>
         <div class="category"><?= $fetch_products['category']; ?></div>
      </div>
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="flex-btn">
         <a href="ürün-güncelle.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Güncelle</a>
         <a href="ürünekle.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="Geri dön confirm('Bu Ürün Silinsin mi  ?');">Sil</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty"> Henüz hiçbir ürün eklenmedi!</p>';
      }
   ?>

   </div>

</section>

</body>
</html>