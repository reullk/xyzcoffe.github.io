<?php

include("../connect.php");

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit(); 
}

$message = []; 


if(isset($_POST['update'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, price = ? WHERE id = ?");
   $update_product->execute([$name, $category, $price, $pid]);

   $message[] = 'Ürün başarıyla güncellendi!';

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'Görüntü boyutu çok büyük (Max 2MB)!';
      }else{
         $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);
         move_uploaded_file($image_tmp_name, $image_folder);
         
      
         if(file_exists('../uploaded_img/'.$old_image) && !empty($old_image)){
            unlink('../uploaded_img/'.$old_image);
         }
         $message[] = 'Fotoğraf başarıyla güncellendi!';
      }
   }
}

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XYZ Coffee - Ürün Güncelle</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <link rel="stylesheet" type="text/css" href="../adminpanel_style.css">
</head>
<body>

<?php
// Sistemden gelen bildirim mesajlarını ekranda gösterir // erdem
if(!empty($message)){
   foreach($message as $msg){
      echo '
      <div class="message" style="background-color: #4CAF50; color: white; padding: 10px; text-align: center; margin-bottom: 15px;">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();" style="cursor:pointer; float:right;"></i>
      </div>
      ';
   }
}
?>

<header>
    <div class="logo">XYZ</div>
    <nav>
        <a href="yönetici.php" class="b">Gösterge paneli</a>
        <a href="ürünekle.php" class="b">Ürün Ekle</a>
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
             <p> Hoş Geldiniz : <?= $fetch_profile['name'] ?? 'Yönetici'; ?></p>
             <a href="update_profile.php" class="">Profili Güncelle</a>
             <div class="flex-btn">
                <a href="admin_login.php" class="option-btn" style="background: green;">Giriş yap</a>
                <a href="register_admin.php" class="option-btn" style="background: green;">Kayıt ol tekrardan</a>
             </div>
             <a href="../components/admin_logout.php" onclick="return confirm('Başarıyla çıkış yaptınız');" class="delete-btn">Çıkış Yap</a>
        </div>
    </section>
</header>

<section class="update-product">
   <h1 class="heading" style="color: white; text-align: center; margin-bottom: 20px;">Ürünü Güncelle</h1>

   <?php
      // URL'den gelen ID'yi alıyoz / Helin
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $show_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
         $show_products->execute([$update_id]);
         if($show_products->rowCount() > 0){
            while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
      
      <div style="text-align: center; margin-bottom: 15px;">
         <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="" style="max-width: 200px; height: auto; border-radius: 10px;">
      </div>

      <span>İsmi Güncelle</span>
      <input type="text" required placeholder="Ürün adını girin" name="name" maxlength="100" class="box" value="<?= $fetch_products['name']; ?>">
      
      <span>Fiyatı Güncelle</span>
      <input type="number" min="0" max="9999999" required placeholder="Fiyatı Girin" name="price" class="box" value="<?= $fetch_products['price']; ?>">
      
      <span>Kategoriyi Güncelle</span>
      <select name="category" class="box" required>
         
         <option value="drinks">Sıcak / Soğuk İçecek</option>
         <option value="mocktail">Mocktail</option>
         <option value="desserts">Tatlı</option>
         <option value="fast food">Fast Food</option>
      </select>
      
      <span>Resmi Güncelle</span>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      
      <div class="flex-btn">
         <input type="submit" value="Güncelle" class="btn" name="update">
         <a href="ürünekle.php" class="option-btn">Geri Dön</a>
      </div>
   </form>
   <?php
            }
         }else{
            echo '<p class="empty" style="color:white; text-align:center;">Güncellenecek ürün bulunamadı!</p>';
         }
      } else {
         echo '<p class="empty" style="color:white; text-align:center;">Geçersiz işlem. Lütfen ürün listesinden bir ürün seçin.</p>';
      }
   ?>
</section>

</body>
</html>