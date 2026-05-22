<?php

session_start();

if(!isset($_SESSION['user'])){

    header("Location: index.php");
    exit();

}

/* TASK FILE */

$tasksFile = "tasks.json";

if(!file_exists($tasksFile)){

    file_put_contents($tasksFile, json_encode([]));

}

/* GET TASKS */

$tasks = json_decode(file_get_contents($tasksFile), true);

if(!$tasks){

    $tasks = [];

}

/* ADD TASK */

if(isset($_POST['add_task'])){

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $priority = trim($_POST['priority']);

    if(empty($title) || empty($description)){

        die("Fields cannot be empty!");

    }

    $newTask = [

        "id" => time(),

        "title" => $title,

        "description" => $description,

        "date" => $date,

        "priority" => $priority,

        "completed" => false

    ];

    $tasks[] = $newTask;

    file_put_contents(
        $tasksFile,
        json_encode($tasks, JSON_PRETTY_PRINT)
    );

    header("Location: dashboard.php");
    exit();

}

/* COMPLETE TASK */

if(isset($_GET['complete'])){

    $id = $_GET['complete'];

    foreach($tasks as &$task){

        if($task['id'] == $id){

            $task['completed'] = !$task['completed'];

        }

    }

    file_put_contents(
        $tasksFile,
        json_encode($tasks, JSON_PRETTY_PRINT)
    );

    header("Location: dashboard.php");
    exit();

}

/* STATS */

$totalTasks = count($tasks);

$completedTasks = count(
    array_filter(
        $tasks,
        fn($task) =>
        isset($task['completed']) &&
        $task['completed']
    )
);

$progress = $totalTasks > 0
? ($completedTasks / $totalTasks) * 100
: 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Dashboard - TaskFlow</title>

<link rel="icon"
type="image/png"
href="https://cdn-icons-png.flaticon.com/512/9068/9068676.png">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{

    min-height:100vh;

    background:
    linear-gradient(
    -45deg,
    #020617,
    #0f172a,
    #1e3a8a,
    #2563eb
    );

    background-size:400% 400%;

    animation:bg 12s ease infinite;

    color:white;

    padding:30px;

    overflow-x:hidden;

}

@keyframes bg{

0%{
background-position:0% 50%;
}

50%{
background-position:100% 50%;
}

100%{
background-position:0% 50%;
}

}

/* GLOW EFFECT */

body::before{

    content:"";

    position:fixed;

    width:450px;
    height:450px;

    background:#2563eb;

    border-radius:50%;

    top:-120px;
    left:-100px;

    filter:blur(160px);

    opacity:0.4;

    z-index:-1;

}

body::after{

    content:"";

    position:fixed;

    width:400px;
    height:400px;

    background:#3b82f6;

    border-radius:50%;

    bottom:-120px;
    right:-120px;

    filter:blur(160px);

    opacity:0.4;

    z-index:-1;

}

.dashboard{

    display:flex;

    gap:30px;

}

/* SIDEBAR */

.sidebar{

    width:280px;

    background:rgba(255,255,255,0.08);

    backdrop-filter:blur(15px);

    border:1px solid rgba(255,255,255,0.1);

    border-radius:25px;

    padding:30px;

    height:fit-content;

}

.logo{

    text-align:center;

    margin-bottom:40px;

}

.logo img{

    width:80px;

    margin-bottom:10px;

}

.logo h1{

    font-size:40px;

}

.menu a{

    display:flex;

    align-items:center;

    gap:12px;

    text-decoration:none;

    color:white;

    padding:15px;

    border-radius:14px;

    margin-bottom:15px;

    background:rgba(255,255,255,0.05);

    transition:0.3s;

}

.menu a:hover{

    background:#2563eb;

    transform:translateX(5px);

}

/* MAIN */

.main{

    flex:1;

}

/* TOPBAR */

.topbar{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:30px;

}

.topbar h2{

    font-size:38px;

}

