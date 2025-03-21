<?php include '../app/views/partials/header.php'; ?>

<h1>Profil Utilisateur</h1>
<p>Nom d'utilisateur : <?= $user['username'] ?></p>
<p>Email : <?= $user['email'] ?></p>
<p>Statut : <?= $user['status'] ?></p>

<a href="/client/edit">Modifier le profil</a>

<?php include '../app/views/partials/footer.php'; ?>