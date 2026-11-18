<style>
    .footer-custom {
        padding: 1.5rem 2rem;
        background-color: #fff;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    .footer-links {
        display: flex;
        gap: 1.5rem;
    }

    .footer-links a {
        color: var(--text-muted);
        text-decoration: none;
        transition: var(--transition);
    }

    .footer-links a:hover {
        color: var(--primary-color);
    }
</style>

<footer class="footer-custom">
    <div>
        &copy; {{ date('Y') }} <span class="fw-bold text-dark">FitCore</span>. All rights reserved.
    </div>
    <div class="footer-links">
        <a href="#">Support</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
    </div>
</footer>
