import { useLayoutEffect, useCallback } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { clearAllAuthCookies } from '../cookieUtils.js';

export default function Sidebar({ activeRoute }) {
    const navigate = useNavigate();
    const sidebarImageUrl = `${process.env.PUBLIC_URL}/assets/img/sidebar-1.jpg`;

    /*
     * Material Dashboard runs $(document).ready before React mounts, so $sidebar was empty and
     * md.checkSidebarImage / md.initSidebarsCheck never wired the photo or mobile nav (account).
     * Sync the global $sidebar and re-run mobile sidebar setup after this component exists.
     */
    useLayoutEffect(() => {
        if (typeof window === 'undefined' || !window.$ || !window.md) return;

        window.$sidebar = window.$('.sidebar');
        window.md.initSidebarsCheck();

        const onResize = () => {
            window.$sidebar = window.$('.sidebar');
            window.md.initSidebarsCheck();
        };
        window.addEventListener('resize', onResize);
        return () => window.removeEventListener('resize', onResize);
    }, []);

    const logoutSubmit = useCallback(
        (e) => {
            e.preventDefault();
            localStorage.removeItem('login');
            localStorage.setItem('loginStatus', 'Logged out successfully.');
            clearAllAuthCookies();
            navigate('/');
        },
        [navigate]
    );

    return (
        <div
            className="sidebar"
            data-color="purple"
            data-background-color="white"
            data-image={sidebarImageUrl}
        >
            <div
                className="sidebar-background"
                style={{ backgroundImage: `url(${sidebarImageUrl})` }}
                aria-hidden
            />
            <div className="logo">
                <span className="simple-text logo-normal"> STC GLD </span>
            </div>
            <div className="sidebar-wrapper">
                <ul className="nav">
                    <li className={`nav-item ${activeRoute === 'dashboard' ? 'active' : ''}`}>
                        <Link to="/dashboard" className="nav-link">
                            <i className="material-icons">dashboard</i>
                            <p>Dashboard</p>
                        </Link>
                    </li>
                    <li className={`nav-item ${activeRoute === 'requisitions' ? 'active' : ''}`}>
                        <Link to="/requisitions" className="nav-link">
                            <i className="material-icons">assignment</i>
                            <p>Requisitions</p>
                        </Link>
                    </li>
                    <li className={`nav-item ${activeRoute === 'inventory' ? 'active' : ''}`}>
                        <Link to="/inventory" className="nav-link">
                            <i className="material-icons">content_paste</i>
                            <p>Inventory</p>
                        </Link>
                    </li>
                    <li className={`nav-item ${activeRoute === 'challan' ? 'active' : ''}`}>
                        <Link to="/challan" className="nav-link">
                            <i className="material-icons">forum</i>
                            <p>Challan</p>
                        </Link>
                    </li>
                    <li className={`nav-item ${activeRoute === 'invoice' ? 'active' : ''}`}>
                        <Link to="/invoice" className="nav-link">
                            <i className="material-icons">forum</i>
                            <p>Invoice (RCM)</p>
                        </Link>
                    </li>
                    <li className="nav-item d-lg-none">
                        <button
                            type="button"
                            className="nav-link w-100 text-left border-0 bg-transparent"
                            onClick={logoutSubmit}
                        >
                            <i className="material-icons">exit_to_app</i>
                            <p>Log out</p>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    );
}
