import { useState, useEffect } from 'react';
import { Link, useNavigate, useSearchParams } from 'react-router-dom';
import { RotatingLines } from 'react-loader-spinner';
import AuthLayout from './AuthLayout';
import './Login.css';

const API_BASE_URL =
    process.env.NODE_ENV === 'production'
        ? 'https://stcassociate.com/stc_gld/vanaheim'
        : 'http://localhost/stc/stc_gld/vanaheim';

export default function ResetPassword() {
    const navigate = useNavigate();
    const [searchParams] = useSearchParams();
    const [token, setToken] = useState('');
    const [newPassword, setNewPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const [msg, setMsg] = useState('');
    const [tokenFromLink, setTokenFromLink] = useState(false);

    useEffect(() => {
        const q = searchParams.get('token');
        if (q && q.trim()) {
            setToken(q.trim());
            setTokenFromLink(true);
        }
    }, [searchParams]);

    const submit = (e) => {
        e.preventDefault();
        setError('');
        setMsg('');
        if (!token.trim()) {
            setError('Open the reset link from your email, or paste the full link into the address bar.');
            return;
        }
        if (newPassword !== confirmPassword) {
            setError('Passwords do not match.');
            return;
        }
        setLoading(true);
        fetch(`${API_BASE_URL}/password_reset.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
            body: JSON.stringify({
                action: 'confirm',
                token: token.trim(),
                new_password: newPassword,
                confirm_password: confirmPassword,
            }),
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
                if (data.success) {
                    setMsg(data.message || 'Password updated.');
                    setTimeout(() => navigate('/'), 2000);
                } else {
                    setError(data.message || 'Reset failed.');
                }
            })
            .catch((err) => {
                setError(err instanceof Error ? err.message : 'Network error.');
            })
            .finally(() => setLoading(false));
    };

    return (
        <AuthLayout navbarTitle="Set new password" activeNav="forgot">
            <div className="col-sm-8 col-md-6 col-lg-5">
                <form className="stc-electronics-login-form" onSubmit={submit}>
                    <div className="imgcontainer">
                        <h1>
                            <i className="fa fa-key" aria-hidden="true" />
                        </h1>
                    </div>
                    <div className="container login-form-fields">
                        {tokenFromLink ? (
                            <p className="text-muted small mb-3">
                                You opened a valid reset link from email. Choose a new password below.
                            </p>
                        ) : (
                            <p className="text-muted small mb-3">
                                Paste the full URL from your email into the browser, or paste only the long token part
                                here.
                            </p>
                        )}

                        {!tokenFromLink ? (
                            <>
                                <label htmlFor="reset-token">
                                    <b>Token from email (if link did not open)</b>
                                </label>
                                <textarea
                                    id="reset-token"
                                    className="login-field font-monospace small"
                                    rows={3}
                                    value={token}
                                    onChange={(e) => setToken(e.target.value)}
                                    disabled={loading}
                                    placeholder="Long code from the reset email"
                                />
                            </>
                        ) : null}

                        <label htmlFor="reset-new">
                            <b>New password</b>
                        </label>
                        <input
                            id="reset-new"
                            className="login-field"
                            type="password"
                            autoComplete="new-password"
                            value={newPassword}
                            onChange={(e) => setNewPassword(e.target.value)}
                            disabled={loading}
                        />

                        <label htmlFor="reset-confirm">
                            <b>Confirm password</b>
                        </label>
                        <input
                            id="reset-confirm"
                            className="login-field"
                            type="password"
                            autoComplete="new-password"
                            value={confirmPassword}
                            onChange={(e) => setConfirmPassword(e.target.value)}
                            disabled={loading}
                        />

                        <button type="submit" className="stc-electro-login-button" disabled={loading}>
                            {loading ? 'Saving…' : 'Update password'}
                        </button>

                        {loading && (
                            <div className="login-inline-loader" aria-live="polite">
                                <RotatingLines strokeColor="#9c27b0" strokeWidth="4" width="28" visible />
                                <span>Working…</span>
                            </div>
                        )}

                        {error ? <p className="error mb-0 mt-2">{error}</p> : null}
                        {msg && !error ? <p className="success mb-0 mt-2">{msg}</p> : null}

                        <p className="mt-3 mb-0">
                            <Link to="/forgot-password">Request a new link</Link>
                            {' · '}
                            <Link to="/">Login</Link>
                        </p>
                    </div>
                </form>
            </div>
        </AuthLayout>
    );
}
