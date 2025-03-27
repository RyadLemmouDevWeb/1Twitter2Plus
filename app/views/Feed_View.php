<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>1Twitter2Plus</title>
  <link href="/css/output.css" rel="stylesheet">
</head>
<body class="dark:bg-neutral-900 dark:text-neutral-300 w-screen h-screen">
  <div class="flex justify-between w-4/5 m-auto h-full gap-8">
    <nav id="left" class="w-1/4 flex flex-col justify-between">
      <img src="assets/White_Icons/icons_twitter-.png" alt="Logo" class="w-16 h-16 mb-3 mt-2">
      <ul class="flex flex-col gap-9 text-2xl select-none">
        <a href="/feed" class="flex gap-6 items-center cursor-pointer hover:bg-neutral-800 rounded-full p-3 pl-6"><img src="/assets/Icons/home.png" alt="Icone Home" class="w-8 h-8"><p class="h-fit font-bold text-white">Accueil</p></a>
        <a href="/feed" class="flex gap-6 items-center cursor-pointer hover:bg-neutral-800 rounded-full p-3 pl-6"><img src="/assets/Icons/search.png" alt="Search Home" class="w-8 h-8"><p class="h-fit">Explorer</p></a>
        <a href="/messages" class="flex gap-6 items-center cursor-pointer hover:bg-neutral-800 rounded-full p-3 pl-6"><img src="/assets/Icons/answer.png" alt="Message Home" class="w-8 h-8"><p class="h-fit">Message</p></a>
        <a href="/korg" class="flex gap-6 items-center cursor-pointer hover:bg-neutral-800 rounded-full p-3 pl-6"><img src="/assets/Icons/korg.png" alt="Korg Home" class="w-8 h-8"><p class="h-fit">Korg</p></a>
        <a href="/account" class="flex gap-6 items-center cursor-pointer hover:bg-neutral-800 rounded-full p-3 pl-6"><img src="/assets/Icons/profile.png" alt="Profil Home" class="w-8 h-8"><p class="h-fit">Profil</p></a>
      </ul>
      <div>
        <button id="post-button" class="bg-neutral-200 text-neutral-800 w-full py-5 rounded-full font-bold text-2xl cursor-pointer hover:bg-neutral-400 hover:text-neutral-900">Poster</button>
        <div class="flex my-8 gap-3 px-6 py-3 rounded-full cursor-pointer hover:bg-neutral-800">
          <img src="/assets/Icons/profile.png" alt="Picture image" class="w-12 h-12">
          <div class="name-profile">
            <p class="font-bold"><?= $_SESSION['user']['firstname'] ?> <?= $_SESSION['user']['lastname'] ?></p>
            <p class="text-neutral-400">@<?= $_SESSION['user']['username'] ?></p>
          </div>
        </div>
      </div>
    </nav>

    <div id="mid" class="border-x border-neutral-600 w-1/2 overflow-hidden">
      <div class="flex justify-around w-full border-b border-neutral-600">
        <button class="hover:border-b-4 hover:border-gray-600 border-b-4 border-blue-400 p-3 box-border h-16 text-white font-bold cursor-pointer">Pour vous</button>
        <button class="hover:border-b-4 hover:border-gray-600 hover:text-white active:border-b-4 active:border-blue-400 p-3 box-border h-16 cursor-pointer">Abonnements</button>
      </div>
      <section class="container-feed max-h-full overflow-auto relative">
        <div id="skeleton-loader" class="flex flex-col gap-4 p-4">
          <div class="w-full border-b border-neutral-600 p-3 pr-15 flex gap-4">
            <div class="w-12 h-12 rounded-full bg-gray-200"></div>
            <div class="w-full flex flex-col gap-2">
              <div class="h-4 w-1/2 bg-gray-200"></div>
              <div class="h-4 w-3/4 bg-gray-200"></div>
              <div class="h-4 w-1/3 bg-gray-200"></div>
            </div>
          </div>
          <div class="w-full border-b border-neutral-600 p-3 pr-15 flex gap-4">
            <div class="w-12 h-12 rounded-full bg-gray-200"></div>
            <div class="w-full flex flex-col gap-2">
              <div class="h-4 w-1/2 bg-gray-200"></div>
              <div class="h-4 w-3/4 bg-gray-200"></div>
              <div class="h-4 w-1/3 bg-gray-200"></div>
            </div>
          </div>
          <div class="w-full border-b border-neutral-600 p-3 pr-15 flex gap-4">
            <div class="w-12 h-12 rounded-full bg-gray-200"></div>
            <div class="w-full flex flex-col gap-2">
              <div class="h-4 w-1/2 bg-gray-200"></div>
              <div class="h-4 w-3/4 bg-gray-200"></div>
              <div class="h-4 w-1/3 bg-gray-200"></div>
            </div>
          </div>
        </div>
        <div id="feed" class="flex flex-col h-full max-h-fit mb-16">
          <?php foreach ($tweetForFeed as $tweet): ?>
            <div class="w-full border-b border-neutral-600 p-3 pr-15 flex gap-1 hover:bg-neutral-800">
              <img src="<?= $tweet["picture"] ? $tweet["picture"] : "/assets/Icons/profile.png" ?>" alt="Profile picture" class="w-12 h-12">
              <div class="w-full">
                <div class="flex gap-2 mt-1">
                  <p class="text-white font-bold"><?= $tweet['display_name'] ?></p>
                  <p><?= "@".$tweet["username"] ?></p>
                  <p><?= "᛫ ".$tweet["creation_date"] ?></p>
                </div>
                <p class="text-white"><?= $tweet["content"] ?></p>
                <div class="my-3">
                  <?php if ($tweet["media1"]): ?>
                    <img class="rounded-lg border-neutral-700 border" src="<?= $tweet['media1'] ?>">
                    <?php if ($tweet["media2"]): ?>
                      <img class="rounded-lg border-neutral-700 border" src="<?= $tweet['media2'] ?>">
                      <?php if ($tweet["media3"]): ?>
                        <img class="rounded-lg border-neutral-700 border" src="<?= $tweet['media3'] ?>">
                        <?php if ($tweet["media4"]): ?>
                          <img class="rounded-lg border-neutral-700 border" src="<?= $tweet['media4'] ?>">
                        <?php endif ?>
                      <?php endif ?>
                    <?php endif ?>
                  <?php endif ?>
                </div>
                <div class="flex justify-around mt-2">
                  <div class="flex items-center cursor-pointer">
                    <img src="/assets/Icons/answer.png" alt="answer icon" class="w-5 h-5 mr-1">
                    <p class="w-fit">3<?php //$tweet["commment"] ?></p>
                  </div>
                  <div class="flex items-center cursor-pointer">
                    <img src="/assets/Icons/retweet.png" alt="retweet icon" class="w-5 h-5 mr-1">
                    <p class="w-fit">5<?php //$tweet["rt"] ?></p>
                  </div>
                  <div class="flex items-center cursor-pointer">
                    <img src="/assets/Icons/like.png" alt="Like icon" class="w-5 h-5 mr-1">
                    <p class="w-fit">12<?php //$tweet["like"] ?></p>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach ?>
        </div>
      </section>
    </div>
    <section id="right" class="w-1/4 flex flex-col justify-between select-none">
      <form id="search-bar" method="GET" class="mt-2 border border-neutral-400 rounded-full px-6 py-3 flex w-full">
        <button type="submit"><img src="/assets/Icons/search.png" alt="" class="w-8 h-8 cursor-pointer aspect-square "></button>
        <input type="text" placeholder="Chercher" class="active:border-none focus:outline-none w-4/5 ml-3">
      </form>
      <div id="suggestions" class="border border-neutral-400 rounded-lg">
        <h2 class="p-3">Suggestions</h2>

        <div class="flex flex-col gap-2">
        <?php foreach ($suggestUsers as $user): ?>
            <div class="flex gap-3 px-6 py-3 cursor-pointer hover:bg-neutral-800">
              <img src="/assets/Icons/profile.png" alt="Picture image" class="w-12 h-12">
              <div class="name-profile">
                <p class="font-bold"><?= $user["display_name"] ?></p>
                <p class="text-neutral-400">@<?= $user["username"] ?></p>
              </div>
            </div>
          <?php endforeach ?>
        </div>
      </div>
      <div id="top-tweet" class="border border-neutral-400 rounded-lg mb-8">
        <h2 class="p-3">Tendances</h2>
        <div class="flex flex-col px-6 py-3 cursor-pointer hover:bg-neutral-800">
          <p class="text-neutral-400">Le chat - Tendances</p>
          <p class="font-bold">#Hoppy</p>
          <p class="text-neutral-400">99999 publications</p>
        </div>
        <div class="flex flex-col px-6 py-3 cursor-pointer hover:bg-neutral-800">
          <p class="text-neutral-400">Le chat - Tendances</p>
          <p class="font-bold">#Hoppy</p>
          <p class="text-neutral-400">99999 publications</p>
        </div>
        <div class="flex flex-col px-6 py-3 cursor-pointer hover:bg-neutral-800">
          <p class="text-neutral-400">Le chat - Tendances</p>
          <p class="font-bold">#Hoppy</p>
          <p class="text-neutral-400">99999 publications</p>
        </div>
        <div class="flex flex-col px-6 py-3 cursor-pointer hover:bg-neutral-800">
          <p class="text-neutral-400">Le chat - Tendances</p>
          <p class="font-bold">#Hoppy</p>
          <p class="text-neutral-400">99999 publications</p>
        </div>
      </div>
    </section>
  </div>
  <div id="overlay" class="fixed inset-0 hidden"></div>
  <div id="tweet-form" class="fixed inset-0  justify-center items-center hidden z-50">
    <div class="bg-neutral-900 p-6 rounded-lg w-1/3">
      <form action="/feed" method="POST">
        <textarea name="content" placeholder="Quoi de neuf ?" maxlength="140" class="w-full p-3 bg-neutral-800 text-white border border-neutral-700 rounded-lg mb-4"></textarea>
        <input type="url" name="media1" placeholder="Lien image 1" class="w-full p-3 bg-neutral-800 text-white border border-neutral-700 rounded-lg mb-2">
        <input type="url" name="media2" placeholder="Lien image 2" class="w-full p-3 bg-neutral-800 text-white border border-neutral-700 rounded-lg mb-2">
        <input type="url" name="media3" placeholder="Lien image 3" class="w-full p-3 bg-neutral-800 text-white border border-neutral-700 rounded-lg mb-2">
        <input type="url" name="media4" placeholder="Lien image 4" class="w-full p-3 bg-neutral-800 text-white border border-neutral-700 rounded-lg mb-4">
        <button type="submit" class="bg-indigo-500 text-white py-2 px-4 rounded-lg">Tweeter</button>
        <button type="button" class="bg-gray-500 text-white py-2 px-4 rounded-lg" onclick="document.getElementById('tweet-form').classList.add('hidden'); document.getElementById('overlay').classList.add('hidden');">Annuler</button>
      </form>
    </div>
  </div>
  <script src="/lib/feed.js"></script>
</body>
</html>