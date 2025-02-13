-- Insert dummy data for lost and found items
INSERT INTO lost_found (user_id, type, item_name, category, description, location, status, contact_info, date_reported) VALUES
-- Lost Items
(1, 'lost', 'Blue Laptop Bag', 'Accessories', 'Navy blue laptop bag with white stripes. Contains laptop charger and mouse.', 'Library - 2nd Floor Study Area', 'open', 'Room 301, +1234567890', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, 'lost', 'iPhone 13 Pro', 'Electronics', 'Space gray iPhone 13 Pro with clear case. Has a photo of a cat as wallpaper.', 'Cafeteria', 'open', 'Student Center, +1234567891', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(3, 'lost', 'Student ID Card', 'Documents', 'Student ID card under the name "John Smith"', 'Gym Locker Room', 'claimed', 'Engineering Building Room 205, +1234567892', DATE_SUB(NOW(), INTERVAL 5 DAY)),
(1, 'lost', 'Calculator TI-84', 'Electronics', 'Texas Instruments TI-84 Plus graphing calculator. Has initials "MS" on the back.', 'Math Building Room 103', 'open', 'Math Department Office, +1234567893', DATE_SUB(NOW(), INTERVAL 3 DAY)),
(2, 'lost', 'Red Umbrella', 'Accessories', 'Automatic red umbrella with black handle. Brand: Windproof.', 'Bus Stop near Admin Building', 'closed', 'Admin Office, +1234567894', DATE_SUB(NOW(), INTERVAL 4 DAY)),

-- Found Items
(3, 'found', 'Water Bottle', 'Accessories', 'Blue Hydro Flask water bottle with stickers. Found on a study table.', 'Library - 1st Floor', 'open', 'Library Front Desk, +1234567895', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(1, 'found', 'Textbook', 'Books', 'Introduction to Psychology textbook. Has highlighting in yellow.', 'Psychology Department Hallway', 'claimed', 'Psychology Office, +1234567896', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, 'found', 'USB Drive', 'Electronics', '32GB black USB drive with no identifying marks.', 'Computer Lab 2', 'open', 'IT Help Desk, +1234567897', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(3, 'found', 'Reading Glasses', 'Accessories', 'Black-rimmed reading glasses in a brown case.', 'Student Center - Study Area', 'open', 'Student Services, +1234567898', DATE_SUB(NOW(), INTERVAL 3 DAY)),
(1, 'found', 'Denim Jacket', 'Clothing', 'Light blue denim jacket, size M, with a patch on the left sleeve.', 'Auditorium', 'closed', 'Lost and Found Office, +1234567899', DATE_SUB(NOW(), INTERVAL 4 DAY));
