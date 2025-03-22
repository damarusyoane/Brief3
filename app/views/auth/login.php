<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="?controller=home&action=index" class="text-xl font-bold text-gray-800">Management</a>
                    </div>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="hidden md:ml-6 md:flex md:space-x-8">
                            <?php if (hasAdminRole()): ?>
                                <a href="?controller=admin&action=dashboard" class="inline-flex items-center px-1 pt-1 text-gray-500 hover:text-gray-700">Dashboard</a>
                                <a href="?controller=admin&action=users" class="inline-flex items-center px-1 pt-1 text-gray-500 hover:text-gray-700">Users</a>
                                <a href="?controller=admin&action=logs" class="inline-flex items-center px-1 pt-1 text-gray-500 hover:text-gray-700">Logs</a>
                            <?php else: ?>
                                <a href="?controller=user&action=dashboard" class="inline-flex items-center px-1 pt-1 text-gray-500 hover:text-gray-700">Dashboard</a>
                                <a href="?controller=user&action=sessions" class="inline-flex items-center px-1 pt-1 text-gray-500 hover:text-gray-700">Sessions</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="flex items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="ml-3 relative group">
                            <div class="flex items-center">
                                <span class="text-gray-700 mr-2"><?= htmlspecialchars($_SESSION['username']) ?></span>
                                <button class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                                    <i class="fas fa-user-circle text-2xl text-gray-300"></i>
                                </button>
                            </div>
                            <div class="hidden group-hover:block absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5">
                                <?php if (hasAdminRole()): ?>
                                    <a href="?controller=admin&action=dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                <?php else: ?>
                                    <a href="?controller=user&action=dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                <?php endif; ?>
                                <a href="?controller=auth&action=logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="?controller=auth&action=login" class="text-gray-500 hover:text-gray-700">Login</a>
                    <?php endif; ?>
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

        <div class="max-w-md mx-auto mt-8">
            <div class="bg-white shadow-md rounded-lg p-8">
                <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
                
                <?php if (isset($data['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo $data['error']; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($data['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $data['success']; ?>
                    </div>
                <?php endif; ?>

                <form action="?controller=auth&action=login" method="POST" class="space-y-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" id="username" name="username" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>
                    
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Login
                    </button>
                </form>
                
                <div class="mt-4 text-center">
                    <a href="index.php?controller=auth&action=forgotPassword" class="text-sm text-blue-600 hover:text-blue-500">
                        Forgot Password?
                    </a>
                </div>
            </div>
        </div>
    </main>
    
    <footer class="bg-white shadow-lg mt-8">
        <div class="max-w-7xl mx-auto py-4 px-4">
            <div class="flex justify-between items-center">
                <div class="text-gray-500 text-sm">
                    &copy; Management System All rights reserved.
                </div>
                <div class="text-gray-500 text-sm">
                    Version 1.0
                </div>
            </div>
        </div>
    </footer>
</body>
</html> 


