<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thread - 1Twitter2Plus</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/output.css">
  <link rel="stylesheet" href="/css/ui-refresh.css">
</head>
<body class="ui-refresh min-h-screen bg-linear-to-br from-amber-50 via-cyan-50 to-rose-50 text-slate-800 dark:text-slate-200 font-['Space_Grotesk',sans-serif] dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
  <div class="relative z-10 mx-auto w-[95%] xl:w-4/5 px-4 py-4">
    <div class="grid min-h-[calc(100vh-2rem)] grid-cols-1 gap-4 xl:grid-cols-[250px_minmax(0,1fr)_320px]">
      <?php include 'Sidebar.php'; ?>

      <main id="mid" class="overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-[0_12px_30px_rgba(15,23,42,0.08)] backdrop-blur-sm">
        <header class="border-b border-slate-200 dark:border-slate-700 px-5 py-4 flex items-center gap-4">
          <a href="/feed" class="text-teal-600 dark:text-teal-400 hover:bg-teal-50 dark:bg-teal-900/30 p-2 rounded-full transition dark:hover:bg-teal-900/30">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
          </a>
          <h1 class="font-['Fraunces',serif] text-xl text-slate-900 dark:text-slate-100">Post</h1>
        </header>

        <div class="p-4 space-y-6">
          <?php if ($parentTweet): ?>
            <article class="relative pl-4 border-l-2 border-slate-200 dark:border-slate-700 mb-4 opacity-70">
              <div class="flex gap-3">
                <img src="<?= $parentTweet['picture'] ?: '/assets/Black_Icons/Black_profil.png' ?>" class="h-10 w-10 rounded-xl border border-slate-200 dark:border-slate-700 object-cover">
                <div>
                  <p class="text-sm font-bold text-slate-900 dark:text-slate-100"><?= htmlspecialchars($parentTweet['display_name']) ?> <span class="font-normal text-slate-500 dark:text-slate-400">@<?= $parentTweet['username'] ?></span></p>
                  <p class="text-sm mt-1 text-slate-700 dark:text-slate-200 dark:text-slate-300"><?= format_content($parentTweet['content']) ?></p>
                </div>
              </div>
              <a href="/tweet?id=<?= $parentTweet['id_tweet'] ?>" class="absolute inset-0"></a>
            </article>
          <?php endif; ?>

          <article class="space-y-4">
            <div class="flex items-center gap-3">
              <img src="<?= $tweet['picture'] ?: '/assets/Black_Icons/Black_profil.png' ?>" class="h-12 w-12 rounded-2xl border border-slate-200 dark:border-slate-700 object-cover">
              <div>
                <p class="font-bold text-slate-900 dark:text-slate-100 text-lg"><?= htmlspecialchars($tweet['display_name']) ?></p>
                <p class="text-slate-500 dark:text-slate-400">@<?= htmlspecialchars($tweet['username']) ?></p>
              </div>
            </div>

            <div class="text-xl leading-relaxed text-slate-800 dark:text-slate-100">
              <?= format_content($tweet['content']) ?>
            </div>

            <?php if ($tweet['media1']): ?>
              <div class="grid gap-2 md:grid-cols-2">
                <img src="<?= $tweet['media1'] ?>" class="rounded-2xl border border-slate-200 dark:border-slate-700 w-full object-cover">
                <?php if ($tweet['media2']): ?>
                  <img src="<?= $tweet['media2'] ?>" class="rounded-2xl border border-slate-200 dark:border-slate-700 w-full object-cover">
                <?php endif; ?>
              </div>
            <?php endif; ?>

            <div class="py-3 border-y border-slate-100 dark:border-slate-700 flex justify-around items-center">
               <button class="flex items-center gap-2 text-slate-500 dark:text-slate-400 hover:text-sky-500 transition">
                 <img src="/assets/Black_Icons/black_answer.png" class="h-5 w-5 dark:brightness-0 dark:invert">
                 <span><?= (int)$tweet['replies_count'] ?></span>
               </button>
               <button class="flex items-center gap-2 <?= $tweet['is_retweeted'] ? 'text-emerald-500' : 'text-slate-500 dark:text-slate-400' ?> hover:text-emerald-500 transition">
                 <img src="/assets/Black_Icons/black_retweet.png" class="h-5 w-5 dark:brightness-0 dark:invert">
                 <span><?= (int)$tweet['retweets_count'] ?></span>
               </button>
               <button class="flex items-center gap-2 <?= $tweet['is_liked'] ? 'text-rose-500' : 'text-slate-500 dark:text-slate-400' ?> hover:text-rose-500 transition">
                 <img src="/assets/Black_Icons/black_like.png" class="h-5 w-5 dark:brightness-0 dark:invert">
                 <span><?= (int)$tweet['likes_count'] ?></span>
               </button>
               <button class="flex items-center gap-2 <?= $tweet['is_bookmarked'] ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' ?> hover:text-amber-500 transition">
                 <img src="/assets/Black_Icons/black_bookmark.png" class="h-5 w-5 dark:brightness-0 dark:invert">
               </button>
            </div>
          </article>

          <div class="pt-4">
            <form action="/tweet/reply?id=<?= $tweet['id_tweet'] ?>" method="POST" class="flex gap-3">
              <?= csrf_input() ?>
              <img src="<?= $_SESSION['user']['picture'] ?: '/assets/Black_Icons/Black_profil.png' ?>" class="h-10 w-10 rounded-xl border border-slate-200 dark:border-slate-700 object-cover">
              <div class="flex-1 space-y-3">
                <textarea name="content" required placeholder="Poster votre réponse" class="w-full bg-transparent border-none focus:ring-0 text-slate-800 dark:text-slate-100 placeholder:text-slate-500 dark:placeholder:text-slate-400 resize-none" rows="2"></textarea>
                <div class="flex justify-end">
                  <button type="submit" class="bg-teal-500 text-white px-6 py-2 rounded-full font-bold hover:bg-teal-600 transition shadow-md shadow-teal-500/20">Répondre</button>
                </div>
              </div>
            </form>
          </div>

          <div class="divide-y divide-slate-100 dark:divide-slate-800">
            <?php foreach ($replies as $reply): ?>
              <article class="py-4 space-y-2">
                <div class="flex gap-3">
                  <img src="<?= $reply['picture'] ?: '/assets/Black_Icons/Black_profil.png' ?>" class="h-10 w-10 rounded-xl border border-slate-200 dark:border-slate-700 object-cover">
                  <div>
                    <p class="text-sm font-bold text-slate-900 dark:text-slate-100"><?= htmlspecialchars($reply['display_name']) ?> <span class="font-normal text-slate-500 dark:text-slate-400">@<?= $reply['username'] ?></span></p>
                    <p class="text-sm mt-1 text-slate-700 dark:text-slate-200 dark:text-slate-300"><?= format_content($reply['content']) ?></p>
                  </div>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </main>

      <aside id="right" class="hidden xl:block">
      </aside>
    </div>
  </div>
</body>
</html>
