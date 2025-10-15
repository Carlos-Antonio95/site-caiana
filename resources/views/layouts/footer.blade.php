<footer class="site-footer">
    <div class="container footer-inner">
        <p>© <span id="year"></span> CAIANA • Moda Tropical</p>

        <div class="social-links">
            <a href="https://www.instagram.com/seuinstagram" target="_blank" aria-label="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.facebook.com/seufacebook" target="_blank" aria-label="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://wa.me/5599999999999" target="_blank" aria-label="WhatsApp">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>

        <div class="mini">
            <a href="#">Política de Privacidade</a>
            <a href="#">Termos</a>
        </div>
    </div>
</footer>

<script>
    document.getElementById('year').textContent = new Date().getFullYear();
</script>

<style>
/* ====== Footer compacto ====== */
.site-footer {
    background: #d9f5ff;
    color: #15203b;
    font-size: 0.8rem;
    padding: 0.4rem 0;
    text-align: center;
    border-top: 1px solid rgba(0,0,0,0.05);
    margin-top: auto;
}

.footer-inner {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.site-footer .mini a {
    text-decoration: none;
    font-size: 0.75rem;
}

.site-footer .mini a:hover {
    text-decoration: underline;
}

/* Faz o footer ficar colado ao fundo da tela quando o conteúdo é curto */
html, body {
    height: 100%;
    display: flex;
    flex-direction: column;
}

main {
    flex: 1;
}
.site-footer{
    padding-top: 0px;
    padding-bottom: 0px;
    height: 0px;
    border-top-width: 0px;
}

/* Redes sociais */
.social-links {
    display: flex;
    gap: 0.8rem;
    justify-content: center;
}

.social-links a {
    color: #15203b;
    font-size: 1.2rem;
    transition: color 0.3s;
}

.social-links a:hover {
    color: #009688;
}

</style>
