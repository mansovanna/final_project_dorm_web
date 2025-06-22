-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2025 at 04:46 PM
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
-- Database: `dorm_ksit`
--

-- --------------------------------------------------------

--
-- Table structure for table `addbuilding`
--

CREATE TABLE `addbuilding` (
  `id` int(255) NOT NULL,
  `building_name` varchar(255) NOT NULL,
  `room_number` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addbuilding`
--

INSERT INTO `addbuilding` (`id`, `building_name`, `room_number`) VALUES
(73, 'NT-M', 10),
(74, 'ABW', 12);



-- --------------------------------------------------------

--
-- Table structure for table `discipline`
--

CREATE TABLE `discipline` (
  `id` int(11) NOT NULL,
  `text_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discipline`
--

INSERT INTO `discipline` (`id`, `text_content`) VALUES
(19, 'ប្រការ១ គោលបំណង\r\nបទបញ្ហានេះអនុវត្តចំពោះនិស្សិតទាំងឡាយដែលកំពុងស្នាក់នៅក្នុងអន្តេវាសិកដ្ឋានវិទ្យាស្ថានបច្ចេកវិទ្យា ដល់អ្នក កំពង់ស្ពឺ ធ្វើឲ្យយល់ដឹងពីគោលការណ៍ក្នុងការស្នាក់នៅក្នុងអគ្គវាសិកដ្ឋានរបស់វិទ្យាស្ថាន បណ្តុះបណ្តាលបស់ ស្នាក់នៅទាំងឡាយគួរបុគ្គលិកលក្ខណៈក្នុងការរស់នៅជាមួយគ្នានិងធ្វើឲ្យអ្នកដែលរស់នៅ ចែករំលែកវប្បធម៌ សន្តិភាពដែលអាចទទួលបាននូវសុខសុវត្ថិភាពទាំងអស់គ្នា ជាមួយគ្នាបោះផ្លាស់ប្តូរ\r\n\r\nប្រការ២-ការអនុវត្ត\r\nនិសិស្សិតដែលស្នាក់នៅក្នុងអង្កេតសិកដ្ឋានទាំងអស់មានភាពព្វកិច្ចគោរពកាមបទបញ្ញារិទ្ធក្នុងនេះឲ្យបាន ខ្លាចខ្លួន និងធ្វើសកម្មភាព ការក្តិសមជានិស្សិតប្រកបដោយចំណេះដឹង និងសីលធម៌រស់នៅ។\r\n\r\nប្រការ៣ ការបែងចែកពីកន្លែងស្នាក់នៅក្នុងអន្តវាសិកដ្ឋាន\r\n១. វិទ្យាស្ថានមានអន្តេវាសិកដ្ឋានសម្រាប់បុរស  និងស្ត្រី។\r\n២.អ្នកស្នាក់នៅទាំងអស់មិនត្រូវចេញចូលទៅក្នុងអគារឬបន្ទប់ដែលអ្នកស្នាក់នៅមានភេទផ្ទុយគ្នារឡើយ។\r\n៣.នៅតាមបន្ទប់នីមួយៗត្រូវមានប្រធាន ១រូប អនុប្រធាន១រូប និងក្នុង ១អគារ ត្រូវមានប្រធានមួយរូប អនុប្រធាន ២រូបសម្រាប់មើលការខុសត្រូវ\r\n\r\nប្រការ៤ ចំណុចត្រូវចៀសវាង\r\nអ្នកស្នាក់នៅអន្តេវាសិកដ្ឋាន មិនត្រូវធ្វើសកម្មភាពដែលបណ្តាលឱ្យមានការប៉ះពាល់ដល់សន្តិសុខសុវត្ថិភាព សណ្តាប់ធ្នាប់ក្នុងគ្រឹះស្ថាន ឬសង្គមដែលមានដូចខាងក្រោម៖ \r\n១. រីកែច្នៃ ឬផ្ទុកអាវុធជាតិផ្ទុះ (កាំភ្លើង ងាវ ចម្លាម) សារធាតុហាមឃាត់ថ្នាំញៀនឬស្រា បាវី) ឬសារធាតុបង្ករគ្រោះថ្នាក់ផ្សេងៗ(ពុល)។\r\n២. រក្សាទុកសារធាតុដែលអាចបណ្តាលឱ្យកាត់ ឬភូតអគារ និងមានគ្រោះថ្នាក់( សារធាតុគីមីដែលអាចកាត់ (សូលុយស្យុងអាស៊ីត បាស...) ពុល និងផ្តល់ហានិភ័យ)។\r\n៣. ប្រើប្រេងកាត ប្រេងសាំង ដែលអាចបង្កឱ្យមានគ្រោះថ្នាក់នៅក្នុងបន្ទប់\r\n៤. រធ្វើឲ្យខូចខាត ឬបែកបាក់ទ្រព្យសម្បត្តិដែលបានបំពាក់ក្នុងអន្តេវាសិកដ្ឋាន ដូចជា ប្រប្រអប់អគ្គិសនី ក្នុងទីអគ្គិសនី អំពូលភ្លើង បណ្តាញទឹក ទ្វារបង្អួច កង្គារ។\r\n៥. យកចរានជំនិះ (កង់ ម៉ូតូ) ចូលក្នុងបន្ទប់ស្នាក់នៅ\r\n៦.មានទំនាក់ទំនងជាមួយក្រុមក្មេងទំរនឹង ឬបបួលរអ្នកនៅអរន្តវាសិកដ្ឋានឱ្យចូលរួមជាមួយខ្លួន\r\n៧. ពាក់ព័ន្ធឬអំបើហិង្សា តែល្បែងស៊ីសង\r\n៨. ចិញ្ចឹមសត្វ (ជ្រូក ឆ្កែគ្នា មាន់ ទា ត្រី)\r\n៩. ចែកចាយ មិនផ្សាយឯកសារ រូបណាទេរផ្សេងៗដោយគ្មានការអនុញ្ញាត\r\n១០. ផឹកស្រា សេពគ្រឿងញៀន នៅក្នុងបន្ទប់ ឬបរិវេណគ្រឹះស្អាត\r\n១១.សកម្មភាពទាំងឡាយណាដែលអាចបណ្តាលឱ្យមានសំឡេងរំខានឡូឡា មើលទូរទស្សន៏ បើកចំរៀងដោយសំឡេងខ្លាំង)\r\n១២. កែច្នៃបន្ទប់ស្នាក់នៅឱ្យខុសពីទម្រង់ដើម\r\n១៣. សកម្មភាពទាំងឡាយណាដែលមានជករប៉ះពាល់ដល់អ្នកស្នាក់នៅជិតខាង និងអ្នកដទៃ\r\n១៨. ហាមទៅនេសាទត្រី ឬដើរចូលព្រៃទាក់ឬជាញ់សត្វ\r\n\r\nប្រការ៥- អំពីអ្នកស្នាក់នៅរួមបន្ទប់\r\n១. អ្នកស្នាក់នៅរួមបន្ទប់ត្រូវចេះគោខេគ្នាទៅវិញទៅមក។ ​ មិនត្រូវធ្វើឱ្យប៉ះពាស់ទ្រព្យសម្បត្តិនិងអារម្មណ៍អ្នករួមបន្ទប់\r\n២. ត្រូវរាយការណ៍ដល់គណៈគ្រប់គ្រងនូវហេតុការណ៍ផ្សេងៗដូចជា ភ្លើងនេះ បំពង់ទុយោបែក ស្ទះលូ បង្គន់ ឬបញ្ហាសុខភាព\r\n\r\nប្រការ៦ ការគេច្ចកិច្ចគ្រប់គ្រង\r\nអ្នកស្នាក់នៅអន្តេវាសិកដ្ឋាងត្រូវអនុវត្តនូវកាតព្វកិច្ចដូចខាងក្រោម៖\r\n១. សម្អាតបន្ទប់របស់ខ្លួនឱ្យស្អាតជានិច្ចរួម ទាំង បន្ទន់ បន្ទប់ទឹក ផ្ទះបាយ មុន និងក្រោយបន្ទប់\r\n២. ចូលរួមសម្អាតអម្តេវាសិកដ្ឋានទាំងអស់គ្នារៀងពល់ព្រឹក និងល្ងាច\r\n៣. ដាក់សំរាម និងកាកសំណល់ផ្សេងៗ ក្នុងធុងសំរាមឱ្យបានត្រឹមត្រូវ (វិញែកតាមប្រភេទសំរាម គយកវត្ថុដែសរើសបានទៅប្រគល់ឲ្យគណៈគ្រប់គ្រងអន្តេវាសិកដ្ឋានដើម្បីរកម្ចាស់ដើមវិញ)\r\n៥.ជួសជុល និងបំពាក់ឧបករណ៍ផ្សេងៗនៃអន្តេវាសិកដ្ឋាន់ដែលខូចខាត ដោយការចំណាយជាប្លុករបស់ អ្នកស្នាក់នៅ\r\n៦. ប្រើប្រាស់តុ ឬ ឧបករណ៍យ៉ារអេឡិចត្រូនិច និងកន្លែងដាក់វត្ថុផ្សេងៗឱ្យបានសមរម្យ\r\n៧. សន្សំសំចៃ ថាមពលអគ្គិសនីឱ្យមានកម្រិតខ្ពស់\r\n៨. ផ្តល់របាយការណ៍ឱ្យបានឡើងទាត់រៀងរាល់ខែ។\r\n៩. ចូលរួមដាំកូនឈើ និងដំណាំជីវៈចំរុះតាមផែនការគណៈគ្រប់គ្រង\r\n១០. ករណីជាក់ ឬវត្ថុរឹងទទៅធំ ឬកូតជញ្ជាំងនាំឱ្យបាក់បែកជញ្ជាំង ត្រូវបញ្ឈប់ពីអន្តេវាសិកដ្ឋាន។\r\n\r\nរការ៧- អ្នកសម្របសម្រួលអន្តេវាសិកដ្ឋាន\r\n១ អ្នកសម្របសម្រួលមានភារកិច្ច ផ្តល់យោបល់ និងធ្វើការណែនាំដល់អ្នកស្នាក់នៅអន្តេវាសិកដ្ឋាន\r\n២ ផ្តល់សេចក្តីជូនដំណឹងនូវរាល់ពេលគណៈគ្រប់គ្រងស្នើសុំឱ្យធ្វើកិច្ចការណាមួយ\r\n៣ ត្រួតពិនិត្យ និងស្រង់អវត្តមានប្រចាំថ្ងៃ\r\n៤ លើកទឹកចិត្តដល់អ្នកស្នាក់នៅអន្តេវាសិកដ្ឋានចូលរួមរាល់សកម្មភាពរបស់វិទ្យាស្ថាន\r\n\r\nប្រការ៨- ការចូលរួមក្នុងការងារសង្គម\r\nអ្នកស្នាក់នៅអន្តេវាសិកដ្ឋានទាំងអស់ត្រូវចូលរួមក្នុងការងារសង្គមផ្សេងៗ វិទ្យាស្ថាន។ ដែលបានកំណត់ដោយវិទ្យាស្ថាន\r\n\r\nប្រការ៩. កម្មវិធីប្រជុំ\r\n១. ប្រធាន និងអនុប្រធានអន្តេវាសិកដ្ឋាន និងបន្ទប់ ត្រូវចូលរួមប្រជុំប្រចាំខែតាមកាលកំណត់ របស់គណៈគ្រប់គ្រង។\r\n២. អ្នកស្នាក់នៅក្នុងអន្តេវាសិកដ្ឋានទាំងអស់ត្រូវចូលរួមប្រជុំនៅដើមឆមាស និងពេលផ្សេងទៀត ក្នុងករណីចាំបាច់។\r\n\r\nរការ១០. ករណីអវត្តមាន\r\n១. អ្នកស្នាក់អន្តេវាសិកដ្ឋានទាំងអស់ត្រូវផ្តល់ព័ត៌មានដល់ប្រធាន អនុប្រធាន បន្ទប់ និងប្រធាន អនុ-ប្រធាន អន្តេវាសិកដ្ឋាន នៅពេលអវត្តមានពីបន្ទប់ចាប់ពី១យប់ឡើងទៅ។\r\n២. ករណីឈប់ឥតច្បាប់ដោយពុំដឹងមូលហេតុ និងត្រូវទទួលការណែនាំ (ករណីមិនគោរពតាមមិនត្រូវអនុញ្ញាតឱ្យស្នាក់នៅបន្តទៀតឡើយ)។\r\n៣. ករណីមានសំណើពីគណៈគ្រប់គ្រងឱ្យជួយកិច្ចការបន្ទាន់ណាមួយ អ្នកស្នាក់នៅទាំងអស់មិនអនុញ្ញាតឱ្យសុំច្បាប់ចេញពីអន្តេវាសិកដ្ឋានឡើយ។\r\n៤. សុំច្បាប់ពីគណៈគ្រប់គ្រងអន្តេវាសិកដ្ឋាន(ស្រី និងប្រុស)មុន ពីរ ឬបីថ្ងៃមុនចាកចេញ អន្តេវាសិកដ្ឋាន ។ \r\n\r\nប្រការ១១. ការសួរសុខទុក្ខ\r\n១.អ្នកដទៃក្រៅពីអ្នកស្នាក់នៅគ្មានសិទ្ធចូលក្នុងបន្ទប់នៃអន្តេវាសិកដ្ឋានបានឡើយ លុះត្រាតែ មានការអនុញ្ញាតពីគណៈគ្រប់គ្រងអន្តេវាសិកដ្ឋាន និងអ្នករួមបន្ទប់។ មិនអនុញ្ញាតឲ្យនាំមនុស្ស ភេទផ្ទុយគ្នាចូលទៅក្នុងបន្ទប់ដាច់ខាត ទោះជាក្រុមគ្រួសារក៏ដោយ។\r\n២. មិនអនុញ្ញាតឱ្យមានការសួរសុខទុក្ខចាប់ពីម៉ោង ២២:០០ តទៅ។\r\n៣. មិនអនុញ្ញាតឱ្យភ្ញៀវស្នាក់នៅក្នុងអន្តេវាសិកដ្ឋានជាដាច់ខាត (លុះត្រាតែមានការអនុញ្ញាត)។\r\n\r\nប្រការ១២. ការត្រួតពិនិត្យ\r\n១. គណៈគ្រប់គ្រងមានសិទ្ធិចូលទៅក្នុងបន្ទប់ដោយមិនចាំបាច់មានវត្តមានអ្នកស្នាក់នៅ ក្នុងករណី មានអគ្គិភ័យ គ្រោះមហន្តរាយ និងព្រឹត្តិការណ៍បន្ទាន់ផ្សេងៗដែលគណៈគ្រប់គ្រងយល់ឃើញថា ជាភាពមិនប្រក្រតី។\r\n២. គណៈគ្រប់គ្រងមានសិទ្ធិចូលទៅក្នុងបន្ទប់ ដោយផ្តល់ដំណឹងជាមុន ដើម្បីត្រួតពិនិត្យសម្ភារ ផ្សេងៗ។\r\n\r\nប្រការ១៣. សន្តិសុខ និងសុវត្ថិភាព\r\n១.​ អ្នកស្នាក់នៅត្រូវមានស្មារតីប្រុងប្រយ័ត្នខ្ពស់ ចៀសវាងការបាត់បង់ជាយថាហេតុ។\r\n២. មិនត្រូវទុកចោលរបស់របរមានតម្លៃនៅក្នុងបន្ទប់។ បើមានការបាត់បង់នោះគណៈគ្រប់គ្រងនឹងមិន ទទួលខុសត្រូវឡើង។\r\n\r\nប្រការ១៤- កម្រិតវិជ័យ\r\n១. អ្នកដែលប្រព្រឹត្តខុសនឹងបទបញ្ជាផ្ទៃក្នុងនឹងត្រូវទទួលការព្រមាន ដោយស្តីបន្ទោស ឬលាយលក្ខណ៍\r\n២. ករណីដែលទទួលបានការព្រមានជាលាយលក្ខណ៍អក្សរវាដង ហើយមិនរាងចាល នឹងត្រូវបញ្ឈប់ការស្នាក់នៅជាស្ថាពរយ៉ាងយូរទៅ៨ម៉ោងក្រោយព្រឹត្តិការណ៍។ \r\n៣. ករណីធ្ងន់ធ្ងរ និស្សិតត្រូវទទួលការបញ្ឈប់ឱ្យស្នាក់នៅតាមការសម្រេចរបស់អង្គប្រជុំនៃគណៈគ្រប់គ្រង អន្តេវាសិកដ្ឋាន ដោយមិនចាំបាច់មានការព្រមានជាមុន។\r\n\r\nប្រការ១៥- រយៈពេលនៃការស្នាក់នៅ\r\n១. រយៈពេលនៃការស្នាក់នៅចាប់ពីខែវិច្ឆិកា ដល់ខែតុលាឆ្នាំបន្ទាប់។ \r\n២. និស្សិនត្រូវដាក់ពាក្យសុំស្នាក់នៅរៀងរាល់ដើមឆ្នាំសិក្សា។\r\n\r\nប្រការទេ១៦. និស្សិតដែលមានទីលំនៅឆ្ងាយពីវិទ្យាស្ថានត្រូវបានផ្តល់អាទិភាពឱ្យស្នាក់នៅអច្ចេវាសិកដ្ឋាន\r\n\r\nប្រការ១៧ បទបញ្ជាផ្ទៃក្នុង ស្តីពី ការស្នាក់នៅក្នុងអន្តេវាសិកដ្ឋានវិទ្យាស្ថានបច្ចេកវិទ្យាកំពង់ស្ពឺណាដែលមាន ខ្លឹមសារផ្ទុយ ត្រូវចាត់ទុកជានិពករណ៍។\r\n\r\nប្រការ១៨- បទបញ្ជាផ្ទៃក្នុងនេះមានសុរាលភាពចាប់ពីថ្ងៃចុះហត្ថលេខាតទៅ។\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `student_id` int(100) NOT NULL,
  `building` varchar(100) NOT NULL,
  `room` int(11) NOT NULL,
  `change_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `lastname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `skill` varchar(255) NOT NULL,
  `year` int(255) NOT NULL,
  `education_level` varchar(255) NOT NULL,
  `phone_student` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`id`, `student_id`, `building`, `room`, `change_date`, `lastname`, `name`, `skill`, `year`, `education_level`, `phone_student`) VALUES
(29, 11220033, 'A', 1, '2025-05-30 14:38:41', 'ម៉ាន់', 'សុវណ្ណា', 'បច្ចេកវិទ្យាកុំព្យូទ័រ', 3, 'បរិញ្ញាបត្រ', 967574404),
(30, 11220033, 'ABW', 1, '2025-05-30 14:42:15', 'ម៉ាន់', 'សុវណ្ណា', 'បច្ចេកវិទ្យាកុំព្យូទ័រ', 3, 'បរិញ្ញាបត្រ', 967574404),
(31, 11220033, 'NT-M', 10, '2025-05-30 14:43:12', 'ម៉ាន់', 'សុវណ្ណា', 'បច្ចេកវិទ្យាកុំព្យូទ័រ', 3, 'បរិញ្ញាបត្រ', 967574404),
(32, 11220033, 'NT-M', 10, '2025-05-30 14:43:47', 'ម៉ាន់', 'សុវណ្ណា', 'បច្ចេកវិទ្យាកុំព្យូទ័រ', 3, 'បរិញ្ញាបត្រ', 967574404);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(255) NOT NULL,
  `student_id` int(100) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `building` varchar(255) NOT NULL,
  `room_number` int(100) NOT NULL,
  `accommodation_fee` int(100) NOT NULL,
  `discount` int(100) NOT NULL,
  `water_fee` int(100) NOT NULL,
  `electricity_fee` int(100) NOT NULL,
  `total_fee` int(255) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Rejected') NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `student_id`, `user_name`, `building`, `room_number`, `accommodation_fee`, `discount`, `water_fee`, `electricity_fee`, `total_fee`, `payment_date`, `status`, `image`) VALUES
(32, 11220033, 'BOUR SOKKHORN', 'A', 1, 100000, 0, 10000, 10000, 120000, '2025-05-30 14:22:20', 'Approved', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_summary`
--

CREATE TABLE `payment_summary` (
  `id` int(11) NOT NULL,
  `electricity_fee` decimal(10,2) NOT NULL,
  `water_fee` decimal(10,2) NOT NULL,
  `discount` decimal(5,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `room` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_summary`
--

INSERT INTO `payment_summary` (`id`, `electricity_fee`, `water_fee`, `discount`, `total`, `created_at`, `room`) VALUES
(2, 10000.00, 10000.00, 0.00, 0.00, '2025-05-02 01:09:45', 100000.00);

-- --------------------------------------------------------

--
-- Table structure for table `qr_code_bank`
--

CREATE TABLE `qr_code_bank` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `qr_code_bank`
--

INSERT INTO `qr_code_bank` (`id`, `name`, `image_url`, `created_at`) VALUES
(8, 'ABA', 'qr_bank.jpg', '2025-05-04 13:11:52'),
(9, 'WIN', 'photo_2025-05-06_05-55-34.jpg', '2025-05-06 12:59:36');

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `user_id` int(11) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `gender` enum('ប្រុស','ស្រី') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone_student` varchar(250) NOT NULL,
  `phone_parent` varchar(20) DEFAULT NULL,
  `skill` varchar(100) DEFAULT NULL,
  `education_level` varchar(100) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `img` varchar(255) NOT NULL,
  `building` varchar(255) NOT NULL,
  `room` int(100) NOT NULL,
  `stay` date NOT NULL,
  `status` enum('មិនអនុញ្ញាត','អនុញ្ញាត','រង់ចាំ','') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`user_id`, `password`, `student_id`, `lastname`, `name`, `username`, `gender`, `dob`, `address`, `phone_student`, `phone_parent`, `skill`, `education_level`, `year`, `img`, `building`, `room`, `stay`, `status`) VALUES
(179, '$2y$10$25ZJQt50EaMSqKSuq4V8k.9.5pMclV2.euXj0LKOg8YiN7ZXEzMEy', '11220033', 'ម៉ាន់', 'សុវណ្ណា', 'MAN SOVANNA', 'ប្រុស', '2004-06-30', 'ភូមិ ពោធិ៍ ឃុំ ពោធិ៍ ស្រុកកំពង់លែង ខេត្តកំពង់ឆ្នាំង', '0967574404', '0967574402', 'បច្ចេកវិទ្យាកុំព្យូទ័រ', 'បរិញ្ញាបត្រ', 3, 'uploads/476630523_1341863290349455_3504267608563571534_n.jpg', 'ABW', 9, '2025-05-02', 'អនុញ្ញាត');

-- --------------------------------------------------------

--
-- Table structure for table `request_room`
--

CREATE TABLE `request_room` (
  `id` int(10) NOT NULL,
  `student_id` int(10) NOT NULL,
  `build` varchar(255) NOT NULL,
  `room` varchar(255) NOT NULL,
  `stay` date NOT NULL,
  `status` enum('Pedding','Reject','Approval') NOT NULL DEFAULT 'Pedding'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reques_alaw`
--

CREATE TABLE `reques_alaw` (
  `student_id` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `sumday` int(100) NOT NULL,
  `first_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('រង់ចាំ','មិនអនុញ្ញាត','អនុញ្ញាត','') NOT NULL DEFAULT 'រង់ចាំ',
  `re_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reques_alaw`
--

INSERT INTO `reques_alaw` (`student_id`, `user_name`, `sumday`, `first_date`, `end_date`, `reason`, `user_id`, `status`, `re_date`) VALUES
('11220033', 'ម៉ាន់ សុវណ្ណា', 4, '2025-06-01', '2025-06-05', 'ជិះទៅជួបគ្រួសារ', 193, 'អនុញ្ញាត', '2025-05-30 20:47:28');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `Email` varchar(255) NOT NULL,
  `staff_Name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `img` text NOT NULL,
  `id` int(11) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `skill` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`Email`, `staff_Name`, `username`, `phone_number`, `img`, `id`, `password`, `skill`) VALUES
('sokkhorn@gmail.com', 'បួរ សុខខន', 'BOUR SOKKHORN', '096783467', 'uploads/teacher sokhorn.jpg', 77, '$2y$10$dITvVXQAmAcSUB3t5PY9z.y6FRr/Yc9AKFibWc5rOOBepJbWGZ0l2', ''),
('admin@gmail.com', 'Admin', 'admin', '0987652102', 'uploads/logoksit.jpg', 81, '$2y$10$TMTkH04T.PuNf4SJCvYONuNeqUawEqUBPaesROZjnYg2E2ukPxIE2', ''),
('Heangsreymey66@gmail.com', 'ហៀង ស្រីមុី', 'HEANG SREYMEY', '0968789213', 'uploads/Screenshot 2024-08-09 090427.png', 82, '$2y$10$flNyydqoLiumcimuS0sX1OCrt/uSdH1S5.rP7ccrwKLyIltNx2QMa', ''),
('dalisnguon108@gmail.com', 'ងួន ដាលិស', 'NGUON DALIS', '078464373', 'uploads/jlis.jpg', 83, '$2y$10$LtodsZ8maVzr1heKSNgw8O93iL8C8yTkteefQHatkoZLm4Qy/SXx6', ''),
('sorpisey78@gmail.com', 'សរ បូរ៉ា', 'SOR BORA', '087410029', 'uploads/bora.jpg', 84, '$2y$10$OyLEbt7wKxD9SjWYRQDUTugCVJILqVB8M1SXz3ZaRBetvI2zK..AS', ''),
('rsa69153@gmail.com', 'ឆន វណ្ណា', 'CHHORN VANNA', '0963694896', 'uploads/vanna.jpg', 85, '$2y$10$tAPYthjgpfTRgxIjKEXnwOEyk/EFv7VWqmJkXRc7X/.JqrGVF5EjG', ''),
('binsinputhyvong88@gmail.com', 'ប៊ិន ស៊ិនពុទ្ធិវង្ស', 'BIN SINPUTHYVONG', '015514881', 'uploads/vong_1.jpg', 86, '$2y$10$S9.cCwORm7EYxyjUxqE/5uawE/Xn6BI7v35glt3VcQVpWvM2DpiAu', '');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(10) NOT NULL,
  `student_id` int(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `date_birth` date NOT NULL,
  `address` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `education_level` varchar(255) NOT NULL,
  `year` int(10) NOT NULL,
  `phone_student` int(20) NOT NULL,
  `phone_parent` int(20) DEFAULT NULL,
  `image_profile` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `password`, `first_name`, `last_name`, `user_name`, `gender`, `date_birth`, `address`, `department`, `education_level`, `year`, `phone_student`, `phone_parent`, `image_profile`) VALUES
(5, 2147483647, '$2y$10$dqJ76lno3pY1c1ZsXJUiTeTXXYc40jxIvLAShdqh.LCOf//ciUywi', 'ម៉ាន់', 'សុវណ្ណា', 'Man Sovanna', 'Male', '2004-02-02', 'ភូមិ ពោធិ៍ ឃុំ ពោធិ៍ ស្រុកកំពង់លែង ខេត្តកំពង់ឆ្នាំង', 'បច្ចេកវិទ្យាកុំព្យូទ័រ', 'បរិញ្ញាបត្រ', 3, 967574404, 967574404, 'uploads/68020e2cefab58.06254255.png');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES register(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `token`, `expires`, `user_id`) VALUES
(11, '3513a390635c32b24bded760565a09adb72bad7bf7bff2a59887f7965dfd959b', 1748610778, '11220033');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addbuilding`
--
ALTER TABLE `addbuilding`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discipline`
--
ALTER TABLE `discipline`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_summary`
--
ALTER TABLE `payment_summary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qr_code_bank`
--
ALTER TABLE `qr_code_bank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `request_room`
--
ALTER TABLE `request_room`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reques_alaw`
--
ALTER TABLE `reques_alaw`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addbuilding`
--
ALTER TABLE `addbuilding`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `discipline`
--
ALTER TABLE `discipline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `payment_summary`
--
ALTER TABLE `payment_summary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `qr_code_bank`
--
ALTER TABLE `qr_code_bank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT for table `request_room`
--
ALTER TABLE `request_room`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reques_alaw`
--
ALTER TABLE `reques_alaw`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
