<?php
// Inclui a configuração do banco (com a conexão PDO em $pdo)
require_once "config.php";

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Valida o ID recebido
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    if ($id !== false && $id !== null) {
        // Prepara a instrução DELETE
        $sql = "DELETE FROM produtos WHERE id = :id";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Exclusão feita com sucesso, redireciona
                header("Location: index.php");
                exit();
            } else {
                echo "Erro: Não foi possível excluir o produto.";
            }
        } catch (PDOException $e) {
            echo "Erro no banco de dados: " . $e->getMessage();
        }
    } else {
        echo "ID inválido.";
    }
} else {
    // Caso a requisição seja GET (exibir o formulário de confirmação)
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

    if ($id === false || $id === null) {
        // Sem ID válido no GET, redireciona para página de erro
        header("Location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Produto - Hadio FX</title>
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
            margin: 80px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(255, 64, 64, 0.3);
        }

        h2 {
            text-align: center;
            color: #ff5252;
            margin-bottom: 20px;
        }

        .alert {
            background-color: #2b2b2b;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #ff1744;
            text-align: center;
        }

        .alert p {
            color: #ccc;
        }

        .btn {
            padding: 10px 20px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        .btn-danger {
            background-color: #ff5252;
            color: #111;
        }

        .btn-danger:hover {
            background-color: #ff1744;
        }

        .btn-secondary {
            background-color: #444;
            color: #fff;
        }

        .btn-secondary:hover {
            background-color: #666;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <h2>Excluir Produto</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="alert">
                <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                <p>Tem certeza que deseja excluir este produto?</p>
                <p>
                    <input type="submit" value="Sim" class="btn btn-danger">
                    <a href="index.php" class="btn btn-secondary">Não</a>
                </p>
            </div>
        </form>
    </div>
</div>
    <div style="justify-self:center;">
        <img style="width:100px;" src="https://i.imgur.com/aYE18Cq.png" alt="Hadio FX logo" />
    </div>
</body>
</html>

