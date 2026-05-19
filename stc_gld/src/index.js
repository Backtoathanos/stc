// index.js
import React from 'react';
import ReactDOM from 'react-dom';
import App from './App';

if (process.env.NODE_ENV === 'production') {
    const noop = () => {};
    /* eslint-disable no-console */
    console.log = noop;
    console.debug = noop;
    console.info = noop;
    /* eslint-enable no-console */
}

ReactDOM.render(
  <React.Fragment> {/* Remove StrictMode */}
    <App />
  </React.Fragment>,
  document.getElementById('root')
);