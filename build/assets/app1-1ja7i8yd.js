const c={products:document.querySelector(".products"),promotions:document.querySelector(".promotions"),search:document.querySelector("#search"),chipCategorias:document.querySelector("#chip-categorias"),preco:document.querySelector("#preco"),precoVal:document.querySelector("#preco-val"),cartBtn:document.querySelector("#btn-cart"),drawer:document.querySelector("#drawer"),backdrop:document.querySelector("#backdrop"),cartItems:document.querySelector("#cart-items"),subtotal:document.querySelector("#subtotal"),total:document.querySelector("#total"),discountArea:document.getElementById("discount-area"),discountValue:document.getElementById("discount-value"),cartCount:document.querySelector("#cart-count"),closeDrawer:document.querySelector("#close-drawer"),checkout:document.querySelector("#checkout")};let i={q:"",category:"tudo",maxPrice:c.preco?parseFloat(c.preco.value):1/0},s=[],m=[],o=JSON.parse(localStorage.getItem("cart"))||[];const n=r=>Number(r).toLocaleString("pt-BR",{style:"currency",currency:"BRL",minimumFractionDigits:2}),p=()=>localStorage.setItem("cart",JSON.stringify(o));async function q(){try{const r=await fetch("/api/products");if(!r.ok)throw new Error("Erro ao buscar produtos");s=await r.json(),l(),d()}catch(r){console.error(r)}}function v(r){return r.filter(e=>{const t=i.category==="tudo"||e.category?.category_name.toLowerCase()===i.category.toLowerCase(),a=!i.q||e.title.toLowerCase().includes(i.q),h=e.price<=i.maxPrice;return t&&a&&h})}function l(){if(!c.products)return;const r=v(s).filter(e=>e.status==="ativo"&&e.stock_quantity>0);c.products.innerHTML=r.map(e=>{const t=e.final_price&&e.final_price<e.price,a=e.images?.[0]?.image_path?`/${e.images[0].image_path.replace(/^\/+/,"")}`:"/assets/default.jpg";return`
      <article class="card" data-id="${e.id}">
        <img src="${a}" alt="${e.title}" />
        <h4>${e.title}</h4>
        <div class="price">
          ${t?`<span style="text-decoration: line-through; color: #999;">${n(e.price)}</span> 
               <span style="color: red; font-weight: bold;">${n(e.final_price)}</span>`:n(e.price)}
        </div>
        <button class="btn btn-dark add-cart">Adicionar ao carrinho</button>
      </article>
    `}).join(""),c.products.querySelectorAll(".add-cart").forEach(e=>{e.addEventListener("click",t=>{const a=parseInt(t.target.closest(".card").dataset.id,10);g(a)})})}function b(){return m=s.filter(r=>r.status==="ativo"&&r.stock_quantity>0&&r.final_price<r.price),m}function d(){if(!c.promotions)return;const r=b();if(r.length===0){c.promotions.innerHTML="<p>Não há produtos em promoção no momento.</p>";return}c.promotions.innerHTML=r.map(e=>{const t=e.images?.[0]?.image_path?`/${e.images[0].image_path.replace(/^\/+/,"")}`:"/assets/default.jpg";return`
      <article class="card" data-id="${e.id}">
        <img src="${t}" alt="${e.title}">
        <h4>${e.title}</h4>
        <div class="price">
          <span style="text-decoration: line-through; color: #999;">${n(e.price)}</span>
          <span style="color:red; font-weight:bold;">${n(e.final_price)}</span>
        </div>
        <button class="btn btn-dark add-cart">Adicionar ao carrinho</button>
      </article>
    `}).join(""),c.promotions.querySelectorAll(".add-cart").forEach(e=>{e.addEventListener("click",t=>{const a=parseInt(t.target.closest(".card").dataset.id,10);g(a)})})}c.search?.addEventListener("input",r=>{i.q=r.target.value.trim().toLowerCase(),l(),d()});c.chipCategorias?.querySelectorAll(".chip").forEach(r=>{r.addEventListener("click",e=>{c.chipCategorias.querySelectorAll(".chip").forEach(t=>t.classList.remove("is-active")),e.target.classList.add("is-active"),i.category=e.target.dataset.category.toLowerCase(),l(),d()})});c.preco?.addEventListener("input",r=>{i.maxPrice=parseFloat(r.target.value),c.precoVal&&(c.precoVal.textContent=`Até ${i.maxPrice}`),l(),d()});function g(r){const e=o.find(t=>t.id===r);if(e)e.qty+=1;else{const t=s.find(a=>a.id===r);if(t){const a=t.images?.[0]?.image_path?`/${t.images[0].image_path.replace(/^\/+/,"")}`:"/assets/default.jpg";o.push({id:t.id,title:t.title,price:t.price,final_price:t.final_price,img:a,qty:1})}}p(),u(),y()}function u(){if(!c.cartItems)return;c.cartItems.innerHTML=o.length?o.map(t=>{const a=t.final_price&&t.final_price<t.price;return`
      <div class="cart-item" data-id="${t.id}">
        <img src="${t.img}" alt="${t.title}" />
        <div class="meta">
          <strong>${t.title}</strong><br/>
          <small>
            ${a?`<span style="text-decoration: line-through; color: #999;">${n(t.price)}</span> <span style="color: red; font-weight: bold;">${n(t.final_price)}</span>`:n(t.price)}
          </small>
        </div>
        <div class="qty">
          <button class="dec">-</button>
          <span>${t.qty}</span>
          <button class="inc">+</button>
        </div>
      </div>
    `}).join(""):"<p>Seu carrinho está vazio.</p>";const r=o.reduce((t,a)=>t+(a.final_price||a.price)*a.qty,0);let e=r;c.discountArea&&(c.discountArea.style.display="none"),c.subtotal&&(c.subtotal.textContent=n(r)),c.total&&(c.total.textContent=n(e)),$(),document.querySelectorAll(".cart-item .inc").forEach(t=>t.onclick=S),document.querySelectorAll(".cart-item .dec").forEach(t=>t.onclick=L)}function S(r){const e=parseInt(r.target.closest(".cart-item").dataset.id,10);o=o.map(t=>t.id===e?{...t,qty:t.qty+1}:t),p(),u()}function L(r){const e=parseInt(r.target.closest(".cart-item").dataset.id,10);o=o.map(t=>t.id===e?{...t,qty:t.qty-1}:t).filter(t=>t.qty>0),p(),u()}function $(){c.cartCount&&(c.cartCount.textContent=o.reduce((r,e)=>r+(e.qty||0),0))}function y(){c.drawer?.classList.add("open"),c.backdrop?.classList.add("show")}function f(){c.drawer?.classList.remove("open"),c.backdrop?.classList.remove("show")}c.cartBtn?.addEventListener("click",y);c.closeDrawer?.addEventListener("click",f);c.backdrop?.addEventListener("click",f);q();u();
