
import { Link, useNavigate } from 'react-router-dom';
import { useEffect, useState } from 'react';
import { RotatingLines } from 'react-loader-spinner';
import AuthLayout from './AuthLayout';
import './Login.css';
import { setCookie } from './cookieUtils.js';

export default function Login() {
    const navigate = useNavigate();
    const [user, setUser] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const [msg, setMsg] = useState('');
    const [loading, setLoading] = useState(false);

    const API_BASE_URL =
        process.env.NODE_ENV === 'production'
            ? 'https://stcassociate.com/stc_gld/vanaheim'
            : 'http://localhost/stc/stc_gld/vanaheim';

    useEffect(() => {
        const login = localStorage.getItem('login');
        if (login) {
            navigate('/dashboard');
        }
        const loginStatus = localStorage.getItem('loginStatus');
        if (login) {
            setError(loginStatus || '');
            setTimeout(() => {
                localStorage.clear();
                window.location.reload();
            }, 3000);
        }
        const t = setTimeout(() => setMsg(''), 5000);
        return () => clearTimeout(t);
    }, [navigate]);

    const handleInputChange = (e, type) => {
        switch (type) {
            case 'user':
                setError('');
                setUser(e.target.value);
                if (e.target.value === '') {
                    setError('Please enter email or phone.');
                }
                break;
            case 'password':
                setError('');
                setPassword(e.target.value);
                if (e.target.value === '') {
                    setError('Please enter password.');
                }
                break;
            default:
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();

        if (user === '' || password === '') {
            setError('All fields are required.');
            return;
        }

        setLoading(true);
        setError('');
        setMsg('');

        const url = `${API_BASE_URL}/useroath.php`;
        const Data = {
            user,
            password,
            check_login: 1,
        };

        fetch(url, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Data),
        })
            .then(async (response) => {
                const text = await response.text();
                let data;
                try {
                    data = text ? JSON.parse(text) : null;
                } catch {
                    throw new Error('Invalid response from server. Check API URL and PHP errors.');
                }
                return data;
            })
            .then((response) => {
                const row = Array.isArray(response) ? response[0] : response;
                if (!row || typeof row !== 'object') {
                    setError('Unexpected response from server.');
                    return;
                }
                if (row.result === 'success') {
                    const userId = row.user_id;
                    const locationVal = row.location;
                    setMsg('Successfully logged in!!! Redirecting.');
                    setCookie('user_id', userId, 7);
                    setCookie('location_stc', locationVal, 7);
                    setTimeout(() => {
                        localStorage.setItem('login', 'true');
                        navigate('/dashboard');
                    }, 2000);
                } else {
                    setError(String(row.result ?? row.message ?? row.error ?? 'Login failed.'));
                }
            })
            .catch((err) => {
                const message =
                    err instanceof Error ? err.message : typeof err === 'string' ? err : 'Network error. Try again.';
                setError(message);
            })
            .finally(() => setLoading(false));
    };

    return (
        <AuthLayout navbarTitle="Login" activeNav="login">
            <div className="col-sm-6 col-md-6">
                <form className="stc-electronics-login-form" onSubmit={handleSubmit}>
                    <div className="imgcontainer">
                        <h1>
                            <i className="fa fa-key" />
                        </h1>
                    </div>

                    <div className="container login-form-fields">
                        <label htmlFor="login-user">
                            <b>Full Name/ Email or Contact</b>
                        </label>
                        <input
                            id="login-user"
                            className="login-field"
                            type="text"
                            autoComplete="username"
                            placeholder="Enter Email or Contact"
                            value={user}
                            onChange={(e) => handleInputChange(e, 'user')}
                            required
                            disabled={loading}
                        />

                        <label htmlFor="login-password">
                            <b>Password</b>
                        </label>
                        <input
                            id="login-password"
                            className="login-field"
                            type="password"
                            autoComplete="current-password"
                            placeholder="Enter Password"
                            value={password}
                            onChange={(e) => handleInputChange(e, 'password')}
                            required
                            disabled={loading}
                        />
                        <input type="hidden" name="agent_signin" />

                        <button type="submit" className="stc-electro-login-button" disabled={loading}>
                            {loading ? 'Signing in…' : 'Login'}
                        </button>

                        {loading && (
                            <div className="login-inline-loader" aria-live="polite">
                                <RotatingLines strokeColor="#9c27b0" strokeWidth="4" width="28" visible />
                                <span>Signing you in…</span>
                            </div>
                        )}

                        <p className="login-forgot-link mt-2 mb-2">
                            <Link to="/forgot-password">Forgot password?</Link>
                        </p>

                        <p className="mb-0">
                            {error !== '' ? <span className="error">{error}</span> : <span className="success">{msg}</span>}
                        </p>
                    </div>
                </form>
            </div>
        </AuthLayout>
    );
}
