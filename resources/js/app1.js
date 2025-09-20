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

let filters = {
  q: "",
  category: "tudo",
  size: null,
  color: null,
  maxPrice: parseFloat(els.preco.value)
};

//let cart = []; 19/09
// Persist cart in localStorage
let cart = JSON.parse(localStorage.getItem('cart')) || [];


// ===== Utils =====
const BRL = v => v.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

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
function addToCart(id){
  const item = cart.find(i => i.id === id);
  if(item) item.qty += 1;
  else{
    const prod = PRODUCTS.find(p => p.id === id);
    cart.push({ id: prod.id, title: prod.title, price: prod.price, img: prod.img, qty: 1 });
  }

  // salva no localStorage
  localStorage.setItem('cart', JSON.stringify(cart));

  renderCart();
  openDrawer();
}

/*
function addToCart(id){
  const item = cart.find(i => i.id === id);
  if(item) item.qty += 1;
  else{
    const prod = PRODUCTS.find(p => p.id === id);
    cart.push({ id: prod.id, title: prod.title, price: prod.price, img: prod.img, qty: 1 });
  }
  renderCart();
  openDrawer();
}*/

function renderCart(){
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
  els.subtotal.textContent = BRL(subtotal);
  els.cartCount.textContent = cart.reduce((s,i)=> s+i.qty, 0);

  // bind qty buttons
  document.querySelectorAll(".cart-item .inc").forEach(b => b.onclick = qtyInc);
  document.querySelectorAll(".cart-item .dec").forEach(b => b.onclick = qtyDec);
}

function qtyInc(e){
  const id = parseInt(e.target.closest(".cart-item").dataset.id, 10);
  cart = cart.map(i => i.id === id ? {...i, qty: i.qty + 1} : i);

  localStorage.setItem('cart', JSON.stringify(cart)); // <-- salva

  renderCart();
}

function qtyDec(e){
  const id = parseInt(e.target.closest(".cart-item").dataset.id, 10);
  cart = cart.map(i => i.id === id ? {...i, qty: i.qty - 1} : i).filter(i => i.qty > 0);

  localStorage.setItem('cart', JSON.stringify(cart)); // <-- salva

  renderCart();
}

/*
function qtyInc(e){
  const id = parseInt(e.target.closest(".cart-item").dataset.id, 10);
  cart = cart.map(i => i.id === id ? {...i, qty: i.qty + 1} : i);
  renderCart();
}
function qtyDec(e){
  const id = parseInt(e.target.closest(".cart-item").dataset.id, 10);
  cart = cart.map(i => i.id === id ? {...i, qty: i.qty - 1} : i).filter(i => i.qty > 0);
  renderCart();
}*/

// ===== Drawer =====
function openDrawer(){
  els.drawer.classList.add("open");
  els.backdrop.classList.add("show");
}
function closeDrawer(){
  els.drawer.classList.remove("open");
  els.backdrop.classList.remove("show");
}

els.cartBtn.addEventListener("click", openDrawer);
els.closeDrawer.addEventListener("click", closeDrawer);
els.backdrop.addEventListener("click", closeDrawer);

// ===== Filters / Search =====
els.search.addEventListener("input", e => {
  filters.q = e.target.value.trim().toLowerCase();
  renderProducts();
});

els.chipCategorias.addEventListener("click", e => {
  if(!e.target.classList.contains("chip")) return;
  document.querySelectorAll("#chip-categorias .chip").forEach(c => c.classList.remove("is-active"));
  e.target.classList.add("is-active");
  filters.category = e.target.dataset.category;
  renderProducts();
});

const chipTamanhos = document.getElementById('chip-tamanhos');
if (chipTamanhos) {
  chipTamanhos.addEventListener('click', e => {
    if(!e.target.classList.contains("chip")) return;
    document.querySelectorAll("#chip-tamanhos .chip").forEach(c => c.classList.remove("is-active"));
    e.target.classList.add("is-active");
    filters.size = e.target.dataset.size;
    renderProducts();
  });
}

const chipCores = document.getElementById('chip-cores');
if (chipCores) {
  chipCores.addEventListener("click", e => {
    if(!e.target.classList.contains("swatch")) return;
    document.querySelectorAll("#chip-cores .swatch").forEach(c => c.classList.remove("is-active"));
    e.target.classList.add("is-active");
    filters.color = e.target.dataset.color;
    renderProducts();
  });
}

els.preco.addEventListener("input", e => {
  filters.maxPrice = parseFloat(e.target.value);
  els.precoVal.textContent = `Até ${BRL(filters.maxPrice)}`;
  renderProducts();
});

// ===== Checkout (mock) =====
els.checkout.addEventListener("click", () => {
  alert("✅ Pedido finalizado! (Fluxo de pagamento fictício para demo)");
  cart = [];
  
  localStorage.setItem('cart', JSON.stringify(cart)); // <-- limpa o carrinho

  renderCart();
  closeDrawer();
});

/*
els.checkout.addEventListener("click", () => {
  alert("✅ Pedido finalizado! (Fluxo de pagamento fictício para demo)");
  cart = [];
  renderCart();
  closeDrawer();
});
*/
// Footer year
document.querySelector("#year").textContent = new Date().getFullYear();

// Init
renderProducts();
renderCart();
