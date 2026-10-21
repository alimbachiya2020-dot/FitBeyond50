<?php
// Functions for the progress page

// Returns the last $days dates as text (oldest first), ending on $endDate
function buildDateRange(string $endDate, int $days): array
{
    $dates = [];
    $day = new DateTimeImmutable($endDate);
    $day = $day->modify('-' . ($days - 1) . ' days');

    for ($i = 0; $i < $days; $i++) {
        $dates[] = $day->format('Y-m-d');
        $day = $day->modify('+1 day');
    }
    return $dates;
}

// Returns [date => calories] for each day that has food logged
function getCaloriesPerDay(PDO $pdo, string $startDate, string $endDate): array
{
    $sql = 'SELECT food_log.date_logged, SUM(food.calories_per_serving * food_log.quantity) FROM food_log INNER JOIN food ON food_log.food_id = food.food_id WHERE food_log.date_logged BETWEEN :start_date AND :end_date GROUP BY food_log.date_logged';
    $statement = $pdo->prepare($sql);
    $statement->execute(['start_date' => $startDate, 'end_date' => $endDate]);
    return $statement->fetchAll(PDO::FETCH_KEY_PAIR);
}

function getWorkoutsPerDay(PDO $pdo, string $startDate, string $endDate): array
{
    $sql = 'SELECT date_completed, COUNT(*) FROM workout_log WHERE date_completed BETWEEN :start_date AND :end_date GROUP BY date_completed';
    $statement = $pdo->prepare($sql);
    $statement->execute(['start_date' => $startDate, 'end_date' => $endDate]);
    return $statement->fetchAll(PDO::FETCH_KEY_PAIR);
}

function getXpPerDay(PDO $pdo, string $startDate, string $endDate): array
{
    $sql = 'SELECT date_completed, SUM(xp_earned) FROM workout_log WHERE date_completed BETWEEN :start_date AND :end_date GROUP BY date_completed';
    $statement = $pdo->prepare($sql);
    $statement->execute(['start_date' => $startDate, 'end_date' => $endDate]);
    return $statement->fetchAll(PDO::FETCH_KEY_PAIR);
}
// Returns the total XP earned before a date
function getXpBefore(PDO $pdo, string $date): int
{
    $sql = 'SELECT COALESCE(SUM(xp_earned), 0) FROM workout_log WHERE date_completed < :date_completed';
    $statement = $pdo->prepare($sql);
    $statement->execute(['date_completed' => $date]);
    return (int) $statement->fetchColumn();
}

function fillMissingDays(array $dates,array $valuesByDate):array
{
    $filled=[];
    foreach($dates as $date){
        if (array_key_exists($date,$valuesByDate)){
        $filled[]=(float) $valuesByDate[$date];
        
        }else{
            $filled[]=0;
        }
    }
return $filled;
}
function runningTotal(array $values,float $startTotal):array
{
    $total=$startTotal;
    $totals=[];
    foreach ($values as $value){
        $total= $total + $value;
        $totals[]=$total;

        }
    return $totals;

}