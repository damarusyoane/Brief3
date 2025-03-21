<?php include '../app/views/partials/header.php'; ?>

<h1>Connexion</h1>
<form action="/auth/login" method="POST">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" name="username" id="username" required>
    <br>
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" required>
    <br>
    <button type="submit">Se connecter</button>
</form>

<?php if (isset($error)) : ?>
    <p style="color: red;"><?= $error ?></p>
<?php endif; ?>

<?php include '../app/views/partials/footer.php'; ?>