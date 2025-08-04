# Piano di Sviluppo DAIMON Hub

## Stato Attuale
- [x] Struttura base del progetto
- [x] Sistema di routing e template di base
- [x] Documentazione iniziale
- [ ] Sistema di migrazione (in corso, da risolvere errori)
- [ ] Sistema di debug
- [ ] Layer Database

## Priorità Immediate

### 1. Miglioramento del Sistema di Template
**Obiettivo**: Migliorare il sistema di template esistente mantenendolo semplice ma più robusto.

**Miglioramenti proposti**:
- [ ] Aggiungere l'ereditarietà dei template (layout base)
- [ ] Implementare l'escape automatico dell'output per prevenire XSS
- [ ] Aggiungere helper per i form e gli URL
- [ ] Creare un sistema di sezioni per i blocchi di contenuto
- [ ] Aggiungere il supporto ai template parziali

### 2. Implementazione di un Sistema di Routing Avanzato
**Obiettivo**: Sostituire il sistema di routing attuale con uno più robusto e sicuro.

**Caratteristiche chiave**:
- [ ] Supporto per parametri nelle rotte (es. `/users/:id`)
- [ ] Supporto per i metodi HTTP (GET, POST, PUT, DELETE)
- [ ] Middleware per la validazione e l'autenticazione
- [ ] Gruppi di rotte per organizzare il codice
- [ ] Nomi delle rotte per generare URL in modo sicuro

### 3. Sistema di Configurazione e Database
**Obiettivo**: Creare un sistema di configurazione flessibile e una tabella per le impostazioni.

**Stato Attuale**:
- [x] Creata la tabella `config` nel database
- [x] Implementata la classe Config per la gestione delle impostazioni
- [x] Aggiunto supporto alla cache per le impostazioni
- [ ] Creare un'interfaccia di amministrazione per modificare le impostazioni

**Problemi Aperti**:
- Il sistema di migrazione non trova la classe MigrationManager
- Necessario verificare l'autoloader e i namespace

## Prossimi Passi

### 1. Migrazione Modulo Genie
**Obiettivo**: Migrare la funzionalità esistente da genie.faroteatrale.it al nuovo progetto seguendo le linee guida.

**Task**:
- [ ] Analisi del codice sorgente esistente in `../genie.faroteatrale.it`
- [ ] Identificazione dei componenti principali da migrare
- [ ] Progettazione dell'architettura per l'integrazione
- [ ] Migrazione dei modelli di dati
- [ ] Migrazione delle viste e dei template
- [ ] Integrazione con il sistema di autenticazione esistente
- [ ] Test di regressione

### 2. Layer Database
**Obiettivo**: Implementare un sistema di accesso ai dati robusto, flessibile e performante.

**Task**:
- [ ] Creare la classe Database base
  - [ ] Gestione connessioni PDO
  - [ ] Prepared statements
  - [ ] Gestione errori e eccezioni
  - [ ] Logging delle query
- [ ] Implementare il query builder
  - [ ] Metodi per SELECT, INSERT, UPDATE, DELETE
  - [ ] Clausole WHERE, JOIN, GROUP BY, HAVING
  - [ ] Supporto per subquery
- [ ] Aggiungere supporto per le transazioni
  - [ ] Inizio/commit/rollback
  - [ ] Gestione dei deadlock
  - [ ] Transazioni annidate
- [ ] Implementare il pattern Repository
  - [ ] Interfaccia base Repository
  - [ ] Repository concreti per entità principali
  - [ ] Caching a livello di repository
- [ ] Aggiungere sistema di migrazioni
  - [ ] Struttura per le migrazioni
  - [ ] Comandi CLI per applicare/annullare migrazioni
  - [ ] Storicizzazione delle migrazioni applicate

### 3. Sistema di Debug
**Obiettivo**: Implementare un sistema di debug integrato che possa essere attivato/disattivato dalle impostazioni.

**Task**:
- [ ] Creare il pannello di debug
  - [ ] Interfaccia utente fluttuante/ancorata
  - [ ] Pulsante di attivazione/disattivazione
  - [ ] Layout responsive
- [ ] Funzionalità di logging
  - [ ] Intercettazione log di sistema
  - [ ] Filtri per livello di log
  - [ ] Ricerca e filtraggio log
- [ ] Informazioni di sistema
  - [ ] Tempo di esecuzione
  - [ ] Utilizzo memoria
  - [ ] Query eseguite e loro durata
  - [ ] Sessioni e cookie
- [ ] Integrazione con le impostazioni
  - [ ] Pagina di configurazione
  - [ ] Persistenza delle preferenze
  - [ ] Impostazioni di default

### 4. Autenticazione e Autorizzazione
**Obiettivo**: Sistema di autenticazione sicuro e flessibile.

**Task**:
- [ ] Implementare il sistema di autenticazione
- [ ] Creare il sistema di ruoli e permessi
- [ ] Integrare con il sistema esistente di Genie

## Note di Migrazione
- Mantenere la compatibilità con i dati esistenti
- Documentare tutte le modifiche al database
- Creare script di migrazione dati se necessario
- Testare accuratamente ogni componente migrato
