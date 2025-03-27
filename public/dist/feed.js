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

function showSkeleton() {
  skeletonLoader.style.display = 'flex';
  feed.style.display = 'none';
}

function hideSkeleton() {
  skeletonLoader.style.display = 'none';
  feed.style.display = 'flex';
}

setInterval(() => {
  showSkeleton();
  setTimeout(hideSkeleton, 3000);
}, 60000);

hideSkeleton();