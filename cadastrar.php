<?php
require_once('class/config.php');
require_once('autoload.php');

//Verificar se existe o POST com todos os dados
if(isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['repete_senha'])) {
    //Receber valores do POST e limpar
    $nome = limparPost($_POST['nome']);
    $email = limparPost($_POST['email']);
    $senha = limparPost($_POST['senha']);
    $repete_senha = limparPost($_POST['repete_senha']);

    //Verificar se valores vindos do POST não estão vazios
    if(empty($nome) or empty($email) or empty($senha) or empty($repete_senha) or empty($_POST['termos'])) {
        $erro_geral = "Todos os campos são obrigatórios";
    } else {
        //Instanciar a classe usuário
        $usuario = new Usuario($nome, $email, $senha);

        //Setar a repetição de senha
        $usuario->set_repeticao($repete_senha);

        //Validar cadastro
        $usuario->validar_cadastro();

        //Se a propriedade erro estiver vazia
        if(empty($usuario->erro)) {
            //Inserir
            if($usuario->insert()) {
                header('location: index.php');
            } else {
                //Deu errado. Erro geral
                $erro_geral = $usuario->erro["erro_geral"];
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/estilo.css" rel="stylesheet">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
    <title>Cadastrar</title>
</head>
<body>
    <form method="post">
        <h1>Cadastrar</h1>

        <?php if(isset($erro_geral)) { ?>
            <div class="erro-geral animate__animated animate__rubberBand">
                <?php echo $erro_geral; ?>
            </div>
        <?php } ?>

        <div class="input-group">
            <img class="input-icon" src="img/card.png">
            <input <?php if(isset($usuario->erro["erro_nome"])){echo 'class="erro-input"';} if(isset($nome)){ echo "value='$nome'";} ?> name="nome" type="text" placeholder="Nome Completo" required>
            <div class="erro"><?php if(isset($usuario->erro["erro_nome"])){echo $usuario->erro["erro_nome"];} ?></div>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/user.png">
            <input <?php if(isset($usuario->erro["erro_email"])){echo 'class="erro-input"';} if(isset($email)){ echo "value='$email'";} ?> type="email" name="email" placeholder="Seu melhor email" required>
            <div class="erro"><?php if(isset($usuario->erro["erro_email"])){echo $usuario->erro["erro_email"];} ?></div>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/lock.png">
            <input <?php if(isset($usuario->erro["erro_senha"])){echo 'class="erro-input"';} if(isset($senha)){ echo "value='$senha'";} ?> type="password" name="senha" placeholder="Senha mínimo 6 Dígitos" required>
            <div class="erro"><?php if(isset($usuario->erro["erro_senha"])){echo $usuario->erro["erro_senha"];} ?></div>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/lock-open.png">
            <input <?php if(isset($usuario->erro["erro_repete_senha"])){echo 'class="erro-input"';} if(isset($repete_senha)){ echo "value='$repete_senha'";} ?> type="password" name="repete_senha" placeholder="Repita a senha criada" required>
            <div class="erro"><?php if(isset($usuario->erro["erro_repete_senha"])){echo $usuario->erro["erro_repete_senha"];} ?></div>
        </div>   
        
        <div class="input-group">
            <input type="checkbox" id="termos" name="termos" value="ok" required>
            <label for="termos">Ao se cadastrar você concorda com a nossa <a class="link" href="#">Política de Privacidade</a> e os <a class="link" href="#">Termos de uso</a></label>
        </div>  
       
        
        <button class="btn-blue" type="submit">Cadastrar</button>
        <a href="index.php">Já tenho uma conta</a>
    </form>
</body>
</html>