<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login & Signup - 1Twitter2Plus</title>
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
<body class="ui-refresh min-h-screen overflow-auto bg-linear-to-br from-amber-50 via-cyan-50 to-orange-100 text-slate-800 dark:text-slate-200 font-['Space_Grotesk',sans-serif] dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
  <main class="relative z-10 mx-auto grid min-h-screen w-[95%] xl:w-4/5 items-center gap-8 px-4 py-8 lg:grid-cols-[1.15fr_1fr]">
    <section class="rounded-3xl border border-white/60 bg-white/75 p-8 shadow-[0_24px_60px_rgba(15,23,42,0.12)] backdrop-blur-xl dark:bg-slate-900/80 dark:border-slate-800">
      <p class="mb-3 inline-block rounded-full bg-teal-100 dark:bg-teal-900/50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-teal-700 dark:text-teal-300 dark:bg-teal-900/30 dark:text-teal-400">Nouvelle Base</p>
      <h1 class="font-['Fraunces',serif] text-4xl leading-tight text-slate-900 dark:text-slate-100 sm:text-5xl">Reprends ton projet avec un flow plus propre.</h1>
      <p class="mt-5 max-w-lg text-sm leading-relaxed text-slate-600 dark:text-slate-400 sm:text-base">
        Auth rapide, feed social et UI Tailwind-only. Connecte-toi pour continuer ou cree un compte en quelques champs.
      </p>
      <div class="mt-8 grid gap-3 text-sm sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/80 px-4 py-3 dark:bg-slate-800">
          <p class="font-semibold text-slate-900 dark:text-slate-100">Stack</p>
          <p class="mt-1 text-slate-600 dark:text-slate-400">PHP + MySQL + Tailwind</p>
        </div>
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/80 px-4 py-3 dark:bg-slate-800">
          <p class="font-semibold text-slate-900 dark:text-slate-100">Objectif</p>
          <p class="mt-1 text-slate-600 dark:text-slate-400">Clone social evolutif</p>
        </div>
      </div>
    </section>

    <section class="w-full">
      <div class="login-form rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-[0_18px_45px_rgba(15,23,42,0.1)] sm:p-8">
        <div class="skeleton">
          <h2 class="mx-auto mb-4 h-6 w-28 animate-pulse rounded bg-slate-200 dark:bg-slate-800"></h2>
          <div class="mb-3 space-y-3">
            <div class="h-11 animate-pulse rounded-xl bg-slate-200 dark:bg-slate-800"></div>
            <div class="h-11 animate-pulse rounded-xl bg-slate-200 dark:bg-slate-800"></div>
          </div>
          <div class="mb-3 h-11 animate-pulse rounded-xl bg-slate-200 dark:bg-slate-800"></div>
          <p class="mx-auto mt-4 h-4 w-48 animate-pulse rounded bg-slate-200 dark:bg-slate-800"></p>
        </div>

        <div class="form-content hidden">
          <h2 class="font-['Fraunces',serif] text-3xl text-slate-900 dark:text-slate-100">Connexion</h2>
          <p class="mb-5 mt-1 text-sm text-slate-500 dark:text-slate-400">Bon retour sur 1Twitter2Plus.</p>

          <form method="POST" class="space-y-3">
            <?= csrf_input() ?>
            <input type="hidden" name="action" value="login">
            <input type="email" name="email" required placeholder="Email" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">
            <input type="password" name="password" required placeholder="Mot de passe" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">
            <button type="submit" name="login" class="w-full rounded-full bg-linear-to-r from-teal-500 to-orange-400 py-2.5 text-sm font-semibold text-white shadow-[0_10px_18px_rgba(20,184,166,0.3)] transition hover:opacity-90">Se connecter</button>
          </form>

          <p class="mt-4 text-center text-sm text-slate-600 dark:text-slate-400">Pas de compte ? <a href="javascript:void(0);" onclick="showSignup()" class="font-semibold text-teal-700 dark:text-teal-300 dark:text-teal-400 hover:underline">Inscription</a></p>

          <div class="mt-3 text-center text-sm text-rose-600 dark:text-rose-400">
            <?php if (isset($_GET['error'])) { echo htmlspecialchars($_GET['error']); } ?>
          </div>
        </div>
      </div>

      <div class="signup-form mt-4 hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-[0_18px_45px_rgba(15,23,42,0.1)] sm:p-8">
        <div class="skeleton">
          <h2 class="mx-auto mb-4 h-6 w-32 animate-pulse rounded bg-slate-200 dark:bg-slate-800"></h2>
          <div class="mb-3 flex gap-2">
            <div class="h-11 w-1/2 animate-pulse rounded-xl bg-slate-200 dark:bg-slate-800"></div>
            <div class="h-11 w-1/2 animate-pulse rounded-xl bg-slate-200 dark:bg-slate-800"></div>
          </div>
          <div class="mb-3 h-11 animate-pulse rounded-xl bg-slate-200 dark:bg-slate-800"></div>
          <div class="mb-3 h-20 animate-pulse rounded-xl bg-slate-200 dark:bg-slate-800"></div>
        </div>

        <div class="form-content hidden">
          <h2 class="font-['Fraunces',serif] text-3xl text-slate-900 dark:text-slate-100">Inscription</h2>
          <p class="mb-5 mt-1 text-sm text-slate-500 dark:text-slate-400">Cree ton profil et commence a publier.</p>

          <form method="POST" enctype="multipart/form-data" class="space-y-3">
            <?= csrf_input() ?>
            <input type="hidden" name="action" value="register">

            <div class="text-center text-sm text-rose-600 dark:text-rose-400">
              <?php if (isset($_GET['error'])) { echo htmlspecialchars($_GET['error']); } ?>
            </div>
            <div class="text-center text-sm text-teal-600 dark:text-teal-400">
              <?php if (isset($_GET['message'])) { echo htmlspecialchars($_GET['message']); } ?>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
              <input type="text" name="firstname" required placeholder="Prenom" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">
              <input type="text" name="lastname" required placeholder="Nom" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">
            </div>

            <input type="text" name="display_name" required placeholder="Nom d'affichage" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">

            <div class="grid gap-3 sm:grid-cols-2">
              <input type="text" name="username" required placeholder="Username" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">
              <input type="email" name="email" required placeholder="Email" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
              <input type="password" name="password" required placeholder="Mot de passe" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">
              <input type="date" name="birthdate" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
              <input type="text" name="phone" placeholder="Telephone" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">
              <select name="genre" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-700 dark:text-slate-200 outline-none transition focus:border-teal-400">
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
            </div>

            <button type="submit" class="w-full rounded-full bg-linear-to-r from-teal-500 to-orange-400 py-2.5 text-sm font-semibold text-white shadow-[0_10px_18px_rgba(20,184,166,0.3)] transition hover:opacity-90">Creer mon compte</button>

            <p class="text-center text-sm text-slate-600 dark:text-slate-400">Deja inscrit ? <a href="javascript:void(0);" onclick="showLogin()" class="font-semibold text-teal-700 dark:text-teal-300 dark:text-teal-400 hover:underline">Connexion</a></p>
          </form>
        </div>
      </div>
    </section>
  </main>

  <script src="/lib/login.js"></script>
</body>
</html>
