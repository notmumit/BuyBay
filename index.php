<?php include('layout/header.php'); ?>

      <!-- Home -->
        <section id="home">
            <div class="container">
            <h5>New Arrivals</h5>
            <h1>Best Price</h1>
            <p>BuyBay offers the best products in the most convenient way.</p>
            <a class="nav-link" href="shop.php"> <button>Shop Now</button></a>
            </div>
        </section>

         <!-- Brands -->
            <section id="brand" class="container">
        <div class="row">
        <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/imgs/Brand1.jpg">
        <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/imgs/Brand2.jpg">
        <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/imgs/Brand3.jpg">
        <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/imgs/Brand4.jpg">
         </div>
            </section>

           <!-- New -->
<section id="new" class="w-100">
    <div class="row p-0 m-0">
        <!-- One -->
        <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
            <img class="img-fluid" src="assets/imgs/1.jpg"/>
            <div class="details">
                <h2>Extremely Awesome Shoes</h2>
                <a class="nav-link" href="shop.php"> <button>Shop Now</button></a>
            </div>
        </div>
        <!-- Two -->
        <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
            <img class="img-fluid" src="assets/imgs/2.jpg"/>
            <div class="details">
                <h2>Awesome Jackets</h2>
                <a class="nav-link" href="shop.php"> <button>Shop Now</button></a>>
            </div>
        </div>
        <!-- Three -->
        <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
            <img class="img-fluid" src="assets/imgs/3.jpg"/>
            <div class="details">
                <h2>20% Off</h2>
                <a class="nav-link" href="shop.php"> <button>Shop Now</button></a>
            </div>
        </div>
    </div>
</section>


<!-- Featured Section -->
<section id="featured" class="my-5 pb-5">
    <div class="container text-center mt-5 py-5">
      <h3>Our Featured</h3>
      <hr class="mx-auto">
      <p>Here you can check out our featured products</p>
    </div>
    <div class="row mx-auto container-fluid">


    <?php include('server/get_featured_products.php'); ?>

    <?php while( $row = $featured_products->fetch_assoc()){ ?>



      <div class="product text-center col-lg-3 col-md-4 col-sm-12">
        <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_image']; ?>" />
        <div class="star">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <h5 class="p-name"> <?php echo $row['product_name']; ?></h5>
        <h4 class="p-price">$ <?php echo $row['product_price']; ?></h4>
        <a href="<?php echo "single_product.php?product_id=". $row['product_id']; ?>"><button class="buy-btn">Buy Now</button></a>
      </div>
      
       <?php } ?>
      
    </div>
  </section>
  
  <!-- Banner -->
<section id="banner" class="my-5 py-5">
    <div class="container">
      <h4>MID SEASON'S SALE</h4>
      <h1>Autumn Collection <br> UP to 30% OFF</h1>
      <a class="nav-link" href="shop.php"> <button>Shop Now</button></a>
    </div>
  </section>
   
  <!--clothes-->
  <section id="featured" class="my-5">
    <div class="container text-center mt-5 py-5">
      <h3>Dress & Coat</h3>
      <hr class="mx-auto">
      <p>Here you can check our Clothes</p>
    </div>
    <div class="row mx-auto container-fluid">

    <?php include ('server/get_coats.php'); ?>

    <?php while($row=$coats_products->fetch_assoc()) { ?>
    
      <div class="product text-center col-lg-3 col-md-4 col-sm-12">
        <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_image']; ?>" />
        <div class="star">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
        <h4 class="p-price"><?php echo $row['product_price']; ?></h4>
        <a href="<?php echo "single_product.php?product_id=". $row['product_id']; ?>"><button class="buy-btn">Buy Now</button></a>
      </div>

    <?php } ?>

    </div>
  </section>

  <!-- Watches Section -->
<section id="Watches" class="my-5">
    <div class="container text-center mt-5 py-5">
        <h3>Watches</h3>
        <hr class="mx-auto">
        <p>Check the most Elegant Watches</p>
    </div>
    <div class="row mx-auto container-fluid">

        <?php include('server/get_watches.php'); ?>

        <?php while ($row = $watches_products->fetch_assoc()) { ?>

            <div class="product text-center col-lg-3 col-md-4 col-sm-12">
                <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_image']; ?>" />
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
                <h4 class="p-price">$<?php echo $row['product_price']; ?></h4>
                <a href="<?php echo "single_product.php?product_id=" . $row['product_id']; ?>"><button class="buy-btn">Buy Now</button></a>
            </div>

        <?php } ?>

    </div>
</section>

<!-- Sneakers Section -->
<section id="Sneakers" class="my-5">
    <div class="container text-center mt-5 py-5">
        <h3>Sneakers</h3>
        <hr class="mx-auto">
        <p>Check the most Stylish and Comfortable Sneakers</p>
    </div>
    <div class="row mx-auto container-fluid">

        <?php include('server/get_sneakers.php'); ?>

        <?php while ($row = $sneakers_products->fetch_assoc()) { ?>

            <div class="product text-center col-lg-3 col-md-4 col-sm-12">
                <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_image']; ?>" />
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
                <h4 class="p-price">$<?php echo $row['product_price']; ?></h4>
                <a href="<?php echo "single_product.php?product_id=" . $row['product_id']; ?>"><button class="buy-btn">Buy Now</button></a>
            </div>

        <?php } ?>

    </div>
</section>


 <!-- Footer -->
 <?php include('layout/footer.php'); ?>