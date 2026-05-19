import { Link } from 'react-router-dom';
import Footer from './layouts/Footer';

/**
 * Shared chrome for login / forgot-password / reset-password (navbar shape for material-dashboard.js).
 */
export default function AuthLayout({ navbarTitle, activeNav = 'login', children }) {
    return (
        <div className="wrapper ">
            <div className="sidebar" data-color="purple" data-background-color="white">
                <div className="logo">
                    <Link to="/" className="simple-text logo-normal">
                        STC GLD
                    </Link>
                </div>
                <div className="sidebar-wrapper">
                    <ul className="nav">
                        <li className={`nav-item ${activeNav === 'login' ? 'active' : ''}`}>
                            <Link to="/" className="nav-link">
                                <i className="material-icons">login</i>
                                <p>Login</p>
                            </Link>
                        </li>
                        <li className={`nav-item ${activeNav === 'forgot' ? 'active' : ''}`}>
                            <Link to="/forgot-password" className="nav-link">
                                <i className="material-icons">lock_open</i>
                                <p>Forgot password</p>
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
            <div className="main-panel">
                <nav className="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
                    <div className="container-fluid">
                        <div className="navbar-wrapper">
                            <Link to="/" className="navbar-brand">
                                {navbarTitle}
                            </Link>
                        </div>
                        <button
                            className="navbar-toggler"
                            type="button"
                            data-toggle="collapse"
                            aria-controls="navigation-index"
                            aria-expanded="false"
                            aria-label="Toggle navigation"
                        >
                            <span className="sr-only">Toggle navigation</span>
                            <span className="navbar-toggler-icon icon-bar" />
                            <span className="navbar-toggler-icon icon-bar" />
                            <span className="navbar-toggler-icon icon-bar" />
                        </button>
                        <div className="collapse navbar-collapse justify-content-end">
                            <form className="navbar-form" aria-hidden="true">
                                <div className="input-group no-border" />
                            </form>
                            <ul className="navbar-nav">
                                <li className="nav-item">
                                    <span className="nav-link" style={{ cursor: 'default' }}>
                                        <p className="d-lg-none d-md-block mb-0">{navbarTitle}</p>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <div className="content">
                    <div className="container-fluid">
                        <div className="row form-group">{children}</div>
                    </div>
                </div>
                <Footer />
            </div>
        </div>
    );
}
