<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../../public/assets/logo.png" type="image/x-icon">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
    <div
        class="hero min-h-screen"
        style="background-image: url(https://img.daisyui.com/images/stock/photo-1507358522600-9f71e620c44e.webp);">
        <div class="hero-overlay"></div>
        <div class="hero-content text-neutral-content text-center">
            <div class="max-w-xl">
                <h1 class="mb-5 text-5xl font-bold">Bienvenue, <?= htmlspecialchars($username) ?> !</h1>
                <p class="mb-5">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Blanditiis vero tenetur, autem culpa reiciendis 
                dolorem quis laboriosam odio obcaecati exercitationem iure vitae et aliquam eum similique error ut laudantium sed.
                </p>
                <a href="/logout" class="btn btn-primary">Se déconnecter</a>
            </div>
        </div>
    </div>
</body>
</html>