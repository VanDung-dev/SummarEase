-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for summarease
CREATE DATABASE IF NOT EXISTS `summarease` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `summarease`;

-- Dumping structure for table summarease.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table summarease.cache: ~0 rows (approximately)

-- Dumping structure for table summarease.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table summarease.cache_locks: ~0 rows (approximately)

-- Dumping structure for table summarease.documents
CREATE TABLE IF NOT EXISTS `documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `content` longtext,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table summarease.documents: ~95 rows (approximately)
REPLACE INTO `documents` (`id`, `user_id`, `title`, `file_name`, `file_type`, `uploaded_at`, `content`) VALUES
	(1, 1, 'biiihhi', NULL, 'text', '2025-08-11 08:11:54', 'biiihhi'),
	(2, 2, 'biiihhi', NULL, 'text', '2025-08-11 08:12:40', 'biiihhi'),
	(4, 1, 'form: in Laravel, it is possible to have multiple submit...', NULL, 'text', '2025-08-11 08:17:57', 'in Laravel, it is possible to have multiple submit buttons within a single HTML form, and these buttons can be used to trigger different actions or handle different types of requests (e.g., POST or GET), although typically forms submit as either POST or GET, and the buttons within that form will adhere to that method.\r\nHandling Multiple Submit Buttons within a Single Form:\r\nDistinguishing Buttons in the Controller:\r\nAssign a name attribute and a unique value to each submit button.\r\nIn your Laravel controller, you can then check if ($request->input(\'button_name\') == \'button_value\') to determine which button was clicked and execute the corresponding logic.\r\nCode\r\n\r\n    <form method="POST" action="{{ route(\'your.route\') }}">\r\n        @csrf\r\n        <!-- Form fields -->\r\n        <button type="submit" name="action" value="save_draft">Save Draft</button>\r\n        <button type="submit" name="action" value="publish">Publish</button>\r\n    </form>\r\nCode\r\n\r\n    // In your controller\r\n    public function handleForm(Request $request)\r\n    {\r\n        if ($request->input(\'action\') == \'save_draft\') {\r\n            // Logic for saving as draft\r\n        } elseif ($request->input(\'action\') == \'publish\') {\r\n            // Logic for publishing\r\n        }\r\n        // ...\r\n    }\r\nUsing JavaScript to Dynamically Change Form Attributes:\r\nYou can use JavaScript to change the action or method of the form dynamically based on which button is clicked before submitting the form. This allows for more complex scenarios, such as submitting to different routes or using different HTTP methods for each button.\r\nRegarding POST and GET Requests:\r\nA single HTML <form> element typically specifies either a method="POST" or method="GET". All submit buttons within that form will trigger a submission using the specified method.\r\nIf you need to perform a GET request with one button and a POST request with another, you would generally need two separate <form> elements, each configured with its respective method.\r\nAlternatively, as mentioned above, JavaScript can be used to dynamically change the form\'s method or action before submission, effectively allowing different "submit" actions from a single visual form structure.'),
	(5, 1, 'shshhshs', NULL, 'text', '2025-08-11 08:20:23', 'shshhshs'),
	(6, 1, 'the: The Valais Blacknose (German: Walliser Schwarznase...', NULL, 'text', '2025-08-11 08:50:21', 'The Valais Blacknose (German: Walliser Schwarznasenschaf) is a breed of domestic sheep originating in the Valais region of Switzerland.[2] It is a dual-purpose breed, raised both for meat and for wool.[3]: 281 \r\n\r\nHistory\r\nThe breed originates in the mountains of the canton of Valais – from which its name derives – and of the Bernese Oberland. It is documented as far back as the fifteenth century, but the present German name was not used before 1884; the breed standard dates from 1962. In the past there was some cross-breeding with imported sheep: in the nineteenth century with Bergamasca and Cotswold stock,[4]: 940  and in the twentieth century with the Southdown.[3]: 280 \r\n\r\nThe Valais Blacknose is also present in Austria, Germany and Holland.[5] The total population reported in Switzerland for 2023 was 10286–19732, with 9380 ewes registered in the herd-book; the conservation status of the breed is listed as \'not at risk\'.[2]\r\n\r\nCharacteristics\r\nThe Schwarznasenschaf is a mountain breed, well adapted to grazing on the stony pastures of its area of origin.[4]: 940  Both rams and ewes are horned,[4]: 940  with helical or spiral-shaped horns. Ewes may have black spots on the tail, but rams may not.[6]: 50 \r\n\r\nUse\r\nThe Valais Blacknose is a dual-purpose breed, reared for both meat and wool. The wool is coarse: fibre diameter averages approximately 38 microns, and staple length is 100 mm (4 in) or more.[4]: 940  The annual yield of wool is about 4 kg (10 lb) per head.[3]: 281'),
	(7, 1, 'sheep: [72] The bleats of individual sheep are distinctiv...', NULL, 'text', '2025-08-11 08:52:55', 'Sounds made by domestic sheep include bleats, grunts, rumbles and snorts. Bleating ("baaing") is used mostly for contact communication, especially between dam and lambs, but also at times between other flock members.[72] The bleats of individual sheep are distinctive, enabling the ewe and her lambs to recognize each other\'s vocalizations.[73] Vocal communication between lambs and their dam declines to a very low level within several weeks after parturition.[72] A variety of bleats may be heard, depending on sheep age and circumstances. Apart from contact communication, bleating may signal distress, frustration or impatience; however, sheep are usually silent when in pain. Isolation commonly prompts bleating by sheep.[74] Pregnant ewes may grunt when in labor.[75] Rumbling sounds are made by the ram during courting; somewhat similar rumbling sounds may be made by the ewe,[72] especially when with her neonate lambs. A snort (explosive exhalation through the nostrils) may signal aggression or a warning,[72][76] and is often elicited from startled sheep.[77]');

-- Dumping structure for table summarease.evaluations
CREATE TABLE IF NOT EXISTS `evaluations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `summary_id` bigint unsigned NOT NULL,
  `evaluator_type` enum('ai','human') DEFAULT 'human',
  `clarity_score` tinyint DEFAULT NULL,
  `coverage_score` tinyint DEFAULT NULL,
  `fluency_score` tinyint DEFAULT NULL,
  `overall_score` float GENERATED ALWAYS AS ((((`clarity_score` + `coverage_score`) + `fluency_score`) / 3)) STORED,
  `comments` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `summary_id` (`summary_id`),
  CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`summary_id`) REFERENCES `summaries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `evaluations_chk_1` CHECK ((`clarity_score` between 0 and 10)),
  CONSTRAINT `evaluations_chk_2` CHECK ((`coverage_score` between 0 and 10)),
  CONSTRAINT `evaluations_chk_3` CHECK ((`fluency_score` between 0 and 10))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table summarease.evaluations: ~0 rows (approximately)
REPLACE INTO `evaluations` (`id`, `summary_id`, `evaluator_type`, `clarity_score`, `coverage_score`, `fluency_score`, `comments`, `created_at`) VALUES
	(1, 1, 'human', 9, 8, 9, 'Bản tóm tắt khá chính xác và mạch lạc', '2025-07-22 00:55:07');

-- Dumping structure for table summarease.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table summarease.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table summarease.guest_documents
CREATE TABLE IF NOT EXISTS `guest_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `guest_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Người upload',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `content` longtext COLLATE utf8mb4_unicode_ci COMMENT 'Nội dung thuần text',
  PRIMARY KEY (`id`),
  KEY `idx_docs_user` (`guest_id`),
  FULLTEXT KEY `ft_docs_content` (`content`),
  CONSTRAINT `fk_documents_user` FOREIGN KEY (`guest_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table summarease.guest_documents: ~0 rows (approximately)
