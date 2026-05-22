<?php

session_start();

$tasksFile = "tasks.json";

if(!file_exists($tasksFile)){
    file_put_contents($tasksFile, json_encode([]));
}

$tasks = json_decode(file_get_contents($tasksFile), true);

if(!$tasks){
    $tasks = [];
}

/* TOGGLE COMPLETE */

if(isset($_GET['complete'])){

    $id = $_GET['complete'];

    foreach($tasks as &$task){

        if($task['id'] == $id){

            if(isset($task['completed'])){

                $task['completed'] = !$task['completed'];

            } else {

                $task['completed'] = true;

            }

        }
    }

    file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));

    header("Location: tasks.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Tasks - TaskFlow</title>
<link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/9068/9068676.png">

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
    background:linear-gradient(135deg,#020617,#0f172a,#1e3a8a);
    color:white;
    padding:40px;
    overflow-x:hidden;
    position:relative;
}

/* ANIMATED BACKGROUND */

body::before{
    content:"";
    position:absolute;
    width:500px;
    height:500px;
    background:#2563eb;
    filter:blur(180px);
    opacity:0.3;
    border-radius:50%;
    top:-100px;
    left:-100px;
    animation:move1 8s infinite alternate;
}

body::after{
    content:"";
    position:absolute;
    width:400px;
    height:400px;
    background:#3b82f6;
    filter:blur(180px);
    opacity:0.3;
    border-radius:50%;
    bottom:-100px;
    right:-100px;
    animation:move2 10s infinite alternate;
}

@keyframes move1{
    from{
        transform:translate(0,0);
    }
    to{
        transform:translate(120px,80px);
    }
}

@keyframes move2{
    from{
        transform:translate(0,0);
    }
    to{
        transform:translate(-120px,-80px);
    }
}

.container{
    max-width:1200px;
    margin:auto;
    position:relative;
    z-index:2;
}

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:40px;
}

.logo{
    display:flex;
    align-items:center;
    gap:15px;
}

.logo img{
    width:75px;
}

.logo h1{
    font-size:55px;
    font-weight:bold;
}

.actions{
    display:flex;
    gap:15px;
}

.btn{
    padding:14px 24px;
    border-radius:14px;
    text-decoration:none;
    color:white;
    font-weight:bold;
    transition:0.3s;
}

.dashboard-btn{
    background:#2563eb;
}

.dashboard-btn:hover{
    background:#1d4ed8;
    transform:translateY(-3px);
}

.logout-btn{
    background:#ef4444;
}

.logout-btn:hover{
    background:#dc2626;
    transform:translateY(-3px);
}

.task-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:20px;
    margin-bottom:35px;
}

.progress-box{
    flex:1;
    height:14px;
    background:rgba(255,255,255,0.1);
    border-radius:20px;
    overflow:hidden;
}

.progress-bar{
    width:65%;
    height:100%;
    background:linear-gradient(to right,#3b82f6,#60a5fa);
    border-radius:20px;
}

.task-header h2{
    font-size:50px;
    font-weight:bold;
}

.task-count{
    background:rgba(255,255,255,0.08);
    padding:15px 22px;
    border-radius:16px;
    backdrop-filter:blur(12px);
    font-size:20px;
}

.tasks-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(350px,1fr));
    gap:30px;
}

.task-card{
    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(15px);
    border-radius:24px;
    padding:28px;
    transition:0.3s ease;
    border:1px solid rgba(255,255,255,0.05);
}

.task-card:hover{
    transform:translateY(-8px);
    box-shadow:0 20px 40px rgba(0,0,0,0.4);
}

.task-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.checkbox{
    width:24px;
    height:24px;
    accent-color:#2563eb;
    cursor:pointer;
}

.status{
    padding:9px 16px;
    border-radius:30px;
    font-size:14px;
    font-weight:bold;
}

.pending{
    background:#f59e0b;
    color:white;
}

.completed-status{
    background:#10b981;
    color:white;
}

