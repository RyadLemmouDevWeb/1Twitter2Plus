<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messages - 1Twitter2Plus</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/output.css">
  <link rel="stylesheet" href="/css/ui-refresh.css">
</head>
<body class="ui-refresh min-h-screen bg-linear-to-br from-amber-50 via-cyan-50 to-rose-50 text-slate-800 dark:text-slate-200 font-['Space_Grotesk',sans-serif] dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
  <div class="relative z-10 mx-auto w-[95%] xl:w-4/5 px-4 py-4">
    <div class="grid min-h-[calc(100vh-2rem)] grid-cols-1 gap-4 xl:grid-cols-[250px_minmax(0,1fr)]">
      <?php include 'Sidebar.php'; ?>

      <section class="relative flex flex-col overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-[0_12px_30px_rgba(15,23,42,0.08)] backdrop-blur-sm">
        <header class="border-b border-slate-200 dark:border-slate-700 px-5 py-4 flex items-center justify-between">
          <div>
            <h1 class="font-['Fraunces',serif] text-2xl text-slate-900 dark:text-slate-100">Messages</h1>
          </div>
          <div class="flex items-center gap-3">
            <button onclick="openNewMessageModal()" class="rounded-full bg-teal-500 px-4 py-2 text-xs font-semibold text-white transition hover:bg-teal-600 shadow-sm">Nouveau message</button>
            <?php if (isset($_GET['username'])): ?>
              <a href="/messages" class="text-sm font-semibold text-teal-600 dark:text-teal-400 hover:underline">← Retour</a>
            <?php endif; ?>
          </div>
        </header>

        <div id="container-messages" class="flex-1 overflow-auto p-4 space-y-4">
          <?php if (!isset($_GET['username'])): ?>
            <!-- Discussion List -->
            <?php if (!empty($conversations)): ?>
              <div class="flex flex-col divide-y divide-slate-100 dark:divide-slate-800">
                <?php foreach ($conversations as $conversation): ?>
                  <a href="/message?username=<?= urlencode($conversation['username']) ?>" class="flex items-center gap-4 py-4 px-2 transition hover:bg-slate-50 dark:hover:bg-slate-800/50">
                    <img src="<?= $conversation['URL_Profile'] ?: '/assets/Black_Icons/Black_profil.png' ?>" class="h-12 w-12 rounded-2xl border border-slate-200 dark:border-slate-700 object-cover">
                    <div class="min-w-0 flex-1">
                      <div class="flex items-center justify-between">
                        <p class="truncate text-sm font-bold text-slate-900 dark:text-slate-100"><?= htmlspecialchars($conversation['display_name']) ?></p>
                        <p class="text-[10px] text-slate-400"><?= $conversation['date'] ?></p>
                      </div>
                      <p class="truncate text-xs text-slate-500 dark:text-slate-400">@<?= htmlspecialchars($conversation['username']) ?></p>
                      <p class="truncate text-sm text-slate-600 dark:text-slate-300 mt-1"><?= htmlspecialchars($conversation['msg_content']) ?></p>
                    </div>
                  </a>
                <?php endforeach ?>
              </div>
            <?php else: ?>
              <div class="flex flex-col items-center justify-center py-20 opacity-40 text-center">
                <img src="/assets/Black_Icons/black_answer.png" class="h-12 w-12 mb-4 dark:brightness-0 dark:invert">
                <p class="text-slate-500 dark:text-slate-400">Aucune conversation pour le moment.</p>
              </div>
            <?php endif ?>
          <?php else: ?>
            <?php if (!empty($messageConversation)): ?>
              <?php foreach ($messageConversation as $message): ?>
                <?php $isMine = (($_SESSION['user']['id'] ?? 0) == $message['id_user']); ?>
                <div class="flex <?= $isMine ? 'justify-end' : 'justify-start' ?>">
                  <div class="max-w-[80%] rounded-2xl px-4 py-2 shadow-sm <?= $isMine ? 'bg-teal-500 text-white rounded-br-sm' : 'bg-slate-100 text-slate-800 dark:text-slate-200 dark:bg-slate-800 rounded-bl-sm' ?>">
                    <p class="text-sm"><?= htmlspecialchars($message['content']) ?></p>
                    <p class="text-[10px] mt-1 opacity-70 text-right"><?= $message['date'] ?></p>
                  </div>
                </div>
              <?php endforeach ?>
            <?php else: ?>
              <div class="text-center py-10 opacity-50">
                <p class="text-sm">Aucun message. Soyez le premier à en envoyer un !</p>
              </div>
            <?php endif ?>
          <?php endif; ?>
        </div>

        <?php if (isset($_GET['username'])): ?>
          <div class="p-4 border-t border-slate-200 dark:border-slate-700 bg-white/50 backdrop-blur-md dark:bg-slate-900/50">
            <form action="/message?username=<?= urlencode($_GET['username']) ?>" method="POST" class="flex items-center gap-3">
              <?= csrf_input() ?>
              <input type="text" name="content" required placeholder="Ecrire un message..." class="flex-1 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 outline-none transition focus:border-teal-400">
              <button type="submit" class="rounded-full bg-teal-500 p-2.5 text-white transition hover:bg-teal-600">
                <img src="/assets/Icons/send.png" class="h-5 w-5 invert">
              </button>
            </form>
          </div>
        <?php endif ?>
      </section>
    </div>
  </div>
</body>
</html>
