-- Popolo la tabella ASL
INSERT INTO ASL VALUES (16, 'ULSS Padova', 'via Scrovegni, 12 - 35131 - Padova', 'VENETO');
INSERT INTO ASL VALUES (18, 'ULSS Rovigo', 'Ospedale "Santa Maria della Misericordia" V.le Tre Martiri, 140 - 45100 Rovigo Ingresso SUD', 'VENETO');
INSERT INTO ASL VALUES (20, 'ULSS Treviso', 'Piazzale dell''Ospedale, 1 - 31100 Treviso ', 'VENETO');
INSERT INTO ASL VALUES (17, 'Azienda Ulss 17', 'via G. Marconi 19, 35043 Monselice (Padova)', 'VENETO');
INSERT INTO ASL VALUES (14, 'Azienda ULSS 14 Chioggia', 'Strada Madonna Marina, 500 - 30015 Sottomarina di Chioggia (VE)', 'VENETO');

-- Popolo la tabella Proprietario
INSERT INTO Proprietario VALUES (default, '', '252760285', 'STURARO GIUSEPPE E F.LLI S.S.', 'VIA CONAPADOVANA N.31', 'AGNA', 'PD');
INSERT INTO Proprietario VALUES (default, 'STRLCU68A08G693Z', '1256080381', 'STURARO LUCA', 'VIA CANAL BIANCO N.220', 'SERRAVALLE BERRA', 'FE');
INSERT INTO Proprietario VALUES (default, 'STRSLV71P51C964Q', '1055490294', 'STURARO SILVIA', 'VIA NUOVA N.1421', 'CORBOLA', 'RO');
INSERT INTO Proprietario VALUES (default, '', '1279970295', 'AGRESTE SOC. COOP. AGRICOLA', 'Via G. Matteotti 129', 'VILLAMARZANA', 'RO');
INSERT INTO Proprietario VALUES (default, '', '3480150287', 'BALDON BENEDETTO E FIGLI SS', 'Via Navegauro 54', 'Candiana', 'PD');
INSERT INTO Proprietario VALUES (default, 'BTTPLA59M25C938W', '2792670271', 'BATTISTINI PAOLO', 'VIA CORDENAZZETTI 8', 'CONA', 'VE');
INSERT INTO Proprietario VALUES (default, 'STRNTN67C17C964G', '', 'STURARO ANTONIO', 'VIA MALIPIERA 3', 'AGNA', 'PD');

-- Popolo la tabella Trasportatore
INSERT INTO Trasportatore VALUES (default, '', '', 'INDIAL SRL', 'VIA F.CAVALLOTTI, 282', 'MONTICHIARI', 'BS', 'AP231NY', 'AUTOMEZZO');
INSERT INTO Trasportatore VALUES (default, '', '23198732189', 'BACCO SRL', 'STRADA dei VIVAI, 100', 'Padova', 'PD', 'BF469RO', 'CAMION');

-- Popolo la tabella Razza
INSERT INTO Razza VALUES ('AAGf', 'ANGUS', 'F', 2, 480);
INSERT INTO Razza VALUES ('AAGm', 'ANGUS', 'M', 2, 600);
INSERT INTO Razza VALUES ('LMSf', 'LIMOUSINE', 'F', 2.17, 500);
INSERT INTO Razza VALUES ('LMSm', 'LIMOUSINE', 'M', 2.17, 600);
INSERT INTO Razza VALUES ('MTTf', 'INCROCIO', 'F', 2, 550);
INSERT INTO Razza VALUES ('MTTm', 'INCROCIO', 'M', 2, 650);
INSERT INTO Razza VALUES ('SIMf', 'SIMMENTAL', 'F', 1.8, 480);
INSERT INTO Razza VALUES ('SIMm', 'SIMMENTAL', 'M', 1.8, 650);

-- Popolo tabella Motivo
INSERT INTO Motivo VALUES (1, 'NASCITA');
INSERT INTO Motivo VALUES (2, 'COMPRAVENDITA ENTRATA');
INSERT INTO Motivo VALUES (13, 'PRIMA IMPORTAZIONE');
INSERT INTO Motivo VALUES (23, 'RIENTRO DA PAESE ESTERO');
INSERT INTO Motivo VALUES (3, 'COMPRAVENDITA USCITA');
INSERT INTO Motivo VALUES (4, 'MORTE');
INSERT INTO Motivo VALUES (6, 'FURTO');
INSERT INTO Motivo VALUES (9, 'MACELLAZIONE');
INSERT INTO Motivo VALUES (10, 'MALATTIA');

