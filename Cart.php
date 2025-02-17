<?php
class Cart {
    private $conn;
    private $table_name = "cart";

    public $id;
    public $product_id;
    public $product_name;
    public $product_price;
    public $user_id;
    public $quantity;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addProduct() {
        $query = "INSERT INTO " . $this->table_name . " (product_id, product_name, product_price, user_id, quantity) VALUES (:product_id, :product_name, :product_price, :user_id, :quantity)";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':product_id', $this->product_id);
        $stmt->bindParam(':product_name', $this->product_name);
        $stmt->bindParam(':product_price', $this->product_price);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(":quantity", $this->quantity);

        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    public function deleteProduct() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id', $this->user_id);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "<pre>";
            print_r($stmt->errorInfo());
            echo "</pre>";
            return false;
        }
    }

    public function getCartItems() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();

        return $stmt;
    }

    public function getCartItemById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addCartItem() {
        $query = "INSERT INTO " . $this->table_name . " (user_id, product_id, product_name, product_price, quantity) VALUES (:user_id, :product_id, :product_name, :product_price, :quantity)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':product_id', $this->product_id);
        $stmt->bindParam(':product_name', $this->product_name);
        $stmt->bindParam(':product_price', $this->product_price);
        $stmt->bindParam(':quantity', $this->quantity);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function deleteCartItem() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

}
?>
