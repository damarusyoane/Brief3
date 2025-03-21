<?php include '../app/views/partials/header.php'; ?>

<h1>Tableau de bord Admin</h1>
<h2>Liste des utilisateurs</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom d'utilisateur</th>
            <th>Email</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['username'] ?></td>
                <td><?= $user['email'] ?></td>
                <td><?= $user['status'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../app/views/partials/footer.php'; ?>