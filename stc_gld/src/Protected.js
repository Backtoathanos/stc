import { useEffect, useLayoutEffect } from "react";
import { useNavigate } from "react-router-dom";
import { validateAuthCookies } from "./components/cookieUtils.js";

const MD_MOBILE_MAX = 991;

const Protected = (props) => {
    const navigate = useNavigate();
    const { Component} = props;
    
    useEffect(()=>{
        const authStatus = validateAuthCookies();
        
        if (!authStatus.isValid) {
            localStorage.setItem('loginStatus', 'Please login.');
            navigate('/', { replace: true });
        }
    }, [navigate]);

    /* Material Dashboard toggles html.nav-open for the mobile drawer; clear it on desktop to avoid a stuck transform. */
    useLayoutEffect(() => {
        const sync = () => {
            if (typeof document === "undefined") return;
            if (window.innerWidth > MD_MOBILE_MAX) {
                document.documentElement.classList.remove("nav-open");
            }
        };
        sync();
        window.addEventListener("resize", sync);
        return () => window.removeEventListener("resize", sync);
    }, []);
    
    return (
        <Component />
    );
}

export default Protected;