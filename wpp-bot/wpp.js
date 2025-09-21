const wppconnect = require('@wppconnect-team/wppconnect');
const fs = require('fs');
const path = require('path');

// Nome da sessão padrão
const SESSION_NAME = 'caiana-bot';
const SESSION_PATH = `wppconnect-session/${SESSION_NAME}`;

// Comando passado via linha de comando
const command = process.argv[2];
const targetNumber = process.argv[3]; // para delete <numero>

switch (command) {
    case 'start':
        startBot();
        break;
    case 'stop':
        stopBot();
        break;
    case 'reset':
        resetSession();
        break;
    case 'delete':
        if (!targetNumber) {
            console.log('❌ Informe o número para deletar a sessão (ex: delete 5581999999999)');
            process.exit(1);
        }
        deleteSessionNumber(targetNumber);
        break;
    default:
        console.log('Comandos disponíveis: start | stop | reset | delete <numero>');
        process.exit(0);
}

// ===========================
// Funções
// ===========================

function startBot() {
    wppconnect.create({
        session: SESSION_NAME,
        puppeteerOptions: {
            headless: true,
            args: ['--no-sandbox', '--disable-setuid-sandbox']
        }
    }).then(client => {
        console.log('✅ Bot iniciado com sucesso!');
        startChat(client);
    }).catch(err => {
        console.error('❌ Erro ao iniciar o bot:', err);
    });
}

function stopBot() {
    wppconnect.create({ session: SESSION_NAME })
        .then(async (client) => {
            console.log('⚠️ Encerrando a sessão...');
            await client.logout();
            await client.close();
            console.log('✅ Sessão encerrada!');
        }).catch(err => {
            console.error('❌ Erro ao encerrar sessão:', err);
        });
}

function resetSession() {
    if (fs.existsSync(SESSION_PATH)) {
        fs.rmSync(SESSION_PATH, { recursive: true, force: true });
        console.log('🗑️ Sessão resetada com sucesso!');
    } else {
        console.log('⚠️ Nenhuma sessão encontrada para resetar.');
    }
}

function deleteSessionNumber(number) {
    const numberPath = path.join(SESSION_PATH, `tokens/${number}`);
    if (fs.existsSync(numberPath)) {
        fs.rmSync(numberPath, { recursive: true, force: true });
        console.log(`🗑️ Sessão do número ${number} excluída com sucesso!`);
    } else {
        console.log(`⚠️ Sessão do número ${number} não encontrada.`);
    }
}

// ===========================
// Chatbot
// ===========================
function startChat(client) {
    const atendimentoHumano = {};

    client.onMessage(async (message) => {
        const from = message.from;
        const text = (message.body || '').toLowerCase().trim();

        if (message.isGroupMsg) return; // ignora grupos
        if (atendimentoHumano[from]) return; // humano assumiu

        // Encerrar conversa
        const endTriggers = ['sair', 'fim', 'encerrar'];
        if (endTriggers.some(trigger => text.includes(trigger))) {
            await client.sendText(from, '✅ Conversa encerrada. Digite "menu" para voltar.');
            return;
        }

        // Menu
        const menuTriggers = ['menu', 'oi', 'olá', 'inicio', 'start', 'quero ajuda', 'bom dia', 'boa tarde', 'boa noite'];
        if (menuTriggers.some(trigger => text.includes(trigger)) || text === '') {
            await client.sendText(from,
`Olá! 👋
Escolha uma opção:

1️⃣ Status do pedido
2️⃣ Consultar produtos
3️⃣ Falar com atendente
4️⃣ Outras dúvidas`);
            return;
        }

        // Respostas
        switch (text) {
            case '1':
                await client.sendText(from, '📦 Para ver o status do pedido, envie seu número de pedido...');
                break;
            case '2':
                await client.sendText(from, '🛍️ Veja os produtos: https://sualoja.com/produtos');
                break;
            case '3':
                atendimentoHumano[from] = true;
                await client.sendText(from, '👩‍💼 Um atendente humano assumiu este chat.');
                break;
            case '4':
                await client.sendText(from, '✍️ Por favor, descreva sua dúvida.');
                break;
            default:
                await client.sendText(from, '❌ Não entendi. Responda com 1 a 4 ou "menu".');
                break;
        }
    });
}
