<?php
// File to store tasks
$tasksFile = 'tasks.txt';

// Load tasks from the file
$tasks = file_exists($tasksFile) ? unserialize(file_get_contents($tasksFile)) : [];

// Add a new task
if (isset($_POST['add'])) {
    $task = [
        'title' => trim($_POST['task']),
        'category' => $_POST['category'],
        'due_date' => $_POST['due_date'],
        'priority' => $_POST['priority'],
        'completed' => false
    ];
    
    if (!empty($task['title'])) {
        $tasks[] = $task;
        file_put_contents($tasksFile, serialize($tasks));
    }
    
    header("Location: index.php");
    exit;
}

// Mark a task as completed
if (isset($_GET['complete'])) {
    $tasks[$_GET['complete']]['completed'] = true;
    file_put_contents($tasksFile, serialize($tasks));
    header("Location: index.php");
    exit;
}

// Delete a task
if (isset($_GET['delete'])) {
    array_splice($tasks, $_GET['delete'], 1);
    file_put_contents($tasksFile, serialize($tasks));
    header("Location: index.php");
    exit;
}

// Edit a task
if (isset($_POST['edit'])) {
    $index = $_POST['task_index'];
    $tasks[$index]['title'] = trim($_POST['task']);
    $tasks[$index]['category'] = $_POST['category'];
    $tasks[$index]['due_date'] = $_POST['due_date'];
    $tasks[$index]['priority'] = $_POST['priority'];
    
    file_put_contents($tasksFile, serialize($tasks));
    header("Location: index.php");
    exit;
}

// Calculate progress
$totalTasks = count($tasks);
$completedTasks = count(array_filter($tasks, fn($task) => $task['completed']));
$progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced PHP To-Do List</title>
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
    <h1 style="font-family: 'Poppins', sans-serif; 
           font-size: 3em; 
           color: #ff6347; 
           background: linear-gradient(45deg, #ff6347, #ff4500, #ff8c00); 
           -webkit-background-clip: text; 
           color: transparent; 
           text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5); 
           text-align: center;">
    To-Do List
</h1>

        <div class="progress-bar">
            <div class="progress" style="width: <?= $progress ?>%;"></div>
        </div>
        <form method="POST" class="task-form">
            <input type="text" name="task" placeholder="Enter a new task" required>
            <select name="category">
                <option value="Work">Work</option>
                <option value="Personal">Personal</option>
                <option value="Other">Other</option>
            </select>
            <input type="date" name="due_date" placeholder="Due Date">
            <select name="priority">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
            <button type="submit" name="add">
                <img src="assets/icons/add.png" alt="Add Task">
            </button>
        </form>
        <ul class="task-list">
            <?php foreach ($tasks as $index => $task): ?>
                <li class="task-card <?= $task['completed'] ? 'completed' : '' ?>">
                    <div class="task-details">
                        <span class="task-title"><?= htmlspecialchars($task['title']) ?></span>
                        <span class="task-category"><?= htmlspecialchars($task['category']) ?></span>
                        <span class="task-due-date">Due: <?= htmlspecialchars($task['due_date']) ?></span>
                        <span class="task-priority priority-<?= strtolower($task['priority']) ?>">
                            <?= htmlspecialchars($task['priority']) ?> Priority
                        </span>
                    </div>
                    <div class="actions">
                        <?php if (!$task['completed']): ?>
                            <a href="?complete=<?= $index ?>" class="complete-btn">
                                <img src="assets/icons/check.png" alt="Complete">
                            </a>
                        <?php endif; ?>
                        <a href="?delete=<?= $index ?>" class="delete-btn">
                            <img src="assets/icons/delete.png" alt="Delete">
                        </a>
                        <button type="button" class="edit-btn" data-index="<?= $index ?>">
                            <img src="assets/icons/edit.png" alt="Edit">
                        </button>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Edit Task Modal -->
        <div id="edit-modal" class="modal">
            <div class="modal-content">
                <h2>Edit Task</h2>
                <form method="POST" class="edit-form">
                    <input type="hidden" name="task_index" id="task-index">
                    <input type="text" name="task" id="edit-task" placeholder="Edit task" required>
                    <select name="category" id="edit-category">
                        <option value="Work">Work</option>
                        <option value="Personal">Personal</option>
                        <option value="Other">Other</option>
                    </select>
                    <input type="date" name="due_date" id="edit-due-date" placeholder="Due Date">
                    <select name="priority" id="edit-priority">
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                    <button type="submit" name="edit">Update Task</button>
                    <button type="button" class="close-modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
