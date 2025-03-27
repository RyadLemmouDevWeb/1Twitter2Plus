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
          <a href="/messages" class="flex gap-6 items-center cursor-pointer hover:bg-neutral-700 rounded-full p-3 pl-6"><img src="/assets/Icons/answer.png" alt="Message Home" class="w-8 h-8"><p class="h-fit font-bold text-white">Message</p></a>
          <a href="/korg" class="flex gap-6 items-center cursor-pointer hover:bg-neutral-700 rounded-full p-3 pl-6"><img src="/assets/Icons/korg.png" alt="Korg Home" class="w-8 h-8"><p class="h-fit">Korg</p></a>
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

      <section class="w-3/4 h-full border-x border-neutral-600 relative overflow-hidden">
        <div id="container-messages" class="flex flex-col h-full overflow-auto">
        <?php if (isset($conversations)): ?>
          <?php foreach ($conversations as $conversation): ?> 
            <a href="/message?username=<?= $conversation['username'] ?>" class="w-full p-3 flex gap-3 border-b border-neutral-700 hover:bg-neutral-800">
              <img src="<?= $conversation['URL_Profile'] ? $conversation['URL_Profile'] : "/assets/Icons/profile.png" ?>" alt="" class="w-16 h-16">
              <div class="">
                <h2 class=""><?= $conversation['display_name'] ?> <span class="text-neutral-500">@<?= $conversation['username'] ?></span></h2>
                <p class="text-neutral-500"><?= $conversation['msg_content'] ?></p>
              </div>
            </a>
          <?php endforeach ?>
        <?php endif ?>

        <?php foreach ($messageConversation as $message): ?>
          <div class="p-6">
              <div class="w-4/5 h-fit gb-neutral-700 rounded-3xl p-3 pr-6 <?php if(/*$_SESSION['user']['id']*/ 3 == $message['id_user']) { echo ' ml-32 rounded-tr-none bg-blue-700';} else {echo ' rounded-tl-none bg-neutral-700';}?> ml-6 flex gap-3">
                <img src="<?= $message['URL_Profile'] ? $message['URL_Profile'] : "/assets/Icons/profile.png" ?>" alt="profile picture" class="w-8 h-8 self-center">
                <div>
                  <h2><?= $message['display_name'] ?> <span class="text-neutral-400">@<?= $message['username'] ?> <?= $message['date'] ?></span></h2>
                  <p class="text-md"><?= $message['content'] ?></p>
                </div>
              </div>
            </div>
        <?php endforeach ?>
        
        <?php if($_GET['username']): ?>
          <form action="/message?username=<?= $_GET['username'] ?>" method="POST" id="send-to-user" class="flex bg-neutral-700 py-3 px-6 rounded-full absolute bottom-6 w-4/5 left-1/10 gap-6 border-4 border-neutral-900">
            <button type="submit" class="cursor-pointer"><img src="/assets/Icons/send.png" alt="Icone send" class="w-6 h-6"></button>
            <input type="text" name="content" placeholder="Text ..." class="w-full focus:outline-none ">
            <button type="submit" class="bg-indigo-500 text-white py-2 px-4 rounded-lg">Tweeter</button>
          </form>
        <?php endif ?>
      </section>
  </div>
<!-- <script src="/lib/display-Messages.js"></script> -->
</body>
</html>