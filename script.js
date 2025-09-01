document.getElementById('cadastroForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const nome = document.getElementById('nome').value;
  const email = document.getElementById('email').value;

  fetch('process.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `nome=${encodeURIComponent(nome)}&email=${encodeURIComponent(email)}`
  })
  .then(response => response.text())
  .then(data => {
    document.getElementById('mensagem').innerText = data;
    document.getElementById('cadastroForm').reset();
  })
  .catch(error => {
    document.getElementById('mensagem').innerText = 'Erro ao enviar o formulário.';
  });
});
