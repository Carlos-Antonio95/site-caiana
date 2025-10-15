// ===== Elements =====
const els = {
  cartItems: document.querySelector("#cart-items"),
  subtotal: document.querySelector("#subtotal"),
  cartCount: document.querySelector("#cart-count"),
  drawer: document.querySelector("#drawer"),
  backdrop: document.querySelector("#backdrop"),
  cartBtn: document.querySelector("#btn-cart"),
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

// ===== Quantidade =====
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

// ===== Checkout =====
// ===== Checkout =====
if (els.checkout) {
  // Remove qualquer listener anterior
  const newCheckout = els.checkout.cloneNode(true);
  els.checkout.parentNode.replaceChild(newCheckout, els.checkout);
  
  newCheckout.addEventListener("click", async () => {
    if (!cart.length) {
      alert("Seu carrinho está vazio.");
      return;
    }

    const addressSelect = document.querySelector('#address_id');
    if (!addressSelect || !addressSelect.value) {
      alert("Selecione um endereço antes de finalizar o pedido.");
      return;
    }

    const address_id = addressSelect.value;

    try {
      const res = await fetch("/cart/checkout", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || ""
        },
        body: JSON.stringify({ items: cart, address_id })
      });

      const data = await res.json();

      if (!res.ok) {
        console.error("Erro do backend:", data);
        alert(`❌ Erro: ${data.message || "Verifique o console"}`);
        return;
      }

      alert(`✅ ${data.message}`);
      cart = [];
      saveCart();
      renderCart();
      closeDrawer();
    } catch (err) {
      console.error("Erro no fetch:", err);
      alert("❌ Erro ao finalizar pedido. Confira o console.");
    }
  });
}

// ===== Init =====
document.addEventListener("DOMContentLoaded", () => {
  renderCart();
});
