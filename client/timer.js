export default function createTimer(cycleMs, tickMs) {
  let elapsedMs = 0;
  let started = false;
  let doneCallbacks = [];
  let updateCallbacks = [];
  let intervalToken = null;

  function on(event, callback) {
    switch (event) {
      case 'update':
        updateCallbacks = [...updateCallbacks, callback];
        break;
      case 'done':
        doneCallbacks = [...doneCallbacks, callback];
        break;
      default:
        throw new Error(`Cannot register unknown event ${event}.`);
    }
  }

  function start() {
    if (started) {
      throw new Error(`Cannot start an already started timer.`);
    }

    started = true;

    intervalToken = setInterval(tick, tickMs);
  }

  function stop() {
    started = false;
    elapsedMs = 0;

    clearInterval(intervalToken);
  }

  function tick() {
    elapsedMs += tickMs;

    if (elapsedMs >= cycleMs) {
      callAll(doneCallbacks);
      stop();

      return;
    }

    callAll(updateCallbacks, getRemainingPercentage());
  }

  function getRemainingPercentage() {
    return 100 - (elapsedMs / cycleMs * 100);
  }

  function callAll(callbacks, ...args) {
    callbacks.forEach(callback => callback(...args));
  }

  return {
    start,
    stop,
    on
  };
}
