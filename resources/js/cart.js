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

// ===== Carrinho =====
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// ===== Utils =====
const BRL = v => v.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
const saveCart = () => localStorage.setItem('cart', JSON.stringify(cart));

// ===== Render carrinho =====
function renderCart() {
  if (!els.cartItems) return;

  els.cartItems.innerHTML = cart.length
    ? cart.map(i => `
        <div class="cart-item" data-id="${i.id}">
          <img src="${i.img || 'assets/default.jpg'}" alt="${i.title}" />
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
      `).join("")
    : "<p>Seu carrinho está vazio.</p>";

  els.subtotal.textContent = BRL(cart.reduce((s, i) => s + i.price * i.qty, 0));
  updateCartCount();

  document.querySelectorAll(".cart-item .inc").forEach(b => b.onclick = qtyInc);
  document.querySelectorAll(".cart-item .dec").forEach(b => b.onclick = qtyDec);
}

// ===== Cart functions =====
function addToCart(id, product) {
  const item = cart.find(i => i.id === id);
  if (item) item.qty += 1;
  else {
    cart.push({
      id: product.id,
      title: product.title,
      price: product.price,
      img: product.images?.[0]?.image_path || 'assets/default.jpg',
      qty: 1
    });
  }
  saveCart();
  renderCart();
  openDrawer();
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
  if (els.cartCount) els.cartCount.textContent = cart.reduce((sum, i) => sum + (i.qty || 0), 0);
}

// ===== Produtos & filtros =====
let filters = { q: "", category: "tudo", maxPrice: els.preco ? parseFloat(els.preco.value) : Infinity };
let PRODUCTS = [];

async function fetchProducts() {
  try {
    const res = await fetch('/api/products');
    if (!res.ok) throw new Error('Erro ao buscar produtos');
    PRODUCTS = await res.json();
    renderProducts();
  } catch (err) { console.error(err); }
}

function applyFilters(list) {
  return list.filter(p => {
    const catMatch = filters.category === "tudo" || p.category?.category_name.toLowerCase() === filters.category.toLowerCase();
    const titleMatch = !filters.q || p.title.toLowerCase().includes(filters.q);
    const priceMatch = p.price <= filters.maxPrice;
    return catMatch && titleMatch && priceMatch;
  });
}

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
      const product = PRODUCTS.find(p => p.id === id);
      addToCart(id, product);
    });
  });
}

// ===== Filtros UI =====
els.search?.addEventListener("input", e => { filters.q = e.target.value.trim().toLowerCase(); renderProducts(); });
els.chipCategorias?.querySelectorAll(".chip").forEach(chip => {
  chip.addEventListener("click", e => {
    els.chipCategorias.querySelectorAll(".chip").forEach(c => c.classList.remove("is-active"));
    e.target.classList.add("is-active");
    filters.category = e.target.dataset.category.toLowerCase();
    renderProducts();
  });
});
els.preco?.addEventListener("input", e => {
  filters.maxPrice = parseFloat(e.target.value);
  if (els.precoVal) els.precoVal.textContent = `Até ${filters.maxPrice}`;
  renderProducts();
});

// ===== Events =====
document.addEventListener("DOMContentLoaded", () => {
  console.log("DOM carregado, inicializando eventos...");

  renderCart();

  // Checkout
  els.checkout?.addEventListener("click", async () => {
    console.log("Botão de checkout clicado");

    if (!cart.length) {
      alert("Seu carrinho está vazio.");
      return;
    }

    try {
      const res = await fetch("/cart/checkout", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || ""
        },
        body: JSON.stringify({ items: cart })
      });

      const data = await res.json();
      if (!res.ok) throw new Error(data.message || "Erro ao finalizar pedido");

      alert(`✅ ${data.message}`);
      cart = [];
      saveCart();
      renderCart();
      closeDrawer();
    } catch (err) {
      console.error("Erro no checkout:", err);
      alert("❌ Erro ao finalizar pedido. Confira o console.");
    }
  });
});

// ===== Footer e logout =====
const yearEl = document.querySelector("#year");
if (yearEl) yearEl.textContent = new Date().getFullYear();

const logoutForm = document.getElementById('logout-form');
logoutForm?.addEventListener('submit', () => { cart = []; saveCart(); updateCartCount(); });

// ===== Init =====
fetchProducts();
renderCart();
