import React, { useState, useEffect } from 'react';
import { Helmet } from 'react-helmet';

import './Login.css'; 
import './components/assets/css/material-dashboard.css?v=2.1.2'; 

function Login() {
  useEffect(() => {
    document.title = "STC GLD || Login";
    
    // Find the favicon element
    const favicon = document.getElementById("favicon");

    // Check if favicon element exists before setting the href
    if (favicon) {
      favicon.href = `${process.env.PUBLIC_URL}/stc_logo_title.png`;
    }
  }, []);

  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [message, setMessage] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData();
    formData.append('stc_electro_login_uname', username);
    formData.append('stc_electro_login_upassword', password);
    formData.append('agent_signin', true);

    try {
      const response = await fetch('/vanaheim/useroath.php', {
        method: 'POST',
        body: formData,
      });
      const result = await response.text();
      if (result.trim() === 'success') {
        window.location.href = 'stc_mazeRunner/dashboard.php';
      } else {
        alert('Please check username or password!');
      }
    } catch (error) {
      console.error('Error during login:', error);
    }
  };

  return (
    <>
      {/* Adding external styles using Helmet */}
      <Helmet>
        <link
          rel="stylesheet"
          type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons"
        />
        <link
          rel="stylesheet"
          type="text/css"
          href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css"
        />
      </Helmet>
      <div class="wrapper ">
        <div class="sidebar" data-color="purple" data-background-color="white">
          <div class="logo">
            <a href="#" class="simple-text logo-normal">
              STC Trading
            </a>
          </div>
          <div class="sidebar-wrapper">
            <ul class="nav">
              <li class="nav-item active  ">
                <a class="nav-link" href="#0">
                  <i class="material-icons">login</i>
                  <p>Login</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="main-panel">
          <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
            <div class="container-fluid">
              <div class="navbar-wrapper">
                <a class="navbar-brand" href="javascript:;">Login</a>
              </div>
              <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="navbar-toggler-icon icon-bar"></span>
                <span class="navbar-toggler-icon icon-bar"></span>
                <span class="navbar-toggler-icon icon-bar"></span>
              </button>
            </div>
          </nav>
          <div class="content">
            <div class="container-fluid">
              <div class="row form-group">
                <div class="col-sm-6 col-md-6">
                <form className="stc-electronics-login-form" onSubmit={handleSubmit}>
                <div className="imgcontainer">
                  <h1>
                    <i className="fa fa-key"></i>
                  </h1>
                </div>

                <div className="container">
                  <label htmlFor="uname"><b>Full Name/ Email or Contact</b></label>
                  <input
                    type="text"
                    placeholder="Enter Full Name/ Email or Contact"
                    name="stc_electro_login_uname"
                    required
                    value={username}
                    onChange={(e) => setUsername(e.target.value)}
                  />

                  <label htmlFor="psw"><b>Password</b></label>
                  <input
                    type="password"
                    placeholder="Enter Password"
                    name="stc_electro_login_upassword"
                    required
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                  />

                  <button type="submit" className="stc-electro-login-button">Login</button>
                </div>
              </form>
                </div>
              </div>
            </div>
          </div>
          <footer class="footer">
            <div class="container-fluid">
              <div class="copyright float-right">
                &copy;
                <script>
                  document.write(new Date().getFullYear())
                </script>, made with <i class="material-icons">favorite</i> by
                GIS
              </div>
            </div>
          </footer>
        </div>
      </div>
    </>
  );
}

export default Login;
