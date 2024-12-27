
import {Link, useNavigate} from 'react-router-dom';
import './Login.css';
import { useEffect, useState } from 'react';
import Footer from './layouts/Footer';
export default function Login(){
    const navigate=useNavigate();
    const [user, setUser]=useState("");
    const [password, setPassword]=useState("");
    const [error, setError]=useState("");
    const [msg, setMsg]=useState("");
    const API_BASE_URL = process.env.REACT_APP_API_BASE_URL;

    useEffect(() => {
        let login=localStorage.getItem("login");
        if(login){
            navigate('/dashboard');
        }
        let loginStatus=localStorage.getItem("loginStatus");
        if(login){
            setError(loginStatus);
            setTimeout(function(){
                localStorage.clear();
                window.location.reload();
            }, 3000);
        }
        setTimeout(function(){
            setMsg("");
        }, 5000)
    });

    const handleInputChange = (e, type) => {
        switch(type){
            case "user":
                setError("");
                setUser(e.target.value);
                if(e.target.value===""){
                    setError("Please enter email or phone.")
                }
                break;
            case "password":
                setError("");
                setPassword(e.target.value);
                if(e.target.value===""){
                    setError("Please enter password.")
                }
                break;
            default:
        }
    }
    const setCookie = (name, value, days) => {
        const expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/; Secure; SameSite=Strict`;
    };
    
    const deleteCookie = (name) => {
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    };
        
    const handleSubmit = (e) => {
        e.preventDefault(); // Prevent page refresh
    
        if (user !== "" && password !== "") {
            var url = API_BASE_URL+"/useroath.php";
            var headers = {
                "Accept": "application/json",
                "Content-Type": "application/json"
            };
            var Data = {
                user: user,
                password: password,
                check_login: 1
            };
            fetch(url, {
                method: "POST",
                headers: headers,
                body: JSON.stringify(Data)
            }).then((response) => response.json())
            .then((response) => {
                if (response[0].result === "success") {
                    const userId = response[0].user_id; // Get the user ID from response
                    setMsg("Successfully logged in!!! Redirecting.");
                    setCookie("user_id", userId, 7); // Set cookie with correct name
                    setTimeout(function () {
                        localStorage.setItem("login", true);
                        navigate("/dashboard");
                    }, 2000);
                } else {
                    setError(response[0].result);
                }
            }).catch((err) => {
                setError(err);
            });
        } else {
            setError("All fields are required.");
        }
    };
    

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
                        <li className="nav-item active  ">
                            <Link to="/" className="nav-link">
                            <i className="material-icons">login</i>
                            <p>Login</p>
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
            <div className="main-panel">
                <nav className="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
                    <div className="container-fluid">
                    <div className="navbar-wrapper">
                        <Link to="/" className="navbar-brand">Login</Link>
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
                        <form className="stc-electronics-login-form" onSubmit={handleSubmit}>
                            <div className="imgcontainer">
                                <h1>
                                    <i className="fa fa-key"></i>
                                </h1>
                            </div>

                            <div className="container">
                                <label for="uname"><b>Full Name/ Email or Contact</b></label>
                                <input type="text" placeholder="Enter Email or Contact" value={user} onChange={(e) => handleInputChange(e, "user")} required/>

                                <label for="psw"><b>Password</b></label>
                                <input type="password" placeholder="Enter Password" value={password} onChange={(e) => handleInputChange (e, "password")} required/>
                                <input type="hidden" name="agent_signin"/>

                                <button type="submit" className="stc-electro-login-button">Login</button>
                                <br/>
                                <p>
                                    {
                                        error !=="" ?
                                        <span className="error">{error}</span>:
                                        <span className="success">{msg}</span>
                                    }
                                </p>
                            </div>
                        </form>
                        </div>
                    </div>
                    </div>
                </div>
                <Footer />
            </div>
        </div>
    )
}