<footer class="site-footer">
    <div class="container footer-inner">
         <p style="color: black;">© <span id="year"></span> CAIANA • Moda Tropical</p>
            <a href="{{ route('politica') }}" style="color: black;">Política de Privacidade</a>
            <a href="{{ route('termos') }}" style="color: black;">Termos</a>

            <!-- Ícone Instagram -->
            <a href="https://www.instagram.com/caianabyana?igsh=NDZcHV0DJ0c3Nz" target="_blank" aria-label="Instagram">
                <img src="{{ asset('assets/insta.png') }}" alt="Instagram" class="social-icon">
            </a>

            <!-- Ícone WhatsApp -->
            <a href="https://wa.me/5581995387102" target="_blank" aria-label="WhatsApp">
                <img src="{{ asset('assets/wats.png') }}" alt="WhatsApp" class="social-icon">
            </a>
        <div class="mini">
           
        </div>
    </div>
</footer>

<script>
    document.getElementById('year').textContent = new Date().getFullYear();
</script>

<style>
/* ====== Footer compacto ====== */
.site-footer {
    display: inline-flex;
    font-size: 0.8rem;
    padding: 0.4rem 0;
    text-align: center;
    border-top: 0px solid rgba(0,0,0,0.05);
    margin-top: auto;
}

.footer-inner {
    
    justify-content: center;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.site-footer .mini a {
    text-decoration: none;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.site-footer .mini a:hover {
    text-decoration: underline;
    
}

/* Ícones das redes sociais */
.social-icon {
    width: 24px;
    height: 24px;
    object-fit: contain;
    margin-left: 6px;
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.social-icon:hover {
    transform: scale(1.1);
    opacity: 0.8;
}

/* Mantém o footer fixado ao fundo */
html, body {
    height: 100%;
    display: flex;
    flex-direction: column;
}

main {
    flex: 1;
}
</style>
