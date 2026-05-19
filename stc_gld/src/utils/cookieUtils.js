// Cookie utility functions for STC GLD application (duplicate of components/cookieUtils — prefer importing from components/cookieUtils.js)

export const getCookie = (name) => {
    const encodedName = encodeURIComponent(name) + '=';
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
    const isProduction = window.location.protocol === 'https:';
    const isLocalhost = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';

    let cookieString = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/`;

    if (isProduction) {
        cookieString += '; Secure';
    }

    if (isProduction && !isLocalhost) {
        cookieString += '; domain=.stcassociate.com';
    }

    cookieString += '; SameSite=Lax';

    document.cookie = cookieString;
};

export const clearAllAuthCookies = () => {
    deleteCookie('user_id');
    deleteCookie('location_stc');
};

export const validateAuthCookies = () => {
    const userId = getCookie('user_id');
    const location = getCookie('location_stc');
    const login = localStorage.getItem('login');

    return {
        isValid: !!(userId && location),
        userId,
        location,
        login,
    };
};

export const deleteCookie = (name) => {
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
};
