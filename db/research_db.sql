-- --------------------------------------------------------
-- db-v2.sql
-- --------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- --------------------------------------------------------
-- department
-- --------------------------------------------------------
CREATE TABLE `department` (
  `department_id` int(100) NOT NULL AUTO_INCREMENT,
  `name`          varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `abbreviation`  varchar(20)  COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`department_id`),
  UNIQUE KEY `uq_dept_abbreviation` (`abbreviation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `department` (`department_id`, `name`, `abbreviation`) VALUES
(1,'Biology','BIO'),(2,'Chemistry','CHEM'),(3,'Computer Science','CS'),
(4,'Physics','PHYS'),(5,'Neuroscience','NEURO'),(6,'Environmental Science','ENV'),
(7,'Biomedical Engineering','BME'),(8,'Mathematics','MATH'),(9,'Materials Science','MSC'),
(10,'Data Science','DS'),(11,'Biochemistry','BIOC'),(12,'Genetics','GEN'),
(13,'Microbiology','MICRO'),(14,'Pharmacology','PHARM'),(15,'Bioinformatics','BINFO'),
(16,'Electrical Engineering','EE'),(17,'Mechanical Engineering','ME'),(18,'Statistics','STAT'),
(19,'Psychology','PSY'),(20,'Cognitive Science','COGS'),(21,'Immunology','IMMUNO'),
(22,'Epidemiology','EPI'),(23,'Astronomy','ASTRO'),(24,'Geology','GEO'),
(25,'Oceanography','OCEAN'),(26,'Chemical Engineering','CHE'),(27,'Robotics','ROB'),
(28,'Nanoscience','NANO'),(29,'Public Health','PH'),(30,'Linguistics','LING');

-- --------------------------------------------------------
-- research_group
-- --------------------------------------------------------
CREATE TABLE `research_group` (
  `group_id` int(100) NOT NULL AUTO_INCREMENT,
  `name`     varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `focus`    varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `research_group` VALUES
(1,'Genomics Lab','Study of genetic sequences and gene expression'),
(2,'Quantum Dynamics Group','Quantum computing and particle interactions'),
(3,'AI in Medicine','Applying machine learning to clinical diagnostics'),
(4,'Climate Modeling Team','Simulation of climate and environmental change'),
(5,'Neural Circuits Lab','Mapping and studying neural connectivity'),
(6,'Biomaterials Research Unit','Development of biocompatible materials'),
(7,'Data Mining Group','Extracting patterns from large-scale datasets'),
(8,'Photonics Lab','Light-matter interaction and optical devices'),
(9,'Structural Biology Group','Protein folding and molecular structures'),
(10,'Computational Chemistry Team','Molecular simulation and drug design'),
(11,'Immunotherapy Lab','Developing T-cell based cancer immunotherapies'),
(12,'Epidemiology Modeling Group','Tracking and forecasting infectious disease spread'),
(13,'Nanoelectronics Unit','Fabrication and characterization of nanoscale devices'),
(14,'Astrobiology Research Team','Investigating conditions for life beyond Earth'),
(15,'Cognitive Neuroscience Lab','Brain imaging and higher cognitive function studies'),
(16,'Marine Biogeochemistry Group','Studying carbon and nitrogen cycles in ocean systems'),
(17,'Robotic Surgery Lab','Designing autonomous surgical assist robots'),
(18,'Population Genetics Group','Analysis of genetic variation across human populations'),
(19,'Atmospheric Chemistry Lab','Measuring trace gases and aerosols in the atmosphere'),
(20,'Soft Matter Physics Group','Studying polymers, gels, and liquid crystal systems'),
(21,'Infectious Disease Genomics','Whole-genome sequencing of bacterial and viral pathogens'),
(22,'Computational Neuroscience Lab','Modeling neural circuits with differential equations'),
(23,'Microfluidics Engineering Group','Lab-on-chip devices for point-of-care diagnostics'),
(24,'Behavioral Ecology Team','Studying animal behavior in natural environments'),
(25,'Geochemistry Research Unit','Isotope tracing in geological formations'),
(26,'Language and Cognition Lab','Neural and computational basis of language processing'),
(27,'Solar Energy Materials Group','Perovskite and thin-film photovoltaic research'),
(28,'Translational Oncology Team','Bridging cancer biology discoveries to clinical trials'),
(29,'Digital Epidemiology Lab','Using social media and mobility data for disease surveillance'),
(30,'Synthetic Biology Group','Engineering biological circuits and metabolic pathways');

-- --------------------------------------------------------
-- person
-- --------------------------------------------------------
CREATE TABLE `person` (
  `person_id` int(100) NOT NULL AUTO_INCREMENT,
  `name`      varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email`     varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`person_id`),
  UNIQUE KEY `uq_person_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `person` VALUES
(1,'Alice Johnson','alice.johnson@uni.edu'),(2,'Bob Martinez','bob.martinez@uni.edu'),
(3,'Carol Chen','carol.chen@uni.edu'),(4,'David Kim','david.kim@uni.edu'),
(5,'Eva Patel','eva.patel@uni.edu'),(6,'Frank Müller','frank.muller@uni.edu'),
(7,'Grace Okafor','grace.okafor@uni.edu'),(8,'Henry Zhao','henry.zhao@uni.edu'),
(9,'Isla Rivera','isla.rivera@uni.edu'),(10,'James Novak','james.novak@uni.edu'),
(11,'Laura Schmidt','laura.schmidt@uni.edu'),(12,'Marcus Thompson','marcus.thompson@uni.edu'),
(13,'Nadia Volkov','nadia.volkov@uni.edu'),(14,'Omar Hassan','omar.hassan@uni.edu'),
(15,'Priya Nair','priya.nair@uni.edu'),(16,'Quinn Fitzgerald','quinn.fitzgerald@uni.edu'),
(17,'Rosa Delgado','rosa.delgado@uni.edu'),(18,'Samuel Osei','samuel.osei@uni.edu'),
(19,'Tanya Bergström','tanya.bergstrom@uni.edu'),(20,'Umar Farooq','umar.farooq@uni.edu'),
(21,'Vera Kowalski','vera.kowalski@uni.edu'),(22,'William Tran','william.tran@uni.edu'),
(23,'Xena Papadopoulos','xena.papadopoulos@uni.edu'),(24,'Yusuf Adeyemi','yusuf.adeyemi@uni.edu'),
(25,'Zoe Lindqvist','zoe.lindqvist@uni.edu'),(26,'Aaron Castillo','aaron.castillo@uni.edu'),
(27,'Beatrice Fontaine','beatrice.fontaine@uni.edu'),(28,'Carlos Mendes','carlos.mendes@uni.edu'),
(29,'Diana Sato','diana.sato@uni.edu'),(30,'Elias Andersen','elias.andersen@uni.edu');

-- --------------------------------------------------------
-- project
-- --------------------------------------------------------
CREATE TABLE `project` (
  `project_id`  int(100)    NOT NULL AUTO_INCREMENT,
  `title`       varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text         COLLATE utf8_unicode_ci,
  `start_date`  date         DEFAULT NULL,
  `end_date`    date         DEFAULT NULL,
  `status`      varchar(50)  COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`project_id`),
  CONSTRAINT `chk_project_status` CHECK (`status` IN ('Active','Completed','On Hold'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `project` VALUES
(1,'CRISPR Gene Editing Study','Investigating CRISPR-Cas9 efficacy in human cell lines','2023-01-15','2024-12-31','Active'),
(2,'Quantum Error Correction','Developing algorithms to minimize quantum decoherence','2022-06-01','2025-05-31','Active'),
(3,'AI Tumor Detection','CNN-based model for early-stage tumor detection in MRI scans','2023-03-01','2024-09-30','Completed'),
(4,'Arctic Ice Melt Simulation','Modeling ice sheet dynamics under climate change scenarios','2021-09-01','2023-08-31','Completed'),
(5,'Synaptic Plasticity Analysis','Studying long-term potentiation in hippocampal neurons','2023-07-01',NULL,'Active'),
(6,'Biodegradable Scaffold Design','Creating scaffolds for bone tissue engineering','2022-11-01','2024-10-31','Active'),
(7,'Social Media Sentiment Mining','Large-scale NLP analysis of public opinion trends','2023-05-15','2024-05-14','Completed'),
(8,'Terahertz Imaging System','Building a sub-millimeter wave imaging device','2024-01-01','2026-01-01','Active'),
(9,'Amyloid Fibril Structure','Cryo-EM study of amyloid aggregation pathways','2023-02-01','2025-01-31','Active'),
(10,'Drug-Receptor Binding Simulation','MD simulations for novel kinase inhibitors','2022-04-01','2024-03-31','Completed'),
(11,'CAR-T Cell Optimization','Engineering chimeric antigen receptors for improved tumor targeting','2023-04-01','2025-03-31','Active'),
(12,'COVID-19 Variant Surveillance','Genomic epidemiology of emerging SARS-CoV-2 variants','2022-01-01','2023-12-31','Completed'),
(13,'Graphene Transistor Fabrication','Developing sub-10nm graphene field-effect transistors','2023-06-01',NULL,'Active'),
(14,'Exoplanet Atmosphere Analysis','Spectroscopic characterization of habitable zone exoplanet atmospheres','2022-08-01','2024-07-31','Active'),
(15,'Working Memory and Aging','fMRI study of prefrontal cortex activity in aging populations','2023-09-01','2025-08-31','Active'),
(16,'Deep Sea Carbon Flux Study','Measuring biological pump efficiency in abyssal zones','2021-05-01','2023-04-30','Completed'),
(17,'Laparoscopic Robot Arm v2','Second-generation autonomous laparoscopic surgical robot','2023-10-01','2026-09-30','Active'),
(18,'Human Migration Genomics','SNP-based analysis of ancient human migration patterns','2022-03-01','2024-02-28','Completed'),
(19,'Urban Air Quality Monitoring','Dense sensor network for real-time urban pollutant mapping','2023-07-01','2025-06-30','Active'),
(20,'Hydrogel Viscoelasticity Study','Rheological characterization of polyacrylamide hydrogels','2022-10-01','2024-09-30','Active'),
(21,'MRSA Resistance Mechanism Mapping','WGS-based identification of antibiotic resistance genes in MRSA','2023-01-01','2024-12-31','Active'),
(22,'Hippocampal Place Cell Simulation','Computational model of spatial navigation in CA1 neurons','2022-07-01','2024-06-30','Active'),
(23,'Droplet Microfluidics for Blood Typing','Encapsulation-based rapid blood group determination platform','2023-05-01','2025-04-30','Active'),
(24,'Migratory Bird Tracking Study','GPS telemetry and foraging behavior of Arctic tern populations','2021-06-01','2023-05-31','Completed'),
(25,'Rare Earth Isotope Profiling','Lanthanide isotope ratios as tracers in sedimentary basins','2022-09-01','2024-08-31','Active'),
(26,'Bilingual Brain Mapping','fMRI and EEG analysis of language switching in bilinguals','2023-11-01',NULL,'Active'),
(27,'Perovskite Solar Cell Stability','Investigating moisture and thermal degradation in perovskite PVs','2023-02-01','2025-01-31','Active'),
(28,'Pancreatic Cancer Biomarker Discovery','Proteomics-based early detection markers for pancreatic ductal carcinoma','2022-05-01','2024-04-30','Active'),
(29,'Flu Mobility Surveillance','Correlating population mobility data with influenza outbreak dynamics','2023-03-01','2024-02-29','Completed'),
(30,'Terpenoid Biosynthesis Engineering','Designing yeast strains for high-yield terpenoid production','2023-08-01',NULL,'Active');

-- --------------------------------------------------------
-- experiment
-- --------------------------------------------------------
CREATE TABLE `experiment` (
  `experiment_id` int(100)    NOT NULL AUTO_INCREMENT,
  `title`         varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `objective`     text         COLLATE utf8_unicode_ci,
  `description`   text         COLLATE utf8_unicode_ci,
  `start_date`    date         DEFAULT NULL,
  `end_date`      date         DEFAULT NULL,
  `status`        varchar(50)  COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`experiment_id`),
  CONSTRAINT `chk_expt_status` CHECK (`status` IN ('Planned','In Progress','Completed'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `experiment` VALUES
(1,'CRISPR Transfection Trial 1','Optimize transfection efficiency','Testing lipofection vs electroporation for Cas9 delivery','2023-02-01','2023-04-30','Completed'),
(2,'Qubit Coherence Time Test','Measure T1 and T2 coherence times','Superconducting qubit characterization at 10 mK','2022-07-01','2022-09-30','Completed'),
(3,'MRI Dataset Labeling','Create annotated training set','Manual labeling of 5000 MRI images by radiologists','2023-03-15','2023-06-15','Completed'),
(4,'Ice Core Sample Analysis','Extract climate proxies from ice cores','Isotope ratio analysis of 10 Arctic ice core samples','2021-10-01','2022-03-31','Completed'),
(5,'LTP Induction in Slices','Induce and record LTP in brain slices','High-frequency stimulation protocol on hippocampal slices','2023-08-01','2023-11-30','Completed'),
(6,'Scaffold Porosity Test','Determine optimal pore size for cell infiltration','SEM imaging and permeability assays on scaffolds','2023-01-01','2023-05-31','Completed'),
(7,'Twitter Corpus Collection','Gather 2M tweets for analysis','Streaming API data collection with geo-tags','2023-05-20','2023-07-20','Completed'),
(8,'THz Source Calibration','Calibrate new THz emitter','Characterize output power and bandwidth of THz source','2024-02-01','2024-04-30','Completed'),
(9,'Fibril Cryo-EM Prep','Prepare amyloid samples for cryo-EM','Vitrification and grid preparation for EM imaging','2023-03-01','2023-05-01','Completed'),
(10,'Kinase Binding Free Energy Calc','Compute binding affinities','Alchemical perturbation calculations for 20 ligands','2022-05-01','2022-10-31','Completed'),
(11,'CAR-T Cytotoxicity Assay','Measure killing efficiency against HER2+ tumor cells','Co-culture assay at varying effector-to-target ratios','2023-05-01','2023-07-31','Completed'),
(12,'Variant Sequencing Run Batch 1','Sequence 500 SARS-CoV-2 isolates','Illumina whole-genome sequencing of clinical swab samples','2022-02-01','2022-05-31','Completed'),
(13,'Graphene CVD Growth Optimization','Maximize monolayer coverage on Cu foil','Chemical vapor deposition at varying CH4/H2 ratios','2023-07-01','2023-10-31','Completed'),
(14,'JWST Spectrum Extraction','Extract transmission spectra from JWST archive data','Pipeline reduction of NIRSpec time-series observations','2022-09-01','2023-01-31','Completed'),
(15,'fMRI Cognitive Task Protocol','Establish n-back task baseline in young adults','Functional MRI scanning of 30 healthy volunteers aged 20-30','2023-10-01','2024-01-31','Completed'),
(16,'Sediment Trap Deployment','Collect sinking particles at 2000 m depth','Moored sediment trap time-series in North Atlantic','2021-06-01','2022-06-01','Completed'),
(17,'Robot Arm Precision Calibration','Achieve sub-mm positional accuracy','Calibration with optical tracker across 200 target positions','2023-11-01','2024-02-28','Completed'),
(18,'Ancient DNA Extraction Trial','Optimize aDNA recovery from degraded bone','Testing silica-based and organic extraction protocols on 50 samples','2022-04-01','2022-07-31','Completed'),
(19,'NO2 Sensor Cross-Validation','Validate electrochemical sensors against reference instrument','Co-located measurements at 5 sites over 4 weeks','2023-08-01','2023-09-30','Completed'),
(20,'Hydrogel Frequency Sweep','Characterize storage and loss moduli','Oscillatory rheology from 0.1 to 100 rad/s','2022-11-01','2023-02-28','Completed'),
(21,'MRSA Whole Genome Sequencing','Generate reference genomes for 100 MRSA isolates','Short-read Illumina sequencing and de novo assembly','2023-02-01','2023-06-30','Completed'),
(22,'Place Cell Firing Rate Simulation','Simulate theta-phase precession in model neurons','Integrate-and-fire network with spatial input tuning curves','2022-08-01','2023-01-31','Completed'),
(23,'Droplet Generation Rate Test','Achieve 1000 droplets/sec throughput','Microfluidic chip testing with varying flow rate combinations','2023-06-01','2023-09-30','Completed'),
(24,'Arctic Tern GPS Tagging','Deploy GPS loggers on 50 breeding individuals','Capture, tag, and release at Svalbard breeding colony','2021-07-01','2021-08-31','Completed'),
(25,'ICP-MS Isotope Measurement','Measure Ce, Nd, Sm isotope ratios in core samples','Acid digestion and ICP-MS analysis of 80 sediment core intervals','2022-10-01','2023-03-31','Completed'),
(26,'EEG Language Switch Recording','Record EEG during cued language-switching task','High-density 128-channel EEG in 20 Spanish-English bilinguals','2023-12-01','2024-03-31','Completed'),
(27,'Humidity Aging Test for Perovskite','Expose cells to 85% RH for 500 hours','Accelerated aging test with J-V characterization every 50 hours','2023-03-01','2023-06-30','Completed'),
(28,'Plasma Proteomics Profiling','Profile 1000 proteins in 200 pancreatic cancer plasma samples','Proximity extension assay using Olink platform','2022-06-01','2022-11-30','Completed'),
(29,'Mobility Data Preprocessing','Clean and aggregate anonymized mobility dataset','Processing 6 months of county-level mobility from smartphone data','2023-03-15','2023-05-15','Completed'),
(30,'Yeast Terpenoid Screening','Screen 50 engineered yeast strains for linalool yield','GC-MS quantification of terpenoid content after 72h fermentation','2023-09-01','2023-12-31','Completed');

-- --------------------------------------------------------
-- task
-- --------------------------------------------------------
CREATE TABLE `task` (
  `task_id`        int(100)    NOT NULL AUTO_INCREMENT,
  `title`          varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description`    text         COLLATE utf8_unicode_ci,
  `status`         varchar(50)  COLLATE utf8_unicode_ci NOT NULL,
  `due_date`       date         DEFAULT NULL,
  `completed_date` date         DEFAULT NULL,
  `progress`       int(3)       DEFAULT 0,
  PRIMARY KEY (`task_id`),
  CONSTRAINT `chk_task_status`   CHECK (`status`   IN ('Not Started','In Progress','Completed')),
  CONSTRAINT `chk_task_progress` CHECK (`progress` BETWEEN 0 AND 100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `task` VALUES
(1,'Design sgRNA sequences','Design 5 guide RNAs targeting BRCA1 locus','Completed','2023-02-28','2023-02-25',100),
(2,'Set up dilution refrigerator','Install and cool down the dilution refrigerator','Completed','2022-07-15','2022-07-12',100),
(3,'Train CNN baseline model','Train ResNet-50 on initial labeled MRI set','Completed','2023-07-01','2023-06-28',100),
(4,'Write climate model report','Summarize findings from simulation runs','Completed','2023-06-30','2023-07-02',100),
(5,'Prepare neuron staining protocol','Optimize immunofluorescence staining for NMDA receptors','In Progress','2024-01-15',NULL,60),
(6,'3D print scaffold prototypes','Print 10 prototype scaffolds using PLA and HA blend','Completed','2023-03-31','2023-03-29',100),
(7,'Build sentiment classifier','Fine-tune BERT model on labeled tweet dataset','Completed','2023-09-01','2023-08-30',100),
(8,'Align optical components','Align beam path for THz spectrometer setup','In Progress','2024-06-01',NULL,45),
(9,'Collect cryo-EM images','Image 200 grid squares at 300 kV','In Progress','2024-02-01',NULL,70),
(10,'Validate docking poses','Cross-check MD poses with experimental IC50 data','Completed','2022-09-30','2022-09-28',100),
(11,'Clone CAR construct into lentiviral vector','Gibson assembly of CD19-targeting CAR into pLenti backbone','Completed','2023-04-30','2023-04-28',100),
(12,'Build variant phylogenetic tree','Maximum likelihood tree of 500 sequenced isolates using IQ-TREE','Completed','2022-06-30','2022-07-01',100),
(13,'Deposit graphene onto SiO2 substrate','Wet transfer of CVD graphene onto 300nm SiO2/Si wafers','Completed','2023-11-30','2023-11-25',100),
(14,'Cross-match JWST targets with Gaia catalog','Astrometric cross-matching of 40 planet-hosting stars','Completed','2022-10-31','2022-10-29',100),
(15,'Recruit fMRI participants aged 60+','Screen and enroll 30 older adult volunteers for aging cohort','In Progress','2024-05-01',NULL,55),
(16,'Analyze sediment trap organic carbon content','CHN elemental analysis of 120 trap samples','Completed','2022-08-31','2022-09-02',100),
(17,'Write robot kinematics controller','Implement inverse kinematics for 6-DOF surgical arm in ROS2','In Progress','2024-07-01',NULL,40),
(18,'Genotype 200 ancient samples with SNP panel','Targeted enrichment and genotyping of 500k SNP array','Completed','2022-09-30','2022-09-28',100),
(19,'Deploy 20 air quality sensor nodes','Install, configure, and network electrochemical sensor units','Completed','2023-08-31','2023-08-30',100),
(20,'Perform SEM imaging of hydrogel cross-section','Freeze-dry and sputter-coat samples for SEM visualization','In Progress','2024-08-01',NULL,30),
(21,'Annotate MRSA resistance genes with ResFinder','Automated resistance gene annotation pipeline for 100 assemblies','Completed','2023-07-31','2023-07-29',100),
(22,'Tune network hyperparameters for place cell model','Grid search over connection weights and noise levels','Completed','2023-03-31','2023-03-30',100),
(23,'Fabricate PDMS microfluidic chips','Soft lithography using SU-8 master molds','Completed','2023-07-31','2023-07-28',100),
(24,'Process and filter GPS tracking data','Remove erroneous fixes and interpolate gaps in telemetry data','Completed','2021-10-31','2021-10-30',100),
(25,'Prepare sediment core sub-samples for ICP-MS','Acid digestion of 80 core intervals in clean lab conditions','Completed','2022-11-30','2022-11-28',100),
(26,'Preprocess EEG data with ICA','Remove ocular and muscle artifacts using independent component analysis','In Progress','2024-06-01',NULL,65),
(27,'Fabricate perovskite solar cell batch 3','Spin-coat MAPbI3 on FTO glass with spiro-OMeTAD HTL','Completed','2023-04-30','2023-04-27',100),
(28,'Literature review on CA19-9 and novel biomarkers','Systematic review of pancreatic cancer biomarker studies 2015-2023','Completed','2022-07-31','2022-07-30',100),
(29,'Merge mobility and flu incidence datasets','Temporal alignment of CDC flu data with mobility indices by county','Completed','2023-06-01','2023-05-31',100),
(30,'Optimize yeast fermentation media composition','Design of experiments varying C:N ratio and trace element concentrations','In Progress','2024-03-01',NULL,50);

-- --------------------------------------------------------
-- literature
-- --------------------------------------------------------
CREATE TABLE `literature` (
  `lit_id`  int(100)     NOT NULL AUTO_INCREMENT,
  `title`   varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `authors` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `year`    year(4)      NOT NULL,
  `journal` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doi`     varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url`     varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `theory`  text         COLLATE utf8_unicode_ci,
  PRIMARY KEY (`lit_id`),
  UNIQUE KEY `uq_lit_doi` (`doi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `literature` VALUES
(1,'A programmable dual-RNA-guided DNA endonuclease in adaptive bacterial immunity','Jinek M, Chylinski K, Fonfara I, Hauer M, Doudna JA, Charpentier E','2012','Science','10.1126/science.1225829','https://doi.org/10.1126/science.1225829','Describes the mechanism of CRISPR-Cas9 as a programmable genome editing tool'),
(2,'Quantum supremacy using a programmable superconducting processor','Arute F et al.','2019','Nature','10.1038/s41586-019-1666-5','https://doi.org/10.1038/s41586-019-1666-5','Demonstrates quantum computational advantage over classical supercomputers'),
(3,'Deep learning for cancer diagnosis: A systematic review','Shen D, Wu G, Suk HI','2017','Annual Review of Biomedical Engineering','10.1146/annurev-bioeng-071516-044442','https://doi.org/10.1146/annurev-bioeng-071516-044442','Reviews deep learning applications in medical image analysis'),
(4,'Global warming and the future of the Arctic','Hansen J et al.','2016','Nature Climate Change','10.1038/nclimate2924','https://doi.org/10.1038/nclimate2924','Predicts irreversible ice loss under continued greenhouse gas emissions'),
(5,'Long-term potentiation – a decade of progress','Malenka RC, Nicoll RA','1999','Science','10.1126/science.285.5435.1870','https://doi.org/10.1126/science.285.5435.1870','Reviews cellular and molecular mechanisms underlying synaptic plasticity'),
(6,'Biomaterials for bone tissue engineering: A review','Rezwan K, Chen QZ, Blaker JJ, Boccaccini AR','2006','Biomaterials','10.1016/j.biomaterials.2006.01.039','https://doi.org/10.1016/j.biomaterials.2006.01.039','Surveys bioactive and biodegradable materials for bone scaffolds'),
(7,'BERT: Pre-training of Deep Bidirectional Transformers for Language Understanding','Devlin J, Chang MW, Lee K, Toutanova K','2019','NAACL-HLT','10.18653/v1/N19-1423','https://doi.org/10.18653/v1/N19-1423','Introduces BERT, a transformer-based model for NLP tasks'),
(8,'Terahertz technology and its applications','Siegel PH','2002','IEEE Transactions on Microwave Theory and Techniques','10.1109/TMTT.2002.808283','https://doi.org/10.1109/TMTT.2002.808283','Reviews THz sources, detectors and imaging applications'),
(9,'Cryo-EM structure of amyloid fibrils from Alzheimer disease brain','Fitzpatrick AWP et al.','2017','Nature','10.1038/nature23002','https://doi.org/10.1038/nature23002','High-resolution structural characterization of disease-relevant amyloid polymorphs'),
(10,'Free Energy Calculations in Structure-Based Drug Design','Cournia Z, Allen B, Sherman W','2017','Journal of Chemical Information and Modeling','10.1021/acs.jcim.7b00169','https://doi.org/10.1021/acs.jcim.7b00169','Reviews alchemical free energy methods and applications in drug discovery'),
(11,'Chimeric antigen receptor T cells for sustained remissions in leukemia','Maude SL et al.','2014','New England Journal of Medicine','10.1056/NEJMoa1407222','https://doi.org/10.1056/NEJMoa1407222','Clinical evidence for durable remission using CD19-targeting CAR-T therapy'),
(12,'SARS-CoV-2 variants of concern and vaccine effectiveness','Lopez Bernal J et al.','2021','New England Journal of Medicine','10.1056/NEJMoa2108891','https://doi.org/10.1056/NEJMoa2108891','Real-world vaccine effectiveness data against Alpha and Delta variants'),
(13,'Electric field effect in atomically thin carbon films','Novoselov KS et al.','2004','Science','10.1126/science.1102896','https://doi.org/10.1126/science.1102896','Discovery of graphene and demonstration of the electric field effect'),
(14,'Atmospheric characterization of the hot Jupiter WASP-39b','JWST Transiting Exoplanet Community Early Release Science Team','2023','Nature','10.1038/s41586-022-05650-9','https://doi.org/10.1038/s41586-022-05650-9','First detection of CO2 and other molecules in exoplanet atmosphere by JWST'),
(15,'Age-related changes in prefrontal cortex activity during working memory','Reuter-Lorenz PA, Cappell KA','2008','Current Directions in Psychological Science','10.1111/j.1467-8721.2008.00567.x','https://doi.org/10.1111/j.1467-8721.2008.00567.x','HAROLD model of hemispheric asymmetry reduction in older adults during cognitive tasks'),
(16,'The biological pump in a high CO2 world','Riebesell U et al.','2009','Marine Ecology Progress Series','10.3354/meps07582','https://doi.org/10.3354/meps07582','Reviews impacts of ocean acidification on particulate organic carbon export'),
(17,'Robot-assisted minimally invasive surgery: a review','Lanfranco AR et al.','2004','Annals of Surgery','10.1097/01.sla.0000103020.19595.7d','https://doi.org/10.1097/01.sla.0000103020.19595.7d','Overview of surgical robot systems and clinical outcomes'),
(18,'The genomic history of ancient Europe','Haak W et al.','2015','Nature','10.1038/nature14317','https://doi.org/10.1038/nature14317','Massive ancient DNA study revealing steppe migration into Neolithic Europe'),
(19,'Urban air pollution and health: A review of epidemiological evidence','Brunekreef B, Holgate ST','2002','Lancet','10.1016/S0140-6736(02)11274-8','https://doi.org/10.1016/S0140-6736(02)11274-8','Epidemiological evidence linking urban air pollutants to respiratory and cardiovascular disease'),
(20,'Mechanical properties of hydrogels and their experimental determination','Anseth KS, Bowman CN, Brannon-Peppas L','1996','Biomaterials','10.1016/0142-9612(96)87284-9','https://doi.org/10.1016/0142-9612(96)87284-9','Reviews theory and measurement of elastic and viscoelastic properties of hydrogels'),
(21,'Molecular epidemiology of methicillin-resistant Staphylococcus aureus','Deurenberg RH, Stobberingh EE','2008','Infection, Genetics and Evolution','10.1016/j.meegid.2007.07.007','https://doi.org/10.1016/j.meegid.2007.07.007','Global MRSA clonal lineages and resistance gene acquisition mechanisms'),
(22,'Theta sequences are essential for internally generated hippocampal firing fields','Wang Y et al.','2020','Nature Neuroscience','10.1038/s41593-019-0559-0','https://doi.org/10.1038/s41593-019-0559-0','Demonstrates theta phase precession as a mechanism for sequential memory retrieval'),
(23,'Droplet microfluidics: a tool for biology, chemistry and nanotechnology','Guo MT et al.','2012','Lab on a Chip','10.1039/c2lc21147e','https://doi.org/10.1039/c2lc21147e','Review of droplet generation, manipulation and applications in high-throughput screening'),
(24,'Long-distance migration of birds and its ecological significance','Alerstam T','2011','Science','10.1126/science.1194106','https://doi.org/10.1126/science.1194106','Review of energetics, navigation and ecological drivers of long-distance avian migration'),
(25,'Rare earth element geochemistry','Taylor SR, McLennan SM','1985','Geochimica et Cosmochimica Acta','10.1016/0016-7037(85)90170-3','https://doi.org/10.1016/0016-7037(85)90170-3','Foundational reference for REE distribution patterns in crustal and mantle rocks'),
(26,'The bilingual brain: Flexibility and control in the human language system','Abutalebi J, Green DW','2016','Psychological Science in the Public Interest','10.1177/1529100616675605','https://doi.org/10.1177/1529100616675605','Neural basis of bilingual language control and the adaptive control hypothesis'),
(27,'Highly efficient perovskite solar cells via improved carrier management','Kim M et al.','2022','Science','10.1126/science.abm953','https://doi.org/10.1126/science.abm953','Interface engineering strategy achieving >25% PCE in perovskite solar cells'),
(28,'Proteomics-based identification of pancreatic cancer biomarkers','Pan S et al.','2009','Journal of Proteome Research','10.1021/pr900008q','https://doi.org/10.1021/pr900008q','Discovery of novel plasma protein biomarkers for early pancreatic cancer detection'),
(29,'Human mobility and the worldwide spread of infectious diseases','Brockmann D, Helbing D','2013','Science','10.1126/science.1245200','https://doi.org/10.1126/science.1245200','Effective distance model linking air traffic mobility to epidemic arrival times'),
(30,'Engineering the third generation of synthetic biology','Khalil AS, Collins JJ','2010','Nature Reviews Genetics','10.1038/nrg2775','https://doi.org/10.1038/nrg2775','Review of genetic circuit design principles and applications in metabolic engineering');

-- --------------------------------------------------------
-- person_dept (M:M — person belongs to department)
-- --------------------------------------------------------
CREATE TABLE `person_dept` (
  `person_id`     int(100) NOT NULL,
  `department_id` int(100) NOT NULL,
  PRIMARY KEY (`person_id`, `department_id`),
  FOREIGN KEY (`person_id`)     REFERENCES `person`     (`person_id`),
  FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `person_dept` VALUES
(1,1),(9,1),(10,2),(30,2),(3,3),(2,4),(8,4),(5,5),(4,6),(19,6),(26,6),
(6,7),(15,9),(7,10),(23,10),(25,10),(11,11),(12,12),(18,12),(14,13),(21,13),
(24,14),(16,15),(20,16),(29,16),(17,17),(22,17),(13,18),(27,18),(28,19);

-- --------------------------------------------------------
-- research_dept (M:M — research group belongs to department)
-- --------------------------------------------------------
CREATE TABLE `research_dept` (
  `group_id`      int(100) NOT NULL,
  `department_id` int(100) NOT NULL,
  PRIMARY KEY (`group_id`, `department_id`),
  FOREIGN KEY (`group_id`)      REFERENCES `research_group` (`group_id`),
  FOREIGN KEY (`department_id`) REFERENCES `department`     (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `research_dept` VALUES
(1,1),(9,1),(24,1),(10,2),(30,2),(3,3),(2,4),(8,4),(5,5),(4,6),(19,6),
(6,7),(23,7),(15,9),(7,10),(22,10),(11,11),(21,11),(28,11),(18,12),(14,13),
(25,14),(16,15),(20,16),(17,17),(13,18),(27,18),(12,19),(29,19),(26,20);

-- --------------------------------------------------------
-- person_group (M:M — person belongs to research group)
-- --------------------------------------------------------
CREATE TABLE `person_group` (
  `person_id` int(100)    NOT NULL,
  `group_id`  int(100)    NOT NULL,
  `role`      varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `join_date` date         DEFAULT NULL,
  PRIMARY KEY (`person_id`, `group_id`),
  FOREIGN KEY (`person_id`) REFERENCES `person`         (`person_id`),
  FOREIGN KEY (`group_id`)  REFERENCES `research_group` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Original 30 + additional M:M entries showing people in multiple groups
INSERT INTO `person_group` VALUES
(1,1,'Principal Investigator','2020-01-10'),
(2,2,'Principal Investigator','2019-06-01'),
(3,3,'Lead Data Scientist','2021-03-15'),
(4,4,'Senior Researcher','2020-09-01'),
(5,5,'Principal Investigator','2021-01-20'),
(6,6,'Researcher','2022-02-01'),
(7,7,'Research Associate','2022-05-10'),
(8,8,'Senior Researcher','2021-11-01'),
(9,9,'Postdoctoral Fellow','2023-01-05'),
(10,10,'Principal Investigator','2020-04-20'),
(11,11,'Principal Investigator','2021-04-01'),
(12,12,'Senior Researcher','2020-11-15'),
(13,13,'Lead Engineer','2022-06-01'),
(14,14,'Principal Investigator','2021-08-01'),
(15,15,'Principal Investigator','2022-09-01'),
(16,16,'Senior Researcher','2020-05-01'),
(17,17,'Lead Engineer','2022-10-01'),
(18,18,'Research Associate','2021-03-01'),
(19,19,'Postdoctoral Fellow','2022-07-01'),
(20,20,'Principal Investigator','2021-10-01'),
(21,21,'Senior Researcher','2022-01-15'),
(22,22,'Postdoctoral Fellow','2021-07-01'),
(23,23,'Lead Engineer','2022-05-15'),
(24,24,'Research Associate','2020-06-01'),
(25,25,'Principal Investigator','2021-09-01'),
(26,26,'Postdoctoral Fellow','2023-01-01'),
(27,27,'Senior Researcher','2022-02-15'),
(28,28,'Principal Investigator','2021-05-01'),
(29,29,'Research Associate','2022-12-01'),
(30,30,'Lead Engineer','2021-08-15'),
-- Cross-group memberships (demonstrating M:M)
(1,11,'Collaborator','2023-06-01'),
(3,7,'Data Consultant','2023-08-01'),
(5,15,'Visiting Researcher','2024-01-10'),
(11,28,'Clinical Advisor','2023-09-01'),
(15,26,'fMRI Specialist','2024-02-01'),
(18,1,'Genomics Consultant','2022-10-01'),
(22,5,'Computational Advisor','2023-03-01'),
(28,11,'Oncology Collaborator','2022-11-01');

-- --------------------------------------------------------
-- group_project (M:M — research group runs projects)
-- --------------------------------------------------------
CREATE TABLE `group_project` (
  `group_id`   int(100) NOT NULL,
  `project_id` int(100) NOT NULL,
  PRIMARY KEY (`group_id`, `project_id`),
  FOREIGN KEY (`group_id`)   REFERENCES `research_group` (`group_id`),
  FOREIGN KEY (`project_id`) REFERENCES `project`        (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `group_project` VALUES
(1,1),(2,2),(3,3),(4,4),(5,5),(6,6),(7,7),(8,8),(9,9),(10,10),
(11,11),(12,12),(13,13),(14,14),(15,15),(16,16),(17,17),(18,18),(19,19),(20,20),
(21,21),(22,22),(23,23),(24,24),(25,25),(26,26),(27,27),(28,28),(29,29),(30,30),
-- Cross-group project involvement
(1,18),(3,15),(11,28),(28,11),(7,3);

-- --------------------------------------------------------
-- project_member (M:M — person is member of project)
-- --------------------------------------------------------
CREATE TABLE `project_member` (
  `person_id`  int(100)    NOT NULL,
  `project_id` int(100)    NOT NULL,
  `role`       varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `join_date`  date         DEFAULT NULL,
  PRIMARY KEY (`person_id`, `project_id`),
  FOREIGN KEY (`person_id`)  REFERENCES `person`  (`person_id`),
  FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Original 30 + additional memberships showing people on multiple projects
INSERT INTO `project_member` VALUES
(1,1,'Project Lead','2023-01-15'),
(2,2,'Project Lead','2022-06-01'),
(3,3,'ML Engineer','2023-03-01'),
(4,4,'Climate Modeler','2021-09-01'),
(5,5,'Neuroscientist','2023-07-01'),
(6,6,'Biomaterials Engineer','2022-11-01'),
(7,7,'NLP Analyst','2023-05-15'),
(8,8,'Optical Engineer','2024-01-01'),
(9,9,'Structural Biologist','2023-02-01'),
(10,10,'Computational Chemist','2022-04-01'),
(11,11,'Project Lead','2023-04-01'),
(12,12,'Genomic Epidemiologist','2022-01-01'),
(13,13,'Nanofabrication Engineer','2023-06-01'),
(14,14,'Observational Astronomer','2022-08-01'),
(15,15,'Cognitive Neuroscientist','2023-09-01'),
(16,16,'Marine Chemist','2021-05-01'),
(17,17,'Robotics Engineer','2023-10-01'),
(18,18,'Bioinformatician','2022-03-01'),
(19,19,'Atmospheric Scientist','2023-07-01'),
(20,20,'Rheologist','2022-10-01'),
(21,21,'Microbiologist','2023-01-01'),
(22,22,'Computational Modeler','2022-07-01'),
(23,23,'Microfluidics Engineer','2023-05-01'),
(24,24,'Field Ecologist','2021-06-01'),
(25,25,'Geochemist','2022-09-01'),
(26,26,'Linguist','2023-11-01'),
(27,27,'Materials Scientist','2023-02-01'),
(28,28,'Oncology Researcher','2022-05-01'),
(29,29,'Epidemiologist','2023-03-01'),
(30,30,'Synthetic Biologist','2023-08-01'),
-- Additional memberships: people on multiple projects
(1,11,'Collaborator','2023-06-15'),
(1,9,'Genomics Advisor','2023-09-01'),
(3,7,'Data Scientist','2023-06-01'),
(5,22,'Neuroscience Collaborator','2023-10-01'),
(11,28,'Clinical Research Liaison','2023-09-15'),
(15,26,'Neuroimaging Specialist','2024-01-15'),
(18,1,'Bioinformatics Support','2023-03-01'),
(22,5,'Computational Modeler','2023-08-01'),
(28,11,'Oncology Collaborator','2022-12-01'),
(12,29,'Epidemiology Consultant','2023-04-01'),
(9,21,'Genomics Specialist','2023-02-15'),
(3,15,'AI Imaging Consultant','2024-01-01');

-- --------------------------------------------------------
-- project_tasks (1:M — project has many tasks)
-- --------------------------------------------------------
CREATE TABLE `project_tasks` (
  `project_id` int(100) NOT NULL,
  `task_id`    int(100) NOT NULL,
  PRIMARY KEY (`project_id`, `task_id`),
  FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`),
  FOREIGN KEY (`task_id`)    REFERENCES `task`    (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `project_tasks` VALUES
(1,1),(2,2),(3,3),(4,4),(5,5),(6,6),(7,7),(8,8),(9,9),(10,10),
(11,11),(12,12),(13,13),(14,14),(15,15),(16,16),(17,17),(18,18),(19,19),(20,20),
(21,21),(22,22),(23,23),(24,24),(25,25),(26,26),(27,27),(28,28),(29,29),(30,30);

-- --------------------------------------------------------
-- task_assignment (M:M — person assigned to task)
-- IMPROVED: multiple tasks per person to demonstrate GROUP BY meaningfully
-- --------------------------------------------------------
CREATE TABLE `task_assignment` (
  `person_id`     int(100) NOT NULL,
  `task_id`       int(100) NOT NULL,
  `assigned_date` date     DEFAULT NULL,
  PRIMARY KEY (`person_id`, `task_id`),
  FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`),
  FOREIGN KEY (`task_id`)   REFERENCES `task`   (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Original 1:1 assignments
INSERT INTO `task_assignment` VALUES
(1,1,'2023-01-20'),(2,2,'2022-06-15'),(3,3,'2023-03-10'),
(4,4,'2023-05-01'),(5,5,'2023-07-10'),(6,6,'2022-12-01'),
(7,7,'2023-05-20'),(8,8,'2024-01-15'),(9,9,'2023-02-10'),
(10,10,'2022-04-10'),(11,11,'2023-04-05'),(12,12,'2022-01-10'),
(13,13,'2023-06-10'),(14,14,'2022-08-15'),(15,15,'2023-09-10'),
(16,16,'2021-05-10'),(17,17,'2023-10-10'),(18,18,'2022-03-10'),
(19,19,'2023-07-10'),(20,20,'2022-10-10'),(21,21,'2023-01-10'),
(22,22,'2022-07-10'),(23,23,'2023-05-10'),(24,24,'2021-06-15'),
(25,25,'2022-09-10'),(26,26,'2023-11-10'),(27,27,'2023-02-10'),
(28,28,'2022-05-10'),(29,29,'2023-03-10'),(30,30,'2023-08-10');

-- Additional assignments: key people assigned to multiple tasks
-- Alice (1): genomics lead, also supports CAR-T and cryo-EM projects
INSERT INTO `task_assignment` VALUES (1,11,'2023-05-01'),(1,9,'2023-09-01');
-- Bob (2): quantum lead, also assists with THz calibration
INSERT INTO `task_assignment` VALUES (2,8,'2024-01-20');
-- Carol (3): ML lead, also supports sentiment and fMRI work
INSERT INTO `task_assignment` VALUES (3,7,'2023-06-01'),(3,15,'2024-01-05');
-- Laura (11): CAR-T lead, also collaborates on oncology literature review
INSERT INTO `task_assignment` VALUES (11,28,'2022-06-01');
-- Priya (15): fMRI lead, also supports bilingual brain EEG work
INSERT INTO `task_assignment` VALUES (15,26,'2023-12-01');
-- Samuel (18): bioinformatics, also supports MRSA and CRISPR
INSERT INTO `task_assignment` VALUES (18,21,'2023-02-01'),(18,1,'2023-01-25');
-- William (22): comp neuro lead, also assists synaptic plasticity
INSERT INTO `task_assignment` VALUES (22,5,'2023-08-01');
-- Carlos (28): oncology lead, also assists CAR-T work
INSERT INTO `task_assignment` VALUES (28,11,'2023-04-10');
-- Marcus (12): genomic epidemiology, also assists flu surveillance
INSERT INTO `task_assignment` VALUES (12,29,'2023-04-01');
-- Isla (9): structural biology, also assists MRSA sequencing
INSERT INTO `task_assignment` VALUES (9,21,'2023-01-15');

-- --------------------------------------------------------
-- project_expt (1:M — project has many experiments)
-- --------------------------------------------------------
CREATE TABLE `project_expt` (
  `project_id`    int(100) NOT NULL,
  `experiment_id` int(100) NOT NULL,
  PRIMARY KEY (`project_id`, `experiment_id`),
  FOREIGN KEY (`project_id`)    REFERENCES `project`    (`project_id`),
  FOREIGN KEY (`experiment_id`) REFERENCES `experiment` (`experiment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `project_expt` VALUES
(1,1),(2,2),(3,3),(4,4),(5,5),(6,6),(7,7),(8,8),(9,9),(10,10),
(11,11),(12,12),(13,13),(14,14),(15,15),(16,16),(17,17),(18,18),(19,19),(20,20),
(21,21),(22,22),(23,23),(24,24),(25,25),(26,26),(27,27),(28,28),(29,29),(30,30);

-- --------------------------------------------------------
-- project_literature (M:M — project references literature)
-- --------------------------------------------------------
CREATE TABLE `project_literature` (
  `project_id` int(100) NOT NULL,
  `lit_id`     int(100) NOT NULL,
  PRIMARY KEY (`project_id`, `lit_id`),
  FOREIGN KEY (`project_id`) REFERENCES `project`    (`project_id`),
  FOREIGN KEY (`lit_id`)     REFERENCES `literature` (`lit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Original 1:1 + cross-references showing M:M
INSERT INTO `project_literature` VALUES
(1,1),(2,2),(3,3),(4,4),(5,5),(6,6),(7,7),(8,8),(9,9),(10,10),
(11,11),(12,12),(13,13),(14,14),(15,15),(16,16),(17,17),(18,18),(19,19),(20,20),
(21,21),(22,22),(23,23),(24,24),(25,25),(26,26),(27,27),(28,28),(29,29),(30,30),
-- Cross-references
(1,11),(3,7),(5,22),(11,1),(15,26),(22,5),(28,11),(12,29);

-- --------------------------------------------------------
-- expt_literature (M:M — experiment references literature)
-- --------------------------------------------------------
CREATE TABLE `expt_literature` (
  `experiment_id` int(100) NOT NULL,
  `lit_id`        int(100) NOT NULL,
  PRIMARY KEY (`experiment_id`, `lit_id`),
  FOREIGN KEY (`experiment_id`) REFERENCES `experiment` (`experiment_id`),
  FOREIGN KEY (`lit_id`)        REFERENCES `literature` (`lit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `expt_literature` VALUES
(1,1),(2,2),(3,3),(4,4),(5,5),(6,6),(7,7),(8,8),(9,9),(10,10),
(11,11),(12,12),(13,13),(14,14),(15,15),(16,16),(17,17),(18,18),(19,19),(20,20),
(21,21),(22,22),(23,23),(24,24),(25,25),(26,26),(27,27),(28,28),(29,29),(30,30);

-- --------------------------------------------------------
-- AUTO_INCREMENT reset
-- --------------------------------------------------------
ALTER TABLE `department`     MODIFY `department_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
ALTER TABLE `experiment`     MODIFY `experiment_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
ALTER TABLE `literature`     MODIFY `lit_id`        int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
ALTER TABLE `person`         MODIFY `person_id`     int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
ALTER TABLE `project`        MODIFY `project_id`    int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
ALTER TABLE `research_group` MODIFY `group_id`      int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
ALTER TABLE `task`           MODIFY `task_id`       int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

COMMIT;