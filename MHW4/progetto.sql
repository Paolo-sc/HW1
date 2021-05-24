-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 25, 2021 alle 00:03
-- Versione del server: 8.0.22
-- Versione PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `progetto`
--

DELIMITER $$
--
-- Procedure
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `aggiungi_acquisto` (IN `cliente` INTEGER, IN `articolo` INTEGER, IN `fornitore` INTEGER, IN `quantita` INTEGER)  BEGIN
	SELECT A.quantità_fornita
    FROM ARTICOLO A
    WHERE A.id_articolo = articolo and A.fornitoreArt = fornitore;
	CASE
		WHEN (SELECT A.quantità_fornita
		FROM ARTICOLO A
		WHERE A.id_articolo = articolo and A.fornitoreArt = fornitore) >= quantità
        THEN INSERT INTO ACQUISTI VALUES(cliente,articolo,fornitore,quantità);
        ELSE signal sqlstate '45000' set message_text = 'Quantità troppo elevata';
	END CASE;
    
    SELECT * FROM ACQUISTI;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `articoli_stesso_importo` ()  BEGIN
	SELECT A.id_articolo,A.fornitoreArt
    FROM ARTICOLO A JOIN ARTICOLO AR
    WHERE A.importo_lordo = AR.importo_lordo AND A.id_articolo <> AR.id_articolo
    GROUP BY A.id_articolo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `inf_cliente_privato` (IN `id` INTEGER)  BEGIN
	SELECT *
	FROM CLIENTE C JOIN CLIENTE_PRIVATO CP ON C.id_cliente = CP.id_cliente_privato
    WHERE id_cliente = id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `inf_richieste` (IN `id` INTEGER)  BEGIN
	DROP TEMPORARY TABLE IF EXISTS RICHIESTE;
    CREATE TEMPORARY TABLE RICHIESTE(
		id_clienteR INTEGER,
        interventoR INTEGER
    );
	
    INSERT INTO RICHIESTE
    SELECT  RC.clienteCorrente,RC.interventoCorrente
    FROM RICHIESTA_CORRENTE RC
    WHERE RC.clienteCorrente = id;
    
    INSERT INTO RICHIESTE
    SELECT  SR.clienteStorico,SR.interventoStorico
    FROM STORICO_RICHIESTE SR
    WHERE SR.clienteStorico = id;
    
    SELECT * FROM RICHIESTE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `p1` (IN `cliente_id` INTEGER)  BEGIN
	IF EXISTS (SELECT clienteCorrente FROM RICHIESTA_CORRENTE WHERE clienteCorrente = cliente_id) THEN
		IF NOT EXISTS(SELECT clienteStorico FROM STORICO_RICHIESTE WHERE clienteStorico = cliente_id) THEN
			SELECT RC.*
			FROM RICHIESTA_CORRENTE RC
            WHERE RC.clienteCorrente = cliente_id;
		ELSE
			SELECT SR.*,RC.*
			FROM STORICO_RICHIESTE SR JOIN RICHIESTA_CORRENTE RC ON SR.clienteStorico = RC.clienteCorrente
			WHERE SR.clienteStorico = cliente_id;
		END IF;
	ELSE 
		SELECT SR.* 
		FROM STORICO_RICHIESTE
		WHERE SR.clienteStorico = cliente_id;
	END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `acquisti`
--

