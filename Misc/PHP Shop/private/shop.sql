/* 
SQLyog v4.04
Host - compaq.vgsoftware.com : Database - shop
**************************************************************
Server version 4.1.13-log
*/

create database if not exists `shop`;

use `shop`;

/*
Table structure for category
*/

drop table if exists `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL default '0',
  `category_group_id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table data for shop.category
*/

INSERT INTO `category` VALUES 
(7,1,'Fighting'),
(6,1,'Sports'),
(5,1,'Shooters'),
(4,1,'Platform'),
(3,1,'Puzzle'),
(2,1,'Action'),
(1,1,'RPG'),
(8,1,'Racing');

/*
Table structure for category_group
*/

drop table if exists `category_group`;
CREATE TABLE `category_group` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table data for shop.category_group
*/

INSERT INTO `category_group` VALUES 
(1,'Video Games');

/*
Table structure for category_group_seq
*/

drop table if exists `category_group_seq`;
CREATE TABLE `category_group_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table data for shop.category_group_seq
*/

INSERT INTO `category_group_seq` VALUES 
(1);

/*
Table structure for category_seq
*/

drop table if exists `category_seq`;
CREATE TABLE `category_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table data for shop.category_seq
*/

INSERT INTO `category_seq` VALUES 
(8);

/*
Table structure for country
*/

drop table if exists `country`;
CREATE TABLE `country` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table data for shop.country
*/