REPLACE INTO `guest_documents` (`id`, `guest_id`, `title`, `file_name`, `file_type`, `uploaded_at`, `content`) VALUES
	(1, 'QDBEAT6hzuL0eKRqfY0OPniROVjs9SFp3h9t33AC', 'Văn bản tóm tắt - 2025-08-11 14:22:41', NULL, 'text', '2025-08-11 14:22:41', 'The Merino is a breed or group of breeds of domestic sheep, characterised by very fine soft wool. It was established in the Iberian Peninsula (modern Spain and Portugal) near the end of the Middle Ages, and was for several centuries kept as a strict Spanish monopoly; exports of the breed were not allowed, and those who tried risked capital punishment. During the eighteenth century, flocks were sent to the courts of a number of European countries, including France (where they developed into the Rambouillet), Hungary, the Netherlands, Prussia, Saxony and Sweden.\r\n\r\nThe Merino subsequently spread to many parts of the world, including South Africa, Australia, and New Zealand. They are presently common in South Africa. Numerous recognised breeds, strains and variants have developed from the original type; these include, among others, the American Merino and Delaine Merino in the Americas, the Australian Merino, Booroola Merino and Peppin Merino in Oceania, and the Gentile di Puglia, Merinolandschaf and Rambouillet in Europe.[1]: 861 \r\n\r\nThe Australian Poll Merino is a polled (hornless) variant. Rams of other Merino breeds have long, spiral horns which grow close to the head, while ewes are usually hornless.\r\n\r\nHistory\r\n\r\nOne of the earliest depictions of a Merino. "El Buen Pastor" (The Good Shepherd) by Bartolomé Esteban Murillo, ca. 1650\r\nEtymology\r\nThe name merino was not documented in Spain until the early 15th century, and its origin is disputed.[2]: 39 \r\n\r\nTwo suggested origins for the Spanish word merino are given in:[3]\r\n\r\nIt may be an adaptation to the sheep of the name of a Castilian official inspector (merino) over a merindad, who may have also inspected sheep pastures. This word is from the medieval Latin maiorinus, a steward or head official of a village, from maior, meaning "greater". However, there is no indication in any of the Leonese or Castilian law codes that this official, either named as maiorinus or merino had any duties connected with sheep, and the late date at which merino was first documented makes any connection with the name of an early medieval magistrate implausible.[4]: 3 \r\nIt also may be from the name of an Imazighen tribe, the Marini (or in Spanish, Benimerines), who occupied parts of the southwest of the Iberian Peninsula during the 12th and 13th centuries. This view gains some support from the derivation of many medieval Spanish pastoral terms from Arabic or Berber languages.[5] However, an etymology based on a 12th-century origin for Merino sheep when the Marinids were in Spain is unacceptable; the origin of the breed occurred much later.[6]: 123 \r\nOrigin\r\nThe three theories of the origins of the Merino breed in Spain are: the importation of North African flocks in the 12th century;[4]: 4  its origin and improvement in Extremadura in the 12th and 13th centuries;[7] the selective crossbreeding of Spanish ewes with imported rams at several different periods, so that its characteristic fine wool was not fully developed until the 15th century or even later.[2]: 40  The first theory accepts that the breed was improved by later importation of North African rams and the second accepts an initial stock of North African sheep related to types from Asia Minor, and both claim an early date and largely North African origin for the Merino breed.[4]: 4, 34 \r\n\r\nSheep were relatively unimportant in the Islamic Caliphate of Córdoba, and there is no record of extensive transhumance before the caliphate\'s fall in the 1030s. The Marinids, when a nomadic Zenata Berber tribe, held extensive sheep flocks in what is now Morocco, and its leaders who formed the Marinid Sultanate militarily intervened in southern Spain, supporting the Emirate of Granada several times in the late 13th and early 14th centuries.[8][9] Although they may possibly have brought new breeds of sheep into Spain,[10] there is no definite evidence that the Marinids did bring extensive flocks to Spain. As the Marinids arrived as an intervening military force, they were hardly in a position to protect extensive flocks and practice selective breeding.[6]: 124 \r\n\r\nThe third theory, that the Merino breed was created in Spain over several centuries with a strong Spanish heritage, rather than simply being an existing North African strain that was imported in the 12th century, is supported both by recent genetic studies and the absence of definitive Merino wool before the 15th century. The predominant native sheep breed in Spain from pre-Roman times was the churro, a homogeneous group closely related to European sheep types north of the Pyrenees and bred mainly for meat and milk, with coarse, coloured wool. Churro wool had little value, except where its ewes had been crossed with a fine wool breed from southern Italy in Roman times.[11] Genetic studies have shown that the Merino breed most probably developed by the crossing of churro ewes with a variety of rams of other breeds at different periods, including Italian rams in Roman times, North African rams in the medieval period, and English rams from fine-wool breeds in the 15th century.[12]: 9 [13]\r\n\r\nAlthough Spain exported wool to England, the Low Countries and Italy in the 13th and 14th centuries, it was only used to make cheap cloth. The earliest evidence of fine Spanish wool exports was to Italy in the 1390s and Flanders in the 1420s, although in both cases fine English wool was preferred. Spain became noted for its fine wool (spinning count between 60s and 64s) in the late 15th century, and by the mid-16th century its Merino wool was acknowledged to equal that of the finest English wools.[14]: 437 [2]: 39 \r\n\r\nThe earliest documentary evidence for Merino wools in Italy dates to the 1400s, and in the 1420s and 1430s, Merino wools were being mixed with fine English wool in some towns in the Low Countries to produce high quality cloth.[6]: 130 [14]: 438, 455  However, it was only in the mid-16th century that the most expensive grades of cloth could be made entirely from Merino wool, after its quality had improved to equal that of the finest English wools, which were in increasingly short supply at that time.[14]: 431 \r\n\r\nPreserved medieval woollen fabrics from the Low Countries show that, before the 16th century, only the best quality English wools had a fineness of staple comparable to modern Merino wool. The wide range of Spanish wools produced in the 13th and early 14th centuries were mostly used domestically for cheap, coarse and light fabrics, and were not Merino wools.[14]: 436  Later in the 14th century, similar non-Merino wools were exported from the northern Castilian ports of San Sebastián, Santander, and Bilbao to England and the Low Countries to make coarse, cheap cloth.[14]: 34  The quality of Spanish wools exported increased markedly in the late 15th century, as did their price, promoted by the efforts of the monarchs Ferdinand and Isabella to improve quality.[4]: 36 \r\n\r\nSpain built up a virtual monopoly in fine wool exports in the final decades of the 15th century and in the 16th century, creating a substantial source of income for Castile.[2]: 84  In part, this was because most English wool was woven and made into textile goods within England by the 16th century, rather than being exported.[15]\r\n\r\nMany of the Castillian Merino flocks were owned by nobility or the church, although Alfonso X realised that granting the urban elites of the towns of Old Castile and León transhumant rights would create an additional source of royal income and counteract the power of the privileged orders.[4]: 239  During the late 15th, 16th and early 17th century, two-thirds of the sheep migrating annually were held in flocks of less than 100 sheep and very few flocks exceeded 1,000 sheep. By the 18th century, there were fewer small owners, and several owners held flocks of more than 20,000 sheep, but owners of small to moderately sized flocks remained, and the Mesta was never simply a combination of large owners.[4]: 59 \r\n\r\nThe transhumant sheep grazed the southern Spanish plains in winter and the northern highlands in summer. The annual migrations to and from Castile and León, where the sheep were owned and where they had summer pasturage, was organised and controlled by the Mesta along designated sheep-walks, or cañadas reales and arranged for suitable grazing, water and rest stops in these routes, and for shearing when the flocks started their return north.[4]: 28 \r\n\r\nThe three Merino strains that founded the world\'s Merino flocks are the Royal Escurial flocks, the Negretti and the Paula. Among Merino bloodlines stemming from Vermont in the US, three historical studs were highly important: Infantado, Montarcos and Aguires. In recent times, Merino and breeds deriving from Merino stocks have spread worldwide. However, there has been a substantial decline in the numbers of several European Merino breeds, which are now considered to be endangered breeds and are no longer the subject of genetic improvement. In Spain, there are now two populations, the commercial Merino flocks, most common in the province of Extremadura and an "historical" Spanish Merino strain, developed and conserved in a breeding centre near Cordoba. The commercial Merino flocks show considerable genetic diversity, probably because of their cross-breeding with non-Spanish Merino-derived breeds since the 1960s, to create a strain more suitable for meat production.[12]: 3, 8  The historical Spanish strain, bred from animals selected from the main traditional Spanish genetic lines to ensure the conservation of a purebred lineage, exhibits signs of inbreeding.[12]: 9 \r\n\r\n\r\nChampion Merino ram, 1905 Sydney Sheep Show\r\nBefore the 18th century, the export of Merinos from Spain was a crime punishable by death. In the 18th century, small exportation of Merinos from Spain and local sheep were used as the foundation of Merino flocks in other countries. In 1723, some were exported to Sweden, but the first major consignment of Escurials was sent by Charles III of Spain to his cousin, Prince Xavier the Elector of Saxony, in 1765. Further exportation of Escurials to Saxony occurred in 1774, to Hungary in 1775 and to Prussia in 1786. Later in 1786, Louis XVI of France received 366 sheep selected from 10 different cañadas; these founded the stud at the Royal Farm at Rambouillet. In addition to the fine wool breeds mentioned, other breeds derived from Merino stocks were developed to produce mutton, including the French Ile de France and Berrichon du Cher breeds. Merino sheep were also sent to Eastern Europe where their breeding began in Hungary in 1774[12]: 2 \r\n\r\n\r\nEwes in New South Wales\r\nThe Rambouillet stud enjoyed some undisclosed genetic development with some English long-wool genes contributing to the size and wool-type of the French sheep.[16] Through one ram in particular named Emperor – imported to Australia in 1860 by the Peppin brothers of Wanganella, New South Wales – the Rambouillet stud had an enormous influence on the development of the Australian Merino.[citation needed]\r\n\r\nSir Joseph Banks procured two rams and four ewes in 1787 by way of Portugal, and in 1792 purchased 40 Negrettis for King George III to found the royal flock at Kew. In 1808, 2000 Paulas were imported.\r\n\r\n\r\nA stud Merino ram that has been branded on his horn\r\nThe King of Spain also gave some Escurials to the Dutch government in 1790; these thrived in the Dutch Cape Colony (South Africa). In 1788, John MacArthur, from the Clan Arthur (or MacArthur Clan) introduced Merinos to Australia from South Africa.\r\n\r\nFrom 1765, the Germans in Saxony crossed the Spanish Merino with the Saxon sheep[17] to develop a dense, fine type of Merino (spinning count between 70s and 80s) adapted to its new environment. From 1778, the Saxon breeding center was operated in the Vorwerk Rennersdorf. It was administered from 1796 by Johann Gottfried Nake, who developed scientific crossing methods to further improve the Saxon Merino. By 1802, the region had four million Saxon Merino sheep, and was becoming the centre for stud Merino breeding, and German wool was considered to be the finest in the world.\r\n\r\nIn 1802, Colonel David Humphreys, United States Ambassador to Spain, introduced the Vermont strain into North America with an importation of 21 rams and 70 ewes from Portugal and a further importation of 100 Infantado Merinos in 1808. The British embargo on wool and wool clothing exports to the U.S. before the 1812 British/U.S. war led to a "Merino Craze", with William Jarvis of the Diplomatic Corps importing at least 3,500[18] sheep between 1809 and 1811 through Portugal.\r\n\r\nThe Napoleonic Wars (1793–1813) almost destroyed the Spanish Merino industry. The old cabañas or flocks were dispersed or slaughtered. From 1810 onwards, the Merino scene shifted to Germany, the United States and Australia. Saxony lifted the export ban on living Merinos after the Napoleonic wars. Highly decorated Saxon sheep breeder Nake from Rennersdorf had established a private sheep farm in Kleindrebnitz in 1811, but ironically after the success of his sheep export to Australia and Russia, failed with his own undertaking.\r\n\r\nUnited States Merinos\r\nMerino sheep were introduced to Vermont in 1812. This ultimately resulted in a boom-bust cycle for wool, which reached a price of 57 cents/pound in 1835. By 1837, 1,000,000 sheep were in the state. The price of wool dropped to 25 cents/pound in the late 1840s. The state could not withstand more efficient competition from the other states, and sheep-raising in Vermont collapsed.[19] Many sheep farmers from Vermont migrated with their flocks to other parts of the United States.\r\n\r\nAustralian Merinos\r\n\r\nMerino ewe judging\r\nEarly history\r\nAbout 70 native sheep, suitable only for mutton, survived the journey to Australia with the First Fleet, which arrived in late January 1788. A few months later, the flock had dwindled to just 28 ewes and one lamb.[20]\r\n\r\nIn 1797, Governor King, Colonel Patterson, Captain Waterhouse and Kent purchased sheep in Cape Town from the widow of Colonel Gordon, commander of the Dutch garrison. When Waterhouse landed in Sydney, he sold his sheep to Captain John MacArthur, Samuel Marsden and Captain William Cox.[21] Although the early origin of the Australian Merino breed involved different stocks from Cape Colony, England, Saxony, France and America and although different Merino strains are bred in Australia, the Australian Merino populations are genetically similar and distinct from all other Merino populations, indicating a common history after they arrived in Australia.[12]: 10 \r\n\r\nJohn and Elizabeth Macarthur\r\nBy 1810, Australia had 33,818 sheep.[22] John MacArthur (who had been sent back from Australia to England following a duel with Colonel Patterson) brought seven rams and one ewe from the first dispersal sale of King George III stud in 1804. The next year, MacArthur and the sheep returned to Australia, Macarthur to reunite with his wife Elizabeth, who had been developing their flock in his absence. Macarthur is considered the father of the Australian Merino industry; in the long term, however, his sheep had very little influence on the development of the Australian Merino.\r\n\r\nMacarthur pioneered the introduction of Saxon Merinos with importation from the Electoral flock in 1812. The first Australian wool boom occurred in 1813, when the Great Dividing Range was crossed. During the 1820s, interest in Merino sheep increased. MacArthur showed and sold 39 rams in October 1820, grossing £510/16/5.[23] In 1823, at the first sheep show held in Australia, a gold medal was awarded to W. Riley (\'Raby\') for importing the most Saxons; W. Riley also imported cashmere goats into Australia.\r\n\r\n\r\nImported Vermont-type sheep, Australia\r\nEliza and John Furlong\r\nTwo of Eliza Furlong\'s (sometimes spelt Forlong or Forlonge) children had died from consumption, and she was determined to protect her surviving two sons by living in a warm climate and finding them outdoor occupations. Her husband John, a Scottish businessman, had noticed wool from the Electorate of Saxony sold for much higher prices than wools from New South Wales. The family decided on sheep farming in Australia for their new business. In 1826, Eliza walked over 1,500 miles (2,400 km) through villages in Saxony and Prussia, selecting fine Saxon Merino sheep. Her sons, Andrew and William, studied sheep breeding and wool classing. The selected 100 sheep were driven (herded) to Hamburg and shipped to Hull. Thence, Eliza and her two sons walked them to Scotland for shipment to Australia. In Scotland, the new Australia Company, which was established in Britain, bought the first shipment, so Eliza repeated the journey twice more. Each time, she gathered a flock for her sons. The sons were sent to New South Wales, but were persuaded to stop in Tasmania with the sheep, where Eliza and her husband joined them.[24]\r\n\r\nThe Age in 1908 described Eliza Furlong as someone who had \'notably stimulated and largely helped to mould the prosperity of an entire state and her name deserved to live for all time in our history\' (reprinted Wagga Wagga Daily Advertiser 27 January 1989).[25]\r\n\r\nJohn Murray\r\nMain article: John Murray (sheep breeder)\r\nThere were nearly 2 million sheep in Australia by 1830, and by 1836, Australia had won the wool trade war with Germany, mainly because of Germany\'s preoccupation with fineness. German manufacturers commenced importing Australian wool in 1845.[26] In 1841, at Mount Crawford in South Australia, Murray established a flock of Camden-blood ewes mated to Tasmanian rams. To broaden the wool and give the animals some size, it is thought some English Leicester blood was introduced. The resultant sheep were the foundation of many South Australian strong wool studs. His brother Alexander Borthwick Murray was also a highly successful breeder of Merino sheep.[27]\r\n\r\nThe Peppin brothers\r\nSee also: Peppin Merino\r\nThe Peppin brothers took a different approach to producing a hardier, longer-stapled, broader wool sheep. After purchasing Wanganella Station in the Riverina, they selected 200 station-bred ewes that thrived under local conditions and purchased 100 South Australian ewes bred at Cannally that were sired by an imported Rambouillet ram. The Peppin brothers mainly used Saxon and Rambouillet rams, importing four Rambouillet rams in 1860.[28] One of these, Emperor, cut an 11.4 lb (5.1 kg clean) wool clip. They ran some Lincoln ewes, but their introduction into the flock is undocumented. In 1865, George Merriman founded the fine wool Merino Ravensworth Stud, part of which is the Merryville Stud at Yass, New South Wales.[20] His son, Sir Walter Merriman, incorporated Peppin bloodlines into his breeding program.[29]\r\n\r\nVermont Merinos in Australia\r\nIn the 1880s, Vermont rams were imported into Australia from the U.S.; since many Australian stud men believed these sheep would improve wool cuts, their use spread rapidly. Unfortunately, the fleece weight was high, but the clean yield low, the greater grease content increased the risk of fly strike, they had lower uneven wool quality, and lower lambing percentages. Their introduction had a devastating effect on many famous fine-wool studs.\r\n\r\n\r\nSuperfine wool Merino ewes and lambs, Walcha, NSW\r\nIn 1889, while Australian studs were being devastated by the imported Vermont rams, several U.S. Merino breeders formed the Rambouillet Association to prevent the destruction of the Rambouillet line in the U.S. As of 1989, an estimated 50% of the sheep on the U.S. western ranges are of Rambouillet blood.[18]\r\n\r\nThe federation drought (1901–1903) reduced the number of Australian sheep from 72 to 53 million and ended the Vermont era. The Peppin and Murray blood strain became dominant in the pastoral and wheat zones of Australia.\r\n\r\nHigh price records\r\nThe world record price for a ram was A$450,000 for JC&S Lustre 53, which sold at the 1988 Merino ram sale at Adelaide, South Australia.[30] In 2008, an Australian Merino ewe was sold for A$14,000 at the Sheep Show and auction held at Dubbo, New South Wales.[31]\r\n\r\nEvents\r\n\r\nNew England Tablelands superfine Merino in snow\r\nThe New England Merino Field Days, which display local studs, wool, and sheep, are held during January in even numbered years in and around the Walcha, New South Wales district.[32] The Annual Wool Fashion Awards, which showcase the use of Merino wool by fashion designers, are hosted by the city of Armidale, New South Wales in March each year.[33]\r\n\r\nAnimal welfare developments\r\nIn Australia, mulesing of Merino sheep is a common practice to reduce the incidence of flystrike. It has been attacked by animal rights and animal welfare activists, with PETA running a campaign against the practice in 2004. The PETA campaign targeted U.S. consumers by using graphic billboards in New York City. PETA threatened U.S. manufacturers with television advertisements showing their companies\' support of mulesing. Fashion retailers including Abercrombie & Fitch Co., Gap Inc and Nordstrom and George (UK) stopped stocking Australian Merino wool products.[34]\r\n\r\nNew Zealand banned mulesing on 1 October 2018.[35]\r\n\r\nCharacteristics\r\n\r\nAustralian Merino wool fibre (top) compared to a human hair (bottom), imaged using scanning electron microscopy\r\nMerino is an excellent forager and very adaptable. It is bred predominantly for its wool,[36] and its carcass size is generally smaller than that of sheep bred for meat. South African Meat Merino (SAMM), American Rambouillet and German Merinofleischschaf[37] have been bred to balance wool production and carcass quality.\r\n\r\nMerino has been domesticated and bred in ways that would not allow them to survive well without regular shearing by their owners. They must be shorn at least once a year because their wool does not stop growing. If this is neglected, the overabundance of wool can cause heat stress, mobility issues, and even blindness. To mitigate parasites, Mulesing is carried out. It is a painful procedure where skin is cut away without pain relief to stop the buildup of parasites in the folds of the skin. [38]\r\n\r\nWool qualities\r\n\r\nStructure of a Merino wool fibre\r\nMerino wool is fine and soft. Staples are commonly 65–100 mm (2.6–3.9 in) long. A Saxon Merino produces 3–6 kg (6.6–13.2 lb) of greasy wool a year, while a good quality Peppin Merino ram produces up to 18 kg (40 lb). Merino wool is generally less than 24 micron (μm) in diameter. Basic Merino types include: strong (broad) wool (23 - 24.5 μm), medium wool (21 - 22.9 μm), fine (18.6 - 20.9  μm), superfine (15 – 18.5 μm) and ultra-fine (11.5 - 15 μm).[39]'),
	(2, 'QDBEAT6hzuL0eKRqfY0OPniROVjs9SFp3h9t33AC', 'Văn bản tóm tắt - 2025-08-11 14:28:05', NULL, 'text', '2025-08-11 14:28:05', 'SELECT * FROM summaries;\r\nSELECT * FROM sessions;\r\nSELECT * FROM documents;\r\nSELECT * FROM guest_documents;');

