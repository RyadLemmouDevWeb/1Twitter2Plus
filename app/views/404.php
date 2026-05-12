<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 - 1Twitter2Plus</title>
  <link rel="icon" type="image/svg+xml" href="/favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/output.css">
  <link rel="stylesheet" href="/css/ui-refresh.css">
  <script>
    (function() {
      const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
      if (theme === 'dark') document.documentElement.classList.add('dark');
    })();
  </script>
</head>
<body class="ui-refresh min-h-screen flex items-center justify-center bg-linear-to-br from-amber-50 via-cyan-50 to-orange-100 text-slate-800 dark:text-slate-200 font-['Space_Grotesk',sans-serif] dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
  <div class="text-center p-8 rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-2xl backdrop-blur-sm max-w-md w-full">
    <h1 class="font-['Fraunces',serif] text-6xl text-teal-500 mb-4">404</h1>
    <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-6">Page non trouvée</h2>
    <p class="text-slate-600 dark:text-slate-400 mb-8">Désolé, la page que vous recherchez semble s'être envolée dans le cyber-espace.</p>
    <a href="/feed" class="inline-block rounded-full bg-linear-to-r from-teal-500 to-orange-400 px-8 py-3 text-base font-semibold text-white shadow-lg transition hover:opacity-90">Retour à l'accueil</a>
  </div>
</body>
</html>
