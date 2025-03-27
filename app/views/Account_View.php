<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link href="css/output.css" rel="stylesheet">
</head>
<body class="bg-white text-neutral-900 dark:bg-neutral-900 dark:text-neutral-300">

    <main class="container mx-auto mt-6 grid grid-cols-12 gap-6 px-6">
        <nav id="left" class="col-span-3 flex flex-col justify-between">
            <div>
                <img src="assets/White_Icons/icons_twitter-.png" alt="Logo" class="w-16 h-16 mb-3 mt-2">
                <ul class="flex flex-col gap-9 text-2xl select-none">
                    <a href="/feed" class="flex gap-6 items-center cursor-pointer hover:bg-neutral-200 dark:hover:bg-neutral-800 rounded-full p-3 pl-6">
                        <img src="/assets/Icons/home.png" alt="Icone Home" class="w-8 h-8">
                        <p class="h-fit font-bold">Accueil</p>
                    </a>
                    <a href="/feed" class="flex gap-6 items-center cursor-pointer hover:bg-neutral-200 dark:hover:bg-neutral-800 rounded-full p-3 pl-6">
                        <img src="/assets/Icons/search.png" alt="Search Home" class="w-8 h-8">
                        <p class="h-fit">Explorer</p>
                    </a>
                    <a href="/messages" class="flex gap-6 items-center cursor-pointer hover:bg-neutral-200 dark:hover:bg-neutral-800 rounded-full p-3 pl-6">
                        <img src="/assets/Icons/answer.png" alt="Message Home" class="w-8 h-8">
                        <p class="h-fit">Message</p>
                    </a>
                    <a href="/korg" class="flex gap-6 items-center cursor-pointer hover:bg-neutral-200 dark:hover:bg-neutral-800 rounded-full p-3 pl-6">
                        <img src="/assets/Icons/korg.png" alt="Korg Home" class="w-8 h-8">
                        <p class="h-fit">Korg</p>
                    </a>
                    <a href="/account" class="flex gap-6 items-center cursor-pointer hover:bg-neutral-200 dark:hover:bg-neutral-800 rounded-full p-3 pl-6">
                        <img src="/assets/Icons/profile.png" alt="Profil Home" class="w-8 h-8">
                        <p class="h-fit">Profil</p>
                    </a>
                </ul>
            </div>
            <div>
                <button class="bg-neutral-200 text-neutral-800 w-full py-5 rounded-full font-bold text-2xl cursor-pointer hover:bg-neutral-400 hover:text-neutral-900">
                    Poster
                </button>
                <div class="flex my-8 gap-3 px-6 py-3 rounded-full cursor-pointer hover:bg-neutral-200 dark:hover:bg-neutral-800" id="profileMenu">
    <img src="/assets/Icons/profile.png" alt="Picture image" class="w-12 h-12">
    <div class="name-profile">
        <p class="font-bold"><?= $_SESSION['user']['firstname'] ?> <?= $_SESSION['user']['lastname'] ?></p>
        <p class="text-neutral-600 dark:text-neutral-400">@<?= $_SESSION['user']['username'] ?></p>
    </div>
</div>

<div id="logoutMenu" class="hidden flex-col gap-2 mt-2 bg-white dark:bg-neutral-800 shadow-lg rounded-lg p-3">
    <a href="/login" class="text-red-500 hover:text-red-700">Se déconnecter</a>
