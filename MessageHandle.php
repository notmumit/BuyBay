<?php
class MessageHandler {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Method to insert a new message into the database
    public function submitMessage($user_id, $user_name, $user_email, $user_message) {
        // Sanitize inputs
        $user_name = $this->conn->real_escape_string($user_name);
        $user_email = $this->conn->real_escape_string($user_email);
        $user_message = $this->conn->real_escape_string($user_message);

        // Prepare SQL statement
        $sql = "INSERT INTO message (user_id, user_name, user_email, user_message) 
                VALUES (?, ?, ?, ?)";

        // Prepare the statement and bind the parameters
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param('isss', $user_id, $user_name, $user_email, $user_message);
            
            // Execute the statement
            if ($stmt->execute()) {
                return "<p style='color:green;'>Your message has been submitted successfully!</p>";
            } else {
                return "<p style='color:red;'>There was an error submitting your message. Please try again.</p>";
            }
        } else {
            // Error if SQL statement cannot be prepared
            return "<p style='color:red;'>Error preparing the query: " . $this->conn->error . "</p>";
        }
    }
}
?>
