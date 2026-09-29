/**
 * HITAM Residential Portal — Inactivity & Smooth Navigation Monitor
 * Enforces 15-minute inactivity session expiration with smooth user experience.
 */
(function () {
    const IDLE_LIMIT_MS = 15 * 60 * 1000; // 15 Minutes
    let lastActivityTime = Date.now();
    let isExpired = false;
    let modalShown = false;

    // Continuously update activity while not expired
    function registerUserActivity() {
        if (!isExpired) {
            lastActivityTime = Date.now();
        }
    }

    // Passive listeners to detect continuous user engagement
    ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart'].forEach(function (evt) {
        window.addEventListener(evt, registerUserActivity, { passive: true });
    });

    // Check inactivity periodically
    function evaluateInactivity() {
        if (!isExpired && (Date.now() - lastActivityTime >= IDLE_LIMIT_MS)) {
            isExpired = true;
        }
    }
    setInterval(evaluateInactivity, 5000);

    // Intercept click interactions once expired
    window.addEventListener('click', function (e) {
        if (isExpired || (Date.now() - lastActivityTime >= IDLE_LIMIT_MS)) {
            isExpired = true;

            // If modal is already shown and user clicks one of its action buttons, allow it
            if (e.target.closest('#sessionTimeoutModal a, #sessionTimeoutModal button')) {
                return;
            }

            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            showTimeoutModal();
        }
    }, true);

    function showTimeoutModal() {
        if (modalShown) return;
        modalShown = true;

        const overlay = document.createElement('div');
        overlay.id = 'sessionTimeoutModal';
        overlay.style.cssText = `
            position: fixed;
            inset: 0;
            background: rgba(2, 44, 34, 0.88);
            z-index: 999999;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(6px);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            opacity: 0;
            transition: opacity 0.3s ease;
        `;

        overlay.innerHTML = `
            <div style="background: #FFFFFF; border-radius: 18px; padding: 36px 32px; max-width: 440px; width: 92%; text-align: center; box-shadow: 0 25px 60px -15px rgba(0,0,0,0.5); transform: translateY(12px); transition: transform 0.3s ease;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: #FEF3C7; color: #D97706; display: inline-flex; align-items: center; justify-content: center; font-size: 28px; margin-bottom: 18px;">
                    ⏱️
                </div>
                <h4 style="font-weight: 700; color: #0F172A; margin-bottom: 8px; font-size: 1.25rem;">
                    Session Timed Out
                </h4>
                <p style="color: #64748B; font-size: 14.5px; margin-bottom: 24px; line-height: 1.55;">
                    Sorry, session timed out due to inactiveness (15 minutes). For security reasons, please sign in again or return to the main portal.
                </p>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <button id="timeoutLoginBtn" style="width: 100%; background: #064E3B; color: #FFFFFF; font-weight: 600; padding: 12px; border-radius: 10px; border: none; font-size: 14.5px; cursor: pointer; transition: background 0.2s ease;">
                        Sign In Again
                    </button>
                    <button id="timeoutHomeBtn" style="width: 100%; background: #F1F5F9; color: #334155; font-weight: 600; padding: 11px; border-radius: 10px; border: 1px solid #E2E8F0; font-size: 14px; cursor: pointer; transition: background 0.2s ease;">
                        Return to Homepage
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);

        // Animate entrance
        requestAnimationFrame(function () {
            overlay.style.opacity = '1';
            overlay.querySelector('div').style.transform = 'translateY(0)';
        });

        document.getElementById('timeoutLoginBtn').addEventListener('click', function () {
            smoothTransitionTo('/login');
        });

        document.getElementById('timeoutHomeBtn').addEventListener('click', function () {
            smoothTransitionTo('/');
        });
    }

    // Smooth page fade-out transition
    function smoothTransitionTo(url) {
        document.body.style.transition = 'opacity 0.4s ease-out';
        document.body.style.opacity = '0';
        setTimeout(function () {
            window.location.href = url;
        }, 380);
    }

    // Attach smooth transition to all logout forms across layouts
    document.addEventListener('DOMContentLoaded', function () {
        const logoutForms = document.querySelectorAll('form[action*="logout"]');
        logoutForms.forEach(function (form) {
            form.addEventListener('submit', function (e) {
                document.body.style.transition = 'opacity 0.4s ease-out';
                document.body.style.opacity = '0';
            });
        });
    });
})();