</div>
            </div>
        </nav>

        <section class="col-span-6 ml-8 bg-neutral-100 dark:bg-neutral-800 rounded-2xl shadow-lg p-6 border-l border-r border-neutral-300 dark:border-neutral-600">
            <?php if (isset($_GET['message'])): ?>
                <div class="p-4 rounded-lg <?= ($_GET['message'] === 'update_success') ? 'bg-green-500 text-white' : 'bg-red-500 text-white' ?>">
                    <?= match($_GET['message']) {
                        'update_success' => 'Profil mis à jour avec succès!',
                        'update_failed' => 'Échec de la mise à jour',
                        'username_taken' => 'Nom d\'utilisateur indisponible',
                        default => 'Erreur système'
                    } ?>
                </div>
            <?php endif; ?>

            <?php if (isset($user) && is_array($user)): ?>
            <section class="bg-white dark:bg-neutral-800 p-6 rounded-lg shadow">
                <div class="flex items-center space-x-6">
                    <img src="<?= !empty($user['picture']) ? htmlspecialchars($user['picture']) : 'https://via.placeholder.com/128' ?>"
                        alt="Photo de profil" class="rounded-full h-24 w-24 object-cover border-4 border-blue-500">
                    <div>
                        <h1 class="text-2xl font-bold"><?= htmlspecialchars($user['firstname'] ?? '') ?> <?= htmlspecialchars($user['lastname'] ?? '') ?></h1>
                        <p class="text-neutral-600 dark:text-neutral-400">@<?= htmlspecialchars($user['username'] ?? '') ?></p>

                        <?php if (!empty($user['biography'])): ?>
                        <p class="mt-2"><?= nl2br(htmlspecialchars($user['biography'])) ?></p>
                        <?php else: ?>
                        <p class="mt-2">Aucune biographie affichable</p>
                        <?php endif; ?>

                        <div class="mt-4 flex space-x-6">
                            <span><span class="text-neutral-900 dark:text-white"><?= $followersCount ?? 0 ?></span> Abonnés</span>
                            <span><span class="text-neutral-900 dark:text-white"><?= $followingCount ?? 0 ?></span> Abonnements</span>
                            <?php if (!empty($user['city']) || !empty($user['country'])): ?>
                            <span><?= htmlspecialchars(trim(($user['city'] ?? '') . ' ' . ($user['country'] ?? ''))) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="mt-4 text-neutral-600 dark:text-neutral-400">
                            <p>Date de création : <?= !empty($user['creation_date']) ? htmlspecialchars($user['creation_date']) : 'Non disponible' ?></p>
                            <p>Statut : <?= !empty($user['is_verified']) && $user['is_verified'] ? 'Vérifié' : 'Non vérifié' ?></p>
                        </div>

                        <button onclick="togglePopup()" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all duration-200">
                            Éditer le profil
                        </button>
                    </div>
                </div>
            </section>
            <?php else: ?>
                <p class="text-neutral-600 dark:text-neutral-400">Aucun profil utilisateur à afficher.</p>
            <?php endif; ?>

            <section class="bg-white dark:bg-neutral-800 p-6 mt-6 rounded-lg shadow">
      <form action="/feed" method="POST">
        <textarea name="content" placeholder="Quoi de neuf ?" maxlength="140" class="w-full p-3 bg-neutral-800 text-white border border-neutral-700 rounded-lg mb-4"></textarea>
        <input type="url" name="media1" placeholder="Lien image 1" class="w-full p-3 bg-neutral-800 text-white border border-neutral-700 rounded-lg mb-2">
        <input type="url" name="media2" placeholder="Lien image 2" class="w-full p-3 bg-neutral-800 text-white border border-neutral-700 rounded-lg mb-2">
        <input type="url" name="media3" placeholder="Lien image 3" class="w-full p-3 bg-neutral-800 text-white border border-neutral-700 rounded-lg mb-2">
        <input type="url" name="media4" placeholder="Lien image 4" class="w-full p-3 bg-neutral-800 text-white border border-neutral-700 rounded-lg mb-4">
        <button type="submit" class="bg-indigo-500 text-white py-2 px-4 rounded-lg">Tweeter</button>
        <button type="button" class="bg-gray-500 text-white py-2 px-4 rounded-lg" onclick="document.getElementById('tweet-form').classList.add('hidden'); document.getElementById('overlay').classList.add('hidden');">Annuler</button>
      </form>
            </section>

            <section class="mt-6">
                <?php if (isset($tweets) && !empty($tweets)): ?>
                    <?php foreach ($tweets as $tweet): ?>
                    <article class="bg-white dark:bg-neutral-800 p-4 rounded-lg shadow hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-all duration-200 mb-4">
                        <div class="flex items-start space-x-4">
                            <img src="<?= htmlspecialchars($tweet['picture'] ?? 'https://via.placeholder.com/48') ?>" alt="Profil" class="rounded-full h-12 w-12 object-cover">
                            <div>
                                <header class="flex items-center space-x-2">
                                    <h3 class="font-bold"><?= htmlspecialchars($tweet['display_name'] ?? 'Utilisateur') ?></h3>
                                    <span class="text-neutral-600 dark:text-neutral-400">@<?= htmlspecialchars($tweet['username'] ?? 'utilisateur') ?></span>
                                    <span class="text-neutral-500 text-sm">· <?= date('d/m/Y', strtotime($tweet['creation_date'] ?? 'now')) ?></span>
                                </header>
                                <p class="mt-2"><?= htmlspecialchars($tweet['content'] ?? '') ?></p>

                                <?php if (!empty($tweet['media1'])): ?>
                                <div class="mt-4 grid grid-cols-2 gap-2">
                                    <?php for ($i = 1; $i <= 4; $i++): ?>
                                        <?php if (!empty($tweet['media' . $i])): ?>
                                        <img src="<?= htmlspecialchars($tweet['media' . $i]) ?>" alt="Média <?= $i ?>" class="rounded-lg w-full h-32 object-cover">
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-neutral-600 dark:text-neutral-400">Aucun tweet à afficher.</p>
                <?php endif; ?>
            </section>
        </section>
    </main>

    <div class="fixed inset-0 bg-black bg-opacity-50 hidden" id="popupOverlay"></div>
    <div class="fixed inset-0  items-center justify-center hidden" id="editProfilePopup">
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg shadow-lg w-full max-w-md">
            <form action="/account/update" method="POST" enctype="multipart/form-data">
                <h2 class="text-xl font-bold mb-4">Éditer le profil</h2>
                <div class="mb-4">
                    <label for="firstname" class="block text-sm font-medium">Prénom</label>
                    <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($user['firstname'] ?? '') ?>" class="w-full p-2 border rounded-lg bg-neutral-100 dark:bg-neutral-700 text-neutral-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="lastname" class="block text-sm font-medium">Nom</label>
                    <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($user['lastname'] ?? '') ?>" class="w-full p-2 border rounded-lg bg-neutral-100 dark:bg-neutral-700 text-neutral-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" class="w-full p-2 border rounded-lg bg-neutral-100 dark:bg-neutral-700 text-neutral-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="biography" class="block text-sm font-medium">Biographie</label>
                    <textarea id="biography" name="biography" class="w-full p-2 border rounded-lg bg-neutral-100 dark:bg-neutral-700 text-neutral-900 dark:text-white"><?= htmlspecialchars($user['biography'] ?? '') ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="picture" class="block text-sm font-medium">Photo de profil</label>
                    <input type="file" id="picture" name="picture" class="w-full p-2 border rounded-lg bg-neutral-100 dark:bg-neutral-700 text-neutral-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="city" class="block text-sm font-medium">Ville</label>
                    <input type="text" id="city" name="city" value="<?= htmlspecialchars($user['city'] ?? '') ?>" class="w-full p-2 border rounded-lg bg-neutral-100 dark:bg-neutral-700 text-neutral-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="country" class="block text-sm font-medium">Pays</label>
                    <input type="text" id="country" name="country" value="<?= htmlspecialchars($user['country'] ?? '') ?>" class="w-full p-2 border rounded-lg bg-neutral-100 dark:bg-neutral-700 text-neutral-900 dark:text-white">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="togglePopup()" class="px-4 py-2 bg-neutral-500 text-white rounded-lg hover:bg-neutral-600 mr-2">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script src ="/lib/account.js"></script>
</body>
</html>