

/*Table structure for table `admin_users` */

DROP TABLE IF EXISTS `admin_users`;

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `admin_users` */

insert  into `admin_users`(`id`,`username`,`password`,`email`,`full_name`,`created_at`) values 
(1,'admin','$2y$12$LyWYKzxd.69LmeVrPMSDDO2PcloJ29c3.WQQcC86i/nEDrxWKnoe2','admin@newsportal.com','प्रधान सम्पादक','2026-04-28 08:16:29');

/*Table structure for table `advertisements` */

DROP TABLE IF EXISTS `advertisements`;

CREATE TABLE `advertisements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(500) DEFAULT NULL,
  `position` enum('header','sidebar','footer','inline') DEFAULT 'sidebar',
  `size` enum('small','medium','large','banner') DEFAULT 'medium',
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `click_count` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `advertisements` */

insert  into `advertisements`(`id`,`title`,`image`,`link`,`position`,`size`,`is_active`,`sort_order`,`start_date`,`end_date`,`click_count`,`created_at`,`updated_at`) values 
(1,'GT News Premium','https://picsum.photos/seed/ad1/300/250','https://example.com/premium','sidebar','medium',1,1,NULL,NULL,0,'2026-04-28 08:16:29','2026-04-28 08:16:29'),
(2,'Mobile Banking App','https://picsum.photos/seed/ad2/300/100','https://example.com/banking','header','banner',1,2,NULL,NULL,0,'2026-04-28 08:16:29','2026-04-28 08:16:29'),
(3,'Online Shopping','https://picsum.photos/seed/ad3/300/200','https://example.com/shopping','sidebar','large',1,3,NULL,NULL,0,'2026-04-28 08:16:30','2026-04-28 08:16:30'),
(4,'Educational Courses','https://picsum.photos/seed/ad4/728/90','https://example.com/courses','footer','banner',1,4,NULL,NULL,0,'2026-04-28 08:16:30','2026-04-28 08:16:30'),
(5,'Real Estate Nepal','https://picsum.photos/seed/ad5/300/150','https://example.com/realestate','inline','medium',1,5,NULL,NULL,0,'2026-04-28 08:16:30','2026-04-28 08:16:30');

/*Table structure for table `articles` */

DROP TABLE IF EXISTS `articles`;

CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `slug` varchar(500) NOT NULL,
  `content` longtext NOT NULL,
  `excerpt` text DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT 'सम्पादक',
  `views` int(11) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_breaking` tinyint(1) DEFAULT 0,
  `status` enum('published','draft') DEFAULT 'published',
  `published_at` timestamp NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `articles` */

