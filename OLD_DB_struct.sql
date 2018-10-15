-- Create syntax for TABLE 'OLDss3_materiell'
CREATE TABLE `OLDss3_materiell` (
  `kommune_id` int(4) NOT NULL DEFAULT '0',
  `fylke_id` int(4) NOT NULL,
  `kommune_navn` varchar(255) DEFAULT NULL,
  `kontaktperson` varchar(255) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `postnummer` varchar(25) DEFAULT NULL,
  `pakke` enum('liten','basis','stor') DEFAULT NULL,
  `malform` enum('nynorsk','bokmal','boksam') DEFAULT NULL,
  `diplomer` int(4) DEFAULT NULL,
  PRIMARY KEY (`kommune_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'wp_materiell_arkiv'
CREATE TABLE `wp_materiell_arkiv` (
  `kommune_id` int(4) NOT NULL DEFAULT '0',
  `fylke_id` int(4) NOT NULL,
  `kommune_navn` varchar(255) DEFAULT NULL,
  `kontaktperson` varchar(255) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `postnummer` int(4) DEFAULT NULL,
  `pakke` enum('liten','basis','stor') DEFAULT NULL,
  `malform` enum('nynorsk','bokmal','boksam') DEFAULT NULL,
  `diplomer` int(4) DEFAULT NULL,
  `skalha` enum('skalha','delermat','arrikke') NOT NULL,
  `kommune_sesong` int(4) NOT NULL,
  PRIMARY KEY (`kommune_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'wp_materiell_fylke_arkiv'
CREATE TABLE `wp_materiell_fylke_arkiv` (
  `fylke_id` int(2) NOT NULL,
  `fylke_kontakt` varchar(255) NOT NULL,
  `fylke_epost` varchar(255) NOT NULL,
  `fylke_mobil` int(8) NOT NULL,
  `fylke_navn` varchar(255) NOT NULL,
  `fylke_ekstradiplom` int(4) NOT NULL,
  `fylke_hvordansendes` enum('allesendes','direktenoen','direktealle') NOT NULL,
  `fylke_sendesdirekte` varchar(255) NOT NULL,
  `fylke_kommentarer` varchar(255) NOT NULL,
  `fylke_sesong` int(4) NOT NULL,
  PRIMARY KEY (`fylke_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'wp_materiell_kommune'
CREATE TABLE `wp_materiell_kommune` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `idfylke` int(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idfylke` (`idfylke`)
) ENGINE=MyISAM AUTO_INCREMENT=3004 DEFAULT CHARSET=latin1;
