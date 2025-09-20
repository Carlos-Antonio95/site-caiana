// ===== Data (mock) =====
const PRODUCTS = [
  {
    id: 1,
    title: "Vestido Mall Estampado",
    price: 84.90,
    category: "vestidos",
    color: "verde",
    size: ["P","M","G"],
    img: "assets/Rosa.jpg"
  },
  {
    id: 2,
    title: "Saia Floral",
    price: 38.90,
    category: "saias",
    color: "verde",
    size: ["P","M","G","GG"],
    img: "assets/Blusas.jpg"
  },
  {
    id: 3,
    title: "Blusa Tropical",
    price: 79.90,
    category: "blusas",
    color: "azul",
    size: ["P","M","G"],
    img: "assets/Marron.jpg"
  },
  {
    id: 4,
    title: "Shorts Folhagem",
    price: 79.90,
    category: "shorts",
    color: "verde",
    size: ["P","M","G"],
    img: "assets/Vestido.jpg"
  },
  {
    id: 5,
    title: "Vestido Aurora",
    price: 49.90,
    category: "vestidos",
    color: "vermelho",
    size: ["P","M","G","GG"],
    img: "assets/Mostarda.jpg"
  },
  {
    id: 6,
    title: "Blusa Sol",
    price: 34.90,
    category: "blusas",
    color: "amarelo",
    size: ["P","M","G"],
    img: "assets/Preta.jpg"
  }
];

// ===== Elements =====
const els = {
  products: document.querySelector(".products"),
  search: document.querySelector("#search"),
  chipCategorias: document.querySelector("#chip-categorias"),
  chipTamanhos: document.querySelector("#chip-tamanhos"),
  chipCores: document.querySelector("#chip-cores"),
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
  size: null,
  color: null,
  maxPrice: els.preco ? parseFloat(els.preco.value) : Infinity
};
// Recupera carrinho do localStorage
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Atualiza o contador no header
function updateCartCount() {
    const cartCountElement = document.querySelector('#cart-count');
    if(cartCountElement) {
        cartCountElement.textContent = cart.reduce((sum, item) => sum + (item.qty || item.quantity || 0), 0);
    }
}

// Chama no carregamento da página
updateCartCount();

// ===== Utils =====
const BRL = v => v.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

function saveCart(){
  localStorage.setItem('cart', JSON.stringify(cart));
}

// ===== Products =====
function applyFilters(list){
  return list.filter(p => {
    const byCat = filters.category === "tudo" || p.category === filters.category;
    const byQ = !filters.q || p.title.toLowerCase().includes(filters.q);
    const byPrice = p.price <= filters.maxPrice;
    const bySize = !filters.size || p.size.includes(filters.size);
    const byColor = !filters.color || p.color === filters.color;
    return byCat && byQ && byPrice && bySize && byColor;
  });
}

function renderProducts(){
  if(!els.products) return; // se a página não tiver produtos, ignora
  const items = applyFilters(PRODUCTS);
  els.products.innerHTML = items.map(p => `
    <article class="card" data-id="${p.id}">
      <img src="${p.img}" alt="${p.title}" />
      <h4>${p.title}</h4>
      <div class="price">${BRL(p.price)}</div>
      <button class="btn btn-dark add-cart">Adicionar ao carrinho</button>
    </article>
  `).join("");

  // Bind add-to-cart
  document.querySelectorAll(".add-cart").forEach(btn => {
    btn.addEventListener("click", e => {
      const id = parseInt(e.target.closest(".card").dataset.id, 10);
      addToCart(id);
    });
  });
}


// ===== Cart functions =====
function addToCart(id){
  const item = cart.find(i => i.id === id);
  if(item) item.qty += 1;
  else {
    const prod = PRODUCTS.find(p => p.id === id);
    if(prod){
      cart.push({ id: prod.id, title: prod.title, price: prod.price, img: prod.img, qty: 1 });
    }
  }
  saveCart();
  renderCart();
  openDrawer();
}

