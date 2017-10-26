

DROP TABLE IF EXISTS `items`;

CREATE TABLE `items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `acc_group` int(4) unsigned NOT NULL,
  `name` char(40) DEFAULT NULL,
  `type` char(1) DEFAULT NULL,
  `liquidity` char(1) DEFAULT '',
  `orderby` int(5) DEFAULT '9999',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;



LOCK TABLES `items` WRITE;

INSERT INTO `items` VALUES (1,0,'Supplies','L','',940),(2,0,'PST','L','',13000),(3,0,'Rent','L','',23000),(4,0,'Received services','L','',1020),(5,0,'Sales','A','',21000),(6,4,'Telephone Expense','L','',960),(7,4,'Food','L','',930),(8,4,'TTC','L','',1000),(9,4,'Fuel','L','',1010),(10,1,'Checking Account','','+',600),(11,1,'Savings Account','','+',830),(12,1,'Cash on hand','','+',100),(13,0,'Received interest','A','',835),(14,0,'Received gov.','A','',22000),(15,0,'Other','','',30000),(16,0,'GST','L','',12000),(17,0,'Salaries','A','',1100),(18,0,'CRA TAX','L','',20000),(19,2,'VISA','','-',800),(20,0,'CPP/QPP','A','',14000),(21,0,'Insurance','L','',11000),(23,0,'HST','L','',11500),(24,4,'Electrical bills','L','',950),(25,0,'Long Term Assets','','',9000),(29,3,'Mortgage Principal','L','',9010),(30,4,'Condo fee','L','',1200),(31,2,'MasterCard','','-',810),(32,0,'Property Tax','L','',11200),(33,3,'Mortgage Interest','L','',9050),(34,2,'Line Of Credit','','-',820),(35,3,'Interest Line of Credit','L','',822),(36,0,'Employment Insurance','A','',21500),(38,0,'Bitcoins BTC','','+',24000),(39,0,'Rent/Lease machinery & equipment','L','',9080),(41,0,'Travel','L','',9060);

UNLOCK TABLES;


CREATE TABLE `ledger` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_dt` int(10) unsigned DEFAULT NULL,
  `ammount` decimal(16,2) DEFAULT NULL,
  `item_ct` int(10) unsigned DEFAULT NULL,
  `operator` int(10) unsigned DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `accounted` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COMMENT='latin1_swedish_ci';

CREATE TABLE `texts` (
  `id` int(10) unsigned NOT NULL DEFAULT '1',
  `docnum` int(10) unsigned DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `texts` WRITE;
INSERT INTO `texts` VALUES (1,1,'');
UNLOCK TABLES;



