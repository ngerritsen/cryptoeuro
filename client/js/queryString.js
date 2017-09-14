export function updateQueryString(queryString, key, value) {
  return encodeQuery({
    ...parseQueryString(queryString),
    [key]: value
  });
}

export function removeFromQueryString(queryString, key) {
  const currentQuery = parseQueryString(queryString);

  const newQuery = Object.keys(currentQuery)
    .filter(k => k.toLowerCase() !== key.toLowerCase())
    .reduce((newQueryObj, key) => ({
      ...newQueryObj,
      [key]: currentQuery[key]
    }), {});

  return encodeQuery(newQuery);
}

function parseQueryString(queryString) {
  return queryString.slice(1, queryString.length)
    .split('&')
    .filter(value => value.length > 0)
    .reduce((queryObj, part) => {
      const [param, value] = part.split('=');
      return {
        ...queryObj,
        [param]: value
      };
    }, {})
}

function encodeQuery(query) {
  const queryString = Object.keys(query)
    .map(key => key + '=' + query[key])
    .join('&');

  return queryString ? '?' + queryString : queryString;
}
