<?php
require_once(__DIR__ . '/../controller/CategoryController.php');

$router = $_REQUEST['router'] ?? 'index';

switch ($router) {
    case 'index':
        CategoryController::index();
        break;

    case 'createCategory':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CategoryController::store();
        } else {
            header('Location: /src/router/CategoryRouter.php');
            exit;
        }
        break;

    case 'updateCategory':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0) {
                CategoryController::update($id);
            } else {
                echo 'Invalid category ID.';
            }
        } else {
            header('Location: /src/router/CategoryRouter.php');
            exit;
        }
        break;

    case 'deleteCategory':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0) {
                CategoryController::delete($id);
            } else {
                echo 'Invalid category ID.';
            }
        } else {
            header('Location: /src/router/CategoryRouter.php');
            exit;
        }
        break;

    default:
        echo 'Unknown route: ' . htmlspecialchars($router);
        break;
}
?>