import {useNavigate} from 'react-router-dom';
import { clearAllAuthCookies } from '../cookieUtils.js';
export default function Navbar(){
    const navigate = useNavigate();
    function logoutSubmit(e) {
        e.preventDefault();
        
        // Clear localStorage
        localStorage.removeItem("login");
        localStorage.setItem("loginStatus", "Logged out successfully.");
        
        // Clear cookies using utility function
        clearAllAuthCookies();
        
        console.log("Logged out - cleared all cookies and localStorage");
        
        navigate("/"); // Redirects to the login page
    }
    
    return (
        <nav className="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
            <div className="container-fluid">
            <div className="navbar-wrapper">
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
                </div>
                </form>
                <ul className="navbar-nav">
                <li className="nav-item dropdown">
                    <a className="nav-link" href="/stc_gld/order" id="navbarDropdownMenuLink">
                    <i className="material-icons">notifications</i>
                    <span className="notification">
                    </span>
                    <p className="d-lg-none d-md-block">
                        Some Actions
                    </p>
                    </a>
                </li>
                <li className="nav-item dropdown">
                    <a className="nav-link" href="javascript:void(0)" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i className="material-icons">person</i>
                    <p className="d-lg-none d-md-block">
                        Account
                    </p>
                    </a>
                    <div className="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                    {/* <a className="dropdown-item" href="#">Profile</a> */}
                    <div className="dropdown-divider"></div>
                    <a className="dropdown-item" onClick={logoutSubmit}>Log out</a>
                    </div>
                </li>
                </ul>
            </div>
            </div>
        </nav>
    );
}