import { useLayoutEffect, useRef } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { clearAllAuthCookies } from '../cookieUtils.js';

const MAIN_NAV_COLLAPSE_ID = 'stc-gld-main-navbar-collapse';

export default function Navbar() {
    const navigate = useNavigate();
    const collapseRef = useRef(null);

    /*
     * Material Dashboard uses Bootstrap 4 CSS, but public/index.html loads Bootstrap 3.4.1 JS last.
     * BS3's collapse plugin attaches to .navbar-collapse and can leave it zero-height after a full
     * page reload, hiding the notification / account controls until client-side navigation remounts.
     */
    useLayoutEffect(() => {
        const el = collapseRef.current;
        if (!el || typeof window === 'undefined' || !window.$) return;
        const $el = window.$(el);
        try {
            if (typeof $el.collapse === 'function' && $el.data('bs.collapse')) {
                $el.collapse('destroy');
            }
        } catch {
            /* ignore */
        }
        el.classList.add('show');
        el.classList.add('in');
        el.style.removeProperty('height');
        el.style.removeProperty('padding-top');
        el.style.removeProperty('overflow');
    }, []);

    function logoutSubmit(e) {
        e.preventDefault();

        // Clear localStorage
        localStorage.removeItem("login");
        localStorage.setItem("loginStatus", "Logged out successfully.");

        // Clear cookies using utility function
        clearAllAuthCookies();

        navigate('/');
    }

    return (
        <nav className="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
            <div className="container-fluid">
                <div className="navbar-wrapper">
                </div>
                <button
                    className="navbar-toggler"
                    type="button"
                    data-toggle="collapse"
                    data-target={`#${MAIN_NAV_COLLAPSE_ID}`}
                    aria-controls={MAIN_NAV_COLLAPSE_ID}
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span className="sr-only">Toggle navigation</span>
                    <span className="navbar-toggler-icon icon-bar"></span>
                    <span className="navbar-toggler-icon icon-bar"></span>
                    <span className="navbar-toggler-icon icon-bar"></span>
                </button>
                <div
                    ref={collapseRef}
                    id={MAIN_NAV_COLLAPSE_ID}
                    className="collapse navbar-collapse justify-content-end"
                >
                    <form className="navbar-form">
                        <div className="input-group no-border">
                        </div>
                    </form>
                    <ul className="navbar-nav">
                        <li className="nav-item dropdown">
                            <Link className="nav-link" to="/order" id="navbarDropdownMenuLink">
                                <i className="material-icons">notifications</i>
                                <span className="notification">
                                </span>
                                <p className="d-lg-none d-md-block">
                                    Requisitions
                                </p>
                            </Link>
                        </li>
                        <li className="nav-item dropdown">
                            <button type="button" className="nav-link btn btn-link border-0 bg-transparent text-body" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i className="material-icons">person</i>
                                <p className="d-lg-none d-md-block">
                                    Account
                                </p>
                            </button>
                            <div className="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                                {/* <a className="dropdown-item" href="#">Profile</a> */}
                                <div className="dropdown-divider"></div>
                                <button type="button" className="dropdown-item" onClick={logoutSubmit}>Log out</button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    );
}