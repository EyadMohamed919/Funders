<?php
require_once(__DIR__ . '/../controller/PostController.php');
$router = $_REQUEST['router'] ?? 'index';
switch ($router) {
    case 'index':
        PostController::index();
        break;

    case 'createPost':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            PostController::store();
        } else {
            header('Location: /src/router/PostRouter.php');
            exit;
        }
        break;

    case 'updatePost':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0) {
                PostController::update($id);
            } else {
                echo 'Invalid post ID.';
            }
        } else {
            header('Location: /src/router/PostRouter.php');
            exit;
        }
        break;

    case 'deletePost':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0) {
                PostController::delete($id);
            } else {
                echo 'Invalid post ID.';
            }
        } else {
            header('Location: /src/router/PostRouter.php');
            exit;
        }
        break;

    default:
        echo 'Unknown route: ' . htmlspecialchars($router);
        break;
}
?>