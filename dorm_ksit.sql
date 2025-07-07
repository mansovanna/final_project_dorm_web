-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2025 at 04:21 AM
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
(75, 'NT-M', 2);

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `body`, `created_at`) VALUES
(1, 'សេក្ដីជូនដំណឹង!!!', 'សូមជូនដំណឹងដល់ប្អូនៗដែលជានិស្សិតកំពុងស្នាក់នៅក្នុងវិទ្យាស្ថានបច្ចេកវិទ្យាកំពង់ស្ពឺឱ្យបានជ្រាបថា៖ យើងខ្ញុំពិតជាមានសេចក្ដីសោមនោរីករាយយ៉ាងក្រៃលែង', '2025-06-08 00:00:00'),
(3, 'សេចក្ដីជូនដំណឹង!!', 'សូមជំរាបជូនដល់សិស្សនិស្សិតទាំងអស់ឱ្យបានជ្រាប់ថា សាលាយើងនិងរៀបចំកម្មវិធីសម្រាប់ជូនដល់សិស្សិត...', '2025-06-12 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `announcement_views`
--

CREATE TABLE `announcement_views` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  `viewed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(19, 'ប្រការ១ គោលបំណង\nបទបញ្ហានេះអនុវត្តចំពោះនិស្សិតទាំងឡាយដែលកំពុងស្នាក់នៅក្នុងអន្តេវាសិកដ្ឋានវិទ្យាស្ថានបច្ចេកវិទ្យា ដល់អ្នក កំពង់ស្ពឺ ធ្វើឲ្យយល់ដឹងពីគោលការណ៍ក្នុងការស្នាក់នៅក្នុងអគ្គវាសិកដ្ឋានរបស់វិទ្យាស្ថាន បណ្តុះបណ្តាលបស់ ស្នាក់នៅទាំងឡាយគួរបុគ្គលិកលក្ខណៈក្នុងការរស់នៅជាមួយគ្នានិងធ្វើឲ្យអ្នកដែលរស់នៅ ចែករំលែកវប្បធម៌ សន្តិភាពដែលអាចទទួលបាននូវសុខសុវត្ថិភាពទាំងអស់គ្នា ជាមួយគ្នាបោះផ្លាស់ប្តូរ\n\nប្រការ២-ការអនុវត្ត\nនិសិស្សិតដែលស្នាក់នៅក្នុងអង្កេតសិកដ្ឋានទាំងអស់មានភាពព្វកិច្ចគោរពកាមបទបញ្ញារិទ្ធក្នុងនេះឲ្យបាន ខ្លាចខ្លួន និងធ្វើសកម្មភាព ការក្តិសមជានិស្សិតប្រកបដោយចំណេះដឹង និងសីលធម៌រស់នៅ។\n\nប្រការ៣ ការបែងចែកពីកន្លែងស្នាក់នៅក្នុងអន្តវាសិកដ្ឋាន\n១. វិទ្យាស្ថានមានអន្តេវាសិកដ្ឋានសម្រាប់បុរស  និងស្ត្រី។\n២.អ្នកស្នាក់នៅទាំងអស់មិនត្រូវចេញចូលទៅក្នុងអគារឬបន្ទប់ដែលអ្នកស្នាក់នៅមានភេទផ្ទុយគ្នារឡើយ។\n៣.នៅតាមបន្ទប់នីមួយៗត្រូវមានប្រធាន ១រូប អនុប្រធាន១រូប និងក្នុង ១អគារ ត្រូវមានប្រធានមួយរូប អនុប្រធាន ២រូបសម្រាប់មើលការខុសត្រូវ\n\nប្រការ៤ ចំណុចត្រូវចៀសវាង\nអ្នកស្នាក់នៅអន្តេវាសិកដ្ឋាន មិនត្រូវធ្វើសកម្មភាពដែលបណ្តាលឱ្យមានការប៉ះពាល់ដល់សន្តិសុខសុវត្ថិភាព សណ្តាប់ធ្នាប់ក្នុងគ្រឹះស្ថាន ឬសង្គមដែលមានដូចខាងក្រោម៖ \n១. រីកែច្នៃ ឬផ្ទុកអាវុធជាតិផ្ទុះ (កាំភ្លើង ងាវ ចម្លាម) សារធាតុហាមឃាត់ថ្នាំញៀនឬស្រា បាវី) ឬសារធាតុបង្ករគ្រោះថ្នាក់ផ្សេងៗ(ពុល)។\n២. រក្សាទុកសារធាតុដែលអាចបណ្តាលឱ្យកាត់ ឬភូតអគារ និងមានគ្រោះថ្នាក់( សារធាតុគីមីដែលអាចកាត់ (សូលុយស្យុងអាស៊ីត បាស...) ពុល និងផ្តល់ហានិភ័យ)។\n៣. ប្រើប្រេងកាត ប្រេងសាំង ដែលអាចបង្កឱ្យមានគ្រោះថ្នាក់នៅក្នុងបន្ទប់\n៤. រធ្វើឲ្យខូចខាត ឬបែកបាក់ទ្រព្យសម្បត្តិដែលបានបំពាក់ក្នុងអន្តេវាសិកដ្ឋាន ដូចជា ប្រប្រអប់អគ្គិសនី ក្នុងទីអគ្គិសនី អំពូលភ្លើង បណ្តាញទឹក ទ្វារបង្អួច កង្គារ។\n៥. យកចរានជំនិះ (កង់ ម៉ូតូ) ចូលក្នុងបន្ទប់ស្នាក់នៅ\n៦.មានទំនាក់ទំនងជាមួយក្រុមក្មេងទំរនឹង ឬបបួលរអ្នកនៅអរន្តវាសិកដ្ឋានឱ្យចូលរួមជាមួយខ្លួន\n៧. ពាក់ព័ន្ធឬអំបើហិង្សា តែល្បែងស៊ីសង\n៨. ចិញ្ចឹមសត្វ (ជ្រូក ឆ្កែគ្នា មាន់ ទា ត្រី)\n៩. ចែកចាយ មិនផ្សាយឯកសារ រូបណាទេរផ្សេងៗដោយគ្មានការអនុញ្ញាត\n១០. ផឹកស្រា សេពគ្រឿងញៀន នៅក្នុងបន្ទប់ ឬបរិវេណគ្រឹះស្អាត\n១១.សកម្មភាពទាំងឡាយណាដែលអាចបណ្តាលឱ្យមានសំឡេងរំខានឡូឡា មើលទូរទស្សន៏ បើកចំរៀងដោយសំឡេងខ្លាំង)\n១២. កែច្នៃបន្ទប់ស្នាក់នៅឱ្យខុសពីទម្រង់ដើម\n១៣. សកម្មភាពទាំងឡាយណាដែលមានជករប៉ះពាល់ដល់អ្នកស្នាក់នៅជិតខាង និងអ្នកដទៃ\n១៨. ហាមទៅនេសាទត្រី ឬដើរចូលព្រៃទាក់ឬជាញ់សត្វ\n\nប្រការ៥- អំពីអ្នកស្នាក់នៅរួមបន្ទប់\n១. អ្នកស្នាក់នៅរួមបន្ទប់ត្រូវចេះគោខេគ្នាទៅវិញទៅមក។ ​ មិនត្រូវធ្វើឱ្យប៉ះពាស់ទ្រព្យសម្បត្តិនិងអារម្មណ៍អ្នករួមបន្ទប់\n២. ត្រូវរាយការណ៍ដល់គណៈគ្រប់គ្រងនូវហេតុការណ៍ផ្សេងៗដូចជា ភ្លើងនេះ បំពង់ទុយោបែក ស្ទះលូ បង្គន់ ឬបញ្ហាសុខភាព\n\nប្រការ៦ ការគេច្ចកិច្ចគ្រប់គ្រង\nអ្នកស្នាក់នៅអន្តេវាសិកដ្ឋាងត្រូវអនុវត្តនូវកាតព្វកិច្ចដូចខាងក្រោម៖\n១. សម្អាតបន្ទប់របស់ខ្លួនឱ្យស្អាតជានិច្ចរួម ទាំង បន្ទន់ បន្ទប់ទឹក ផ្ទះបាយ មុន និងក្រោយបន្ទប់\n២. ចូលរួមសម្អាតអម្តេវាសិកដ្ឋានទាំងអស់គ្នារៀងពល់ព្រឹក និងល្ងាច\n៣. ដាក់សំរាម និងកាកសំណល់ផ្សេងៗ ក្នុងធុងសំរាមឱ្យបានត្រឹមត្រូវ (វិញែកតាមប្រភេទសំរាម គយកវត្ថុដែសរើសបានទៅប្រគល់ឲ្យគណៈគ្រប់គ្រងអន្តេវាសិកដ្ឋានដើម្បីរកម្ចាស់ដើមវិញ)\n៥.ជួសជុល និងបំពាក់ឧបករណ៍ផ្សេងៗនៃអន្តេវាសិកដ្ឋាន់ដែលខូចខាត ដោយការចំណាយជាប្លុករបស់ អ្នកស្នាក់នៅ\n៦. ប្រើប្រាស់តុ ឬ ឧបករណ៍យ៉ារអេឡិចត្រូនិច និងកន្លែងដាក់វត្ថុផ្សេងៗឱ្យបានសមរម្យ\n៧. សន្សំសំចៃ ថាមពលអគ្គិសនីឱ្យមានកម្រិតខ្ពស់\n៨. ផ្តល់របាយការណ៍ឱ្យបានឡើងទាត់រៀងរាល់ខែ។\n៩. ចូលរួមដាំកូនឈើ និងដំណាំជីវៈចំរុះតាមផែនការគណៈគ្រប់គ្រង\n១០. ករណីជាក់ ឬវត្ថុរឹងទទៅធំ ឬកូតជញ្ជាំងនាំឱ្យបាក់បែកជញ្ជាំង ត្រូវបញ្ឈប់ពីអន្តេវាសិកដ្ឋាន។\n\nរការ៧- អ្នកសម្របសម្រួលអន្តេវាសិកដ្ឋាន\n១ អ្នកសម្របសម្រួលមានភារកិច្ច ផ្តល់យោបល់ និងធ្វើការណែនាំដល់អ្នកស្នាក់នៅអន្តេវាសិកដ្ឋាន\n២ ផ្តល់សេចក្តីជូនដំណឹងនូវរាល់ពេលគណៈគ្រប់គ្រងស្នើសុំឱ្យធ្វើកិច្ចការណាមួយ\n៣ ត្រួតពិនិត្យ និងស្រង់អវត្តមានប្រចាំថ្ងៃ\n៤ លើកទឹកចិត្តដល់អ្នកស្នាក់នៅអន្តេវាសិកដ្ឋានចូលរួមរាល់សកម្មភាពរបស់វិទ្យាស្ថាន\n\nប្រការ៨- ការចូលរួមក្នុងការងារសង្គម\nអ្នកស្នាក់នៅអន្តេវាសិកដ្ឋានទាំងអស់ត្រូវចូលរួមក្នុងការងារសង្គមផ្សេងៗ វិទ្យាស្ថាន។ ដែលបានកំណត់ដោយវិទ្យាស្ថាន\n\nប្រការ៩. កម្មវិធីប្រជុំ\n១. ប្រធាន និងអនុប្រធានអន្តេវាសិកដ្ឋាន និងបន្ទប់ ត្រូវចូលរួមប្រជុំប្រចាំខែតាមកាលកំណត់ របស់គណៈគ្រប់គ្រង។\n២. អ្នកស្នាក់នៅក្នុងអន្តេវាសិកដ្ឋានទាំងអស់ត្រូវចូលរួមប្រជុំនៅដើមឆមាស និងពេលផ្សេងទៀត ក្នុងករណីចាំបាច់។\n\nរការ១០. ករណីអវត្តមាន\n១. អ្នកស្នាក់អន្តេវាសិកដ្ឋានទាំងអស់ត្រូវផ្តល់ព័ត៌មានដល់ប្រធាន អនុប្រធាន បន្ទប់ និងប្រធាន អនុ-ប្រធាន អន្តេវាសិកដ្ឋាន នៅពេលអវត្តមានពីបន្ទប់ចាប់ពី១យប់ឡើងទៅ។\n២. ករណីឈប់ឥតច្បាប់ដោយពុំដឹងមូលហេតុ និងត្រូវទទួលការណែនាំ (ករណីមិនគោរពតាមមិនត្រូវអនុញ្ញាតឱ្យស្នាក់នៅបន្តទៀតឡើយ)។\n៣. ករណីមានសំណើពីគណៈគ្រប់គ្រងឱ្យជួយកិច្ចការបន្ទាន់ណាមួយ អ្នកស្នាក់នៅទាំងអស់មិនអនុញ្ញាតឱ្យសុំច្បាប់ចេញពីអន្តេវាសិកដ្ឋានឡើយ។\n៤. សុំច្បាប់ពីគណៈគ្រប់គ្រងអន្តេវាសិកដ្ឋាន(ស្រី និងប្រុស)មុន ពីរ ឬបីថ្ងៃមុនចាកចេញ អន្តេវាសិកដ្ឋាន ។ \n\nប្រការ១១. ការសួរសុខទុក្ខ\n១.អ្នកដទៃក្រៅពីអ្នកស្នាក់នៅគ្មានសិទ្ធចូលក្នុងបន្ទប់នៃអន្តេវាសិកដ្ឋានបានឡើយ លុះត្រាតែ មានការអនុញ្ញាតពីគណៈគ្រប់គ្រងអន្តេវាសិកដ្ឋាន និងអ្នករួមបន្ទប់។ មិនអនុញ្ញាតឲ្យនាំមនុស្ស ភេទផ្ទុយគ្នាចូលទៅក្នុងបន្ទប់ដាច់ខាត ទោះជាក្រុមគ្រួសារក៏ដោយ។\n២. មិនអនុញ្ញាតឱ្យមានការសួរសុខទុក្ខចាប់ពីម៉ោង ២២:០០ តទៅ។\n៣. មិនអនុញ្ញាតឱ្យភ្ញៀវស្នាក់នៅក្នុងអន្តេវាសិកដ្ឋានជាដាច់ខាត (លុះត្រាតែមានការអនុញ្ញាត)។\n\nប្រការ១២. ការត្រួតពិនិត្យ\n១. គណៈគ្រប់គ្រងមានសិទ្ធិចូលទៅក្នុងបន្ទប់ដោយមិនចាំបាច់មានវត្តមានអ្នកស្នាក់នៅ ក្នុងករណី មានអគ្គិភ័យ គ្រោះមហន្តរាយ និងព្រឹត្តិការណ៍បន្ទាន់ផ្សេងៗដែលគណៈគ្រប់គ្រងយល់ឃើញថា ជាភាពមិនប្រក្រតី។\n២. គណៈគ្រប់គ្រងមានសិទ្ធិចូលទៅក្នុងបន្ទប់ ដោយផ្តល់ដំណឹងជាមុន ដើម្បីត្រួតពិនិត្យសម្ភារ ផ្សេងៗ។\n\nប្រការ១៣. សន្តិសុខ និងសុវត្ថិភាព\n១.​ អ្នកស្នាក់នៅត្រូវមានស្មារតីប្រុងប្រយ័ត្នខ្ពស់ ចៀសវាងការបាត់បង់ជាយថាហេតុ។\n២. មិនត្រូវទុកចោលរបស់របរមានតម្លៃនៅក្នុងបន្ទប់។ បើមានការបាត់បង់នោះគណៈគ្រប់គ្រងនឹងមិន ទទួលខុសត្រូវឡើង។\n\nប្រការ១៤- កម្រិតវិជ័យ\n១. អ្នកដែលប្រព្រឹត្តខុសនឹងបទបញ្ជាផ្ទៃក្នុងនឹងត្រូវទទួលការព្រមាន ដោយស្តីបន្ទោស ឬលាយលក្ខណ៍\n២. ករណីដែលទទួលបានការព្រមានជាលាយលក្ខណ៍អក្សរវាដង ហើយមិនរាងចាល នឹងត្រូវបញ្ឈប់ការស្នាក់នៅជាស្ថាពរយ៉ាងយូរទៅ៨ម៉ោងក្រោយព្រឹត្តិការណ៍។ \n៣. ករណីធ្ងន់ធ្ងរ និស្សិតត្រូវទទួលការបញ្ឈប់ឱ្យស្នាក់នៅតាមការសម្រេចរបស់អង្គប្រជុំនៃគណៈគ្រប់គ្រង អន្តេវាសិកដ្ឋាន ដោយមិនចាំបាច់មានការព្រមានជាមុន។\n\nប្រការ១៥- រយៈពេលនៃការស្នាក់នៅ\n១. រយៈពេលនៃការស្នាក់នៅចាប់ពីខែវិច្ឆិកា ដល់ខែតុលាឆ្នាំបន្ទាប់។ \n២. និស្សិនត្រូវដាក់ពាក្យសុំស្នាក់នៅរៀងរាល់ដើមឆ្នាំសិក្សា។\n\nប្រការទេ១៦. និស្សិតដែលមានទីលំនៅឆ្ងាយពីវិទ្យាស្ថានត្រូវបានផ្តល់អាទិភាពឱ្យស្នាក់នៅអច្ចេវាសិកដ្ឋាន\n\nប្រការ១៧ បទបញ្ជាផ្ទៃក្នុង ស្តីពី ការស្នាក់នៅក្នុងអន្តេវាសិកដ្ឋានវិទ្យាស្ថានបច្ចេកវិទ្យាកំពង់ស្ពឺណាដែលមាន ខ្លឹមសារផ្ទុយ ត្រូវចាត់ទុកជានិពករណ៍។\n\nប្រការ១៨- បទបញ្ជាផ្ទៃក្នុងនេះមានសុរាលភាពចាប់ពីថ្ងៃចុះហត្ថលេខាតទៅ។\n');

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
  `image` varchar(255) DEFAULT NULL,
  `date` year(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `student_id`, `user_name`, `building`, `room_number`, `accommodation_fee`, `discount`, `water_fee`, `electricity_fee`, `total_fee`, `payment_date`, `status`, `image`, `date`) VALUES
(115, 123456789, '', '', 0, 100000, 0, 10000, 10000, 120000, '2025-06-23 10:00:23', 'Pending', 'received_374383708877274.jpeg.png', '2022'),
(116, 123456789, '', '', 0, 100000, 0, 10000, 10000, 120000, '2025-06-23 10:00:25', 'Pending', 'received_374383708877274.jpeg.png', '2022'),
(117, 11220033, '', 'ABW', 9, 100000, 0, 10000, 10000, 120000, '2025-06-23 10:00:57', 'Pending', 'photo_2025-05-12_23-08-54.jpg', '2025'),
(118, 11004422, '', '', 0, 100000, 0, 10000, 10000, 120000, '2025-06-23 10:10:17', 'Pending', 'Screenshot_20250623-164836.png', '2025');

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
  `stay` date DEFAULT NULL,
  `status` enum('មិនអនុញ្ញាត','អនុញ្ញាត','រង់ចាំ','') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`user_id`, `password`, `student_id`, `lastname`, `name`, `username`, `gender`, `dob`, `address`, `phone_student`, `phone_parent`, `skill`, `education_level`, `year`, `img`, `building`, `room`, `stay`, `status`) VALUES
(179, '$2y$10$25ZJQt50EaMSqKSuq4V8k.9.5pMclV2.euXj0LKOg8YiN7ZXEzMEy', '11220033', 'ម៉ាន់', 'សុវណ្ណា', 'MAN SOVANNA', 'ប្រុស', '2004-06-30', 'ភូមិ ពោធិ៍ ឃុំ ពោធិ៍ ស្រុកកំពង់លែង ខេត្តកំពង់ឆ្នាំង', '0967574404', '0967574402', 'បច្ចេកវិទ្យាកុំព្យូទ័រ', 'បរិញ្ញាបត្រ', 3, 'uploads/476630523_1341863290349455_3504267608563571534_n.jpg', 'ABW', 9, '2025-05-02', 'អនុញ្ញាត'),
(180, '$2y$10$kLdmQgpOgw7y7teYP8obQOqf80dQRCM87ACWwO4URI.67DsC37KUG', '11220034', 'ម៉ាន់', 'អ៊ីណុច', 'Man Enoch', 'ស្រី', '2005-01-14', 'ភូមិ ពោធិ៍ ឃុំ ពោធិ៍ ស្រុក​កំពុងលែង ខេត្តកំពុងឆ្នាំង', '2', '1', 'បច្ចេកវិទ្យាកុំព្យូទ័រ', 'បរិញ្ញាបត្ររង', 2, 'uploads/students.png', 'NT-M', 2, '2023-06-01', 'អនុញ្ញាត'),
(181, '$2y$10$TR/SjxGil7ks.1K.U14U.uGmiyu48uueRJisOv/2laCXZnrD30gma', '11002244', 'វ៉ន', 'សុជាតិ', 'VORN SOJEAT', 'ប្រុស', '2025-05-02', 'kkkkkk', '0000000000', '000000000', 'បច្ចេកវិទ្យាកុំព្យូទ័រ', 'បរិញ្ញាបត្រ', 3, 'uploads/students.png', '', 0, '2024-06-02', 'រង់ចាំ'),
(183, '$2y$10$Ts68DqkjsIv6lFOhc7RUxOf358QMpxpomGKGDuXLZuevHo/zqU8bW', '១១១២២២២', 'វណ្ណដែត', 'អាន', 'AN VANDET', 'ប្រុស', '2025-06-08', 'កបថ', '0000000', '1111111', 'បច្ចេកវិទ្យាកុំព្យូទ័រ', 'បរិញ្ញាបត្រ', 3, 'uploads/inbound2250385186748578339.png', '', 0, '2025-06-08', 'រង់ចាំ'),
(185, '$2y$10$FzAHCuWmwoXNrpqdNGEH9Oz9ctUG8VWKTZuCd6eCvZLqmyQ3sDRve', '11004422', 'ឌី', 'សុភ្្តនាថ', 'DY SOPHEAKNEATH', 'ប្រុស', '2006-01-01', 'ភូមិ ក ឃុំ ខ ស្រុកសាមគ្គីមានជ័យ ខេត្តកំពង់ឆ្នាំង', '0967574404', '092627244', 'បច្ចេកវិទ្យាកុំព្យូទ័រ', 'បរិញ្ញាបត្ររង', 2, 'uploads/profile.jpg', '', 0, '2025-06-08', 'រង់ចាំ'),
(186, '$2y$10$lAibXAeOUpsupjU23FIAlOm0VhD4EBKpILBqJT8v28DJHLJ1NFZtW', '221689400', 'ស្តេច', 'ហែកគ័រ', 'Sdach hacker', 'ប្រុស', '2007-06-12', 'ច្រកឬស្ស អមលាំង ថ្ពង ស្ពឺ', '014869680', '096557458', 'បច្ចេកវិទ្យាកុំព្យូទ័រ', 'បរិញ្ញាបត្រ', 3, 'uploads/Screenshot_20250623_161003_Mobile Legends Bang Bang.jpg', '', 0, '2021-06-01', 'រង់ចាំ'),
(187, '$2y$10$mv66ITINIEQ3ZIYOJv.pAurK0SyxDJ1AyY1.aPY9lIAjvzm9kbSQS', '123456789', 'LIM', 'SEU', 'LIM SEU', 'ប្រុស', '2004-08-18', 'កំពង់ស្ពឺ', '066442393', '0987777777', 'បច្ចេកវិទ្យាកុំព្យូទ័រ', 'បរិញ្ញាបត្រ', 3, 'uploads/received_743012107768308.jpeg', '', 0, '2022-06-01', 'រង់ចាំ'),
(188, '$2y$10$n9WX1QieW2o8B/wTKgY6ZeOKKsOa2VVCY/raxjwZjFZPV1o4Qs7OS', '11005544', 'ដារ៉ា', 'វិការ', 'Dara Vika', 'ប្រុស', '2000-05-07', 'អន្លង់ន្នួរ', '0967574404', '0967574404', 'បច្ចេកវិទ្យាកុំព្យូទ័រ', 'បរិញ្ញាបត្ររង', 2, 'uploads/476630523_1341863290349455_3504267608563571534_n.jpg', '', 0, '2025-06-23', 'អនុញ្ញាត');

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
  `status` enum('pending','rejected','approved') DEFAULT 'pending',
  `re_date` datetime DEFAULT current_timestamp(),
  `admin_username` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reques_alaw`
--

INSERT INTO `reques_alaw` (`student_id`, `user_name`, `sumday`, `first_date`, `end_date`, `reason`, `user_id`, `status`, `re_date`, `admin_username`) VALUES
('11220033', 'ម៉ាន់ សុវណ្ណា', 2, '2025-06-14', '2025-06-15', 'ទៅលេងផ្ទះ', 198, 'pending', '2025-06-12 09:07:08', 'BOUR SOKKHORN'),
('11220033', 'ម៉ាន់ សុវណ្ណា', 4, '2025-06-01', '2025-06-05', 'ជិះទៅជួបគ្រួសារ', 199, 'pending', '2025-06-22 10:43:46', NULL),
('11002244', 'VORN SOJEAT', 4, '2025-06-01', '2025-06-05', 'ជិះទៅជួបគ្រួសារ', 202, 'approved', '2025-06-24 16:06:35', NULL),
('11002244', 'VORN SOJEAT', 4, '2025-06-01', '2025-06-05', 'ជិះទៅជួបគ្រួសារ', 203, 'approved', '2025-06-24 16:08:47', NULL),
('12121212121', 'VORN SOJEAT', 3, '2025-06-26', '2025-06-28', 'go home', 204, 'approved', '2025-06-24 22:26:23', NULL),
('843834534535', 'VORN SOJEAT', 5, '2025-06-26', '2025-06-30', 'go home', 205, 'approved', '2025-06-24 22:28:42', NULL);

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
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `token`, `expires_at`, `user_id`) VALUES
(80, '8d0e2d11c737c15036bbbafe20e4b7ac6cf6c2c6f45a9cfbc9ccab032817e444', '2026-06-25 03:57:50', 181);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addbuilding`
--
ALTER TABLE `addbuilding`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcement_views`
--
ALTER TABLE `announcement_views`
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
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addbuilding`
--
ALTER TABLE `addbuilding`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `announcement_views`
--
ALTER TABLE `announcement_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `request_room`
--
ALTER TABLE `request_room`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reques_alaw`
--
ALTER TABLE `reques_alaw`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `register` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
