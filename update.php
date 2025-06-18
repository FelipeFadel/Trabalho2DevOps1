<?php
// Inclui o arquivo de configuração (com PDO)
require_once "config.php";

// Define as variáveis
$nome = $preco = $imagem = $quantidade = "";
$nome_err = $preco_err = $imagem_err = $quantidade_err = "";

// Processa o formulário quando for enviado via POST
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    $id = $_POST["id"];

    // Validação do nome
    $input_nome = trim($_POST["nome"]);
    if (empty($input_nome)) {
        $nome_err = "Por favor insira o nome do produto.";
    } else {
        $nome = $input_nome;
    }

    // Validação do preço
    $input_preco = trim($_POST["preco"]);
    if (empty($input_preco)) {
        $preco_err = "Por favor insira o preço.";
    } elseif (!is_numeric($input_preco)) {
        $preco_err = "O preço deve ser um número.";
    } else {
        $preco = $input_preco;
    }

    // Validação da imagem
    $input_imagem = trim($_POST["imagem"]);
    if (empty($input_imagem)) {
        $imagem_err = "Por favor insira a URL da imagem.";
    } else {
        $imagem = $input_imagem;
    }

    // Validação da quantidade
    $input_quantidade = trim($_POST["quantidade"]);
    if (empty($input_quantidade)) {
        $quantidade_err = "Por favor insira a quantidade.";
    } elseif (!ctype_digit($input_quantidade)) {
        $quantidade_err = "A quantidade deve ser um número inteiro positivo.";
    } else {
        $quantidade = $input_quantidade;
    }

    // Se não houver erros, faz o UPDATE
    if (empty($nome_err) && empty($preco_err) && empty($imagem_err) && empty($quantidade_err)) {
        $sql = "UPDATE produtos SET nome = :nome, preco = :preco, imagem_url = :imagem, quantidade = :quantidade WHERE id = :id";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":preco", $preco);
            $stmt->bindParam(":imagem", $imagem);
            $stmt->bindParam(":quantidade", $quantidade, PDO::PARAM_INT);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                echo "Algo deu errado. Tente novamente.";
            }
        } catch (PDOException $e) {
            echo "Erro no banco de dados: " . $e->getMessage();
        }
    }
} else {
    // Se o formulário ainda não foi enviado, carrega os dados existentes (GET)
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        $id = trim($_GET["id"]);

        $sql = "SELECT * FROM produtos WHERE id = :id";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    $nome = $row["nome"];
                    $preco = $row["preco"];
                    $imagem = $row["imagem_url"];
                    $quantidade = $row["quantidade"];
                } else {
                    header("Location: error.php");
                    exit();
                }
            } else {
                echo "Algo deu errado ao carregar os dados.";
            }
        } catch (PDOException $e) {
            echo "Erro no banco de dados: " . $e->getMessage();
        }
    } else {
        header("Location: error.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Atualizar Produto - Hadio FX</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #111;
            color: #fff;
            font-family: 'Oswald', sans-serif;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            background-color: #1e1e1e;
            width: 600px;
            margin: 60px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 255, 134, 0.3);
        }

        h2 {
            text-align: center;
            color: #00e676;
            margin-bottom: 20px;
        }

        p {
            text-align: center;
            margin-bottom: 30px;
            color: #ccc;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #ccc;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            background-color: #2b2b2b;
            border: 1px solid #444;
            border-radius: 5px;
            color: #fff;
        }

        .form-control.is-invalid {
            border-color: #ff1744;
        }

        .invalid-feedback {
            color: #ff5252;
        }

        .btn {
            padding: 10px 20px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #00e676;
            color: #111;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #00c853;
        }

        .btn-secondary {
            background-color: #444;
            color: #fff;
            margin-left: 10px;
        }

        .btn-secondary:hover {
            background-color: #666;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <h2>Atualizar Produto</h2>
        <p>Edite os campos abaixo para atualizar o produto.</p>
        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
            <div class="form-group">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control <?php echo (!empty($nome_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nome; ?>">
                <span class="invalid-feedback"><?php echo $nome_err;?></span>
            </div>
            <div class="form-group">
                <label>Preço</label>
                <input type="text" name="preco" class="form-control <?php echo (!empty($preco_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $preco; ?>">
                <span class="invalid-feedback"><?php echo $preco_err;?></span>
            </div>
            <div class="form-group">
                <label>Imagem (URL)</label>
                <input type="text" name="imagem" class="form-control <?php echo (!empty($imagem_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $imagem; ?>">
                <span class="invalid-feedback"><?php echo $imagem_err;?></span>
            </div>
            <div class="form-group">
                <label>Quantidade</label>
                <input type="text" name="quantidade" class="form-control <?php echo (!empty($quantidade_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $quantidade; ?>">
                <span class="invalid-feedback"><?php echo $quantidade_err;?></span>
            </div>
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <input type="submit" class="btn btn-primary" value="Salvar">
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
    <div style="justify-self:center;">
        <img style="width:100px;" src="https://i.imgur.com/aYE18Cq.png" alt="Hadio FX logo" />
    </div>

</body>
</html>

