import updateTime from './updateTime';

export default function refresh() {
  return retrieveNewContent()
    .then(replaceHtml)
    .then(updateTime);
}

function replaceHtml(html) {
  document.querySelector('.js-container').innerHTML = html;
}

function retrieveNewContent() {
  const url = window.location.pathname + window.location.search;
  const headers = new Headers({ 'X-CurrenciesOnly': true });

  return fetch(url, { headers })
    .then(response => {
      if (response.status < 200 || response.status >= 400) {
        throw new Error('Retrieving content failed.');
      }

      return response.text();
    });
}
