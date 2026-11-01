# MIGRACAO.md — site-caiana → VPS (Nginx + PHP 8.3-FPM + PostgreSQL 18)

Branch `migracao-vps`, a partir de `origin/main` (`61acf0e`). Atualizado em 2026-11-01.

Legenda: 🔴 bloqueia deploy · 🟠 corrigir logo · 🔵 melhoria · ✅ resolvido nesta branch

---

## 1. O que mudou (um commit por item)

| Commit | O quê |
|---|---|
| `7653a33` | **Sincroniza com a versão de produção.** Código do `public_html` por cima do GitHub. Os backups da hospedagem (`buildbk*`, `build(1)`, `storagebkp/`, arquivos `(1)…(5)`, zip) ficaram de fora via `.git/info/exclude`. README mantido o do GitHub. |
| `360743a` | **Estrutura padrão (raiz web = `public/`).** `assets/`, `js/`, `favicon.*` e `robots.txt` movidos/mesclados em `public/` (os duplicados eram idênticos); removidos `index.php`, `.htaccess` e `build/` da raiz; upload grava em `public_path('assets')`. **As mudanças feitas direto no bundle de produção** (ViaCEP, cupom, promoções, endereço, logout, CSS) foram portadas para `resources/js/app1.js` e `resources/css/styles.css`. O `npm run build` gera CSS idêntico ao de produção. |
| `74a0f99` | **PostgreSQL.** `where('active', 1)` → `true` (a página `/promocoes` quebrava); `promotional_price` `decimal(5,2)` → `(10,2)`; `DB_CONNECTION` padrão `pgsql` (config + `.env.example`, porta 5432). |
| `391bc76` | **Telescope.** Não está instalado (fora do `composer.json`/`vendor`, provider nunca registrado), então nunca esteve acessível. Removidos o provider, o config e a migration; nova migration apaga as tabelas antigas se existirem. |
| `453e449` | **AdminSeeder** idempotente (`config/admin.php` ← `ADMIN_NAME/EMAIL/PASSWORD`, `firstOrCreate`, sem Faker). Chamado pelo `DatabaseSeeder`. |
| `06ead27` | **DemoSeeder** (só explícito, em transação, sem Faker, idempotente): catálogo real (4 categorias, 9 produtos, imagens), cupons `BEMVINDO10` e `CAIANA15`, promoção "Semana Brasilidade" com datas a partir de hoje, 8 clientes fictícios com endereço e 15 pedidos nos últimos 5 meses. |
| `688078d` | **vite.config.js:** o bloco `build` customizado mandava o manifest para `public/build/.vite/`, então toda página dava 500 com "Vite manifest not found". |
| `3556d8f` | **Painel protegido** — ver seção 3. |
| `599b0b1` | **Ids de cliente** + listener + client do admin — ver seção 3. |
| `f03e625` | **Escalada de privilégio** — ver seção 3. |
| `c7c672c` | **Upload** — ver seção 3. |
| `3ac8b8a` | **Cupom e checkout** — ver seção 3. |
| `d8e0dfd` | **Throttle no login** e `max:` no cadastro. |
| `85df545` | **WhatsApp/Instagram via config** (`WHATSAPP_NUMBER`, `INSTAGRAM_URL`). A faixa "Loja demonstrativa" adicionada aqui foi retirada depois, a pedido. |
| `b5cd6e2` | `wpp-bot/` no `.gitignore` (a branch não tinha nada do bot). |

### Decisões que vale saber

- **Variantes (tamanho/cor):** o model não as exige e a loja não as usa (carrinho e estoque são por produto), então o DemoSeeder não cria variantes.
- **CPF:** a tabela `clients` não tem coluna de CPF, então não há onde gravar.
- **Clientes demo** têm senha aleatória (não dá para entrar com eles). Para testar como cliente, cadastre-se pelo site.
- **Pedidos demo** não alteram o estoque: o `stock_quantity` do seed já é o estoque "atual".

---

## 2. Testes

Feitos em Docker, já que o PHP local é 7.4: **PHP 8.3.36 (CLI) + pdo_pgsql, PostgreSQL 18, Node 24.22**, com `php artisan serve`.

| Testado | Resultado |
|---|---|
| `migrate:fresh` em banco vazio | ✅ 23 migrations |
| `db:seed` + `db:seed --class=DemoSeeder` rodados 2× | ✅ contagens iguais (1 admin, 8 clientes, 9 produtos, 15 pedidos, 2 cupons, 1 promoção) |
| `npm ci && npm run build` | ✅ |
| Vitrine, `/produtos`, `/promocoes`, `/api/products`, as 9 imagens | ✅ 200; preço promocional aplicado |
| Cadastro, login, endereço, cupom, checkout, `/sucesso` com link wa.me | ✅ |
| Cliente em `/admin/*` e `/dashboard` | ✅ 403 · visitante → 302 para `/login` |
| Admin: dashboard, produtos, upload, pedidos, troca de status (baixa estoque) | ✅ |
| Segurança (seção 3) | ✅ cada caso no corpo do commit |

**Não testado:**
- **Nginx + PHP-FPM** (só `artisan serve`). Conferir na VPS o bloco Nginx da seção 4 e o `php.ini`.
- **JavaScript no navegador:** filtro por categoria, gaveta do carrinho, ViaCEP e mensagens do cupom. O servidor e o bundle foram verificados, mas o JS em si não rodou num browser.
- **E-mail** de "esqueci a senha" (depende do SMTP do `.env`).
- Layout em celular.

---