function renderCart(){
  if(!els.cartItems) return; // se a página não tiver drawer, ignora

  els.cartItems.innerHTML = cart.length
    ? cart.map(i => `
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
    `).join("")
    : `<p>Seu carrinho está vazio.</p>`;

  // subtotal & count
  const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
  if(els.subtotal) els.subtotal.textContent = BRL(subtotal);
  if(els.cartCount) els.cartCount.textContent = cart.reduce((s,i)=> s+i.qty, 0);

  // bind qty buttons
  document.querySelectorAll(".cart-item .inc").forEach(b => b.onclick = qtyInc);
  document.querySelectorAll(".cart-item .dec").forEach(b => b.onclick = qtyDec);
}

function qtyInc(e){
  const id = parseInt(e.target.closest(".cart-item").dataset.id, 10);
  cart = cart.map(i => i.id === id ? {...i, qty: i.qty + 1} : i);
  saveCart();
  renderCart();
}

function qtyDec(e){
  const id = parseInt(e.target.closest(".cart-item").dataset.id, 10);
  cart = cart.map(i => i.id === id ? {...i, qty: i.qty - 1} : i).filter(i => i.qty > 0);
  saveCart();
  renderCart();
}

// ===== Drawer =====
function openDrawer(){
  if(els.drawer) els.drawer.classList.add("open");
  if(els.backdrop) els.backdrop.classList.add("show");
}
function closeDrawer(){
  if(els.drawer) els.drawer.classList.remove("open");
  if(els.backdrop) els.backdrop.classList.remove("show");
}
if(els.cartBtn) els.cartBtn.addEventListener("click", openDrawer);
if(els.closeDrawer) els.closeDrawer.addEventListener("click", closeDrawer);
if(els.backdrop) els.backdrop.addEventListener("click", closeDrawer);

// ===== Filters / Search =====
if(els.search){
  els.search.addEventListener("input", e => {
    filters.q = e.target.value.trim().toLowerCase();
    renderProducts();
  });
}
if(els.chipCategorias){
  els.chipCategorias.addEventListener("click", e => {
    if(!e.target.classList.contains("chip")) return;
    document.querySelectorAll("#chip-categorias .chip").forEach(c => c.classList.remove("is-active"));
    e.target.classList.add("is-active");
    filters.category = e.target.dataset.category;
    renderProducts();
  });
}
if(els.chipTamanhos){
  els.chipTamanhos.addEventListener("click", e => {
    if(!e.target.classList.contains("chip")) return;
    document.querySelectorAll("#chip-tamanhos .chip").forEach(c => c.classList.remove("is-active"));
    e.target.classList.add("is-active");
    filters.size = e.target.dataset.size;
    renderProducts();
  });
}
if(els.chipCores){
  els.chipCores.addEventListener("click", e => {
    if(!e.target.classList.contains("swatch")) return;
    document.querySelectorAll("#chip-cores .swatch").forEach(c => c.classList.remove("is-active"));
    e.target.classList.add("is-active");
    filters.color = e.target.dataset.color;
    renderProducts();
  });
}
if(els.preco){
  els.preco.addEventListener("input", e => {
    filters.maxPrice = parseFloat(e.target.value);
    if(els.precoVal) els.precoVal.textContent = `Até ${BRL(filters.maxPrice)}`;
    renderProducts();
  });
}

// ===== Checkout (mock) =====
if(els.checkout){
  els.checkout.addEventListener("click", () => {
    alert("✅ Pedido finalizado! (Fluxo de pagamento fictício para demo)");
    cart = [];
    saveCart();
    renderCart();
    closeDrawer();
  });
}

// ===== Footer year =====
const yearEl = document.querySelector("#year");
if(yearEl) yearEl.textContent = new Date().getFullYear();

// ===== Init =====
renderProducts();
renderCart();
