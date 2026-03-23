<?php 


require_once __DIR__ . "/../../controller/PostController.php";
$posts = PostController::getAllPosts();

for ($i=0; $i < count($posts); $i++) { 

    // $json = file_get_contents("https://picsum.photos/200/300");
    // var_dump($json);
    // $data = json_decode($json);
    // $imageUrl = $data->message;

    $percent = ($posts[$i]->getCurrentAmount() / $posts[$i]->getTargetAmount()) * 100;


    echo "<div class='post-card'>";
        echo "<div class='post-image' style='background-image:url(https://picsum.photos/1000/1200)'></div>";
        echo "<h3>" . $posts[$i]->getTitle() . "</h3>";
        if(strlen($posts[$i]->getDescription()) > 50)
        {
            echo "<p>" . substr($posts[$i]->getDescription(), 0, 47) . "..." . "</p>";
        }
        else
        {
            echo "<p>" . $posts[$i]->getDescription() . "</p>";
        }
        
        echo "<div class='post-bar-container'>";
            echo "<h3>" . round($percent) . "% Reached</h3>";
            echo "<h4>" . $posts[$i]->getCurrentAmount() . "EGP/";
            echo $posts[$i]->getTargetAmount() . "EGP</h4>";
        echo "</div>";
        echo "<div class='post-bar'><div class='post-bar-overlay' style='width:" . $percent . "%'></div></div>";

    echo "</div>";
}

?>