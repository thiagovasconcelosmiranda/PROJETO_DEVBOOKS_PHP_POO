<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$postDao = new PostDaoMysql($pdo);

$array = ['error' => ''];
$maxWidth = 800;
$maxHeight = 800;

if (isset($_FILES['photo']) && !empty($_FILES['photo']['tmp_name'])) {
    $photo = $_FILES['photo'];
    if (in_array($photo['type'], ['image/jpeg', 'image/jpg', 'image/png'])) {
        list($widthOrig, $heigthOrig) = getimagesize($photo['tmp_name']);
        $ratio = $widthOrig / $heigthOrig;

        $newWidth = $maxWidth;
        $newHeight = $maxHeight;

        if ($newHeight < $maxHeight) {
            $newWidth = $maxHeight;
            $newWidth = $newHeight * $ratio;
        }

        $finalImage = imagecreatetruecolor($newWidth, $newHeight);
        switch ($photo['type']) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($photo['tmp_name']);
                break;
            case 'image/png':
                $image = imagecreatefrompng($photo['tmp_name']);
        }

        imagecopyresampled(
            $finalImage,
            $image,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $widthOrig,
            $heigthOrig
        );

        $photoName = md5(time() . rand(0, 9999)) . '.jpg';
        $array[] = $photoName;

        imagejpeg($finalImage, 'assets/media/uploads/' . $photoName);

        $newPost = new Post();
        $newPost->id_user = $userInfo->id;
        $newPost->type = 'photo';
        $newPost->created_at = date('Y-m-d H:i:s');
        $newPost->body = $photoName;
       
        $array[] = $postDao->insert($newPost);

    } else {
        $array['error'] = 'Arquivo não suportado (jpg ou png)';
    }
} else {
    $array['error'] = 'Nenhum arquivo encontrado';
}

echo header('Content-Type: application/json');
echo json_encode($array);
exit;