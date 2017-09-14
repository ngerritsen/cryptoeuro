import refresh from './refresh';
import ready from './ready';
import updateTime from './updateTime';
import createTimer from './timer';
import updateCountdown from './updateCountdown';
import createPicker from './picker';

const REFRESH_INTERVAL = 60000;
const TIMER_TICK_RATE = 100;

const timer = createTimer(REFRESH_INTERVAL, TIMER_TICK_RATE);

ready()
  .then(start);

function start() {
  createPicker();

  timer.on('done', handleTimerDone);
  timer.on('update', updateCountdown);

  updateTime();
  timer.start();
}

function handleTimerDone() {
  refresh()
    .then(timer.start)
}
