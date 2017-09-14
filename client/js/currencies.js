export default function createCurrencies(currenciesEl, pubSub) {
  const currencyEls = [].slice.call(currenciesEl.querySelectorAll('.js-currency'));

  currencyEls.forEach(currencyEl => {
    const currency = currencyEl.getAttribute('data-currency');

    currencyEl
      .querySelector('.js-currency-delete')
      .addEventListener('click', () => pubSub.emit('currencies.delete', currency));
  });

  function showLoader() {
    currenciesEl.classList.add('currencies--is-loading');
    currenciesEl.innerHTML += `
      <div class="currencies__overlay">
        <div class="currencies__overlay-inner">
          <div class="currencies__loader--container">
              <p class="currencies__loader">Loading...</p>
          </div>
        </div>
      </div>
    `;
  }

  return { showLoader };
}
