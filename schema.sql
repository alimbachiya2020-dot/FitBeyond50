CREATE TABLE food (
    food_id INT AUTO_INCREMENT PRIMARY KEY,
    food_name VARCHAR(80) NOT NULL,
    calories_per_serving DECIMAL(7,2) NOT NULL CHECK (calories_per_serving >= 0),
    serving_size VARCHAR(40) NOT NULL,
    category VARCHAR(30)
);

CREATE TABLE workout(
    workout_id INT AUTO_INCREMENT PRIMARY KEY,
    workout_name VARCHAR(60) NOT NULL,
    duration_minutes INT NOT NULL CHECK(duration_minutes>0) 
);

CREATE TABLE exercise(
    exercise_id INT AUTO_INCREMENT PRIMARY KEY,
    exercise_name VARCHAR(60) NOT NULL,
    instructions TEXT NOT NULL,
    target_area VARCHAR(40) 
);

CREATE TABLE workout_exercise(
    workout_id INT NOT NULL,
    exercise_id INT NOT NULL,
    exercise_order INT NOT NULL CHECK(exercise_order>0),
    sets INT NOT NULL CHECK(sets>0),
    reps INT CHECK(reps>0),
    duration_seconds INT CHECK(duration_seconds>0),
    rest_seconds INT CHECK(rest_seconds>=0),
    PRIMARY KEY(workout_id,exercise_id),
    FOREIGN KEY(workout_id) REFERENCES workout(workout_id),
    FOREIGN KEY(exercise_id) REFERENCES exercise(exercise_id)
);

CREATE TABLE workout_log(
    workout_log_id INT AUTO_INCREMENT PRIMARY KEY,
    workout_id INT NOT NULL,
    date_completed DATE NOT NULL,
    xp_earned INT NOT NULL CHECK(xp_earned>=0),
    FOREIGN KEY (workout_id)REFERENCES workout(workout_id),
    UNIQUE(workout_id,date_completed)
);

CREATE TABLE food_log(
    food_log_id INT AUTO_INCREMENT PRIMARY KEY,
    food_id INT NOT NULL,
    quantity DECIMAL(6,2) NOT NULL CHECK(quantity>0),
    date_logged DATE NOT NULL,
    FOREIGN KEY (food_id) REFERENCES food(food_id)
);
