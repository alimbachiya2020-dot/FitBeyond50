<?php

function getAllWorkouts(PDO $pdo):array
{
    $sql='SELECT workout_id,workout_name,duration_minutes FROM workout ORDER BY workout_name';
    return $pdo->query($sql)->fetchAll();
    
}

function getWorkoutById(PDO $pdo, int $workoutId):?array
{
    $sql='SELECT workout_id,workout_name,duration_minutes FROM workout WHERE workout_id=:workout_id';
    $statement = $pdo->prepare($sql);
    $statement->execute(['workout_id' => $workoutId]);
    $workout= $statement->fetch();

    if ($workout == false) {
        return null;

    }
    return $workout;
}

function getWorkoutExercises(PDO $pdo, int $workoutId):array
{   // INNER JOIN matches each link row to the exercise it points to, so I can get the name and instructions
    $sql='SELECT exercise.exercise_name,
    exercise.instructions,
    workout_exercise.sets,
    workout_exercise.reps,
    workout_exercise.duration_seconds,
    workout_exercise.rest_seconds 
    FROM workout_exercise 
    INNER JOIN exercise
    ON workout_exercise.exercise_id=exercise.exercise_id 
    WHERE workout_exercise.workout_id=:workout_id 
    ORDER BY workout_exercise.exercise_order ';

    $statement=$pdo->prepare($sql);
    $statement->execute(['workout_id'=>$workoutId]);
    return $statement->fetchAll();

}

function hasCompletedToday(PDO $pdo, int $workoutId, string $date):bool
{
    $sql='SELECT COUNT(*) FROM workout_log WHERE workout_id=:workout_id AND date_completed=:date_completed';
    $statement=$pdo->prepare($sql);
    $statement->execute(['workout_id'=>$workoutId,'date_completed'=>$date]);
    return $statement->fetchColumn()>0;

}

function logWorkout(PDO $pdo, int $workoutId, string $date, int $xp):void
{
    $sql='INSERT INTO workout_log(workout_id, date_completed, xp_earned) VALUES (:workout_id,:date_completed,:xp_earned)';
    $statement=$pdo->prepare($sql);
    $statement->execute(['workout_id'=>$workoutId,'date_completed'=>$date,'xp_earned'=>$xp]);

}
