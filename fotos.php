<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';
require 'partials/modal-photo.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'fotos';
$user = [];
$feed = [];

$id = filter_input(INPUT_GET, 'id');
if (!$id) {
  $id = $userInfo->id;
}

if ($id != $userInfo->id) {
  $activeMenu = '';
}

$postDao = new PostDaoMysql($pdo);
$feed = $postDao->getHomeFeed($id);
$userDao = new UserDaoMysql($pdo);

$user = $userDao->findById($id, true);
if (!$user) {
  header("Location: " . $base);
  exit;
}

$dateFrom = new DateTime($user->birthdate);
$dateTo = new DateTime('today');
$user->ageYers = $dateFrom->diff($dateTo)->y;


//Pegar o feed do usuário
$feed = $postDao->getUserfeed($id);




require 'partials/header.php';
require 'partials/menu.php';
?>
<section class="feed">
    <div class="row">
        <div class="box flex-1 border-top-flat">
            <div class="box-body">
                <div class="profile-cover"
                    style="background-image: url('<?= $base; ?>/assets/media/covers/<?= $user->cover; ?>');">
                </div>
                <div class="profile-info m-20 row">
                    <a href="<?= $base; ?>/perfil.php?id=<?= $user->id; ?>">
                        <div class="profile-info-avatar">
                            <img src="<?= $base; ?>/assets/media/avatars/<?= $user->avatar; ?>" />
                        </div>
                    </a>
                    <div class="profile-info-name">
                        <div class="profile-info-name-text"><?= $user->name; ?></div>
                        <?php if (!empty($user->city)): ?>
                        <div class="profile-info-location"><?= $user->city; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="profile-info-data row">
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?= count($user->followers); ?></div>
                            <div class="profile-info-item-s">Seguidores</div>
                        </div>
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?= count($user->following); ?></div>
                            <div class="profile-info-item-s">Seguindo</div>
                        </div>
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?= count($user->photos); ?></div>
                            <div class="profile-info-item-s">Fotos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="column">
            <div class="box">
                <div class="box-body">
                    <div class="full-user-photos">
                        <?php foreach ($user->photos as $key => $item): ?>
                        <div class="user-photo-item">
                            <img src="<?= $base; ?>/assets/media/uploads/<?= $item->body; ?>" />
                        </div>
                        <?php endforeach; ?>

                        <?php if (count($user->photos) === 0): ?>
                        Não há fotos deste usuário
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
Modal.templates = {
    header: '<div class="modal-header foo"></div>',
};
document.querySelector('.js-static-modal-toggle-main')
    .addEventListener('click', function() {
        new Modal({
            el: document.getElementById('static-modal')
        }).show();
    });
document.querySelector('.js-static-modal-toggle')
    .addEventListener('click', function() {
        new Modal({
            el: document.getElementById('static-modal')
        }).show();
    });

document.querySelector('.js-dynamic-modal-toggle')
    .addEventListener('click', function() {

        // Here we create our dynamic modal
        new Modal({
            title: 'Hooray!',
            content: 'My Very Dynamic Modal Content'
        }).show();

    });

document.querySelector('.js-alert-modal-toggle')
    .addEventListener('click', function() {

        // Here we create our dynamic modal
        Modal.alert('My Custom Alert').show();

    });

document.querySelector('.js-confirm-modal-toggle')
    .addEventListener('click', function() {

        // Here we create our dynamic modal
        var cfrm = Modal.confirm('Are you sure?');
        cfrm.on('hide', function() {
            alert('Triggered hide event.');
        });
        cfrm.on('hidden', function() {
            alert('Modal is hidden.');
        });
        cfrm.show();

    });

document.querySelector('.js-confirm-event-modal-toggle')
    .addEventListener('click', function() {

        // Here we create our dynamic modal
        var confirmModal = Modal.confirm('Are You Sure?');
        confirmModal.show().once('dismiss', function(modal, ev, button) {
            if (button && button.value) {
                alert("You've clicked on an OK button.");
            }
        });

    });
</script>
<?php
require 'partials/footer.php';
?>