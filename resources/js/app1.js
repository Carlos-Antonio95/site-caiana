// ===== Elements =====
const els = {
  products: document.querySelector(".products"),
  promotions: document.querySelector(".promotions"),
  search: document.querySelector("#search"),
  chipCategorias: document.querySelector("#chip-categorias"),
  preco: document.querySelector("#preco"),
  precoVal: document.querySelector("#preco-val"),
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

// ===== Filters =====
let filters = {
  q: "",
  category: "tudo",
  maxPrice: els.preco ? parseFloat(els.preco.value) : Infinity
};

// ===== Produtos e carrinho =====
let PRODUCTS = [];
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let appliedCoupon = null;

// ===== Utils =====
const BRL = v => Number(v).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL', minimumFractionDigits: 2 });
const saveCart = () => localStorage.setItem('cart', JSON.stringify(cart));
const csrfToken = () => document.querySelector('meta[name="csrf-token"]').content;

// image_path é relativo a public/ (ex.: "assets/arquivo.jpg")
const imageUrl = p => p.images?.[0]?.image_path
  ? `/${p.images[0].image_path.replace(/^\/+/, '')}`
  : '/assets/default.jpg';

// ===== Fetch produtos =====
async function fetchProducts() {
  try {
    const res = await fetch('/api/products');
    if (!res.ok) throw new Error('Erro ao buscar produtos');
    PRODUCTS = await res.json();
    renderProducts();
    renderPromotions();
  } catch (err) {
    console.error(err);
  }
}

// ===== Apply filters =====
function applyFilters(list) {
  return list.filter(p => {
    const catMatch =
      filters.category === "tudo" ||
      p.category?.category_name.toLowerCase() === filters.category.toLowerCase();

    const titleMatch = !filters.q || p.title.toLowerCase().includes(filters.q);
    const priceMatch = p.price <= filters.maxPrice;
    return catMatch && titleMatch && priceMatch;
  });
}

// ===== Render de uma grade de produtos =====
function renderGrid(items, container) {
  if (!container) return;
  if (items.length === 0) {
    container.innerHTML = "<p>Não há produtos no momento.</p>";
    return;
  }
  container.innerHTML = items.map(p => {
    const hasDiscount = p.final_price && p.final_price < p.price;
    return `
      <article class="card" data-id="${p.id}">
        <img src="${imageUrl(p)}" alt="${p.title}" />
        <h4>${p.title}</h4>
        <div class="price">
          ${hasDiscount
            ? `<span style="text-decoration: line-through; color: #999;">${BRL(p.price)}</span>
               <span style="color: red; font-weight: bold;">${BRL(p.final_price)}</span>`
            : BRL(p.price)}
        </div>
        <button class="btn btn-dark add-cart">Adicionar ao carrinho</button>
      </article>
    `;
  }).join("");

  container.querySelectorAll(".add-cart").forEach(btn => {
    btn.addEventListener("click", e => {
      const id = parseInt(e.target.closest(".card").dataset.id, 10);
      addToCart(id);
    });
  });
}

// ===== Render produtos =====
function renderProducts() {
  const items = applyFilters(PRODUCTS).filter(p => p.status === 'ativo' && p.stock_quantity > 0);
  renderGrid(items, els.products);
}

// ===== Render promoções (produtos com preço final menor) =====
function renderPromotions() {
  const items = PRODUCTS.filter(p => p.status === 'ativo' && p.stock_quantity > 0 && p.final_price < p.price);
  renderGrid(items, els.promotions);
}

// ===== Filtros =====
els.search?.addEventListener("input", e => {
  filters.q = e.target.value.trim().toLowerCase();
  renderProducts();
  renderPromotions();
});

els.chipCategorias?.querySelectorAll(".chip").forEach(chip => {
  chip.addEventListener("click", e => {
    els.chipCategorias.querySelectorAll(".chip").forEach(c => c.classList.remove("is-active"));
    e.target.classList.add("is-active");
    filters.category = e.target.dataset.category.toLowerCase();
    renderProducts();
    renderPromotions();
  });
});

els.preco?.addEventListener("input", e => {
  filters.maxPrice = parseFloat(e.target.value);
  if (els.precoVal) els.precoVal.textContent = `Até ${filters.maxPrice}`;
  renderProducts();
  renderPromotions();
});

// ===== Cart functions =====
function addToCart(id) {
  const item = cart.find(i => i.id === id);
  if (item) item.qty += 1;
  else {
    const prod = PRODUCTS.find(p => p.id === id);
    if (prod) {
      cart.push({
        id: prod.id,
        title: prod.title,
        price: prod.price,
        final_price: prod.final_price,
        img: imageUrl(prod),
        qty: 1
      });
    }
  }
  saveCart();
  renderCart();
  openDrawer();
}

// ===== Render carrinho =====
function renderCart() {
  if (!els.cartItems) return;
  els.cartItems.innerHTML = cart.length ? cart.map(i => {
    const hasDiscount = i.final_price && i.final_price < i.price;
    return `
      <div class="cart-item" data-id="${i.id}">
        <img src="${i.img}" alt="${i.title}" />
        <div class="meta" style="color: black !important;">
          <strong>${i.title}</strong><br/>
          <small>
            ${hasDiscount
              ? `<span style="text-decoration: line-through; color: #999;">${BRL(i.price)}</span> <span style="color: red; font-weight: bold;">${BRL(i.final_price)}</span>`
              : BRL(i.price)}
          </small>
        </div>
        <div class="qty">
          <button class="dec">-</button>
          <span style="color: black;">${i.qty}</span>
          <button class="inc">+</button>
        </div>
      </div>
    `;
  }).join("") : '<p style="color: black;">Seu carrinho está vazio.</p>';

  const subtotal = cart.reduce((s, i) => s + (i.final_price || i.price) * i.qty, 0);
  let total = subtotal;

  if (appliedCoupon?.amount) {
    const discount = Number(appliedCoupon.amount) || 0;
    total = Math.max(0, subtotal - discount);
    if (els.discountArea) {
      els.discountArea.style.display = 'block';
      els.discountValue.textContent = BRL(discount);
    }
  } else {
    if (els.discountArea) els.discountArea.style.display = 'none';
  }

  if (els.subtotal) els.subtotal.textContent = BRL(subtotal);
  if (els.total) els.total.textContent = BRL(total);

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

function updateCartCount() {
  if (els.cartCount) {
    els.cartCount.textContent = cart.reduce((sum, item) => sum + (item.qty || 0), 0);
  }
}

// ===== Drawer =====
function openDrawer() { els.drawer?.classList.add("open"); els.backdrop?.classList.add("show"); }
function closeDrawer() { els.drawer?.classList.remove("open"); els.backdrop?.classList.remove("show"); }
els.cartBtn?.addEventListener("click", openDrawer);
els.closeDrawer?.addEventListener("click", closeDrawer);
els.backdrop?.addEventListener("click", closeDrawer);

// ===== Cupom =====
document.getElementById("apply-coupon")?.addEventListener("click", async () => {
  const code = document.getElementById("coupon-code").value.trim();
  if (!code) return alert("Digite um código de cupom válido");

  // O servidor calcula o total pelos itens; só enviamos id e quantidade
  const items = cart.map(i => ({ id: i.id, qty: i.qty }));

  try {
    const res = await fetch("/apply-coupon", {
      method: "POST",
      headers: { "Content-Type": "application/json", "Accept": "application/json", "X-CSRF-TOKEN": csrfToken() },
      body: JSON.stringify({ code, items })
    });
    const data = await res.json();
    const msg = document.getElementById("coupon-message");
    msg.style.display = "block";

    if (res.status === 401) {
      appliedCoupon = null;
      msg.style.color = "red";
      msg.textContent = "Entre na sua conta para usar um cupom.";
    } else if (res.ok) {
      appliedCoupon = { amount: Number(parseFloat(data.discount).toFixed(2)) };
      renderCart();
      msg.style.color = "green";
      msg.textContent = "Cupom aplicado com sucesso!";
    } else {
      appliedCoupon = null;
      renderCart();
      msg.style.color = "red";
      msg.textContent = data.error || "Erro ao aplicar cupom";
    }
  } catch (err) {
    console.error(err);
    alert("Erro ao aplicar cupom");
  }
});

// ===== Checkout =====
els.checkout?.addEventListener("click", () => {
  closeDrawer();
  window.location.href = "/cart";
});

// ===== CEP (ViaCEP) =====
document.getElementById("btn-cep")?.addEventListener("click", async () => {
  const cep = document.getElementById("cep").value.replace(/\D/g, "");
  if (!cep) return alert("Digite um CEP válido");

  try {
    const data = await (await fetch(`https://viacep.com.br/ws/${cep}/json/`)).json();
    if (data.erro) return alert("CEP não encontrado");

    document.getElementById("road").value = data.logradouro;
    document.getElementById("neighborhood").value = data.bairro;
    document.getElementById("city").value = data.localidade;
    document.getElementById("state").value = data.uf;
  } catch (err) {
    console.error(err);
    alert("Erro ao buscar CEP");
  }
});

// ===== Endereço =====
document.getElementById("address-form")?.addEventListener("submit", async e => {
  e.preventDefault();
  const formData = new FormData(e.target);

  try {
    const res = await fetch("/addresses", {
      method: "POST",
      headers: { "X-CSRF-TOKEN": csrfToken() },
      body: formData
    });
    if (res.ok) {
      alert("Endereço salvo!");
      window.location.reload();
    } else {
      alert("Erro ao salvar endereço");
    }
  } catch (err) {
    console.error(err);
    alert("Erro ao salvar endereço");
  }
});

document.getElementById("btn-change-address")?.addEventListener("click", () => {
  const form = document.getElementById("address-form");
  form.style.display = form.style.display === "none" ? "block" : "none";
});

// ===== Rodapé =====
const year = document.querySelector("#year");
if (year) year.textContent = new Date().getFullYear();

// ===== Logout limpa o carrinho =====
document.getElementById("logout-form")?.addEventListener("submit", () => {
  cart = [];
  saveCart();
  updateCartCount();
});

// ===== Init =====
fetchProducts();
renderCart();
