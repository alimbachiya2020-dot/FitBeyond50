<?php
require_once __DIR__ .'/db.php';
require_once __DIR__ .'/includes/workouts.php';

$pdo=getDatabaseConnection();
$workouts=getAllWorkouts($pdo);

$workoutId=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
$selectedWorkout=null;
$exercises=[];
$errorMessage='';
$successMessage='';

if ($_SERVER['REQUEST_METHOD']=='POST'){
    $postedId=filter_input(INPUT_POST,'workout_id',FILTER_VALIDATE_INT);
    $today=date('Y-m-d');

    if ($postedId === null || $postedId === false || getWorkoutById($pdo, $postedId) === null) {
        $errorMessage = 'Sorry, that workout could not be found.';
    } elseif (hasCompletedToday($pdo, $postedId, $today)) {
        $errorMessage = 'You have already completed this workout today.';
    } else {
        logWorkout($pdo, $postedId, $today, XP_PER_WORKOUT);
        $successMessage = 'Well done! Workout completed. You earned ' . XP_PER_WORKOUT . ' XP.';
    }

}

if ($workoutId !==null){
    if($workoutId !==false){
        $selectedWorkout=getWorkoutById($pdo,$workoutId);
    }
    if ($selectedWorkout==null){
        $errorMessage='Sorry,that workout could not be found.';
    } else{
        $exercises=getWorkoutExercises($pdo,$workoutId);
    }
}

?>

<!DOCTYPE html>
<html>
<html lang="en">
<head>
 <meta name="viewport" content="width=device-width,initial-scale=1">
 <meta charset="UTF-8">
 <title>Workouts - FitBeyond50</title>

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
   <a href="index.php">Home</a>
   <a class="active" href="workout.php">Workout</a>
   <a href="food.php">Food</a>
   <a href="progress.php">Progress</a>
  </div>

  <?php if($errorMessage!==''): ?>
    <p class="error"><?= htmlspecialchars($errorMessage)?></p>
<?php endif;?>

<?php if ($successMessage !== ''): ?>
    <p class="success"><?= htmlspecialchars($successMessage) ?></p>
<?php endif; ?>

<?php if ($selectedWorkout!==null): ?>
    
    <h1 class="main-title"><?=htmlspecialchars($selectedWorkout['workout_name'])?></h1>
    <div class="dashboard-content">
        <p><?= htmlspecialchars((string)$selectedWorkout['duration_minutes'])?> minutes</p>
        <ol>
        <?php foreach($exercises as $exercise): ?>
            <li>
                <h2><?=htmlspecialchars($exercise['exercise_name'])?></h2>
                <p><?= htmlspecialchars($exercise['instructions'])?></p>
                <?php if($exercise['reps']!==null): ?>
                    <p><?= htmlspecialchars((string)$exercise['sets']) ?>
                     sets of <?=htmlspecialchars((string)$exercise['reps'])?> reps</p>
                <?php else: ?>
                    <p><?= htmlspecialchars((string)$exercise['sets'])?>
                    sets of <?= htmlspecialchars((string)$exercise['duration_seconds']) ?> seconds</p>
                <?php endif; ?>
                    <p>Rest:<?= htmlspecialchars((string)$exercise['rest_seconds']) ?> seconds</p>
            </li>
        <?php endforeach; ?>
        </ol>
        <form method="post" action="workout.php?id=<?= $selectedWorkout['workout_id'] ?>">
                <input type="hidden" name="workout_id" value="<?= $selectedWorkout['workout_id'] ?>">
                    <button type="submit" class="button button1">Mark workout completed</button>
        </form>
        <a href="workout.php" class="button button1">Back to workouts</a>
        </div>
<?php else: ?>
    <h1 class="main-title">Workouts</h1>
    <div class="dashboard-content">
    <?php foreach($workouts as $workout): ?>
        <article class="card1">
            <h2><?= htmlspecialchars($workout['workout_name'])?></h2>
            <p><?= htmlspecialchars((string)$workout['duration_minutes']) ?>minutes</p>
            <a href="workout.php?id=<?= $workout['workout_id']?>" class="button button1">View workout</a>
    </article>
    <?php endforeach; ?>
    </div>
<?php endif; ?>

</body>
</html>



