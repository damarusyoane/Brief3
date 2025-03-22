<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="?controller=auth&action=login" class="text-xl font-bold text-gray-800">
                            Management System
                        </a>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="?controller=admin&action=dashboard" 
                           class="inline-flex items-center px-1 pt-1 text-gray-500 hover:text-gray-700">
                            Dashboard
                        </a>
                        <a href="?controller=admin&action=users" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 border-blue-500 text-gray-900">
                            Users
                        </a>
                        <a href="?controller=admin&action=logs" 
                           class="inline-flex items-center px-1 pt-1 text-gray-500 hover:text-gray-700">
                            Logs
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="ml-3 relative group">
                        <div class="flex items-center">
                            <span class="text-gray-700 mr-2"><?= htmlspecialchars($_SESSION['username']) ?></span>
                            <button class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                                <i class="fas fa-user-circle text-2xl text-gray-300"></i>
                            </button>
                        </div>
                        <div class="hidden group-hover:block absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5">
                            <a href="?controller=auth&action=logout" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="max-w-7xl mx-auto px-4 mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <?php 
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                    ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="max-w-7xl mx-auto px-4 mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <?php 
                    echo $_SESSION['error_message'];
                    unset($_SESSION['error_message']);
                    ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Create New User</h1>
                <a href="?controller=admin&action=users" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Users
                </a>
            </div>

            <div class="bg-white shadow rounded-lg">
                <form action="?controller=admin&action=create_user" method="POST" class="space-y-6 p-6">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                            <input type="text" name="username" id="username" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" id="password" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role_id" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role_id" id="role_id" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="2" <?= isset($_POST['role_id']) && $_POST['role_id'] == 2 ? 'selected' : '' ?>>User</option>
                                <option value="1" <?= isset($_POST['role_id']) && $_POST['role_id'] == 1 ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="1" <?= isset($_POST['status']) && $_POST['status'] == 1 ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= isset($_POST['status']) && $_POST['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="?controller=admin&action=users" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="bg-white shadow-lg mt-8">
        <div class="max-w-7xl mx-auto py-4 px-4">
            <div class="flex justify-between items-center">
                <div class="text-gray-500 text-sm">
                    &copy; <?= date('Y') ?> Management System All rights reserved
                </div>
                <div class="text-gray-500 text-sm">
                    Version 1.0
                </div>
            </div>
        </div>
    </footer>
</body>
</html> 