-- Dumping structure for table summarease.guest_summaries
CREATE TABLE IF NOT EXISTS `guest_summaries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint unsigned NOT NULL,
  `summary_text` longtext,
  `summary_ratio` float DEFAULT '0.2',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `document_id` (`document_id`),
  CONSTRAINT `guest_summaries_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `guest_documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table summarease.guest_summaries: ~0 rows (approximately)

-- Dumping structure for table summarease.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table summarease.jobs: ~0 rows (approximately)

-- Dumping structure for table summarease.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table summarease.job_batches: ~0 rows (approximately)

-- Dumping structure for table summarease.keywords
CREATE TABLE IF NOT EXISTS `keywords` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `summary_id` bigint unsigned NOT NULL,
  `keyword_text` varchar(255) NOT NULL,
  `weight` float DEFAULT '0',
  `is_auto_generated` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `summary_id` (`summary_id`),
  CONSTRAINT `keywords_ibfk_1` FOREIGN KEY (`summary_id`) REFERENCES `summaries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=497 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table summarease.keywords: ~438 rows (approximately)
REPLACE INTO `keywords` (`id`, `summary_id`, `keyword_text`, `weight`, `is_auto_generated`) VALUES
	(1, 1, 'ngôn ngữ tự nhiên', 0.85, 1),
	(2, 1, 'AI', 0.7, 1),
	(3, 2, 'sheep', 1, 1),
	(4, 2, 'the', 0.9, 1),
	(5, 2, 'and', 0.8, 1),
	(6, 2, 'are', 0.7, 1),
	(11, 4, 'sheep', 1, 1),
	(12, 4, 'the', 0.9, 1),
	(13, 4, 'and', 0.8, 1),
	(14, 4, 'are', 0.7, 1),
	(15, 5, 'sheep', 1, 1),
	(16, 5, 'the', 0.9, 1),
	(17, 5, 'and', 0.8, 1),
	(18, 5, 'are', 0.7, 1),
	(19, 6, 'sheep', 1, 1),
	(20, 6, 'the', 0.9, 1),
	(21, 6, 'and', 0.8, 1),
	(22, 6, 'are', 0.7, 1),
	(23, 7, 'sheep', 1, 1),
	(24, 7, 'the', 0.9, 1),
	(25, 7, 'and', 0.8, 1),
	(26, 7, 'are', 0.7, 1),
	(27, 9, 'limbus', 1, 1),
	(28, 10, 'limbus', 1, 1),
	(29, 11, 'tóm', 1, 1),
	(30, 11, 'tắt', 0.9, 1),
	(31, 11, 'bản', 0.8, 1),
	(32, 11, 'hoặc', 0.7, 1),
	(33, 11, 'giai', 0.6, 1),
	(34, 12, 'tóm', 1, 1),
	(35, 12, 'tắt', 0.9, 1),
	(36, 12, 'bản', 0.8, 1),
	(37, 12, 'hoặc', 0.7, 1),
	(38, 12, 'giai', 0.6, 1),
	(39, 13, 'tóm', 1, 1),
	(40, 13, 'tắt', 0.9, 1),
	(41, 13, 'bản', 0.8, 1),
	(42, 13, 'hoặc', 0.7, 1),
	(43, 13, 'giai', 0.6, 1),
	(44, 14, 'tóm', 1, 1),
	(45, 14, 'tắt', 0.9, 1),
	(46, 14, 'bản', 0.8, 1),
	(47, 14, 'hoặc', 0.7, 1),
	(48, 14, 'giai', 0.6, 1),
	(49, 15, 'tóm', 1, 1),
	(50, 15, 'tắt', 0.9, 1),
	(51, 15, 'bản', 0.8, 1),
	(52, 15, 'hoặc', 0.7, 1),
	(53, 15, 'giai', 0.6, 1),
	(54, 16, 'tóm', 1, 1),
	(55, 16, 'tắt', 0.9, 1),
	(56, 16, 'bản', 0.8, 1),
	(57, 16, 'hoặc', 0.7, 1),
	(58, 16, 'giai', 0.6, 1),
	(59, 17, 'tóm', 1, 1),
	(60, 17, 'tắt', 0.9, 1),
	(61, 17, 'bản', 0.8, 1),
	(62, 17, 'hoặc', 0.7, 1),
	(63, 17, 'giai', 0.6, 1),
	(64, 18, 'tóm', 1, 1),
	(65, 18, 'tắt', 0.9, 1),
	(66, 18, 'bản', 0.8, 1),
	(67, 18, 'hoặc', 0.7, 1),
	(68, 18, 'giai', 0.6, 1),
	(69, 19, 'tóm', 1, 1),
	(70, 19, 'tắt', 0.9, 1),
	(71, 19, 'bản', 0.8, 1),
	(72, 19, 'hoặc', 0.7, 1),
	(73, 19, 'giai', 0.6, 1),
	(74, 20, 'tóm', 1, 1),
	(75, 20, 'tắt', 0.9, 1),
	(76, 20, 'bản', 0.8, 1),
	(77, 20, 'hoặc', 0.7, 1),
	(78, 20, 'giai', 0.6, 1),
	(79, 22, 'what', 1, 1),
	(80, 22, 'the', 0.9, 1),
	(81, 22, 'meaning', 0.8, 1),
	(82, 22, 'life', 0.7, 1),
	(83, 23, 'what', 1, 1),
	(84, 23, 'the', 0.9, 1),
	(85, 23, 'meaning', 0.8, 1),
	(86, 23, 'life', 0.7, 1),
	(87, 24, 'sheep', 1, 1),
	(88, 25, 'sheep', 1, 1),
	(89, 25, 'the', 0.9, 1),
	(90, 25, 'and', 0.8, 1),
	(91, 25, 'are', 0.7, 1),
	(92, 26, 'sheep', 1, 1),
	(93, 26, 'the', 0.9, 1),
	(94, 26, 'and', 0.8, 1),
	(95, 26, 'are', 0.7, 1),
	(96, 27, 'sheep', 1, 1),
	(97, 27, 'the', 0.9, 1),
	(98, 27, 'and', 0.8, 1),
	(99, 27, 'are', 0.7, 1),
	(100, 28, 'lamb', 1, 1),
	(101, 29, 'the', 1, 1),
	(102, 29, 'submit', 0.9, 1),
	(103, 29, 'button', 0.8, 1),
	(104, 29, 'form', 0.7, 1),
	(105, 29, 'with', 0.6, 1),
	(106, 30, 'sheep', 1, 1),
	(107, 30, 'the', 0.9, 1),
	(108, 30, 'and', 0.8, 1),
	(109, 30, 'are', 0.7, 1),
	(110, 31, 'sheep', 1, 1),
	(111, 31, 'the', 0.9, 1),
	(112, 31, 'and', 0.8, 1),
	(113, 31, 'are', 0.7, 1),
	(114, 32, 'sheep', 1, 1),
	(115, 32, 'the', 0.9, 1),
	(116, 32, 'and', 0.8, 1),
	(117, 32, 'are', 0.7, 1),
	(118, 33, 'sheep', 1, 1),
	(119, 33, 'the', 0.9, 1),
	(120, 33, 'and', 0.8, 1),
	(121, 33, 'are', 0.7, 1),
	(122, 34, 'sheep', 1, 1),
	(123, 34, 'the', 0.9, 1),
	(124, 34, 'and', 0.8, 1),
	(125, 34, 'are', 0.7, 1),
	(126, 35, 'sheep', 1, 1),
	(127, 35, 'the', 0.9, 1),
	(128, 35, 'and', 0.8, 1),
	(129, 35, 'are', 0.7, 1),
	(130, 36, 'sheep', 1, 1),
	(131, 36, 'the', 0.9, 1),
	(132, 36, 'and', 0.8, 1),
	(133, 36, 'are', 0.7, 1),
	(134, 37, 'sheep', 1, 1),
	(135, 37, 'the', 0.9, 1),
	(136, 37, 'and', 0.8, 1),
	(137, 37, 'are', 0.7, 1),
	(138, 38, 'sheep', 1, 1),
	(139, 38, 'the', 0.9, 1),
	(140, 38, 'and', 0.8, 1),
	(141, 38, 'are', 0.7, 1),
	(142, 39, 'polled', 1, 1),
	(143, 39, 'both', 0.9, 1),
	(144, 39, 'and', 0.8, 1),
	(145, 39, 'livestock', 0.7, 1),
	(146, 39, 'are', 0.6, 1),
	(147, 40, 'polled', 1, 1),
	(148, 40, 'both', 0.9, 1),
	(149, 40, 'and', 0.8, 1),
	(150, 40, 'livestock', 0.7, 1),
	(151, 40, 'are', 0.6, 1),
	(152, 41, 'polled', 1, 1),
	(153, 41, 'both', 0.9, 1),
	(154, 41, 'and', 0.8, 1),
	(155, 41, 'livestock', 0.7, 1),
	(156, 41, 'are', 0.6, 1),
	(157, 42, 'polled', 1, 1),
	(158, 42, 'both', 0.9, 1),
	(159, 42, 'livestock', 0.8, 1),
	(160, 42, 'that', 0.7, 1),
	(161, 42, 'horned', 0.6, 1),
	(162, 43, 'polled', 1, 1),
	(163, 43, 'both', 0.9, 1),
	(164, 43, 'livestock', 0.8, 1),
	(165, 43, 'that', 0.7, 1),
	(166, 43, 'horned', 0.6, 1),
	(167, 44, 'polled', 1, 1),
	(168, 44, 'both', 0.9, 1),
	(169, 44, 'livestock', 0.8, 1),
	(170, 44, 'that', 0.7, 1),
	(171, 44, 'horned', 0.6, 1),
	(172, 45, 'lamb', 1, 1),
	(173, 45, 'the', 0.9, 1),
	(174, 45, 'used', 0.8, 1),
	(175, 45, 'young', 0.7, 1),
	(176, 45, 'sheep', 0.6, 1),
	(177, 46, 'and', 1, 1),
	(178, 46, 'n\'t', 0.9, 1),
	(179, 46, 'the', 0.8, 1),
	(180, 46, 'today', 0.7, 1),
	(181, 46, 'since', 0.6, 1),
	(182, 47, 'sheep', 1, 1),
	(183, 47, 'the', 0.9, 1),
	(184, 47, 'and', 0.8, 1),
	(185, 47, 'are', 0.7, 1),
	(186, 48, 'sheep', 1, 1),
	(187, 48, 'the', 0.9, 1),
	(188, 48, 'and', 0.8, 1),
	(189, 48, 'are', 0.7, 1),
	(190, 49, 'sheep', 1, 1),
	(191, 49, 'the', 0.9, 1),
	(192, 49, 'and', 0.8, 1),
	(193, 49, 'are', 0.7, 1),
	(194, 50, 'sheep', 1, 1),
	(195, 50, 'the', 0.9, 1),
	(196, 50, 'and', 0.8, 1),
	(197, 50, 'are', 0.7, 1),
	(198, 51, 'sheep', 1, 1),
	(199, 51, 'the', 0.9, 1),
	(200, 51, 'and', 0.8, 1),
	(201, 51, 'are', 0.7, 1),
	(202, 52, 'sheep', 1, 1),
	(203, 52, 'the', 0.9, 1),
	(204, 52, 'and', 0.8, 1),
	(205, 52, 'are', 0.7, 1),
	(206, 53, 'sheep', 1, 1),
	(207, 53, 'the', 0.9, 1),
	(208, 53, 'and', 0.8, 1),
	(209, 53, 'are', 0.7, 1),
	(210, 54, 'sheep', 1, 1),
	(211, 54, 'the', 0.9, 1),
	(212, 54, 'and', 0.8, 1),
	(213, 54, 'are', 0.7, 1),
	(214, 55, 'sheep', 1, 1),
	(215, 55, 'the', 0.9, 1),
	(216, 55, 'and', 0.8, 1),
	(217, 55, 'are', 0.7, 1),
	(218, 56, 'sheep', 1, 1),
	(219, 56, 'the', 0.9, 1),
	(220, 56, 'and', 0.8, 1),
	(221, 56, 'are', 0.7, 1),
	(222, 57, 'sheep', 1, 1),
	(223, 57, 'the', 0.9, 1),
	(224, 57, 'and', 0.8, 1),
	(225, 57, 'are', 0.7, 1),
	(226, 58, 'sheep', 1, 1),
	(227, 58, 'the', 0.9, 1),
	(228, 58, 'and', 0.8, 1),
	(229, 58, 'are', 0.7, 1),
	(230, 59, 'sheep', 1, 1),
	(231, 59, 'the', 0.9, 1),
	(232, 59, 'and', 0.8, 1),
	(233, 59, 'are', 0.7, 1),
	(234, 60, 'sheep', 1, 1),
	(235, 60, 'the', 0.9, 1),
	(236, 60, 'and', 0.8, 1),
	(237, 60, 'are', 0.7, 1),
	(238, 61, 'sheep', 1, 1),
	(239, 61, 'the', 0.9, 1),
	(240, 61, 'and', 0.8, 1),
	(241, 61, 'are', 0.7, 1),
	(242, 62, 'sheep', 1, 1),
	(243, 62, 'the', 0.9, 1),
	(244, 62, 'and', 0.8, 1),
	(245, 62, 'are', 0.7, 1),
	(246, 63, 'sheep', 1, 1),
	(247, 63, 'the', 0.9, 1),
	(248, 63, 'and', 0.8, 1),
	(249, 63, 'are', 0.7, 1),
	(250, 64, 'sheep', 1, 1),
	(251, 64, 'the', 0.9, 1),
	(252, 64, 'and', 0.8, 1),
	(253, 64, 'are', 0.7, 1),
	(254, 65, 'sheep', 1, 1),
	(255, 65, 'the', 0.9, 1),
	(256, 65, 'and', 0.8, 1),
	(257, 65, 'are', 0.7, 1),
	(258, 66, 'sheep', 1, 1),
	(259, 66, 'the', 0.9, 1),
	(260, 66, 'and', 0.8, 1),
	(261, 66, 'are', 0.7, 1),
	(262, 67, 'sheep', 1, 1),
	(263, 67, 'the', 0.9, 1),
	(264, 67, 'and', 0.8, 1),
	(265, 67, 'are', 0.7, 1),
	(266, 68, 'sheep', 1, 1),
	(267, 68, 'the', 0.9, 1),
	(268, 68, 'and', 0.8, 1),
	(269, 68, 'are', 0.7, 1),
	(270, 69, 'sheep', 1, 1),
	(271, 69, 'the', 0.9, 1),
	(272, 69, 'and', 0.8, 1),
	(273, 69, 'are', 0.7, 1),
	(274, 70, 'sheep', 1, 1),
	(275, 70, 'the', 0.9, 1),
	(276, 70, 'and', 0.8, 1),
	(277, 70, 'are', 0.7, 1),
	(278, 71, 'sheep', 1, 1),
	(279, 71, 'the', 0.9, 1),
	(280, 71, 'and', 0.8, 1),
	(281, 71, 'are', 0.7, 1),
	(282, 72, 'sheep', 1, 1),
	(283, 72, 'the', 0.9, 1),
	(284, 72, 'and', 0.8, 1),
	(285, 72, 'are', 0.7, 1),
	(286, 73, 'sheep', 1, 1),
	(287, 73, 'the', 0.9, 1),
	(288, 73, 'and', 0.8, 1),
	(289, 73, 'are', 0.7, 1),
	(290, 74, 'sheep', 1, 1),
	(291, 74, 'the', 0.9, 1),
	(292, 74, 'and', 0.8, 1),
	(293, 74, 'are', 0.7, 1),
	(294, 75, 'sheep', 1, 1),
	(295, 75, 'the', 0.9, 1),
	(296, 75, 'and', 0.8, 1),
	(297, 75, 'are', 0.7, 1),
	(298, 76, 'sheep', 1, 1),
	(299, 76, 'the', 0.9, 1),
	(300, 76, 'and', 0.8, 1),
	(301, 76, 'are', 0.7, 1),
	(302, 77, 'sheep', 1, 1),
	(303, 77, 'the', 0.9, 1),
	(304, 77, 'and', 0.8, 1),
	(305, 77, 'are', 0.7, 1),
	(306, 78, 'sheep', 1, 1),
	(307, 78, 'the', 0.9, 1),
	(308, 78, 'and', 0.8, 1),
	(309, 78, 'are', 0.7, 1),
	(310, 79, 'sheep', 1, 1),
	(311, 79, 'the', 0.9, 1),
	(312, 79, 'and', 0.8, 1),
	(313, 79, 'are', 0.7, 1),
	(314, 80, 'sheep', 1, 1),
	(315, 80, 'the', 0.9, 1),
	(316, 80, 'and', 0.8, 1),
	(317, 80, 'are', 0.7, 1),
	(318, 81, 'sheep', 1, 1),
	(319, 81, 'the', 0.9, 1),
	(320, 81, 'and', 0.8, 1),
	(321, 81, 'are', 0.7, 1),
	(322, 82, 'sheep', 1, 1),
	(323, 82, 'the', 0.9, 1),
	(324, 82, 'and', 0.8, 1),
	(325, 82, 'are', 0.7, 1),
	(326, 83, 'sheep', 1, 1),
	(327, 83, 'the', 0.9, 1),
	(328, 83, 'and', 0.8, 1),
	(329, 83, 'are', 0.7, 1),
	(330, 84, 'sheep', 1, 1),
	(331, 84, 'the', 0.9, 1),
	(332, 84, 'and', 0.8, 1),
	(333, 84, 'are', 0.7, 1),
	(334, 85, 'sheep', 1, 1),
	(335, 85, 'the', 0.9, 1),
	(336, 85, 'and', 0.8, 1),
	(337, 85, 'are', 0.7, 1),
	(338, 86, 'sheep', 1, 1),
	(339, 86, 'the', 0.9, 1),
	(340, 86, 'and', 0.8, 1),
	(341, 86, 'are', 0.7, 1),
	(342, 87, 'sheep', 1, 1),
	(343, 87, 'the', 0.9, 1),
	(344, 87, 'and', 0.8, 1),
	(345, 87, 'are', 0.7, 1),
	(346, 88, 'sheep', 1, 1),
	(347, 88, 'the', 0.9, 1),
	(348, 88, 'and', 0.8, 1),
	(349, 88, 'are', 0.7, 1),
	(350, 89, 'sheep', 1, 1),
	(351, 89, 'the', 0.9, 1),
	(352, 89, 'and', 0.8, 1),
	(353, 89, 'are', 0.7, 1),
	(354, 90, 'sheep', 1, 1),
	(355, 90, 'the', 0.9, 1),
	(356, 90, 'and', 0.8, 1),
	(357, 90, 'are', 0.7, 1),
	(358, 91, 'sheep', 1, 1),
	(359, 91, 'the', 0.9, 1),
	(360, 91, 'and', 0.8, 1),
	(361, 91, 'are', 0.7, 1),
	(362, 92, 'sheep', 1, 1),
	(363, 92, 'the', 0.9, 1),
	(364, 92, 'and', 0.8, 1),
	(365, 92, 'are', 0.7, 1),
	(366, 93, 'sheep', 1, 1),
	(367, 93, 'the', 0.9, 1),
	(368, 93, 'and', 0.8, 1),
	(369, 93, 'are', 0.7, 1),
	(370, 94, 'sheep', 1, 1),
	(371, 94, 'the', 0.9, 1),
	(372, 94, 'and', 0.8, 1),
	(373, 94, 'are', 0.7, 1),
	(374, 95, 'sheep', 1, 1),
	(375, 95, 'the', 0.9, 1),
	(376, 95, 'and', 0.8, 1),
	(377, 95, 'are', 0.7, 1),
	(378, 96, 'the', 1, 1),
	(379, 96, 'suffolk', 0.9, 1),
	(380, 96, '923', 0.8, 1),
	(381, 96, 'and', 0.7, 1),
	(382, 97, 'the', 1, 1),
	(383, 97, 'suffolk', 0.9, 1),
	(384, 97, '923', 0.8, 1),
	(385, 97, 'and', 0.7, 1),
	(386, 98, 'the', 1, 1),
	(387, 98, 'suffolk', 0.9, 1),
	(388, 98, '923', 0.8, 1),
	(389, 98, 'and', 0.7, 1),
	(390, 99, 'the', 1, 1),
	(391, 99, 'suffolk', 0.9, 1),
	(392, 99, '923', 0.8, 1),
	(393, 99, 'and', 0.7, 1),
	(394, 100, 'the', 1, 1),
	(395, 100, 'suffolk', 0.9, 1),
	(396, 100, '923', 0.8, 1),
	(397, 100, 'and', 0.7, 1),
	(398, 101, 'the', 1, 1),
	(399, 101, 'suffolk', 0.9, 1),
	(400, 101, '923', 0.8, 1),
	(401, 101, 'and', 0.7, 1),
	(402, 102, 'the', 1, 1),
	(403, 102, 'sheep', 0.9, 1),
	(404, 102, 'and', 0.8, 1),
	(405, 102, 'are', 0.7, 1),
	(406, 103, 'the', 1, 1),
	(407, 103, 'sheep', 0.9, 1),
	(408, 103, 'and', 0.8, 1),
	(409, 103, 'are', 0.7, 1),
	(410, 104, 'the', 1, 1),
	(411, 104, 'sheep', 0.9, 1),
	(412, 104, 'and', 0.8, 1),
	(413, 104, 'are', 0.7, 1),
	(414, 105, 'the', 1, 1),
	(415, 105, 'sheep', 0.9, 1),
	(416, 105, 'and', 0.8, 1),
	(417, 105, 'are', 0.7, 1),
	(418, 106, 'the', 1, 1),
	(419, 106, 'sheep', 0.9, 1),
	(420, 106, 'and', 0.8, 1),
	(421, 106, 'are', 0.7, 1),
	(422, 107, 'hãy', 1, 1),
	(423, 107, 'nhập', 0.9, 1),
	(424, 107, 'văn', 0.8, 1),
	(425, 107, 'bản', 0.7, 1),
	(426, 107, 'cần', 0.6, 1),
	(427, 108, 'hãy', 1, 1),
	(428, 108, 'nhập', 0.9, 1),
	(429, 108, 'văn', 0.8, 1),
	(430, 108, 'bản', 0.7, 1),
	(431, 108, 'cần', 0.6, 1),
	(432, 109, 'hãy', 1, 1),
	(433, 109, 'nhập', 0.9, 1),
	(434, 109, 'văn', 0.8, 1),
	(435, 109, 'bản', 0.7, 1),
	(436, 109, 'cần', 0.6, 1),
	(437, 110, 'placeholder=', 1, 1),
	(438, 110, 'hãy', 0.9, 1),
	(439, 110, 'nhập', 0.8, 1),
	(440, 110, 'văn', 0.7, 1),
	(441, 110, 'bản', 0.6, 1),
	(442, 111, 'and', 1, 1),
	(443, 111, 'for', 0.9, 1),
	(444, 111, 'instructions', 0.8, 1),
	(445, 111, 'prompt', 0.7, 1),
	(446, 111, 'pattern', 0.6, 1),
	(447, 112, 'and', 1, 1),
	(448, 112, 'for', 0.9, 1),
	(449, 112, 'instructions', 0.8, 1),
	(450, 112, 'prompt', 0.7, 1),
	(451, 112, 'pattern', 0.6, 1),
	(452, 113, 'and', 1, 1),
	(453, 113, 'for', 0.9, 1),
	(454, 113, 'instructions', 0.8, 1),
	(455, 113, 'prompt', 0.7, 1),
	(456, 113, 'pattern', 0.6, 1),
	(457, 114, 'and', 1, 1),
	(458, 114, 'for', 0.9, 1),
	(459, 114, 'instructions', 0.8, 1),
	(460, 114, 'prompt', 0.7, 1),
	(461, 114, 'pattern', 0.6, 1),
	(462, 115, 'and', 1, 1),
	(463, 115, 'for', 0.9, 1),
	(464, 115, 'instructions', 0.8, 1),
	(465, 115, 'prompt', 0.7, 1),
	(466, 115, 'pattern', 0.6, 1),
	(467, 116, 'and', 1, 1),
	(468, 116, 'for', 0.9, 1),
	(469, 116, 'instructions', 0.8, 1),
	(470, 116, 'prompt', 0.7, 1),
	(471, 116, 'pattern', 0.6, 1),
	(472, 117, 'and', 1, 1),
	(473, 117, 'for', 0.9, 1),
	(474, 117, 'instructions', 0.8, 1),
	(475, 117, 'prompt', 0.7, 1),
	(476, 117, 'pattern', 0.6, 1),
	(477, 118, 'biiihhi', 1, 1),
	(478, 1, 'biiihhi', 1, 1),
	(479, 2, 'biiihhi', 1, 1),
	(481, 4, 'form', 1, 1),
	(482, 4, 'submit', 0.9, 1),
	(483, 4, 'the', 0.8, 1),
	(484, 4, 'button', 0.7, 1),
	(485, 4, 'buttons', 0.6, 1),
	(486, 5, 'shshhshs', 1, 1),
	(487, 6, 'the', 1, 1),
	(488, 6, 'and', 0.9, 1),
	(489, 6, 'breed', 0.8, 1),
	(490, 6, 'valais', 0.7, 1),
	(491, 6, 'with', 0.6, 1),
	(492, 7, 'sheep', 1, 1),
	(493, 7, 'and', 0.9, 1),
	(494, 7, 'the', 0.8, 1),
	(495, 7, 'may', 0.7, 1),
	(496, 7, 'lambs', 0.6, 1);

