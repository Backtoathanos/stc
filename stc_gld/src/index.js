// index.js
import React from 'react';
import ReactDOM from 'react-dom';
import App from './App';

ReactDOM.render(
  <React.Fragment> {/* Remove StrictMode */}
    <App />
  </React.Fragment>,
  document.getElementById('root')
);