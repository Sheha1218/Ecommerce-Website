<?php
class Order {
    private $conn;
    private $table_name = "orders";

    public $id;
    public $user_id;
    public $product_id;
    public $cart_id;
    public $user_name;
    public $user_email;
    public $product_name;
    public $product_price;
    public $quantity;
    public $total_price;

    public function __construct($db) {
        $this->conn = $db;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  (user_id, product_id, cart_id, user_name, user_email, product_name, product_price, quantity, total_price)
                  VALUES (:user_id, :product_id, :cart_id, :user_name, :user_email, :product_name, :product_price, :quantity, :total_price)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':product_id', $this->product_id);
        $stmt->bindParam(':cart_id', $this->cart_id);
        $stmt->bindParam(':user_name', $this->user_name);
        $stmt->bindParam(':user_email', $this->user_email);
        $stmt->bindParam(':product_name', $this->product_name);
        $stmt->bindParam(':product_price', $this->product_price);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':total_price', $this->total_price);

        if ($stmt->execute()) {
            return true;
        } else {
            print_r($stmt->errorInfo());
            return false;
        }
    }

    function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function getOrderById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function isOrderPlaced() {
        $query = "SELECT COUNT(*) as order_count FROM " . $this->table_name . " WHERE cart_id = :cart_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cart_id', $this->cart_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['order_count'] > 0;
    }

    function fetchOrderDetails() {
        $query = "SELECT user_name, user_email, product_name, product_price, quantity, total_price FROM " . $this->table_name . " WHERE name = :name LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getOrdersByUserId() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        return $stmt;
    }
    
    function deleteOrderByUser() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $this->user_id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

}
?>
