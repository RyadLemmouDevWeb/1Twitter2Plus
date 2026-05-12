<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifications - 1Twitter2Plus</title>
  <link href="/css/output.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/ui-refresh.css">
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="ui-refresh min-h-screen bg-linear-to-br from-amber-50 via-cyan-50 to-rose-50 text-slate-800 dark:text-slate-200 font-['Space_Grotesk',sans-serif] dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
  <div class="relative z-10 mx-auto w-[95%] xl:w-4/5 px-4 py-4">
    <div class="grid min-h-[calc(100vh-2rem)] grid-cols-1 gap-4 xl:grid-cols-[250px_minmax(0,1fr)]">
      <?php include 'Sidebar.php'; ?>

      <section class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 p-6 shadow-[0_12px_30px_rgba(15,23,42,0.08)] backdrop-blur-sm">
        <h1 class="font-['Fraunces',serif] text-2xl text-slate-900 dark:text-slate-100 mb-6">Notifications</h1>

        <?php if (!empty($notifications)): ?>
            <div class="flex flex-col gap-3">
                <?php foreach ($notifications as $n): ?>
                    <div class="flex items-start gap-4 p-4 rounded-2xl border <?= $n['is_read'] ? 'border-slate-100 dark:border-slate-700' : 'border-teal-100 bg-teal-50 dark:bg-teal-900/30/30' ?> transition hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <div class="mt-1">
                            <?php if ($n['type'] === 'like'): ?>
                                <img src="/assets/Black_Icons/black_like.png" class="h-6 w-6">
                            <?php elseif ($n['type'] === 'retweet'): ?>
                                <img src="/assets/Black_Icons/black_retweet.png" class="h-6 w-6">
                            <?php elseif ($n['type'] === 'follow'): ?>
                                <img src="/assets/Black_Icons/Black_profil.png" class="h-6 w-6">
                            <?php elseif ($n['type'] === 'reply'): ?>
                                <img src="/assets/Black_Icons/black_answer.png" class="h-6 w-6">
                            <?php endif ?>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <img src="<?= $n['picture'] ?: '/assets/Black_Icons/Black_profil.png' ?>" class="h-8 w-8 rounded-lg border border-slate-200 dark:border-slate-700 object-cover">
                                <p class="text-sm">
                                    <span class="font-bold text-slate-900 dark:text-slate-100"><?= htmlspecialchars($n['display_name']) ?></span> 
                                    <span class="dark:text-slate-300">
                                    <?php if ($n['type'] === 'like'): ?>
                                        a aimé votre tweet
                                    <?php elseif ($n['type'] === 'retweet'): ?>
                                        a retweeté votre tweet
                                    <?php elseif ($n['type'] === 'follow'): ?>
                                        vous a suivi
                                    <?php elseif ($n['type'] === 'reply'): ?>
                                        a répondu à votre tweet
                                    <?php endif ?>
                                    </span>
                                </p>
                            </div>
                            <?php if ($n['id_tweet']): ?>
                                <p class="text-xs text-slate-500 dark:text-slate-400 italic mt-1 line-clamp-1 border-l-2 border-slate-200 dark:border-slate-700 pl-3">"<?= htmlspecialchars($n['tweet_content']) ?>"</p>
                                <a href="/tweet?id=<?= (int)$n['id_tweet'] ?>" class="text-xs font-semibold text-teal-600 dark:text-teal-400 mt-2 block hover:underline">Voir le tweet</a>
                            <?php endif ?>
                            <p class="text-[10px] text-slate-400 mt-2"><?= $n['date_creation'] ?></p>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        <?php else: ?>
            <div class="text-center py-20">
                <img src="/assets/Black_Icons/black_notifications.png" class="h-12 w-12 mx-auto opacity-20 mb-4">
                <p class="text-slate-500 dark:text-slate-400">Aucune notification pour le moment.</p>
            </div>
        <?php endif ?>
      </section>
    </div>
  </div>
</body>
</html>
