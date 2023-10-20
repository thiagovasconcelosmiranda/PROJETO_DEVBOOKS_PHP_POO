<?php 
require 'config.php';
require 'models/Auth.php';
require 'dao/PostDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'perfil';
 
$id = filter_input(INPUT_GET, 'id');
if(!$id){
   $id = $userInfo->id;
}

$postDao = new PostDaoMysql($pdo);
$feed = $postDao->getHomeFeed($id);
$userDao = new UserDaoMysql($pdo);

$user = $userDao->findById($id, true);
if(!$user){
   header("Location: ".$base);
   exit;
}

$dateFrom = new DateTime($user->birthdate);
$dateTo = new DateTime('today');
$user->ageYers = $dateFrom->diff($dateTo)->y;

//Pegar informações do usuario


//Pegar o feed do usuário

//verificar se eu sigo o usuario

 require 'partials/header.php';
 require 'partials/menu.php';
 ?>
<section class="feed">
 <div class="row">
    <div class="box flex-1 border-top-flat">
        <div class="box-body">
            <div class="profile-cover" style="background-image: url('<?=$base;?>/media/covers/<?=$user->cover;?>');"></div>
            <div class="profile-info m-20 row">
                <a href="<?=$base;?>/perfil.php?id=<?=$user->id;?>">
                 <div class="profile-info-avatar">
                    <img src="<?=$base;?>/media/avatars/<?=$user->avatar;?>" />
                 </div>
                </a>
                <div class="profile-info-name">
                    <div class="profile-info-name-text"><?=$user->name;?></div>
                    <?php if(!empty($user->city)):?>
                    <div class="profile-info-location"><?=$user->city;?></div>
                    <?php endif; ?>
                </div>
                <div class="profile-info-data row">
                    <div class="profile-info-item m-width-20">
                        <div class="profile-info-item-n"><?=count($user->followers); ?></div>
                        <div class="profile-info-item-s">Seguidores</div>
                    </div>
                    <div class="profile-info-item m-width-20">
                        <div class="profile-info-item-n"><?=count($user->following);?></div>
                        <div class="profile-info-item-s">Seguindo</div>
                    </div>
                    <div class="profile-info-item m-width-20">
                        <div class="profile-info-item-n"><?=count($user->photos);?></div>
                        <div class="profile-info-item-s">Fotos</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="column side pr-5">
        
        <div class="box">
            <div class="box-body">
                
                <div class="user-info-mini">
                    <img src="<?=$base;?>/assets/images/calendar.png" />
                    <?=date('d/m/Y', strtotime($user->birthdate));?> (<?=$user->ageYers;?> anos)
                </div>

                <?php if(!empty($user->city)): ?>
                 <div class="user-info-mini">
                    <img src="<?=$base;?>/assets/images/pin.png" />
                    <?=$user->city;?>, Brasil
                 </div>
                <?php endif;?>

                <?php if(!empty($user->work)): ?>
                  <div class="user-info-mini">
                    <img src="<?=$base;?>/assets/images/work.png" />
                    <?=$user->work;?>
                  </div>
                <?php endif;?>
            </div>
        </div>

        <div class="box">
            <div class="box-header m-10">
                <div class="box-header-text">
                    Seguindo
                    <span>(<?=count($user->following);?>)</span>
                </div>
               
                <div class="box-header-buttons">
                    <a href="<?=$base;?>/amigos.php?id=<?=$user->id;?>">ver todos</a>
                </div>
        
            </div>
            <div class="box-body friend-list">

              <?php if(count($user->following) > 0):?>
                <?php foreach($user->following as $item):?>
                  <div class="friend-icon">
                     <a href="<?=$base;?>/perfil.php?id=<?=$user->id;?>">
                        <div class="friend-icon-avatar">
    
                            <img src="<?=$base;?>/media/avatars/<?=$user->avatar?>" />
                        </div>
                        <div class="friend-icon-name">
                           <?=$user->name;?>
                        </div>
                     </a>
                   </div>
                  <?php endforeach;?>
               <?php endif;?>
            </div>
        </div>

    </div>
    <div class="column pl-5">
       <?php  foreach($feed as $item):?>
          <?php require 'partials/feed-item.php';?>
        <?php  endforeach;?>
    </div>
</section>
<?php
  require 'partials/footer.php';
?>
