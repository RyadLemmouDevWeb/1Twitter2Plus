<?php
$current_uri = parse_url($_SERVER['REQUEST_URI'] ?? '/feed', PHP_URL_PATH);
?>
<nav id="left" class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 p-4 shadow-[0_12px_30px_rgba(15,23,42,0.08)] backdrop-blur-sm xl:sticky xl:top-4 xl:h-[calc(100vh-2rem)] xl:flex xl:flex-col xl:justify-between">
    <div>
        <div class="mb-5 flex items-center gap-3">
            <img src="/favicon.svg" alt="Logo" class="h-11 w-11 rounded-2xl bg-slate-900 p-1">
            <div>
                <p class="font-['Fraunces',serif] text-xl font-semibold text-slate-900 dark:text-slate-100">1Twitter2Plus</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Social Sandbox</p>
            </div>
        </div>
        <ul class="grid grid-cols-2 gap-2 text-sm font-medium xl:grid-cols-1 xl:text-base">
            <?php
            $navItems = [
                ['href' => '/feed', 'icon' => '/assets/Black_Icons/black_home.png', 'label' => 'Accueil', 'active' => ($current_uri === '/feed')],
                ['href' => '/search', 'icon' => '/assets/Black_Icons/black_search.png', 'label' => 'Explorer', 'active' => ($current_uri === '/search')],
                ['href' => '/messages', 'icon' => '/assets/Black_Icons/black_answer.png', 'label' => 'Messages', 'active' => (strpos($current_uri, '/message') === 0)],
                ['href' => '/notifications', 'icon' => '/assets/Black_Icons/black_notifications.png', 'label' => 'Notifications', 'active' => ($current_uri === '/notifications')],
                ['href' => '/bookmarks', 'icon' => '/assets/Black_Icons/black_bookmark.png', 'label' => 'Signets', 'active' => ($current_uri === '/bookmarks')],
                ['href' => '/korg', 'icon' => '/assets/Black_Icons/Black_Korg.png', 'label' => 'Korg', 'active' => ($current_uri === '/korg')],
                ['href' => '/account', 'icon' => '/assets/Black_Icons/Black_profil.png', 'label' => 'Profil', 'active' => ($current_uri === '/account'), 'extra_class' => 'col-span-2 xl:col-span-1'],
            ];

            foreach ($navItems as $item):
                $isActive = $item['active'];
                $class = $isActive 
                    ? "flex items-center gap-3 rounded-2xl bg-teal-50 dark:bg-teal-900/30 px-4 py-3 text-teal-700 dark:text-teal-400 shadow-sm transition hover:bg-teal-100 dark:hover:bg-teal-900/50" 
                    : "flex items-center gap-3 rounded-2xl px-4 py-3 text-slate-600 dark:text-slate-400 transition hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-200";
                if (isset($item['extra_class'])) $class .= " " . $item['extra_class'];
            ?>
                <a href="<?= $item['href'] ?>" class="<?= $class ?>">
                    <img src="<?= $item['icon'] ?>" alt="<?= $item['label'] ?>" class="h-5 w-5 dark:brightness-0 dark:invert">
                    <p class="<?= $isActive ? 'font-semibold' : '' ?>"><?= $item['label'] ?></p>
                </a>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="mt-6">
        <button id="theme-toggle" class="mb-4 flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-slate-600 dark:text-slate-400 transition hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-200">
            <img id="theme-toggle-icon" src="/assets/Icons/dark_mode.png" alt="Theme Toggle" class="h-5 w-5 dark:brightness-0 dark:invert">
            <p class="font-semibold text-sm xl:text-base">Changer le thème</p>
        </button>
        <button id="post-button" onclick="document.getElementById('tweet-form')?.classList.remove('hidden'); document.getElementById('tweet-form')?.classList.add('flex'); document.getElementById('overlay')?.classList.remove('hidden');" class="w-full rounded-full bg-linear-to-r from-teal-500 to-orange-400 py-3 text-base font-semibold text-white shadow-[0_10px_18px_rgba(20,184,166,0.3)] transition hover:-translate-y-px hover:shadow-[0_14px_24px_rgba(20,184,166,0.35)]">Poster</button>
        
        <div class="mt-4 flex items-center gap-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 cursor-pointer" id="profileMenu">
            <img src="<?= !empty($_SESSION['user']['picture']) ? htmlspecialchars($_SESSION['user']['picture']) : '/assets/Black_Icons/Black_profil.png' ?>" alt="Picture" class="h-11 w-11 rounded-2xl border border-slate-200 dark:border-slate-700 p-1">
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-slate-900 dark:text-slate-100"><?= $_SESSION['user']['firstname'] ?> <?= $_SESSION['user']['lastname'] ?></p>
                <p class="truncate text-xs text-slate-500 dark:text-slate-400">@<?= $_SESSION['user']['username'] ?></p>
            </div>
        </div>

        <div id="logoutMenu" class="mt-2 hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 text-sm">
            <a href="/logout" class="font-semibold text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:text-rose-300">Se déconnecter</a>
        </div>
    </div>
