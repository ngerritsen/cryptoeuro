import { updateQueryString, removeFromQueryString } from './queryString';

export default function createCurrencyService(pubSub) {
  function addCurrency(currency, amount) {
    const query = updateQueryString(window.location.search, currency, amount);

    refresh(query);
  }

  function removeCurrency(currency) {
    const query = removeFromQueryString(window.location.search, currency);
    refresh(query);
  }

  function refresh(query = window.location.search) {
    updateUrl(query);

    return retrieveNewContent(query)
      .then(html => pubSub.emit('currencyService.currenciesRetrieved', html))
  }

  function updateUrl(query) {
    history.pushState({}, '', query || '/');
  }

  function retrieveNewContent(query) {
    const headers = new Headers({ 'X-CurrenciesOnly': true });

    return fetch('/' + query, { headers })
      .then(response => {
        if (response.status < 200 || response.status >= 400) {
          throw new Error('Retrieving content failed.');
        }

        return response.text();
      });
  }

  return {
    refresh,
    removeCurrency,
    addCurrency
  };
}
