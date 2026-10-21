-- Sample data for FitBeyond50: foods commonly eaten by the user
INSERT INTO food (food_name, calories_per_serving, serving_size, category) VALUES
('Rotli', 90, '1 rotli (with a little ghee)', 'Bread'),
('Rotlo', 190, '1 rotlo (bajra)', 'Bread'),
('Bhinda nu shaak', 130, '1 bowl (150g)', 'Shaak'),
('Paneer nu shaak', 280, '1 bowl (150g)', 'Shaak'),
('Tofu spinach', 150, '1 bowl (150g)', 'Shaak'),
('Parvar bataka nu shaak', 150, '1 bowl (150g)', 'Shaak'),
('Doodhi bataka nu shaak', 120, '1 bowl (150g)', 'Shaak'),
('Mag', 160, '1 bowl (150g)', 'Daal'),
('Daal', 130, '1 bowl (150ml)', 'Daal'),
('Rice', 195, '1 bowl (150g cooked)', 'Rice'),
('Khichdi (plain)', 230, '1 bowl (200g)', 'Rice'),
('Khichdi vaghareli', 280, '1 bowl (200g)', 'Rice'),
('Greek yoghurt with muesli', 280, '1 bowl (150g + 40g muesli)', 'Breakfast'),
('Chai', 80, '1 cup with milk and sugar', 'Drink');

-- Sample data: beginner-friendly exercises for over-50s
INSERT INTO exercise (exercise_name, instructions, target_area) VALUES
('Marching on the spot', 'Stand tall and march on the spot, lifting your knees to a comfortable height. Swing your arms gently. Hold a chair for balance if needed.', 'Cardio'),
('Chair sit-to-stand', 'Sit near the front of a sturdy chair with feet flat. Lean forward slightly and stand up without using your hands if possible, then sit back down slowly.', 'Legs'),
('Wall push-ups', 'Stand an arm length from a wall with hands flat on it at shoulder height. Bend your elbows to bring your chest towards the wall, then push back.', 'Chest and arms'),
('Heel raises', 'Stand behind a chair holding the back for support. Rise slowly onto your toes, hold for a second, then lower your heels back down.', 'Calves and balance'),
('Seated knee lifts', 'Sit upright on a chair. Lift one knee towards your chest, lower it slowly, then repeat with the other leg.', 'Core'),
('Arm circles', 'Stand or sit with arms out to the sides at shoulder height. Make small circles forwards, then backwards.', 'Shoulders'),
('Side leg raises', 'Stand behind a chair holding the back. Lift one leg out to the side, keeping your back straight, then lower it slowly. Repeat on the other side.', 'Hips'),
('Seated torso twists', 'Sit upright with feet flat. Cross your arms over your chest and slowly turn your upper body to one side, then the other.', 'Back and core'),
('Step touches', 'Step one foot out to the side and bring the other foot to meet it, then step back the other way. Keep a steady rhythm.', 'Cardio'),
('Seated hamstring stretch', 'Sit on the edge of a chair with one leg straight out and heel on the floor. Lean forward gently from the hips until you feel a stretch. Hold, then switch legs.', 'Flexibility');

-- Sample data: workouts
INSERT INTO workout (workout_name, duration_minutes) VALUES
('Beginner Full Body', 30),
('Gentle Cardio', 30),
('Strength and Balance', 30),
('Stretch and Mobility', 30);

-- Sample data: exercises in each workout (each workout is about 30 minutes)
-- Columns: workout, exercise, order, sets, reps, duration (s), rest (s)
INSERT INTO workout_exercise (workout_id, exercise_id, exercise_order, sets, reps, duration_seconds, rest_seconds) VALUES
-- Workout 1: Beginner Full Body
(1, 1, 1, 3, NULL, 90, 30),
(1, 2, 2, 3, 10, NULL, 60),
(1, 3, 3, 3, 10, NULL, 60),
(1, 4, 4, 3, 12, NULL, 45),
(1, 5, 5, 3, 10, NULL, 45),
(1, 7, 6, 2, 10, NULL, 45),
(1, 10, 7, 2, NULL, 60, 15),
-- Workout 2: Gentle Cardio
(2, 1, 1, 4, NULL, 120, 45),
(2, 9, 2, 4, NULL, 120, 45),
(2, 6, 3, 3, NULL, 45, 30),
(2, 5, 4, 3, 12, NULL, 30),
(2, 10, 5, 1, NULL, 60, 0),
-- Workout 3: Strength and Balance
(3, 1, 1, 2, NULL, 90, 30),
(3, 2, 2, 4, 10, NULL, 60),
(3, 3, 3, 4, 10, NULL, 60),
(3, 4, 4, 4, 12, NULL, 45),
(3, 7, 5, 3, 10, NULL, 45),
(3, 10, 6, 2, NULL, 45, 15),
-- Workout 4: Stretch and Mobility
(4, 1, 1, 2, NULL, 90, 30),
(4, 6, 2, 3, NULL, 60, 30),
(4, 8, 3, 3, 12, NULL, 30),
(4, 5, 4, 3, 10, NULL, 30),
(4, 9, 5, 2, NULL, 120, 30),
(4, 7, 6, 2, 8, NULL, 30),
(4, 10, 7, 4, NULL, 60, 30);

-- Sample data: completed workouts (for testing XP, streak and graphs)
INSERT INTO workout_log (workout_id, date_completed, xp_earned) VALUES
(1, '2026-10-03', 50),
(2, '2026-10-06', 50),
(1, '2026-10-07', 50),
(3, '2026-10-08', 50),
(4, '2026-10-08', 50),
(2, '2026-10-09', 50),
(1, '2026-10-10', 50);

-- Sample data: food logged (for testing calorie totals and graphs)
INSERT INTO food_log (food_id, quantity, date_logged) VALUES
(14, 1, '2026-10-08'),
(13, 1, '2026-10-08'),
(2, 2, '2026-10-08'),
(7, 1, '2026-10-08'),
(9, 1, '2026-10-08'),
(14, 2, '2026-10-09'),
(13, 1, '2026-10-09'),
(1, 3, '2026-10-09'),
(3, 1, '2026-10-09'),
(9, 1, '2026-10-09'),
(10, 1, '2026-10-09'),
(14, 2, '2026-10-10'),
(1, 2, '2026-10-10'),
(4, 1, '2026-10-10'),
(8, 1, '2026-10-10'),
(10, 0.5, '2026-10-10');