insert  into `articles`(`id`,`title`,`slug`,`content`,`excerpt`,`category_id`,`image`,`author`,`views`,`is_featured`,`is_breaking`,`status`,`published_at`,`created_at`,`updated_at`) values 
(1,'प्रधानमन्त्रीले राष्ट्रिय एकता दिवसको अवसरमा देशवासीलाई सम्बोधन गरे','pm-rashtriya-ekta','प्रधानमन्त्रीले आज राष्ट्रिय एकता दिवसको अवसरमा देशवासीलाई सम्बोधन गर्दै देशको एकता र अखण्डता कायम राख्न सबैलाई आह्वान गरेका छन्।\n\nउनले भने, \"नेपाल एक समृद्ध राष्ट्र बन्नका लागि सबै नागरिकले मिलेर काम गर्नु आवश्यक छ। हाम्रो विविधता नै हाम्रो शक्ति हो।\"\n\nप्रधानमन्त्रीले थपे कि आगामी आर्थिक वर्षमा देशको पूर्वाधार विकासमा ठूलो लगानी गरिनेछ। सडक, विद्युत र खानेपानीका क्षेत्रमा अर्बौं रुपैयाँ खर्च गर्ने योजना रहेको उनले जानकारी दिए।\n\nसरकारले गरिबी निवारण, रोजगार सिर्जना र युवा उद्यमशीलता प्रवर्धनमा विशेष ध्यान दिने भएको पनि उनले बताए।','प्रधानमन्त्रीले राष्ट्रिय एकता दिवसमा देशवासीलाई सम्बोधन गर्दै एकता र विकासको आह्वान गरे।',1,'https://picsum.photos/seed/pm1/800/500','राजनीतिक संवाददाता',1249,1,1,'published','2026-04-21 08:16:29','2026-04-28 08:16:29','2026-04-28 20:05:56'),
(2,'संसद्मा बजेट अधिवेशन सुरु: मुख्य बुँदाहरू के के छन्?','bhajet-adhiveshan','संघीय संसद्को बजेट अधिवेशन आज औपचारिक रूपमा सुरु भएको छ। यस अधिवेशनमा आगामी आर्थिक वर्षको बजेट प्रस्तुत गरिनेछ।\n\nअर्थमन्त्रीले संसद्मा बजेट प्रस्तुत गर्दै भने कि यो बजेट जनताको आकाँक्षा पूरा गर्नेखालको हुनेछ।\n\nबजेटका मुख्य बुँदाहरूमा शिक्षामा बजेट वृद्धि, स्वास्थ्य सेवाको विस्तार र कृषि क्षेत्रलाई प्राथमिकता दिनु रहेको छ।','संसद्मा बजेट अधिवेशन सुरु भई अर्थमन्त्रीले मुख्य योजनाहरू सार्वजनिक गरे।',1,'https://picsum.photos/seed/bj2/800/500','संसद् संवाददाता',892,0,0,'published','2026-04-02 08:16:29','2026-04-28 08:16:29','2026-04-28 08:16:29'),
(3,'काठमाडौंमा नयाँ मेट्रो रेल योजना अगाडि बढ्यो','kathmandu-metro','काठमाडौं उपत्यकामा नयाँ मेट्रो रेल सञ्जाल निर्माणको योजना थप अगाडि बढेको छ। सरकारले यस परियोजनाका लागि विदेशी लगानी आकर्षण गर्ने प्रयास तेज गरेको छ।\n\nयो परियोजना पूरा भएपछि काठमाडौं, ललितपुर र भक्तपुरका नागरिकहरूलाई सुगम यातायात सुविधा मिल्नेछ।\n\nअधिकारीहरूका अनुसार निर्माण कार्य आगामी वर्षभित्र सुरु हुने सम्भावना छ।','काठमाडौंमा मेट्रो रेल परियोजना अगाडि बढ्दै, सरकारले विदेशी लगानी खोज्दै।',2,'https://picsum.photos/seed/metro3/800/500','नगर संवाददाता',2342,1,0,'published','2026-04-10 08:16:29','2026-04-28 08:16:29','2026-04-28 08:18:55'),
(4,'नेपालमा विद्युत उत्पादन नयाँ कीर्तिमान: ३ हजार मेगावाट नाघ्यो','bijuli-record','नेपालको विद्युत उत्पादन पहिलो पटक ३ हजार मेगावाट नाघेको छ। यो ऐतिहासिक उपलब्धि नेपालको ऊर्जा क्षेत्रका लागि मील पत्थर मानिएको छ।\n\nनेपाल विद्युत प्राधिकरणका अनुसार यस उपलब्धिले भारत र बंगलादेशमा विद्युत निर्यातको ढोका थप खुल्नेछ।\n\nविशेषज्ञहरू भन्छन् कि यदि यो गतिले विद्युत उत्पादन जारी रह्यो भने नेपाल आगामी दशकमा ऊर्जा निर्यातमुखी देश बन्न सक्छ।','नेपालको विद्युत उत्पादन ३ हजार मेगावाट नाघी ऐतिहासिक कीर्तिमान स्थापित।',2,'https://picsum.photos/seed/bj4/800/500','ऊर्जा संवाददाता',1876,1,1,'published','2026-04-14 08:16:29','2026-04-28 08:16:29','2026-04-28 08:16:29'),
(5,'भारतसँगको व्यापार सम्झौतामा नयाँ प्रावधानहरू थपिए','india-trade','नेपाल र भारतबीचको व्यापार तथा पारवहन सन्धिमा नयाँ प्रावधानहरू थपिएका छन्।\n\nयी प्रावधानले नेपाली निर्यातकर्ताहरूलाई भारतीय बजारमा पहुँच सजिलो बनाउनेछ। विशेष गरी कृषि उत्पादन र हस्तकला वस्तुहरूलाई फाइदा हुनेछ।\n\nदुई देशबीचको सम्बन्ध थप सुदृढ गर्ने उद्देश्यले यो सम्झौता महत्त्वपूर्ण मानिएको छ।','नेपाल-भारत व्यापार सम्झौतामा नयाँ प्रावधान थपिई नेपाली निर्यातकर्तालाई फाइदा।',3,'https://picsum.photos/seed/ind5/800/500','परराष्ट्र संवाददाता',654,0,0,'published','2026-04-10 08:16:29','2026-04-28 08:16:29','2026-04-28 08:16:29'),
(6,'शेयर बजारमा तेजी: नेप्से ३ हजार बिन्दु नजिक','nepse-teji','नेपाल शेयर बजार (नेप्से) आज ३ हजार बिन्दु नजिक पुगेको छ। यो गत एक वर्षमा नेप्सेको उच्चतम विन्दु हो।\n\nलगानीकर्ताहरूको बढ्दो विश्वास र बैंकिङ क्षेत्रको राम्रो प्रदर्शनले बजारलाई माथि उठाएको विश्लेषकहरू बताउँछन्।\n\nसेबोनका अनुसार आज कारोबार रकम २ अर्ब नाघेको छ जुन गत महिनाभन्दा ३५ प्रतिशत बढी हो।','नेप्से ३ हजार बिन्दु नजिक पुग्दा लगानीकर्ताहरूमा उत्साह छाएको।',4,'https://picsum.photos/seed/nepse6/800/500','आर्थिक संवाददाता',3214,1,0,'published','2026-04-12 08:16:29','2026-04-28 08:16:29','2026-04-28 08:16:29'),
(7,'पर्यटन क्षेत्रमा रेकर्ड वृद्धि: ११ लाख पर्यटक नेपाल भित्रिए','tourism-record','यस वर्ष नेपालमा ११ लाखभन्दा बढी विदेशी पर्यटक आएका छन् जुन नेपालको इतिहासमा सर्वाधिक हो।\n\nपर्यटन बोर्डका अनुसार सगरमाथा आधार शिविरमा यस वर्ष ५० हजारभन्दा बढी पर्यटक पुगेका छन्।\n\nपर्यटन क्षेत्रबाट यस वर्ष करिब ३ अर्ब अमेरिकी डलर आम्दानी हुने अनुमान गरिएको छ।','नेपालमा ११ लाख पर्यटक आई रेकर्ड स्थापित, ३ अर्ब डलर आम्दानी अनुमान।',4,'https://picsum.photos/seed/tour7/800/500','पर्यटन संवाददाता',4522,1,1,'published','2026-04-01 08:16:29','2026-04-28 08:16:29','2026-04-28 08:28:16'),
(8,'नेपाली क्रिकेट टोलीले एसिया कपमा ऐतिहासिक जित हात पार्‍यो','cricket-asia-cup','नेपाली क्रिकेट टोलीले एसिया कपको महत्त्वपूर्ण खेलमा शानदार जित हात पारेको छ।\n\nरोहित पौडेलको अगुवाइमा नेपाली टोलीले लक्ष्य पछ्याउँदै सफलता पाएको हो। उनले ७५ रनको महत्त्वपूर्ण पारी खेले।\n\nयो जित नेपाली क्रिकेटको इतिहासमा मील पत्थर मानिएको छ। सम्पूर्ण देशमा खुसीको लहर छाएको छ।','नेपाली क्रिकेट टोलीले एसिया कपमा ऐतिहासिक जित दर्ता गर्‍यो।',5,'https://picsum.photos/seed/cricket8/800/500','खेल संवाददाता',7832,1,1,'published','2026-04-02 08:16:29','2026-04-28 08:16:29','2026-04-28 08:16:29'),
(9,'साग खेलकुदमा नेपालले सुनको पदक जित्यो','sag-gold','दक्षिण एसियाली खेलकुद (साग) प्रतियोगितामा नेपाली खेलाडीले सुनको पदक जितेका छन्।\n\nकराते र वुसुमा नेपालले थप पदक थप्दै पदक तालिकामा राम्रो स्थान कायम गरेको छ।\n\nखेलकुद मन्त्रीले नेपाली खेलाडीहरूको प्रदर्शनप्रति सन्तोष व्यक्त गर्दै भने कि खेलकुद विकासमा थप लगानी गरिनेछ।','साग खेलकुदमा नेपाली खेलाडीले सुनको पदक जित्दै राम्रो प्रदर्शन।',5,'https://picsum.photos/seed/sag9/800/500','खेल संवाददाता',2134,0,0,'published','2026-04-06 08:16:29','2026-04-28 08:16:29','2026-04-28 08:16:29'),
(10,'स्वास्थ्य मन्त्रालयले नयाँ टीकाकरण अभियान सुरु गर्‍यो','tikakaran','स्वास्थ्य तथा जनसंख्या मन्त्रालयले देशव्यापी टीकाकरण अभियान सुरु गरेको छ।\n\nयस अभियान अन्तर्गत ५ वर्षमुनिका बच्चाहरूलाई निःशुल्क टीकाकरण गरिनेछ।\n\nमन्त्रालयका अनुसार यस पटकको अभियानमा ३० लाखभन्दा बढी बच्चाहरूलाई टीका लगाउने लक्ष्य राखिएको छ।','स्वास्थ्य मन्त्रालयले ३० लाख बच्चालाई लक्षित गरी देशव्यापी टीकाकरण अभियान सुरु।',6,'https://picsum.photos/seed/health10/800/500','स्वास्थ्य संवाददाता',1123,0,0,'published','2026-04-27 08:16:29','2026-04-28 08:16:29','2026-04-28 08:16:29'),
(11,'नेपालमा ५जी इन्टरनेट सेवा सुरु हुने तयारी','5g-nepal','नेपाल दूरसञ्चार प्राधिकरणले देशमा ५जी इन्टरनेट सेवा सुरु गर्ने तयारी गरिरहेको छ।\n\nआगामी वर्षभित्र काठमाडौं उपत्यकामा परीक्षण सुरु हुने अधिकारीहरूले जानकारी दिएका छन्।\n\n५जी सेवाले इन्टरनेट गति अहिलेभन्दा दश गुणा बढाउने अपेक्षा गरिएको छ।','नेपालमा ५जी इन्टरनेट सेवाको तयारी सुरु, काठमाडौंबाट परीक्षण हुने।',7,'https://picsum.photos/seed/5g11/800/500','प्रविधि संवाददाता',3461,0,0,'published','2026-04-28 08:16:29','2026-04-28 08:16:29','2026-04-28 08:35:30'),
(12,'एसईई परीक्षाफल प्रकाशन: कति विद्यार्थी उत्तीर्ण?','see-result','राष्ट्रिय परीक्षा बोर्डले माध्यमिक शिक्षा परीक्षा (एसईई) को परीक्षाफल प्रकाशन गरेको छ।\n\nयस पटक कुल उत्तीर्ण प्रतिशत ७८ रहेको छ जुन गत वर्षभन्दा ५ प्रतिशत बढी हो।\n\nजिल्लागत रूपमा काठमाडौंको नतिजा सर्वोत्तम रहेको बोर्डले जानकारी दिएको छ।','एसईई परीक्षाफल प्रकाशन, ७८ प्रतिशत उत्तीर्ण भई गत वर्षभन्दा सुधार।',9,'https://picsum.photos/seed/see12/800/500','शिक्षा संवाददाता',8923,1,0,'published','2026-04-02 08:16:29','2026-04-28 08:16:29','2026-04-28 08:31:19');

