

CREATE TABLE IF NOT EXISTS `items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `acc_group` int(4) unsigned NOT NULL,
  `name` char(40) DEFAULT NULL,
  `type` char(1) DEFAULT NULL,
  `liquidity` char(1) DEFAULT '',
  `orderby` int(5) DEFAULT '9999',
  `notes` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;



LOCK TABLES `items` WRITE;

-- Insert the following items if they don't exist
INSERT INTO `items` VALUES (1,12,'Supplies','L','',940,''),(2,6,'PST','L','',13000,''),(3,13,'Rent','L','',23000,''),(4,14,'Received services','L','',1020,''),(5,9,'Sales','A','',21000,''),(6,14,'Telephone Expense','L','',960,''),(7,14,'Food','L','',930,''),(8,14,'TTC','L','',1000,''),(9,14,'Fuel','L','',1010,''),(10,10,'Checking Account','','+',600,'$10.95 monthly fee, waived if ballance is above $3,000. Transactions included per month: 25'),(11,11,'Savings Account','','+',830,'Transactions included per month: 1. Additional Transactions: $3.00 each'),(12,1,'Cash on hand','','+',100,''),(13,5,'Received interest','A','',9055,''),(14,5,'Received gov.','A','',22000,''),(15,90,'Other','','',30000,''),(16,6,'GST','L','',12000,''),(17,5,'Salaries','A','',1100,''),(18,6,'CRA TAX','','',20000,''),(19,2,'VISA','','-',800,''),(20,6,'CPP/QPP','A','',14000,''),(21,7,'Insurance','L','',11000,''),(23,6,'HST','L','',11500,''),(24,14,'Electrical bills','L','',950,''),(25,8,'Long Term Assets','','',9000,''),(29,3,'Mortgage Principal','L','',9010,''),(30,14,'Condo fee','L','',1200,''),(31,2,'MasterCard TD','','-',12100,''),(32,6,'Property Tax','L','',11200,''),(33,15,'Mortgage Interest','L','',9050,''),(34,3,'Line Of Credit','','-',820,''),(35,15,'Interest Line of Credit','L','',822,''),(36,5,'Employment Insurance','A','',21500,''),(38,1,'Bitcoins BTC','','',24000,''),(39,13,'Rent/Lease machinery & equipment','L','',9080,''),(42,2,'MasterCard WalMart','','-',810,''),(41,14,'Travel','L','',9060,''),(43,2,'MasterCard CanTire','','-',815,''),(44,5,'Income MSP Cantire','A','',1150,''),(45,10,'Gift card',NULL,'+',11300,'') ON DUPLICATE KEY UPDATE id=id;

UNLOCK TABLES;


CREATE TABLE IF NOT EXISTS `ledger` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_dt` int(10) unsigned DEFAULT NULL,
  `amount` decimal(16,2) DEFAULT NULL,
  `item_ct` int(10) unsigned DEFAULT NULL,
  `operator` int(10) unsigned DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `accounted` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5990 DEFAULT CHARSET=latin1 COMMENT='latin1_swedish_ci';



CREATE TABLE IF NOT EXISTS `texts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `docnum` int(10) unsigned DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5990 DEFAULT CHARSET=latin1 COMMENT='latin1_swedish_ci';


