import React, { useState, useEffect } from 'react';
import { validateAuthCookies } from './cookieUtils.js';

const CookieDebugger = () => {
    const [debugInfo, setDebugInfo] = useState({});
    const [isVisible, setIsVisible] = useState(false);

    useEffect(() => {
        const updateDebugInfo = () => {
            const authStatus = validateAuthCookies();
            setDebugInfo({
                ...authStatus,
                allCookies: document.cookie,
                timestamp: new Date().toLocaleString(),
                userAgent: navigator.userAgent,
                protocol: window.location.protocol,
                hostname: window.location.hostname,
                port: window.location.port
            });
        };

        // Update immediately
        updateDebugInfo();

        // Update every 5 seconds
        const interval = setInterval(updateDebugInfo, 5000);

        return () => clearInterval(interval);
    }, []);

    if (!isVisible) {
        return (
            <button 
                onClick={() => setIsVisible(true)}
                style={{
                    position: 'fixed',
                    bottom: '10px',
                    right: '10px',
                    zIndex: 9999,
                    background: '#007bff',
                    color: 'white',
                    border: 'none',
                    padding: '5px 10px',
                    borderRadius: '5px',
                    cursor: 'pointer',
                    fontSize: '12px',
                    display:'none'
                }}
            >
                Debug Cookies
            </button>
        );
    }

    return (
        <div style={{
            position: 'fixed',
            bottom: '10px',
            right: '10px',
            zIndex: 9999,
            background: 'white',
            border: '1px solid #ccc',
            borderRadius: '5px',
            padding: '10px',
            maxWidth: '400px',
            maxHeight: '300px',
            overflow: 'auto',
            boxShadow: '0 2px 10px rgba(0,0,0,0.1)'
        }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '10px' }}>
                <h4 style={{ margin: 0, fontSize: '14px' }}>Cookie Debug Info</h4>
                <button 
                    onClick={() => setIsVisible(false)}
                    style={{ background: 'none', border: 'none', fontSize: '16px', cursor: 'pointer' }}
                >
                    ×
                </button>
            </div>
            
            <div style={{ fontSize: '12px', lineHeight: '1.4' }}>
                <div><strong>Status:</strong> {debugInfo.isValid ? '✅ Valid' : '❌ Invalid'}</div>
                <div><strong>User ID:</strong> {debugInfo.userId || 'Not found'}</div>
                <div><strong>Location:</strong> {debugInfo.location || 'Not found'}</div>
                <div><strong>Login:</strong> {debugInfo.login || 'Not found'}</div>
                <div><strong>Protocol:</strong> {debugInfo.protocol}</div>
                <div><strong>Hostname:</strong> {debugInfo.hostname}</div>
                <div><strong>Port:</strong> {debugInfo.port || 'default'}</div>
                <div><strong>Last Updated:</strong> {debugInfo.timestamp}</div>
                
                <details style={{ marginTop: '10px' }}>
                    <summary style={{ cursor: 'pointer', fontWeight: 'bold' }}>All Cookies</summary>
                    <pre style={{ 
                        fontSize: '10px', 
                        background: '#f5f5f5', 
                        padding: '5px', 
                        margin: '5px 0',
                        wordBreak: 'break-all',
                        whiteSpace: 'pre-wrap'
                    }}>
                        {debugInfo.allCookies || 'No cookies found'}
                    </pre>
                </details>
            </div>
        </div>
    );
};

export default CookieDebugger;
