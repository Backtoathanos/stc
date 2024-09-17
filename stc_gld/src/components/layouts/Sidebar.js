import { Link } from 'react-router-dom';

export default function Sidebar({ activeRoute }) {
    return (
        <div className="sidebar" data-color="purple" data-background-color="white" data-image="%PUBLIC_URL%/assets/img/sidebar-1.jpg">
            <div className="logo">
                <Link to="http://www.creative-tim.com" className="simple-text logo-normal"> STC GLD </Link>
            </div>
            <div className="sidebar-wrapper">
                <ul className="nav">
                    <li className={`nav-item ${activeRoute === 'dashboard' ? 'active' : ''}`}>
                        <Link to="/dashboard" className="nav-link">
                            <i className="material-icons">dashboard</i>
                            <p>Dashboard</p>
                        </Link>
                    </li>
                    <li className={`nav-item ${activeRoute === 'inventory' ? 'active' : ''}`}>
                        <Link to="/inventory" className="nav-link">
                            <i className="material-icons">content_paste</i>
                            <p>Inventory</p>
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    );
}
