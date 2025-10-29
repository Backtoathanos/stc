import { useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { validateAuthCookies } from "./components/cookieUtils.js";

const Protected = (props) => {
    const navigate = useNavigate();
    const { Component} = props;
    
    useEffect(()=>{
        const authStatus = validateAuthCookies();
        
        if(!authStatus.isValid){
            console.log("Authentication failed - redirecting to login");
            localStorage.setItem("loginStatus", "Please login.");
            navigate("/", {replace:true});
        }
    }, []);
    
    return (
        <Component />
    );
}

export default Protected;