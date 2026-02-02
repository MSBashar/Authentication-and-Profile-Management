<?php

$projectRoot = str_replace("\helper", "", __DIR__);

// -- Preventing direct access and redirecting to the login page --
// Get the request URI (e.g., /path/to/page.php?param=value)
$uri = trim(dirname($_SERVER['REQUEST_URI']));
if ($uri == '/helper' || $uri == '/helper/') {
    require_once $projectRoot . '/helper/prevent-direct-access.php';
}


class SendMail {
    private $fromEmail = "noreply@interactivecares.com"; // Change to your domain
    private $fromName = "Interactive Cares";

    /**
     * Send a basic HTML email
     */
    public function send($to, $subject, $message) {
        // 1. Set Headers for HTML Email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        
        // 2. Set From Header
        $headers .= "From: " . $this->fromName . " <" . $this->fromEmail . ">" . "\r\n";

        // 3. Simple HTML Template wrapper
        $htmlMessage = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; padding: 20px;'>
                <h2 style='color: #4f46e5;'>$subject</h2>
                <p>$message</p>
                <hr style='border: 0; border-top: 1px solid #eee;'>
                <p style='font-size: 12px; color: #888;'>This is an automated message from Interactive Cares.</p>
            </div>
        </body>
        </html>
        ";

        // 4. Execute Mail
        if (mail($to, $subject, $htmlMessage, $headers)) {
            return true;
        } else {
            return false;
        }
    }
}