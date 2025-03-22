<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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
                <h1 class="text-2xl font-semibold text-gray-900">User Management</h1>
                <a href="?controller=admin&action=create_user" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i> Add New User
                </a>
            </div>

            <div class="bg-white shadow rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <i class="fas fa-user-circle text-gray-400 text-2xl"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($user['username']) ?></div>
                                            <div class="text-sm text-gray-500"><?= htmlspecialchars($user['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $user['role_id'] == 1 ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?>">
                                        <?= $user['role_id'] == 1 ? 'Admin' : 'User' ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $user['status'] == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= $user['status'] == 1 ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $user['last_login'] ? date('M j, Y H:i:s', strtotime($user['last_login'])) : 'Never' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <a href="?controller=admin&action=edit_user&id=<?= $user['id'] ?>" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <?php if ($user['status'] == 1): ?>
                                                <a href="?controller=admin&action=deactivate_user&id=<?= $user['id'] ?>" 
                                                   class="text-yellow-600 hover:text-yellow-900">
                                                    <i class="fas fa-user-slash"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="?controller=admin&action=activate_user&id=<?= $user['id'] ?>" 
                                                   class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-user-check"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="?controller=admin&action=delete_user&id=<?= $user['id'] ?>" 
                                               class="text-red-600 hover:text-red-900"
                                               onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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