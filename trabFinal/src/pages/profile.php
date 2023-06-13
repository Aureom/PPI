<?php
require_once __DIR__ . "/../components/header/header.php";
require_once __DIR__ . "/../model/User.php";
require_once __DIR__ . "/../model/Product.php";
require_once __DIR__ . "/../model/ProductPhoto.php";
require_once __DIR__ . "/../security/Auth.php";
require_once __DIR__ . "/../service/ProductService.php";
require_once __DIR__ . "/../repository/ProductRepository.php";
require_once __DIR__ . "/../database/MySQLConnector.php";

if (!Auth::check()) {
    header("Location: /trabFinal/src/pages/login.php");
    exit;
}

$user = Auth::user();

$mysqlConnector = new MySQLConnector();
$productRepository = new ProductRepository($mysqlConnector);
$productService = new ProductService($productRepository);
?>

<head>
    <title>Mercado Fácil | Perfil</title>
    <link rel="stylesheet" href="/trabFinal/src/style/profile.style.css">
</head>

<body>
<div class="profile-container">
    <div class="profile-info">
        <h3 class="profile-title">Perfil</h3>
        <p class="profile-text">Nome: <?php echo $user->getName(); ?></p>
        <p class="profile-text">Email: <?php echo $user->getEmail(); ?></p>
        <p class="profile-text">CPF: <?php echo $user->getCpf(); ?></p>
        <p class="profile-text">Telefone: <?php echo $user->getPhone(); ?></p>
        <a class="profile-edit-button" href="/trabFinal/src/pages/editProfile.php">Editar perfil</a>
    </div>
    <div class="profile-products">
        <h3 class="profile-title">Produtos cadastrados</h3>
        <div class="product-list">
            <?php
            $products = $productService->getProductsForLoggedInUser();
            if (empty($products)) {
                echo "<p class='profile-text'>Você ainda não cadastrou nenhum produto :(</p>";
                echo "<a class='profile-link' href='/trabFinal/src/pages/createProduct.php'>Cadastrar produto</a>";
            } else {
                foreach ($products as $product) { ?>
                    <div class="product-card">
                        <?php
                        if ($product->hasImage()) {
                            echo "<img class='product-image' src='{$product->getMainImage()}' alt='Product Image'>";
                        } else {
                            echo "<img class='product-image' src='https://placehold.jp/150x150.png' alt='Product Image'>";
                        }
                        ?>
                        <div class="product-info">
                            <h3 class="product-title"><?php echo $product->getTitle(); ?></h3>
                            <p class="product-description"><?php echo $product->getDescription(); ?></p>
                            <p class="product-price"><?php echo $product->getFormattedPrice(); ?></p>
                            <button class="product-button" id="<?php echo $product->getId(); ?>">Excluir</button>
                            <?php
                            if ($product->hasInterest()) {
                                echo "<button class='product-button-interest' id='{$product->getId()}'>Interessados</button>";
//                               Dialog to show interested users
                                echo <<<HTML
                                        <dialog id="dialog-{$product->getId()}" class="dialog">
                                            <div class="dialog-container">
                                                <div class="dialog-header">
                                                    <h3 class="dialog-title">Interessados</h3>
                                                    <button class="dialog-close-button">X</button>
                                                </div>
                                                <div class="dialog-body">
                                                    <ul class="dialog-list">
                                        HTML;
                                foreach ($product->getInterests() as $interest) {
                                    echo "<li class='dialog-list-item'>{$interest->getMessage()} - {$interest->getContact()}</li>";
                                }
                                echo <<<HTML
                                                    </ul>
                                                </div>
                                            </div>
                                        </dialog>
                                        HTML;

                            } else {
                                echo "<p class='product-text'>Nenhum interessado</p>";
                            } ?>
                        </div>
                    </div>
                    <?php
                }
            } ?>
        </div>
    </div>
</div>
</body>

<script>
    const buttons = document.querySelectorAll('.product-button');
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const productId = button.id;
            const url = `/trabFinal/src/usecases/product.php/delete`;
            // /delete?id=7
            fetch(`${url}?id=${productId}`, {
                method: 'DELETE'
            }).then(() => {
                window.location.reload();
            })
        })
    })

    const interestButtons = document.querySelectorAll('.product-button-interest');
    interestButtons.forEach(button => {
        button.addEventListener('click', () => {
            const productId = button.id;
            const dialog = document.querySelector(`#dialog-${productId}`);
            dialog.showModal();
            const closeButton = dialog.querySelector('.dialog-close-button');
            closeButton.addEventListener('click', () => {
                dialog.close();
            })
        })
    })
</script>


<?php
require_once __DIR__ . "/../components/footer/footer.php";
?>
