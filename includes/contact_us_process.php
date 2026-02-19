<?php
    require_once 'config.php';

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $to_email = "info@idea.com"; // mail del sito

        $name = trim(strip_tags($_POST['name']));
        $user_email = filter_var(trim($_POST['email'], FILTER_SANITIZE_EMAIL));
        $subject_input = trim(strip_tags($_POST['subject']));
        $message_input = trim(strip_tags($_POST['message']));

        if(empty($name) || empty($user_email) || empty($subject_input) || empty($message_input)) {
            header("Location: ../index.php");
            exit();
        }

        $email_subject = "New Contact Form Message: " . $subject_input;

        $email_body =   "You have recieved a new message from your website contact form.\n\n" .
                        "Name: " . $name . "\n" .
                        "Email: " . $user_email . "\n" .
                        "Subject: " . $subject_input . "\n" .
                        "Message: " . $message_input;

        $headers = "From: mailautorizzata@idea.com\r\n";
        $headers .= "Reply-To: $user_email\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Invio mail
        if(!mail($to_email, $email_subject, $email_body, $headers)) {
            $_SESSION['contact_us_error'] = "Failed to send email";
        } else {
            $_SESSION['contact_us_error'] = "";
        }

        header("Location: ../index.php");
        exit();

    } else {
        header("Location: ../index.php");
        exit();
    }
?>
