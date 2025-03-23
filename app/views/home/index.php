<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Welcome to User Management System</h1>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="mb-4">
                            <p class="text-gray-600">Logged in as: <span class="font-semibold"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
                            <p class="text-gray-600">Role: <span class="font-semibold"><?php echo htmlspecialchars($_SESSION['user_role']); ?></span></p>
                        </div>

                        <?php if ($_SESSION['user_role'] === 'admin'): ?>
                            <div class="space-y-4">
                                <h2 class="text-xl font-semibold text-gray-800">Admin Options:</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <a href="/users" class="block p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100">
                                        <h3 class="text-lg font-medium text-indigo-900">Manage Users</h3>
                                        <p class="text-indigo-700">View, create, edit, and delete users</p>
                                    </a>
                                    <a href="/users/create" class="block p-4 bg-green-50 rounded-lg hover:bg-green-100">
                                        <h3 class="text-lg font-medium text-green-900">Add New User</h3>
                                        <p class="text-green-700">Create a new user account</p>
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="space-y-4">
                                <h2 class="text-xl font-semibold text-gray-800">User Options:</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <a href="/profile" class="block p-4 bg-blue-50 rounded-lg hover:bg-blue-100">
                                        <h3 class="text-lg font-medium text-blue-900">View Profile</h3>
                                        <p class="text-blue-700">View your profile information</p>
                                    </a>
                                    <a href="/profile/edit" class="block p-4 bg-purple-50 rounded-lg hover:bg-purple-100">
                                        <h3 class="text-lg font-medium text-purple-900">Edit Profile</h3>
                                        <p class="text-purple-700">Update your profile information</p>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mt-6">
                            <a href="/logout" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Logout
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center">
                            <p class="text-gray-600 mb-4">Please sign in to access the system</p>
                            <a href="/login" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Sign in
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div> 