.task-title{
    font-size:34px;
    margin-bottom:12px;
    font-weight:bold;
}

.task-description{
    color:#cbd5e1;
    line-height:1.7;
    margin-bottom:20px;
    font-size:17px;
}

.completed-text{
    text-decoration:line-through;
    opacity:0.6;
}

.date{
    color:#94a3b8;
    font-size:15px;
    margin-bottom:25px;
}

.task-actions{
    display:flex;
    gap:15px;
}

.edit-btn,
.delete-btn{
    flex:1;
    padding:14px;
    text-align:center;
    border-radius:14px;
    text-decoration:none;
    font-weight:bold;
    transition:0.3s;
}

.edit-btn{
    background:#2563eb;
    color:white;
}

.edit-btn:hover{
    background:#1d4ed8;
    transform:scale(1.03);
}

.delete-btn{
    background:rgba(239,68,68,0.15);
    border:1px solid rgba(239,68,68,0.3);
    color:white;
}

.delete-btn:hover{
    background:#dc2626;
    transform:scale(1.03);
}

.empty{
    text-align:center;
    padding:100px 20px;
    background:rgba(255,255,255,0.08);
    border-radius:25px;
    backdrop-filter:blur(10px);
}

.empty i{
    font-size:80px;
    margin-bottom:20px;
    color:#60a5fa;
}

@media(max-width:900px){

    .topbar{
        flex-direction:column;
        gap:20px;
    }

    .task-header{
        flex-direction:column;
        align-items:flex-start;
    }

    .progress-box{
        width:100%;
    }

}

</style>

</head>

<body>

<div class="container">

    <div class="topbar">

        <div class="logo">

            <img src="https://cdn-icons-png.flaticon.com/512/9068/9068676.png">

            <h1>TaskFlow</h1>

        </div>

        <div class="actions">

            <a href="dashboard.php" class="btn dashboard-btn">
                Dashboard
            </a>

            <a href="logout.php" class="btn logout-btn">
                Logout
            </a>

        </div>

    </div>

    <div class="task-header">

        <div class="progress-box">
            <div class="progress-bar"></div>
        </div>

        <h2>My Tasks</h2>

        <div class="task-count">

            Total Tasks:
            <?php echo count($tasks); ?>

        </div>

    </div>

    <?php if(count($tasks) > 0): ?>

    <div class="tasks-grid">

        <?php foreach($tasks as $task): ?>

        <div class="task-card">

            <div class="task-top">

                <a href="?complete=<?php echo $task['id']; ?>">

                    <input
                    type="checkbox"
                    class="checkbox"
                    <?php if(isset($task['completed']) && $task['completed']) echo "checked"; ?>
                    >

                </a>

                <?php if(isset($task['completed']) && $task['completed']): ?>

                    <div class="status completed-status">
                        Completed
                    </div>

                <?php else: ?>

                    <div class="status pending">
                        Pending
                    </div>

                <?php endif; ?>

            </div>

            <h3 class="task-title <?php if(isset($task['completed']) && $task['completed']) echo 'completed-text'; ?>">

                <?php echo $task['title']; ?>

            </h3>

            <p class="task-description <?php if(isset($task['completed']) && $task['completed']) echo 'completed-text'; ?>">

                <?php echo $task['description']; ?>

            </p>

            <p class="date">
                <i class="fa-regular fa-calendar"></i>
                22 May 2026
            </p>

            <div class="task-actions">

                <a
                href="edit_task.php?id=<?php echo $task['id']; ?>"
                class="edit-btn">
                Edit
                </a>

                <a
                href="delete_task.php?id=<?php echo $task['id']; ?>"
                class="delete-btn">
                Delete
                </a>

            </div>

        </div>

        <?php endforeach; ?>

    </div>

    <?php else: ?>

    <div class="empty">

        <i class="fa-solid fa-list-check"></i>

        <h2>No Tasks Yet</h2>

    </div>

    <?php endif; ?>

</div>

</body>
</html>