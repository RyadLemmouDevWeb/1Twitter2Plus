"use strict";

var postButton = document.getElementById('post-button');
var tweetForm = document.getElementById('tweet-form');
var overlay = document.getElementById('overlay');
var skeletonLoader = document.getElementById('skeleton-loader');
var feed = document.getElementById('feed');
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
setInterval(function () {
  showSkeleton();
  setTimeout(hideSkeleton, 3000);
}, 60000);
hideSkeleton();