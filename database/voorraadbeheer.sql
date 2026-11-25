
CREATE TABLE `voorraad` (
  `id` int NOT NULL AUTO_INCREMENT,
  `naam` varchar(100) NOT NULL,
  `smaak` varchar(100) NOT NULL,
  `prijs` decimal(6,2) NOT NULL,
  `voorraad` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `voorraad` VALUES (1,'Frisdrank','Citroen',1.50,24),(2,'Sportdrank','Bessen',2.15,12),(3,'Bruiswater','Naturel',0.95,0);

