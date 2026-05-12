<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mes Signets - 1Twitter2Plus</title>
  <link href="/css/output.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/ui-refresh.css">
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="ui-refresh min-h-screen bg-linear-to-br from-amber-50 via-cyan-50 to-rose-50 text-slate-800 dark:text-slate-200 font-['Space_Grotesk',sans-serif] dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
  <div class="relative z-10 mx-auto w-[95%] xl:w-4/5 px-4 py-4">
    <div class="grid min-h-[calc(100vh-2rem)] grid-cols-1 gap-4 xl:grid-cols-[250px_minmax(0,1fr)]">
      <?php include 'Sidebar.php'; ?>

      <section class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 p-6 shadow-[0_12px_30px_rgba(15,23,42,0.08)] backdrop-blur-sm">
        <h1 class="font-['Fraunces',serif] text-2xl text-slate-900 dark:text-slate-100 mb-6">Signets</h1>

        <?php if (!empty($bookmarks)): ?>
            <div class="flex flex-col divide-y divide-slate-100 dark:divide-slate-800">
                <?php foreach ($bookmarks as $tweet): ?>
                    <article class="py-5 transition hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <div class="flex gap-3">
                            <a href="/account?username=<?= urlencode($tweet['username']) ?>" class="shrink-0">
                                <img src="<?= $tweet['picture'] ?: '/assets/Black_Icons/Black_profil.png' ?>" class="h-11 w-11 rounded-2xl border border-slate-200 dark:border-slate-700 object-cover">
                            </a>
                            <div class="w-full min-w-0">
                                <div class="flex items-center gap-2 text-sm mb-1">
                                    <span class="font-bold text-slate-900 dark:text-slate-100"><?= htmlspecialchars($tweet['display_name']) ?></span>
                                    <span class="text-slate-500 dark:text-slate-400">@<?= htmlspecialchars($tweet['username']) ?></span>
                                </div>
                                <p class="text-sm text-slate-700 dark:text-slate-200 dark:text-slate-300 leading-relaxed"><?= htmlspecialchars($tweet['content']) ?></p>
                                
                                <?php if ($tweet['media1']): ?>
                                    <div class="mt-3 grid gap-2 md:grid-cols-2">
                                        <img class="max-h-64 w-full rounded-xl border border-slate-200 dark:border-slate-700 object-cover" src="<?= $tweet['media1'] ?>">
                                        <?php if ($tweet['media2']): ?>
                                            <img class="max-h-64 w-full rounded-xl border border-slate-200 dark:border-slate-700 object-cover" src="<?= $tweet['media2'] ?>">
                                        <?php endif ?>
                                    </div>
                                <?php endif ?>

                                <div class="mt-4 flex items-center justify-between max-w-xs text-xs text-slate-500 dark:text-slate-400">
                                    <a href="/tweet?id=<?= (int)$tweet['id_tweet'] ?>" class="action-btn flex items-center gap-1 rounded-full px-3 py-1.5 transition hover:bg-sky-100 dark:hover:bg-sky-900/50 hover:text-sky-700 dark:hover:text-sky-400">
                                        <img src="/assets/Black_Icons/black_answer.png" class="h-4 w-4">
                                        <span><?= (int)$tweet['replies_count'] ?></span>
                                    </a>
                                    <form action="/tweet/retweet" method="POST">
                                        <?= csrf_input() ?>
                                        <input type="hidden" name="tweet_id" value="<?= (int)$tweet['id_tweet'] ?>">
                                        <input type="hidden" name="redirect" value="/bookmarks">
                                        <button type="submit" class="action-btn flex items-center gap-1 rounded-full px-3 py-1.5 transition hover:bg-emerald-100 dark:hover:bg-emerald-900/50 <?= $tweet['is_retweeted'] ? 'action-retweeted font-bold' : '' ?>">
                                            <img src="/assets/Black_Icons/black_retweet.png" class="h-4 w-4">
                                            <span><?= (int)$tweet['retweets_count'] ?></span>
                                        </button>
                                    </form>
                                    <form action="/tweet/like" method="POST">
                                        <?= csrf_input() ?>
                                        <input type="hidden" name="tweet_id" value="<?= (int)$tweet['id_tweet'] ?>">
                                        <input type="hidden" name="redirect" value="/bookmarks">
                                        <button type="submit" class="action-btn flex items-center gap-1 rounded-full px-3 py-1.5 transition hover:bg-rose-100 dark:hover:bg-rose-900/50 <?= $tweet['is_liked'] ? 'action-liked font-bold' : '' ?>">
                                            <img src="/assets/Black_Icons/black_like.png" class="h-4 w-4">
                                            <span><?= (int)$tweet['likes_count'] ?></span>
                                        </button>
                                    </form>
                                    <form action="/tweet/bookmark" method="POST">
                                        <?= csrf_input() ?>
                                        <input type="hidden" name="tweet_id" value="<?= (int)$tweet['id_tweet'] ?>">
                                        <input type="hidden" name="redirect" value="/bookmarks">
                                        <button type="submit" class="action-btn flex items-center gap-1 rounded-full px-3 py-1.5 transition hover:bg-amber-100 dark:hover:bg-amber-900/50 action-bookmarked font-bold">
                                            <img src="/assets/Black_Icons/black_bookmark.png" class="h-4 w-4">
                                            <span>Retirer</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach ?>
            </div>
        <?php else: ?>
            <div class="text-center py-20">
                <img src="/assets/Black_Icons/black_bookmark.png" class="h-12 w-12 mx-auto opacity-20 mb-4">
                <p class="text-slate-500 dark:text-slate-400">Vous n'avez pas encore de signets.</p>
                <a href="/feed" class="text-teal-600 dark:text-teal-400 font-semibold mt-2 inline-block hover:underline">Découvrir des tweets</a>
            </div>
        <?php endif ?>
      </section>
    </div>
  </div>
</body>
</html>