## 3. Segurança

### Resolvido nesta branch ✅

| Achado | Correção |
|---|---|
| 🔴 Qualquer cliente virava admin editando o próprio usuário (`role=admin`) | `role` fora do `$fillable`; só admin altera role; admin não se rebaixa |
| 🔴 `POST /test/orders/{order}/status` **sem auth** (mudava status e estoque) e `/test-status` | Removidos; troca de status em `POST /admin/orders/{order}/status` |
| 🔴 `admin/*` e `/dashboard` só com `auth` (cliente lia/apagava contatos, via painel etc.); `Kernel.php` não carregado no Laravel 12, alias `admin` inexistente | Alias em `bootstrap/app.php`, `['auth','admin']` nas 132 rotas admin e no dashboard; `Kernel.php` apagado |
| 🟠 CRUD inteiro duplicado fora de `/admin` | Removido; ficaram só as rotas de cliente |
| 🔴 Admin sem registro em `clients` → **500 em todas as páginas** (`MergeCartAfterLogin`) | Listener ignora quem não tem client; AdminSeeder cria o client |
| 🟠 Upload salvava com a extensão enviada pelo cliente | `hashName()` (extensão pelo conteúdo), `mimes:jpg,jpeg,png,webp`, `max:5120` |
| 🟠 `image_path` livre + `unlink(public_path(...))` → apagar arquivo arbitrário (`../.env`) | Regex `assets/<arquivo>` e exclusão só dentro de `public/assets` |
| 🟠 Cupom: total vindo do cliente (pedido de R$ 0), `max_use` baixava a cada clique, rota pública | Total calculado no servidor; uso contado só no checkout (transação + lock); exige login; código em maiúsculas |
| 🟠 `POST /coupons/validate` consultava coluna inexistente | Removida |
| 🟠 Checkout aceitava `qty` negativa/fracionada e ids quaisquer | `items.*.id exists`, `items.*.qty integer 1..99`, só produtos ativos |
| 🟠 `users.id` usado onde a FK é `clients.id` (pedidos, endereços, avaliações, contato) | `$user->client?->id` |
| 🟠 Login sem rate limit | `throttle:5,1` |
| 🟠 `routes/web.php` com espaços antes de `<?php` (redirects saíam 200 sem output buffering) | Removidos |

**Correção do diagnóstico anterior:** eu tinha apontado RCE por upload de `x.php`. Não procede: o Laravel já recusa nomes com extensão PHP (`shouldBlockPhpUpload`). O risco real era um PNG válido chamado `x.html`/`x.svg` ser salvo com essa extensão (XSS armazenado). Isso foi resolvido pelo `hashName()`.

### Ainda pendente

| Achado | Gravidade | O que fazer |
|---|---|---|
| Servidor antigo servia a raiz do projeto: `.env` e `storage/logs/laravel.log` provavelmente acessíveis pela web | 🔴 | Se ainda estiver no ar, tirar do ar e **trocar senha do banco, do SMTP e o `APP_KEY`** que estavam lá |
| `APP_DEBUG` | 🔴 | `APP_DEBUG=false` e `APP_ENV=production` na VPS |
| Histórico público do GitHub com `wpp-bot/tokens/session` (commits `dd34b42`, `0c935c7`) | 🟠 | Desconectar os aparelhos no WhatsApp. Opcional: `git filter-repo --path wpp-bot --invert-paths` + force push (reescreve o `main`) |
| `innerHTML` com título de produto em `resources/js/app1.js` | 🔵 | XSS só por quem é admin; escapar texto |
| `UsersController@destroy` protege ids 1 e 2 fixos | 🔵 | Trocar por regra de papel |
| Mensagens de validação parcialmente em inglês (`The items.0.qty field…`) | 🔵 | Completar `resources/lang/pt` / `APP_LOCALE=pt_BR` |

### Bugs anteriores encontrados (não corrigidos)

- `/admin/order_items` → 500 (a view `admin/order_items/index` nunca existiu; nada aponta para ela).
- `ProductsController@show` usa a view `products.show`, que não existe.
- `ContactController@update` grava a coluna `respondido`, que não existe.
- Trocar a foto de um produto demo pelo admin **apaga o arquivo original** em `public/assets`. Para restaurar: `git checkout -- public/assets` e rodar o DemoSeeder de novo.

---

## 4. Deploy na VPS

```bash
git clone -b migracao-vps <repo> /var/www/site-caiana && cd /var/www/site-caiana
composer install --no-dev --optimize-autoloader
npm ci && npm run build
cp .env.example .env   # preencher: APP_URL, APP_ENV=production, APP_DEBUG=false,
                       # DB_*, MAIL_*, ADMIN_*, WHATSAPP_NUMBER, INSTAGRAM_URL
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force                      # admin
php artisan db:seed --class=DemoSeeder --force   # catálogo e pedidos demo
php artisan storage:link
php artisan config:cache && php artisan route:cache && php artisan view:cache
chown -R www-data:www-data storage bootstrap/cache public/assets
```

`php.ini` do FPM (upload de até 5 MB): `upload_max_filesize = 6M`, `post_max_size = 8M`.

```nginx
root /var/www/site-caiana/public;
index index.php;
client_max_body_size 8m;
location / { try_files $uri $uri/ /index.php?$query_string; }
location = /index.php {
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    fastcgi_pass unix:/run/php/php8.3-fpm.sock;
}
location ~ \.php$ { return 404; }           # nenhum outro .php executa
location ~ /\.(?!well-known) { deny all; }
```