-- Dumping structure for table summarease.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table summarease.migrations: ~5 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_07_21_135447_add_google_id', 1),
	(5, '2025_07_21_152649_create_personal_access_tokens_table', 2);

-- Dumping structure for table summarease.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table summarease.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table summarease.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT 'admin, user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table summarease.roles: ~2 rows (approximately)
REPLACE INTO `roles` (`id`, `name`) VALUES
	(1, 'admin'),
	(2, 'user');

-- Dumping structure for table summarease.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table summarease.sessions: ~1 rows (approximately)
REPLACE INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('MU7wppocjcUzlTcVqJKuVm78dyqtL7KXBjilSWZO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJbXhyVVdGa2NISmFjMmx2YUVOR2NrZ3ZaSGs1SzBFOVBTSXNJblpoYkhWbElqb2lUR3B4UkZRcmQyTnpibXBLU0RGUWNYQnZlRU0xTm5sb1FsWlFTamd3YURNeU1VcGphMjFaZERKQ1lteFJVVGMxV1hGNVprbHlORUpVVlZkVVJGbFNXVEZOZUZGaVNubHhlR2RETUhoT1pYaGxjakp1UWpCRE5sUjVaRzR5Um1oelFUUkpSekJHYWt4TFNVNVhiSFlyWldVNE1VNW5TRmRNVDJrMVVFdEJZMFpJWm01NE9TOUdlbnBwVlN0aU9GWmlTbkJ2VEdSYVFtMTBTemQzVnl0aVRFRmtXWHBSTkRaaFJtMXFOazVMYTNSMVUwcERObWtyYTNCSVEySXllak5QYWsxemRrZDNXSEU0Ukc5dWIxRmpOMmRuVkZOUlkwNVFkVVJCWW1ScllqVjZRbXRLY1VSSVRGZ3hZMnh0TW5kNFEzWlVZWEpPZWxSMVNGbHJUSGhFTWtWU2RIRXdkV2hIWm1aWk1WQldUV0ZGTHk5b1dXUlJkSGRJUjFwV00zTnBkMDF4YURsNmJTODRSelpyUVVzdlpIaFFaV1JMTDJzM0swdzBXbVZtTVVJNVRrTjRXa1p4VFdreE5sSm5NVFZMY0hkUVozTk1WM0ZSV21sWkwwVktlRkI2YTBsTVNFOXBibUZGUFNJc0ltMWhZeUk2SW1ZNE5UWXlNekV4WVdVd05ETTJNelk0TW1ZMk5UWmhZak0wT1RZMllUYzJNVFZrWWpJNVltVXhPVE5rWXpBek16RXpaalZrT0RCaVkyWTJNemt5TnpNaUxDSjBZV2NpT2lJaWZRPT0=', 1754900423),
	('QDBEAT6hzuL0eKRqfY0OPniROVjs9SFp3h9t33AC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJa3RqU1VKb2VVUlFORW81VEhSbGMwWTFiMlIzVTJjOVBTSXNJblpoYkhWbElqb2llVEF4Wkd0Q00ydzJTMDVPVFhwNGJHTmFhR3hLY1ZkNFRERkRkakpwVEdOMk5UVm5kRzVuWjFnMmFEWjBRa1JQTDNwTFZuZDRTRlF6VlZWaFNqZHFNbGR6WldwbVQzRnlOQzlCYkhjMmJGaEdkbmxEYmtaVk9YSnpaVUpqWkRORGRsWlpVR1F5VEZOSFJETnFhRlJqU2xKR1dIcDFabXRvUW1aMGR6SmxOR2x2ZEZKNFZrUXJSRW92WVZKTVltUnJla3h3Ym5Wb056RlRZMngxVkhONlltSnBTbGRpVURsUFNHTlJRbUpOV1dsbEwyWmhRa1Z5V0ZNeGF5OU9ORGMwTnprNVVrSlBlVlpCZW5oTU5XSnRhV1JvUWpGRFQyNW1ORXhyVEVwbFYwaE9PSEpDTTJ4TlRsTTBjVVEzVjNOcVZXTmlMMHAzTWtZNFNXWmhiWFJGYzNOUWJqRnJXR1JRZWtsdGNqSmFURUUwYUVWMmMxQnljRlZOTURWUFdtaEVSVEZFYTFFeFF6TkNkRU5sU25CTmFHMXlWVzB2TUhWblNDOW5VV0pSVEVGYVJ5dEhjMWxFV21KRk1WTlNVekEwU1RRemEzTjRjMmdyVHl0cmNVeHhhMVpQYkU1d01uUkRkWFp0WW5CUVV5OXpNMjB2VG5RMGRHcHhVMWxrYWtwaVExUXhaVkJQYlVvemNVTXJOSGxXVWtSRU0wYzNja3A2Y1ZCcllUUkxRa042TUVrekwzQXlTMUJ4VkdWMlFWaDVSR3RUU0VwUlRHZFFhMVJCWmxoQlptaE1abFp3VUhRNWMwaE9RV1JFY1ZFMGIxSklia2hoYjNwVVYxRllLMk5YWlZwQmEwcGFOemRUZGswOUlpd2liV0ZqSWpvaU5ETTJNRGxpT1RjMU16VTNaREF4T0dVeE1qQmxNMlJoTTJJNE9HTTRaVFptTVdRNU5UQTROakV6TWpobVlqbGxZMlJqWmpNMlkyVTJaR1kyT1RrMk5pSXNJblJoWnlJNklpSjk=', 1754922486),
	('sz55W4xR2DuJbkljldNKWCXss03C3t8gZeHQNea3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJbEJ6YldwWVdIQkNSRWhITjJWbGFYcEhPR0l2VG5jOVBTSXNJblpoYkhWbElqb2lNMk15YURVclRtSTRPWFY2ZUVkaFIyMWFlRlJ2YVN0VmVuZzFkbXQwVFVoak5VeE5kbTlyYURnMVEzUjFZeko0U2xKak5UaFpNbFkzZHk5RFJEVm1kbGgwVnpWUlQwWktNWGgxUkZSRFpuZGlibmxPYmxFdlJHcDNSSGhXY3pCamRXeExXbk55ZFhsaVJtMHlOSGxRTTBaalJuZHFhRlkyTkc5MWJuSjRNRTFPWlV0bFkwWmlRelpSUjFoeFVWTm1XSGhzZG5aT1YydDNlR0ZNY1haaGNHNW9Xa1Z1ZFhaUE1YWTBiV051UTJoUVFtNVhURFl5YW1WclVXNW1RblEzUXk5RGNVaFJaMUpTU0cxYVZVVjZhVk1yVlVoQ2VXTTVTWEUwUjNodFMybzNNRE5HV1ZsVlZrWTVOMjR6ZHl0NWVVYzVOVlU1YWxkUVpUZzBUakZPY2s0MFoxaG5WRUZ1ZFhnM1MwVlZiWFJzTTNreGQwZFJWMGRtZW5Sa2R6aEJRVGRWYmtOU1VqTkxSbFZDWnpsaWIwRnlLMXB4TjB0MlZrOVFPRU0xUm5OdGJGVnhjRlJoZGtSM05HbGtaSFJtWjBKSmJqaHFXVEpMUTFOVEsycFdTR3hyVTFaSGQzWTRUM1p2UFNJc0ltMWhZeUk2SW1JM01qQTBaamt6WWpReU5XUXdOMkk1TW1WaFpEWXlOakV5Tm1GalpESXpORGxsTm1ObVptTTJNV0U1TmpGalpqbGhOMlJtTURjM09UYzNNMlUwTldZaUxDSjBZV2NpT2lJaWZRPT0=', 1754901774),
	('yn9KZNfOe1Aru7Al5bGcPEct0RoWUmBPVlD2JGwb', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJaXRUY1RKaFpHa3JWalU1TXpSNU1HNUJNRkJ4YW1jOVBTSXNJblpoYkhWbElqb2lTVVozVlVnM1VHMUdPR1JMVHpWUEt6QlBVbVV3UzB4dlptRnZVa0pIYUROelVYRklXSEJVYlZwdWExSlhaRVF5YkZSTGFIRmtaREpDWVd4WWRERlVhMjB6ZDNFeWJYTTJNMDFYYVdwU00xcEhTMEo1Uld0b2NqbG9aVkJXTHprclRXMU9VSGxRU1dodVJIZFZNSFZqU0ZkMFZFY3JNbWh1V25Kc1puZGhOR1JHYUcxcVoycG9aMWxYWkRkRlpFSnVha1J6TjJJeUsyTldjRUpDUjBNMFIxWnZiR1prYm14dlNrWmxTazByWTBSU1ZEa3dhelJoVUhWd1prWjZjV1Z3UXk5TlZtODRTVmhKT0VGRllXWlFlRkl3YUZKTFJIWlNNbHAzVDNSdE1teHZRVmN4WTFkWU1GSlFlSEU0ZUhaWFlpdHJNVGhaU1hOU01XVk9aVU42WVRab1lWTlpkaXMyVEZOTFlVTXZORmswVFZWNWNqaE9VREpJWmxaclpucDVUR1JKZWs1eWFIY3pjMUZUVDJWWlJHbHBWM3BIYlVOaU9UVjZVRVI0WmtWWWFtUnVWR2hSVDFGT1VuSkZPVVZuZDJOcVRtcHZRVVZ2ZW1FclZFbHljMjVSTDNsVFkya3hhM2hOYjBFMGNrSlRNWFo2Y0ZGeVYyRlpja0poYTNsWlZXRXpRMHBLVERJeVp6RjZaVmh4WmpWclRXY3JUV3hwV1RKWFRXNW5WSEowVVdzeFYxTnNZa1JFYW10M2VYaFZkWEZ2Vm5KNlpFcHhjbXBRY0VwWlYycFJPSGhhU0RkaFpuWnRSbWxtYTJkQlFqTnJVSFpSUjFKblFpOTBiVlJTTWpSQldWQlNTSGQ0VkdKaGRWRTVaVXhLV1RWQ1RIUlNOMDFMYW13eGRUaE5iSHBhUlZnd0wxWXlPVEl5YkM5eVQzbFVWWFJaU1hRemRUUjBSVFpVU21kc05uQkxRbWRJY1NzNVdUUnJkMWM0WmxWWmRqWkZkblY1TUdkTkszTmthRXRXWlVsUVVXMW1jMlF4VjNZeFdIaFNXaXN4VERSVFoyRlpaMnRzVXpCM1YwOUVVVEJLTTFsTUszQTBhakkzVDNadFYzcHJXRkY0ZEdOeVUxUllaRFZUTjBSaVprTnhhVmwzTmtWSksxTlhNSG81ZVdaRVUyWllhV2xZZDBwSWREZG5PRkpvTUU5YWVIRmpMMGd2TjJ0SmVtZEthbW94TVZKNVJucEZjVk16VkZSMlYyRjViVWxFZVVSUVVpOTRjWFpPUjB3MlZIQTBiMWQyUTBwM2NucDZka1owYUU5VWEwMDViVzk2Ym1aaFl6YzBTakJDYTIxeFdEWjRXa0ZFYVVGcGRFZDJkVkZLTDFreE9XWmpXbEpVY1RGaWFsUlJhalYyYVcwM1JtMHpNVkZ6WkVSWlNtSTBkRTEzWjNWcVkyaENTa2w0YzI1MU5VcFRaWEYwZEVKbFlWWnFSbWt4VUdjNUwxQmpMMnRSVkdoeFFqaHRTMjlrVDA1WVNEUmpSRVJYYjFsTU1DODBkMWgzUlZscksxaE9PR3hWYkhkRU5HaGtaRXRJZWxGNlNXZDZOSEJHVGtwRWJua3plVzFIWW1sMVJHcDBiMXBEU0dzNFdWQnFkbXQzY2xGdFprbEdZV0k0V1VkVlZtc3JXV3hMT1RsT2NVWXpjelZZVXk5b2N6VktaVkoyYjBsa2NsaE9LMjV1WjNoWlMybzBkR1o2YkdJMldsaEthak5vYjFKUFFrMTZlU3RSWml0WlYxSlBVVEUxZUZWa05EbDJjSEJ2ZG5wM04zUlpaVEl2UkZWd2JHUk5aek16YTNsdlNXNU1kMHBJVjI1NU9ESkxaelpIVDA4eGNraFNMM2REYkNzelduQnRTREYyY2xGQmVHbzVVRmQyT1RkSVdVdHFlSEZHYUVSRFpETldkV3RKSzNJNFpGWlRZekEyZVRnclVVUnhiamxWUVRSUksxa3JWR0k0V1ZBNE1FRjBPWG95Ukd4RlJDOU9WVlJXZUZSWUsycDRUVzlITkRSMldFWmtaRXR5VDNSRGJHeExVRGw0YjBoREsxSkJOMFYzYms5S1pFbElWSEYxVEdrclFtTldRMjFQVWxWTEsxbHhPR2RvVDFGMFRVVmFhM3B0U0ZwWlJDdElVM2c0WjA0MU1HMHlkVFEzVFRoNmJETjZkblpHYjJ4dU9TOXNMMU5NYzJkS2JYWllWV1JOVmt0d09HMWlXa1J1VGtKQlpqRnlhWGxtTkdOb1lXNVRaRVp2U0RaVGVXOTFaVGd6Y205NFlrdENTVVJ2VW13cmNGcFVMMHRQY1ZaMFdtWXZWazVpYUdaSVJGVjVialJhZFVGMFUzVTRTbFo1TkROVFZYQnFRek5CT1hCT2IwWlJNbEJOYlZvNU9UVnFVV1ZXYUZSMU1sWk9UWFZ6T0hWeWJWZFRZVWxPTUVSS2FIRjVVMGgwWWxsdE5HcE1aMjVFWnpsS1pFNWhVRlZFUTJGNFpYbFhNbFZWTUZoWlVUTm5WVVpqVTFkWVFqZEhjVU5HUjFod1MyODBUblJEVG1sVFMyRkhZbFl2YVdFd1dXNWhXVlYxYVVjdldUaGphRUYwWjFjeVRXMXRTVTFXZVRKakwwbFlUMmRDVTFOYWFsYzVkRmRUVGt0elZEYzRURFpYVjJKMllraERaeXMxV0hod2FFTmphSGgzV1dSVmRsSjBhMjVyYWtKSFQwZFBTMDU2VVNzclVIUlRWWEpoWkhoTlNFTnZhV2Q2TldRNU56RkhXVFE1VlUxblVHSXZjWEJPZEdaTlZtaHRNV0ZLVVZOb1VtbHJiV3N4TkRSWk1tZHJNVTlGV204MFFqSnJkeXR4Y1VkdloyWmpPVk0wVERBek1GTTBWV0Z1Y25CSFJGcEZkbFZCTDBkaGNVcEdTSGxUTkVkaVJIUldhemcyY0VVNWNGcG9iVTF3ZW5KT05FZEthVEV4TVdGWE0yOHpNRVJtVDNVNFl6ZEdSV0Y0T1U5MFVXNHdWSEpLWTJFeGRWVktWV3hWUm05VFVsSkJRMjFCTTNJMFVqSnRVbUpNTnpka1dIWXJUbFpYYW13dmFtbDJTRzlyTUZkWlMxUlhTRGhNUmxsaEx6UlhRV2RwZERCbWR6VjVhREZrYTFKa1ozaE1hbGd2ZEdaTk9IaFBRbEV3YVd4d1JGTnJXWEpxY25KR2FXeGliR1YyU1VoMWRrdDBMMkY2YldaclJsTlllazVMYVRSWWNrRTRSeXMzV1ZvNFUzVjFkM3BCV0c4d2RHODFNMkoyV1M5Sll6SkNSM2h1ZGtZemRUaEZPV0psUVZKbU5qSlRWamRSWlc1R09EWlFRekpWT1dONlpVUklkWHBOWXpRMk9FTlBhRkJZUmpKWE1tTk5LMUJOYlUwclExSmliMHRLUjBOMVQxbzFWV2xJT1haTlEybzVRbTFpWkVObmJsYzNSWE50UTBZM1lrcDVOV1U0VlZaWVdEaDVkVGRwU0hsWWNEYzNkRlZyVEZGT1VtaHdUMk5CY0hRMVIycDRiVkZVVFRCVFNqVnpTR2xWY2s1bVl6ZDVkWHB4SzI1SllqUmlXa2RwUVdSSFNqa3hjVTFaVG1oT1UyWm5VRFpETWtVcldYUXpVRFl4Ym5OcmVHVnJVSFFyYlVOTE1sVjBUMk5OYjBwUVMxaHFRV3hpVEVreGFFaFVSRVI0TW1GelFqaFNlWFIxTlcwck5HWlBkVFZqUlRaV1ZEWXhaM2c0YWtSNVdURkdhamxqTUdaNGRUSktjbVIxVkVsdE1sbzRUMkp2Tkdjd1lVRkRTRmxRZGtWeFFVeFJXV05oWkhOa2RHODVhRkJ2YTNjMGNuTm9RbEpEYkd4VVRrYzNUSE53V2tzemRGcEZUR1ZPVW5FeGJtMUJVVmwxV21OaFExaEdWemd5Y1RCalZWTkRSamRUYUhGRk1WaDJURmw2TDJ4TmRGaDFjRmszUzFKSGEwaExhV0ZMVkhaUE1uVktWR1ZUTjJkWVZEWmtkakYyVVROU2FtUldZekZvUTBGUGNHcG5kamRIZG1SamRYSktiVGhzVFRWUFlXRjFVekV5Wnl0R1FsYzRha2RQV1hFMVdrOUZSbGxNWjNSaE1uTXdjV1JtWVhGWVMyRklaSEZ6UjJrM05GQjRXV1ZVVkRkWlVtSTFlWFpsUkZFelNFNUVOR3NyWnpNNVVrbzNhWEZQWkN0SmJtZFJNbkZDUkhvelVYcDZTMmszVkU5bVZXNXlZa0pyUVVOVFRGSkRPRE0zT1hncldYUkROWGQxVlRKa1RISTJaV0ZwTHk5U1dVOVVOVEZRTjNjeVowbERRM1Z3U0dsRVZsWkJSQzlzYUdkNFIzTjVZbWwxTXpGWGVrTm9UekZoTVZsVlExTmtUakZCUjJ4d1pWUm9kbGhHVEc4eE9WUTBjUzkxU0Zack5EUk9WWGh3V0d4M1VYcFFZM0kzUnpWd2RXRjNVRk5MYVVWSVNXUkVWamxzYUVKWGNEQnpXVEJQWWpnNE5uQkdWbUpwZVZaTWRUTkVVWGhSTkRGeFZ6Tk9URzlhUjNOYU5qTnNWWFk0ZEhGelVGVXZaak52VWxacE1tTmthSFY0V2xreGQzaFJRMDVFTDFOM1pTOVVRbEZaT1Zwc2VWUnNMMXAxU1d4bVl6RjJXbGd3VkhRNVlrSjRWWGRGTDI4ME5XcFRZbXRpVlZOaU1GcEVLM3BrTVVWbk5EZDVZekJ1WkU5NFJVMUtlVGRqVTJ4WlptWkJjM0k1UjFNNVUwMVRhSEpMTDNKVVkwVnJWekJ6WnpGeU5WSnNjM2hUWW05V1NqWjZjVVJwYlhKS2JqUnVkSEJQU0U5TVRVZzBSVmNyVWxSUmFYUldlbGRvZFhGRE1tOW5SRFJVVm10WGFFSjFLMlJ6VlZCdU1HOUpVa2R6YkRNd1dHeE9WbTFuUW1vM1ZUSnFSMHd5TmxwWVYzcFFRMkp1YTJweGQwUlhPVUZQV1c1c1FWUmFUSE5zVVVKTFduaHBhekExVkV0MkwzWXhhRkpHU2paSlVUZDRlR1ZCVEhoSFpsUkZNWFJQU0RkSmFtTk1jRU15UjJocU5rdEtTRWxoTVZaWlpWVjZWemhUYUVFdlV5OXpaalpZUjFKNVFUVkllVm8zSzA5dlRqbHpLMjVDTURFelVtRlpiUzlUTTNOVk5uRXhWRU5YZEdOa1RUVlZSVEl6T1VOT09HMWFjelpzTVVKWlVUYzVWRXN6ZUZwaWEyMVViMnBVZEZWU1ZVMDNMMnBsU2xKNFRrOWxRMk5pVFRWSUsyNVdZV1JVWjNKT1JteDNOblZtSzNsbVUzZGxRVU5wWVdKMk5tMVNWbmRRV2pacmFqRk9aWEU1YTFoUVdFOWxLelJCYWpBMGFFcHFVbTVqVVd4NFYwaFBTM0l6YXlzelNISjFlbGhJUm1sVVlWaFBkRkJ5WkZaeFdXMWlLMFJWUTNWc1NUbFBTM1F6YjFoVWFVbzRNSGw0YW1Oc1NGTlJSMjFPY2xaclRDOXhiMHBEV21jdllYVXdTRlUwZGt4RVRGRkVLemQ2YnpKU05IQklhRGd4V0daSVYycEVkSFoxTDNJM0sxQnFOVzFwTkRWM01HNWtaSGRhT1hJdk1rRXZSVzl3ZFZVckt5OTRTbU5KVVZaR2JrRlliVlphY21KRlJFVkllRGcyTUN0UVEyaG9kRTFPUzBkdFp6aGFRMkV3YWtSSVFrMXhWblZKT1ZOVFdsTTFSakZIYW1SMk5HWlJVMlpuUlc0NFpUSlphR1pLVEdwQ2ExZ3hWMEZPWWpoUWVHOXJObGxzYUV4WVUzbHdURUZETlM5dmN6bGhWV3RPVjJGTFExSlFibTlaUTNOTE0wTm9XV0kyZW1STE9UVmFVRlIzZHpaYWNIVlNWVEUzVjBSRFFXSm9aVzlhY201WmJHWmxRMXA0WldsR1drcFdaVlpwV1RWMmJFdGhXbTh5VkRjeldWTTRaa1p6TDJ0UWVHVldiR3BNWkZreWJHazBiV2R6VVZnNGIwVTJNVE1yYzNFMWJrbHpVMUJMV0VNeFpIbHBjM0l4WVRCelJsQmxjbEl4U1ZkalRtczNXbFpLVjBSRWVVVkpOSEZUUWpGTlEydDZjMU14Y2xoak1GUnlaakYwVlVGSFZFZGxabFZaY0c1cE9IaHplSFJKUTAwMVVFVXJlVmxoZEhadVVHd3phMUJyYlVwUFZFZFlja3hPYWpGSWJtSjFXRk16UzFsME1WVTRNamhwWVVaUVRESklVRU5XVkdaNWVubFdSWFpxWml0NlNHRjNXQzlGVEhadlRqWm1OSGhwTVdsMkwwSk9PVzVSVkRacU9XMWxTVE5tTTA5MFVGTTNRMGxNZERWaFZTdElUVU5MUmxjeFFXUlBiVzVaY1c5RWMySXpUSGRJZERacWMyY3JhWEJQU3pkVmFFdFZWak5SYW5KMVRHZzJjR2xGWlRsVFpsSkVMMDFSTVRGMU9UQndXU3REVmxabVIyOWtaRnAyZDNaaWVEaE5WMkZPYjBZM1prUmhkRTFvVWxOSFVWWnhlbEpETWtSNlUya3phbFp4YlVGTk1rZDJNR0ZpVEhKMldEZGtUM1J1V2poaFR5OW5VQzkxTUhWSFZVMTBiMUJhWmpSSGVtcGpRVzVNTkhKS1ZGaDFVMmhvZGtSNWJuTXhTak15ZGxCNlQxQmxZU3ROZEZvNVV5OUJVbUpZUkVGTWNTOWFRMVpJTVd0VlVuSnRXVUpST0U4MWEyaGhZa053UW1oM0wxSkVZMk5XWnpkaFNXODVjbnBFZDAxSGNFeE1SbmhPY2sxTE4ydFVWVlUyTjBoUVFuVmpRbFZDZGsxT2IwZE1lSGwwYmpkSVYwaE5lWEppVlU1eFdrVmhhVUU1U1V4S1ZGYzJTR1ZMU2tSQmFFUnZOMmt4UzFGQ05GcGxOM3BzWTBWUVYyRjRZV05uTTNoVU5rdGplRWQ0WVdRMmVHeEZaamRvY2l0S2VqSTFWMFJDUmtWWVpWQlBjVmRwYzI5MWFGYzVPREpMWVVOTU9HSjBkVkpTVjJwa1ExSnBkVVZ0VEM5RGNrTkxjMUpNT1dSdWRWWkhjMkZCZFZaVVpFdzFjV0pDU0hWeGRHRjRVbWN2V0RBMlZ6ZERVV05vVkVsblZXeDZOREpKVTBjd1dIVm5iMEYzZFZBMFVuaHBhMU56UjBWRFNGa3phMnMyUTI1U1UwRktPQ0lzSW0xaFl5STZJakU1WTJWaU1qZGlaREkyT1dKaU5EQXpNakV6T1dFd1lqTmhaak13TlRJNFpqYzJZbUU0WkdVMU0yUXdaalExTWpsbFlqa3lObVZoTURRM05USTROalVpTENKMFlXY2lPaUlpZlE9PQ==', 1754902375);

-- Dumping structure for table summarease.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `default_summary_ratio` float DEFAULT '0.2',
  `language_preference` varchar(20) DEFAULT 'vi',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table summarease.settings: ~0 rows (approximately)

-- Dumping structure for table summarease.summaries
CREATE TABLE IF NOT EXISTS `summaries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint unsigned NOT NULL,
  `summary_text` longtext,
  `summary_ratio` float DEFAULT '0.2',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `document_id` (`document_id`),
  CONSTRAINT `summaries_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table summarease.summaries: ~111 rows (approximately)
REPLACE INTO `summaries` (`id`, `document_id`, `summary_text`, `summary_ratio`, `created_at`) VALUES
	(1, 1, '**biiihhi**', 0.4, '2025-08-11 08:11:54'),
	(2, 2, '**biiihhi**', 0.4, '2025-08-11 08:12:40'),
	(4, 4, 'in Laravel, it is possible to have multiple **submit** **button**s within a single HTML **form**, and **the**se **button**s can be used to trigger different actions or handle different types of requests (e.g., POST or GET), although typically **form**s **submit** as ei**the**r POST or GET, and **the** **button**s within that **form** will adhere to that method. Code <**form** method="POST" action="{{ route(\'your.route\') }}"> @csrf <!-- Form fields --> <**button** type="**submit**" name="action" value="save_draft">Save Draft</**button**> <**button** type="**submit**" name="action" value="publish">Publish</**button**> </**form**> Code // In your controller public function handleForm(Request $request) { if ($request->input(\'action\') == \'save_draft\') { // Logic for saving as draft } elseif ($request->input(\'action\') == \'publish\') { // Logic for publishing } // ... } Using JavaScript to Dynamically Change Form Attributes: You can use JavaScript to change **the** action or method of **the** **form** dynamically based on which **button** is clicked before **submit**ting **the** **form**. Regarding POST and GET Requests: A single HTML <**form**> element typically specifies ei**the**r a method="POST" or method="GET". Alternatively, as mentioned above, JavaScript can be used to dynamically change **the** **form**\'s method or action before submission, effectively allowing different "**submit**" actions from a single visual **form** structure.', 0.5, '2025-08-11 08:17:57'),
	(5, 5, '**shshhshs**', 0.5, '2025-08-11 08:20:23'),
	(6, 6, 'The Valais Blacknose (German: Walliser Schwarznasenschaf) is a **breed** of domestic sheep originating in **the** Valais region of Switzerl**and**. [3]: 281 History The **breed** originates in **the** mountains of **the** canton of Valais – from which its name derives – **and** of **the** Bernese Oberl**and**. It is documented as far back as **the** fifteenth century, but **the** present German name was not used before 1884; **the** **breed** st**and**ard dates from 1962. In **the** past **the**re was some cross-**breed**ing **with** imported sheep: in **the** nineteenth century **with** Bergamasca **and** Cotswold stock,[4]: 940 **and** in **the** twentieth century **with** **the** Southdown. [5] The total population reported in Switzerl**and** for 2023 was 10286–19732, **with** 9380 ewes registered in **the** herd-book; **the** conservation status of **the** **breed** is listed as \'not at risk\'. [2] Characteristics The Schwarznasenschaf is a mountain **breed**, well adapted to grazing on **the** stony pastures of its area of origin. [4]: 940 Both rams **and** ewes are horned,[4]: 940 **with** helical or spiral-shaped horns.', 0.5, '2025-08-11 08:50:21'),
	(7, 7, '[72] The bleats of individual **sheep** are distinctive, enabling **the** ewe **and** her **lambs** to recognize each o**the**r\'s vocalizations. [72] A variety of bleats **may** be heard, depending on **sheep** age **and** circumstances. Apart from contact communication, bleating **may** signal distress, frustration or impatience; however, **sheep** are usually silent when in pain. [75] Rumbling sounds are made by **the** ram during courting; somewhat similar rumbling sounds **may** be made by **the** ewe,[72] especially when with her neonate **lambs**. A snort (explosive exhalation through **the** nostrils) **may** signal aggression or a warning,[72][76] **and** is often elicited from startled **sheep**.', 0.5, '2025-08-11 08:52:55');

-- Dumping structure for table summarease.summary_sentences
CREATE TABLE IF NOT EXISTS `summary_sentences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `summary_id` bigint unsigned NOT NULL,
  `sentence_text` text NOT NULL,
  `sentence_index` int DEFAULT NULL,
  `is_highlighted` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `summary_id` (`summary_id`),
  CONSTRAINT `summary_sentences_ibfk_1` FOREIGN KEY (`summary_id`) REFERENCES `summaries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table summarease.summary_sentences: ~2 rows (approximately)
REPLACE INTO `summary_sentences` (`id`, `summary_id`, `sentence_text`, `sentence_index`, `is_highlighted`) VALUES
	(1, 1, 'Xử lý ngôn ngữ tự nhiên là một nhánh của AI.', 1, 1),
	(2, 1, 'Nó giúp máy hiểu và sinh ngôn ngữ con người.', 2, 1);

-- Dumping structure for table summarease.summary_tags
CREATE TABLE IF NOT EXISTS `summary_tags` (
  `summary_id` bigint unsigned NOT NULL,
  `tag_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`summary_id`,`tag_id`),
  KEY `idx_st_summary` (`summary_id`),
  KEY `idx_st_tag` (`tag_id`),
  CONSTRAINT `fk_st_summary` FOREIGN KEY (`summary_id`) REFERENCES `summaries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_st_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table summarease.summary_tags: ~0 rows (approximately)

-- Dumping structure for table summarease.tags
CREATE TABLE IF NOT EXISTS `tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table summarease.tags: ~0 rows (approximately)

-- Dumping structure for table summarease.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table summarease.users: ~2 rows (approximately)
REPLACE INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `google_id`) VALUES
	(1, 'Iris Garret', 'hoangphantom2468@gmail.com', NULL, '$2y$12$D47LjZPq2J8Oh/Jt6xpKBeIPtOBSFmRdCeNnZTVP/8wxoMcCKqfMq', NULL, '2025-07-21 07:58:31', '2025-08-11 01:43:00', '112000107226043225452'),
	(2, 'Hoàng Nguyễn Lê Khánh', '2311552947@nttu.edu.vn', NULL, '$2y$12$nczdXlRvMuNQKlvJ8FpnfOiMFi3NkDgJDj8M/05g2sU1325/JpjOG', NULL, '2025-07-21 17:51:03', '2025-08-11 01:12:37', '105391043900681834156');

-- Dumping structure for table summarease.user_roles
CREATE TABLE IF NOT EXISTS `user_roles` (
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table summarease.user_roles: ~2 rows (approximately)
REPLACE INTO `user_roles` (`user_id`, `role_id`) VALUES
	(1, 1),
	(2, 2);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
