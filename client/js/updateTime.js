export default function updateTime() {
  const timeString = getTimestring();

  document.querySelector('.js-time').textContent = 'Last updated: ' + timeString;
}

function getTimestring() {
  const currentTime = new Date();
  const hours = addZero(currentTime.getHours());
  const minutes = addZero(currentTime.getMinutes());
  const seconds = addZero(currentTime.getSeconds());

  return hours + ':' + minutes + ':' + seconds;
}

function addZero(timePart) {
  return timePart < 10 ? '0' + timePart : timePart;
}
