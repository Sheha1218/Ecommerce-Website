<?php
include_once 'Database.php';
include_once 'Feedback.php';

$database = new Database();
$db = $database->getConnection();

$feedback = new Feedback($db);

$stmt = $feedback->viewFeedbacks();
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = '';
$alertClass = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['feedback_id'])) {
        $feedback->id = $_POST['feedback_id'];
        
        if ($feedback->deleteFeedback()) {
            $message = "Feedback deleted successfully.";
            $alertClass = "alert-success";
            
            $stmt = $feedback->viewFeedbacks();
            $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $message = "Unable to delete feedback.";
            $alertClass = "alert-danger";
        }
    }
}

if (isset($_GET['fetch_feedback']) && isset($_GET['id'])) {
    $feedback->id = $_GET['id'];
    $details = $feedback->fetchFeedbackDetails();
    echo json_encode($details);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Feedback</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="css/admin_styles.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="container">
        <?php if (!empty($message)): ?>
        <div class="alert <?php echo $alertClass; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>

        <h2>Delete Feedback</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="feedback-id">Select Feedback ID to Delete:</label>
                <select name="feedback_id" id="feedback-id" class="form-control">
                    <option value="">Select a feedback</option>
                    <?php foreach ($feedbacks as $feedback): ?>
                        <option value="<?php echo htmlspecialchars($feedback['id']); ?>"><?php echo htmlspecialchars($feedback['id']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="feedback-user-id">User ID:</label>
                <input type="text" id="feedback-user-id" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="feedback-user-email">User Email:</label>
                <input type="text" id="feedback-user-email" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="feedback-content">Feedback:</label>
                <textarea id="feedback-content" class="form-control" rows="3" readonly></textarea>
            </div>

            <div class="form-group">
                <label for="feedback-rating">Rating:</label>
                <input type="text" id="feedback-rating" class="form-control" readonly>
            </div>

            <button type="submit" class="btn btn-danger">Delete Feedback</button><br><br>
        </form>
        
        <a href="view_feedbacks.php" class="btn btn-primary">Back to Feedback Details</a><br><br>
    </div>

    <script>
        $(document).ready(function() {
            $('#feedback-id').change(function() {
                var feedbackId = $(this).val();
                if (feedbackId) {
                    $.ajax({
                        url: '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>',
                        type: 'GET',
                        data: {
                            fetch_feedback: 1,
                            id: feedbackId
                        },
                        success: function(data) {
                            var feedback = JSON.parse(data);
                            $('#feedback-user-id').val(feedback.user_id);
                            $('#feedback-user-email').val(feedback.user_email);
                            $('#feedback-content').val(feedback.feedback);
                            $('#feedback-rating').val(feedback.rating);
                        }
                    });
                } else {
                    $('#feedback-user-id').val('');
                    $('#feedback-user-email').val('');
                    $('#feedback-content').val('');
                    $('#feedback-rating').val('');
                }
            });
        });
    </script>
</body>
</html>
