<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>1Twitter2Plus</title>
  <link rel="icon" type="image/svg+xml" href="/favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="/css/output.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/ui-refresh.css">
</head>
<body class="ui-refresh min-h-screen bg-linear-to-br from-amber-50 via-cyan-50 to-rose-50 text-slate-800 dark:text-slate-200 font-['Space_Grotesk',sans-serif] dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
  <div class="relative z-10 mx-auto w-[95%] xl:w-4/5 px-4 py-4">
    <div class="grid min-h-[calc(100vh-2rem)] grid-cols-1 gap-4 xl:grid-cols-[250px_minmax(0,1fr)_320px]">
      <?php include 'Sidebar.php'; ?>

      <div id="mid" class="overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-[0_12px_30px_rgba(15,23,42,0.08)] backdrop-blur-sm">
        <?php $currentTab = $activeTab ?? 'for-you'; ?>
        <div class="grid grid-cols-2 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/80">
          <a href="/feed?tab=for-you" class="flex h-14 items-center justify-center text-sm font-semibold transition hover:bg-slate-100 dark:hover:bg-slate-800 <?= $currentTab === 'for-you' ? 'border-b-2 border-teal-500 text-slate-900 dark:text-slate-100' : 'text-slate-500 dark:text-slate-400' ?>">Pour vous</a>
          <a href="/feed?tab=following" class="flex h-14 items-center justify-center text-sm font-semibold transition hover:bg-slate-100 dark:hover:bg-slate-800 <?= $currentTab === 'following' ? 'border-b-2 border-teal-500 text-slate-900 dark:text-slate-100' : 'text-slate-500 dark:text-slate-400' ?>">Abonnements</a>
        </div>

        <section class="container-feed relative max-h-[calc(100vh-8rem)] overflow-auto">
          <div id="feed" class="mb-16 flex flex-col">
            <?php if (!empty($tweetForFeed)): ?>
            <?php foreach ($tweetForFeed as $tweet): ?>
              <article class="border-b border-slate-100 dark:border-slate-700 p-4 transition hover:bg-slate-50 dark:hover:bg-slate-800/50">
                <div class="flex gap-3">
                  <a href="/account?username=<?= urlencode($tweet['username']) ?>" class="shrink-0">
                    <img src="<?= $tweet['picture'] ? $tweet['picture'] : '/assets/Black_Icons/Black_profil.png' ?>" alt="Profile" class="h-12 w-12 rounded-2xl border border-slate-200 dark:border-slate-700 object-cover">
                  </a>

                  <div class="w-full min-w-0">
                    <div class="flex flex-wrap items-center gap-2 text-sm">
                      <a href="/account?username=<?= urlencode($tweet['username']) ?>" class="font-semibold text-slate-900 dark:text-slate-100 hover:underline"><?= $tweet['display_name'] ?></a>
                      <a href="/account?username=<?= urlencode($tweet['username']) ?>" class="text-slate-500 dark:text-slate-400 hover:underline">@<?= $tweet['username'] ?></a>
                      <p class="text-slate-400">᛫ <?= $tweet['creation_date'] ?></p>
                    </div>

                    <p class="mt-2 text-sm leading-relaxed text-slate-800 dark:text-slate-200"><?= format_content($tweet['content']) ?></p>

                    <?php if ($tweet['media1']): ?>
                      <div class="mt-3 grid gap-2 md:grid-cols-2">
                        <img class="max-h-64 w-full rounded-xl border border-slate-200 dark:border-slate-700 object-cover" src="<?= $tweet['media1'] ?>">
                        <?php if ($tweet['media2']): ?>
                          <img class="max-h-64 w-full rounded-xl border border-slate-200 dark:border-slate-700 object-cover" src="<?= $tweet['media2'] ?>">
                        <?php endif ?>
                      </div>
                    <?php endif ?>

                    <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500 dark:text-slate-400">
                      <a href="/tweet?id=<?= (int) $tweet['id_tweet'] ?>" class="action-btn flex items-center gap-1 rounded-full px-3 py-1.5 transition hover:bg-sky-100 dark:hover:bg-sky-900/50 hover:text-sky-700 dark:hover:text-sky-400">
                        <img src="/assets/Black_Icons/black_answer.png" alt="answer" class="h-4 w-4">
                        <p class="font-medium"><?= (int) ($tweet['replies_count'] ?? 0) ?></p>
                      </a>

                      <form action="/tweet/retweet" method="POST" class="inline">
                        <?= csrf_input() ?>
                        <input type="hidden" name="tweet_id" value="<?= (int) $tweet['id_tweet'] ?>">
                        <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/feed') ?>">
                        <button type="submit" class="action-btn flex items-center gap-1 rounded-full px-3 py-1.5 transition hover:bg-emerald-100 dark:hover:bg-emerald-900/50 <?= !empty($tweet['is_retweeted']) ? 'action-retweeted font-bold shadow-sm' : 'text-slate-500 dark:text-slate-400' ?>">
                          <img src="/assets/Black_Icons/black_retweet.png" alt="retweet" class="h-4 w-4">
                          <p class="font-medium"><?= (int) ($tweet['retweets_count'] ?? 0) ?></p>
                        </button>
                      </form>

                      <form action="/tweet/like" method="POST" class="inline">
                        <?= csrf_input() ?>
                        <input type="hidden" name="tweet_id" value="<?= (int) $tweet['id_tweet'] ?>">
                        <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/feed') ?>">
                        <button type="submit" class="action-btn flex items-center gap-1 rounded-full px-3 py-1.5 transition hover:bg-rose-100 dark:hover:bg-rose-900/50 <?= !empty($tweet['is_liked']) ? 'action-liked font-bold shadow-sm' : 'text-slate-500 dark:text-slate-400' ?>">
                          <img src="/assets/Black_Icons/black_like.png" alt="like" class="h-4 w-4">
                          <p class="font-medium"><?= (int) ($tweet['likes_count'] ?? 0) ?></p>
                        </button>
                      </form>

                      <form action="/tweet/bookmark" method="POST" class="inline">
                        <?= csrf_input() ?>
                        <input type="hidden" name="tweet_id" value="<?= (int) $tweet['id_tweet'] ?>">
                        <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/feed') ?>">
                        <button type="submit" class="action-btn flex items-center gap-1 rounded-full px-3 py-1.5 transition hover:bg-amber-100 dark:hover:bg-amber-900/50 <?= !empty($tweet['is_bookmarked']) ? 'action-bookmarked font-bold shadow-sm' : 'text-slate-500 dark:text-slate-400' ?>">
                          <img src="/assets/Black_Icons/black_bookmark.png" alt="bookmark" class="h-4 w-4">
                          <p class="font-medium"><?= (int) ($tweet['bookmarks_count'] ?? 0) ?></p>
                        </button>
                      </form>

                      <button type="button" onclick="openShareModal(<?= (int) $tweet['id_tweet'] ?>)" class="action-btn flex items-center gap-1 rounded-full px-3 py-1.5 transition hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400">
                        <img src="/assets/Black_Icons/black_share.png" alt="share" class="h-4 w-4">
                      </button>
                    </div>
                  </div>
                </div>
              </article>
            <?php endforeach ?>
            <?php else: ?>
              <p class="p-8 text-center text-sm text-slate-500 dark:text-slate-400">
                <?= $currentTab === 'following' ? 'Ton feed Abonnements est vide pour le moment. Suis des comptes pour voir leurs tweets ici.' : 'Aucun tweet a afficher pour le moment.' ?>
              </p>
            <?php endif ?>
          </div>
        </section>
      </div>

      <section id="right" class="hidden select-none xl:flex xl:flex-col xl:gap-4">
        <form id="search-bar" method="GET" action="/search" class="flex items-center gap-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 px-4 py-3 shadow-[0_12px_30px_rgba(15,23,42,0.08)]">
          <button type="submit"><img src="/assets/Black_Icons/black_search.png" alt="search" class="h-5 w-5"></button>
          <input type="text" name="q" placeholder="Chercher" class="w-full bg-transparent text-sm text-slate-700 dark:text-slate-200 outline-none placeholder:text-slate-400">
        </form>

        <div id="suggestions" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 p-3 shadow-[0_12px_30px_rgba(15,23,42,0.08)]">
          <h2 class="mb-2 px-2 font-['Fraunces',serif] text-lg text-slate-900 dark:text-slate-100">Suggestions</h2>
          <div class="flex flex-col gap-2">
            <?php foreach ($suggestUsers as $user): ?>
              <a href="/account?username=<?= urlencode($user['username']) ?>" class="flex items-center gap-3 rounded-xl px-2 py-2 transition hover:bg-slate-100 dark:hover:bg-slate-800">
                <img src="/assets/Black_Icons/Black_profil.png" alt="Profile" class="h-10 w-10 rounded-xl border border-slate-200 dark:border-slate-700 p-1">
                <div class="min-w-0">
                  <p class="truncate text-sm font-semibold text-slate-900 dark:text-slate-100"><?= $user['display_name'] ?></p>
                  <p class="truncate text-xs text-slate-500 dark:text-slate-400">@<?= $user['username'] ?></p>
                </div>
              </a>
            <?php endforeach ?>
          </div>
        </div>

        <div id="top-tweet" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 p-3 shadow-[0_12px_30px_rgba(15,23,42,0.08)]">
          <h2 class="mb-2 px-2 font-['Fraunces',serif] text-lg text-slate-900 dark:text-slate-100">Tendances</h2>
          <div class="space-y-2">
            <?php $trends = $controller->getTrends(); ?>
            <?php if (!empty($trends)): ?>
                <?php foreach ($trends as $trend): ?>
                <a href="/search?q=<?= urlencode('#' . $trend['name']) ?>" class="block rounded-xl px-2 py-2 transition hover:bg-slate-100 dark:hover:bg-slate-800">
                  <p class="text-xs text-slate-500 dark:text-slate-400">En ce moment</p>
                  <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">#<?= htmlspecialchars($trend['name']) ?></p>
                  <p class="text-xs text-slate-500 dark:text-slate-400"><?= (int)$trend['count'] ?> publications</p>
                </a>
                <?php endforeach ?>
            <?php else: ?>
                <p class="px-2 text-xs text-slate-500 dark:text-slate-400">Aucune tendance pour le moment.</p>
            <?php endif ?>
          </div>
        </div>
      </section>
    </div>
  </div>

  <script src="/lib/feed.js"></script>
</body>
</html>