</nav>

<div id="overlay" class="fixed inset-0 z-40 hidden bg-slate-900/45 backdrop-blur-sm" onclick="closeAllModals()"></div>

<div id="tweet-form" class="fixed inset-0 z-50 hidden items-center justify-center px-4 pointer-events-none">
    <div class="w-full max-w-2xl rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-2xl pointer-events-auto">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-['Fraunces',serif] text-2xl text-slate-900 dark:text-slate-100">Nouveau Post</h2>
            <button onclick="closeAllModals()" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
        </div>
        <form action="/feed" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_input() ?>
            <div class="flex gap-3">
                <img src="<?= !empty($_SESSION['user']['picture']) ? htmlspecialchars($_SESSION['user']['picture']) : '/assets/Black_Icons/Black_profil.png' ?>" class="h-12 w-12 rounded-2xl object-cover">
                <textarea name="content" placeholder="Quoi de neuf ?" maxlength="140" class="min-h-32 w-full rounded-2xl border-none bg-transparent px-2 py-1 text-lg text-slate-800 dark:text-slate-200 outline-none transition focus:ring-0 placeholder:text-slate-400" autofocus></textarea>
            </div>
            
            <div class="space-y-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 p-4">
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <img src="/assets/Black_Icons/black_add_a_photo.png" class="h-4 w-4 opacity-60 dark:invert">
                    <span>Médias (Photos)</span>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <?php for($i=1; $i<=4; $i++): ?>
                    <div class="space-y-1.5">
                        <p class="text-[10px] font-bold text-slate-400">PHOTO <?= $i ?></p>
                        <input type="file" name="local_media<?= $i ?>" class="w-full text-[11px] text-slate-500 file:mr-2 file:rounded-xl file:border-0 file:bg-teal-50 dark:bg-teal-900/30 file:px-3 file:py-1 file:text-[11px] file:font-semibold file:text-teal-700 dark:text-teal-300 hover:file:bg-teal-100 dark:bg-teal-900/50 dark:file:bg-teal-900/30 dark:file:text-teal-400">
                        <input type="url" name="media<?= $i ?>" placeholder="Ou URL de l'image" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-1.5 text-[11px] outline-none focus:border-teal-400 dark:text-slate-200">
                    </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" class="rounded-full border border-slate-200 dark:border-slate-700 px-6 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 transition hover:bg-slate-100 dark:hover:bg-slate-800" onclick="closeAllModals()">Annuler</button>
                <button type="submit" class="rounded-full bg-linear-to-r from-teal-500 to-orange-400 px-8 py-2 text-sm font-bold text-white shadow-lg shadow-teal-500/20 transition hover:opacity-90 active:scale-95">Publier</button>
            </div>
        </form>
    </div>
</div>

<div id="shareTweetModal" class="fixed inset-0 z-50 hidden items-center justify-center px-4 pointer-events-none">
    <div class="w-full max-w-md rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-2xl pointer-events-auto">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-['Fraunces',serif] text-2xl text-slate-900 dark:text-slate-100">Partager le post</h2>
            <button onclick="closeAllModals()" class="text-slate-400 hover:text-slate-600 dark:text-slate-400 text-2xl leading-none">&times;</button>
        </div>
        <form action="/message/share" method="POST" id="shareForm">
            <?= csrf_input() ?>
            <input type="hidden" name="tweet_id" id="shareTweetId">
            <div class="relative">
                <input type="text" id="shareUserSearchInput" placeholder="Rechercher un destinataire..." class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3 text-sm text-slate-800 dark:text-slate-200 outline-none transition focus:border-teal-400">
                <div id="shareUserSearchResults" class="mt-2 max-h-60 overflow-y-auto rounded-2xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-lg hidden"></div>
            </div>
            <input type="hidden" name="receiver_username" id="shareReceiverUsername">
            <div id="selectedReceiver" class="mt-3 hidden items-center gap-2 rounded-2xl bg-teal-50 dark:bg-teal-900/30 p-2 text-sm text-teal-700 dark:text-teal-300 dark:text-teal-400">
                <span id="selectedReceiverName" class="font-semibold"></span>
                <button type="button" onclick="clearSelectedReceiver()" class="ml-auto text-teal-900 font-bold dark:text-teal-200">&times;</button>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit" id="shareSubmitBtn" disabled class="rounded-full bg-linear-to-r from-teal-500 to-orange-400 px-6 py-2 text-sm font-semibold text-white transition opacity-50 cursor-not-allowed">Envoyer</button>
            </div>
        </form>
    </div>
