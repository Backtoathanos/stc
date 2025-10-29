// Cookie utility functions for STC GLD application

export const getCookie = (name) => {
    const encodedName = encodeURIComponent(name) + "=";
    const cookies = document.cookie.split(';');
    
    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i];
        while (cookie.charAt(0) === ' ') {
            cookie = cookie.substring(1);
        }
        if (cookie.indexOf(encodedName) === 0) {
            return decodeURIComponent(cookie.substring(encodedName.length, cookie.length));
        }
    }
    return null;
};

export const setCookie = (name, value, days) => {
    const expires = new Date(Date.now() + days * 864e5).toUTCString();
    // Check if we're in production (HTTPS) or development (HTTP)
    const isProduction = window.location.protocol === 'https:';
    const isLocalhost = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
    
    let cookieString = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/`;
    
    // Only add Secure flag in production with HTTPS
    if (isProduction) {
        cookieString += '; Secure';
    }
    
    // Add domain for production deployment
    if (isProduction && !isLocalhost) {
        cookieString += '; domain=.stcassociate.com';
    }
    
    // Use SameSite=Lax for better compatibility
    cookieString += '; SameSite=Lax';
    
    console.log('Setting cookie:', cookieString); // Debug log
    document.cookie = cookieString;
    
    // Verify cookie was set
    setTimeout(() => {
        const cookieValue = getCookie(name);
        console.log(`Cookie ${name} verification:`, cookieValue ? 'SUCCESS' : 'FAILED', 'Value:', cookieValue);
    }, 100);
};

export const deleteCookie = (name) => {
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
};

export const clearAllAuthCookies = () => {
    deleteCookie("user_id");
    deleteCookie("location_stc");
    console.log("Cleared all authentication cookies");
};

export const validateAuthCookies = () => {
    const userId = getCookie("user_id");
    const location = getCookie("location_stc");
    const login = localStorage.getItem("login");
    
    console.log("Cookie Validation:", {
        userId: userId,
        location: location,
        login: login,
        allCookies: document.cookie
    });
    
    return {
        isValid: !!(userId && location && login),
        userId,
        location,
        login
    };
};
