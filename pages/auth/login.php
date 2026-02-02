<?php
$projectRoot = str_replace("\pages\auth", "", __DIR__);

if (session_status() === PHP_SESSION_NONE) session_start();

require_once $projectRoot . '/database/Database.php';
require_once $projectRoot . '/logic/User.php';

$title = 'Login';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$message = "";
// Check for registration success message
if (isset($_GET['success'])) {
  $message = ["success", "Registration successful! Please login."];
}
// Check for "Password Reset Request" email sent success message
if (isset($_SESSION['message'])) {
  $message = ["success", $_SESSION['message']];
  unset($_SESSION['message']); // Clear the message after displaying it
}

// --- AUTO LOGIN LOGIC (Remember Me) ---
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
  $existingUser = $user->getUserByToken($_COOKIE['remember_me']);
  if ($existingUser) {
    $_SESSION['user_id'] = $existingUser['id'];
    $_SESSION['user_name'] = $existingUser['name'];
    header("Location: dashboard.php?id=" . $_SESSION['user_id'] . "&name=" . $_SESSION['user_name']);
    exit;
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if ($user->login($_POST['email'], $_POST['password'])) {

    // Handle Remember Me
    if (isset($_POST['remember-me'])) {
      $token = bin2hex(random_bytes(16)); // Generate secure random token
      $user->updateRememberToken($_SESSION['user_id'], $token);
      setcookie("remember_me", $token, time() + (86400 * 30), "/"); // Valid for 30 days
    }

    header("Location: ../dashboard.php");
    exit;
  } else {
    $message = ["error", "Invalid email or password."];
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
      Welcome to Interactive Cares
    </h1>

    <?php if (is_array($message)): ?>
      <div class="bg-<?php echo $message[0] === "success" ? "green" : "red"; ?>-100 text-<?php echo $message[0] === "success" ? "green" : "red"; ?>-700 p-2 mb-4 rounded text-sm text-center">
        <?php echo $message[1]; ?>
      </div>
    <?php else: ?>
      <p class="mt-2 text-gray-600">Sign in to your account to continue</p>
    <?php endif; ?>
  </div>

  <div
    class="bg-white glass rounded-2xl shadow-xl p-8 transition-all duration-300 hover:shadow-2xl">
    <form class="space-y-6 animate-fadeIn" method="POST">
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
        <div class="relative">
          <div
            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="w-5 h-5 text-gray-400">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
          </div>
          <input
            type="email"
            name="email"
            required
            class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent input-focus transition-all duration-300"
            placeholder="you@example.com" />
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between mb-2">
          <label class="block text-sm font-semibold text-gray-700">Password</label>
          <a
            href="../auth/forgot-password.php"
            class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors">Forgot password?</a>
        </div>
        <div class="relative">
          <div
            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="w-5 h-5 text-gray-400">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
          </div>
          <input
            type="password"
            name="password"
            required
            class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent input-focus transition-all duration-300"
            placeholder="••••••••" />
        </div>
      </div>

      <div class="flex items-center">
        <input
          id="remember-me"
          name="remember-me"
          type="checkbox"
          class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
        <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
      </div>

      <button
        type="submit"
        class="group relative w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg">
        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-5 h-5 text-indigo-300 group-hover:text-white transition-colors">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
          </svg>
        </span>
        Sign In
      </button>
    </form>
  </div>

  <div class="text-center text-sm text-gray-600">
    <p>
      Don't have an account?
      <a
        href="../auth/signup.php"
        class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">Sign up</a>
    </p>
  </div>
</div>

<?php
$authContent = ob_get_clean();
require_once $projectRoot . '/pages/layouts/auth-layout.php';
?>