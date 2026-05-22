<?php

if(isset($_GET['id'])){

    $id = $_GET['id'];

    $tasks = json_decode(file_get_contents("tasks.json"), true);

    $newTasks = [];

    foreach($tasks as $task){

        if($task['id'] != $id){
            $newTasks[] = $task;
        }
    }

    file_put_contents("tasks.json", json_encode($newTasks, JSON_PRETTY_PRINT));

}

header("Location: dashboard.php");

?>