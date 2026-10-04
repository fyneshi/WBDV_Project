let currentAuthMode = 'login';
let resetToken = sessionStorage.getItem('station_reset_token') || null;

function showView(viewName) {
  document.querySelectorAll('.view-container').forEach(el => el.classList.remove('active'));
  const activeEl = document.getElementById('view-' + viewName);
  if (activeEl) activeEl.classList.add('active');

  const backBtn = document.getElementById('globalBackBtn');
  backBtn.style.display = (viewName === 'auth' || viewName === 'welcome') ? 'none' : 'inline-flex';
}

function setAuthMode(mode) {
  currentAuthMode = mode;
  const isLogin = mode === 'login';

  document.getElementById('tabLogin').classList.toggle('active', isLogin);
  document.getElementById('tabSignup').classList.toggle('active', !isLogin);
  document.getElementById('usernameGroup').style.display = isLogin ? 'none' : 'block';
  document.getElementById('authSubtitle').textContent = isLogin 
    ? 'Log in to continue to your account.' 
    : 'Sign up to continue to your account.';
  document.getElementById('forgotPasswordLink').style.display = isLogin ? 'inline-block' : 'none';
  document.getElementById('authSubmitBtn').textContent = isLogin ? 'Log in' : 'Sign up';
  document.getElementById('footerPrompt').textContent = isLogin ? "Don't have an account?" : 'Already have an account?';
  document.getElementById('footerActionBtn').textContent = isLogin ? 'Sign up' : 'Log in';
}

function toggleAuthMode() {
  setAuthMode(currentAuthMode === 'login' ? 'signup' : 'login');
}

function togglePasswordVisibility(inputId, btn) {
  const input = document.getElementById(inputId);
  const isPassword = input.type === 'password';
  input.type = isPassword ? 'text' : 'password';

  btn.innerHTML = isPassword
    ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
        <line x1="1" y1="1" x2="23" y2="23"></line>
       </svg>`
    : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
        <circle cx="12" cy="12" r="3"></circle>
       </svg>`;
}

// ── Toast Notification ────────────────────────────────────────────
function showToast(message, type = 'success') {
  const toast = document.getElementById('toast');
  toast.textContent = message;
  toast.className = 'toast toast-' + type;
  toast.style.display = 'block';

  setTimeout(() => {
    toast.style.display = 'none';
  }, 4000);
}

// ── Helper: Send request to auth_handler.php ──────────────────────
async function sendAuthRequest(data) {
  try {
    const response = await fetch('auth_handler.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    return await response.json();
  } catch (error) {
    return { success: false, message: 'Network error. Please try again.' };
  }
}

// ── Sign Up / Log In ──────────────────────────────────────────────
async function handleAuthSubmit(e) {
  e.preventDefault();

  const email    = document.getElementById('authEmail').value;
  const password = document.getElementById('authPassword').value;
  const btn      = document.getElementById('authSubmitBtn');

  // Disable button while processing
  btn.disabled = true;
  btn.textContent = currentAuthMode === 'login' ? 'Logging in…' : 'Signing up…';

  let payload = { action: currentAuthMode, email, password };

  if (currentAuthMode === 'signup') {
    const username = document.getElementById('signupUsername').value;
    if (!username.trim()) {
      showToast('Username is required.', 'error');
      btn.disabled = false;
      btn.textContent = 'Sign up';
      return;
    }
    payload.username = username;
  }

  const result = await sendAuthRequest(payload);

  if (result.success) {
    showToast(result.message, 'success');
    const targetUrl = result.redirect || 'home.php';
    setTimeout(() => {
      window.location.href = targetUrl;
    }, 800);
  } else {
    showToast(result.message, 'error');
    btn.disabled = false;
    btn.textContent = currentAuthMode === 'login' ? 'Log in' : 'Sign up';
  }
}

// ── Admin Demo Login Helper ───────────────────────────────────────
function fillAdminLogin() {
  setAuthMode('login');
  document.getElementById('authEmail').value = 'admin@stationfinds.com';
  document.getElementById('authPassword').value = 'admin123';
  showToast('Admin credentials filled. Click "Log in" to access Admin Dashboard.', 'success');
}

// ── Forgot Password ──────────────────────────────────────────────
async function handleForgotSubmit(e) {
  e.preventDefault();
  const email = document.getElementById('forgotEmail').value;

  const result = await sendAuthRequest({ action: 'forgot_password', email });

  if (result.success) {
    // Save token for the reset step (dev mode — in production this comes via email link)
    resetToken = result.token || null;
    if (resetToken) {
      sessionStorage.setItem('station_reset_token', resetToken);
    }
    document.getElementById('displaySentEmail').textContent = email || 'jordan.davis@email.com';
    showView('check-email');
  } else {
    showToast(result.message, 'error');
  }
}

// ── Reset Password ───────────────────────────────────────────────
async function handleResetPasswordSubmit(e) {
  e.preventDefault();
  const newPassword     = document.getElementById('newPassword').value;
  const confirmPassword = document.getElementById('confirmNewPassword').value;

  if (newPassword !== confirmPassword) {
    showToast('Passwords do not match. Please try again.', 'error');
    return;
  }

  if (newPassword.length < 8) {
    showToast('Password must be at least 8 characters.', 'error');
    return;
  }

  if (!resetToken) {
    showToast('No reset token found. Please request a new password reset.', 'error');
    return;
  }

  const result = await sendAuthRequest({
    action: 'reset_password',
    token: resetToken,
    new_password: newPassword,
    confirm_password: confirmPassword
  });

  if (result.success) {
    showToast(result.message, 'success');
    resetToken = null;
    sessionStorage.removeItem('station_reset_token');
    setTimeout(() => {
      showView('auth');
      setAuthMode('login');
    }, 1500);
  } else {
    showToast(result.message, 'error');
  }
}

// ── Logout ───────────────────────────────────────────────────────
async function handleLogout() {
  const result = await sendAuthRequest({ action: 'logout' });
  if (result.success) {
    window.location.href = 'index.php';
  }
}