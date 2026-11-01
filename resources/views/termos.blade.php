@extends('layouts.app')

@section('title', 'Termos de Uso • CAIANA')

@section('content')
<section class="privacy-section">
    <div class="container">
        <h1>Termos de Uso</h1>
        <p>Última atualização: {{ date('d/m/Y') }}</p>

        <p>
            Bem-vindo(a) ao site <strong>CAIANA — Moda Tropical</strong>. Ao acessar e utilizar este site, você concorda com os termos e condições descritos abaixo. Se não concordar, por favor, não utilize nosso site.
        </p>

        <h2>1. Uso do site</h2>
        <p>
            O site <strong>CAIANA — Moda Tropical</strong> é destinado à compra de produtos e serviços oferecidos pela nossa loja.  
            Ao utilizar o site, você se compromete a fornecer informações corretas e verdadeiras durante o cadastro e compras.  
            É proibido utilizar o site para fins ilegais, fraudulentos ou que violem direitos de terceiros.
        </p>

        <h2>2. Cadastro e Conta</h2>
        <p>
            Alguns serviços exigem cadastro. Você é responsável por manter seus dados de acesso (login e senha) em sigilo.  
            Você concorda em notificar imediatamente a loja sobre qualquer uso não autorizado de sua conta.
        </p>

        <h2>3. Compras e Pagamentos</h2>
        <p>
            Todas as compras estão sujeitas à disponibilidade de estoque.  
            Os preços e condições podem ser alterados sem aviso prévio.  
            O pagamento deve ser realizado pelos meios disponíveis no site.  
            Cancelamentos e devoluções seguem a nossa <a href="{{ route('politica') }}">Política de Trocas e Devoluções</a>.
        </p>

        <h2>4. Propriedade Intelectual</h2>
        <p>
            Todo o conteúdo do site — incluindo imagens, textos, logotipos e design — é protegido por direitos autorais.  
            É proibida a reprodução, distribuição ou uso comercial sem autorização expressa da CAIANA.
        </p>

        <h2>5. Limitação de Responsabilidade</h2>
        <p>
            A loja não se responsabiliza por danos diretos ou indiretos decorrentes do uso do site.  
            Não nos responsabilizamos por falhas em serviços de terceiros, como meios de pagamento, transportadoras ou provedores externos.
        </p>

        <h2>6. Privacidade e Cookies</h2>
        <p>
            O uso do site implica concordância com a nossa <a href="{{ route('politica') }}">Política de Privacidade</a>, que explica como coletamos e usamos seus dados.  
            Utilizamos cookies para melhorar a experiência do usuário, mas você pode gerenciar ou recusar cookies no banner de aviso.
        </p>

        <h2>7. Alterações nos Termos</h2>
        <p>
            Reservamo-nos o direito de atualizar estes Termos de Uso a qualquer momento.  
            As alterações entrarão em vigor imediatamente após a publicação no site.  
            Recomendamos que você revise os Termos periodicamente.
        </p>

        <h2>8. Legislação e Foro</h2>
        <p>
            Estes Termos de Uso são regidos pelas leis brasileiras.  
            Em caso de disputa, fica eleito o foro da cidade de Recife - PE, renunciando a qualquer outro.
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
