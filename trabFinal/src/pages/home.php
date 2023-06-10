<?php
include __DIR__ . "/../components/header/header.php";
include __DIR__ . "/../model/Product.php";

$products = [];
for ($i = 0; $i < 12; $i++) {
    $products[] = Product::mock();
}
?>

    <head>
        <title>Mercado Fácil</title>
        <link rel="stylesheet" href="/trabFinal/src/style/home.style.css">
    </head>

    <main class="main-container">
        <div class="product-list">
            <?php foreach ($products as $product) { ?>
                <div class="product-card">
                    <img class="product-image" src="<?php echo $product->getImageUrl(); ?>" alt="Product Image">
                    <div class="product-info">
                        <h3 class="product-title"><?php echo $product->getName(); ?></h3>
                        <p class="product-description"><?php echo $product->getDescription(); ?></p>
                        <p class="product-price"><?php echo $product->getFormattedPrice(); ?></p>
                        <button class="product-button">Add to Cart</button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>


<?php
include __DIR__ . "/../components/footer/footer.php";
?>