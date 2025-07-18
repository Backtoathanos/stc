import { Link } from 'react-router-dom';

export default function Sidebar({ activeRoute }) {
    return (
        <div className="sidebar" data-color="purple" data-background-color="white" data-image={`${process.env.PUBLIC_URL}/assets/img/sidebar-1.jpg`}>
            <div className="logo">
                <Link to="javascript:void(0)" className="simple-text logo-normal"> STC GLD </Link>
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
                </ul>
            </div>
        </div>
    );
}
