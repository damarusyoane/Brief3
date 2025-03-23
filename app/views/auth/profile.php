<?php
ob_start();
?>
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Profile</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Your account information</p>
    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <form action="/profile" method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <div class="mt-1">
                    <input type="text" name="username" id="username" required
                        value="<?php echo htmlspecialchars($user['username']); ?>"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <div class="mt-1">
                    <input type="email" name="email" id="email" required
                        value="<?php echo htmlspecialchars($user['email']); ?>"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">New Password (leave blank to keep current)</label>
                <div class="mt-1">
                    <input type="password" name="password" id="password"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Role</label>
                <p class="mt-1 text-sm text-gray-500"><?php echo htmlspecialchars($user['role_name']); ?></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <p class="mt-1 text-sm text-gray-500"><?php echo htmlspecialchars($user['status']); ?></p>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="/" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php'; 