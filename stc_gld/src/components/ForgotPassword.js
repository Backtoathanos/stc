import { useState } from 'react';
import { Link } from 'react-router-dom';
import { RotatingLines } from 'react-loader-spinner';
import AuthLayout from './AuthLayout';
import './Login.css';

const API_BASE_URL =
    process.env.NODE_ENV === 'production'
        ? 'https://stcassociate.com/stc_gld/vanaheim'
        : 'http://localhost/stc/stc_gld/vanaheim';

export default function ForgotPassword() {
    const [user, setUser] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const [msg, setMsg] = useState('');
    const [submitted, setSubmitted] = useState(false);

    const submit = (e) => {
        e.preventDefault();
        setError('');
        setMsg('');
        setSubmitted(false);
        if (!user.trim()) {
            setError('Enter email or contact number.');
            return;
        }
        setLoading(true);
        fetch(`${API_BASE_URL}/password_reset.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
            body: JSON.stringify({ action: 'request', user: user.trim() }),
        })
            .then(async (r) => {
                const text = await r.text();
                try {
                    return text ? JSON.parse(text) : {};
                } catch {
                    throw new Error('Invalid server response.');
                }
            })
            .then((data) => {
                if (data.success && data.message) {
                    setMsg(data.message);
                    setSubmitted(true);
                } else {
                    setError(data.message || 'Request failed.');
                }
            })
            .catch((err) => {
                setError(err instanceof Error ? err.message : 'Network error.');
            })
            .finally(() => setLoading(false));
    };

    return (
        <AuthLayout navbarTitle="Forgot password" activeNav="forgot">
            <div className="col-sm-8 col-md-6 col-lg-5">
                <form className="stc-electronics-login-form" onSubmit={submit}>
                    <div className="imgcontainer">
                        <h1>
                            <i className="fa fa-unlock-alt" aria-hidden="true" />
                        </h1>
                    </div>
                    <div className="container login-form-fields">
                        <p className="text-muted small">
                            Enter the <strong>email</strong> or <strong>contact number</strong> registered on your STC GLD
                            account. We will email a reset link to the <strong>address on file only</strong> — not to this
                            browser — so only the account owner can complete the reset.
                        </p>
                        <label htmlFor="forgot-user">
                            <b>Email or contact</b>
                        </label>
                        <input
                            id="forgot-user"
                            className="login-field"
                            type="text"
                            autoComplete="username"
                            value={user}
                            onChange={(e) => setUser(e.target.value)}
                            disabled={loading || submitted}
                        />

                        <button
                            type="submit"
                            className="stc-electro-login-button"
                            disabled={loading || submitted}
                        >
                            {loading ? 'Sending…' : submitted ? 'Email sent' : 'Send reset link'}
                        </button>

                        {loading && (
                            <div className="login-inline-loader" aria-live="polite">
                                <RotatingLines strokeColor="#9c27b0" strokeWidth="4" width="28" visible />
                                <span>Working…</span>
                            </div>
                        )}

                        {error ? <p className="error mb-0 mt-2">{error}</p> : null}
                        {msg && !error ? <p className="success mb-2 mt-2">{msg}</p> : null}

                        {submitted ? (
                            <button
                                type="button"
                                className="stc-electro-login-button mt-2"
                                style={{ backgroundColor: '#6c757d' }}
                                onClick={() => {
                                    setSubmitted(false);
                                    setMsg('');
                                    setUser('');
                                }}
                            >
                                Use a different email or contact
                            </button>
                        ) : null}

                        <p className="mt-3 mb-0">
                            <Link to="/">Back to login</Link>
                        </p>
                    </div>
                </form>
            </div>
        </AuthLayout>
    );
}
