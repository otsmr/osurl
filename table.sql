CREATE TABLE `osurl` (
  `ID` int(11) NOT NULL,
  `urlID` varchar(150) NOT NULL,
  `userID` int(11) NOT NULL,
  `pass` text NOT NULL,
  `onetime` tinyint(4) NOT NULL,
  `expiry` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `link` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `osurl`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `osurl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;