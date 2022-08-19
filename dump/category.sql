-- SELECT t1.name AS level_1, t2.name as level_2, t3.name as level_3, t4.name as level_4
-- FROM category AS t1
--          LEFT JOIN category AS t2 ON t2.parent = t1.category_id
--          LEFT JOIN category AS t3 ON t3.parent = t2.category_id
--          LEFT JOIN category AS t4 ON t4.parent = t3.category_id
-- WHERE t1.name = 'Global';


CREATE TABLE `category` (
  `category_id` int NOT NULL,
  `name` varchar(20) NOT NULL,
  `parent` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `name`, `parent`) VALUES
(1, 'Global', NULL),
(2, 'Africa', 1),
(3, 'Northern Africa', 2),
(4, 'Sub-Saharan Africa', 2),
(5, 'Americas', 1),
(6, 'Latin America', 5),
(7, 'Northern America', 5),
(8, 'Asia', 1),
(9, 'Central Asia', 8),
(10, 'Eastern Asia', 8),
(11, 'Eastern Africa', 4),
(12, 'Middle Africa', 4),
(13, 'Southern Africa', 4),
(14, 'Central America', 6),
(15, 'South America', 6),
(16, 'Singapore', 9),
(17, 'India', 9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`,`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT;
