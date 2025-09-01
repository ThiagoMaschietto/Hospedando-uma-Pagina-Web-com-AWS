const form = document.getElementById('cadastroForm');
const mensagem = document.getElementById('mensagem');
const listaUsuarios = document.getElementById('listaUsuarios');

// Função para carregar lista de usuários
// JS: script.js (função modificada)

function carregarUsuarios() {
  fetch('process.php')
    .then(response => response.json())
    .then(data => {
      // VERIFICAÇÃO ADICIONADA: Checa se a resposta tem a propriedade "error"
      if (data.error) {
        listaUsuarios.innerHTML = `<p>Erro vindo do servidor: ${data.error}</p>`;
        return;
      }

      if (data.length === 0) {
        listaUsuarios.innerHTML = '<p>Nenhum usuário cadastrado.</p>';
        return;
      }

      let tabela = '<table><thead><tr><th>ID</th><th>Nome</th><th>Email</th></tr></thead><tbody>';
      data.forEach(usuario => {
        tabela += `<tr><td>${usuario.id}</td><td>${usuario.nome}</td><td>${usuario.email}</td></tr>`;
      });
      tabela += '</tbody></table>';

      listaUsuarios.innerHTML = tabela;
    })
    .catch(() => {
      // Este catch agora pegará erros de rede ou JSON inválido
      listaUsuarios.innerHTML = '<p>Erro de comunicação ao carregar usuários.</p>';
    });
}

// Ao enviar o formulário
form.addEventListener('submit', function(e) {
  e.preventDefault();

  const nome = document.getElementById('nome').value;
  const email = document.getElementById('email').value;

  fetch('process.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `nome=${encodeURIComponent(nome)}&email=${encodeURIComponent(email)}`
  })
  .then(response => response.text())
  .then(msg => {
    mensagem.innerText = msg;
    form.reset();
    carregarUsuarios();  // Atualiza lista depois do cadastro
  })
  .catch(() => {
    mensagem.innerText = 'Erro ao enviar o formulário.';
  });
});

// Carrega lista ao abrir a página
window.onload = carregarUsuarios;
