<?php
declare(strict_types = 1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Middleware\SessionMiddleware;
use Slim\Views\Twig;
use PDO;

class ProductController {
    private PDO $db;
    private Twig $view;
    private array $token;

    public function __construct(PDO $db, Twig $view, array $token) {
        $this->db = $db;
        $this->view = $view;
        $this->token = $token;
    }

    public function getAll(Request $request, Response $response, $args) {
        $stmt = $this->db->query('SELECT * FROM products');
        $products = $stmt->fetchAll();

        $response->getBody()->write(json_encode(['products' => $products]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getProductByName(Request $request, Response $response, $args) {
        $name = $args['name'] ?? null;

        error_log("Product name : " . $name);

        if (!$name) {
            $response->getBody()->write(json_encode(['error' => 'Product not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $sql = 'SELECT * FROM products WHERE `name` = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name]);

        $product = $stmt->fetch();

        $response->getBody()->write(json_encode(['product' => $product]));
        return $response->withHeader('Content-Type', 'application/json');
        
    }

    public function getByCategory(Request $request, Response $response, $args) {
        $queryParams = $request->getQueryParams();
        $category = $queryParams['category'] ?? null;
        $sortOrder = $queryParams['order'] ?? null;

        if (!$category) {
            $response->getbody()->write(json_encode(['error' => 'Category not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
        

        $sql = 'SELECT * FROM products WHERE category = ?';

        if ($sortOrder) {
            $sql .= ' ORDER BY ' . $sortOrder;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$category]);
        $products = $stmt->fetchAll();

        $response->getBody()->write(json_encode(['products' => $products]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function create(Request $request, Response $response, $args) {
        /*print_r($this->token);
        if ($this->token['userId'] == NULL) {
            return $this->view->render($response->withStatus(401), 'error_not_found.twig', ['message' => 'User is not authenticated!']);
        }*/

        $data = $request->getParsedBody();

        error_log('Received data:');
        error_log(var_export($data, true));

        if ($data === null) {
            $response->getBody()->write(json_encode(['error' => 'Error! No product data received!']));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');  
        }
    
        $name = $data['name'];
        $category = $data['category'];
        $photoUrl = $data['photoUrl'];
        $quantity = floatval($data['quantity']);
        $description = $data['description'];
        $price = floatVal($data['price']);
        $discount = floatVal($data['discount']) ?? 0;

        

        $stmt = $this->db->prepare('SELECT * FROM products WHERE name = ?');
        $stmt->execute([$name]);
        $existingProduct = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingProduct) {
           $response->getBody()->write(json_encode(['error' => 'Product already exists']));
           return $response->withHeader('Content-Type', 'application/json');
        }
    
        $stmt = $this->db->prepare('INSERT INTO products (`name`, category, photoUrl, quantity, `description`, price, discount) VALUES (?, ?, ?, ?, ?, ?, ?)');
        
        $stmt->execute([$name, $category, $photoUrl, $quantity, $description, $price, $discount]);
    
        $response->getBody()->write(json_encode(['message' => 'Product created successfully']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function update(Request $request, Response $response, $args) {
        $productId = $args['productId'];
        $data = $request->getParsedBody();
    
        error_log('Received data:');
        error_log(var_export($data, true));
    
        $stmt = $this->db->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$product) {
            $response->getBody()->write(json_encode(['error' => 'Product not found']));
            return $response->withHeader('Content-Type', 'application/json');
        } 
    
        $stmt = $this->db->prepare('UPDATE products SET `name` = ?, category = ?, quantity = ?, `description` = ?, price = ?, photoUrl = ?, discount = ? WHERE id = ?');
        
        if (!$stmt->execute([
            $data['name'], 
            $data['category'], 
            $data['quantity'], 
            $data['description'], 
            $data['price'], 
            $data['photoUrl'], 
            $data['discount'], 
            $productId
        ])) {
            $response->getBody()->write(json_encode(['error' => 'Error at updating product']));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    
        $response->getBody()->write(json_encode(['message' => 'Product updated successfully']));
        return $response->withAddedHeader('Content-Type', 'application/json');
    }
        

    public function delete(Request $request, Response $response, $args) {
        $productId = $args['productId'];

        $stmt = $this->db->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$productId]);

        $response->getBody()->write(json_encode(['message' => 'Product deleted']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>