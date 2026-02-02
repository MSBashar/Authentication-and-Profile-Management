<?php 

$projectRoot = str_replace("\pages\layouts", "", __DIR__);

// -- Preventing direct access and redirecting to the login page --
// Get the request URI (e.g., /path/to/page.php?param=value)
$uri = trim(dirname($_SERVER['REQUEST_URI']));
if ($uri == '/pages/layouts' || $uri == '/pages/layouts') {
    require_once $projectRoot . '/helper/prevent-direct-access.php';
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $title . " | Interactive Cares" ?? 'Interactive Cares'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            animation: {
              float: "float 3s ease-in-out infinite",
              fadeIn: "fadeIn 0.5s ease-in forwards",
            },
            keyframes: {
              float: {
                "0%, 100%": { transform: "translateY(0px)" },
                "50%": { transform: "translateY(-10px)" },
              },
              fadeIn: {
                from: { opacity: 0, transform: "translateY(10px)" },
                to: { opacity: 1, transform: "translateY(0)" },
              },
            },
          },
        },
      };
    </script>
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap");
      body {
        font-family: "Inter", sans-serif;
        overflow-x: hidden;
      }
      .glass {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
      }
      .input-focus:focus {
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
      }
    </style>
  </head>
  <body
    class="bg-gradient-to-br from-indigo-50 via-white to-cyan-50 min-h-screen flex items-center justify-center p-4"
  >
    <!-- Body Section -->
    <?php echo $authContent; ?>

  </body>
</html>