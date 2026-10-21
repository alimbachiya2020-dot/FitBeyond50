<?php

function searchFoods(PDO $pdo, string $searchTerm): array
{
    $sql= 'SELECT food_id,food_name, calories_per_serving,serving_size
    FROM food
    WHERE food_name LIKE :search ORDER BY food_name';
    $statement=$pdo->prepare($sql);
    $statement->execute(['search' => '%' . $searchTerm . '%']);
    return $statement->fetchAll();

}

// Returns one food, or null if that ID doesn't exist
function getFoodById(PDO $pdo, int $foodId): ?array
{
    $sql = 'SELECT food_id, food_name, calories_per_serving FROM food WHERE food_id = :food_id';
    $statement = $pdo->prepare($sql);
    $statement->execute(['food_id' => $foodId]);
    $food = $statement->fetch();

    if ($food === false) {
        return null;
    }
    return $food;
}

function logFood(PDO $pdo, int $foodId, float $quantity, string $date):void
{
    $sql='INSERT INTO food_log(food_id, quantity, date_logged) VALUES (:food_id,:quantity,:date_logged)';
    $statement=$pdo->prepare($sql);
    $statement->execute(['food_id'=>$foodId,'quantity'=>$quantity,'date_logged'=>$date]);

}

// Returns each food logged on a date, with its calories
function getFoodLogForDate(PDO $pdo, string $date): array
{
    $sql = 'SELECT food.food_name,
                   food_log.quantity,
                   food.calories_per_serving * food_log.quantity AS entry_calories
            FROM food_log
            INNER JOIN food ON food_log.food_id = food.food_id
            WHERE food_log.date_logged = :date_logged
            ORDER BY food_log.food_log_id';
    $statement = $pdo->prepare($sql);
    $statement->execute(['date_logged' => $date]);
    return $statement->fetchAll();
}

