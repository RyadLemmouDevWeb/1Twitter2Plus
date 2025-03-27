document.addEventListener('DOMContentLoaded', () => {
    const interBubble = document.querySelector('.interactive');
    let curX = 0;
    let curY = 0;
    let tgX = 0;
    let tgY = 0;

    function move() {
      curX += (tgX - curX) / 20;
      curY += (tgY - curY) / 20;
      interBubble.style.transform = `translate(${Math.round(curX)}px, ${Math.round(curY)}px)`;
      requestAnimationFrame(() => {
        move();
      });
    }

    window.addEventListener('mousemove', (event) => {
      tgX = event.clientX;
      tgY = event.clientY;
    });

    move();

    setTimeout(() => {
      document.querySelectorAll('.skeleton').forEach(skeleton => {
        skeleton.classList.add('hidden');
      });
      document.querySelectorAll('.form-content').forEach(form => {
        form.classList.remove('hidden');
      });
    }, 1000);
  });

  function showSignup() {
    document.querySelector(".login-form").classList.add("hidden");
    document.querySelector(".signup-form").classList.remove("hidden");
  }

  function showLogin() {
    document.querySelector(".signup-form").classList.add("hidden");
    document.querySelector(".login-form").classList.remove("hidden");
  }