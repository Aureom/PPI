<?php
require_once __DIR__ . "/../components/header/header.php";
require_once __DIR__ . "/../database/MySQLConnector.php";
require_once __DIR__ . "/../repository/CategoryRepository.php";
require_once __DIR__ . "/../model/Category.php";
require_once __DIR__ . "/../service/CategoryService.php";

$user = Auth::user();

if ($user === null) {
    header("Location: /trabFinal/src/pages/login.php");
    exit;
}

$categoryRepository = new CategoryRepository(new MySQLConnector());
$categoryService = new CategoryService($categoryRepository);
?>

    <head>
        <title>Mercado Fácil | Cadastrar produto</title>
        <link rel="stylesheet" href="/trabFinal/src/style/createProduct.style.css">
    </head>

    <body>
    <div class="create-product-container">
        <h3 class="create-product-title">Cadastrar produto</h3>
        <form class="create-product-form" action="/trabFinal/src/usecases/product.php/create" method="POST"
              enctype="multipart/form-data">
            <div class="form-group">
                <label for="title" class="form-label">Título:</label>
                <input type="text" id="title" name="title" class="form-input" placeholder="Digite o nome do produto"
                       required>
            </div>
            <div class="form-group">
                <label for="description" class="form-label">Descrição:</label>
                <textarea id="description" name="description" class="form-input"
                          placeholder="Digite a descrição do produto" required></textarea>
            </div>
            <div class="form-group">
                <label for="price" class="form-label">Preço:</label>
                <input type="number" id="price" name="price" class="form-input" placeholder="Digite o preço do produto"
                       min="1" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="zipCode" class="form-label">CEP:</label>
                <input type="number" id="zipCode" name="zipCode" class="form-input"
                       placeholder="Digite o CEP onde o produto está localizado"
                       min="10000000" max="99999999" required>
            </div>
            <div class="form-group">
                <label for="state" class="form-label">Estado:</label>
                <select id="state" name="state" class="form-input" required>
                    <option value="" selected disabled hidden>Selecione o estado onde o produto está localizado</option>
                    <option value="AC">Acre</option>
                    <option value="AL">Alagoas</option>
                    <option value="AM">Amazonas</option>
                    <option value="AP">Amapá</option>
                    <option value="BA">Bahia</option>
                    <option value="CE">Ceará</option>
                    <option value="DF">Distrito Federal</option>
                    <option value="ES">Espirito Santo</option>
                    <option value="GO">Goiás</option>
                    <option value="MA">Maranhão</option>
                    <option value="MS">Mato Grosso do Sul</option>
                    <option value="MT">Mato Grosso</option>
                    <option value="MG">Minas Gerais</option>
                    <option value="PA">Pará</option>
                    <option value="PB">Paraíba</option>
                    <option value="PR">Paraná</option>
                    <option value="PE">Pernambuco</option>
                    <option value="PI">Piauí</option>
                    <option value="RJ">Rio de Janeiro</option>
                    <option value="RN">Rio Grande do Norte</option>
                    <option value="RS">Rio Grande do Sul</option>
                    <option value="RO">Rondônia</option>
                    <option value="RR">Roraima</option>
                    <option value="SC">Santa Catarina</option>
                    <option value="SE">Sergipe</option>
                    <option value="SP">São Paulo</option>
                    <option value="TO">Tocantins</option>
                </select>
            </div>
            <div class="form-group">
                <label for="city" class="form-label">Cidade:</label>
                <input type="text" id="city" name="city" class="form-input"
                       placeholder="Digite o nome da cidade onde o produto está localizado"
                       required>
            </div>
            <div class="form-group">
                <label for="neighborhood" class="form-label">Bairro:</label>
                <input type="text" id="neighborhood" name="neighborhood" class="form-input"
                       placeholder="Digite o nome do bairro onde o produto está localizado"
                       required>
            </div>
            <div class="form-group">
                <label for="categoryId" class="form-label">Categoria:</label>
                <select id="categoryId" name="categoryId" class="form-input" required>
                    <option value="" selected disabled hidden>Selecione a categoria do produto</option>
                    <?php
                    $categories = $categoryService->findAll();
                    foreach ($categories as $category) {
                        echo "<option value='{$category->getId()}'>{$category->getName()}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="images" class="form-label">Imagem:</label>
                <input type="file" id="images" name="images" class="form-input" multiple>
            </div>
            <div class="form-group">
                <input type="submit" value="Cadastrar" class="form-button">
            </div>
        </form>
    </div>
    </body>

<script>
    const form = document.querySelector(".create-product-form");
    const title = document.querySelector("#title");
    const description = document.querySelector("#description");
    const price = document.querySelector("#price");
    const zipCode = document.querySelector("#zipCode");
    const state = document.querySelector("#state");
    const city = document.querySelector("#city");
    const neighborhood = document.querySelector("#neighborhood");
    const categoryId = document.querySelector("#categoryId");
    const images = document.querySelector("#images");

    form.addEventListener("submit", (event) => {
        event.preventDefault();

        const formData = new FormData();
        formData.append("title", title.value);
        formData.append("description", description.value);
        formData.append("price", price.value);
        formData.append("zipCode", zipCode.value);
        formData.append("state", state.value);
        formData.append("city", city.value);
        formData.append("neighborhood", neighborhood.value);
        formData.append("categoryId", categoryId.value);
        formData.append("images", images.files);

        fetch("/trabFinal/src/usecases/product.php/create", {
            method: "POST",
            body: formData
        }).then(response => {
            if (response.status === 201) {
                alert("Produto cadastrado com sucesso!");
                window.location.href = "/trabFinal/src/pages/home.php";
            } else {
                alert("Erro ao cadastrar produto!");
            }
        });
    });
</script>
<?php
include __DIR__ . "/../components/footer/footer.php";
?>