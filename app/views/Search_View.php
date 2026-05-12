<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recherche - 1Twitter2Plus</title>
  <link href="/css/output.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/ui-refresh.css">
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="ui-refresh min-h-screen bg-linear-to-br from-amber-50 via-cyan-50 to-rose-50 text-slate-800 dark:text-slate-200 font-['Space_Grotesk',sans-serif] dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
  <div class="relative z-10 mx-auto w-[95%] xl:w-4/5 px-4 py-4">
    <div class="grid min-h-[calc(100vh-2rem)] grid-cols-1 gap-4 xl:grid-cols-[250px_minmax(0,1fr)]">
      <?php include 'Sidebar.php'; ?>

      <div class="space-y-4">
        <header class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 p-6 shadow-sm backdrop-blur-sm">
            <form action="/search" method="GET" class="flex gap-3">
                <input type="text" name="q" value="<?= htmlspecialchars($query) ?>" placeholder="Chercher à nouveau..." class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3 text-sm text-slate-800 dark:text-slate-200 outline-none transition focus:border-teal-400">
                <button type="submit" class="rounded-2xl bg-teal-600 px-6 py-3 font-semibold text-white shadow-md transition hover:bg-teal-700">Chercher</button>
            </form>
            <h1 class="mt-4 text-xl font-semibold text-slate-900 dark:text-slate-100">Résultats pour "<?= htmlspecialchars($query) ?>"</h1>
        </header>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <section class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 p-4 shadow-sm backdrop-blur-sm">
                <h2 class="mb-4 font-['Fraunces',serif] text-xl text-slate-900 dark:text-slate-100 border-b pb-2">Utilisateurs</h2>
                <?php if (!empty($users)): ?>
                    <div class="flex flex-col gap-3">
                        <?php foreach ($users as $u): ?>
                            <div class="flex items-center gap-3 rounded-2xl border border-slate-50 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-xs transition hover:bg-slate-50 dark:hover:bg-slate-800">
                                <a href="/account?username=<?= urlencode($u['username']) ?>" class="flex flex-1 items-center gap-3 overflow-hidden">
                                    <img src="<?= $u['picture'] ?: '/assets/Black_Icons/Black_profil.png' ?>" class="h-12 w-12 rounded-xl border border-slate-200 dark:border-slate-700 object-cover">
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-slate-900 dark:text-slate-100"><?= htmlspecialchars($u['display_name']) ?></p>
                                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">@<?= htmlspecialchars($u['username']) ?></p>
                                    </div>
                                </a>
                                <?php if (isset($_SESSION['user']['username']) && $_SESSION['user']['username'] !== $u['username']): ?>
                                    <a href="/message?username=<?= urlencode($u['username']) ?>" class="rounded-full border border-teal-200 dark:border-teal-800 bg-teal-50 dark:bg-teal-900/30 px-4 py-2 text-xs font-semibold text-teal-600 dark:text-teal-400 transition hover:bg-teal-100 dark:hover:bg-teal-900/50">
                                        Message
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach ?>
                    </div>
                <?php else: ?>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Aucun utilisateur trouvé.</p>
                <?php endif ?>
            </section>

            <section class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 p-4 shadow-sm backdrop-blur-sm">
                <h2 class="mb-4 font-['Fraunces',serif] text-xl text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 pb-2">Tweets</h2>
                <?php if (!empty($tweets)): ?>
                    <div class="flex flex-col gap-3">
                        <?php foreach ($tweets as $t): ?>
                            <article class="rounded-2xl border border-slate-50 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-xs transition hover:bg-slate-50 dark:hover:bg-slate-800">
                                <div class="flex gap-3">
                                    <img src="<?= $t['picture'] ?: '/assets/Black_Icons/Black_profil.png' ?>" class="h-10 w-10 rounded-xl border border-slate-200 dark:border-slate-700 object-cover">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2">
                                            <p class="truncate text-sm font-semibold text-slate-900 dark:text-slate-100"><?= htmlspecialchars($t['display_name']) ?></p>
                                            <p class="truncate text-xs text-slate-500 dark:text-slate-400">@<?= htmlspecialchars($t['username']) ?></p>
                                        </div>
                                        <p class="mt-1 text-sm text-slate-700 dark:text-slate-200 dark:text-slate-300"><?= htmlspecialchars($t['content']) ?></p>
                                        <a href="/tweet?id=<?= (int)$t['id_tweet'] ?>" class="mt-2 block text-xs font-semibold text-teal-600 dark:text-teal-400 hover:underline">Voir la discussion</a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach ?>
                    </div>
                <?php else: ?>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Aucun tweet trouvé.</p>
                <?php endif ?>
            </section>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
