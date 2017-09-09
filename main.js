(function () {
  var REFRESH_INTERVAL = 60000;
  var CONTAINER_SELECTOR = '.js-container';
  
  updateTime();

  ready(function () {
    setInterval(refresh, REFRESH_INTERVAL);
  });
  
  function refresh() {
    var request = new XMLHttpRequest();
    request.open('GET', window.location.pathname + window.location.search, true);
  
    request.onload = function() {
      if (request.status >= 200 && request.status < 400) {
        var html = request.responseText;
  
        replaceContent(html);
        updateTime();
      }
    };
  
    request.send();
  }
  
  function replaceContent(html) {
    var doc = document.createElement('div');
  
    doc.innerHTML = html;
  
    var newContent = doc.querySelector(CONTAINER_SELECTOR).innerHTML;
    var containerEl = document.querySelector(CONTAINER_SELECTOR);

    containerEl.innerHTML = newContent;
  }
  
  function ready(fn) {
    if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading"){
      fn();
    } else {
      document.addEventListener('DOMContentLoaded', fn);
    }
  }

  function updateTime() {
    var currentTime = new Date();
    var hours = addZero(currentTime.getHours());
    var minutes = addZero(currentTime.getMinutes());
    var seconds = addZero(currentTime.getSeconds());

    const timeString = hours + ':' + minutes + ':' + seconds;

    document.querySelector('.js-time').textContent = 'Last updated: ' + timeString;
  }

  function addZero(timePart) {
    return timePart = timePart < 10 ? '0' + timePart : timePart;
  }
})();
