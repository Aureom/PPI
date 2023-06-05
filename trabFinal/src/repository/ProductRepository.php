<?php



class ProductRepository {

    private $products = [];

    private $host = 'localhost';
    private $username = 'your_username';
    private $password = 'your_password';
    private $database = 'your_database';
    
    private $connector;
    
    public function __construct() {
        // Inicialize a lista de produtos (pode ser um banco de dados, arquivo, etc.)
        $this->products = [];
        $this->connector = new MySQLConnector($this->host, $this->username, $this->password, $this->database);
    }

    public function findById($id) {
        // Buscar o produto pelo ID no armazenamento (banco de dados, arquivo, etc.)
        foreach ($this->products as $product) {
            if ($product->getId() === $id) {
                return $product;
            }
        }

        return null; // Caso o produto não seja encontrado
    }

    public function findAll() {
        // Retornar todos os produtos no armazenamento
        return $this->products;
    }

    public function save(Product $product) {
        // Salvar o produto no armazenamento (banco de dados, arquivo, etc.)
        $this->products[] = $product;
    }

    public function delete(Product $product) {
        // Remover o produto do armazenamento
        $index = array_search($product, $this->products);
        if ($index !== false) {
            unset($this->products[$index]);
        }
    }
}
