@extends('layouts.app')

@section('title', 'Política de Privacidade • CAIANA')

@section('content')
<section class="privacy-section">
    <div class="container">
        <h1>Política de Privacidade</h1>
        <p>Última atualização: {{ date('d/m/Y') }}</p>

        <p>
            A <strong>CAIANA — Moda Tropical</strong> valoriza a sua privacidade e está comprometida em proteger
            os dados pessoais de nossos clientes e visitantes. Esta Política de Privacidade explica como coletamos,
            usamos e protegemos as informações fornecidas em nosso site.
        </p>

        <h2>1. Coleta de informações</h2>
        <p>
            Coletamos informações pessoais apenas quando necessário, como:
        </p>
        <ul>
            <li>Nome completo;</li>
            <li>Endereço de e-mail;</li>
            <li>Número de telefone;</li>
            <li>Endereço de entrega (em compras);</li>
            <li>Informações de navegação (via cookies ou ferramentas de análise).</li>
        </ul>

        <h2>2. Uso das informações</h2>
        <p>As informações coletadas são utilizadas para:</p>
        <ul>
            <li>Processar e entregar pedidos realizados;</li>
            <li>Melhorar a experiência do usuário no site;</li>
            <li>Enviar comunicações sobre promoções, ofertas e novidades (com consentimento);</li>
            <li>Cumprir obrigações legais e fiscais.</li>
        </ul>

        <h2>3. Compartilhamento de dados</h2>
        <p>
            Não compartilhamos suas informações pessoais com terceiros, exceto quando necessário para a operação do site
            (por exemplo, empresas de pagamento ou entrega), sempre garantindo a proteção dos dados.
        </p>

        <h2>4. Cookies</h2>
        <p>
            Utilizamos cookies para melhorar sua experiência de navegação. Você pode ajustar as preferências do seu navegador
            para recusar cookies, mas algumas partes do site podem não funcionar corretamente.
        </p>

        <h2>5. Segurança</h2>
        <p>
            Implementamos medidas de segurança para proteger suas informações pessoais contra acesso não autorizado,
            alteração, divulgação ou destruição.
        </p>

        <h2>6. Seus direitos</h2>
        <p>
            Você pode solicitar a exclusão ou atualização de seus dados pessoais a qualquer momento, entrando em contato conosco
            pelo e-mail: <a href="mailto:aceciliaragao@gmail.com">aceciliaragao@gmail.com</a>
        </p>

        <h2>7. Alterações nesta política</h2>
        <p>
            Podemos atualizar esta Política de Privacidade periodicamente. Recomendamos que você reveja esta página de tempos
            em tempos para se manter informado.
        </p>

        <p><strong>CAIANA — Moda Tropical</strong><br>
        Recife - PE, Brasil</p>
    </div>
</section>

<style>
.privacy-section {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
    background: rgba(255,255,255,0.9);
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.privacy-section h1 {
    color: #0f172a;
    text-align: center;
    margin-bottom: 1.5rem;
}

.privacy-section h2 {
    margin-top: 1.5rem;
    color: #155e75;
}

.privacy-section p, 
.privacy-section ul {
    color: #333;
    line-height: 1.6;
    font-size: 0.95rem;
}

.privacy-section ul {
    list-style: disc;
    margin-left: 1.5rem;
}
</style>
@endsection
