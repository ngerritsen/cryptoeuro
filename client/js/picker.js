export default function createPicker(pubSub) {
  const pickerEl = document.querySelector('.js-picker');

  renderButton();
  
  function pickCurrency() {
    renderLoader();

    getCurrencies()
      .then(renderPicker)
      .then(listenForSubmits);
  }
  
  function listenForSubmits() {
    pickerEl.querySelector('.js-form').addEventListener('submit', submit);
  }
 
  function submit(event) {
    const { currency, amount } = getFormValues();

    event.preventDefault();

    if (currency) {
      addCurrency(currency, amount);
      renderButton();
    }
  }

  function addCurrency(currency, amount) {
    pubSub.emit('picker.picked', { currency, amount });
  }

  function getFormValues() {
    return {
      amount: Number(pickerEl.querySelector('.js-amount').value || 1),
      currency: pickerEl.querySelector('.js-select').value
    }
  }

  function getCurrencies() {
    return fetch('/api/currencies')
      .then((response) => {
        if (response.status < 200 || response.status >= 400) {
          throw new Error('Retrieving content failed.');
        }

        return response.json();
      });
  }

  function renderButton() {
    pickerEl.innerHTML = '<button class="picker__button js-picker-button">Add currency</button>';

    getPickerButton().addEventListener('click', pickCurrency);
  }

  function renderLoader() {
    const pickerButton = getPickerButton();

    pickerButton.textContent = 'Loading...';
    pickerButton.disabled = true;
  }

  function getPickerButton() {
    return pickerEl.querySelector('.js-picker-button');
  }

  function renderPicker(currencies) {
    pickerEl.innerHTML = `
      <form class="picker__form js-form">
          <fieldset class="picker__fieldset">
              <legend class="picker__legend">Add a currency</legend>
              <div class="picker__form-section">
                <label class="picker__label hidden" for="currencies">Currency</label>
                <span class="picker__select-container">
                  <select id="currencies" class="picker__select js-select">
                      ${currencies.map(({ currency, name }) => (
                        `<option value="${currency}">${currency} - ${name}</option>`  
                      )).join('\n')}
                  </select>
                </span>
              </div>
              <div class="picker__form-section">
                <label class="picker__label" for="amount">Amount</label>
                <input id="amount" placeholder="1" class="picker__input js-amount" step="any" min="0" type="number">
              </div>
              <input class="picker__button" type="submit" value="Add">
          </fieldset>
      <form>
    `;
  }
}
