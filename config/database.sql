                                -- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tl_bp_ideen`
--

CREATE TABLE `tl_bp_ideen` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `member_id` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `description` longtext NOT NULL default '',
  `location` varchar(255) NOT NULL default '',
  `published` int(1) NOT NULL default '0',
  `liked` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tl_bp_ideen_votes`
--

CREATE TABLE `tl_bp_ideen_votes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `member_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;