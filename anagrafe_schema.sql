-- Elimino tutte le tabelle se esistono
DROP TABLE IF EXISTS Proprietario CASCADE;
DROP TABLE IF EXISTS Deterrente CASCADE;
DROP TABLE IF EXISTS ASL CASCADE;
DROP TABLE IF EXISTS Trasportatore CASCADE;
DROP TABLE IF EXISTS Motivo CASCADE;
DROP TABLE IF EXISTS Razza CASCADE;
DROP TABLE IF EXISTS Ditta CASCADE;
DROP TABLE IF EXISTS Capo CASCADE;
DROP TABLE IF EXISTS Alleva CASCADE;
DROP TABLE IF EXISTS Farmacia CASCADE;
DROP TABLE IF EXISTS Prescrizione CASCADE;
DROP TABLE IF EXISTS Farmaco CASCADE;
DROP TABLE IF EXISTS Trattamento CASCADE;
DROP TABLE IF EXISTS Scorte CASCADE;
DROP TABLE IF EXISTS Cura CASCADE;

-- tipo per specificare il sesso del capo
CREATE TYPE SEX AS ENUM (
	'M',
	'F'
);

-- tipo per specificare il tipo di capo
CREATE TYPE TIPO AS ENUM (
	'Bovino',
	'Suino',
	'Caprino'
);

-- tipo per specificare la via di somministrazione del trattamento nel capo
CREATE TYPE VIASOMMINISTRAZIONE AS ENUM (
	'Muscolare',
	'Intramuscolare',
	'Endovenosa'
);

CREATE TABLE Proprietario (
	id smallserial NOT NULL,
	cf varchar(16),
	pi varchar(20),
	nome varchar(50) NOT NULL,
	indirizzo varchar(100) NOT NULL,
	localita varchar(50) NOT NULL,
	provincia varchar(30) NOT NULL,
	CONSTRAINT "Proprietario_PK" PRIMARY KEY (id)
);

CREATE TABLE ASL (
	numero smallint NOT NULL,
	nome varchar(50) NOT NULL,
	indirizzo varchar(100) NOT NULL,
	regione varchar(20) NOT NULL,
	CONSTRAINT "ASL_PK" PRIMARY KEY (numero)
);

CREATE TABLE Trasportatore (
	id smallserial NOT NULL,
	cf varchar(16),
	pi varchar(20),
	nome varchar(50) NOT NULL,
	indirizzo varchar(100) NOT NULL,
	localita varchar(50) NOT NULL,
	provincia varchar(30) NOT NULL,
	targa varchar(7) NOT NULL,
	mezzo_trasporto varchar(20) NOT NULL,
	CONSTRAINT "Trasportatore_PK" PRIMARY KEY (id)
);

CREATE TABLE Motivo (
	codice smallint NOT NULL,
	descrizione text NOT NULL,
	CONSTRAINT "Motivo_PK" PRIMARY KEY (codice),
	CONSTRAINT "CodiceMotivoNotNegative" CHECK (codice > 0)
);

CREATE TABLE Razza (
	codice varchar(10) NOT NULL,
	nome varchar(30) NOT NULL,
	sesso SEX NOT NULL,
	peso_vendita real NOT NULL,
	prezzo_vendita real NOT NULL,
	CONSTRAINT "Razza_PK" PRIMARY KEY (codice),
	CONSTRAINT "PesoVenditaNotNegative" CHECK (peso_vendita > 0),
	CONSTRAINT "PrezzoVenditaNotNegative" CHECK (prezzo_vendita > 0)
);

