<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/progress.php';
require_once __DIR__ . '/includes/dashboard.php';

$pdo = getDatabaseConnection();

$today = date('Y-m-d');
$dates = buildDateRange($today, 14);
$startDate = $dates[0];

$caloriesPerDay = fillMissingDays($dates, getCaloriesPerDay($pdo, $startDate, $today));
$workoutsPerDay = fillMissingDays($dates, getWorkoutsPerDay($pdo, $startDate, $today));
$xpTotals = runningTotal(fillMissingDays($dates, getXpPerDay($pdo, $startDate, $today)), getXpBefore($pdo, $startDate));

$totalXp = getTotalXP($pdo);
$streak = calculateStreak(getWorkoutDates($pdo), $today);
$labels=[];
foreach($dates as $date){
    $day=new DateTimeImmutable($date);
    $labels[]=$day->format('j M');

}
?>


<!DOCTYPE html>
<html>
<html lang="en">
<head>
 <meta name="viewport" content="width=device-width,initial-scale=1">
 <meta charset="UTF-8">
 <title>Food - FitBeyond50</title>

 <style>
 body{
  margin:0;
  background-colour:#D3D3D3;
  font-family:sans-serif;
 }
 .topnav{
 background-color:white;
 width:100%;
 display:flex;
 justify-content:center;
 position:sticky;
 top:0;
 z-index:100;
}
.topnav a{
 color:black;
 text-align:center;
 padding: 16px 24px;
 text-decoration:none;
 font-size:18px;
 font-family:sans-serif;
 border-bottom:3px solid transparent;
}
.topnav a :hover{
 background-color:#ddd;
 color:white;
}

.topnav a.active{
 background-color: white;
 color:Black;
 border-bottom:3px solid black;
}

.main-title{
 color:Black;
 font-family:sans-serif;
 font-size:400%;
 text-align:center;
 margin-top:40px;
 margin-bottom:10px;
}

.dashboard-content{
 max-width:800px;
 margin:0 auto;
 padding:48px 20px 20px 20px;
 text-align:center;
}
.stats-container{
 display:flex;
 justify-content:center;
 gap:100px;
 color:Black;
 margin-top:20px;
 font-size:160%;
}

:root{
    --surface:#fff;
    --border:#d4d9e5;
    --accent:#2563eb;

}
body{
    font-family:"Inter",sans-serif;
    background:#D3D3D3;
    padding:0;
    margin:0;
}
.container{
    display:flex;
    justify-content:center;
    gap:20px;
    max-width:800px;
    margin:20px auto;
}
.card1{
    width:50%;
    margin:0 ;
    padding:16px 28px;
    border:2px solid var(--border);
    border-radius:18px;
    background:var(--surface);
    box-shadow:0 14px 28px rgba(15,23,42,0.08);
}
.card1 p{
    margin:0;
    line-height:1.6;
    font-size:0.85rem;
}
.card2{
    width:50%;
    margin:0 ;
    padding:16px 28px;
    border:2px solid var(--border);
    border-radius:18px;
    background:var(--surface);
    box-shadow:0 14px 28px rgba(15,23,42,0.08);
}
.card2 p{
    margin:0;
    line-height:1.6;
    font-size:0.85rem;
}
.bottom-box{
    max-width:800px;
    margin:20px auto;
    padding:20px 28px;
    border:2px solid var(--border);
    border-radius:18px;
    background:var(--surface);
    box-shadow:0 14px 28px rgba(15,23,42,0.08);
}
h1{
    font-size:20px;
    font-family:"Inter",sans-serif
}
.button{
    border:none;
    color:white;
    padding:15px 32px;
    text-align:center;
    text-decoration:none;
    display:inline-block;
    font-size:16px;
    margin:4px 2px;
    cursor:pointer;
}
.button1{background-color: black;}

.button2{background-color:black}

.button3{
    background-color: black;
    text-align:center;
    }
.center-wrapper{
    text-align:center;
    margin:30px 0;
}
</style>

</head>
<link rel="stylesheet"
href="styles.css"
type="text/css">

 <body>
  <div class="topnav">
     <body>
  <div class="topnav">
   <a href="index.php">Home</a>
   <a href="workout.php">Workout</a>
   <a href="food.php">Food</a>
   <a class="active" href="progress.php">Progress</a>
  </div>
<h1 class="main-title">Progress</h1>

<div class="dashboard-content">
    <p>Total XP: <?= $totalXp ?> · Streak: <?= $streak ?> days</p>

    <div class="bottom-box">
    <h2>Calories per day</h2>
    <canvas id="caloriesChart"></canvas>
</div>

<div class="bottom-box">
    <h2>Workouts completed</h2>
    <canvas id="workoutsChart"></canvas>
</div>

<div class="bottom-box">
    <h2>XP progress</h2>
    <canvas id="xpChart"></canvas>
</div>

    <table>
        <tr><th>Date</th><th>Calories</th><th>Workouts</th><th>Total XP</th></tr>
        <?php for ($i = 0; $i < count($dates); $i++): ?>
            <tr>
                <td><?= $dates[$i] ?></td>
                <td><?= number_format($caloriesPerDay[$i]) ?></td>
                <td><?= $workoutsPerDay[$i] ?></td>
                <td><?= $xpTotals[$i] ?></td>
            </tr>
        <?php endfor; ?>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Data from PHP, turned into JavaScript lists
    const labels = <?= json_encode($labels) ?>;
    const calories = <?= json_encode($caloriesPerDay) ?>;
    const workouts = <?= json_encode($workoutsPerDay) ?>;
    const xpTotals = <?= json_encode($xpTotals) ?>;

    // Graph 1: calories per day (line graph)
    new Chart(document.getElementById('caloriesChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{ label: 'Calories', data: calories }]
        },
        options: {
            scales: {
                x: { title: { display: true, text: 'Date' } },
                y: { beginAtZero: true, title: { display: true, text: 'Calories' } }
            }
        }
    });

    // Graph 2: workouts per day (YOU WRITE: copy Graph 1 and change it)

    // Graph 3: XP over time (YOU WRITE)

    // Graph 1: calories per day (line graph)
    new Chart(document.getElementById('workoutsChart'), {        // ← 1. canvas id
        type: 'bar',                                             // ← 2. type
        data: {
            labels: labels,
            datasets: [{ label: 'Workouts', data: workouts }]     // ← 3. label   4. data
        },
        options: {
            scales: {
                x: { title: { display: true, text: 'Date' } },
                y: { beginAtZero: true, title: { display: true, text: 'Workouts completed' } }   // ← 5. y-axis title
            }
        }
    });

        // Graph 1: calories per day (line graph)
    new Chart(document.getElementById('xpChart'), {        // ← 1. canvas id
        type: 'line',                                             // ← 2. type
        data: {
            labels: labels,
            datasets: [{ label: 'Total XP', data: xpTotals }]     // ← 3. label   4. data
        },
        options: {
            scales: {
                x: { title: { display: true, text: 'Date' } },
                y: { beginAtZero: true, title: { display: true, text: 'Total XP' } }   // ← 5. y-axis title
            }
        }
    });

</script>
 </body>

</html>