CREATE TABLE `acquisti` (
  `clienteAc` int NOT NULL,
  `articoloAc` int NOT NULL,
  `fornitoreAc` int NOT NULL,
  `quantita_acquistata` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `acquisti`
--

INSERT INTO `acquisti` (`clienteAc`, `articoloAc`, `fornitoreAc`, `quantita_acquistata`) VALUES
(1, 2, 1, 2),
(1, 4, 3, 5),
(4, 1, 4, 4),
(6, 3, 5, 10),
(9, 5, 2, 5),
(10, 4, 3, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `articolo`
--

CREATE TABLE `articolo` (
  `id_articolo` int NOT NULL,
  `fornitoreArt` int NOT NULL,
  `importo_netto` float DEFAULT NULL,
  `iva` float DEFAULT NULL,
  `importo_lordo` float DEFAULT NULL,
  `quantità_fornita` int DEFAULT NULL,
  `nome` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descrizione` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `immagine` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `articolo`
--

INSERT INTO `articolo` (`id_articolo`, `fornitoreArt`, `importo_netto`, `iva`, `importo_lordo`, `quantità_fornita`, `nome`, `descrizione`, `immagine`) VALUES
(1, 4, 12.5, 22, 161.96, 100, 'Caldaia Ecotec Pure a condensazione', 'VMW 246/7-2, Kw 24,0, Metano (convertibile ad Aria Propanata oppure a Propano) 13,5 L/min Cod. 0010019985 24,0 Kw; Ideale per riscaldare fino a 150 mq. con altezza media di circa 2,7 MT', '1_1.jpg'),
(2, 1, 132.75, 22, 161.96, 20, 'Caldaia Divatech', 'Divatech D LN a camera aperta 17,2 l./min Caldaia a Metano, convertibile a GPL.30.0 Kw; Ideale per riscaldare fino a 200 mq. con altezza media di circa 2,7 MT', '1_2.jpg'),
(3, 5, 55.32, 22, 67.5, 30, 'Caldaia Ciao Green a condensazione', 'Camera Stagna 25 Kw Metano Convertibile a GPL, 14 L/min 25 Kw; Ideale per riscaldare fino a 150 mq. con altezza media di circa 2,7 MT', '1_3.jpg'),
(4, 3, 412.5, 22, 503.25, 7, 'Condizionatore AR35 R32 inverter A++', '12.000 BTU; Ideale per ambienti da 25 a 40 mq con altezza media di 2,7 m', '2_1.jpg'),
(5, 2, 200, 22, 244, 13, 'Condizionatore Perfera Wall inverter A+++', '12.000 BTU; Ideale per ambienti da 25 a 40 mq con altezza media di 2,7 m, wi-fi integrato', '2_2.jpg');

-- --------------------------------------------------------

--
-- Struttura della tabella `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int NOT NULL,
  `telefono` char(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `indirizzo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `telefono`, `indirizzo`, `email`) VALUES
(1, '0931299591', 'Via Santa Lucia 2', NULL),
(2, '4260925757', 'Via P. Bronzetti 12', NULL),
(3, '2494346471', 'Via Zara 9', NULL),
(4, '1777474070', 'Via V. Polacco 4', NULL),
(5, '8593711444', 'Via Montà 32', NULL),
(6, '9732283817', 'Via del Cristo 10', NULL),
(7, '5997688800', 'Via Roma 23', NULL),
(8, '1856878707', 'Via Catena 11', NULL),
(9, '7319594476', 'Via C. Battisti 1', NULL),
(10, '0252735887', 'Via G.Garibaldi 29', NULL),
(11, '3466721063', 'Via Giotto 3, Enna, 94100', 'paolo.scarentino@gmail.com');

-- --------------------------------------------------------

--
-- Struttura della tabella `cliente_impresa`
--

CREATE TABLE `cliente_impresa` (
  `id_impresa` int NOT NULL,
  `p_ivac` char(11) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rag_socc` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `cliente_impresa`
--

INSERT INTO `cliente_impresa` (`id_impresa`, `p_ivac`, `rag_socc`) VALUES
(1, '68190730900', 'Sfrenal S.R.L'),
(3, '44978650768', 'Fratelli Rossi S.R.L'),
(6, '23767430863', 'Obiettivo meccanico S.R.L'),
(8, '23317360677', 'Barbiere Gianfranco S.R.L'),
(9, '79291870396', 'Supermercati Franco S.R.L');

-- --------------------------------------------------------

--
-- Struttura della tabella `cliente_privato`
--

CREATE TABLE `cliente_privato` (
  `id_cliente_privato` int NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cognome` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CF` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `cliente_privato`
--

INSERT INTO `cliente_privato` (`id_cliente_privato`, `nome`, `cognome`, `CF`, `username`, `password`) VALUES
(2, 'Eulalia', 'Trentino', 'SSCREO98R65A429H', NULL, NULL),
(3, 'Francesco', 'Beneventi', 'MHJSJC28P60C704I', NULL, NULL),
(5, 'Mario', 'Rossi', 'MSFHLR49R02D300P', NULL, NULL),
(7, 'Maria', 'Ferrari', 'MFPKGM66L58H281C', NULL, NULL),
(10, 'Alessandro', 'Lombardi', 'MVBGNV56L54F924E', NULL, NULL),
(11, 'Paolo', 'Scarentino', 'SCRPLA99L06C342E', 'Crowded', '$2y$10$U1d4WVEWlg.kxzEkX2nQae.jk5lmQsRA2YQ3LFURdIuxt/jhkPNqa');

-- --------------------------------------------------------

--
-- Struttura della tabella `dipendente`
--

CREATE TABLE `dipendente` (
  `id_dipendente` int NOT NULL,
  `stipendio` float DEFAULT NULL,
  `livello` int DEFAULT NULL,
  `data_nascita` date DEFAULT NULL,
  `nome` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cognome` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `interventoD` int DEFAULT NULL,
  `veicoloD` int DEFAULT NULL,
  `CF` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `dipendente`
--

INSERT INTO `dipendente` (`id_dipendente`, `stipendio`, `livello`, `data_nascita`, `nome`, `cognome`, `interventoD`, `veicoloD`, `CF`, `username`, `password`) VALUES
(1, 1000, 1, '1959-10-03', 'Giordano', 'Calabresi', 1, 2, 'GNRMQL63B68B653G', NULL, NULL),
(2, 1200, 2, '1977-05-22', 'Stefania', 'Fallaci', 2, 1, 'JKHDHF43T01G271N', NULL, NULL),
(3, 1400, 3, '1967-07-06', 'Emanuele', 'Trevisan', 1, 2, 'KTPJPF82C16C557Q', NULL, NULL),
(4, 1400, 3, '1978-12-31', 'Raniero', 'Greece', 3, 3, 'HMMVCP96H03H744U', NULL, NULL),
(5, 1200, 2, '1963-06-24', 'Iole', 'Fiorentino', 4, 5, 'KMLPFU75A60L889Z', NULL, NULL),
(6, 1200, 2, '1998-01-02', 'Franco', 'Fanucci', 5, 4, 'NTLVBW67E67G379Z', 'Crowded', 'salve');

-- --------------------------------------------------------

--
-- Struttura della tabella `esecuzioni`
--

CREATE TABLE `esecuzioni` (
  `dipendenteEs` int NOT NULL,
  `interventoEs` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `fornitore`
--

CREATE TABLE `fornitore` (
  `id_fornitore` int NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `p_iva` char(11) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rag_sociale` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `fornitore`
--

INSERT INTO `fornitore` (`id_fornitore`, `nome`, `p_iva`, `rag_sociale`) VALUES
(1, 'IdroTerm', '70609690014', 'IdroTerm S.R.L'),
(2, 'ElectroSil', '17620990568', 'ElectroSil S.R.L'),
(3, 'TermoInf', '48324220382', 'TermoInf S.R.L'),
(4, 'Fernol', '28001920645', 'Fernol S.R.L'),
(5, 'Marinox', '20037250147', 'Marinox S.R.L');

-- --------------------------------------------------------

--
-- Struttura della tabella `intervento`
--

CREATE TABLE `intervento` (
  `id_intervento` int NOT NULL,
  `costo` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `intervento`
--

INSERT INTO `intervento` (`id_intervento`, `costo`) VALUES
(1, 235),
(2, 313),
(3, 643),
(4, 1230),
(5, 70),
(6, 34),
(7, 211),
(8, 1500),
(9, 123),
(10, 2000),
(11, 1500),
(12, 512);

-- --------------------------------------------------------

--
-- Struttura della tabella `richiesta_corrente`
--

CREATE TABLE `richiesta_corrente` (
  `clienteCorrente` int NOT NULL,
  `interventoCorrente` int NOT NULL,
  `data_inizio_rc` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `richiesta_corrente`
--

INSERT INTO `richiesta_corrente` (`clienteCorrente`, `interventoCorrente`, `data_inizio_rc`) VALUES
(1, 10, '2020-12-25'),
(3, 4, '2020-11-30'),
(5, 6, '2020-09-09'),
(6, 8, '2020-10-15'),
(8, 9, '2020-08-31');

-- --------------------------------------------------------

--
-- Struttura della tabella `storico_richieste`
--

CREATE TABLE `storico_richieste` (
  `clienteStorico` int NOT NULL,
  `interventoStorico` int NOT NULL,
  `data_inizio` date DEFAULT NULL,
  `data_fine` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `storico_richieste`
--

INSERT INTO `storico_richieste` (`clienteStorico`, `interventoStorico`, `data_inizio`, `data_fine`) VALUES
(2, 1, '2020-05-06', '2020-05-20'),
(2, 12, '2019-12-11', '2020-01-11'),
(3, 11, '2019-04-03', '2019-05-01'),
(4, 3, '2019-10-21', '2019-11-06'),
(7, 5, '2018-06-15', '2018-06-16'),
(9, 7, '2020-03-24', '2020-03-26'),
(10, 2, '2018-11-01', '2019-01-05');

-- --------------------------------------------------------

--
-- Struttura della tabella `utilizzo`
--

CREATE TABLE `utilizzo` (
  `interventoU` int NOT NULL,
  `articoloU` int NOT NULL,
  `quantità` int DEFAULT NULL,
  `fornitoreU` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utilizzo`
--

INSERT INTO `utilizzo` (`interventoU`, `articoloU`, `quantità`, `fornitoreU`) VALUES
(1, 4, 2, 1),
(2, 2, 1, 3),
(3, 5, 1, 5),
(4, 3, 1, 4),
(5, 1, 2, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `veicolo`
--

CREATE TABLE `veicolo` (
  `id_veicolo` int NOT NULL,
  `targa` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `modello` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `marca` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `veicolo`
--

INSERT INTO `veicolo` (`id_veicolo`, `targa`, `modello`, `marca`) VALUES
(1, 'DS453AS', 'Fiorino', 'Fiat'),
(2, 'QW321FD', 'Panda', 'Fiat'),
(3, 'RI392DS', 'Panda', 'Fiat'),
(4, 'BD043XS', 'Partner', 'Peugeot'),
(5, 'EF282AS', 'Megane', 'Renault');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `acquisti`
--
ALTER TABLE `acquisti`
  ADD PRIMARY KEY (`clienteAc`,`articoloAc`,`fornitoreAc`),
  ADD KEY `idx_clienteAc` (`clienteAc`),
  ADD KEY `idx_articoloAc` (`articoloAc`),
  ADD KEY `idx_fornitoreAc` (`fornitoreAc`);

--
-- Indici per le tabelle `articolo`
--
ALTER TABLE `articolo`
  ADD PRIMARY KEY (`id_articolo`,`fornitoreArt`),
  ADD KEY `idx_fornitore` (`fornitoreArt`);

--
-- Indici per le tabelle `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indici per le tabelle `cliente_impresa`
--
ALTER TABLE `cliente_impresa`
  ADD PRIMARY KEY (`id_impresa`),
  ADD KEY `idx_id_impresa` (`id_impresa`);

--
-- Indici per le tabelle `cliente_privato`
--
ALTER TABLE `cliente_privato`
  ADD PRIMARY KEY (`id_cliente_privato`),
  ADD KEY `idx_id_cliente` (`id_cliente_privato`);

--
-- Indici per le tabelle `dipendente`
--
ALTER TABLE `dipendente`
  ADD PRIMARY KEY (`id_dipendente`),
  ADD UNIQUE KEY `CF` (`CF`),
  ADD KEY `idx_inter` (`interventoD`),
  ADD KEY `idx_veicolo` (`veicoloD`);

--
-- Indici per le tabelle `esecuzioni`
--
ALTER TABLE `esecuzioni`
  ADD PRIMARY KEY (`dipendenteEs`,`interventoEs`),
  ADD KEY `idx_dipendenteEs` (`dipendenteEs`),
  ADD KEY `idx_interventoEs` (`interventoEs`);

--
-- Indici per le tabelle `fornitore`
--
ALTER TABLE `fornitore`
  ADD PRIMARY KEY (`id_fornitore`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indici per le tabelle `intervento`
--
ALTER TABLE `intervento`
  ADD PRIMARY KEY (`id_intervento`);

--
-- Indici per le tabelle `richiesta_corrente`
--
ALTER TABLE `richiesta_corrente`
  ADD PRIMARY KEY (`clienteCorrente`,`interventoCorrente`),
  ADD KEY `idx_clienteCorrente` (`clienteCorrente`),
  ADD KEY `idx_interventoCorrente` (`interventoCorrente`);

--
-- Indici per le tabelle `storico_richieste`
--
ALTER TABLE `storico_richieste`
  ADD PRIMARY KEY (`clienteStorico`,`interventoStorico`),
  ADD KEY `idx_clienteStorico` (`clienteStorico`),
  ADD KEY `idx_interventoStirico` (`interventoStorico`);

--
-- Indici per le tabelle `utilizzo`
--
ALTER TABLE `utilizzo`
  ADD PRIMARY KEY (`interventoU`,`articoloU`,`fornitoreU`),
  ADD KEY `idx_intervento` (`interventoU`),
  ADD KEY `idx_articolo` (`articoloU`),
  ADD KEY `idx_fornitoreu` (`fornitoreU`);

--
-- Indici per le tabelle `veicolo`
--
ALTER TABLE `veicolo`
  ADD PRIMARY KEY (`id_veicolo`),
  ADD UNIQUE KEY `targa` (`targa`);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `acquisti`
--
ALTER TABLE `acquisti`
  ADD CONSTRAINT `acquisti_ibfk_1` FOREIGN KEY (`clienteAc`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `acquisti_ibfk_2` FOREIGN KEY (`articoloAc`) REFERENCES `articolo` (`id_articolo`),
  ADD CONSTRAINT `acquisti_ibfk_3` FOREIGN KEY (`fornitoreAc`) REFERENCES `articolo` (`fornitoreArt`);

--
-- Limiti per la tabella `articolo`
--
ALTER TABLE `articolo`
  ADD CONSTRAINT `articolo_ibfk_1` FOREIGN KEY (`fornitoreArt`) REFERENCES `fornitore` (`id_fornitore`);

--
-- Limiti per la tabella `cliente_impresa`
--
ALTER TABLE `cliente_impresa`
  ADD CONSTRAINT `cliente_impresa_ibfk_1` FOREIGN KEY (`id_impresa`) REFERENCES `cliente` (`id_cliente`) ON DELETE CASCADE;

--
-- Limiti per la tabella `cliente_privato`
--
ALTER TABLE `cliente_privato`
  ADD CONSTRAINT `cliente_privato_ibfk_1` FOREIGN KEY (`id_cliente_privato`) REFERENCES `cliente` (`id_cliente`) ON DELETE CASCADE;

--
-- Limiti per la tabella `dipendente`
--
ALTER TABLE `dipendente`
  ADD CONSTRAINT `dipendente_ibfk_1` FOREIGN KEY (`interventoD`) REFERENCES `intervento` (`id_intervento`),
  ADD CONSTRAINT `dipendente_ibfk_2` FOREIGN KEY (`veicoloD`) REFERENCES `veicolo` (`id_veicolo`);

--
-- Limiti per la tabella `esecuzioni`
--
ALTER TABLE `esecuzioni`
  ADD CONSTRAINT `esecuzioni_ibfk_1` FOREIGN KEY (`dipendenteEs`) REFERENCES `dipendente` (`id_dipendente`),
  ADD CONSTRAINT `esecuzioni_ibfk_2` FOREIGN KEY (`interventoEs`) REFERENCES `intervento` (`id_intervento`);

--
-- Limiti per la tabella `richiesta_corrente`
--
ALTER TABLE `richiesta_corrente`
  ADD CONSTRAINT `richiesta_corrente_ibfk_1` FOREIGN KEY (`clienteCorrente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `richiesta_corrente_ibfk_2` FOREIGN KEY (`interventoCorrente`) REFERENCES `intervento` (`id_intervento`);

--
-- Limiti per la tabella `storico_richieste`
--
ALTER TABLE `storico_richieste`
  ADD CONSTRAINT `storico_richieste_ibfk_1` FOREIGN KEY (`clienteStorico`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `storico_richieste_ibfk_2` FOREIGN KEY (`interventoStorico`) REFERENCES `intervento` (`id_intervento`);

--
-- Limiti per la tabella `utilizzo`
--
ALTER TABLE `utilizzo`
  ADD CONSTRAINT `utilizzo_ibfk_1` FOREIGN KEY (`interventoU`) REFERENCES `intervento` (`id_intervento`),
  ADD CONSTRAINT `utilizzo_ibfk_2` FOREIGN KEY (`articoloU`) REFERENCES `articolo` (`id_articolo`),
  ADD CONSTRAINT `utilizzo_ibfk_3` FOREIGN KEY (`fornitoreU`) REFERENCES `fornitore` (`id_fornitore`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
