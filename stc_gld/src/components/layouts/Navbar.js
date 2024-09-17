import {Link, useNavigate} from 'react-router-dom';
export default function Navbar(){
    const navigate = useNavigate();
    function logoutSubmit(e) {
        e.preventDefault();
        localStorage.removeItem("login"); // Clears the login status from localStorage
        localStorage.setItem("loginStatus", "Logged out successfully."); // Optional message
        navigate("/"); // Redirects to the login page
    }
    
    return (
        <nav className="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
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
                    <input type="text" value="" className="form-control stc-elec-pd-search-val" placeholder="Search..."/>
                    <button type="submit" href="" className="btn btn-white btn-round btn-just-icon stc-elec-pd-search-hit">
                    <i className="material-icons">search</i>
                    <div className="ripple-container"></div>
                    </button>
                </div>
                </form>
                <ul className="navbar-nav">
                <li className="nav-item dropdown active">
                    <a className="nav-link" href="request-action.php" id="navbarDropdownMenuLink">
                    <i className="material-icons">notifications</i>
                    <span className="notification">
                    </span>
                    <p className="d-lg-none d-md-block">
                        Some Actions
                    </p>
                    </a>
                </li>
                <li className="nav-item dropdown">
                    <a className="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i className="material-icons">person</i>
                    <p className="d-lg-none d-md-block">
                        Account
                    </p>
                    </a>
                    <div className="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                    <a className="dropdown-item" href="#">Profile</a>
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