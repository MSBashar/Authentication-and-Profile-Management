<?php
require_once '../init.php';

$title = 'Edit Profile';
$subTitle = 'Update your personal information';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$userData = $user->getUser($_SESSION['user_id']);

$message = "";

// Handle Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $result = $user->updateProfile($_SESSION['user_id'], $name, $email);

    if ($result === true) {
        $message = ["success", "Profile updated successfully!"];
        
        // Refresh data
        $userData = $user->getUser($_SESSION['user_id']);
        $_SESSION['user_name'] = $name; // Update session name
    } else {
        $message = $result; // Use the error message returned by updateProfile
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
                  >Full Name</label
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
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"
                      />
                    </svg>
                  </div>
                  <input
                    type="text" 
                    name="name" 
                    value="<?php echo htmlspecialchars($userData['name']); ?>" 
                    required 
                    class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300"
                    placeholder="John Doe"
                  />
                </div>
              </div>

              <div>
                <label
                  class="block text-sm font-semibold text-gray-700 mb-2"
                  >Email Address</label
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
                        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"
                      />
                    </svg>
                  </div>
                  <input
                    type="email" 
                    name="email" 
                    value="<?php echo htmlspecialchars($userData['email']); ?>" 
                    required 
                    class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300"
                    placeholder="you@example.com"
                  />
                </div>
              </div>
            </div>

            <div class="flex justify-end pt-4">
              <button
                type="submit"
                class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
              >
                Save Changes
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