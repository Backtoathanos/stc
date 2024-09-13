import React from 'react';
import { Link } from 'react-router-dom';
import { Helmet } from 'react-helmet';
import { useAuth } from './AuthContext';
// import './Dashboard.css';

const Dashboard = () => {
  const { logout } = useAuth();

  const handleLogout = () => {
    logout();
    window.location.href = '/';
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
      <div className="wrapper">
        <div className="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
          <div className="logo"><a href="#" className="simple-text logo-normal">
            STC GLD
          </a></div>
          <div className="sidebar-wrapper">
            <ul className="nav">
              <li className="nav-item active">
                <a className="nav-link" href="./dashboard">
                  <i className="material-icons">dashboard</i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li className="nav-item">
                <a className="nav-link" href="./product">
                  <i className="material-icons">shop</i>
                  <p>Product</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div className="main-panel">
          <nav className="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top">
            <div className="container-fluid">
              <div className="navbar-wrapper">
                <a className="navbar-brand" href="javascript:;">Dashboard</a>
              </div>
              <button className="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                <span className="sr-only">Toggle navigation</span>
                <span className="navbar-toggler-icon icon-bar"></span>
                <span className="navbar-toggler-icon icon-bar"></span>
                <span className="navbar-toggler-icon icon-bar"></span>
              </button>
              <div className="collapse navbar-collapse justify-content-end">
                <form className="navbar-form">
                  <div className="input-group no-border">
                    <input type="text" value="" className="form-control stc-elec-pd-search-val" placeholder="Search..." />
                    <button type="submit" className="btn btn-white btn-round btn-just-icon stc-elec-pd-search-hit">
                      <i className="material-icons">search</i>
                      <div className="ripple-container"></div>
                    </button>
                  </div>
                </form>
                <ul className="navbar-nav">
                  <li className="nav-item dropdown active">
                    <a className="nav-link" href="request-action.php" id="navbarDropdownMenuLink">
                      <i className="material-icons">notifications</i>
                      <span className="notification">2</span>
                      <p className="d-lg-none d-md-block">Some Actions</p>
                    </a>
                  </li>
                  <li className="nav-item dropdown">
                    <a className="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i className="material-icons">person</i>
                      <p className="d-lg-none d-md-block">Account</p>
                    </a>
                    <div className="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                      <a className="dropdown-item" href="#">Profile</a>
                      <div className="dropdown-divider"></div>
                      <a className="dropdown-item" href="./logout">Log out</a>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </nav>
          <div className="content">
            <div className="container-fluid">
              <div className="row">
                <div className="col-lg-3 col-md-6 col-sm-6">
                  <div className="card card-stats">
                    <div className="card-header card-header-warning card-header-icon">
                      <div className="card-icon">
                        <i className="material-icons">content_copy</i>
                      </div>
                      <p className="card-category">Total Customer</p>
                      <h3 className="card-title"><small></small></h3>
                    </div>
                    <div className="card-footer">
                      <div className="stats">
                        <i className="material-icons">local_offer</i> Carry On 
                      </div>
                    </div>
                  </div>
                </div>
                <div className="col-lg-3 col-md-6 col-sm-6">
                  <div className="card card-stats">
                    <div className="card-header card-header-success card-header-icon">
                      <div className="card-icon">
                        <i className="material-icons">store</i>
                      </div>
                      <p className="card-category">Revenue</p>
                      <p className="card-title">₹ </p>
                    </div>
                    <div className="card-footer">
                      <div className="stats">
                        <i className="material-icons">date_range</i> Last 1 Month
                      </div>
                    </div>
                  </div>
                </div>
                <div className="col-lg-3 col-md-6 col-sm-6">
                  <div className="card card-stats">
                    <div className="card-header card-header-danger card-header-icon">
                      <div className="card-icon">
                        <i className="fa fa-user-secret" aria-hidden="true"></i>
                      </div>
                      <p className="card-category">Total Agent</p>
                      <h3 className="card-title"></h3>
                    </div>
                    <div className="card-footer">
                      <div className="stats">
                        <i className="material-icons">local_offer</i> Tracked from STC Electronics
                      </div>
                    </div>
                  </div>
                </div>
                <div className="col-lg-3 col-md-6 col-sm-6">
                  <div className="card card-stats">
                    <div className="card-header card-header-info card-header-icon">
                      <div className="card-icon">
                        <i className="fa fa-instagram"></i>
                      </div>
                      <p className="card-category">Followers</p>
                      <h3 className="card-title"></h3>
                    </div>
                    <div className="card-footer">
                      <div className="stats">
                        <i className="material-icons">update</i> Just Updated
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default Dashboard;
