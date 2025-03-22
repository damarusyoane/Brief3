<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <link href="/css/tailwind.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 py-6 flex flex-col">
            <div class="px-6 mb-8">
                <h2 class="text-2xl font-bold">User Panel</h2>
            </div>
            <nav class="flex-1">
                <a href="?controller=user&action=dashboard" class="block px-6 py-2 hover:bg-gray-700">
                    <i class="fas fa-user mr-2"></i> My Profile
                </a>
                <a href="?controller=user&action=sessions" class="block px-6 py-2 hover:bg-gray-700">
                    <i class="fas fa-history mr-2"></i> Session History
                </a>
                <a href="?controller=user&action=settings" class="block px-6 py-2 hover:bg-gray-700 bg-gray-700">
                    <i class="fas fa-cog mr-2"></i> Settings
                </a>
            </nav>
            <div class="px-6 py-4">
                <div class="flex items-center mb-4">
                    <div class="p-2 rounded-full bg-gray-700">
                        <i class="fas fa-user text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="font-semibold"><?= htmlspecialchars($user['username']) ?></p>
                        <p class="text-sm text-gray-400">User Account</p>
                    </div>
                </div>
                <a href="?controller=auth&action=logout" class="block px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-center">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden">
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4">
                    <h1 class="text-2xl font-bold text-gray-800">Settings</h1>
                </div>
            </header>

            <main class="p-6">
                <!-- Notification Settings -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Notification Settings</h2>
                    <form method="POST" action="?controller=user&action=update-notifications" class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="email_notifications" id="email_notifications" 
                                   <?= $user['email_notifications'] ? 'checked' : '' ?>
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="email_notifications" class="ml-2 block text-sm text-gray-900">
                                Email Notifications
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="session_alerts" id="session_alerts" 
                                   <?= $user['session_alerts'] ? 'checked' : '' ?>
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="session_alerts" class="ml-2 block text-sm text-gray-900">
                                Session Alerts
                            </label>
                        </div>
                        <div>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Save Notification Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Privacy Settings -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Privacy Settings</h2>
                    <form method="POST" action="?controller=user&action=update-privacy" class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="show_online_status" id="show_online_status" 
                                   <?= $user['show_online_status'] ? 'checked' : '' ?>
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="show_online_status" class="ml-2 block text-sm text-gray-900">
                                Show Online Status
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="show_last_seen" id="show_last_seen" 
                                   <?= $user['show_last_seen'] ? 'checked' : '' ?>
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="show_last_seen" class="ml-2 block text-sm text-gray-900">
                                Show Last Seen
                            </label>
                        </div>
                        <div>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Save Privacy Settings
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <?php if (isset($success)): ?>
    <div id="success-alert" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg">
        <p><?= $success ?></p>
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('success-alert').style.display = 'none';
        }, 3000);
    </script>
    <?php endif; ?>

    <?php if (isset($error)): ?>
    <div id="error-alert" class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded shadow-lg">
        <p><?= $error ?></p>
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('error-alert').style.display = 'none';
        }, 3000);
    </script>
    <?php endif; ?>
</body>
</html> 