</div>

<div id="newMessageModal" class="fixed inset-0 z-50 hidden items-center justify-center px-4 pointer-events-none">
    <div class="w-full max-w-md rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-2xl pointer-events-auto">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-['Fraunces',serif] text-2xl text-slate-900 dark:text-slate-100">Nouveau message</h2>
            <button onclick="closeAllModals()" class="text-slate-400 hover:text-slate-600 dark:text-slate-400 text-2xl leading-none">&times;</button>
        </div>
        <div class="relative">
            <input type="text" id="newUserSearchInput" placeholder="Rechercher un utilisateur..." class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3 text-sm text-slate-800 dark:text-slate-200 outline-none transition focus:border-teal-400">
            <div id="newUserSearchResults" class="mt-2 max-h-60 overflow-y-auto rounded-2xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-lg hidden"></div>
        </div>
    </div>
</div>

<script>
(function() {
    const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
        const icon = document.getElementById('theme-toggle-icon');
        if (icon) icon.src = '/assets/Icons/light_mode.png';
    }
})();

document.getElementById('theme-toggle')?.addEventListener('click', () => {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    const icon = document.getElementById('theme-toggle-icon');
    if (icon) icon.src = isDark ? '/assets/Icons/light_mode.png' : '/assets/Icons/dark_mode.png';
});

document.getElementById('profileMenu')?.addEventListener('click', function() {
    document.getElementById('logoutMenu')?.classList.toggle('hidden');
});

function closeAllModals() {
    document.getElementById('tweet-form')?.classList.add('hidden');
    document.getElementById('tweet-form')?.classList.remove('flex');
    document.getElementById('shareTweetModal')?.classList.add('hidden');
    document.getElementById('shareTweetModal')?.classList.remove('flex');
    document.getElementById('newMessageModal')?.classList.add('hidden');
    document.getElementById('newMessageModal')?.classList.remove('flex');
    document.getElementById('overlay')?.classList.add('hidden');
}

function openShareModal(tweetId) {
    const el = document.getElementById('shareTweetId');
    if (el) el.value = tweetId;
    document.getElementById('shareTweetModal')?.classList.remove('hidden');
    document.getElementById('shareTweetModal')?.classList.add('flex');
    document.getElementById('overlay')?.classList.remove('hidden');
}

function openNewMessageModal() {
    document.getElementById('newMessageModal')?.classList.remove('hidden');
    document.getElementById('newMessageModal')?.classList.add('flex');
    document.getElementById('overlay')?.classList.remove('hidden');
}

function selectReceiver(username, displayName) {
    const receiverInput = document.getElementById('shareReceiverUsername');
    if (receiverInput) {
        receiverInput.value = username;
        const nameEl = document.getElementById('selectedReceiverName');
        if (nameEl) nameEl.textContent = '@' + username;
        document.getElementById('selectedReceiver')?.classList.remove('hidden');
        document.getElementById('selectedReceiver')?.classList.add('flex');
        const btn = document.getElementById('shareSubmitBtn');
        if (btn) {
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
}

function clearSelectedReceiver() {
    const el = document.getElementById('shareReceiverUsername');
    if (el) el.value = '';
    document.getElementById('selectedReceiver')?.classList.add('hidden');
    const btn = document.getElementById('shareSubmitBtn');
    if (btn) {
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

document.getElementById('shareUserSearchInput')?.addEventListener('input', function(e) {
    const q = e.target.value.trim();
    if (q.length < 2) return;
    fetch('/api/search/users?q=' + encodeURIComponent(q))
        .then(r => r.json())
        .then(users => {
            const results = document.getElementById('shareUserSearchResults');
            if (!results) return;
            results.innerHTML = users.map(u => '<div onclick="selectReceiver(\'' + u.username + '\', \'' + u.display_name + '\')" class="p-3 hover:bg-slate-50 dark:bg-slate-800 dark:hover:bg-slate-800 cursor-pointer dark:hover:bg-slate-700">@' + u.username + ' (' + u.display_name + ')</div>').join('');
            results.classList.remove('hidden');
        });
});

document.getElementById('newUserSearchInput')?.addEventListener('input', function(e) {
    const q = e.target.value.trim();
    if (q.length < 2) return;
    fetch('/api/search/users?q=' + encodeURIComponent(q))
        .then(r => r.json())
        .then(users => {
            const results = document.getElementById('newUserSearchResults');
            if (!results) return;
            results.innerHTML = users.map(u => '<div onclick="window.location.href=\'/message?username=' + u.username + '\'" class="p-3 hover:bg-slate-50 dark:bg-slate-800 dark:hover:bg-slate-800 cursor-pointer dark:hover:bg-slate-700">@' + u.username + ' (' + u.display_name + ')</div>').join('');
            results.classList.remove('hidden');
        });
});
</script>
