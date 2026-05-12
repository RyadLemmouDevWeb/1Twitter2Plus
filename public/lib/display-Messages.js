"use strict";

window.onload = function() {
    const container = document.getElementById("container-messages");
    if (container) {
        container.scrollTop = container.scrollHeight;
    }

    const messageForm = document.getElementById("send-message");
    if (messageForm) {
        messageForm.addEventListener("submit", function() {
            // Optional: Show a loading state or something
            const submitBtn = messageForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.opacity = 0.5;
            }
        });
    }
};