INSERT INTO `country` VALUES 
(75,'Chad'),
(74,'Central African Republic'),
(73,'Cape Verde'),
(72,'Canada'),
(71,'Cameroon'),
(70,'Cambodia'),
(69,'Burundi'),
(68,'Burkina Faso'),
(67,'Bulgaria'),
(66,'Brunei'),
(65,'Brazil'),
(64,'Botswana'),
(63,'Bosnia and Herzegovina'),
(62,'Bolivia'),
(61,'Bhutan'),
(60,'Benin'),
(59,'Belize'),
(58,'Belgium'),
(57,'Belarus'),
(56,'Barbados'),
(55,'Bangladesh'),
(54,'Bahrain'),
(53,'Bahamas'),
(52,'Azerbaijan'),
(51,'Austria'),
(50,'Australia'),
(49,'Armenia'),
(48,'Argentina'),
(47,'Antigua and Barbuda'),
(46,'Angola'),
(45,'Andorra'),
(44,'Algeria'),
(43,'Albania'),
(42,'Afghanistan'),
(76,'Chile'),
(77,'China'),
(78,'Colombia'),
(79,'Comoros'),
(80,'Congo'),
(81,'Congo'),
(82,'Costa Rica'),
(83,'Côte dIvoire'),
(84,'Croatia'),
(85,'Cuba'),
(86,'Cyprus'),
(87,'Czech Republic'),
(88,'Denmark'),
(89,'Djibouti'),
(90,'Dominica'),
(91,'Dominican Republic'),
(92,'East Timor'),
(93,'Ecuador'),
(94,'Egypt'),
(95,'El Salvador'),
(96,'Equatorial Guinea'),
(97,'Eritrea'),
(98,'Estonia'),
(99,'Ethiopia'),
(100,'Fiji'),
(101,'Finland'),
(102,'France'),
(103,'Gabon'),
(104,'Gambia, The'),
(105,'Georgia'),
(106,'Germany'),
(107,'Ghana'),
(108,'Greece'),
(109,'Grenada'),
(110,'Guatemala'),
(111,'Guinea'),
(112,'Guinea-Bissau'),
(113,'Guyana'),
(114,'Haiti'),
(115,'Honduras'),
(116,'Hungary'),
(117,'Iceland'),
(118,'India'),
(119,'Indonesia'),
(120,'Iran'),
(121,'Iraq'),
(122,'Ireland'),
(123,'Israel'),
(124,'Italy'),
(125,'Jamaica'),
(126,'Japan'),
(127,'Jordan'),
(128,'Kazakhstan'),
(129,'Kenya'),
(130,'Kiribati'),
(131,'Korea, North'),
(132,'Korea, South'),
(133,'Kuwait'),
(134,'Kyrgyzstan'),
(135,'Laos'),
(136,'Latvia'),
(137,'Lebanon'),
(138,'Lesotho'),
(139,'Liberia'),
(141,'Libya'),
(142,'Liechtenstein'),
(143,'Lithuania'),
(144,'Luxembourg'),
(145,'Macedonia'),
(146,'Madagascar'),
(147,'Malawi'),
(148,'Malaysia'),
(149,'Maldives'),
(150,'Mali'),
(151,'Malta'),
(152,'Marshall Islands'),
(153,'Mauritania'),
(154,'Mauritius'),
(155,'Mexico'),
(156,'Micronesia'),
(157,'Moldova'),
(158,'Monaco'),
(159,'Mongolia'),
(160,'Morocco'),
(161,'Mozambique'),
(162,'Myanmar'),
(163,'Namibia'),
(164,'Nauru'),
(165,'Nepal'),
(166,'Netherlands'),
(167,'New Zealand'),
(168,'Nicaragua'),
(169,'Niger'),
(170,'Nigeria'),
(171,'Norway'),
(172,'Oman'),
(173,'Pakistan'),
(174,'Palau'),
(175,'Panama'),
(176,'Papua New Guinea'),
(177,'Paraguay'),
(178,'Peru'),
(179,'Philippines'),
(180,'Poland'),
(181,'Portugal'),
(182,'Qatar'),
(183,'Romania'),
(184,'Russia'),
(185,'Rwanda'),
(186,'Saint Kitts and Nevis'),
(187,'Saint Lucia'),
(188,'Saint Vincent'),
(189,'Samoa'),
(190,'San Marino'),
(191,'Sao Tome and Principe'),
(192,'Saudi Arabia'),
(193,'Senegal'),
(194,'Serbia and Montenegro'),
(195,'Seychelles'),
(196,'Sierra Leone'),
(197,'Singapore'),
(198,'Slovakia'),
(199,'Slovenia'),
(200,'Solomon Islands'),
(201,'Somalia'),
(202,'South Africa'),
(203,'Spain'),
(204,'Sri Lanka'),
(205,'Sudan'),
(206,'Suriname'),
(207,'Swaziland'),
(208,'Sweden'),
(209,'Switzerland'),
(210,'Syria'),
(211,'Taiwan'),
(212,'Tajikistan'),
(213,'Tanzania'),
(214,'Thailand'),
(215,'Togo'),
(216,'Tonga'),
(217,'Trinidad and Tobago'),
(218,'Tunisia'),
(219,'Turkey'),
(220,'Turkmenistan'),
(221,'Tuvalu'),
(222,'Uganda'),
(223,'Ukraine'),
(224,'United Arab Emirates'),
(225,'United Kingdom'),
(226,'United States'),
(227,'Uruguay'),
(228,'Uzbekistan'),
(229,'Vanuatu'),
(230,'Vatican City'),
(231,'Venezuela'),
(232,'Vietnam'),
(233,'Western Sahara'),
(234,'Yemen'),
(235,'Zambia'),
(236,'Zimbabwe');

/*
Table structure for customer
*/

