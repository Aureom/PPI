<?php
/*
https://en.wikiquote.org/wiki/Rasmus_Lerdorf

PHP 8 is significantly better because it contains a lot less of my code.

I did not develop the PHP we know today. Dozens, if not hundreds of people, developed PHP. I was simply the first developer.

I actually hate programming, but I love solving problems.

I really don't like programming. I built this tool to program less so that I could just reuse code.

PHP is about as exciting as your toothbrush. You use it every day, it does the job, it is a simple tool, so what? Who would want to read about toothbrushes?

I was really, really bad at writing parsers. I still am really bad at writing parsers.

We have things like protected properties. We have abstract methods. We have all this stuff that your computer science teacher told you you should be using. I don't care about this crap at all.

There are people who actually like programming. I don't understand why they like programming.

I'm not a real programmer. I throw together things until it works then I move on. The real programmers will say "Yeah it works but you're leaking memory everywhere. Perhaps we should fix that." I’ll just restart Apache every 10 requests.

I do care about memory leaks but I still don't find programming enjoyable.

I don't know how to stop it, there was never any intent to write a programming language [...] I have absolutely no idea how to write a programming language, I just kept adding the next logical step on the way.

For all the folks getting excited about my quotes. Here is another - Yes, I am a terrible coder, but I am probably still better than you :)

I've never thought of PHP as more than a simple tool to solve problems.

PHP is rarely the bottleneck.

Ugly problems often require ugly solutions. Solving an ugly problem in a pure manner is bloody hard.

PHP is just a hammer. Nobody has ever gotten rich making hammers.

Back when PHP had less than 100 functions and the function hashing mechanism was strlen().
*/
include __DIR__ . "/../components/header/header.php";
include __DIR__ . "/../model/Product.php";
include __DIR__ . "/../database/MySQLConnector.php";
include __DIR__ . "/../repository/ProductRepository.php";
include __DIR__ . "/../service/ProductService.php";

$mysqlConnector = new MySQLConnector();
$productRepository = new ProductRepository($mysqlConnector);
$productService = new ProductService($productRepository);
$products = $productService->findRecentProducts();
?>

    <head>
        <title>Mercado Fácil</title>
        <link rel="stylesheet" href="/trabFinal/src/style/home.style.css">
    </head>

    <main class="main-container">
        <dialog class="dialog-interest">
            <div class="dialog-content">
                <form action="/trabFinal/src/usecases/product.php/interest" method="post" class="interest-form">
                    <input type="hidden" name="product_id" id="product_id">
                    <div class="form-group">
                        <label for="message">Por que você tem interesse neste produto?</label>
                        <textarea name="message" id="message" cols="30" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="phone">Telefone de contato:</label>
                        <input type="tel" name="phone" id="phone" placeholder="Telefone para contato" required>
                    </div>
                    <div class="form-group">
                        <button class="interest-button" type="submit">Enviar</button>
                    </div>
                </form>
            </div>
        </dialog>
        <div class="product-list">
            <?php foreach ($products as $product) { ?>
                <div class="product-card">
                    <?php
                    if ($product->hasImage()) {
                        echo "<img class='product-image' src='{$product->getMainImage()}' alt='Product Image'>";
                    } else {
                        echo "<img class='product-image' src='https://placehold.jp/150x150.png' alt='Product Image'>";
                    }
                    ?>
                    <div class="product-info" id="<?php echo $product->getId(); ?>">
                        <h3 class="product-title"><?php echo $product->getTitle(); ?></h3>
                        <p class="product-description"><?php echo $product->getDescription(); ?></p>
                        <p class="product-price"><?php echo $product->getFormattedPrice(); ?></p>
                        <button class="product-button">Demostrar interesse</button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>

    <script>
        let page = 1;
        let loading = false;
        let finished = false;
        const productContainer = document.querySelector('.product-list');

        const loadProducts = async () => {
            if (loading || finished) {
                return;
            }

            loading = true;
            const response = await fetch(`/trabFinal/src/usecases/product.php/list?page=${page}`);
            const products = await response.json();

            if (products.length === 0) {
                finished = true;
                return;
            }

            products.forEach(product => {
                const productCard = document.createElement('div');
                productCard.classList.add('product-card');

                const productImage = document.createElement('img');
                productImage.classList.add('product-image');
                if (product.images.length === 0) {
                    productImage.src = 'https://placehold.jp/150x150.png';
                } else {
                    productImage.src = product.images[0];
                }
                productImage.alt = 'Product Image';

                const productInfo = document.createElement('div');
                productInfo.classList.add('product-info');
                productInfo.id = product.id;

                const productTitle = document.createElement('h3');
                productTitle.classList.add('product-title');
                productTitle.innerText = product.title;

                const productDescription = document.createElement('p');
                productDescription.classList.add('product-description');
                productDescription.innerText = product.description;

                const productPrice = document.createElement('p');
                productPrice.classList.add('product-price');
                productPrice.innerText = "R$ " + (product.price / 100).toFixed(2).replace(".", ",");


                const productButton = document.createElement('button');
                productButton.classList.add('product-button');
                productButton.innerText = 'Demostrar interesse';

                productInfo.appendChild(productTitle);
                productInfo.appendChild(productDescription);
                productInfo.appendChild(productPrice);
                productInfo.appendChild(productButton);

                productCard.appendChild(productImage);
                productCard.appendChild(productInfo);

                productContainer.appendChild(productCard);
            });

            page++;
            loading = false;
        }

        window.addEventListener('scroll', () => {
            const {scrollTop, scrollHeight, clientHeight} = document.documentElement;

            if (scrollTop + clientHeight >= scrollHeight - 5) {
                loadProducts();
            }
        });

        const buttons = document.querySelectorAll('.product-button');
        const dialog = document.querySelector('.dialog-interest');
        const productId = document.querySelector('#product_id');
        const message = document.querySelector('#message');
        const phone = document.querySelector('#phone');
        const interestForm = document.querySelector('.interest-form');

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                productId.value = button.parentElement.id;
                dialog.showModal();
            });
        });

        interestForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const data = new FormData();
            data.append('product_id', productId.value);
            data.append('message', message.value);
            data.append('phone', phone.value);

            fetch('/trabFinal/src/usecases/product.php/interest', {
                method: 'POST',
                body: data
            }).then(response => {
                if (response.status === 201) {
                    dialog.close();
                    alert('Sua mensagem foi enviada ao vendedor!');
                } else {
                    alert('Erro ao enviar mensagem. Tente novamente mais tarde.');
                }
            });
        });


        loadProducts();
    </script>

<?php
include __DIR__ . "/../components/footer/footer.php";
?>