<?php
$projectRoot = str_replace("\pages\auth", "", __DIR__);

require_once $projectRoot . '/database/Database.php';
require_once $projectRoot . '/logic/User.php';
require_once $projectRoot . '/helper/Validator.php';

require_once $projectRoot . '/helper/SendMail.php';

$title = 'Forgot Password';

// Check if the connection is secure (HTTPS)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
// Get the host name (e.g., www.example.com)
$host = $_SERVER['HTTP_HOST'];
// Get the request URI (e.g., /path/to/page.php?param=value)
// $request_uri = $_SERVER['REQUEST_URI'];
// Assemble the full URL
$domain = $protocol . "://" . $host;


$message = "";
$resetLink = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = (new Database())->getConnection();
    $user = new User($db);

    $token = bin2hex(random_bytes(20));
    $user->createPasswordReset($_POST['email'], $token);

    // Simulate sending email
    $resetLink = $domain . "/pages/auth/reset-password.php?token=" . $token . "&email=" . $_POST['email'];

    $mail = new SendMail();

    $to = $_POST['email'];
    $subject = "Password Reset Request";
    $emailMessage = "Hello! You have requested a password reset for your Interactive Cares account. Please click the link below to reset your password: <a href='$resetLink'>Reset Password</a> <br>This link will expire in 1 hour.<br><br>If you did not request this, please ignore this email.";

    $emailValidated = Validator::email($to);

    if ($emailValidated === true) {
        $result = $mail->send($to, $subject, $emailMessage);

        if ($result === true) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['message'] = "The 'Password Reset' link has been sent to your email!<br>Please check your inbox.";
            header("Location: login.php");
            exit;
        } else {
            $message = "Mail delivery failed. Please try again later.";
        }
    } else {
        $message = $emailValidated;
    }
}


ob_start();
?>

<!-- body Section -->
<div class="max-w-md w-full space-y-8">
    <div class="text-center animate-float">
        <div
            class="mx-auto bg-gradient-to-r from-indigo-500 to-purple-600 w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg mb-4">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
                class="w-6 h-6 text-white">
                <path
                    fill-rule="evenodd"
                    d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z"
                    clip-rule="evenodd" />
            </svg>
        </div>
        <h1
            class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
            Interactive Cares
        </h1>
        <h2 class="text-xl font-bold mb-4">Reset Password</h2>

        <?php if (is_array($message)): ?>
            <div class="bg-<?php echo $message[0] === "success" ? "green" : "red"; ?>-100 text-<?php echo $message[0] === "success" ? "green" : "red"; ?>-700 p-2 mb-4 rounded text-sm text-center">
                <?php echo $message[1]; ?>
            </div>
        <?php endif; ?>
    </div>

    <div
        class="bg-white glass rounded-2xl shadow-xl p-8 transition-all duration-300 hover:shadow-2xl">
        <form class="space-y-6 animate-fadeIn" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required class="w-full px-3 py-2 border rounded mb-4">
            <button class="bg-blue-600 text-white px-4 py-2 rounded w-full">Get Reset Link</button>

            <!-- <?php // if ($resetLink): 
                    ?>
                <div class="mt-4 p-2 bg-yellow-100 text-xs break-all">
                    <p class="font-bold">Simulated Email Sent!</p>
                    <a href="<?php //echo $resetLink; 
                                ?>" class="text-blue-600 underline">Click here to reset password</a>
                </div>
            <?php //endif; 
            ?> -->
        </form>
    </div>

</div>

<?php
$authContent = ob_get_clean();
require_once $projectRoot . '/pages/layouts/auth-layout.php';
?>