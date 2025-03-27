<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>1Twitter2Plus</title>
  <link rel="stylesheet" href="/css/output.css">
</head>
<body class="dark:bg-neutral-900 dark:text-neutral-300 w-screen h-screen">
  <div class="flex justify-between w-4/5 m-auto h-full gap-8">
    <nav id="left" class="w-1/4 flex flex-col justify-between">
      <img src="/assets/White_Icons/icons_twitter-.png" alt="Logo" class="w-16 h-16 mb-3 mt-2">
      <ul class="flex flex-col gap-9 text-2xl select-none">
        <a href="/feed" class="flex gap-6 items-center cursor-pointer hover:bg-neutral-700 rounded-full p-3 pl-6"><img src="/assets/Icons/home.png" alt="Icone Home" class="w-8 h-8"><p class="h-fit">Accueil</p></a>
        <a href="/feed" class="flex gap-6 items-center cursor-pointer hover:bg-neutral-700 rounded-full p-3 pl-6"><img src="/assets/Icons/search.png" alt="Search Home" class="w-8 h-8"><p class="h-fit">Explorer</p></a>
        <a href="/messages" class="flex gap-6 items-center cursor-pointer hover:bg-neutral-700 rounded-full p-3 pl-6"><img src="/assets/Icons/answer.png" alt="Message Home" class="w-8 h-8"><p class="h-fit">Message</p></a>
        <a href="/korg" class="flex gap-6 items-center cursor-pointer hover:bg-neutral-700 rounded-full p-3 pl-6"><img src="/assets/Icons/korg.png" alt="Korg Home" class="w-8 h-8"><p class="h-fit font-bold text-white">Korg</p></a>
        <a href="/account" class="flex gap-6 items-center cursor-pointer hover:bg-neutral-700 rounded-full p-3 pl-6"><img src="/assets/Icons/profile.png" alt="Profil Home" class="w-8 h-8"><p class="h-fit">Profil</p></a>
      </ul>
      <div>
        <button class="bg-neutral-200 text-neutral-800 w-full py-5 rounded-full font-bold text-2xl cursor-pointer hover:bg-neutral-400 hover:text-neutral-900">Poster</button>
        <div class="flex my-8 gap-3 px-6 py-3 rounded-full  cursor-pointer hover:bg-neutral-700">
          <img src="/assets/Icons/profile.png" alt="Picture image" class="w-12 h-12">
          <div class="name-profile">
          <p class="font-bold"><?= $_SESSION['user']['firstname'] ?> <?= $_SESSION['user']['lastname'] ?></p>
          <p class="text-neutral-400">@<?= $_SESSION['user']['username'] ?></p>
          </div>
        </div>
      </div>
    </nav>

    <section class="w-3/4 h-full border-l border-r relative overflow-hidden">
      <div id="container-korg" class="flex flex-col gap-6 h-full overflow-auto pb-12">

      </div>
      <form id="send-to-korg" class="flex bg-neutral-700 py-3 px-6 rounded-full absolute bottom-6 w-4/5 left-1/10 gap-6 border-4 border-neutral-900">
        <button type="submit" class="cursor-pointer"><img src="/assets/Icons/send.png" alt="Icone send" class="w-6 h-6"></button>
        <input type="text" id="input-prompt" placeholder="Prompt" class="w-full focus:outline-none">
      </form>
    </section>
  </div>
<script src="/lib/korg.js"></script>
</body>
</html>