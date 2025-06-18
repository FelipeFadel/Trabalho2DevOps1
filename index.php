<?php
require_once "config.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Hadio FX - Catálogo</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet" />
</head>
<body>
  <nav class="navbar">
    <div class="nav-left">
      <a href="#inicio" class="nav-link">Inicio</a>
      <a href="#produtos" class="nav-link">Produtos</a>
      <a href="#contato" class="nav-link">Contato</a>
    </div>
    <div class="nav-right">
      <a href="#" class="nav-link" onclick="abrirModal('modalConta')">Sua conta</a>
      <a href="#" class="nav-link" onclick="abrirModal('modalCarrinho')">Carrinho</a>
    </div>
  </nav>

  <header id="inicio">
    <div class="contCenter">
      <div class="logo">
        <img src="https://i.imgur.com/aYE18Cq.png" alt="Hadio FX logo" />
      </div>
      <h1>BEM-VINDO À HADIO FX</h1>
      <p>Explore os melhores efeitos e pedais para sua guitarra.</p>
    </div>
  </header>

  <section id="produtos">
    <h2>PRODUTOS</h2>
    <p>Aqui você encontrará nossos pedais, acessórios e novidades da nossa linha.</p>

    <div class="produtos-container">
      <?php
      try {
          $sql = "SELECT * FROM produtos";
          $stmt = $pdo->query($sql);

          if ($stmt->rowCount() > 0) {
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo '<div class="produto-card" data-id="' . htmlspecialchars($row["id"]) . '">';
                  echo '<img src="' . htmlspecialchars($row["imagem_url"]) . '" alt="' . htmlspecialchars($row["nome"]) . '" />';
                  echo '<h3>' . htmlspecialchars($row["nome"]) . '</h3>';
                  echo '<p>R$ ' . number_format($row["preco"], 2, ',', '.') . '</p>';
                  echo '<div class="crud-links">';
                  echo '<a href="read.php?id=' . $row["id"] . '">Ver</a> | ';
                  echo '<a href="update.php?id=' . $row["id"] . '">Editar</a> | ';
                  echo '<a href="delete.php?id=' . $row["id"] . '">Excluir</a>';
                  echo '</div>';
                  echo '</div>';
              }
          } else {
              echo '<p>Nenhum produto encontrado no catálogo.</p>';
          }
      } catch (PDOException $e) {
          echo '<p>Erro ao carregar os produtos: ' . htmlspecialchars($e->getMessage()) . '</p>';
      }
      ?>
    </div>

    <div style="text-align: center; margin-top: 20px;">
      <a href="create.php" class="btn-crud">Adicionar Novo Produto</a>
    </div>
  </section>

  <section id="contato">
    <h2>Contato</h2>
    <p>Entre em contato conosco para pedidos especiais ou dúvidas técnicas.</p>
    <form action="contato.php" method="POST">
      <label for="nome">Nome:</label><br />
      <input type="text" id="nome" name="nome" required /><br /><br />

      <label for="email">Email:</label><br />
      <input type="email" id="email" name="email" required /><br /><br />

      <label for="mensagem">Mensagem:</label><br />
      <textarea id="mensagem" name="mensagem" rows="5" required></textarea><br /><br />

      <button type="submit">Enviar</button>
    </form>
    <img src="https://i.imgur.com/aYE18Cq.png" alt="Hadio FX logo" class="logofinal" />
  </section>

  <!-- Modais -->
  <div id="modalConta" class="modal">
    <div class="modal-content">
      <span class="close" onclick="fecharModal('modalConta')">&times;</span>
      <h2>Login</h2>
      <p>Entre na sua conta para continuar</p>
      <input type="text" id="usuario" placeholder="Usuário" />
      <input type="password" id="senha" placeholder="Senha" />
      <button onclick="fazerLogin()">Entrar</button>
    </div>
  </div>

  <div id="modalCarrinho" class="modal">
    <div class="modal-content">
      <span class="close" onclick="fecharModal('modalCarrinho')">&times;</span>
      <h2>Carrinho</h2>
      <div id="listaCarrinho"></div>
      <p id="totalCarrinho">Total: R$ 0,00</p>
      <button onclick="finalizarPedido()">Finalizar Pedido</button>
    </div>
  </div>

  <!-- SCRIPT -->
  <script>
    function abrirModal(id) {
      document.getElementById(id).style.display = "block";
    }

    function fecharModal(id) {
      document.getElementById(id).style.display = "none";
    }

    function fazerLogin() {
      const user = document.getElementById("usuario").value;
      const pass = document.getElementById("senha").value;
      alert("Login simulado: " + user);
    }

    const carrinho = [];
    function adicionarAoCarrinho(id) {
      carrinho.push(id);
      atualizarCarrinho();
    }

    function atualizarCarrinho() {
      const lista = document.getElementById("listaCarrinho");
      lista.innerHTML = carrinho.map(id => `<p>Produto ID: ${id}</p>`).join('');
      document.getElementById("totalCarrinho").innerText = "Total: R$ " + (carrinho.length * 350).toFixed(2).replace(".", ",");
    }

    function finalizarPedido() {
      alert("Pedido finalizado!");
    }
  </script>
</body>
</html>

