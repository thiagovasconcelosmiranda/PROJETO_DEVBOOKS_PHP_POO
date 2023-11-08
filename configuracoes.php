<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/UserDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$activeMenu = 'configuracao';

$userDao = new UserDaoMysql($pdo);

require 'partials/header.php';
require 'partials/menu.php';
?>

<section class="feed mt-10">
     <h1>Configurações</h1>
     <?php if(!empty($_SESSION['flash'])):?>
        <?=$_SESSION['flash']= '';?>
     <?php endif;?>

     <form method="POST"  autocomplete="off" class="config-form" enctype="multipart/form-data" action="configuracoes_action.php">
         <input type="hidden" name="id_user" value="<?=$userInfo->id;?>">
     <label>
            Novo Avatar:<br/>
            <input type="file" name="avatar"/><br/>
            <img class="mini" src="<?=$base;?>/assets/media/avatars/<?=$userInfo->avatar;?>">
        </label>
        <label>
            Novo Capa:<br/>
            <input type="file" name="cover"/></br/>
            <img class="mini" src="<?=$base;?>/assets/media/covers/<?=$userInfo->cover;?>">
        </label>
       <hr>
        <label>
            Nome Completo:<br/>
            <input type="text" value="<?=$userInfo->name;?>" name="name"/>
        </label>

        <label>
            Email:<br/>
            <input type="email" value="<?=$userInfo->email;?>" name="email"/>
        </label>

        <label>
            Data de Nascimento:<br/>
            <input type="text" id="birthdate-config" value="<?=date( 'd/m/Y', strtotime($userInfo->birthdate));?>" name="birthdate"/>
        </label>
        <label>
            Cidade:<br/>
            <input type="text" value="<?=$userInfo->city;?>" name="city"/>
            <label>
            Trabalho:<br/>
            <input type="text" value="<?=$userInfo->work;?>" name="work"/>
        </label>
        </label>
        <hr>
        <label>
            Nova Senha:<br/>
            <input type="password"  name="password"/>
        </label>
       
        <label>
            Confirmar Senha:<br/>
            <input type="password"  name="password_confirmation"/>
        </label>
        <button class="button" type="submit">Salvar</button>

     </form>
</session>
<?php
  require 'partials/footer.php';
?>
 <script src="https://unpkg.com/imask"></script>
    <script>
      IMask(
        document.getElementById('birthdate-config'),
        {mask: '00/00/0000'}
      );
    </script>
