<?php
require_once '../init.php';

$title = 'Change Password';
$subTitle = 'Ensure your account is secure';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$userData = $user->getUser($_SESSION['user_id']);

$message = "";

// Handle Password Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = ["error", "Passwords do not match."];
    } else {
        $result = $user->updatePassword($_SESSION['user_id'], $current_password, $password, $confirm_password);
        if ($result === true) {
            $message = ["success", "Password updated successfully!"];
        } else {
            $message = $result; // Use the error message returned by updatePassword
        }
    }
}


ob_start();
?>

<!-- Profile Section -->
    <div class="max-w-4xl">
      <div class="bg-white rounded-xl shadow overflow-hidden">
        <div
          class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2"
        ></div>
        <div class="p-6">

          <?php if (is_array($message)) { echo "<div class='bg-" . ($message[0] === "success" ? "green" : "red") . "-100 text-" . ($message[0] === "success" ? "green" : "red") . "-700 p-2 mb-4 rounded text-sm text-center'>" . $message[1] . "</div>"; } ?>

          <form class="space-y-6" method="POST">
            <div class="grid grid-cols-1 gap-6">
              <div>
                <label
                  class="block text-sm font-semibold text-gray-700 mb-2"
                  >Current Password</label
                >
                <div class="relative">
                  <div
                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-5 h-5 text-gray-400"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                      />
                    </svg>
                  </div>
                  <input
                    type="password" 
                    name="current_password" 
                    required 
                    class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300"
                    placeholder="••••••••"
                  />
                </div>
              </div>

              <div>
                <label
                  class="block text-sm font-semibold text-gray-700 mb-2"
                  >New Password</label
                >
                <div class="relative">
                  <div
                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-5 h-5 text-gray-400"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                      />
                    </svg>
                  </div>
                  <input
                    type="password" 
                    name="password" 
                    required minlength="6" 
                    class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300"
                    placeholder="••••••••"
                  />
                </div>
              </div>

              <div>
                <label
                  class="block text-sm font-semibold text-gray-700 mb-2"
                  >Confirm New Password</label
                >
                <div class="relative">
                  <div
                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-5 h-5 text-gray-400"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                      />
                    </svg>
                  </div>
                  <input
                    type="password" 
                    name="confirm_password" 
                    required minlength="6" 
                    class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300"
                    placeholder="••••••••"
                  />
                </div>
              </div>
            </div>

            <div class="flex justify-end pt-4">
              <button
                type="submit"
                class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
              >
                Update Password
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

<?php
$content = ob_get_clean();
require_once '../pages/layouts/main-layout.php';
?>
