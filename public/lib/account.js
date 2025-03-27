"use strict";

function togglePopup() {
  var popup = document.getElementById('editProfilePopup');
  var overlay = document.getElementById('popupOverlay');
  popup.classList.toggle('hidden');
  overlay.classList.toggle('hidden');
}
var profileMenu = document.getElementById('profileMenu');
var logoutMenu = document.getElementById('logoutMenu');
profileMenu.addEventListener('click', function () {
  logoutMenu.classList.toggle('hidden');
});
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