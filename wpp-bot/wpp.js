const wppconnect = require('@wppconnect-team/wppconnect');

wppconnect.create().then((client) => start(client));

function start(client) {

  
  // Enviar mensagem de teste
 // client.sendText('5581987602846@c.us', '🚀 Conexão funcionando! Mensagem de teste enviada pelo bot.');
  
  // Exemplo para responder automaticamente "ping"
   console.log('Bot rodando...');

  client.onMessage(async (message) => {
    const from = message.from;

    // Encerrar conversa
    const endTriggers = ['sair', 'fim', 'encerrar'];
    if (endTriggers.some(trigger => text.includes(trigger))) {
        await client.sendText(from, 'Conversa encerrada. Sempre que precisar, digite "menu" para voltar.');
        return; // não processa mais nada
    }

    // Se a mensagem for 'menu' ou primeira mensagem
    const triggers = ['menu', 'oi', 'olá', 'inicio', 'start', 'quero ajuda', 'como funciona','bomdia','boa tarde','boa noite'];

    const text = message.body.toLowerCase();
    if (triggers.some(trigger => text.includes(trigger)) || !text) {
          await client.sendText(from, 
        `Olá! 👋
        Eu sou o assistente automático da CAIANA.
        Enquanto nosso atendente não está disponível, você pode escolher uma opção:

        1️⃣ Ver status do pedido
        2️⃣ Consultar produtos
        3️⃣ Falar com atendente
        4️⃣ Outras dúvidas

        Responda apenas com o número da opção.`);
      return;
    }

    // Tratamento de respostas
    switch(message.body.trim()) {
      case '1':
        await client.sendText(from, 'Para ver o status do pedido, por favor envie seu número de pedido.');
        break;
      case '2':
        await client.sendText(from, 'Você pode ver todos os produtos aqui: https://sualoja.com/produtos');
        break;
      case '3':
        await client.sendText(from, 'Um atendente humano entrará em contato assim que possível.');
        break;
      case '4':
        await client.sendText(from, 'Descreva sua dúvida que tentaremos ajudar da melhor forma.');
        break;
      default:
        await client.sendText(from, 'Desculpe, não entendi. Por favor responda com um número de 1 a 4 ou digite "menu" para ver as opções novamente.');
        break;
    }
  });
}
