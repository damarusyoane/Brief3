<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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
                <a href="?controller=user&action=dashboard" class="block px-6 py-2 hover:bg-gray-700 bg-gray-700">
                    <i class="fas fa-user mr-2"></i> My Profile
                </a>
                <a href="?controller=user&action=sessions" class="block px-6 py-2 hover:bg-gray-700">
                    <i class="fas fa-history mr-2"></i> Session History
                </a>
                <a href="?controller=user&action=settings" class="block px-6 py-2 hover:bg-gray-700">
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
                    <h1 class="text-2xl font-bold text-gray-800">My Profile</h1>
                </div>
            </header>

            <main class="p-6">
                <!-- Profile Information -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex items-start">
                        <div class="flex-1">
                            <form method="POST" action="?controller=user&action=update-profile" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Username</label>
                                    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Change Password</h2>
                    <form method="POST" action="?controller=user&action=change-password" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Current Password</label>
                            <input type="password" name="current_password" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" name="new_password" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <input type="password" name="confirm_password" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                Update Password
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