-- Popolo tabella Farmacia
INSERT INTO Farmacia VALUES ('FARMACIA GHEZZO', 'VIA ROMA 100, CONA \\(VE\\)');
INSERT INTO Farmacia VALUES ('VENETA ZOOTECNICI', 'VIA ALBARE, PIOMBINO DESE \\(PD\\)');

-- Popolo la tabella Ditta
INSERT INTO Ditta VALUES (default, '021PD003', 'BALDON BENEDETTO E FIGLI SS', 'Via Navegauro 54', 'Candiana', 'PD', TRUE, 1, 4, 17, 2008);
INSERT INTO Ditta VALUES (default, '002PD006', 'STURARO ANTONIO', 'VIA MALIPIERA 3', 'AGNA', 'PD', FALSE, 4, 1, 17, 2007);

-- Popolo tabella Farmaco
INSERT INTO Farmaco VALUES (default, 'AAGENT 10%', 500, 'ML', 3, 'HENTAMICINA', 'FATRO');
INSERT INTO Farmaco VALUES (default, 'BAYOVAC A.B.1-10', 10, 'DS', 1, 'VAC. BRS', 'BAYER');
INSERT INTO Farmaco VALUES (default, 'BAYOVAC A.B.1-50', 50, 'DS', 1, 'VAC. BRS', 'BAYER');
INSERT INTO Farmaco VALUES (default, 'MULTIBIO', 250, 'ML', 10, 'AMOXICILLINA-COLISTI', 'VIRBAC');
INSERT INTO Farmaco VALUES (default, 'SPIRAMIN      100 ML', 100, 'ML', 10, 'SPIRAMICINA', 'MERIAL');
INSERT INTO Farmaco VALUES (default, 'SPIRAMICINA', 50, 'ML', 2, 'TULATROMICINA', 'PFITZER');

-- Popolo tabella Scorte
INSERT INTO Scorte VALUES (1, 3, 10);
INSERT INTO Scorte VALUES (1, 2, 4);
INSERT INTO Scorte VALUES (1, 5, 1);
INSERT INTO Scorte VALUES (1, 4, 3);
INSERT INTO Scorte VALUES (1, 1, 20);
INSERT INTO Scorte VALUES (2, 3, 10);
INSERT INTO Scorte VALUES (2, 4, 14);
INSERT INTO Scorte VALUES (2, 5, 4);
INSERT INTO Scorte VALUES (2, 6, 10);
INSERT INTO Scorte VALUES (2, 1, 5);


-- Popolo la tabella Capi
INSERT INTO Capo VALUES ('PL005123653782', 'M', 'Bovino', '2006-08-20', NULL, 'AAGm', 'PL143117/06/158');
INSERT INTO Capo VALUES ('PL005124996482', 'M', 'Bovino', '2006-08-08', NULL, 'AAGm', 'PL143117/06/158');
INSERT INTO Capo VALUES ('PL005126103147', 'M', 'Bovino', '2006-08-17', NULL, 'AAGm', 'PL143117/06/158');
INSERT INTO Capo VALUES ('PL005151046853', 'M', 'Bovino', '2006-08-14', NULL, 'AAGm', 'PL143117/06/158');
INSERT INTO Capo VALUES ('PL005151938370', 'M', 'Bovino', '2006-08-09', NULL, 'AAGm', 'PL143117/06/158');
INSERT INTO Capo VALUES ('PL005156520372', 'M', 'Bovino', '2006-08-09', NULL, 'AAGm', 'PL143117/06/158');
INSERT INTO Capo VALUES ('PL005112281880', 'M', 'Bovino', '2006-08-20', NULL, 'AAGm', 'PL143117/06/286');
INSERT INTO Capo VALUES ('PL005113153017', 'M', 'Bovino', '2006-08-04', 'PL005145099308', 'MTTm', 'PL143117/06/286');
INSERT INTO Capo VALUES ('PL005113693101', 'M', 'Bovino', '2006-08-15', 'PL005145099308', 'MTTm', 'PL143117/06/286');
INSERT INTO Capo VALUES ('PL005129642230', 'M', 'Bovino', '2006-08-05', 'PL005146061045', 'SIMm', 'PL143117/06/286');
INSERT INTO Capo VALUES ('PL005129917482', 'M', 'Bovino', '2006-07-30', NULL, 'AAGm', 'PL143117/06/286');
INSERT INTO Capo VALUES ('PL005147625574', 'M', 'Bovino', '2006-08-21', NULL, 'AAGm', 'PL143117/06/286');
INSERT INTO Capo VALUES ('PL005145099308', 'F', 'Bovino', '2004-08-20', NULL, 'MTTf', 'PL143117/06/286');
INSERT INTO Capo VALUES ('PL005145273357', 'F', 'Bovino', '2004-01-09', NULL, 'SIMf', 'PL143117/06/286');
INSERT INTO Capo VALUES ('PL005146061045', 'F', 'Bovino', '2004-08-15', NULL, 'SIMf', 'PL143117/06/286');

