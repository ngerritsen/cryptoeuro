export default function createCurrencies(pubSub) {
  const currencyEls = [].slice.call(document.querySelectorAll('.js-currency'));

  currencyEls.forEach(currencyEl => {
    const currency = currencyEl.getAttribute('data-currency');

    currencyEl
      .querySelector('.js-currency-delete')
      .addEventListener('click', () => pubSub.emit('currencies.delete', currency));
  });
}
