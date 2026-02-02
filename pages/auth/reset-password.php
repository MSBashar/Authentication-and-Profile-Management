<?php
$projectRoot = str_replace("\pages\auth", "", __DIR__);

require_once $projectRoot . '/database/Database.php';
require_once $projectRoot . '/logic/User.php';
require_once $projectRoot . '/helper/Validator.php';

$title = 'Reset Password';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$email = $_GET['email'] ?? '';
$token = $_GET['token'] ?? '';
$error = "";
$success = "";

// 1. Initial Validation: Check if token is valid before showing the form
$tokenData = $user->verifyResetToken($email, $token);

if (!$tokenData) {
    $error = "Invalid or expired reset link. Please request a new one.";
}

// 2. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && $tokenData) {
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate Inputs
    $passwordCheck = Validator::password($newPassword);

    if ($passwordCheck !== true) {
        $error = $passwordCheck;
    } elseif ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Update password and clean up the token
        if ($user->resetPassword($email, $newPassword)) {
            $user->deleteResetToken($email);
            $success = "Password reset successful! Redirecting to login...";
            header("refresh:3;url=../auth/login.php");
        } else {
            $error = "Something went wrong. Please try again.";
        }
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
        <h2 class="text-xl font-bold mb-4">New Password</h2>
    </div>

    <div
        class="bg-white glass rounded-2xl shadow-xl p-8 transition-all duration-300 hover:shadow-2xl">
        
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded text-sm text-center">
                <?php echo $error; ?>
            </div>
            <?php if (!$tokenData): ?>
                <a href="./forgot-password.php" class="block text-center text-blue-500 underline text-sm">Request new link</a>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-100 text-green-700 p-3 mb-4 rounded text-sm text-center">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if ($tokenData && !$success): ?>
            <form class="space-y-6 animate-fadeIn" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">New Password</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Confirm New Password</label>
                    <input type="password" name="confirm_password" required class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <button type="submit" class="w-full bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition">
                    Reset Password
                </button>
            </form>
        <?php endif; ?>
        
    </div>

</div>

<?php
$authContent = ob_get_clean();
require_once $projectRoot . '/pages/layouts/auth-layout.php';
?>