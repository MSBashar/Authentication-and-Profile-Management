<?php
$projectRoot = str_replace("\pages\auth", "", __DIR__);

require_once $projectRoot . '/database/Database.php';
require_once $projectRoot . '/logic/User.php';

$title = 'Sign Up';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $database = new Database();
  $db = $database->getConnection();
  $user = new User($db);

  $result = $user->register($_POST['name'], $_POST['email'], $_POST['password'], $_POST['confirm_password']);

  if ($result === true) {
    header("Location: login.php?success=1");
    exit;
  } else {
    $message = $result;
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
          d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z" />
      </svg>
    </div>
    <h1
      class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
      Create an account at Interactive Care
    </h1>
    <p class="mt-2 text-gray-600">Join us today to get started</p>

    <?php if ((!empty($message)) && (is_array($message))) {
      echo "<div class='bg-" . ($message[0] === "success" ? "green" : "red") . "-100 text-" . ($message[0] === "success" ? "green" : "red") . "-700 p-2 mb-4 rounded text-sm text-center'>" . $message[1] . "</div>";
    } ?>

  </div>

  <div
    class="bg-white glass rounded-2xl shadow-xl p-8 transition-all duration-300 hover:shadow-2xl">
    <form class="space-y-6 animate-fadeIn" method="POST">
      <div class="grid grid-cols-1 gap-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
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
                  d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
              </svg>
            </div>
            <input
              type="text"
              name="name"
              required
              class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent input-focus transition-all duration-300"
              placeholder="John doe" />
          </div>
        </div>
      </div>

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
                d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
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
        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
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
                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
          </div>
          <input
            type="password"
            name="password"
            required minlength="6"
            class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent input-focus transition-all duration-300"
            placeholder="••••••••" />
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
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
                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
          </div>
          <input
            type="password"
            name="confirm_password"
            required minlength="6"
            class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent input-focus transition-all duration-300"
            placeholder="••••••••" />
        </div>
      </div>

      <div class="flex items-start">
        <div class="flex items-center h-5">
          <input
            id="terms"
            name="terms"
            type="checkbox"
            required
            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
        </div>
        <div class="ml-3 text-sm">
          <label for="terms" class="text-gray-700">I agree to the
            <a
              href="#"
              class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">Terms and Conditions</a>
            and
            <a
              href="#"
              class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">Privacy Policy</a></label>
        </div>
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
              d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 15.75h3m0 0h3m-3 0V12m0 3.75V18" />
          </svg>
        </span>
        Create Account
      </button>
    </form>
  </div>

  <div class="text-center text-sm text-gray-600">
    <p>
      Already have an account?
      <a
        href="../auth/login.php"
        class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">Sign in</a>
    </p>
  </div>
</div>

<?php
$authContent = ob_get_clean();
require_once $projectRoot . '/pages/layouts/auth-layout.php';
?>