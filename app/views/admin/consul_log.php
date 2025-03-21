<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des contacts</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8">
    <h1 class="text-2xl font-bold mb-6">Liste des contacts</h1>
    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 p-2">Nom</th>
                <th class="border border-gray-300 p-2">Email</th>
                <th class="border border-gray-300 p-2">Téléphone</th>
                <th class="border border-gray-300 p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($contact['nom']) ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($contact['email']) ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($contact['telephone']) ?></td>
                    <td class="border border-gray-300 p-2">
                        <!-- Lien pour modifier le contact -->
                        <a href="/?controller=contact&action=modifier&id=<?= $contact['id'] ?>" class="text-blue-500">Modifier</a>
                        <!-- Lien pour supprimer le contact -->
                        <a href="/?controller=contact&action=supprimer&id=<?= $contact['id'] ?>" class="text-red-500 ml-2">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- Lien pour ajouter un nouveau contact -->
    <p class="mt-4"><a href="/?controller=contact&action=ajouter" class="text-blue-500">Ajouter un contact</a></p>
</body>
</html>