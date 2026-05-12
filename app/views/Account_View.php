<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="/css/output.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/ui-refresh.css">
</head>
<body class="ui-refresh min-h-screen bg-linear-to-br from-amber-50 via-cyan-50 to-rose-50 text-slate-800 dark:text-slate-200 font-['Space_Grotesk',sans-serif] dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 ">
    <main class="relative z-10 mx-auto w-[95%] xl:w-4/5 px-4 py-4">
        <div class="grid min-h-[calc(100vh-2rem)] grid-cols-1 gap-4 xl:grid-cols-[250px_minmax(0,1fr)]">
            <?php include 'Sidebar.php'; ?>

            <section class="space-y-4 overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 p-4 shadow-[0_12px_30px_rgba(15,23,42,0.08)] backdrop-blur-sm sm:p-6">
                <?php if (isset($_GET['message'])): ?>
                    <div class="rounded-2xl px-4 py-3 text-sm font-medium <?= ($_GET['message'] === 'update_success') ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300' : 'bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-300' ?>">
                        <?= match($_GET['message']) {
                            'update_success' => 'Profil mis a jour avec succes.',
                            'update_failed' => 'Echec de la mise a jour.',
                            'username_taken' => 'Nom d\'utilisateur indisponible.',
                            default => 'Erreur systeme.'
                        } ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($user) && is_array($user)): ?>
                <section class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm sm:p-6">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-start">
                        <img src="<?= !empty($user['picture']) ? htmlspecialchars($user['picture']) : '/assets/Black_Icons/Black_profil.png' ?>"
                            alt="Photo de profil" class="h-24 w-24 rounded-3xl border border-slate-200 dark:border-slate-700 object-cover p-1">
                        <div class="w-full">
                            <h1 class="font-['Fraunces',serif] text-3xl leading-tight text-slate-900 dark:text-slate-100"><?= htmlspecialchars($user['firstname'] ?? '') ?> <?= htmlspecialchars($user['lastname'] ?? '') ?></h1>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">@<?= htmlspecialchars($user['username'] ?? '') ?></p>

                            <?php if ($hasBlockedMe): ?>
                                <p class="mt-4 rounded-2xl border border-rose-100 dark:border-rose-800 bg-rose-50 dark:bg-rose-900/30 px-4 py-3 text-sm font-semibold text-rose-700 dark:text-rose-300">
                                    Cet utilisateur vous a bloqué.
                                </p>
                            <?php elseif ($isBlockedByMe): ?>
                                <p class="mt-4 rounded-2xl border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200">
                                    Vous avez bloqué cet utilisateur.
                                </p>
                                <form action="/unblock" method="POST" class="mt-4">
                                    <?= csrf_input() ?>
                                    <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                    <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/account') ?>">
                                    <button type="submit" class="rounded-full bg-slate-700 px-5 py-2 text-sm font-semibold text-white transition hover:bg-slate-600">
                                        Débloquer
                                    </button>
                                </form>
                            <?php else: ?>
                                <p class="mt-4 rounded-2xl border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3 text-sm leading-relaxed text-slate-700 dark:text-slate-200">
                                    <?= !empty($user['biography']) ? nl2br(htmlspecialchars($user['biography'])) : 'Aucune biographie affichable.' ?>
                                </p>

                                <div class="mt-4 flex flex-wrap gap-3 text-sm">
                                    <span class="rounded-full bg-teal-50 dark:bg-teal-900/30 px-3 py-1 text-teal-700 dark:text-teal-300"><strong><?= $followersCount ?? 0 ?></strong> abonnes</span>
                                    <span class="rounded-full bg-orange-50 dark:bg-orange-900/30 px-3 py-1 text-orange-700 dark:text-orange-300"><strong><?= $followingCount ?? 0 ?></strong> abonnements</span>
                                    <?php if (!empty($user['city']) || !empty($user['country'])): ?>
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-600 dark:text-slate-400"><?= htmlspecialchars(trim(($user['city'] ?? '') . ' ' . ($user['country'] ?? ''))) ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="mt-4 text-xs text-slate-500 dark:text-slate-400">
                                    <p>Date de creation : <?= !empty($user['creation_date']) ? htmlspecialchars($user['creation_date']) : 'Non disponible' ?></p>
                                    <p>Statut : <?= !empty($user['is_verified']) && $user['is_verified'] ? 'Verifie' : 'Non verifie' ?></p>
                                </div>

                                <div class="mt-4 flex gap-2">
                                <?php if (!($isOwnProfile ?? true)): ?>
                                    <form action="<?= ($isFollowing ?? false) ? '/unfollow' : '/follow' ?>" method="POST">
                                        <?= csrf_input() ?>
                                        <input type="hidden" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>">
                                        <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/account') ?>">
                                        <button type="submit" class="rounded-full px-5 py-2 text-sm font-semibold text-white transition <?= ($isFollowing ?? false) ? 'bg-slate-700 hover:bg-slate-600' : 'bg-teal-600 hover:bg-teal-700' ?>">
                                            <?= ($isFollowing ?? false) ? 'Ne plus suivre' : 'Suivre' ?>
                                        </button>
                                    </form>

                                    <a href="/message?username=<?= urlencode($user['username'] ?? '') ?>" class="rounded-full border border-teal-200 dark:border-teal-800 bg-teal-50 dark:bg-teal-900/30 px-5 py-2 text-sm font-semibold text-teal-600 dark:text-teal-400 transition hover:bg-teal-100 dark:hover:bg-teal-900/50 flex items-center justify-center">
                                        Message
                                    </a>

                                    <form action="/block" method="POST">
                                        <?= csrf_input() ?>
                                        <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                        <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/account') ?>">
                                        <button type="submit" class="rounded-full border border-rose-200 dark:border-rose-800 bg-rose-50 dark:bg-rose-900/30 px-5 py-2 text-sm font-semibold text-rose-600 dark:text-rose-400 transition hover:bg-rose-100 dark:hover:bg-rose-900/50">
                                            Bloquer
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button onclick="togglePopup()" class="rounded-full bg-teal-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-teal-700">
                                        Editer le profil
                                    </button>
                                <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
                <?php else: ?>
                    <p class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3 text-sm text-slate-500 dark:text-slate-400">Aucun profil utilisateur a afficher.</p>
                <?php endif; ?>

                <section class="space-y-3">
                    <?php if (!$isBlockedByMe && !$hasBlockedMe && isset($tweets) && !empty($tweets)): ?>
                        <?php foreach ($tweets as $tweet): ?>
                        <article class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 shadow-sm transition hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <div class="flex items-start gap-3">
                                <img src="<?= htmlspecialchars($tweet['picture'] ?? '/assets/Black_Icons/Black_profil.png') ?>" alt="Profil" class="h-11 w-11 rounded-2xl border border-slate-200 dark:border-slate-700 object-cover p-1">
                                <div class="w-full">
                                    <header class="flex flex-wrap items-center gap-2 text-sm">
                                        <h3 class="font-semibold text-slate-900 dark:text-slate-100"><?= htmlspecialchars($tweet['display_name'] ?? 'Utilisateur') ?></h3>
                                        <span class="text-slate-500 dark:text-slate-400">@<?= htmlspecialchars($tweet['username'] ?? 'utilisateur') ?></span>
                                        <span class="text-slate-400">· <?= date('d/m/Y', strtotime($tweet['creation_date'] ?? 'now')) ?></span>
                                    </header>
                                    <p class="mt-2 text-sm leading-relaxed text-slate-700 dark:text-slate-200"><?= format_content($tweet['content'] ?? '') ?></p>

                                    <?php if (!empty($tweet['media1'])): ?>
                                    <div class="mt-3 grid gap-2 sm:grid-cols-2">
                                        <?php for ($i = 1; $i <= 4; $i++): ?>
                                            <?php if (!empty($tweet['media' . $i])): ?>
                                            <img src="<?= htmlspecialchars($tweet['media' . $i]) ?>" alt="Media <?= $i ?>" class="h-36 w-full rounded-xl border border-slate-200 dark:border-slate-700 object-cover">
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <?php endif; ?>

                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="flex gap-4">
                                            <button type="button" onclick="openShareModal(<?= (int) $tweet['id_tweet'] ?>)" class="action-btn flex items-center gap-1 rounded-full px-3 py-1.5 transition hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400">
                                                <img src="/assets/Black_Icons/black_share.png" alt="share" class="h-4 w-4">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    <?php elseif (!$isBlockedByMe && !$hasBlockedMe): ?>
                        <p class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3 text-sm text-slate-500 dark:text-slate-400">Aucun tweet a afficher.</p>
                    <?php endif; ?>
                </section>
            </section>
        </div>
    </main>

    <?php if ($isOwnProfile ?? true): ?>
    <div class="fixed inset-0 z-50 hidden items-center justify-center px-4 pointer-events-none" id="editProfilePopup">
        <div class="w-full max-w-lg rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-2xl pointer-events-auto">
            <form action="/account/update" method="POST" enctype="multipart/form-data" class="space-y-3">
                <?= csrf_input() ?>
                <h2 class="font-['Fraunces',serif] text-2xl text-slate-900 dark:text-slate-100">Editer le profil</h2>
                <div class="max-h-[70vh] overflow-y-auto pr-2 space-y-3">
                    <div>
                        <label for="firstname" class="mb-1 block text-sm font-medium text-slate-600 dark:text-slate-400">Prenom</label>
                        <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($user['firstname'] ?? '') ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">
                    </div>
                    <div>
                        <label for="lastname" class="mb-1 block text-sm font-medium text-slate-600 dark:text-slate-400">Nom</label>
                        <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($user['lastname'] ?? '') ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">
                    </div>
                    <div>
                        <label for="display_name" class="mb-1 block text-sm font-medium text-slate-600 dark:text-slate-400">Nom d'affichage</label>
                        <input type="text" id="display_name" name="display_name" value="<?= htmlspecialchars($user['display_name'] ?? '') ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">
                    </div>
                    <div>
                        <label for="username" class="mb-1 block text-sm font-medium text-slate-600 dark:text-slate-400">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">
                    </div>
                    <div>
                        <label for="biography" class="mb-1 block text-sm font-medium text-slate-600 dark:text-slate-400">Biographie</label>
                        <textarea id="biography" name="biography" class="min-h-24 w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400"><?= htmlspecialchars($user['biography'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label for="picture" class="mb-1 block text-sm font-medium text-slate-600 dark:text-slate-400">Photo de profil</label>
                        <input type="file" id="picture" name="picture" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-sm text-slate-700 dark:text-slate-200 file:mr-3 file:rounded-lg file:border-0 file:bg-teal-100 dark:bg-teal-900/50 file:px-3 file:py-1 file:text-teal-700 dark:text-teal-300">
                    </div>
                    <div>
                        <label for="header" class="mb-1 block text-sm font-medium text-slate-600 dark:text-slate-400">Bannière</label>
                        <input type="file" id="header" name="header" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-sm text-slate-700 dark:text-slate-200 file:mr-3 file:rounded-lg file:border-0 file:bg-orange-100 dark:bg-orange-900/50 file:px-3 file:py-1 file:text-orange-700 dark:text-orange-300">
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label for="city" class="mb-1 block text-sm font-medium text-slate-600 dark:text-slate-400">Ville</label>
                            <input type="text" id="city" name="city" value="<?= htmlspecialchars($user['city'] ?? '') ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">
                        </div>
                        <div>
                            <label for="country" class="mb-1 block text-sm font-medium text-slate-600 dark:text-slate-400">Pays</label>
                            <input type="text" id="country" name="country" value="<?= htmlspecialchars($user['country'] ?? '') ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t mt-2">
                    <button type="button" onclick="togglePopup()" class="rounded-full border border-slate-200 dark:border-slate-700 px-4 py-2 text-sm text-slate-600 dark:text-slate-400 transition hover:bg-slate-100 dark:hover:bg-slate-800">Annuler</button>
                    <button type="submit" class="rounded-full bg-teal-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-teal-700">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script src ="/lib/account.js"></script>
    <script>
        function togglePopup() {
            const popup = document.getElementById('editProfilePopup');
            const overlay = document.getElementById('overlay');
            if (popup.classList.contains('hidden')) {
                popup.classList.remove('hidden');
                popup.classList.add('flex');
                overlay.classList.remove('hidden');
            } else {
                popup.classList.add('hidden');
                popup.classList.remove('flex');
                overlay.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
