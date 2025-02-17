<?php
class Feedback {
    private $conn;
    private $table_name = "feedbacks";

    public $id;
    public $user_id;
    public $user_email;
    public $feedback;
    public $rating;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    function addFeedback() {
        $query = "INSERT INTO " . $this->table_name . " (user_id, user_email, feedback, rating) VALUES (:user_id, :user_email, :feedback, :rating)";
        $stmt = $this->conn->prepare($query);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->user_email = htmlspecialchars(strip_tags($this->user_email));
        $this->feedback = htmlspecialchars(strip_tags($this->feedback));
        $this->rating = htmlspecialchars(strip_tags($this->rating));

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':user_email', $this->user_email);
        $stmt->bindParam(':feedback', $this->feedback);
        $stmt->bindParam(':rating', $this->rating);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "<pre>";
            print_r($stmt->errorInfo());
            echo "</pre>";
            return false;
        }
    }

    function viewFeedbacks() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function deleteFeedback() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function fetchFeedbackDetails() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->user_id = $row['user_id'];
            $this->user_email = $row['user_email'];
            $this->feedback = $row['feedback'];
            $this->rating = $row['rating'];
            $this->created_at = $row['created_at'];

            return $row;
        }
        return null;
    }
}
?>
