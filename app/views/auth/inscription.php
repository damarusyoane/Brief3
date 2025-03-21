<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-6">Inscription</h2>
        <?php if (isset($error)): ?>
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded"><?= $error ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded"><?= $success ?></div>
        <?php endif; ?>
        <form action="/?controller=user&action=register" method="POST">
            <div class="mb-4">
                <label for="nom" class="block text-gray-700">Nom</label>
                <input type="text" name="nom" id="nom" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Mot de passe</label>
                <input type="password" name="password" id="password" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">S'inscrire</button>
        </form>
        <p class="mt-4 text-center">Déjà un compte ? <a href="/?controller=auth&action=connexion" class="text-blue-500">Connectez-vous ici</a>.</p>
    </div>
    <script src="./public/assets/validate.js"></script>
</body>
</html>