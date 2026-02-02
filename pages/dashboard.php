<?php
require_once '../init.php';

$title = 'My Dashboard';
$subTitle = 'Welcome, ' . htmlspecialchars($_SESSION['user_name']) . '!';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$userData = $user->getUser($_SESSION['user_id']);

preg_match_all('/\b\w/u', $userData['name'], $matches);
$acronym = implode('', $matches[0]);
$acronym = substr(mb_strtoupper($acronym), 0, 2);


ob_start();
?>

<!-- Profile Section -->
    <div class="max-w-4xl">
      <div class="bg-white rounded-xl shadow overflow-hidden">
        <div
          class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2"></div>
        <div class="p-6">
          <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-shrink-0">
              <div
                class="w-24 h-24 rounded-full bg-gradient-to-r from-indigo-100 to-purple-100 flex items-center justify-center text-3xl font-bold text-indigo-600">
                <?php echo $acronym; ?>
              </div>
            </div>
            <div class="mt-4 md:mt-0 md:ml-6 flex-1">
              <h3 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($userData['name']); ?></h3>
            </div>
          </div>

          <div class="mt-8">
            <div class="p-4 border rounded-lg">
              <p class="text-gray-500 text-sm">Email</p>
              <p class="font-medium"><?php echo htmlspecialchars($userData['email']); ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php
$content = ob_get_clean();
require_once '../pages/layouts/main-layout.php';
?>