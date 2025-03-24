<!-- code pour permettre l'affichage le contenu du head et footer sur le fichier main -->
<?php
ob_start();
?>
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Edit User</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Modify user information</p>
    </div>
    <!-- modifier les infos de  l'utilisateur a partir de l'id de l'utilisateur -->
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <form action="/users/edit/<?php echo $user['id']; ?>" method="POST" class="space-y-6">
            <!-- modifier le nom d'utilisateur -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <div class="mt-1">
                    <input type="text" name="username" id="username" required
                        value="<?php echo htmlspecialchars($user['username']); ?>"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
            </div>
               <!-- modifier l'email d'utilisateur -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <div class="mt-1">
                    <input type="email" name="email" id="email" required
                        value="<?php echo htmlspecialchars($user['email']); ?>"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
            </div>
             <!-- modifier le mot de passe d'utilisateur -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password (leave blank to keep current)</label>
                <div class="mt-1">
                    <input type="password" name="password" id="password"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
            </div>
           <!-- verifie que l'utilisateur est l'administrateur avant de le permetre de modifier le role d'un utilisateur -->
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
            <div>
                <label for="role_id" class="block text-sm font-medium text-gray-700">Role</label>
                <div class="mt-1">
                    <select name="role_id" id="role_id" required
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <option value="2" <?php echo $user['role_id'] == 2 ? 'selected' : ''; ?>>User</option>
                        <option value="1" <?php echo $user['role_id'] == 1 ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
            </div>
            <!-- verifie que l'utilisateur est l'administrateur avant de le permetre de status le role d'un utilisateur -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <div class="mt-1">
                    <select name="status" id="status" required
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $user['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <?php endif; ?>
            <!-- Renvoie l'admin a la page de la liste des utilisateurs et ou modifie les infos de l'utilisateur  -->
            <div class="flex justify-end space-x-3">
                <a href="/users" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div> 
 <!-- code pour permettre l'affichage le contenu du head et footer sur le fichier main -->
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php'; 