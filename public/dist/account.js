function togglePopup() {
    const popup = document.getElementById('editProfilePopup');
    const overlay = document.getElementById('popupOverlay');
    popup.classList.toggle('hidden');
    overlay.classList.toggle('hidden');
}

const profileMenu = document.getElementById('profileMenu');
const logoutMenu = document.getElementById('logoutMenu');

profileMenu.addEventListener('click', function() {
logoutMenu.classList.toggle('hidden');
});

const postButton = document.getElementById('post-button');
const tweetForm = document.getElementById('tweet-form');
const overlay = document.getElementById('overlay');
const skeletonLoader = document.getElementById('skeleton-loader');
const feed = document.getElementById('feed');

postButton.addEventListener('click', function () {
  tweetForm.classList.remove('hidden');
  overlay.classList.remove('hidden');
});

overlay.addEventListener('click', function () {
  tweetForm.classList.add('hidden');
  overlay.classList.add('hidden');
});