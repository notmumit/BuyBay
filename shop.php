<?php
include('server/connection.php');

// Determine page number
$page_no = isset($_GET['page_no']) && $_GET['page_no'] != "" ? $_GET['page_no'] : 1;
$total_records_per_page = 8;
$offset = ($page_no - 1) * $total_records_per_page;

// Initialize variables
$category = isset($_POST['category']) ? $_POST['category'] : (isset($_GET['category']) ? $_GET['category'] : 'all');
$price = isset($_POST['price']) ? $_POST['price'] : (isset($_GET['price']) ? $_GET['price'] : 1000);
$search = isset($_POST['Search']) || isset($_GET['Search']) ? true : false;

// Count total records
if ($search) {
    if ($category === 'all') {
        $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products WHERE product_price < ?");
        $stmt1->bind_param('i', $price);
    } else {
        $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products WHERE product_category = ? AND product_price < ?");
        $stmt1->bind_param('si', $category, $price);
    }
} else {
    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products");
}

$stmt1->execute();
$stmt1->bind_result($total_records);
$stmt1->store_result();
$stmt1->fetch();
$total_no_of_pages = ceil($total_records / $total_records_per_page);

// Fetch products
if ($search) {
    if ($category === 'all') {
        $stmt2 = $conn->prepare("SELECT * FROM products WHERE product_price < ? LIMIT ?, ?");
        $stmt2->bind_param('iii', $price, $offset, $total_records_per_page);
    } else {
        $stmt2 = $conn->prepare("SELECT * FROM products WHERE product_category = ? AND product_price < ? LIMIT ?, ?");
        $stmt2->bind_param('siii', $category, $price, $offset, $total_records_per_page);
    }
} else {
    $stmt2 = $conn->prepare("SELECT * FROM products LIMIT ?, ?");
    $stmt2->bind_param('ii', $offset, $total_records_per_page);
}

$stmt2->execute();
$products = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.1/css/all.css">
    <link rel="stylesheet" href="assets/css/style.css"/>
    <style>
        .product .img {
            width: 100%;
            height: auto;
            box-sizing: border-box;
            object-fit: cover;
        }

        .pagination a {
            color: coral;
        }

        .pagination li:hover a {
            color: #fff;
            background-color: coral;
        }

        .search-section {
            width: 200px;
            background-color: #f8f9fa;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .shop-content {
            flex: 1;
            padding: 20px;
        }

        .container-flex {
            display: flex;
        }

        .search-button {
            background-color: coral;
            border: none;
        }

        .search-button:hover {
            background-color: orange;
        }

        @media (max-width: 992px) {
            .shop-content .product {
                flex: 1 1 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 768px) {
            .shop-content .product {
                flex: 1 1 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
<?php include('layout/header.php'); ?>

<div class="container-flex">
    <!-- Search Section -->
    <section id="search" class="search-section my-5 py-5">
        <div class="container mt-5 py-5">
            <h4>Search Products</h4>
        </div>
        <form action="shop.php" method="POST">
            <div class="row mx-auto container">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <p>Category</p>
                    <div class="form-check">
                        <input class="form-check-input" value="sneakers" type="radio" name="category" id="category_one" <?php if (isset($category) && $category=='sneakers') { echo 'checked'; } ?>>
                        <label class="form-check-label" for="category_one">Shoes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" value="coats" type="radio" name="category" id="category_two" <?php if (isset($category) && $category=='coats') { echo 'checked'; } ?>>
                        <label class="form-check-label" for="category_two">Coats</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" value="watches" type="radio" name="category" id="category_three" <?php if (isset($category) && $category=='watches') { echo 'checked'; } ?>>
                        <label class="form-check-label" for="category_three">Watches</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" value="all" type="radio" name="category" id="category_all" <?php if (isset($category) && $category=='all') { echo 'checked'; } ?>>
                        <label class="form-check-label" for="category_all">All</label>
                    </div>
                </div>
            </div>
            <div class="row mx-auto container mt-5">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <p>Price</p>
                    <input type="range" class="form-range w-100" name="price" value="<?php echo isset($price) ? $price : "100"; ?>" min="1" max="1000" id="customRange2">
                    <div class="w-100">
                        <span style="float: left;">1</span>
                        <span style="float: right;">1000</span>
                    </div>
                </div>
            </div>
            <div class="form-group my-3 mx-3">
                <input type="submit" name="Search" value="Search" class="search-button w-100" />
            </div>
        </form>
    </section>

    <!-- Shop Section -->
    <section id="featured" class="shop-content my-5 py-5">
        <div class="container mt-5 py-5">
            <h3>Our Products</h3>
            <hr>
            <p>Here you can check out our products</p>
            <div class="row mx-auto">
                <?php while($row = $products->fetch_assoc()){ ?>
                <div onclick="window.location.href='single_product.php?product_id=<?php echo $row['product_id']; ?>';" class="product text-center col-lg-3 col-md-4 col-sm-12">
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

            <!-- Pagination -->
            <nav aria-label="Page navigation example" class="mx-auto">
                <ul class="pagination mt-5 mx-auto">
                    <li class="page-item <?php if($page_no <= 1) { echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($page_no <= 1) { echo '#'; } else { echo "?page_no=".($page_no-1)."&category=".$category."&price=".$price."&Search="; } ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_no_of_pages; $i++) { ?>
                        <li class="page-item <?php if ($page_no == $i) { echo 'active'; } ?>">
                            <a class="page-link" href="shop.php?page_no=<?php echo $i; ?>&category=<?php echo $category; ?>&price=<?php echo $price; ?>&Search="><?php echo $i; ?></a>
                        </li>
                    <?php } ?>
                    <li class="page-item <?php if($page_no >= $total_no_of_pages) { echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($page_no >= $total_no_of_pages) { echo '#'; } else { echo "?page_no=".($page_no+1)."&category=".$category."&price=".$price."&Search="; } ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-wcw6J6FrSb9dovWsDtvJooQv8KHNShLvhgj3S2aiUZubOeVyFl1ZKPOot1+NxI6E" crossorigin="anonymous"></script>
</body>
</html>

<?php include('layout/footer.php'); ?>