-- Popolo la tabella Alleva
INSERT INTO Alleva VALUES (default, 1, '2014-3-20', 1, 1, 'PL005123653782', NULL, NULL, NULL);
INSERT INTO Alleva VALUES (default, 1, '2012-4-30', 1, 1, 'PL005124996482', NULL, NULL, NULL);
INSERT INTO Alleva VALUES (default, 13, '2011-5-27', 1, 1, 'PL005126103147', NULL, NULL, NULL);
INSERT INTO Alleva VALUES (default, 13, '2012-7-29', 1, 1, 'PL005151046853', NULL, NULL, NULL);
INSERT INTO Alleva VALUES (default, 13, '2014-3-13', 1, 1, 'PL005151938370', NULL, NULL, NULL);
INSERT INTO Alleva VALUES (default, 23, '2013-12-09', 2, 1, 'PL005156520372', NULL, NULL, NULL);
INSERT INTO Alleva VALUES (default, 1, '2014-1-20', 2, 1, 'PL005112281880', NULL, NULL, NULL);
INSERT INTO Alleva VALUES (default, 13, '2014-1-04', 2, 1, 'PL005113153017', NULL, NULL, NULL);
INSERT INTO Alleva VALUES (default, 13, '2014-1-02', 2, 1, 'PL005113693101', NULL, NULL, NULL);
INSERT INTO Alleva VALUES (default, 13, '2014-3-20', 2, 1, 'PL005129642230', NULL, NULL, NULL);
INSERT INTO Alleva VALUES (default, 13, '2014-3-20', 1, 1, 'PL005129917482', NULL, NULL, NULL);
INSERT INTO Alleva VALUES (default, 13, '2012-4-30', 1, 1, 'PL005147625574', NULL, NULL, NULL);
INSERT INTO Alleva VALUES (default, 13, '2011-5-27', 1, 1, 'PL005145099308', NULL, NULL, NULL);
INSERT INTO Alleva VALUES (default, 13, '2012-7-29', 1, 1, 'PL005145273357', NULL, NULL, NULL);
INSERT INTO Alleva VALUES (default, 13, '2013-8-05', 1, 1, 'PL005146061045', NULL, NULL, NULL);

-- Popolo la tabella Prescrizione
INSERT INTO Prescrizione VALUES (default, '2014-5-10', 1, NULL);
INSERT INTO Prescrizione VALUES (default, '2014-4-7', 1, NULL);
INSERT INTO Prescrizione VALUES (default, '2014-5-28', 2, NULL);

-- Popolo la tabella Trattamento
INSERT INTO Trattamento VALUES (default, 3, 2, 1.5, 'Muscolare', '2014-05-10', '2014-05-30', 3, 1, 1, 10);
INSERT INTO Trattamento VALUES (default, 4, 11, 1, 'Endovenosa', '2014-4-7', '2014-5-7', 2, 4, 1, 10);
INSERT INTO Trattamento VALUES (default, 7, 1, 2, 'Endovenosa', '2014-5-28', '2014-6-11', 1, 1, 3, 10);

-- Popolo la tabella Cura
INSERT INTO Cura VALUES ('PL005123653782', 1);
INSERT INTO Cura VALUES ('PL005124996482', 1);
INSERT INTO Cura VALUES ('PL005126103147', 1);
INSERT INTO Cura VALUES ('PL005151046853', 1);
INSERT INTO Cura VALUES ('PL005151046853', 2);
INSERT INTO Cura VALUES ('PL005156520372', 4);
INSERT INTO Cura VALUES ('PL005129642230', 4);
INSERT INTO Cura VALUES ('PL005113693101', 4);
INSERT INTO Cura VALUES ('PL005113153017', 4);
INSERT INTO Cura VALUES ('PL005112281880', 4);
