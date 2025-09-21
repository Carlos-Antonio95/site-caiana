// ===== Elements =====
const els = {
  products: document.querySelector(".products"),
  search: document.querySelector("#search"),
  chipCategorias: document.querySelector("#chip-categorias"),
  preco: document.querySelector("#preco"),
  precoVal: document.querySelector("#preco-val"),
  cartBtn: document.querySelector("#btn-cart"),
  drawer: document.querySelector("#drawer"),
  backdrop: document.querySelector("#backdrop"),
  cartItems: document.querySelector("#cart-items"),
  subtotal: document.querySelector("#subtotal"),
  cartCount: document.querySelector("#cart-count"),
  closeDrawer: document.querySelector("#close-drawer"),
  checkout: document.querySelector("#checkout"),
};

// ===== Filters =====
let filters = {
  q: "",
  category: "tudo",
  maxPrice: els.preco ? parseFloat(els.preco.value) : Infinity
};

// ===== Produtos e carrinho =====
let PRODUCTS = [];
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// ===== Utils =====
const BRL = v => v.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
const saveCart = () => localStorage.setItem('cart', JSON.stringify(cart));

// ===== Fetch produtos =====
async function fetchProducts() {
  try {
    const res = await fetch('/api/products');
    if (!res.ok) throw new Error('Erro ao buscar produtos');
    PRODUCTS = await res.json();
    renderProducts();
  } catch (err) {
    console.error(err);
  }
}

// ===== Apply filters =====
function applyFilters(list) {
  return list.filter(p => {
    const catMatch = filters.category === "tudo" || p.category?.category_name.toLowerCase() === filters.category.toLowerCase();
    const titleMatch = !filters.q || p.title.toLowerCase().includes(filters.q);
    const priceMatch = p.price <= filters.maxPrice;
    return catMatch && titleMatch && priceMatch;
  });
}

// ===== Render produtos =====
function renderProducts() {
  if (!els.products) return;
  const items = applyFilters(PRODUCTS);
  els.products.innerHTML = items.map(p => `
    <article class="card" data-id="${p.id}">
      <img src="${p.images?.[0]?.image_path || 'assets/default.jpg'}" alt="${p.title}" />
      <h4>${p.title}</h4>
      <div class="price">${BRL(p.price)}</div>
      <button class="btn btn-dark add-cart">Adicionar ao carrinho</button>
    </article>
  `).join("");

  document.querySelectorAll(".add-cart").forEach(btn => {
    btn.addEventListener("click", e => {
      const id = parseInt(e.target.closest(".card").dataset.id, 10);
      addToCart(id);
    });
  });
}

// ===== Filtros =====
// Busca
els.search?.addEventListener("input", e => {
  filters.q = e.target.value.trim().toLowerCase();
  renderProducts();
});

// Categoria
els.chipCategorias?.querySelectorAll(".chip").forEach(chip => {
  chip.addEventListener("click", e => {
    // Remove ativo
    els.chipCategorias.querySelectorAll(".chip").forEach(c => c.classList.remove("is-active"));
    // Marca selecionado
    e.target.classList.add("is-active");
    filters.category = e.target.dataset.category.toLowerCase();
    renderProducts();
  });
});

// Preço
els.preco?.addEventListener("input", e => {
  filters.maxPrice = parseFloat(e.target.value);
  if (els.precoVal) els.precoVal.textContent = `Até ${filters.maxPrice}`;
  renderProducts();
});

// ===== Cart functions =====
function addToCart(id) {
  const item = cart.find(i => i.id === id);
  if (item) item.qty += 1;
  else {
    const prod = PRODUCTS.find(p => p.id === id);
    if (prod) cart.push({
      id: prod.id,
      title: prod.title,
      price: prod.price,
      img: prod.images?.[0]?.image_path || 'assets/default.jpg',
      qty: 1
    });
  }
  saveCart();
  renderCart();
  openDrawer();
}

function renderCart() {
  if (!els.cartItems) return;
  els.cartItems.innerHTML = cart.length ? cart.map(i => `
    <div class="cart-item" data-id="${i.id}">
      <img src="${i.img}" alt="${i.title}" />
      <div class="meta">
        <strong>${i.title}</strong><br/>
        <small>${BRL(i.price)}</small>
      </div>
      <div class="qty">
        <button class="dec">-</button>
        <span>${i.qty}</span>
        <button class="inc">+</button>
      </div>
    </div>
  `).join("") : "<p>Seu carrinho está vazio.</p>";

  const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
  if (els.subtotal) els.subtotal.textContent = BRL(subtotal);
  updateCartCount();

  document.querySelectorAll(".cart-item .inc").forEach(b => b.onclick = qtyInc);
  document.querySelectorAll(".cart-item .dec").forEach(b => b.onclick = qtyDec);
}

function qtyInc(e) {
  const id = parseInt(e.target.closest(".cart-item").dataset.id, 10);
  cart = cart.map(i => i.id === id ? { ...i, qty: i.qty + 1 } : i);
  saveCart();
  renderCart();
}

function qtyDec(e) {
  const id = parseInt(e.target.closest(".cart-item").dataset.id, 10);
  cart = cart.map(i => i.id === id ? { ...i, qty: i.qty - 1 } : i).filter(i => i.qty > 0);
  saveCart();
  renderCart();
}

// ===== Drawer =====
function openDrawer() { els.drawer?.classList.add("open"); els.backdrop?.classList.add("show"); }
function closeDrawer() { els.drawer?.classList.remove("open"); els.backdrop?.classList.remove("show"); }
els.cartBtn?.addEventListener("click", openDrawer);
els.closeDrawer?.addEventListener("click", closeDrawer);
els.backdrop?.addEventListener("click", closeDrawer);

// ===== Cart count =====
function updateCartCount() {
  if (els.cartCount) {
    els.cartCount.textContent = cart.reduce((sum, item) => sum + (item.qty || 0), 0);
  }
}

// ===== Checkout mock =====

els.checkout?.addEventListener("click", () => {
 // alert("✅ Pedido finalizado!");
  //cart = [];
  //saveCart();
  //renderCart();
   window.location.href = '/cart';
  closeDrawer();
  
});

// ===== Footer year =====
const yearEl = document.querySelector("#year");
if (yearEl) yearEl.textContent = new Date().getFullYear();

// ===== Logout =====
const logoutForm = document.getElementById('logout-form');
logoutForm?.addEventListener('submit', () => {
  cart = [];
  saveCart();
  updateCartCount();
});

// ===== Init =====
fetchProducts();
renderCart();
