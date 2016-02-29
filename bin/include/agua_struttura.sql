DROP TABLE IF EXISTS agenti;

CREATE TABLE `agenti` (
  `codice` float NOT NULL,
  `data_reg` date NOT NULL DEFAULT '0000-00-00',
  `ragsoc` varchar(100) NOT NULL DEFAULT '',
  `ragsoc2` varchar(100) DEFAULT NULL,
  `indirizzo` varchar(60) DEFAULT NULL,
  `cap` varchar(5) DEFAULT NULL,
  `citta` varchar(60) DEFAULT NULL,
  `prov` char(2) DEFAULT NULL,
  `codnazione` varchar(30) DEFAULT NULL,
  `codfisc` varchar(16) DEFAULT NULL,
  `piva` varchar(14) DEFAULT NULL,
  `contatto` varchar(60) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `telefono2` varchar(20) DEFAULT NULL,
  `cell` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `iva` char(3) DEFAULT NULL,
  `codpag` varchar(20) DEFAULT NULL,
  `banca` varchar(50) DEFAULT NULL,
  `abi` varchar(5) DEFAULT NULL,
  `cab` varchar(5) DEFAULT NULL,
  `cin` char(1) DEFAULT NULL,
  `cc` varchar(12) DEFAULT NULL,
  `iban` varchar(4) DEFAULT NULL,
  `swift` varchar(11) DEFAULT NULL,
  `zona` varchar(20) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `email2` varchar(60) DEFAULT NULL,
  `email3` varchar(60) DEFAULT '',
  `sitocli` varchar(100) DEFAULT NULL,
  `bloccocli` char(2) NOT NULL DEFAULT 'no',
  `privacy` char(2) DEFAULT 'NO',
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `note` text,
  PRIMARY KEY (`codice`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS aliquota;

CREATE TABLE `aliquota` (
  `codice` char(3) NOT NULL DEFAULT '',
  `descrizione` char(30) NOT NULL,
  `ivacee` char(1) NOT NULL,
  `eseniva` int(1) NOT NULL DEFAULT '1',
  `aliquota` smallint(3) NOT NULL DEFAULT '0',
  `ventilazione` smallint(3) NOT NULL DEFAULT '0',
  `colonnacli` int(1) DEFAULT '0',
  `colonnafor` int(1) DEFAULT '0',
  `plafond` char(1) NOT NULL,
  `modello1012` char(1) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`codice`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS articoli;

CREATE TABLE `articoli` (
  `articolo` varchar(15) NOT NULL DEFAULT '',
  `descrizione` varchar(120) NOT NULL,
  `desrid` varchar(20) DEFAULT '',
  `unita` varchar(5) DEFAULT NULL,
  `codbar` varchar(14) DEFAULT '',
  `fornitore` varchar(6) DEFAULT NULL,
  `artfor` varchar(20) DEFAULT NULL,
  `preacqnetto` float(10,2) DEFAULT NULL,
  `prelisacq` float(10,2) DEFAULT NULL,
  `scaa` float DEFAULT NULL,
  `scab` float DEFAULT NULL,
  `scac` float DEFAULT NULL,
  `qta_cartone` int(5) DEFAULT NULL,
  `qta_multi_ord` int(5) DEFAULT NULL,
  `qtaminord` float(10,2) DEFAULT NULL,
  `pesoart` float(10,3) DEFAULT '0.000',
  `lead_time` char(2) DEFAULT NULL,
  `prod_composto` char(2) DEFAULT NULL,
  `stato_prod` char(2) DEFAULT NULL,
  `data_var` date NOT NULL DEFAULT '0000-00-00',
  `tipart` varchar(40) DEFAULT NULL,
  `catmer` varchar(40) DEFAULT NULL,
  `iva` char(3) DEFAULT NULL,
  `memoart` text,
  `provvart` float(4,2) DEFAULT NULL,
  `fornitore2` varchar(6) DEFAULT '',
  `preacqnetto2` float(10,2) DEFAULT '0.00',
  `prelisacq_2` float(10,2) DEFAULT '0.00',
  `scaa_2` float DEFAULT '0',
  `scab_2` float DEFAULT '0',
  `scac_2` float DEFAULT '0',
  `sitoart` varchar(100) DEFAULT '',
  `data_reg` date DEFAULT '0000-00-00',
  `data_scad` varchar(4) DEFAULT '',
  `artfor2` varchar(20) DEFAULT '',
  `qta_cartone_2` int(5) DEFAULT NULL,
  `qta_multi_ord_2` int(5) DEFAULT NULL,
  `qtaminord_2` int(5) DEFAULT NULL,
  `lead_time_2` char(2) DEFAULT NULL,
  `prod_composto_2` char(2) DEFAULT NULL,
  `stato_prod_2` char(2) DEFAULT NULL,
  `data_var_2` date NOT NULL DEFAULT '0000-00-00',
  `fornitore_3` varchar(6) DEFAULT NULL,
  `artfor_3` varchar(20) DEFAULT NULL,
  `prelisacq_3` float(10,2) DEFAULT '0.00',
  `scaa_3` float DEFAULT '0',
  `scab_3` float DEFAULT '0',
  `scac_3` float DEFAULT '0',
  `preacqnetto_3` float(10,2) DEFAULT '0.00',
  `qta_cartone_3` int(5) DEFAULT NULL,
  `qta_multi_ord_3` int(5) DEFAULT NULL,
  `qtaminord_3` int(5) DEFAULT NULL,
  `lead_time_3` char(2) DEFAULT NULL,
  `prod_composto_3` char(2) DEFAULT NULL,
  `stato_prod_3` char(2) DEFAULT NULL,
  `data_var_3` date NOT NULL DEFAULT '0000-00-00',
  `esco` char(2) DEFAULT '',
  `ultacq` float(10,2) DEFAULT '0.00',
  `esma` char(2) NOT NULL DEFAULT 'NO',
  `scorta` varchar(10) DEFAULT NULL,
  `immagine` varchar(100) DEFAULT NULL,
  `pagcat` int(5) DEFAULT '0',
  `catalogo` varchar(30) DEFAULT NULL,
  `pubblica` char(2) DEFAULT 'SI',
  `descsito` text,
  `artcorr` varchar(15) DEFAULT NULL,
  `artcorr_2` varchar(15) DEFAULT NULL,
  `artcorr_3` varchar(15) DEFAULT NULL,
  `a_settore` varchar(11) DEFAULT NULL,
  `a_scaffale` varchar(5) DEFAULT NULL,
  `a_ripiano` varchar(5) DEFAULT NULL,
  `a_cassetto` varchar(5) DEFAULT NULL,
  `art_alternativo` varchar(15) DEFAULT NULL,
  `ordine_cat` varchar(30) DEFAULT NULL,
  `egpz` char(2) DEFAULT 'NO',
  `immagine2` varchar(100) DEFAULT NULL,
  `es_selezione` char(2) NOT NULL DEFAULT 'NO',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`articolo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS banche;

CREATE TABLE `banche` (
  `codice` char(2) NOT NULL,
  `banca` varchar(100) NOT NULL,
  `indirizzo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `cell` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `abi` varchar(6) DEFAULT NULL,
  `cab` varchar(6) DEFAULT NULL,
  `cin` char(1) DEFAULT NULL,
  `cc` varchar(12) DEFAULT NULL,
  `iban` varchar(4) DEFAULT NULL,
  `swift` varchar(11) DEFAULT NULL,
  `note` text,
  `es_selezione` char(2) NOT NULL DEFAULT 'NO',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`codice`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS banned_ip;

CREATE TABLE `banned_ip` (
  `ip_remoto` varchar(20) NOT NULL,
  `n_volte` int(1) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS barcode;

CREATE TABLE `barcode` (
  `codbar` varchar(30) NOT NULL,
  `articolo` varchar(15) NOT NULL,
  `rigo` int(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`codbar`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS bv_bolle;

CREATE TABLE `bv_bolle` (
  `anno` varchar(4) NOT NULL DEFAULT '',
  `suffix` char(1) NOT NULL DEFAULT 'A',
  `ndoc` float NOT NULL DEFAULT '0',
  `datareg` date NOT NULL DEFAULT '0000-00-00',
  `utente` varchar(6) NOT NULL DEFAULT '',
  `dragsoc` varchar(40) DEFAULT NULL,
  `dragsoc2` varchar(60) DEFAULT NULL,
  `dindirizzo` varchar(30) DEFAULT NULL,
  `dcap` varchar(5) DEFAULT NULL,
  `dcitta` varchar(30) DEFAULT NULL,
  `dprov` char(2) DEFAULT NULL,
  `dcodnazione` varchar(30) DEFAULT NULL,
  `modpag` varchar(20) DEFAULT NULL,
  `banca` varchar(30) DEFAULT NULL,
  `vettore` varchar(40) DEFAULT NULL,
  `scontofattura` float(10,2) DEFAULT '0.00',
  `spesevarie` float(10,2) DEFAULT '0.00',
  `porto` varchar(10) DEFAULT NULL,
  `aspetto` varchar(40) DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT '',
  `note` text,
  `colli` int(4) DEFAULT '0',
  `pesotot` float(10,2) DEFAULT '0.00',
  `trasporto` float(10,2) DEFAULT '0.00',
  `totimpo` float(16,2) DEFAULT '0.00',
  `totiva` float(16,2) DEFAULT '0.00',
  `totdoc` float(16,2) DEFAULT '0.00',
  `tdocevaso` varchar(30) DEFAULT NULL,
  `evasonum` varchar(6) DEFAULT '',
  `evasoanno` varchar(4) DEFAULT '',
  `evasosuffix` char(1) DEFAULT 'A',
  `causale` varchar(20) DEFAULT NULL,
  `invio` char(2) DEFAULT NULL,
  `id_collo` varchar(20) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`anno`,`suffix`,`ndoc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS bv_dettaglio;

CREATE TABLE `bv_dettaglio` (
  `anno` varchar(4) NOT NULL DEFAULT '',
  `suffix` char(1) NOT NULL DEFAULT 'A',
  `ndoc` float NOT NULL DEFAULT '0',
  `rigo` decimal(4,1) NOT NULL DEFAULT '0.0',
  `utente` varchar(6) DEFAULT NULL,
  `articolo` varchar(15) DEFAULT NULL,
  `descrizione` text,
  `unita` char(3) DEFAULT NULL,
  `quantita` float(16,2) DEFAULT '0.00',
  `listino` float(16,2) DEFAULT '0.00',
  `scva` float DEFAULT '0',
  `scvb` float DEFAULT '0',
  `scvc` float DEFAULT '0',
  `nettovendita` float(16,2) DEFAULT '0.00',
  `totriga` float(16,2) DEFAULT '0.00',
  `iva` char(3) DEFAULT NULL,
  `totrigaprovv` float(10,2) DEFAULT NULL,
  `peso` float(10,3) DEFAULT '0.000',
  `qtaevasa` float(16,2) DEFAULT '0.00',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS bvfor_dettaglio;

CREATE TABLE `bvfor_dettaglio` (
  `anno` varchar(4) NOT NULL DEFAULT '',
  `ndoc` float NOT NULL DEFAULT '0',
  `rigo` decimal(4,1) NOT NULL DEFAULT '0.0',
  `utente` varchar(6) DEFAULT NULL,
  `articolo` varchar(15) DEFAULT NULL,
  `descrizione` varchar(80) DEFAULT NULL,
  `unita` char(3) DEFAULT NULL,
  `quantita` float(16,2) DEFAULT '0.00',
  `listino` float(16,2) DEFAULT '0.00',
  `scva` float DEFAULT '0',
  `scvb` float DEFAULT '0',
  `scvc` float DEFAULT '0',
  `nettovendita` float(16,2) DEFAULT '0.00',
  `totriga` float(16,2) DEFAULT '0.00',
  `iva` char(3) DEFAULT NULL,
  `totrigaprovv` float(10,2) DEFAULT NULL,
  `peso` float(10,3) DEFAULT '0.000',
  `qtaevasa` float(16,2) DEFAULT '0.00',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS bvfor_testacalce;

CREATE TABLE `bvfor_testacalce` (
  `anno` varchar(4) NOT NULL DEFAULT '',
  `ndoc` float NOT NULL DEFAULT '0',
  `datareg` date NOT NULL DEFAULT '0000-00-00',
  `utente` varchar(6) NOT NULL DEFAULT '',
  `dragsoc` varchar(40) DEFAULT NULL,
  `dragsoc2` varchar(60) DEFAULT NULL,
  `dindirizzo` varchar(30) DEFAULT NULL,
  `dcap` varchar(5) DEFAULT NULL,
  `dcitta` varchar(30) DEFAULT NULL,
  `dprov` char(2) DEFAULT NULL,
  `dcodnazione` varchar(30) DEFAULT NULL,
  `modpag` varchar(20) DEFAULT NULL,
  `banca` varchar(30) DEFAULT NULL,
  `vettore` varchar(40) DEFAULT NULL,
  `scontofattura` float(10,2) DEFAULT '0.00',
  `spesevarie` float(10,2) DEFAULT '0.00',
  `porto` varchar(10) DEFAULT NULL,
  `aspetto` varchar(40) DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT '',
  `note` text CHARACTER SET latin1,
  `colli` int(4) DEFAULT '0',
  `pesotot` float(10,2) DEFAULT '0.00',
  `trasporto` float(10,2) DEFAULT '0.00',
  `totimpo` float(16,2) DEFAULT '0.00',
  `totiva` float(16,2) DEFAULT '0.00',
  `totdoc` float(16,2) DEFAULT '0.00',
  `tdocevaso` varchar(30) DEFAULT NULL,
  `evasonum` varchar(6) DEFAULT '',
  `evasoanno` varchar(4) DEFAULT '',
  `causale` varchar(20) DEFAULT NULL,
  `invio` char(2) DEFAULT NULL,
  `id_collo` varchar(20) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`anno`,`ndoc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS catmer;

CREATE TABLE `catmer` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `codice` varchar(18) NOT NULL,
  `catmer` varchar(70) DEFAULT NULL,
  `imballo` int(1) NOT NULL DEFAULT '0',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS causali_contabili;

CREATE TABLE `causali_contabili` (
  `causale` varchar(3) NOT NULL COMMENT 'codice causale',
  `descrizione` varchar(50) DEFAULT NULL COMMENT 'descrizione causale',
  `conto_1` char(8) DEFAULT NULL,
  `conto_2` char(8) DEFAULT NULL,
  `conto_3` char(8) DEFAULT NULL,
  `conto_4` char(8) DEFAULT NULL,
  `conto_5` char(8) DEFAULT NULL,
  `conto_6` char(8) DEFAULT NULL,
  `conto_7` char(8) DEFAULT NULL,
  `conto_8` char(8) DEFAULT NULL,
  `conto_9` char(8) DEFAULT NULL,
  `conto_10` char(8) DEFAULT NULL,
  PRIMARY KEY (`causale`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tabella causali contabili';

DROP TABLE IF EXISTS clienti;

CREATE TABLE `clienti` (
  `codice` float NOT NULL,
  `data_reg` date NOT NULL DEFAULT '0000-00-00',
  `ragsoc` varchar(100) NOT NULL DEFAULT '',
  `ragsoc2` varchar(100) DEFAULT NULL,
  `indirizzo` varchar(60) DEFAULT NULL,
  `cap` varchar(5) DEFAULT NULL,
  `citta` varchar(60) DEFAULT NULL,
  `prov` char(2) DEFAULT NULL,
  `codnazione` varchar(30) DEFAULT NULL,
  `codfisc` varchar(16) DEFAULT NULL,
  `piva` varchar(14) DEFAULT NULL,
  `contatto` varchar(60) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `telefono2` varchar(20) DEFAULT NULL,
  `cell` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `iva` char(3) DEFAULT NULL,
  `codpag` varchar(20) DEFAULT NULL,
  `banca` varchar(50) DEFAULT NULL,
  `abi` varchar(5) DEFAULT NULL,
  `cab` varchar(5) DEFAULT NULL,
  `cin` char(1) DEFAULT NULL,
  `cc` varchar(12) DEFAULT NULL,
  `iban` varchar(4) DEFAULT NULL,
  `swift` varchar(11) DEFAULT NULL,
  `scontocli` float(4,2) DEFAULT '0.00',
  `scontocli2` float(4,2) DEFAULT '0.00',
  `scontocli3` float(4,2) DEFAULT '0.00',
  `listino` char(3) DEFAULT NULL,
  `codagente` varchar(6) DEFAULT NULL,
  `zona` varchar(20) DEFAULT NULL,
  `dragsoc` varchar(100) DEFAULT NULL,
  `dragsoc2` varchar(100) DEFAULT NULL,
  `dindirizzo` varchar(60) DEFAULT NULL,
  `dcap` varchar(5) DEFAULT NULL,
  `dcitta` varchar(60) DEFAULT NULL,
  `dprov` char(2) DEFAULT NULL,
  `dcodnazione` varchar(30) DEFAULT NULL,
  `telefonodest` varchar(20) DEFAULT NULL,
  `faxdest` varchar(20) DEFAULT '',
  `email` varchar(60) DEFAULT NULL,
  `email2` varchar(60) DEFAULT NULL,
  `email3` varchar(60) DEFAULT '',
  `sitocli` varchar(100) DEFAULT NULL,
  `bloccocli` char(2) NOT NULL DEFAULT 'no',
  `privacy` char(2) DEFAULT 'NO',
  `username` varchar(80) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `note` text,
  `vettore` varchar(40) DEFAULT NULL,
  `porto` varchar(10) DEFAULT NULL,
  `tiposoc` char(2) DEFAULT NULL,
  `allegato` char(2) DEFAULT NULL,
  `nintento` varchar(15) DEFAULT NULL,
  `nproto` varchar(15) DEFAULT NULL,
  `cod_conto` char(8) DEFAULT NULL COMMENT 'codice piano dei conti',
  `indice_pa` varchar(7) DEFAULT NULL,
  `cod_ute_dest` varchar(15) DEFAULT NULL,
  `es_selezione` char(2) NOT NULL DEFAULT 'NO',
  `es_pubblicita` char(2) NOT NULL DEFAULT 'NO',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`codice`),
  UNIQUE KEY `pi` (`piva`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS co_dettaglio;

CREATE TABLE `co_dettaglio` (
  `anno` varchar(4) NOT NULL DEFAULT '',
  `suffix` char(1) NOT NULL DEFAULT 'A',
  `ndoc` float NOT NULL DEFAULT '0',
  `rigo` decimal(4,1) NOT NULL DEFAULT '0.0',
  `utente` varchar(6) DEFAULT NULL,
  `articolo` varchar(15) DEFAULT NULL,
  `descrizione` text,
  `unita` char(3) DEFAULT NULL,
  `quantita` float(16,2) DEFAULT '0.00',
  `qtaevasa` float(16,2) DEFAULT '0.00',
  `qtaestratta` float(16,2) DEFAULT '0.00',
  `qtasaldo` float(16,2) DEFAULT '0.00',
  `rsaldo` char(2) NOT NULL DEFAULT 'NO',
  `listino` float(16,2) DEFAULT '0.00',
  `scva` float DEFAULT '0',
  `scvb` float DEFAULT '0',
  `scvc` float DEFAULT '0',
  `nettovendita` float(16,2) DEFAULT '0.00',
  `totriga` float(16,2) DEFAULT '0.00',
  `iva` char(3) DEFAULT NULL,
  `totrigaprovv` float(10,2) DEFAULT NULL,
  `peso` float(10,3) DEFAULT '0.000',
  `consegna` char(10) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS co_testacalce;

CREATE TABLE `co_testacalce` (
  `anno` varchar(4) NOT NULL DEFAULT '',
  `suffix` char(1) NOT NULL DEFAULT 'A',
  `ndoc` float NOT NULL DEFAULT '0',
  `datareg` date NOT NULL DEFAULT '0000-00-00',
  `utente` varchar(6) NOT NULL DEFAULT '',
  `dragsoc` varchar(40) DEFAULT NULL,
  `dragsoc2` varchar(60) DEFAULT NULL,
  `dindirizzo` varchar(30) DEFAULT NULL,
  `dcap` varchar(5) DEFAULT NULL,
  `dcitta` varchar(30) DEFAULT NULL,
  `dprov` char(2) DEFAULT NULL,
  `dcodnazione` char(3) DEFAULT NULL,
  `modpag` varchar(20) DEFAULT NULL,
  `banca` varchar(30) DEFAULT NULL,
  `vettore` varchar(40) DEFAULT NULL,
  `scontofattura` float(10,2) DEFAULT '0.00',
  `spesevarie` float(10,2) DEFAULT '0.00',
  `porto` varchar(10) DEFAULT NULL,
  `aspetto` varchar(40) DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT '',
  `note` text,
  `colli` int(4) DEFAULT '0',
  `pesotot` float(10,2) DEFAULT '0.00',
  `trasporto` float(10,2) DEFAULT '0.00',
  `totimpo` float(16,2) DEFAULT '0.00',
  `totiva` float(16,2) DEFAULT '0.00',
  `totdoc` float(16,2) DEFAULT '0.00',
  `tdocevaso` varchar(30) DEFAULT NULL,
  `evasonum` varchar(6) DEFAULT '',
  `evasoanno` varchar(4) DEFAULT '',
  `evasosuffix` char(1) DEFAULT 'A',
  `rev` int(3) NOT NULL DEFAULT '1',
  `invio` char(2) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`anno`,`suffix`,`ndoc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS destinazioni;

CREATE TABLE `destinazioni` (
  `utente` varchar(6) NOT NULL DEFAULT '',
  `codice` int(2) NOT NULL DEFAULT '1',
  `datareg` date NOT NULL DEFAULT '0000-00-00',
  `dragsoc` varchar(40) DEFAULT NULL,
  `dragsoc2` varchar(60) DEFAULT NULL,
  `dindirizzo` varchar(30) DEFAULT NULL,
  `dcap` varchar(5) DEFAULT NULL,
  `dcitta` varchar(30) DEFAULT NULL,
  `dprov` char(2) DEFAULT NULL,
  `dcodnazione` varchar(30) DEFAULT NULL,
  `telefonodest` varchar(20) DEFAULT NULL,
  `faxdest` varchar(20) DEFAULT '',
  `demail` varchar(60) DEFAULT NULL,
  `dcontatto` varchar(60) DEFAULT NULL,
  `predefinito` tinyint(1) NOT NULL DEFAULT '0',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`utente`,`codice`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS doc_basket;

CREATE TABLE `doc_basket` (
  `sessionid` varchar(32) NOT NULL DEFAULT '',
  `rigo` double(4,1) NOT NULL AUTO_INCREMENT,
  `anno` varchar(4) NOT NULL DEFAULT '',
  `suffix` char(1) NOT NULL DEFAULT 'A',
  `ndoc` float NOT NULL DEFAULT '0',
  `utente` varchar(6) DEFAULT NULL,
  `articolo` varchar(15) NOT NULL DEFAULT '0',
  `artfor` varchar(20) DEFAULT NULL,
  `descrizione` text,
  `unita` char(3) DEFAULT NULL,
  `quantita` float(16,2) DEFAULT '0.00',
  `qtaevasa` float(16,2) DEFAULT NULL,
  `qtaestratta` float(16,2) DEFAULT NULL,
  `qtasaldo` float(16,2) DEFAULT NULL,
  `rsaldo` char(2) DEFAULT NULL,
  `listino` float(16,2) DEFAULT '0.00',
  `sca` float DEFAULT '0',
  `scb` float DEFAULT '0',
  `scc` float DEFAULT '0',
  `nettovendita` float(16,2) DEFAULT '0.00',
  `totriga` float(16,2) DEFAULT '0.00',
  `iva` char(3) DEFAULT NULL,
  `totrigaprovv` float(10,2) DEFAULT NULL,
  `peso` float(10,3) DEFAULT '0.000',
  `consegna` char(10) DEFAULT NULL,
  `agg` char(2) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sessionid`,`rigo`,`anno`,`suffix`,`ndoc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS effetti;

CREATE TABLE `effetti` (
  `tipoeff` char(1) NOT NULL DEFAULT '',
  `annoeff` varchar(4) NOT NULL DEFAULT '',
  `numeff` float NOT NULL DEFAULT '0',
  `dataeff` date NOT NULL DEFAULT '0000-00-00',
  `scadeff` date NOT NULL DEFAULT '0000-00-00',
  `impeff` float(16,2) NOT NULL DEFAULT '0.00',
  `tipodoc` varchar(30) DEFAULT NULL,
  `annodoc` varchar(4) DEFAULT NULL,
  `suffixdoc` char(1) DEFAULT 'A',
  `numdoc` float DEFAULT NULL,
  `datadoc` date DEFAULT NULL,
  `totdoc` float(16,2) DEFAULT NULL,
  `codcli` varchar(6) DEFAULT NULL,
  `modpag` varchar(4) DEFAULT NULL,
  `bancapp` varchar(100) DEFAULT NULL,
  `abi` varchar(5) DEFAULT NULL,
  `cab` varchar(5) DEFAULT NULL,
  `cin` char(1) DEFAULT NULL,
  `cc` varchar(12) DEFAULT NULL,
  `ndistinta` float DEFAULT NULL,
  `datadist` date DEFAULT NULL,
  `bancadist` varchar(100) DEFAULT NULL,
  `presenta` varchar(2) DEFAULT 'NO',
  `status` varchar(20) DEFAULT NULL,
  `datapag` date DEFAULT '0000-00-00',
  `tipo_pres` char(3) DEFAULT NULL,
  `contabilita` char(2) DEFAULT 'NO',
  `spese` float(16,2) DEFAULT '0.00',
  `conta_anno` year(4) NOT NULL,
  `conta_nreg` float NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tipoeff`,`annoeff`,`numeff`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS fornitori;

CREATE TABLE `fornitori` (
  `codice` float NOT NULL,
  `data_reg` date NOT NULL DEFAULT '0000-00-00',
  `ragsoc` varchar(100) NOT NULL DEFAULT '',
  `ragsoc2` varchar(100) DEFAULT NULL,
  `indirizzo` varchar(60) DEFAULT NULL,
  `cap` varchar(5) DEFAULT NULL,
  `citta` varchar(60) DEFAULT NULL,
  `prov` char(2) DEFAULT NULL,
  `codnazione` varchar(30) DEFAULT NULL,
  `codfisc` varchar(16) DEFAULT NULL,
  `piva` varchar(14) DEFAULT NULL,
  `contatto` varchar(60) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `telefono2` varchar(20) DEFAULT NULL,
  `cell` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `iva` char(3) DEFAULT NULL,
  `codpag` varchar(20) DEFAULT NULL,
  `banca` varchar(50) DEFAULT NULL,
  `abi` varchar(5) DEFAULT NULL,
  `cab` varchar(5) DEFAULT NULL,
  `cin` char(1) DEFAULT NULL,
  `cc` varchar(12) DEFAULT NULL,
  `iban` varchar(4) DEFAULT NULL,
  `swift` varchar(11) DEFAULT NULL,
  `spesometro` char(2) DEFAULT 'NO',
  `listino` char(3) DEFAULT NULL,
  `codagente` varchar(6) DEFAULT NULL,
  `zona` varchar(20) DEFAULT NULL,
  `dragsoc` varchar(100) DEFAULT NULL,
  `dragsoc2` varchar(100) DEFAULT NULL,
  `dindirizzo` varchar(60) DEFAULT NULL,
  `dcap` varchar(5) DEFAULT NULL,
  `dcitta` varchar(60) DEFAULT NULL,
  `dprov` char(2) DEFAULT NULL,
  `dcodnazione` varchar(30) DEFAULT NULL,
  `telefonodest` varchar(20) DEFAULT NULL,
  `faxdest` varchar(20) DEFAULT '',
  `email` varchar(60) DEFAULT NULL,
  `email2` varchar(60) DEFAULT NULL,
  `email3` varchar(60) DEFAULT '',
  `sitofor` varchar(100) DEFAULT NULL,
  `privacy` char(2) DEFAULT 'NO',
  `note` text,
  `vettore` varchar(40) DEFAULT NULL,
  `porto` varchar(10) DEFAULT NULL,
  `tiposoc` char(2) DEFAULT NULL,
  `allegato` char(2) DEFAULT NULL,
  `cod_conto` char(8) DEFAULT NULL COMMENT 'codice piano dei conti',
  `indice_pa` varchar(7) DEFAULT NULL,
  `cod_ute_dest` varchar(15) DEFAULT NULL,
  `es_selezione` char(2) NOT NULL DEFAULT 'NO',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`codice`),
  UNIQUE KEY `pi` (`piva`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS fv_dettaglio;

CREATE TABLE `fv_dettaglio` (
  `tdoc` varchar(30) DEFAULT '',
  `anno` varchar(4) NOT NULL DEFAULT '',
  `suffix` char(1) NOT NULL DEFAULT 'A',
  `ndoc` float NOT NULL DEFAULT '0',
  `rigo` decimal(4,1) NOT NULL DEFAULT '0.0',
  `utente` varchar(6) DEFAULT NULL,
  `articolo` varchar(15) DEFAULT NULL,
  `descrizione` text,
  `unita` char(3) DEFAULT NULL,
  `quantita` float(16,2) DEFAULT '0.00',
  `listino` float(16,2) DEFAULT '0.00',
  `scva` float DEFAULT '0',
  `scvb` float DEFAULT '0',
  `scvc` float DEFAULT '0',
  `nettovendita` float(16,2) DEFAULT '0.00',
  `totriga` float(16,2) DEFAULT '0.00',
  `totrigaprovv` float(10,2) DEFAULT '0.00',
  `iva` char(3) DEFAULT NULL,
  `totrigaiva` float(10,2) DEFAULT NULL,
  `peso` float(10,3) DEFAULT '0.000',
  `qtaevasa` float(16,2) DEFAULT '0.00',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS fv_testacalce;

CREATE TABLE `fv_testacalce` (
  `tdoc` varchar(30) NOT NULL DEFAULT '',
  `anno` varchar(4) NOT NULL DEFAULT '',
  `suffix` char(1) NOT NULL DEFAULT 'A',
  `ndoc` float NOT NULL DEFAULT '0',
  `datareg` date NOT NULL DEFAULT '0000-00-00',
  `utente` varchar(6) NOT NULL DEFAULT '',
  `dragsoc` varchar(40) DEFAULT NULL,
  `dragsoc2` varchar(60) DEFAULT NULL,
  `dindirizzo` varchar(30) DEFAULT NULL,
  `dcap` varchar(5) DEFAULT NULL,
  `dcitta` varchar(30) DEFAULT NULL,
  `dprov` char(2) DEFAULT NULL,
  `dcodnazione` varchar(30) DEFAULT NULL,
  `vettore` varchar(40) DEFAULT NULL,
  `listino` int(3) DEFAULT '0',
  `porto` varchar(10) DEFAULT NULL,
  `aspetto` varchar(40) DEFAULT NULL,
  `note` text,
  `zona` varchar(10) DEFAULT NULL,
  `agente` varchar(40) DEFAULT '',
  `modpag` varchar(50) DEFAULT '',
  `banca` varchar(100) DEFAULT '',
  `abi` varchar(5) DEFAULT '',
  `cab` varchar(5) DEFAULT '',
  `cin` char(1) DEFAULT '',
  `cc` varchar(12) DEFAULT '',
  `iban` varchar(4) DEFAULT NULL,
  `swift` varchar(11) DEFAULT NULL,
  `colli` int(4) DEFAULT '0',
  `pesotot` float(10,2) DEFAULT '0.00',
  `nettomerce` float(10,2) NOT NULL DEFAULT '0.00',
  `scoinco` float(10,2) DEFAULT '0.00',
  `spesevarie` float(10,2) DEFAULT '0.00',
  `trasporto` float(10,2) DEFAULT '0.00',
  `imballo` float(10,2) DEFAULT '0.00',
  `sp_bancarie` float(10,2) DEFAULT '0.00',
  `cod_iva_1` char(3) DEFAULT NULL,
  `imponibile_1` float(16,2) DEFAULT NULL,
  `imposta_1` float(16,2) DEFAULT NULL,
  `cod_iva_2` char(3) DEFAULT NULL,
  `imponibile_2` float(16,2) DEFAULT NULL,
  `imposta_2` float(16,2) DEFAULT NULL,
  `cod_iva_3` char(3) DEFAULT NULL,
  `imponibile_3` float(16,2) DEFAULT NULL,
  `imposta_3` float(16,2) DEFAULT NULL,
  `cod_iva_4` char(3) DEFAULT NULL,
  `imponibile_4` float(16,2) DEFAULT NULL,
  `imposta_4` float(16,2) DEFAULT NULL,
  `cod_iva_5` char(3) DEFAULT NULL,
  `imponibile_5` float(16,2) DEFAULT NULL,
  `imposta_5` float(16,2) DEFAULT NULL,
  `totimpo` float(16,2) DEFAULT '0.00',
  `totiva` float(16,2) DEFAULT '0.00',
  `totdoc` float(16,2) DEFAULT '0.00',
  `totprovv` float(10,2) DEFAULT '0.00',
  `status` varchar(20) DEFAULT '',
  `invio` char(2) DEFAULT '',
  `tdocevaso` varchar(30) DEFAULT NULL,
  `evasonum` varchar(6) DEFAULT NULL,
  `evasoanno` varchar(4) DEFAULT NULL,
  `contabilita` char(2) DEFAULT 'NO',
  `id_collo` varchar(20) DEFAULT NULL,
  `sp_bolli` float(10,2) DEFAULT '0.00',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tdoc`,`anno`,`suffix`,`ndoc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS imballi;

CREATE TABLE `imballi` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `imballo` varchar(20) NOT NULL DEFAULT '',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS liquid_iva_periodica;

CREATE TABLE `liquid_iva_periodica` (
  `anno` int(4) NOT NULL,
  `periodo` char(2) NOT NULL,
  `iva_acq` float(16,2) DEFAULT NULL,
  `iva_vend` float(16,2) DEFAULT NULL,
  `diff_periodo` float(16,2) DEFAULT NULL,
  `cred_residuo` float(16,2) DEFAULT '0.00',
  `val_liquid` float(16,2) DEFAULT NULL,
  `versato` char(2) DEFAULT 'NO',
  `banca` char(2) DEFAULT NULL,
  `data_vers` date DEFAULT NULL,
  `n_reg` float DEFAULT NULL,
  `cod_tributo` int(4) DEFAULT NULL,
  PRIMARY KEY (`anno`,`periodo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS listini;

CREATE TABLE `listini` (
  `codarticolo` varchar(15) NOT NULL DEFAULT '',
  `listino` float(10,2) NOT NULL DEFAULT '0.00',
  `rigo` smallint(6) NOT NULL DEFAULT '0',
  `disponibilita` float(10,2) DEFAULT '0.00',
  `ts1` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`codarticolo`,`rigo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS magastorico;

CREATE TABLE `magastorico` (
  `tdoc` varchar(30) DEFAULT '',
  `anno` varchar(4) NOT NULL DEFAULT '',
  `suffix` char(1) DEFAULT 'A',
  `ndoc` float DEFAULT NULL,
  `datareg` date DEFAULT '0000-00-00',
  `tut` varchar(6) NOT NULL DEFAULT '',
  `rigo` decimal(4,1) NOT NULL DEFAULT '0.0',
  `utente` varchar(6) DEFAULT '',
  `articolo` varchar(15) NOT NULL DEFAULT '',
  `qtacarico` double(16,2) DEFAULT '0.00',
  `valoreacq` double(16,2) DEFAULT '0.00',
  `qtascarico` double(16,2) DEFAULT '0.00',
  `valorevend` double(16,2) DEFAULT '0.00',
  `ddtfornitore` varchar(20) DEFAULT '',
  `fatturacq` varchar(20) DEFAULT '',
  `protoiva` char(5) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS magazzino;

CREATE TABLE `magazzino` (
  `tdoc` varchar(30) DEFAULT '',
  `anno` varchar(4) NOT NULL DEFAULT '',
  `suffix` char(1) DEFAULT 'A',
  `ndoc` float DEFAULT NULL,
  `datareg` date DEFAULT '0000-00-00',
  `tut` varchar(6) NOT NULL DEFAULT '',
  `rigo` decimal(4,1) NOT NULL DEFAULT '0.0',
  `utente` varchar(6) DEFAULT '',
  `articolo` varchar(15) NOT NULL DEFAULT '',
  `qtacarico` double(16,2) DEFAULT '0.00',
  `valoreacq` double(16,2) DEFAULT '0.00',
  `qtascarico` double(16,2) DEFAULT '0.00',
  `valorevend` double(16,2) DEFAULT '0.00',
  `ddtfornitore` varchar(20) DEFAULT '',
  `fatturacq` varchar(20) DEFAULT '',
  `protoiva` char(5) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS oc_dettaglio;

CREATE TABLE `oc_dettaglio` (
  `anno` varchar(4) NOT NULL DEFAULT '',
  `suffix` char(1) NOT NULL DEFAULT 'A',
  `ndoc` float NOT NULL DEFAULT '0',
  `rigo` decimal(4,1) NOT NULL DEFAULT '0.0',
  `utente` varchar(6) DEFAULT NULL,
  `articolo` varchar(15) DEFAULT NULL,
  `descrizione` text,
  `unita` char(3) DEFAULT NULL,
  `quantita` float(16,2) DEFAULT '0.00',
  `qtaevasa` float(16,2) DEFAULT '0.00',
  `qtaestratta` float(16,2) DEFAULT NULL,
  `qtasaldo` float(16,2) DEFAULT '0.00',
  `rsaldo` char(2) DEFAULT NULL,
  `listino` float(16,2) DEFAULT '0.00',
  `scva` float DEFAULT '0',
  `scvb` float DEFAULT '0',
  `scvc` float DEFAULT '0',
  `nettovendita` float(16,2) DEFAULT '0.00',
  `totriga` float(16,2) DEFAULT '0.00',
  `iva` char(3) DEFAULT NULL,
  `totrigaprovv` float(10,2) DEFAULT NULL,
  `peso` float(10,3) DEFAULT '0.000',
  `dcons` datetime DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS oc_testacalce;

CREATE TABLE `oc_testacalce` (
  `anno` varchar(4) NOT NULL DEFAULT '',
  `suffix` char(1) NOT NULL DEFAULT 'A',
  `ndoc` float NOT NULL DEFAULT '0',
  `datareg` date NOT NULL DEFAULT '0000-00-00',
  `utente` varchar(6) NOT NULL DEFAULT '',
  `dragsoc` varchar(40) DEFAULT NULL,
  `dragsoc2` varchar(60) DEFAULT NULL,
  `dindirizzo` varchar(30) DEFAULT NULL,
  `dcap` varchar(5) DEFAULT NULL,
  `dcitta` varchar(30) DEFAULT NULL,
  `dprov` char(2) DEFAULT NULL,
  `dcodnazione` char(3) DEFAULT NULL,
  `modpag` varchar(20) DEFAULT NULL,
  `banca` varchar(30) DEFAULT NULL,
  `vettore` varchar(40) DEFAULT NULL,
  `scontofattura` float(10,2) DEFAULT '0.00',
  `spesevarie` float(10,2) DEFAULT '0.00',
  `porto` varchar(10) DEFAULT NULL,
  `aspetto` varchar(40) DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT '',
  `note` text,
  `colli` int(4) DEFAULT '0',
  `pesotot` float(10,2) DEFAULT '0.00',
  `trasporto` float(10,2) DEFAULT '0.00',
  `totimpo` float(16,2) DEFAULT '0.00',
  `totiva` float(16,2) DEFAULT '0.00',
  `totdoc` float(16,2) DEFAULT '0.00',
  `tdocevaso` varchar(30) DEFAULT NULL,
  `evasonum` varchar(6) DEFAULT '',
  `evasoanno` varchar(4) DEFAULT '',
  `evasosuffix` char(1) DEFAULT 'A',
  `rev` int(3) DEFAULT '1',
  `invio` char(2) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`anno`,`suffix`,`ndoc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS of_dettaglio;

CREATE TABLE `of_dettaglio` (
  `anno` varchar(4) NOT NULL DEFAULT '',
  `suffix` char(1) NOT NULL DEFAULT 'A',
  `ndoc` float NOT NULL DEFAULT '0',
  `rigo` decimal(4,1) NOT NULL DEFAULT '0.0',
  `utente` varchar(6) DEFAULT NULL,
  `articolo` varchar(15) DEFAULT NULL,
  `artfor` varchar(20) DEFAULT NULL,
  `descrizione` text,
  `unita` char(3) DEFAULT NULL,
  `quantita` float(16,2) DEFAULT '0.00',
  `qtaevasa` float(16,2) DEFAULT '0.00',
  `qtaestratta` float(16,2) DEFAULT '0.00',
  `qtasaldo` float(16,2) DEFAULT '0.00',
  `rsaldo` char(2) NOT NULL DEFAULT 'NO',
  `listino` float(16,2) DEFAULT '0.00',
  `scaa` float DEFAULT '0',
  `scab` float DEFAULT '0',
  `scac` float DEFAULT '0',
  `nettoacq` float(16,2) DEFAULT '0.00',
  `totriga` float(16,2) DEFAULT '0.00',
  `peso` float(10,3) DEFAULT '0.000',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS of_testacalce;

CREATE TABLE `of_testacalce` (
  `anno` varchar(4) NOT NULL DEFAULT '',
  `suffix` char(1) NOT NULL DEFAULT 'A',
  `ndoc` float NOT NULL DEFAULT '0',
  `datareg` date NOT NULL DEFAULT '0000-00-00',
  `utente` varchar(6) NOT NULL DEFAULT '',
  `dragsoc` varchar(40) DEFAULT NULL,
  `dragsoc2` varchar(60) DEFAULT NULL,
  `dindirizzo` varchar(30) DEFAULT NULL,
  `dcap` varchar(5) DEFAULT NULL,
  `dcitta` varchar(30) DEFAULT NULL,
  `dprov` char(2) DEFAULT NULL,
  `dcodnazione` char(3) DEFAULT NULL,
  `modpag` varchar(20) DEFAULT NULL,
  `banca` varchar(30) DEFAULT NULL,
  `vettore` varchar(40) DEFAULT NULL,
  `scontofattura` float(10,2) DEFAULT '0.00',
  `listino` int(3) DEFAULT '0',
  `porto` varchar(10) DEFAULT NULL,
  `aspetto` varchar(40) DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT '',
  `note` text,
  `colli` double(4,2) DEFAULT '0.00',
  `pesotot` float(10,2) DEFAULT '0.00',
  `totimpo` float(16,2) DEFAULT '0.00',
  `totiva` float(16,2) DEFAULT '0.00',
  `totdoc` float(16,2) DEFAULT '0.00',
  `tdocevaso` varchar(30) DEFAULT NULL,
  `evasonum` varchar(6) DEFAULT NULL,
  `evasoanno` varchar(4) DEFAULT NULL,
  `evasosuffix` char(1) DEFAULT 'A',
  `invio` char(2) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`anno`,`suffix`,`ndoc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS pagamenti;

CREATE TABLE `pagamenti` (
  `codice` varchar(5) NOT NULL DEFAULT '',
  `descrizione` varchar(100) DEFAULT NULL,
  `sconto` float(2,0) DEFAULT '0',
  `rataiva` int(1) DEFAULT '1',
  `scadfissa` char(2) DEFAULT '',
  `unomese` char(2) DEFAULT '',
  `duemese` char(2) DEFAULT '',
  `tipopag` int(1) DEFAULT '0',
  `nscad` char(2) DEFAULT '',
  `ggprimascad` char(3) DEFAULT '',
  `ggtrascad` char(3) DEFAULT '',
  `dffm` char(2) DEFAULT NULL,
  PRIMARY KEY (`codice`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS piano_conti;

CREATE TABLE `piano_conti` (
  `codconto` char(8) NOT NULL COMMENT 'codice piano dei conti',
  `descrizione` varchar(50) DEFAULT NULL,
  `natcon` char(1) NOT NULL COMMENT 'natura conto',
  `livello` int(1) NOT NULL COMMENT 'livello del codice',
  `tipo_cf` char(1) NOT NULL COMMENT 'tipo di campo',
  `cod_cee` varchar(10) DEFAULT NULL COMMENT 'codice allacciamento bilancio cee',
  PRIMARY KEY (`codconto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS prezzi_cliente;

CREATE TABLE `prezzi_cliente` (
  `codarticolo` varchar(15) NOT NULL DEFAULT '',
  `descrizione` varchar(80) NOT NULL DEFAULT '',
  `listino` float(10,2) NOT NULL DEFAULT '0.00',
  `cliente` varchar(6) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS prima_nota;

CREATE TABLE `prima_nota` (
  `anno` int(4) NOT NULL COMMENT 'anno_reg',
  `nreg` float NOT NULL COMMENT 'numero registrazione',
  `rigo` int(3) NOT NULL AUTO_INCREMENT COMMENT 'rigo operazione',
  `data_reg` date DEFAULT NULL COMMENT 'data registrazione',
  `data_cont` date DEFAULT NULL COMMENT 'data contabile',
  `segno` char(1) DEFAULT NULL COMMENT 'segno operazione ',
  `causale` char(3) DEFAULT NULL COMMENT 'causale contabile',
  `descrizione` varchar(50) DEFAULT NULL COMMENT 'descrizione operazione',
  `ndoc` varchar(20) DEFAULT NULL,
  `anno_doc` int(4) DEFAULT NULL COMMENT 'anno documeno',
  `suffix_doc` char(1) DEFAULT 'A',
  `data_doc` date DEFAULT NULL COMMENT 'data documento',
  `conto` char(8) NOT NULL COMMENT 'conto del piano dei conti',
  `desc_conto` varchar(100) DEFAULT NULL COMMENT 'descrizione conto',
  `iva` char(3) DEFAULT NULL COMMENT 'iva associata',
  `dare` double(16,2) DEFAULT NULL COMMENT 'valore in dare',
  `avere` double(16,2) DEFAULT NULL COMMENT 'valore in avere',
  `tipopag` char(4) DEFAULT NULL COMMENT 'tipo pagamento',
  `nproto` float DEFAULT NULL COMMENT 'numero protocollo',
  `anno_proto` int(4) DEFAULT NULL COMMENT 'anno protocollo',
  `suffix_proto` char(1) DEFAULT 'A',
  `liquid_iva` char(2) DEFAULT 'NO',
  `status` varchar(20) DEFAULT NULL,
  `giornale` int(5) DEFAULT NULL,
  `sp_metro` char(2) DEFAULT 'NO' COMMENT 'campo relativo allo spesometro 2011',
  `note` text,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`anno`,`nreg`,`rigo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tabella muovimenti contabili';

DROP TABLE IF EXISTS prima_nota_basket;

CREATE TABLE `prima_nota_basket` (
  `sessionid` varchar(32) NOT NULL,
  `anno` int(4) NOT NULL COMMENT 'anno_reg',
  `nreg` float NOT NULL COMMENT 'numero registrazione',
  `rigo` int(3) NOT NULL AUTO_INCREMENT COMMENT 'rigo operazione',
  `data_reg` date DEFAULT NULL COMMENT 'data registrazione',
  `data_cont` date DEFAULT NULL COMMENT 'data contabile',
  `segno` char(1) DEFAULT NULL COMMENT 'segno operazione ',
  `causale` char(3) DEFAULT NULL COMMENT 'causale contabile',
  `descrizione` varchar(100) DEFAULT NULL COMMENT 'descrizione operazione',
  `ndoc` varchar(20) DEFAULT NULL,
  `anno_doc` int(4) DEFAULT NULL COMMENT 'anno documeno',
  `suffix_doc` char(1) DEFAULT 'A',
  `data_doc` date DEFAULT NULL COMMENT 'data documento',
  `conto` char(8) NOT NULL COMMENT 'conto del piano dei conti',
  `iva` char(3) DEFAULT NULL COMMENT 'iva associata',
  `dare` double(16,2) DEFAULT NULL,
  `avere` double(16,2) DEFAULT NULL,
  `tipopag` char(4) DEFAULT NULL COMMENT 'tipo pagamento',
  `nproto` float DEFAULT NULL COMMENT 'numero protocollo',
  `anno_proto` int(4) DEFAULT NULL COMMENT 'anno protocollo',
  `suffix_proto` char(1) DEFAULT 'A',
  `liquid_iva` char(2) DEFAULT NULL COMMENT 'se si liquidazione iva',
  `note` text,
  PRIMARY KEY (`sessionid`,`anno`,`nreg`,`rigo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tabella muovimenti contabili';

DROP TABLE IF EXISTS promozioni;

CREATE TABLE `promozioni` (
  `cosa` varchar(100) NOT NULL,
  `dadata` char(10) DEFAULT NULL,
  `adata` char(10) DEFAULT NULL,
  `tipo` char(1) DEFAULT NULL,
  `valore` float(16,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tabella gestione promozioni';

DROP TABLE IF EXISTS provvigioni;

CREATE TABLE `provvigioni` (
  `codage` varchar(6) NOT NULL DEFAULT '',
  `ndoc` int(6) NOT NULL DEFAULT '0',
  `anno` varchar(4) NOT NULL DEFAULT '',
  `suffix` char(1) DEFAULT 'A',
  `tdoc` varchar(30) NOT NULL DEFAULT '',
  `datareg` date DEFAULT '0000-00-00',
  `utente` varchar(6) NOT NULL DEFAULT '',
  `totdoc` float(10,2) NOT NULL DEFAULT '0.00',
  `riscosso` float(10,2) DEFAULT '0.00',
  `differenza` float(10,2) DEFAULT '0.00',
  `provvigioni` float(10,2) DEFAULT '0.00',
  `liquid` char(2) DEFAULT 'NO',
  `status` varchar(10) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS pv_dettaglio;

CREATE TABLE `pv_dettaglio` (
  `anno` varchar(4) NOT NULL DEFAULT '',
  `suffix` char(1) NOT NULL DEFAULT 'A',
  `ndoc` float NOT NULL DEFAULT '0',
  `rigo` decimal(4,1) NOT NULL DEFAULT '0.0',
  `utente` varchar(6) DEFAULT NULL,
  `articolo` varchar(15) DEFAULT NULL,
  `descrizione` text,
  `unita` char(3) DEFAULT NULL,
  `quantita` float(16,2) DEFAULT '0.00',
  `qtaevasa` float(16,2) DEFAULT NULL,
  `qtaestratta` float(16,2) DEFAULT NULL,
  `qtasaldo` float(16,2) DEFAULT NULL,
  `rsaldo` char(2) DEFAULT NULL,
  `listino` float(16,2) DEFAULT '0.00',
  `scva` float DEFAULT '0',
  `scvb` float DEFAULT '0',
  `scvc` float DEFAULT '0',
  `nettovendita` float(16,2) DEFAULT '0.00',
  `totriga` float(16,2) DEFAULT '0.00',
  `iva` char(3) DEFAULT NULL,
  `totrigaprovv` float(10,2) DEFAULT NULL,
  `peso` float(10,3) DEFAULT '0.000',
  `consegna` char(10) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS pv_testacalce;

CREATE TABLE `pv_testacalce` (
  `anno` varchar(4) NOT NULL DEFAULT '',
  `suffix` char(1) NOT NULL DEFAULT 'A',
  `ndoc` float NOT NULL DEFAULT '0',
  `datareg` date NOT NULL DEFAULT '0000-00-00',
  `utente` varchar(6) NOT NULL DEFAULT '',
  `dragsoc` varchar(40) DEFAULT NULL,
  `dragsoc2` varchar(60) DEFAULT NULL,
  `dindirizzo` varchar(30) DEFAULT NULL,
  `dcap` varchar(5) DEFAULT NULL,
  `dcitta` varchar(30) DEFAULT NULL,
  `dprov` char(2) DEFAULT NULL,
  `dcodnazione` char(3) DEFAULT NULL,
  `modpag` varchar(20) DEFAULT NULL,
  `banca` varchar(30) DEFAULT NULL,
  `vettore` varchar(40) DEFAULT NULL,
  `scontofattura` float(10,2) DEFAULT '0.00',
  `spesevarie` float(10,2) DEFAULT '0.00',
  `porto` varchar(10) DEFAULT NULL,
  `aspetto` varchar(40) DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT '',
  `note` text,
  `colli` int(4) DEFAULT '0',
  `pesotot` float(10,2) DEFAULT '0.00',
  `trasporto` float(10,2) DEFAULT '0.00',
  `totimpo` float(16,2) DEFAULT '0.00',
  `totiva` float(16,2) DEFAULT '0.00',
  `totdoc` float(16,2) DEFAULT '0.00',
  `tdocevaso` varchar(30) DEFAULT NULL,
  `evasonum` varchar(6) DEFAULT '',
  `evasoanno` varchar(4) DEFAULT '',
  `evasosuffix` char(1) DEFAULT 'A',
  `rev` int(3) DEFAULT '1',
  `invio` char(2) DEFAULT NULL,
  `data_scad` date DEFAULT '0000-00-00',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`anno`,`suffix`,`ndoc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS scadenziario;

CREATE TABLE `scadenziario` (
  `anno` year(4) NOT NULL DEFAULT '0000',
  `nscad` int(3) NOT NULL AUTO_INCREMENT,
  `data_scad` date NOT NULL,
  `descrizione` varchar(80) NOT NULL,
  `importo` float(16,2) NOT NULL,
  `utente` char(15) DEFAULT NULL,
  `anno_doc` int(4) DEFAULT NULL,
  `ndoc` varchar(20) DEFAULT NULL,
  `data_doc` date DEFAULT NULL,
  `anno_proto` int(4) DEFAULT NULL,
  `suffix_proto` char(1) DEFAULT 'A',
  `nproto` float DEFAULT NULL,
  `codpag` char(4) DEFAULT NULL,
  `banca` char(2) DEFAULT NULL,
  `impeff` float(16,2) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `data_pag` date NOT NULL DEFAULT '0000-00-00',
  `contabilita` char(2) NOT NULL DEFAULT 'NO',
  `note` text,
  PRIMARY KEY (`anno`,`nscad`)
) ENGINE=MyISAM AUTO_INCREMENT=3966 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS stampe_layout;

CREATE TABLE `stampe_layout` (
  `tdoc` varchar(20) NOT NULL,
  `ST_NDOC` varchar(20) DEFAULT NULL,
  `ST_LOGOG` varchar(45) DEFAULT NULL,
  `ST_LOGOM` varchar(45) DEFAULT NULL,
  `ST_LOGOP` varchar(45) DEFAULT NULL,
  `ST_TLOGO` int(2) DEFAULT NULL,
  `ST_FONTOLOGO` varchar(20) DEFAULT NULL,
  `ST_FONTLOGOSIZE` char(4) DEFAULT NULL,
  `ST_TIPOTESTATA` int(2) DEFAULT NULL,
  `ST_SOTTOTESTATA` int(2) DEFAULT NULL,
  `ST_FONTINTEST` varchar(20) DEFAULT NULL,
  `ST_FONTINTESTSIZE` char(4) DEFAULT NULL,
  `ST_TIPOCALCE` int(2) DEFAULT NULL,
  `ST_FONTESTACALCE` varchar(20) DEFAULT NULL,
  `ST_FONTESTASIZE` char(4) DEFAULT NULL,
  `ST_FONTCORPO` varchar(20) DEFAULT NULL,
  `ST_FONTCORPOSIZE` char(4) DEFAULT NULL,
  `ST_RPP` int(3) NOT NULL,
  `ST_RIGA` char(2) DEFAULT NULL,
  `ST_RIGA_LC` int(3) DEFAULT NULL,
  `ST_ARTICOLO` varchar(2) DEFAULT NULL,
  `ST_ARTICOLO_ALL` char(10) DEFAULT NULL,
  `ST_ARTICOLO_CT` int(3) DEFAULT NULL,
  `ST_ARTICOLO_LC` int(3) DEFAULT NULL,
  `ST_ARTFOR` char(2) DEFAULT NULL,
  `ST_ARTFOR_ALL` char(10) DEFAULT NULL,
  `ST_ARTFOR_CT` int(3) DEFAULT NULL,
  `ST_ARTFOR_LC` int(3) DEFAULT NULL,
  `ST_DESCRIZIONE` char(2) DEFAULT NULL,
  `ST_DESCRIZIONE_ALL` char(10) DEFAULT NULL,
  `ST_DESCRIZIONE_CT` int(3) DEFAULT NULL,
  `ST_DESCRIZIONE_LC` int(3) DEFAULT NULL,
  `ST_UNITA` char(2) DEFAULT NULL,
  `ST_UNITA_ALL` char(10) DEFAULT NULL,
  `ST_UNITA_LC` int(3) DEFAULT NULL,
  `ST_QUANTITA` char(2) DEFAULT NULL,
  `ST_QUANTITA_ALL` char(10) DEFAULT NULL,
  `ST_QUANTITA_CT` int(3) DEFAULT NULL,
  `ST_QUANTITA_LC` int(3) DEFAULT NULL,
  `ST_QTAEVASA` char(2) DEFAULT NULL,
  `ST_QTAEVASA_ALL` char(10) DEFAULT NULL,
  `ST_QTAEVASA_CT` int(3) DEFAULT NULL,
  `ST_QTAEVASA_LC` int(3) DEFAULT NULL,
  `ST_QTAESTRATTA` char(2) DEFAULT NULL,
  `ST_QTAESTRATTA_ALL` char(10) DEFAULT NULL,
  `ST_QTAESTRATTA_CT` int(3) DEFAULT NULL,
  `ST_QTAESTRATTA_LC` int(3) DEFAULT NULL,
  `ST_QTASALDO` char(2) DEFAULT NULL,
  `ST_QTASALDO_ALL` char(10) DEFAULT NULL,
  `ST_QTASALDO_CT` int(3) DEFAULT NULL,
  `ST_QTASALDO_LC` int(3) DEFAULT NULL,
  `ST_LISTINO` char(2) DEFAULT NULL,
  `ST_LISTINO_ALL` char(10) DEFAULT NULL,
  `ST_LISTINO_CT` int(3) DEFAULT NULL,
  `ST_LISTINO_LC` int(3) DEFAULT NULL,
  `ST_AVV_PN` char(2) DEFAULT NULL,
  `ST_SCONTI` char(2) DEFAULT NULL,
  `ST_SCONTI_ALL` char(10) DEFAULT NULL,
  `ST_SCONTI_LC` int(3) DEFAULT NULL,
  `ST_NETTO` char(2) DEFAULT NULL,
  `ST_NETTO_ALL` char(10) DEFAULT NULL,
  `ST_NETTO_CT` int(3) DEFAULT NULL,
  `ST_NETTO_LC` int(3) DEFAULT NULL,
  `ST_TOTRIGA` char(2) DEFAULT NULL,
  `ST_TOTRIGA_ALL` char(10) DEFAULT NULL,
  `ST_TOTRIGA_CT` int(3) DEFAULT NULL,
  `ST_TOTRIGA_LC` int(3) DEFAULT NULL,
  `ST_CODIVA` char(2) DEFAULT NULL,
  `ST_CODIVA_ALL` char(10) DEFAULT NULL,
  `ST_CODIVA_LC` int(3) DEFAULT NULL,
  `ST_RSALDO` char(2) DEFAULT NULL,
  `ST_RSALDO_ALL` char(10) DEFAULT NULL,
  `ST_RSALDO_LC` int(3) DEFAULT NULL,
  `ST_PESO` char(2) DEFAULT NULL,
  `ST_PESO_ALL` char(10) DEFAULT NULL,
  `ST_PESO_LC` int(3) DEFAULT NULL,
  `ST_CONSEGNA` char(2) DEFAULT NULL,
  `ST_CONSEGNA_ALL` char(10) DEFAULT NULL,
  `ST_CONSEGNA_CT` int(3) DEFAULT NULL,
  `ST_CONSEGNA_LC` int(3) DEFAULT NULL,
  `ST_AVVISO` char(2) DEFAULT NULL,
  `ST_AVVISO_ALL` char(10) DEFAULT NULL,
  `ST_AVVISO_LC` varchar(60) DEFAULT NULL,
  `ST_PREZZI` char(2) DEFAULT NULL,
  `ST_DATA` char(2) DEFAULT NULL,
  `BODY` text,
  `ST_INTERLINEA` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tdoc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS tipart;

CREATE TABLE `tipart` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `codice` varchar(18) NOT NULL,
  `tipoart` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=230 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS todo;

CREATE TABLE `todo` (
  `anno` int(4) NOT NULL,
  `numero` int(5) NOT NULL AUTO_INCREMENT,
  `utente_start` int(3) NOT NULL,
  `utente_end` int(3) NOT NULL,
  `data_start` date NOT NULL DEFAULT '0000-00-00',
  `data_end` date NOT NULL DEFAULT '0000-00-00',
  `completato` int(3) NOT NULL DEFAULT '0',
  `titolo` varchar(50) NOT NULL,
  `corpo` text,
  `priorita` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`anno`,`numero`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS utente_campivari;

CREATE TABLE `utente_campivari` (
  `utente` varchar(6) NOT NULL DEFAULT '',
  `campo1` float(10,2) DEFAULT '0.00',
  `campo2` float(10,2) DEFAULT '0.00',
  `campo3` float(10,2) DEFAULT '0.00',
  `campo4` float(10,2) DEFAULT '0.00',
  `campo5` float(10,2) DEFAULT '0.00',
  `campo6` varchar(100) DEFAULT '',
  `campo7` varchar(100) DEFAULT '',
  `campo8` varchar(100) DEFAULT '',
  `campo9` varchar(100) DEFAULT '',
  `campo10` varchar(100) DEFAULT '',
  `campo11` float(10,2) DEFAULT NULL,
  `campo12` varchar(100) DEFAULT NULL,
  `campo13` float(10,2) DEFAULT NULL,
  `campo14` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`utente`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS utenti;

CREATE TABLE `utenti` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `user` varchar(100) NOT NULL DEFAULT '',
  `pwd` varchar(100) NOT NULL DEFAULT '',
  `perm` varchar(100) DEFAULT '',
  `anagrafiche` int(1) NOT NULL DEFAULT '0',
  `vendite` int(1) NOT NULL DEFAULT '0',
  `magazzino` int(1) NOT NULL DEFAULT '0',
  `contabilita` int(1) NOT NULL DEFAULT '0',
  `stampe` int(1) NOT NULL DEFAULT '0',
  `scadenziario` int(1) NOT NULL DEFAULT '0',
  `setting` int(1) NOT NULL DEFAULT '0',
  `plugins` int(1) NOT NULL DEFAULT '0',
  `blocco` char(2) NOT NULL DEFAULT 'NO',
  `nvolte` int(1) NOT NULL DEFAULT '0',
  `datareg` date NOT NULL DEFAULT '0000-00-00',
  `USER_SCREEN_COLOR_BACKGROUND` char(10) DEFAULT NULL,
  `USER_SCREEN_WIDTH` char(4) DEFAULT '100',
  `USER_SCREEN_FONT_TYPE` char(50) DEFAULT NULL,
  `USER_SCREEN_FONT_SIZE` char(5) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS version;

CREATE TABLE `version` (
  `id` int(2) NOT NULL DEFAULT '1',
  `aguagest` varchar(10) DEFAULT NULL,
  `aguabase` varchar(10) DEFAULT '',
  `notegest` varchar(100) DEFAULT '',
  `notebase` varchar(100) DEFAULT '',
  UNIQUE KEY `unico` (`aguagest`,`aguabase`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='versioni e commenti di agua';

DROP TABLE IF EXISTS vettori;

CREATE TABLE `vettori` (
  `codice` varchar(6) NOT NULL DEFAULT '',
  `vettore` varchar(40) DEFAULT NULL,
  `indirizzo` varchar(100) DEFAULT NULL,
  `telefono` varchar(40) DEFAULT NULL,
  `cell` varchar(40) DEFAULT NULL,
  `fax` varchar(40) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `web` varchar(100) DEFAULT NULL,
  `traking` varchar(200) DEFAULT NULL,
  `note` text,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`codice`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS zone;

CREATE TABLE `zone` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

