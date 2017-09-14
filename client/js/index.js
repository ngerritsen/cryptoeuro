import createCurrencies from './currencies';
import createCurrencyService from './currencyService';
import createPicker from './picker';
import createPubSub from './pubSub';
import ready from './ready';
import createTimer from './timer';
import updateTime from './updateTime';
import updateCountdown from './updateCountdown';

import '../css/main.css';

const REFRESH_INTERVAL = 60000;
const TIMER_TICK_RATE = 100;

const pubSub = createPubSub(pubSub);
const timer = createTimer(pubSub, REFRESH_INTERVAL, TIMER_TICK_RATE);
const currencyService = createCurrencyService(pubSub);
let currencies = null;

ready()
  .then(() => {
    const pickerEl = document.querySelector('.js-picker');

    createPicker(pickerEl, pubSub);
    currencies = createCurrencies(document.querySelector('.js-currencies'), pubSub);

    updateTime();
    timer.start();

    pubSub.on('picker.picked', ({ currency, amount }) => {
      currencyService.addCurrency(currency, amount)
    });

    pubSub.on('timer.done', currencyService.refresh);
    pubSub.on('timer.update', updateCountdown);

    pubSub.on('currencyService.retrievingCurrencies', () => currencies.showLoader());
    pubSub.on('currencyService.currenciesRetrieved', (html) => {
      document.querySelector('.js-container').innerHTML = html;
      updateTime();
      timer.restart();
      currencies = createCurrencies(document.querySelector('.js-currencies'), pubSub);
    });

    pubSub.on('currencies.delete', currencyService.removeCurrency);
  })
  .catch(error => {
    console.error(error.stack);
  });