.date-box{

    background:rgba(255,255,255,0.08);

    padding:14px 20px;

    border-radius:14px;

    backdrop-filter:blur(10px);

}

/* STATS */

.stats{

    display:grid;

    grid-template-columns:
    repeat(auto-fit,minmax(200px,1fr));

    gap:20px;

    margin-bottom:30px;

}

.stat-card{

    background:rgba(255,255,255,0.08);

    backdrop-filter:blur(15px);

    padding:25px;

    border-radius:20px;

    border:1px solid rgba(255,255,255,0.1);

}

.stat-card h3{

    font-size:18px;

    color:#cbd5e1;

}

.stat-card h1{

    font-size:40px;

    margin-top:10px;

}

/* PROGRESS */

.progress-container{

    margin-top:15px;

}

.progress-bar-bg{

    width:100%;

    height:12px;

    background:rgba(255,255,255,0.1);

    border-radius:20px;

    overflow:hidden;

}

.progress-bar{

    height:100%;

    background:
    linear-gradient(
    to right,
    #3b82f6,
    #60a5fa
    );

    width:<?php echo $progress; ?>%;

}

/* FORM */

.task-form{

    background:rgba(255,255,255,0.08);

    backdrop-filter:blur(15px);

    border:1px solid rgba(255,255,255,0.1);

    border-radius:25px;

    padding:30px;

    margin-bottom:30px;

}

.task-form h2{

    margin-bottom:20px;

}

.task-form input,
.task-form textarea,
.task-form select{

    width:100%;

    padding:15px;

    border:none;

    outline:none;

    border-radius:14px;

    margin-top:15px;

    background:rgba(255,255,255,0.1);

    color:white;

    font-size:16px;

}

.task-form textarea{

    resize:none;

    height:120px;

}

.task-form button{

    width:100%;

    padding:15px;

    margin-top:20px;

    border:none;

    border-radius:14px;

    background:#2563eb;

    color:white;

    font-size:18px;

    font-weight:bold;

    cursor:pointer;

    transition:0.3s;

}

.task-form button:hover{

    background:#1d4ed8;

}

/* TASKS */

.tasks{

    display:flex;

    flex-direction:column;

    gap:20px;

}

.task-card{

    background:rgba(255,255,255,0.08);

    backdrop-filter:blur(15px);

    border:1px solid rgba(255,255,255,0.1);

    border-radius:20px;

    padding:25px;

    display:flex;

    justify-content:space-between;

    align-items:center;

    transition:0.3s;

}

.task-card:hover{

    transform:translateY(-5px);

    box-shadow:
    0 10px 30px rgba(0,0,0,0.3);

}

.task-left{

    display:flex;

    gap:18px;

}

.checkbox{

    width:22px;

    height:22px;

    accent-color:#2563eb;

    margin-top:5px;

    cursor:pointer;

}

.completed{

    text-decoration:line-through;

    opacity:0.6;

}

.task-card h3{

    font-size:24px;

}

.task-card p{

    margin-top:8px;

    color:#cbd5e1;

}

.task-date{

    margin-top:12px;

    color:#94a3b8;

    font-size:14px;

}

.priority{

    display:inline-block;

    padding:8px 14px;

    border-radius:20px;

    margin-top:12px;

    font-size:13px;

    font-weight:bold;

}

.high{
    background:#ef4444;
}

.medium{
    background:#f59e0b;
}

.low{
    background:#22c55e;
}

.task-actions{

    display:flex;

    gap:12px;

}

.btn{

    padding:10px 18px;

    border-radius:12px;

    text-decoration:none;

    color:white;

    font-weight:bold;

    transition:0.3s;

}

.edit{

    background:#2563eb;

}

.delete{

    background:rgba(239,68,68,0.15);

    border:1px solid rgba(239,68,68,0.3);

    color:white;

}

.edit:hover,
.delete:hover{

    transform:scale(1.05);

}

/* RESPONSIVE */

