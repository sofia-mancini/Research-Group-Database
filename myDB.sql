USE sofiaman_research_db;

-- --------------------------------------------------------
-- Table structure for table `Person`
-- --------------------------------------------------------
CREATE TABLE `Person` (
  `ID`    int(100)     NOT NULL AUTO_INCREMENT,
  `name`  varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `role`  varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

-- Table structure for table `Department`

-- --------------------------------------------------------

CREATE TABLE `Department` (

`department_id` int(100) NOT NULL AUTO_INCREMENT,

`name`          varchar(100) COLLATE utf8_unicode_ci NOT NULL,

`abbreviation`  varchar(20)  COLLATE utf8_unicode_ci NOT NULL,

PRIMARY KEY (`department_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `Research_Group`

-- --------------------------------------------------------

CREATE TABLE `Research_Group` (

`group_id` int(100) NOT NULL AUTO_INCREMENT,

`name`     varchar(100) COLLATE utf8_unicode_ci NOT NULL,

`focus`    varchar(255) COLLATE utf8_unicode_ci NOT NULL,

PRIMARY KEY (`group_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `Project`

-- --------------------------------------------------------

CREATE TABLE `Project` (

`project_id`  int(100)     NOT NULL AUTO_INCREMENT,

`title`       varchar(100) COLLATE utf8_unicode_ci NOT NULL,

`description` text         COLLATE utf8_unicode_ci,

`start_date`  date,

`end_date`    date,

`status`      varchar(50)  COLLATE utf8_unicode_ci NOT NULL,

PRIMARY KEY (`project_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `Experiment`

-- --------------------------------------------------------

CREATE TABLE `Experiment` (

`experiment_id` int(100)     NOT NULL AUTO_INCREMENT,

`title`         varchar(100) COLLATE utf8_unicode_ci NOT NULL,

`theory`        text         COLLATE utf8_unicode_ci,

`start_date`    date,

`end_date`      date,

`status`        varchar(50)  COLLATE utf8_unicode_ci NOT NULL,

PRIMARY KEY (`experiment_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `Task`

-- --------------------------------------------------------

CREATE TABLE `Task` (

`task_id`        int(100)     NOT NULL AUTO_INCREMENT,

`title`          varchar(100) COLLATE utf8_unicode_ci NOT NULL,

`theory`         text         COLLATE utf8_unicode_ci,

`status`         varchar(50)  COLLATE utf8_unicode_ci NOT NULL,

`due_date`       date,

`completed_date` date,

PRIMARY KEY (`task_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `Literature`

-- --------------------------------------------------------

CREATE TABLE `Literature` (

`lit_id`  int(100)     NOT NULL AUTO_INCREMENT,

`title`   varchar(255) COLLATE utf8_unicode_ci NOT NULL,

`authors` varchar(255) COLLATE utf8_unicode_ci NOT NULL,

`year`    year(4)      NOT NULL,

`journal` varchar(255) COLLATE utf8_unicode_ci,

`doi`     varchar(255) COLLATE utf8_unicode_ci,

`url`     varchar(255) COLLATE utf8_unicode_ci,

PRIMARY KEY (`lit_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;