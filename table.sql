CREATE TABLE `shorturls` (
  `urlID` varchar(150) NOT NULL,
  `userID` int(11) NOT NULL,
  `pass` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `link` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `shorturls` ADD PRIMARY KEY (`urlID`);
ALTER TABLE `shorturls` MODIFY `urlID` varchar(150) NOT NULL;

CREATE TABLE `stats` (
  `ID` int(11) NOT NULL,
  `urlID` varchar(150) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `city` varchar(150) NOT NULL DEFAULT "Unbekannt",
  `region` varchar(150) NOT NULL DEFAULT "Unbekannt",
  `country` varchar(150) NOT NULL DEFAULT "Unbekannt",
  `latitude` varchar(150) NOT NULL DEFAULT "Unbekannt",
  `longitude` varchar(150) NOT NULL DEFAULT "Unbekannt",
  `ip_hashed` varchar(150) NOT NULL DEFAULT "Unbekannt"
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `stats` ADD PRIMARY KEY (`ID`);
ALTER TABLE `stats` MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stats`
  ADD CONSTRAINT `stats_shorturls` FOREIGN KEY (`urlID`) REFERENCES `shorturls` (`urlID`) ON DELETE CASCADE ON UPDATE CASCADE;