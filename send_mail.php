<?php
// Check that the request was sent via POST (form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Your email address (where the messages should be sent)
    $to = 'ralf.hoenich@gmail.com';

    // Read form values safely
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $phone   = trim($_POST['phone']   ?? '');
    $message = trim($_POST['message'] ?? '');

    // Basic validation
    $error = '';

    if ($name === '' || $email === '' || $message === '') {
        $error = 'Please fill in all required fields (name, e-mail, message).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid e-mail address.';
    }

    if ($error !== '') {
        // Show a simple error page
        echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form error</title>
</head>
<body>
    <p>' . htmlspecialchars($error) . '</p>
    <p><a href="index.html">Back to the contact form</a></p>
</body>
</html>';
        exit;
    }

    // Prevent header injection in e-mail headers
    $safe_email = str_replace(array("\r", "\n", "%0a", "%0d"), '', $email);

    // Subject of the email you receive
    $subject = 'New contact message from website';

    // Build the e-mail body
    $body  = "You have received a new message from the contact form on your website:\n\n";
    $body .= "Name:   " . $name . "\n";
    $body .= "E-mail: " . $email . "\n";
    $body .= "Phone:  " . $phone . "\n\n";
    $body .= "Message:\n" . $message . "\n";

    // E-mail headers
    // IMPORTANT: replace no-reply@your-domain.com with a valid address of your domain
    $headers  = "From: Website Contact Form <no-reply@your-domain.com>\r\n";
    $headers .= "Reply-To: " . $safe_email . "\r\n";

    // Try to send the e-mail
    if (mail($to, $subject, $body, $headers)) {
        // Simple thank you page
        echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Message sent</title>
</head>
<body>
    <p>Thank you, your message has been sent successfully.</p>
    <p><a href="index.html">Back to the website</a></p>
</body>
</html>';
    } else {
        // Error sending e-mail
        echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
</head>
<body>
    <p>Sorry, there was a problem sending your message. Please try again later.</p>
    <p><a href="index.html">Back to the contact form</a></p>
</body>
</html>';
    }
} else {
    // If someone opens this file directly in the browser, redirect to the form
    header('Location: index.html');
    exit;
}