/*Table structure for table `breaking_news` */

DROP TABLE IF EXISTS `breaking_news`;

CREATE TABLE `breaking_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `breaking_news` */

insert  into `breaking_news`(`id`,`text`,`is_active`,`created_at`) values 
(1,'काठमाडौंमा आज बिहान ठूलो भूकम्प गयो, तत्काल सुरक्षित स्थानमा जानुहोस्',1,'2026-04-28 08:16:29'),
(2,'प्रधानमन्त्रीले आजदेखि नयाँ आर्थिक नीति लागू गर्ने घोषणा गरे',1,'2026-04-28 08:16:29'),
(3,'नेपाली क्रिकेट टोलीले एसिया कप जित्यो - देशभर खुसीको लहर',1,'2026-04-28 08:16:29'),
(4,'नेप्से ३ हजार बिन्दु नाघ्यो, शेयर बजारमा ठूलो तेजी',1,'2026-04-28 08:16:29'),
(5,'नेपालमा ५जी परीक्षण सुरु हुने, काठमाडौंवासीलाई छिटो इन्टरनेट',1,'2026-04-28 08:16:29');

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `color` varchar(20) DEFAULT '#c0392b',
  `sort_order` int(11) DEFAULT 0,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `categories` */

insert  into `categories`(`id`,`name`,`slug`,`color`,`sort_order`,`parent_id`) values 
(1,'राजनीति','rajniti','#c0392b',1,NULL),
(2,'देश','desh','#2980b9',2,NULL),
(3,'अन्तर्राष्ट्रिय','antarrashtriya','#27ae60',3,NULL),
(4,'अर्थ/वाणिज्य','artha','#e67e22',4,NULL),
(5,'खेलकुद','khelkud','#8e44ad',5,NULL),
(6,'स्वास्थ्य','swasthya','#16a085',6,NULL),
(7,'प्रविधि','prabidhi','#2c3e50',7,NULL),
(8,'मनोरञ्जन','manoranjan','#e91e63',8,NULL),
(9,'शिक्षा','shiksha','#795548',9,NULL),
(10,'प्रदेश','pradesh','#607d8b',10,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
