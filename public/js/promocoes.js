// ===== Elements =====
const els = {
    products: document.querySelector(".products"),
    cartBtn: document.querySelector("#btn-cart"),
    drawer: document.querySelector("#drawer"),
    backdrop: document.querySelector("#backdrop"),
    cartItems: document.querySelector("#cart-items"),
    subtotal: document.querySelector("#subtotal"),
    total: document.querySelector("#total"),
    discountArea: document.getElementById("discount-area"),
    discountValue: document.getElementById("discount-value"),
    cartCount: document.querySelector("#cart-count"),
    closeDrawer: document.querySelector("#close-drawer"),
    checkout: document.querySelector("#checkout")
};

// ===== Dados =====
let PRODUCTS = [];
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let appliedCoupon = null;

// ===== Utils =====
const BRL = v => Number(v).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL', minimumFractionDigits: 2 });

// ===== Atualiza o ano no footer =====
const yearEl = document.getElementById('year');
if(yearEl) yearEl.textContent = new Date().getFullYear();

// ===== Fetch produtos =====
async function fetchProducts() {
    try {
        const res = await fetch('/api/products');
        if(!res.ok) throw new Error('Erro ao buscar produtos');
        PRODUCTS = await res.json();
        renderPromoProducts();
    } catch(err) {
        console.error(err);
    }
}

// ===== Render produtos em promoção =====
function renderPromoProducts() {
    if(!els.products) return;

    const items = PRODUCTS.filter(p =>
        p.status === 'ativo' &&
        p.stock_quantity > 0 &&
        p.final_price < p.price // só promoções
    );

    if(items.length === 0){
        els.products.innerHTML = "<p>Não há produtos em promoção no momento.</p>";
        return;
    }

    els.products.innerHTML = items.map(p => {
        const imgPath = p.images?.[0]?.image_path 
            ? `/storage/assets/${p.images[0].image_path.replace(/^\/+/, '')}`
            : '/assets/default.jpg';

        return `
        <article class="card" data-id="${p.id}">
            <img src="${imgPath}" alt="${p.title}">
            <h4>${p.title}</h4>
            <div class="price">
                <span style="text-decoration: line-through; color: #999;">${BRL(p.price)}</span>
                <span style="color:red; font-weight:bold;">${BRL(p.final_price)}</span>
            </div>
            <button class="btn btn-dark add-cart">Adicionar ao carrinho</button>
        </article>
        `;
    }).join("");

    // Adiciona evento de adicionar ao carrinho
    document.querySelectorAll(".add-cart").forEach(btn => {
        btn.addEventListener("click", e => {
            const id = parseInt(e.target.closest(".card").dataset.id, 10);
            addToCart(id);
        });
    });
}

// ===== Funções do carrinho =====
function addToCart(id) {
    const item = cart.find(i => i.id === id);
    if(item) item.qty += 1;
    else {
        const prod = PRODUCTS.find(p => p.id === id);
        if(prod){
            const imgPath = prod.images?.[0]?.image_path 
                ? `/storage/assets/${prod.images[0].image_path.replace(/^\/+/, '')}`
                : '/assets/default.jpg';
            cart.push({ id: prod.id, title: prod.title, price: prod.price, final_price: prod.final_price, img: imgPath, qty: 1 });
        }
    }
    saveCart();
    renderCart();
    openDrawer();
}

function saveCart(){ localStorage.setItem('cart', JSON.stringify(cart)); }

function renderCart(){ 
    if(!els.cartItems) return;

    els.cartItems.innerHTML = cart.length ? cart.map(i => {
        const hasDiscount = i.final_price && i.final_price < i.price;
        return `
        <div class="cart-item" data-id="${i.id}">
            <img src="${i.img}" alt="${i.title}">
            <div class="meta">
                <strong>${i.title}</strong><br/>
                <small>${hasDiscount ? `<span style="text-decoration: line-through; color: #999;">${BRL(i.price)}</span>
                <span style="color: red; font-weight: bold;">${BRL(i.final_price)}</span>` : BRL(i.price)}</small>
            </div>
            <div class="qty">
                <button class="dec">-</button>
                <span>${i.qty}</span>
                <button class="inc">+</button>
            </div>
        </div>
        `;
    }).join("") : "<p>Seu carrinho está vazio.</p>";

    const subtotal = cart.reduce((s,i)=> s + (i.final_price || i.price)*i.qty,0);
    let total = subtotal;
    if(appliedCoupon?.amount){
        total = Math.max(0, subtotal - appliedCoupon.amount);
        els.discountArea.style.display = 'block';
        els.discountValue.textContent = BRL(appliedCoupon.amount);
    } else els.discountArea.style.display = 'none';

    els.subtotal.textContent = BRL(subtotal);
    els.total.textContent = BRL(total);

    document.querySelectorAll(".cart-item .inc").forEach(b => b.onclick = qtyInc);
    document.querySelectorAll(".cart-item .dec").forEach(b => b.onclick = qtyDec);
    updateCartCount();
}

function qtyInc(e){ const id = parseInt(e.target.closest(".cart-item").dataset.id,10); cart = cart.map(i=> i.id===id? {...i, qty:i.qty+1} : i); saveCart(); renderCart();
