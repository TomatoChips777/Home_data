-- Insert dummy reports with various statuses and types
INSERT INTO reports (user_id, location, issue_type, description, status, created_at) VALUES
(1, 'Building A - Room 101', 'electrical', 'Flickering lights in the classroom, making it difficult to read and concentrate', 'pending', DATE_SUB(NOW(), INTERVAL 2 DAY)),

(2, 'Main Library - 2nd Floor', 'plumbing', 'Water leak from ceiling near the computer stations', 'in_progress', DATE_SUB(NOW(), INTERVAL 5 DAY)),

(1, 'Cafeteria', 'cleaning', 'Garbage bins are overflowing and need immediate attention', 'resolved', DATE_SUB(NOW(), INTERVAL 7 DAY)),

(2, 'Parking Lot B', 'safety', 'Several lights are out in the parking lot, creating safety concerns at night', 'pending', DATE_SUB(NOW(), INTERVAL 1 DAY)),

(1, 'Science Building - Lab 3', 'plumbing', 'Sink is clogged and water is not draining properly', 'in_progress', DATE_SUB(NOW(), INTERVAL 3 DAY)),

(2, 'Gymnasium', 'structural', 'Crack in the wall near the entrance door', 'pending', NOW()),

(1, 'Student Center', 'electrical', 'Power outlets in the study area are not working', 'resolved', DATE_SUB(NOW(), INTERVAL 10 DAY)),

(2, 'Building C - Restroom', 'plumbing', 'Toilet in the mens restroom is continuously running', 'in_progress', DATE_SUB(NOW(), INTERVAL 4 DAY)),

(1, 'Outdoor Basketball Court', 'structural', 'Damaged flooring creating tripping hazard', 'pending', DATE_SUB(NOW(), INTERVAL 6 HOUR)),

(2, 'Admin Building - Room 205', 'cleaning', 'Air conditioning vents need cleaning, causing dust issues', 'resolved', DATE_SUB(NOW(), INTERVAL 15 DAY));

-- Insert more reports with different status distributions
INSERT INTO reports (user_id, location, issue_type, description, status, created_at) VALUES
(1, 'Library Study Room 3', 'electrical', 'Power strip not working, limiting available outlets for laptops', 'pending', DATE_SUB(NOW(), INTERVAL 8 HOUR)),

(2, 'Building B Hallway', 'safety', 'Emergency exit sign light is not working', 'in_progress', DATE_SUB(NOW(), INTERVAL 2 DAY)),

(1, 'Computer Lab 2', 'cleaning', 'Keyboards and mice need sanitizing', 'resolved', DATE_SUB(NOW(), INTERVAL 12 DAY)),

(2, 'Student Lounge', 'structural', 'Window seal is broken, causing draft', 'pending', DATE_SUB(NOW(), INTERVAL 1 DAY)),

(1, 'Faculty Building', 'plumbing', 'Water fountain on 2nd floor is not dispensing water', 'in_progress', DATE_SUB(NOW(), INTERVAL 3 DAY));
