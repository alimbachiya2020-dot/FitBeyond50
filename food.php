<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/food.php';
require_once __DIR__ . '/includes/dashboard.php';

$pdo = getDatabaseConnection();

$searchTerm = trim((string) filter_input(INPUT_GET, 'search'));
$results = [];

if ($searchTerm !== '') {
    $results = searchFoods($pdo, $searchTerm);
}
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $foodId = filter_input(INPUT_POST, 'food_id', FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_FLOAT);

    $selectedFood = null;
    if ($foodId !== null && $foodId !== false) {
        $selectedFood = getFoodById($pdo, $foodId);
    }

    if ($selectedFood === null) {
        $errorMessage = 'Sorry, that food could not be found.';
    } elseif ($quantity === null || $quantity === false || $quantity <= 0) {
        $errorMessage = 'Please enter a quantity greater than 0.';
    } elseif ($quantity > MAX_QUANTITY) {
        $errorMessage = 'Please enter a quantity of ' . MAX_QUANTITY . ' or less.';
    } else {
        logFood($pdo, $foodId, $quantity, date('Y-m-d'));
        $calories = (float) $selectedFood['calories_per_serving'] * $quantity;
        $successMessage = 'Added ' . $quantity . ' x ' . $selectedFood['food_name'] . ' (' . round($calories) . ' calories).';
    }
}
$todaysFood = getFoodLogForDate($pdo, date('Y-m-d'));
$caloriesToday = getDailyCalories($pdo, date('Y-m-d'));

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
   <a class="active" href="food.php">Food</a>
   <a href="progress.php">Progress</a>
  </div>

<h1 class="main-title">Log Food</h1>
<?php if ($successMessage !== ''): ?>
    <p class="success"><?= htmlspecialchars($successMessage) ?></p>
<?php endif; ?>
<?php if ($errorMessage !== ''): ?>
    <p class="error"><?= htmlspecialchars($errorMessage) ?></p>
<?php endif; ?>

<div class="dashboard-content">

    <form method="get" action="food.php">
        <input type="search" name="search" value="<?= htmlspecialchars($searchTerm) ?>" placeholder="Search for a food, e.g. rotli">
        <button type="submit" class="button button1">Search</button>
    </form>

    <?php if ($searchTerm !== '' && count($results) === 0): ?>
        <p class="error">No foods found for "<?= htmlspecialchars($searchTerm) ?>". Try a different spelling.</p>
    <?php endif; ?>

    <?php foreach ($results as $food): ?>
        <article class="card1">
            <h2><?= htmlspecialchars($food['food_name']) ?></h2>
            <p><?= htmlspecialchars($food['serving_size']) ?>: <?= number_format((float) $food['calories_per_serving']) ?> calories</p>
            <form method="post" action="food.php?search=<?= urlencode($searchTerm) ?>">
                <input type="hidden" name="food_id" value="<?= $food['food_id'] ?>">
                <label>Servings:
                    <input type="number" name="quantity" value="1" min="0.5" max="<?= MAX_QUANTITY ?>" step="0.5">
                </label>
                <button type="submit" class="button button1">Add</button>
            </form>
        </article>
    <?php endforeach; ?>
<div class="bottom-box">
    <h2>Today's food</h2>

    <?php if (count($todaysFood) === 0): ?>
        <p>Nothing logged yet today.</p>
    <?php else: ?>
        <?php foreach ($todaysFood as $entry): ?>
            <?php if ((float) $entry['quantity'] === 1.0): ?>
    <p><?= htmlspecialchars($entry['food_name']) ?>: 1 serving: <?= number_format((float) $entry['entry_calories']) ?> calories</p>
<?php else: ?>
    <p><?= htmlspecialchars($entry['food_name']) ?>: <?= (float) $entry['quantity'] ?> servings: <?= number_format((float) $entry['entry_calories']) ?> calories</p>
<?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <p><b>Total today: <?= number_format($caloriesToday) ?> calories</b></p>
</div>
</div>

</body>
</html>