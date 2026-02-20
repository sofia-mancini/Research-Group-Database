USE sofiaman_research_db;

-- --------------------------------------------------------
-- Table structure for table `person`
-- --------------------------------------------------------
CREATE TABLE `person` (

  `person_id` int(100)     NOT NULL AUTO_INCREMENT,

  `name`      varchar(100) COLLATE utf8_unicode_ci NOT NULL,

  `email`     varchar(100) COLLATE utf8_unicode_ci NOT NULL,

  PRIMARY KEY (`person_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
-- Table structure for table `department`
-- --------------------------------------------------------

CREATE TABLE `department` (

`department_id` int(100) NOT NULL AUTO_INCREMENT,

`name`          varchar(100) COLLATE utf8_unicode_ci NOT NULL,

`abbreviation`  varchar(20)  COLLATE utf8_unicode_ci NOT NULL,

PRIMARY KEY (`department_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
-- Table structure for table `research_group`
-- --------------------------------------------------------

CREATE TABLE `research_group` (

`group_id` int(100) NOT NULL AUTO_INCREMENT,

`name`     varchar(100) COLLATE utf8_unicode_ci NOT NULL,

`focus`    varchar(255) COLLATE utf8_unicode_ci NOT NULL,

PRIMARY KEY (`group_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
-- Table structure for table `project`
-- --------------------------------------------------------

CREATE TABLE `project` (

`project_id`  int(100)     NOT NULL AUTO_INCREMENT,

`title`       varchar(100) COLLATE utf8_unicode_ci NOT NULL,

`description` text         COLLATE utf8_unicode_ci,

`start_date`  date,

`end_date`    date,

`status`      varchar(50)  COLLATE utf8_unicode_ci NOT NULL,

PRIMARY KEY (`project_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
-- Table structure for table `experiment`
-- --------------------------------------------------------

CREATE TABLE `experiment` (

`experiment_id` int(100)     NOT NULL AUTO_INCREMENT,

`title`         varchar(100) COLLATE utf8_unicode_ci NOT NULL,

`objective`     text         COLLATE utf8_unicode_ci,

`description`   text         COLLATE utf8_unicode_ci,

`start_date`    date,

`end_date`      date,

`status`        varchar(50)  COLLATE utf8_unicode_ci NOT NULL,

PRIMARY KEY (`experiment_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
-- Table structure for table `task`
-- --------------------------------------------------------

CREATE TABLE `task` (

`task_id`        int(100)     NOT NULL AUTO_INCREMENT,

`title`          varchar(100) COLLATE utf8_unicode_ci NOT NULL,

`description`    text         COLLATE utf8_unicode_ci,

`status`         varchar(50)  COLLATE utf8_unicode_ci NOT NULL,

`due_date`       date,

`completed_date` date,

`progress`       int(3)       DEFAULT 0,

PRIMARY KEY (`task_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
-- Table structure for table `literature`
-- --------------------------------------------------------

CREATE TABLE `literature` (

`lit_id`  int(100)     NOT NULL AUTO_INCREMENT,

`title`   varchar(255) COLLATE utf8_unicode_ci NOT NULL,

`authors` varchar(255) COLLATE utf8_unicode_ci NOT NULL,

`year`    year(4)      NOT NULL,

`journal` varchar(255) COLLATE utf8_unicode_ci,

`doi`     varchar(255) COLLATE utf8_unicode_ci,

`url`     varchar(255) COLLATE utf8_unicode_ci,

`theory`  text         COLLATE utf8_unicode_ci,

PRIMARY KEY (`lit_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



-- --------------------------------------------------------
-- Table structure for relationship table `person_dept`
-- Connects person to department (M:1)
-- Many persons can belong to one department
-- --------------------------------------------------------

CREATE TABLE `person_dept` (

  `person_id`     int(100) NOT NULL,

  `department_id` int(100) NOT NULL,

  PRIMARY KEY (`person_id`, `department_id`),

  FOREIGN KEY (`person_id`)     REFERENCES `person`     (`person_id`),

  FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
-- Table structure for relationship table `research_dept`
-- Connects research_dept to department (M:1)
-- Many research groups can belong to one department
-- --------------------------------------------------------

CREATE TABLE `research_dept` (

  `group_id` int(100) NOT NULL,

  `department_id`    int(100) NOT NULL,

  PRIMARY KEY (`group_id`, `department_id`),

  FOREIGN KEY (`group_id`) REFERENCES `research_group` (`group_id`),

  FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
-- Table structure for relationship table `person_group`
-- Connects person to research_group (M:M)
-- A person can belong to many research groups
-- A research group can have many people
-- Includes role and join_date as relationship attributes
-- --------------------------------------------------------

CREATE TABLE `person_group` (

  `person_id` int(100) NOT NULL,

  `group_id` int(100) NOT NULL,

  `role` varchar(100) COLLATE utf8_unicode_ci NOT NULL,

  `join_date` date,

  PRIMARY KEY (`person_id`, `group_id`),

  FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`),

  FOREIGN KEY (`group_id`) REFERENCES `research_group` (`group_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
-- Relationship table `group_project`
-- Connects research_group to project (1:M)
-- One research group can have many projects     
-- --------------------------------------------------------

CREATE TABLE `group_project` (

  `group_id` int(100) NOT NULL,

  `project_id` int(100) NOT NULL,

  PRIMARY KEY (`group_id`, `project_id`),

  FOREIGN KEY (`group_id`) REFERENCES `research_group` (`group_id`),

  FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
-- Relationship table for  `project_member`
-- Connects person to project (M:M)
-- A person can be a member of many projects
-- A project can have many persons
-- Includes role and join_date as relationship attributes
-- --------------------------------------------------------

CREATE TABLE `project_member` (

  `person_id` int(100) NOT NULL,

  `project_id` int(100) NOT NULL,

  `role` varchar(100) COLLATE utf8_unicode_ci NOT NULL,

  `join_date` date,

  PRIMARY KEY (`person_id`, `project_id`),
  
  FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`),

  FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
-- Relationship table for `project_tasks`
-- Connects project to task (1:M)
-- One project can have many tasks
-- --------------------------------------------------------

CREATE TABLE `project_tasks` (

  `project_id` int(100) NOT NULL,

  `task_id` int(100) NOT NULL,

  PRIMARY KEY (`project_id`, `task_id`),

  FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`),

  FOREIGN KEY (`task_id`) REFERENCES `task` (`task_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
-- Relationship table for `task_assignment`
-- Connects person to task (M:M)
-- A person can be assigned to many tasks
-- A task can be assigned to many persons
-- Includes assigned_date as a relationship attribute
-- --------------------------------------------------------

CREATE TABLE `task_assignment` (

  `person_id`     int(100) NOT NULL,

  `task_id`       int(100) NOT NULL,

  `assigned_date` date,

  PRIMARY KEY (`person_id`, `task_id`),

  FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`),

  FOREIGN KEY (`task_id`)   REFERENCES `task`   (`task_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
-- Relationship table for `project_expt`
-- Connects project to experiment (One-to-Many)
-- One project can have many experiments
-- --------------------------------------------------------

CREATE TABLE `project_expt` (

  `project_id`    int(100) NOT NULL,

  `experiment_id` int(100) NOT NULL,

  PRIMARY KEY (`project_id`, `experiment_id`),

  FOREIGN KEY (`project_id`)    REFERENCES `project`    (`project_id`),

  FOREIGN KEY (`experiment_id`) REFERENCES `experiment` (`experiment_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
-- Relationship table for `project_literature`
-- Connects project to literature (Many-to-Many)
-- A project can have many literature references
-- A literature entry can belong to many projects
-- --------------------------------------------------------

CREATE TABLE `project_literature` (

  `project_id` int(100) NOT NULL,

  `lit_id`     int(100) NOT NULL,

  PRIMARY KEY (`project_id`, `lit_id`),

  FOREIGN KEY (`project_id`) REFERENCES `project`    (`project_id`),

  FOREIGN KEY (`lit_id`)     REFERENCES `literature` (`lit_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
-- Relationship table for `expt_literature`
-- Connects experiment to literature (Many-to-Many)
-- An experiment can reference many literature entries
-- and a literature entry can belong to many experiments
-- --------------------------------------------------------

CREATE TABLE `expt_literature` (

  `experiment_id` int(100) NOT NULL,

  `lit_id`        int(100) NOT NULL,

  PRIMARY KEY (`experiment_id`, `lit_id`),

  FOREIGN KEY (`experiment_id`) REFERENCES `experiment` (`experiment_id`),

  FOREIGN KEY (`lit_id`)        REFERENCES `literature` (`lit_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