@media(max-width:1000px){

.dashboard{
    flex-direction:column;
}

.sidebar{
    width:100%;
}

.task-card{
    flex-direction:column;
    align-items:flex-start;
    gap:20px;
}

}

</style>

</head>

<body>

<div class="dashboard">

    <!-- SIDEBAR -->

    <div class="sidebar">

        <div class="logo">

            <img src="https://cdn-icons-png.flaticon.com/512/9068/9068676.png">

            <h1>TaskFlow</h1>

        </div>

        <div class="menu">

            <a href="dashboard.php">

                <i class="fa-solid fa-house"></i>

                Dashboard

            </a>

            <a href="tasks.php">

                <i class="fa-solid fa-list-check"></i>

                My Tasks

            </a>

            <a href="logout.php">

                <i class="fa-solid fa-right-from-bracket"></i>

                Logout

            </a>

        </div>

    </div>

    <!-- MAIN -->

    <div class="main">

        <div class="topbar">

            <h2>
                Welcome Back 👋
            </h2>

            <div class="date-box">

                <?php echo date("d M Y"); ?>

            </div>

        </div>

        <!-- STATS -->

        <div class="stats">

            <div class="stat-card">

                <h3>Total Tasks</h3>

                <h1>
                    <?php echo $totalTasks; ?>
                </h1>

            </div>

            <div class="stat-card">

                <h3>Completed</h3>

                <h1>
                    <?php echo $completedTasks; ?>
                </h1>

            </div>

            <div class="stat-card">

                <h3>Progress</h3>

                <h1>
                    <?php echo round($progress); ?>%
                </h1>

                <div class="progress-container">

                    <div class="progress-bar-bg">

                        <div class="progress-bar"></div>

                    </div>

                </div>

            </div>

        </div>

        <!-- ADD TASK -->

        <form class="task-form" method="POST">

            <h2>Add New Task</h2>

            <input
            type="text"
            name="title"
            placeholder="Task Title"
            required>

            <textarea
            name="description"
            placeholder="Task Description"
            required></textarea>

            <input
            type="date"
            name="date">

            <select name="priority">

                <option value="Low">
                    Low Priority
                </option>

                <option value="Medium">
                    Medium Priority
                </option>

                <option value="High">
                    High Priority
                </option>

            </select>

            <button
            type="submit"
            name="add_task">

            Add Task

            </button>

        </form>

        <!-- TASKS -->

        <div class="tasks">

            <?php foreach(array_slice($tasks,0,4) as $task): ?>

            <div class="task-card">

                <div class="task-left">

                    <label>

                        <input
                        type="checkbox"
                        class="checkbox"

                        onclick="location.href='?complete=<?php echo $task['id']; ?>'"

                        <?php if(isset($task['completed']) && $task['completed']) echo "checked"; ?>

                        >

                    </label>

                    <div>

                        <h3 class="<?php if(isset($task['completed']) && $task['completed']) echo 'completed'; ?>">

                            <?php echo htmlspecialchars($task['title']); ?>

                        </h3>

                        <p class="<?php if(isset($task['completed']) && $task['completed']) echo 'completed'; ?>">

                            <?php echo htmlspecialchars($task['description']); ?>

                        </p>

                        <div class="task-date">

                            <i class="fa-regular fa-calendar"></i>

                            <?php echo htmlspecialchars($task['date']); ?>

                        </div>

                        <div class="priority <?php echo strtolower($task['priority']); ?>">

                            <?php echo htmlspecialchars($task['priority']); ?> Priority

                        </div>

                    </div>

                </div>

                <div class="task-actions">

                    <a
                    href="edit_task.php?id=<?php echo $task['id']; ?>"
                    class="btn edit">

                    Edit

                    </a>

                    <a
                    href="delete_task.php?id=<?php echo $task['id']; ?>"
                    class="btn delete">

                    Delete

                    </a>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

    </div>

</div>

</body>
</html>