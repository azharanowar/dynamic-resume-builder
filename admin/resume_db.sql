-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `resume_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `resume_db`;

-- --------------------------------------------------------

--
-- Table structure for table `certifications`
--

CREATE TABLE `certifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `authority` varchar(255) NOT NULL,
  `issue_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `certifications`
--

INSERT INTO `certifications` (`id`, `name`, `authority`, `issue_date`) VALUES
(1, 'Certified Laravel Developer', 'Laracasts', '2021-07-10');

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE `education` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `degree` varchar(255) NOT NULL,
  `institution` varchar(255) NOT NULL,
  `graduation_date` date NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `education`
--

INSERT INTO `education` (`id`, `degree`, `institution`, `graduation_date`, `description`) VALUES
(1, 'B.Sc. in Computer Science', 'University of Technology', '2018-05-20', 'Focused on software engineering, database management, and web technologies.');

-- --------------------------------------------------------

--
-- Table structure for table `experience`
--

CREATE TABLE `experience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_title` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `experience`
--

INSERT INTO `experience` (`id`, `job_title`, `company_name`, `start_date`, `end_date`, `description`) VALUES
(1, 'Senior Web Developer', 'Tech Solutions Inc.', '2020-01-15', NULL, 'Leading the development of client-facing web applications using PHP, Laravel, and Vue.js. Responsible for mentoring junior developers and ensuring code quality.'),
(2, 'Web Developer', 'Creative Agency', '2018-06-01', '2019-12-31', 'Developed and maintained WordPress websites for various clients. Collaborated with designers to create responsive and user-friendly interfaces.');

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `about_me` text NOT NULL,
  `profile_image` varchar(255) DEFAULT 'assets/images/default-profile.png',
  `github_url` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `full_name`, `role`, `about_me`, `profile_image`, `github_url`, `email`, `phone`, `linkedin_url`) VALUES
(1, 'Azhar Anowar', 'Full Stack Web Developer', 'A passionate and creative full-stack developer with a knack for building elegant and efficient web applications. I have experience in both front-end and back-end technologies, and I am always eager to learn new things and take on challenging projects.', 'assets/images/azhar-anowar.jpeg', 'https://github.com/azharanowar', 'azharanowar@gmail.com', '010-5149-3665', 'https://linkedin.com/in/azharanowar');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--
-- The password for this user is 'admin123'
--

INSERT INTO `users` (`id`, `email`, `password`) VALUES
(1, 'azharanowar@gmail.com', '$2y$10$SebxqUzYyqsHtoIGiP4Yp.kW8TEAc5cAY.haJr1NGZ8w6tW/p81hm');

