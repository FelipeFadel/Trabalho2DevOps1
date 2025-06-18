<?php
// Inclui o arquivo de configuração com a conexão
require_once "config.php";

// Define variáveis e inicializa com valores vazios
$nome = $preco = $imagem = $quantidade = "";
$nome_err = $preco_err = $imagem_err = $quantidade_err = "";

// Processa os dados do formulário quando ele for enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validação do nome
    $input_nome = trim($_POST["nome"]);
    if (empty($input_nome)) {
        $nome_err = "Por favor, insira um nome.";
    } else {
        $nome = $input_nome;
    }

    // Validação do preço
    $input_preco = trim($_POST["preco"]);
    if (empty($input_preco) || !is_numeric($input_preco)) {
        $preco_err = "Por favor, insira um preço válido.";
    } else {
        $preco = $input_preco;
    }

    // Validação da imagem
    $input_imagem = trim($_POST["imagem"]);
    if (empty($input_imagem)) {
        $imagem_err = "Por favor, insira a URL da imagem.";
    } else {
        $imagem = $input_imagem;
    }

    // Validação da quantidade
    $input_quantidade = trim($_POST["quantidade"]);
    if (empty($input_quantidade) || !ctype_digit($input_quantidade)) {
        $quantidade_err = "Por favor, insira um número inteiro válido.";
    } else {
        $quantidade = $input_quantidade;
    }

    // Verifica se não há erros antes de inserir no banco
    if (empty($nome_err) && empty($preco_err) && empty($imagem_err) && empty($quantidade_err)) {
        try {
            // Insere o produto
            $sql = "INSERT INTO produtos (nome, preco, imagem_url, quantidade) VALUES (:nome, :preco, :imagem, :quantidade)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":preco", $preco);
            $stmt->bindParam(":imagem", $imagem);
            $stmt->bindParam(":quantidade", $quantidade, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Registra o log
                $detalhes_log = "Produto adicionado: Nome='$nome', Preço='$preco', Imagem='$imagem', Quantidade='$quantidade'";
                $acao_log = "Criação de Produto";

                $log_sql = "INSERT INTO logs (acao, detalhes) VALUES (:acao, :detalhes)";
                $log_stmt = $pdo->prepare($log_sql);
                $log_stmt->bindParam(":acao", $acao_log);
                $log_stmt->bindParam(":detalhes", $detalhes_log);
                $log_stmt->execute();

                header("location: index.php");
                exit();
            } else {
                echo "Erro ao inserir o produto.";
            }
        } catch (PDOException $e) {
            echo "Erro: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Produto - Hadio FX</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #111;
            font-family: 'Oswald', sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .form-container {
            background-color: #1e1e1e;
            padding: 30px;
            max-width: 500px;
            margin: 50px auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 255, 134, 0.2);
        }

        h2 {
            color: #00e676;
            font-size: 2em;
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            text-align: center;
            margin-bottom: 30px;
            color: #ccc;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            background-color: #222;
            border: 1px solid #444;
            color: #fff;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .error-message {
            color: #ff6b6b;
            font-size: 0.9em;
            margin-top: -8px;
            margin-bottom: 10px;
        }

        .btn-form {
            background-color: #00e676;
            color: #111;
            padding: 10px 20px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .btn-form:hover {
            background-color: #00c853;
        }

        .btn-cancelar {
            background-color: transparent;
            border: 1px solid #888;
            color: #ccc;
	    padding-top: 5px;
	}

        .btn-cancelar:hover {
            border-color: #00e676;
            color: #00e676;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Adicionar Produto</h2>
        <p>Preencha o formulário para adicionar um novo pedal</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label>Nome</label>
            <input type="text" name="nome" value="<?php echo $nome; ?>">
            <?php if (!empty($nome_err)) echo "<div class='error-message'>$nome_err</div>"; ?>

            <label>Preço</label>
            <input type="text" name="preco" value="<?php echo $preco; ?>">
            <?php if (!empty($preco_err)) echo "<div class='error-message'>$preco_err</div>"; ?>

            <label>Imagem (URL)</label>
            <input type="text" name="imagem" value="<?php echo $imagem; ?>">
            <?php if (!empty($imagem_err)) echo "<div class='error-message'>$imagem_err</div>"; ?>

            <label>Quantidade</label>
            <input type="text" name="quantidade" value="<?php echo $quantidade; ?>">
            <?php if (!empty($quantidade_err)) echo "<div class='error-message'>$quantidade_err</div>"; ?>

            <input type="submit" class="btn-form" value="Salvar">
            <a href="index.php" class="btn-form btn-cancelar">Cancelar</a>
        </form>
    </div>
    <div style="justify-self:center;">
	<img style="width:100px;" src="https://i.imgur.com/aYE18Cq.png" alt="Hadio FX logo" />
    </div>
</body>
</html>
