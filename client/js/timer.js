export default function createTimer(pubSub, cycleMs, tickMs) {
  let elapsedMs = 0;
  let started = false;
  let intervalToken = null;

  function start() {
    if (started) {
      throw new Error(`Cannot start an already started timer.`);
    }

    started = true;

    intervalToken = setInterval(tick, tickMs);
  }

  function restart() {
    stop();
    start();
  }

  function stop() {
    started = false;
    elapsedMs = 0;

    clearInterval(intervalToken);
  }

  function tick() {
    elapsedMs += tickMs;

    if (elapsedMs >= cycleMs) {
      pubSub.emit('timer.done');
      stop();

      return;
    }

    pubSub.emit('timer.update', getRemainingPercentage());
  }

  function getRemainingPercentage() {
    return 100 - (elapsedMs / cycleMs * 100);
  }

  return { start, restart };
}
