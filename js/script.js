const formCadastro = document.getElementById('form-cadastro');
const formLogin = document.getElementById('form-login');

if (formCadastro) {
    document.getElementById('form-cadastro').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('backend.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'ok') {
            alert("Cadastro realizado com sucesso!");
            this.reset();
            window.location.href = 'login.html';
            } else {
            alert("Erro: " + data.mensagem);
            }
        })
        .catch(err => alert("Erro ao enviar: " + err));
        });
}

if (formLogin) {
    formLogin.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData();
    formData.append('email', document.getElementById('login-email').value);
    formData.append('password', document.getElementById('login-password').value);
    formData.append('action', 'login'); // usamos isso no PHP para saber que é login

    fetch('backend.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'ok') {
        // Redirecionar para index.php após login
        window.location.href = 'index.html';
      } else {
        alert("Erro: " + data.mensagem);
      }
    })
    .catch(err => alert("Erro no login: " + err));
  });
}


