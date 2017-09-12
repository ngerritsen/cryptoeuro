export default function updateCountdown(percentage) {
  const countdownEl = document.querySelector('.js-countdown');

  if (countdownEl.style.display !== 'block') {
    document.querySelector('.js-countdown').style.display = 'block';

    countdownEl.innerHTML = `<div
        class="js-countdown-bar countdown__bar"
        style="width: ${percentage}%"
    ></div>`;

    return;
  }

  countdownEl.querySelector('.js-countdown-bar').style.width = percentage + '%';
}
