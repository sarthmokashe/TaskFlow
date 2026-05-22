<?php

$tasksFile = "tasks.json";

$tasks = json_decode(file_get_contents($tasksFile), true);

$id = $_GET['id'];

foreach($tasks as $key => $task){

    if($task['id'] == $id){

        $currentTask = $task;
        $currentKey = $key;
    }
}

if(isset($_POST['update_task'])){

    $tasks[$currentKey]['title'] = $_POST['title'];
    $tasks[$currentKey]['description'] = $_POST['description'];

    file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));

    header("Location: dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Task</title>

<style>

body{
    font-family:Arial;
    background:linear-gradient(135deg,#0f172a,#1e3a8a);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    margin:0;
}

.container{
    width:500px;
    background:rgba(255,255,255,0.08);
    padding:30px;
    border-radius:20px;
    backdrop-filter:blur(10px);
    color:white;
}

h1{
    margin-bottom:20px;
}

input, textarea{
    width:100%;
    padding:15px;
    margin-top:15px;
    border:none;
    border-radius:10px;
    background:rgba(255,255,255,0.1);
    color:white;
    font-size:16px;
}

textarea{
    height:120px;
    resize:none;
}

button{
    width:100%;
    padding:15px;
    margin-top:20px;
    border:none;
    border-radius:10px;
    background:#2563eb;
    color:white;
    font-size:18px;
    font-weight:bold;
    cursor:pointer;
}

</style>

</head>
<body>

<div class="container">

    <h1>Edit Task</h1>

    <form method="POST">

        <input
        type="text"
        name="title"
        value="<?php echo $currentTask['title']; ?>"
        required>

        <textarea
        name="description"
        required><?php echo $currentTask['description']; ?></textarea>

        <button type="submit" name="update_task">
            Update Task
        </button>

    </form>

</div>

</body>
</html>
