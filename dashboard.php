<?php

function getTotalXP(PDO $pdo): int  //gives database connection and returns int

{
    $sql='SELECT COALESCE(SUM(xp_earned),0)FROM workout_log'; //the sql adds up every xp_earned column for every row in workout_log using sum and the coalesce makes it return 0 instead of nothing if no workouts have been logged.
    return(int)$pdo->query($sql)->fetchColumn(); //gets single value that came back
    // converts result to int
    // sneds number back to where the function was called and ends it
}

function getDailyCalories(PDO $pdo,string $date):float 
// cuz i want date to be string(lext)
// but i want it to return a decimal since i have portions that can be halves
{
    $sql= '
    SELECT COALESCE( 
    SUM(food.calories_per_serving * food_log.quantity),
    0)FROM food_log INNER JOIN food ON food_log.food_id = food.food_id 
    WHERE food_log.date_logged = :date_logged';
    //ofc the coalesce gives 0 if nothing was eaten that day
    // multiplitcation between calories per serving of the food and the food log quanitity
    // sum adds up all those entries
    // the last line joins the two tables. since quanity is stored in food_log table and calories per serving are in food.
    // inner join= returns only rows that have matching values in both tables
    // where: makes sure only that day's food ,rn fate_logged is just a placeholder
    $statement=$pdo->prepare($sql);
    $statement->execute(['date_logged'=>$date]);
    //this fills the placeholder with real date and runs it.
    return(float)$statement->fetchColumn();
    //gets the single total and makes it a decimal
}

function getWorkoutDates(PDO $pdo): array
//returns a list
{
    $sql='SELECT DISTINCT date_completed FROM workout_log ORDER BY date_completed DESC';
    // this gets each date once only , even if 2 workouts are done on the same day.
    // distinct= returns only distinct-unique-values
    //desc=descending order . sorted the newsest is first.
    return $pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    //fetchall means every row is selected
    //fetchcolumn returns a  list of all the dates
}

function calculateStreak(array $dates,string $today):int
//list of all dates given, and today's date. so u can test it
//1) if no workouts at all
{
    if (count($dates)==0){
        return 0; 
    // counts all the dates up from dates array.
    // if it is zero(done 0 workouts) , it returns zero.
} //endif
//2) to check if it works
$todayDate=new DateTimeImmutable($today);
// turns today;s date text into a date object. cuz b4 , php couldnt work on it
//datetimeimmutable returns new objects when other modification methods are called unlike datetime
$yesterday=$todayDate->modify('-1 day');
// this works out yesterday without changni todaydate
if ($dates[0]!==$today && $dates[0]!==$yesterday->format('Y-m-d')){
  return 0;
//is the first item(newest workoout date as it is already in descengin order) is not equal to todays date and the newest workout date is not equal to yesterdays date.
// then: turn the date object back into text, so it can be compared with the text dates from database.
// no need to do that with today since it is already in text
// do not need to define today-cuz of datetimezone
// so, if the newest workout is not today or yesterday then she has missed a day , so the streak is 0.
}
//3) to count backwards by each day
$streak=0;
//starts at zero
$expected=new DateTimeImmutable($dates[0]);
//date we expected to see next is the newest workout date

foreach($dates as $date){
    // this goes thru each date in the list one at a time
    if ($date==$expected->format('Y-m-d')){
    //is the date is the same as expected date then 
        $streak=$streak+1;
    // add one to the streak
        $expected=$expected->modify('-1 day');
    //now changed the expected to expect day before
    }else{
        break;
    //ends it so it stops counting
    }

    }
    return $streak;
    //gives back the final streak
}

