
import {Link, useNavigate} from 'react-router-dom';
import './Login.css';
export default function Dashboard(){
    const navigate = useNavigate();
    function logoutSubmit() {
        localStorage.removeItem("login"); // Clears the login status from localStorage
        localStorage.setItem("loginStatus", "Logged out successfully."); // Optional message
        navigate("/"); // Redirects to the login page
    }
    
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
                        <li className="nav-item active">
                            <Link to="/dashboard" className="nav-link">
                            <i className="material-icons">dashboard</i>
                            <p>Dashboard</p>
                            </Link>
                        </li>
                        <li className="nav-item">
                            <button className="nav-link" style={{ border: 'none', background: 'none' }} onClick={logoutSubmit}>
                            <i className="material-icons">logout</i>
                            <p>Logout</p>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div className="main-panel">
                <nav className="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
                    <div className="container-fluid">
                    <div className="navbar-wrapper">
                        <Link to="/" className="navbar-brand">Dashboard</Link>
                    </div>
                    <button className="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span className="sr-only">Toggle navigation</span>
                        <span className="navbar-toggler-icon icon-bar"></span>
                        <span className="navbar-toggler-icon icon-bar"></span>
                        <span className="navbar-toggler-icon icon-bar"></span>
                    </button>
                    </div>
                </nav>
                <div className="content">
                    <div className="container-fluid">
                    <div className="row form-group">
                        <div className="col-sm-6 col-md-6">
                            <h1>Hi i am dashboard</h1>
                        </div>
                    </div>
                    </div>
                </div>
                <footer className="footer">
                    <div className="container-fluid">
                    <div className="copyright float-right">
                        &copy;
                        <script>
                        document.write(new Date().getFullYear())
                        </script>, made with <i className="material-icons">favorite</i> by
                        GIS
                    </div>
                    </div>
                </footer>
            </div>
        </div>
    )
}