<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>1Twitter2Plus</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/output.css">
  <link rel="stylesheet" href="/css/ui-refresh.css">
</head>
<body class="ui-refresh min-h-screen bg-linear-to-br from-amber-50 via-cyan-50 to-rose-50 text-slate-800 dark:text-slate-200 font-['Space_Grotesk',sans-serif] dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
  <link rel="icon" type="image/svg+xml" href="/favicon.svg">

  <main class="relative z-10 mx-auto w-[95%] xl:w-4/5 px-4 py-4">
    <div class="grid min-h-[calc(100vh-2rem)] grid-cols-1 gap-4 xl:grid-cols-[250px_minmax(0,1fr)]">
      <?php include 'Sidebar.php'; ?>

      <section class="relative flex min-h-[calc(100vh-2rem)] flex-col overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-[0_12px_30_rgba(15,23,42,0.08)] backdrop-blur-sm">
        <header class="border-b border-slate-200 dark:border-slate-700 px-5 py-4 sm:px-6">
          <h1 class="font-['Fraunces',serif] text-2xl text-slate-900 dark:text-slate-100">Korg</h1>
          <p class="text-sm text-slate-500 dark:text-slate-400">Ton copilote IA dans le flux du produit.</p>
        </header>

        <div id="container-korg" class="flex h-full flex-col gap-4 overflow-auto px-4 py-4 pb-28 sm:px-6">
          <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-4 text-sm text-slate-600 dark:text-slate-400">
            Pose une question a Korg pour demarrer la conversation.
          </div>
        </div>

        <form id="send-to-korg" class="absolute bottom-4 left-1/2 flex w-[calc(100%-2rem)] -translate-x-1/2 items-center gap-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-lg sm:w-[calc(100%-3rem)]">
          <button type="submit" class="rounded-xl bg-slate-100 p-2 transition hover:bg-slate-200"><img src="/assets/Black_Icons/black_ping.png" alt="send" class="h-5 w-5"></button>
          <input type="text" id="input-prompt" placeholder="Prompt" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">
          <button type="submit" class="rounded-full bg-linear-to-r from-teal-500 to-orange-400 px-4 py-2 text-sm font-semibold text-white transition hover:opacity-90">Envoyer</button>
        </form>
      </section>
    </div>
  </main>
<script src="/lib/korg.js"></script>
</body>
</html>