drop table if exists `customer`;
CREATE TABLE `customer` (
  `id` int(10) unsigned NOT NULL default '0',
  `email` varchar(64) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `name` varchar(50) NOT NULL default '',
  `address` varchar(50) NOT NULL default '',
  `city` varchar(50) default NULL,
  `state` varchar(50) NOT NULL default '',
  `post_code` varchar(10) NOT NULL default '',
  `country_id` int(11) NOT NULL default '0',
  `customer_type` int(11) NOT NULL default '0',
  `currency` varchar(5) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table structure for customer_seq
*/

drop table if exists `customer_seq`;
CREATE TABLE `customer_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table data for shop.customer_seq
*/

INSERT INTO `customer_seq` VALUES 
(0);

/*
Table structure for invoice
*/

drop table if exists `invoice`;
CREATE TABLE `invoice` (
  `id` int(11) NOT NULL default '0',
  `customer_id` int(11) NOT NULL default '0',
  `order_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `total_price` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table structure for invoice_item
*/

drop table if exists `invoice_item`;
CREATE TABLE `invoice_item` (
  `id` int(11) NOT NULL default '0',
  `invoice_id` int(11) NOT NULL default '0',
  `item_id` int(11) NOT NULL default '0',
  `quantity` int(11) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table structure for invoice_item_seq
*/

drop table if exists `invoice_item_seq`;
CREATE TABLE `invoice_item_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table data for shop.invoice_item_seq
*/

INSERT INTO `invoice_item_seq` VALUES 
(0);

/*
Table structure for invoice_seq
*/

drop table if exists `invoice_seq`;
CREATE TABLE `invoice_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table data for shop.invoice_seq
*/

INSERT INTO `invoice_seq` VALUES 
(0);

/*
Table structure for item
*/

drop table if exists `item`;
CREATE TABLE `item` (
  `id` int(11) NOT NULL default '0',
  `category_id` int(11) NOT NULL default '0',
  `product_group_id` int(11) NOT NULL default '0',
  `region_id` int(11) NOT NULL default '0',
  `rrp` decimal(10,2) NOT NULL default '0.00',
  `name` varchar(50) NOT NULL default '',
  `description` text NOT NULL,
  `picture` varchar(255) default NULL,
  `small_picture` varchar(255) default NULL,
  `release_date` timestamp NULL default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table data for shop.item
*/

INSERT INTO `item` VALUES 
(27,2,1,1,59.99,'Grand Theft Auto - San Andreas','Five years ago Carl Johnson escaped from the pressures of life in Los Santos, San Andreas... a city tearing itself apart with gang trouble, drugs and corruption. Where filmstars and millionaires do their best to avoid the dealers and gangbangers. Now, it\'s the early 90s. Carl\'s got to go home. His mother has been murdered, his family has fallen apart and his childhood friends are all heading towards disaster. On his return to the neighborhood, a couple of corrupt cops frame him for homicide. CJ is forced on a journey that takes him across the entire state of San Andreas, to save his family and to take control of the streets.','/images/upload/27.jpg','/images/upload/27_small.jpg','2004-10-29 00:00:00'),
(28,2,1,1,59.99,'Grand Theft Auto 3','Liberty City, the worst place in America. You\'ve been betrayed and left for dead. Now you\'re taking revenge, unless the city gets you first. Mob bosses need a favour, crooked cops need help and street gangs want you dead. You\'ll have to rob, steal and kill just to stay out of serious trouble. The new perspective and stunning graphics will now allow an even wider audience to explore their inner criminal and immerse themselves in all the dangers, threats and possibilities of a city without end, and where anything can happen.','/images/upload/28.jpg','/images/upload/28_small.jpg','2001-10-27 00:00:00'),
(29,2,1,1,59.99,'Grand Theft Auto - Vice City','Grand Theft Auto: Vice City will surpass the unprecedented gameplay, cinematic graphics and immersive audio experience that gamers associate with Grand Theft Auto 3. It\'s an entirely new game set in the \'80s, the location is Vice City, and the vibe is glamour, power and corruption.','/images/upload/29.jpg','/images/upload/29_small.jpg','2002-10-15 00:00:00'),
(26,8,1,1,59.99,'Gran Turismo 3','Gran Turismo 3 A-spec offers the highest possible level of involvement, realism, thrill and perfection as you get the chance to drive your own dream car. The game makes full use of the PlayStation®2’s superior next-generation technology to produce the ultimate in driving simulation. Advanced physics calculations means car handling and behavior are much more realistic, and everything from course layout to road surfaces has been rendered to an extremely sophisticated standard.','/images/upload/26.jpg','/images/upload/26_small.jpg','2001-07-21 00:00:00'),
(19,2,1,1,59.99,'Metal Gear Solid 2','Avoid battles, avoid being seen, and infiltrate enemy territory. Metal Gear Solid 2 lives up to the true meaning of the word interactive, and realizes a maximum level of thrill and tension, creating a totally new and genuine 3D gaming experience. Solid Snake is back in this the ultimate tactical espionage adventure. Also features a bonus \'Making Of MGS2\' DVD.','/images/upload/19.jpg','/images/upload/19_small.jpg','2002-04-12 00:00:00'),
(20,8,1,1,54.99,'Race Driver','Exhaust drifts across the asphalt as engines roar and tyres scream their impatience against the red-hot surface. Sunlight sparks off the bodywork of the touring cars drawing up to the grid and on the track ahead, heat shimmers transform the raceway into treacherous pools of quicksilver.','/images/upload/20.jpg','/images/upload/20_small.jpg','2002-08-27 00:00:00'),
(21,4,1,1,59.99,'Ratchet and Clank','Delivering exhilarating gameplay mechanics, unique character designs, enormous environments, where it is possible to blow up everything that stands in your way, brilliant graphics, an engaging storyline and an arsenal of the coolest weapons and high-tech gadgets ever seen in an action-platform game, Ratchet &amp; Clank provides players with an immersive experience that demonstrates superb technological advancements and highlights the previously untapped power of PlayStation 2.','/images/upload/21.jpg','/images/upload/21_small.jpg','2002-09-18 00:00:00'),
(22,2,1,1,49.99,'Stuntman','Rise from rookie driver to hero of the silver screen. Have you got what it takes? In the world of stunt driving, fear is failure. One slip and your Hollywood dreams are over. The higher you climb, the higher the stakes. Grip the wheel, hit the gas, chase the glory.','/images/upload/22.jpg','/images/upload/22_small.jpg','2002-09-14 00:00:00'),
(23,2,1,2,49.99,'Midnight Club 2','Midnight Club 2, the sequel to Angel\'s popular PlayStation 2 urban racing game Midnight Club. Midnight Club 2 includes new cities to race in -- Paris, Los Angeles, and Tokyo -- as well as more eye-catching sports cars and complex racing challenges. The basic premise of the game remains familiar, with waypoint-based street racing competitions in open city environments. Midnight Club 2 has more types of cars, more realistic AI, and in particular more detail to its urban courses. Shortcuts and jumps add a more open-ended character to each race, while pedestrians, slower vehicles, and many other types of obstacles demand precise maneuvering.','/images/upload/23.jpg','/images/upload/23_small.jpg','2003-05-27 00:00:00'),
(24,2,1,1,34.99,'Thunderhawk - Operation Phoenix','','/images/upload/24.jpg','/images/upload/24_small.jpg','2002-05-12 00:00:00'),
(25,8,1,1,59.99,'Gran Turismo 4','Gran Turismo 4 features similarly, authentic racing gameplay with new fully-enhanced features that focus on more cars, courses, race modes and online gameplay via the Network Adaptor (Ethernet/modem) (for PlayStation 2). Developed by Polyphony Digital Inc., Gran Turismo 4 uses an all-new physics engine coupled with revolutionary technology to deliver enhanced, if not perfected, game physics and graphics providing a near-realistic automotive racing simulation.','/images/upload/25.jpg','/images/upload/25_small.jpg','2005-03-10 00:00:00'),
(17,2,1,1,54.99,'Ace Combat Distant Thunder','','/images/upload/17.jpg','/images/upload/17_small.jpg','2002-04-15 00:00:00'),
(18,2,1,1,59.99,'The Getaway','Live on both sides of the law. Mark is a professional bank-robber, trying in vain to leave his past behind him. But when his son is kidnapped by gangland boss, Charlie Jolson, he is unwillingly dragged back into the seedy underworld of vice and corruption in order to free his son from the clutches of the most feared man in organised crime. THE GETAWAY is a first for video-gaming. The team has painstakingly recreated over 50 square kilometres of the heart of London. Explore the city on foot or steal a car and drive through any street from Kensington Palace to the Tower of London; it\'s all there. Over 50 cars are available; all based on real-life car models. Perform astounding car-stunts such as two-wheel races down back alleys, jumps and skids.','/images/upload/18.jpg','/images/upload/18_small.jpg','2002-12-13 00:00:00'),
(16,8,1,1,49.99,'WRC','As an officially licensed product thanks to an exclusive 5-year deal with the ISC/FIA, World Rally Championship™ features all the cars, all the teams and all the drivers from the official 2001 World Rally Championship. Battle your way through 108 heart-pounding stages in 14 rallies located all over the world. Experience the pure emotion, hardcore action and sheer power of rally racing in the quest for ultimate glory. WRC is a different kind of driving game. It’s about a personal battle, a brand new challenge, and an intense experience. WRC pits man and machine against the elements in a pure expression of competition. Harnessing the PlayStation 2’s power this game will bring revolutionary graphical quality allowing players to enjoy a new level of perfection and realism. Deformable cars, realistic weather effects, gigantic viewing distances and in car cockpit views will submerge the player in the world of World Rally racing.','/images/upload/16.jpg','/images/upload/16_small.jpg','2001-11-30 00:00:00'),
(13,4,3,1,59.99,'Super Mario Sunshine','When Mario arrives in tropical Isle Delfino, the once-pristine island is polluted and plastered with graffiti. Even worse, he\'s being blamed for the mess! Now instead of enjoying a relaxing vacation, Mario has to clean up the place and track down the real culprit. Armed with FLUDD, Mario will battle all sorts of big, bad bosses, including goop-spewing Piranha Plants!','/images/upload/13.jpg','/images/upload/13_small.jpg','2002-10-04 00:00:00'),
(14,2,3,1,29.99,'Legend Of Zelda - Collectors Edition','','/images/upload/14.jpg','/images/upload/14_small.jpg','2003-10-10 00:00:00'),
(15,8,3,1,49.99,'F-Zero GX','F-Zero has been a long time in coming and now, it just seems like a normal game. But it?s not a normal game at all and if someone had told you about it three years ago you would have laughed, long and hard.<br />\r\nDescended from an arcade platform co-developed between Nintendo, Namco and Sega, this is a key Nintendo IP, running on the GameCube, developed largely by a high-profile Sega developer.<br />\r\n<br />\r\nIt?s a racing game, the latest in the acclaimed F-Zero series that needs no introduction.<br />\r\n<br />\r\nAnd it?s really, really fast.<br />\r\n<br />\r\nImagine if Road Runner were to breed with Speedy Gonzales. Then that child was genetically engineered with a rogue Billy Whiz DNA strand. Then fed only nitrous oxide equipped with a turbo-charged jet engine. Times how fast that is by a trillion and you?re not even halfway towards understanding how fast F-Zero GX is.<br />\r\n<br />\r\nThe game a true evolution of the series, and combines the wondrous sprawling 3D world found in the Nintendo 64 classic F-Zero X with up-to-date GameCube sparkliness and something very, very special indeed.<br />\r\n<br />\r\nThat something is the creative genius that is Toshihiro Nagoshi, development director at Sega. Having invented 3D gaming with his bare hands, creating in the main as he did the seminal Sega Model 1 arcade board, Monkey Ball, his leading franchise alongside Daytona, has been shelved until now. Nagoshi has been working flat out with team Amusement Vision on a game franchise of which he is a lifelong fan.<br />\r\n<br />\r\nThe new game is massive, complete with 20 courses and 30 pilots.<br />\r\n<br />\r\nBroken down into Time Attack, Grand Prix and versus modes, a new story mode augments what has proven time and time again to be a winning racing formula. Captain Falcon predictably takes centre stage in a massive and pretty engaging plot taking place over nine chapters.<br />\r\n<br />\r\nIndeed, story mode is more than a gameplay diversion and evolves into a specific training mode that will see players forced to perform certain manouvers and master techniques. Story mode is designed to make you a more complete racer and it works absolutely.<br />\r\n<br />\r\nThe scope of F-Zero has been hugely expanded from the somewhat closed offerings of the past. Racers can now be customised to an unbelievable extent, with the complete garage mode included in the game.<br />\r\n<br />\r\nThis offers everything form customisable liveries in the decal-editing suite to specific parts to adjust the handling and performance of each ship.<br />\r\n<br />\r\nGran Turismo in space. Perfect!<br />\r\n<br />\r\nAdd to this a multiplayer mode that offers four-player split screen mode, with individual player recognition, that sees no slowdown or noticeable graphical degradation even when pushed to the absolute limits of what should be possible and you?ll find that F-Zero GX offers one the most complete and visually stunning racing games the world has ever seen.<br />\r\n<br />\r\nAlso, did we mention that it?s very, very fast? Oh yes we did. You have been warned.','/images/upload/15.jpg','/images/upload/15_small.jpg','2003-12-15 00:00:00'),
(11,2,2,1,54.99,'Jetset Radio Future','','/images/upload/11.jpg','/images/upload/11_small.jpg','2001-11-15 00:00:00'),
(12,2,3,1,59.99,'Legend of Zelda - The Wind Waker','Since the debut of The Legend of Zelda in 1987, all of Link\'s games have been the stuff of legends -- universally appealing adventures that emerged as beloved triumphs of gameplay, presentation, innovation, graphics and fun.<br />\r\nIn the ninth entry in the series, Link debuts on GCN with a fun new look, but the game is still serious about adventure. <br />\r\nThe series\' classic mix of sword-swinging action, perplexing puzzles and stirring story lines remains intact while the new cel-shaded graphics enable the characters of Link\'s world to be the most lively and expressive personalities gamers have ever seen.<br />\r\nEmotions hit a fever pitch when Link witnesses his sister being snatched up by a giant bird. Embarking on an epic voyage to locate his sister, Link unravels a mystery that deftly blends unique, stylized graphics and effects with the much-heralded mechanics of the gaming milestone, The Legend of Zelda: Ocarina of Time. ','/images/upload/12.jpg','/images/upload/12_small.jpg','2003-05-09 00:00:00'),
(10,8,2,1,39.99,'Outrun 2','Seventeen years since its first appearance in the world\'s arcades the legendary racer returns and its lost non of the games original appeal. With blistering graphics, remixes of the original sound tracks and game play that demands one more play the beautiful journey has never been so appealing.With Xbox Live and Mission modes coming to the Microsoft Xbox exclusive conversion of this classic arcade racing machine, OutRun2 delivers a unique console experience. Take the wheel of your chosen Ferrari and take to the open road to race against the clock from Palm Beach to one of five final destinations Tulip Garden, Metropolis, Ancient Ruins, Imperial Avenue and Cape Way. Fully licensed and approved by Ferrari, players can choose from one of eight different Ferrari cars including Enzo Ferrari, Dino 246 GTS, 365 GTS/4 Daytona, Testarossa, 360 Spider, 288 GTO, F40 and F50.','/images/upload/10.jpg','/images/upload/10_small.jpg','2004-10-15 00:00:00'),
(9,8,2,1,59.99,'Project Gotham Racing 2','Project Gotham Racing 2 is the newest version of the critically acclaimed &quot;Project Gotham Racing&quot; franchise. Rewarding drivers for focusing on speed and style while still taking risks around every corner, &quot;Project Gotham Racing 2&quot; offers circuit-based racing through highly detailed, photorealistic urban environments. Racers can choose from more than 50 of the hottest cars while learning their way around new and exciting cities, mastering corners, and accelerating through the straightaways before moving to a different venue. Players also can race against up to three friends at once through a multiplayer SystemLink or match up against players online utilizing Xbox Live.','/images/upload/9.jpg','/images/upload/9_small.jpg','2003-11-24 00:00:00'),
(8,8,2,1,49.99,'Sega GT 2002','Prepare for the definitive car racing simulation. SEGA GT 2002 features more than 125 current and classic high performance vehicles, created using exact handling and performance specifications from their real life counterparts. With astounding vehicle control, incredibly deep gameplay, and multiple modes to conquer, SEGA GT 2002 is one of the most realistic driving experiences available!','/images/upload/8.jpg','/images/upload/8_small.jpg','2002-11-15 00:00:00'),
(7,8,2,1,44.99,'Midtown Madness 3','&quot;Midtown Madness 3,&quot; the latest in Microsoft Corp.\'s popular &quot;Midtown Madness&quot; franchise, allows gamers to get behind the wheel of more than 30 vehicles while competing in exciting race modes such as Blitz, Checkpoint and Cruise. With a robust Career mode featuring 14 driving careers, &quot;Midtown Madness 3&quot; delivers the rush and excitement of street racing while challenging gamers to put their driving skills to the ultimate test.* Wide-open racing in two living, breathing cities. Highly detailed, fully researched renditions of Paris and Washington, complete with ambient traffic and animated pedestrians, await gamers in &quot;Midtown Madness 3.&quot; Hidden routes, shortcuts and hideouts are encountered throughout each of the cities, showcasing the incredible depth of these environments.* Career mode. Players work through more than 50 missions across 14 careers, including a limousine driver, pizza deliverer, taxi driver, secret agent and police officer. New careers, vehicles and challenges are unlocked as players complete a series of missions to progress through the game.','/images/upload/7.jpg','/images/upload/7_small.jpg','2003-06-18 00:00:00'),
(6,5,2,1,49.99,'Halo','Halo is a sci-fi shooter that takes place on a mysterious alien ring-world. Packed with combat, Halo will have you battling on foot, in vehicles, inside and outdoors with Alien and Human weaponry. Your objective: to uncover Halo\'s horrible secrets and destroy mankind\'s sworn enemy, the Covenant. Halo transports gamers into a science fiction universe fresh out of a Hollywood movie. With a detailed twisting story-line, complex characters and cunning enemies Halo will fulfill every sci-fi enthusiasts dream. Features 4-player split-screen or 16 player system link play.','/images/upload/6.jpg','/images/upload/6_small.jpg','2001-11-21 00:00:00'),
(5,8,2,1,54.99,'Rallisport Challenge 2','Intense Competition. &quot;RalliSport Challenge 2&quot; is the first racing title in the new XSN Sports brand. XSN Sports games let gamers compete in the first truly virtual sports league for your Xbox. Now gamers can easily set up a rally league on XSNsports.com, take out rivals over Xbox Live, and track up-to-date stats on a PC throughout their own rally season. All XSN Sports games bring the power of the Xbox and PC together for the first time through XSNsports.com, so gamers are always plugged into the competition.','/images/upload/5.jpg','/images/upload/5_small.jpg','2004-05-21 00:00:00'),
(4,8,2,1,49.99,'Burnout 3','Burnout 3 rewrites the rules of the road with a new mantra: aggressive racing required. Racing gamers and adrenaline junkies are invited to get behind the wheel and experience unprecedented speed and action. Use your car as a weapon and battle your way to the front of the pack by taking down rivals and instigating spectacular crashes. Live dangerously with a Crash mode featuring 45 segments, each of which rewards you for creating the most massive pileups ever seen. Vie for first place with aggressive driving or simply indulge your thirst for twisted metal and breathtaking crashes. With stunning 3D graphics, addictive multiplayer races, and over 70 cars and 40 tracks, Burnout 3 is a breakneck, adrenaline-fuelled, arcade-racing experience.','/images/upload/4.jpg','/images/upload/4_small.jpg','2004-09-21 00:00:00'),
(3,8,2,1,44.99,'Project Gotham Racing','Project Gotham Racing is circuit-based racing in photo-realistic downtown environments. Players can race against up to three friends at a time through realistic time-of-day and weather conditions. Choose from 25 high-performance dream cars including the Ferrari F50 and Porsche Boxster S. Compete on more than 200 city-based circuits in New York, London, Tokyo and more. See real damage to your cars.','/images/upload/3.jpg','/images/upload/3_small.jpg','2001-11-21 00:00:00'),
(1,6,2,1,59.99,'Top Spin','','/images/upload/1.jpg','/images/upload/1_small.jpg','2003-11-15 00:00:00'),
(2,8,2,1,49.99,'TOCA Race Driver 2','Prepare to race against the whole world like never before as you take on Australia\'s greatest V8 Supercar drivers. Race with 21 of the Ford or Holden drivers from the V8 Championship Series. Features 7 accurately modelled tracks including Bathurst and Phillip Island, and a further 31 internationally licenced circuits. 13 official global championships to compete in. Race in one of 42 international race cars including the Commodore VX, Falcon AU, Skyline GT-R, and many more.','/images/upload/2.jpg','/images/upload/2_small.jpg','2004-05-04 00:00:00');

/*
Table structure for item_price
*/

drop table if exists `item_price`;
CREATE TABLE `item_price` (
  `id` int(11) NOT NULL default '0',
  `item_id` int(11) NOT NULL default '0',
  `price_date` timestamp NULL default NULL,
  `price` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table data for shop.item_price
*/

INSERT INTO `item_price` VALUES 
(21,21,'2005-03-19 21:45:21',29.99),
(20,20,'2005-03-19 21:44:34',14.99),
(19,19,'2005-03-19 21:43:42',29.99),
(18,18,'2005-03-19 21:42:36',24.99),
(17,17,'2005-03-19 21:41:42',39.99),
(16,16,'2005-03-19 21:40:48',19.99),
(15,15,'2005-03-19 21:39:29',34.99),
(14,14,'2005-03-19 21:37:40',27.99),
(13,13,'2005-03-19 21:36:57',34.99),
(12,12,'2005-03-19 21:36:08',49.99),
(11,11,'2005-03-19 21:34:01',9.99),
(10,10,'2005-03-19 21:31:53',24.99),
(9,9,'2005-03-19 21:31:03',44.99),
(8,8,'2005-03-19 21:30:00',29.99),
(7,7,'2005-03-19 21:29:13',39.99),
(6,6,'2005-03-19 21:27:47',24.99),
(5,5,'2005-03-19 21:27:00',29.99),
(4,4,'2005-03-19 21:25:57',44.99),
(3,3,'2005-03-19 21:24:54',9.99),
(2,2,'2005-03-19 21:23:45',39.99),
(1,1,'2005-03-19 21:20:26',19.99),
(22,22,'2005-03-19 21:46:55',19.99),
(23,23,'2005-03-19 21:47:54',19.99),
(24,24,'2005-03-19 21:48:45',9.99),
(25,25,'2005-03-19 21:50:30',54.99),
(26,26,'2005-03-19 21:51:07',29.99),
(27,27,'2005-03-19 21:52:17',49.99),
(28,28,'2005-03-19 21:53:13',29.99),
(29,29,'2005-03-19 21:54:05',29.99);

/*
Table structure for item_price_seq
*/

drop table if exists `item_price_seq`;
CREATE TABLE `item_price_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table data for shop.item_price_seq
*/

INSERT INTO `item_price_seq` VALUES 
(29);

/*
Table structure for item_seq
*/

drop table if exists `item_seq`;
CREATE TABLE `item_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table data for shop.item_seq
*/

INSERT INTO `item_seq` VALUES 
(29);

/*
Table structure for product_group
*/

drop table if exists `product_group`;
CREATE TABLE `product_group` (
  `id` int(11) NOT NULL default '0',
  `category_group_id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `pic_width` int(11) NOT NULL default '0',
  `pic_height` int(11) NOT NULL default '0',
  `pic_width_small` int(11) NOT NULL default '0',
  `pic_height_small` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table data for shop.product_group
*/

INSERT INTO `product_group` VALUES 
(3,1,'Gamecube',150,213,67,95),
(2,1,'XBox',150,213,67,95),
(1,1,'PlayStation 2',150,213,67,95);

/*
Table structure for product_group_seq
*/

drop table if exists `product_group_seq`;
CREATE TABLE `product_group_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table data for shop.product_group_seq
*/

INSERT INTO `product_group_seq` VALUES 
(3);

/*
Table structure for region
*/

drop table if exists `region`;
CREATE TABLE `region` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table data for shop.region
*/

INSERT INTO `region` VALUES 
(3,'NTSC-U'),
(2,'NTSC-J'),
(1,'PAL');

/*
Table structure for region_seq
*/

drop table if exists `region_seq`;
CREATE TABLE `region_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table data for shop.region_seq
*/

INSERT INTO `region_seq` VALUES 
(3);

/*
Table structure for wishlist
*/

drop table if exists `wishlist`;
CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL default '0',
  `customer_id` int(11) NOT NULL default '0',
  `item_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table structure for wishlist_seq
*/

drop table if exists `wishlist_seq`;
CREATE TABLE `wishlist_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*
Table data for shop.wishlist_seq
*/

INSERT INTO `wishlist_seq` VALUES 
(0);