CREATE TABLE Ditta (
	id smallserial NOT NULL,
	cod_az varchar(10) NOT NULL,
	nome_ditta varchar(50) NOT NULL,
	indirizzo varchar(100) NOT NULL,
	localita varchar(50) NOT NULL,
	provincia varchar(30) NOT NULL,
	scorte boolean NOT NULL,
	deterrente smallint NOT NULL,
	proprietario smallint NOT NULL,
	asl smallint NOT NULL,
	anno_attivazione smallint NOT NULL,
	UNIQUE (cod_az, deterrente, proprietario),
	CONSTRAINT "Ditta_PK" PRIMARY KEY (id),
	CONSTRAINT "Proprietario_FK" FOREIGN KEY (proprietario) REFERENCES Proprietario(id)
		ON UPDATE CASCADE ON DELETE SET NULL,
	CONSTRAINT "Deterrente_FK" FOREIGN KEY (deterrente) REFERENCES Proprietario(id)
		ON UPDATE CASCADE ON DELETE SET NULL,
	CONSTRAINT "Asl_FK" FOREIGN KEY (asl) REFERENCES ASL(numero)
		ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE Capo (
	marca varchar(20) NOT NULL,
	sesso SEX NOT NULL,
	tipo TIPO NOT NULL,
	data_nascita date NOT NULL,
	madre varchar(20),
	razza varchar(10) NOT NULL,
	cert_sanitario varchar(20) NOT NULL,
	CONSTRAINT "Capo_PK" PRIMARY KEY (marca),
	CONSTRAINT "Madre_FK" FOREIGN KEY (madre) REFERENCES Capo(marca)
		ON UPDATE CASCADE ON DELETE SET NULL,
	CONSTRAINT "Razza_FK" FOREIGN KEY (razza) REFERENCES Razza(codice)
		ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE Alleva (
	id serial NOT NULL,
	motivo_ingresso smallint NOT NULL,
	data_ingresso date NOT NULL,
	ditta_ingresso smallint NOT NULL,
	trasportatore smallint NOT NULL,
	capo varchar(20) NOT NULL,
	ditta_uscita smallint,
	data_uscita date,
	motivo_uscita smallint,
	CONSTRAINT "Alleva_PK" PRIMARY KEY(id),
	CONSTRAINT "Motivo_ingresso_FK" FOREIGN KEY(motivo_ingresso) REFERENCES Motivo(codice)
		ON UPDATE CASCADE ON DELETE SET NULL,
	CONSTRAINT "Motivo_uscita_FK" FOREIGN KEY(motivo_uscita) REFERENCES Motivo(codice)
		ON UPDATE CASCADE ON DELETE SET NULL,
	CONSTRAINT "Ditta_ingresso_FK" FOREIGN KEY(ditta_ingresso) REFERENCES Ditta(id)
		ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "Ditta_uscita_FK" FOREIGN KEY(ditta_uscita) REFERENCES Ditta(id)
		ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "Trasportatore_FK" FOREIGN KEY(trasportatore) REFERENCES Trasportatore(id)
		ON UPDATE CASCADE ON DELETE SET NULL,
	CONSTRAINT "Capo_FK" FOREIGN KEY(capo) REFERENCES Capo(marca)
		ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "DataAllevamento" CHECK (data_ingresso > data_uscita),
	CONSTRAINT "DittaTrasferimento" CHECK (ditta_ingresso != ditta_uscita)
);

CREATE TABLE Farmaco (
	id smallserial NOT NULL,
	nome varchar(20) NOT NULL,
	confezione smallint NOT NULL,
	unita_misura varchar(5) NOT NULL,
	dose smallint NOT NULL,
	principio varchar(20) NOT NULL,
	fabbrica varchar(20) NOT NULL,
	UNIQUE (nome, confezione, unita_misura, dose, principio, fabbrica),
	CONSTRAINT "Farmaco_PK" PRIMARY KEY (id),
	CONSTRAINT "ConfezioneNotNegative" CHECK (confezione > 0),
	CONSTRAINT "DoseNotNegative" CHECK (dose > 0)
);

CREATE TABLE Farmacia (
	nome varchar(30) NOT NULL,
	indirizzo varchar(100) NOT NULL,
	CONSTRAINT "Farmacia_PK" PRIMARY KEY (nome)
);

CREATE TABLE Prescrizione (
	id smallserial NOT NULL,
	data date NOT NULL,
	ditta smallint NOT NULL,
	farmacia varchar(30),
	CONSTRAINT "Prescrizione_PK" PRIMARY KEY (id),
	CONSTRAINT "Ditta_FK" FOREIGN KEY (ditta) REFERENCES Ditta(id)
		ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "Farmacia_FK" FOREIGN KEY (farmacia) REFERENCES Farmacia(nome)
		ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE Trattamento (
	id serial NOT NULL,
	gg_sospensione smallint NOT NULL,
	n_volte smallint NOT NULL,
	dose real NOT NULL,
	via_somministrazione VIASOMMINISTRAZIONE NOT NULL,
	data_inizio date NOT NULL,
	data_fine date NOT NULL,
	farmaco smallint NOT NULL,
	qta_farmaco smallint NOT NULL,
	prescrizione smallint NOT NULL,
	motivo smallint NOT NULL,
	CONSTRAINT "Trattamento_PK" PRIMARY KEY (id),
	CONSTRAINT "Farmaco_FK" FOREIGN KEY (farmaco) REFERENCES Farmaco(id)
		ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "Prescrizione_FK" FOREIGN KEY (prescrizione) REFERENCES Prescrizione(id)
		ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "Motivo_FK" FOREIGN KEY (motivo) REFERENCES Motivo(codice)
		ON UPDATE CASCADE ON DELETE SET NULL,
	CONSTRAINT "DataTrattamento" CHECK (data_inizio <= data_fine),
	CONSTRAINT "GiorniSospensioneNotNegative" CHECK (gg_sospensione > 0),
	CONSTRAINT "NVolteNotNegative" CHECK (n_volte > 0),
	CONSTRAINT "DoseNotNegative" CHECK (dose > 0),
	CONSTRAINT "QuantitaFarmacoNotNegative" CHECK (qta_farmaco > 0)
);

CREATE TABLE Cura (
	capo varchar(20) NOT NULL,
	trattamento serial NOT NULL,
	CONSTRAINT "Cura_PK" PRIMARY KEY (capo, trattamento),
	CONSTRAINT "Capo_FK" FOREIGN KEY (capo) REFERENCES Capo(marca)
		ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "Trattamento_FK" FOREIGN KEY (trattamento) REFERENCES Trattamento(id)
		ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE Scorte (
	ditta smallint NOT NULL,
	farmaco smallint NOT NULL,
	qta smallint NOT NULL,
	CONSTRAINT "Scorte_PK" PRIMARY KEY (ditta, farmaco),
	CONSTRAINT "Farmaco_FK" FOREIGN KEY (farmaco) REFERENCES Farmaco(id)
		ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "Ditta_FK" FOREIGN KEY (ditta) REFERENCES Ditta(id)
		ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "QuantitaNotNegative" CHECK (qta > 0)
);


-- Assegno i permessi alle tabelle
GRANT SELECT, UPDATE, INSERT, DELETE ON Proprietario TO webdb;
GRANT SELECT, UPDATE, INSERT, DELETE ON Asl TO webdb;
GRANT SELECT, UPDATE, INSERT, DELETE ON Trasportatore TO webdb;
GRANT SELECT, UPDATE, INSERT, DELETE ON Motivo TO webdb;
GRANT SELECT, UPDATE, INSERT, DELETE ON Razza TO webdb;
GRANT SELECT, UPDATE, INSERT, DELETE ON Ditta TO webdb;
GRANT SELECT, UPDATE, INSERT, DELETE ON Capo TO webdb;
GRANT SELECT, UPDATE, INSERT, DELETE ON Alleva TO webdb;
GRANT SELECT, UPDATE, INSERT, DELETE ON Farmaco TO webdb;
GRANT SELECT, UPDATE, INSERT, DELETE ON Farmacia TO webdb;
GRANT SELECT, UPDATE, INSERT, DELETE ON Cura TO webdb;
GRANT SELECT, UPDATE, INSERT, DELETE ON Trattamento TO webdb;
GRANT SELECT, UPDATE, INSERT, DELETE ON Prescrizione TO webdb;
GRANT SELECT, UPDATE, INSERT, DELETE ON Scorte TO webdb;
GRANT SELECT ON ditta_id_seq TO webdb;
GRANT SELECT, USAGE ON alleva_id_seq TO webdb;
