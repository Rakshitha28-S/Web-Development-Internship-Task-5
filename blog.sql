-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 04:18 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `created_at`, `user_id`) VALUES
(17, 'Tree', 'Our supporters know that we plant locally sourced, native trees on high need National Forests through our partnership with the U.S. Forest Service, but we often receive inquiries about how and when we plant. Below are five quick facts that may help answer some of your planting questions and demonstrate the measures our agency partner takes to ensure that our trees survive.', '2025-05-03 09:19:05', 1),
(18, 'Technology', 'Computer science is an amazing field of study, formalizing as a college discipline in the ’60s and evolving through the decades since to become part of daily life around the world. And, for some of us, it’s also turned into a passion.\r\n\r\nWhether computer science is one of your college courses or just something of casual interest, this blog post is dedicated to you. Detailing 30 of the best computer science blogs, this list is a place to start; continue your own education and find enriching, intriguing topics related to this rapidly changing discipline, no matter your level of experience or your background.', '2025-05-03 09:21:02', 1),
(19, 'Science Friction', 'Geographies and Sites: The novel takes readers on a journey across a geographically diverse world, from the redwood forests of California to the ancient ruins of Anatolia and the arid landscapes of the Middle East. We see Silicon Valley\'s technological hub juxtaposed with the ancient sites of Göbekli Tepe and Çatalhöyük in Turkey, where the story\'s origins are rooted in pre-Neolithic societies. The journey further extends to the historical heart of Jerusalem, with its layered religious history and the ancient caverns beneath the Temple Mount. Even Crimea, a region steeped in historical conflict, features prominently, serving as the setting for the Gollinger family\'s ancient legend.', '2025-05-03 09:21:58', 1),
(20, 'Cultures', 'The novel explores the rich tapestry of cultures present in its world. From the ancient societies of Mesopotamia to the contemporary Kurdish, Jewish, and Islamic communities, the story reveals the complexities of cultural identities and the clashes and intersections that occur in a multi-faceted world. The Kurdish characters, particularly Zara Khatum, are portrayed with depth and nuance, highlighting their resilience and struggles for freedom and self-determination. The portrayal of the Jewish and Islamic characters is nuanced, demonstrating that cultural clashes are often rooted in historical grievances and misperceptions.', '2025-05-03 09:22:31', 1),
(21, 'MoxWorld Empire', 'The story\'s world is dominated by MoxWorld Holdings, a powerful tech empire with a global reach. MoxWorld\'s influence extends from digital platforms like MoxMedia News and MoxReads to advanced technologies like MoxWraps, MoxMovers, and MoxWorld Security. The corporation\'s control over vital resources and infrastructure creates a dynamic where global politics are intertwined with technological advancement, raising questions about the ethical implications of corporate power and the potential for technological dystopia.', '2025-05-03 09:24:30', 9),
(22, 'Religious Myths', 'The novel weaves ancient religious myths and beliefs into its narrative fabric. Central to the story is the \"black object,\" a mythical artifact with the power to both destroy and save the world. This artifact is tied to an ancient matriarchal lineage, with characters like Zara and Peter carrying the genetic legacy of this lineage and inheriting the knowledge of its secrets. The story also incorporates the goddess Asherah, who is depicted as a powerful and important figure in ancient Hebrew and Semitic cultures, challenging the patriarchal interpretation of the Torah and highlighting the enduring power of ancient female deities.', '2025-05-03 09:24:50', 9),
(23, 'Ancient World', 'The novel delves into the ancient world through the oral traditions passed down through generations. The story explores the origins of customs, beliefs, and societal structures, suggesting that these ancient narratives continue to shape the present day. We see glimpses of pre-Neolithic societies in the chapters set in Çatalhöyük and Göbekli Tepe, and the story emphasizes the importance of understanding the past to navigate the present. The inclusion of these ancient narratives adds a layer of depth and complexity to the narrative, suggesting that the past holds important clues for the future.', '2025-05-03 09:25:12', 9),
(24, 'Science and Technology', 'The novel envisions a future where technology has dramatically advanced, with MoxWorld Holdings spearheading innovation in areas like AI, self-driving vehicles, and digital media. The story explores the transformative potential of these technologies but also highlights the risks they pose. The MoxWorld empire\'s control over vital resources and infrastructure raises questions about the ethical implications of technological advancement and the need for responsible innovation. The novel uses science fiction to explore anxieties about privacy, control, and the potential for technology to be used for nefarious purposes.', '2025-05-03 09:25:40', 9),
(25, 'Geopolitical Landscape', 'The novel presents a complex geopolitical landscape, with nations embroiled in conflict, vying for power, and struggling to control vital resources. The story explores the tensions between the US, China, and Russia, highlighting the dangers of an arms race and the potential for nuclear war. It also examines the plight of the Kurds, who seek autonomy and self-determination, and the simmering conflicts between them and Turkey. The novel emphasizes the importance of diplomacy and cooperation in a world increasingly dominated by political rivalry.', '2025-05-03 09:26:06', 9),
(26, 'John Scalzi', 'The thing about it was… (And again, this helps establish the trend of I will do anything as long as it furthers my own laziness.) I was going into college to be a writer. I went immediately to the school newspaper and started writing there. As I tell people, regardless of what degree I would have ended up with, I majored in newspaper. But while I was writing for the newspaper, I still had to take classes, or they wouldn’t let me stay in school. Strange how that works out. So, I started taking the classes that looked interesting to me, and they ended up being philosophy courses.', '2025-05-03 09:27:11', 9);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `site_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_name`, `site_description`) VALUES
(1, 'Welcome to Blog-app', 'This project is a powerful blog management system where users can explore posts, editors can create and edit content, and admins can oversee the entire platform. Experience seamless content management and collaboration.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','editor','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'manas', '$2y$10$EbnRb.NZAXomi6.2Y/f3W.MStUNy65dC65dBjYFCKZ4lXxf6iWFWy', 'editor'),
(9, 'manas_2', '$2y$10$a5485xXKw0kmj85Fq5TQl.pk9jBekw1hMCib0WpHZchav9bAnluSW', 'editor'),
(10, 'manas_3', '$2y$10$tU3A3S.hXsk76BlP2uvUF.yzoqCSC.Fihvukc4HqBjDP40Ch06TWm', 'user'),
(12, 'admin', '$2y$10$xum.mPQ4RZzkRRt.locFGuLfWOIRBUdCHy.ahgPtJgCAJAa6N5SJS', 'admin'),
(26, 'mj6', '$2y$10$czKKQhHoCflHb3UhUE3eNuTnz7HuhLvqDF/k6MQkQiQtO3dj7WL12', 'editor'),
(27, 'mj7', '$2y$10$ZyD5FoOwc2FZo/zQYypQqev9KhmHMUPsEv3SDaA6TCNqRZzXP4XB6', 'editor');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;