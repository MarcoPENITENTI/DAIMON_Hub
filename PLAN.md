# DAIMON Hub - Piano di Sviluppo

## Stato Attuale
- [x] Struttura base del progetto
- [x] Sistema di routing e template di base
- [x] Documentazione iniziale
- [x] Sistema di autoloading PSR-4
- [x] Template Engine funzionante
- [x] Gestione di base delle rotte
- [x] Sistema di gestione degli errori
- [ ] Sistema di migrazione (prossimo passo)
  - [x] Creata tabella `config`
  - [ ] Implementare classe Config
  - [ ] Aggiungere supporto cache
  - [ ] Gestire migrazioni database
- [ ] Sistema di debug (prossimi passi)
  - [ ] Creare sistema di logging
  - [ ] Implementare debug bar
  - [ ] Aggiungere variabili d'ambiente di debug
- [ ] Layer Database (prossimi passi)
  - [ ] Implementare Query Builder
  - [ ] Aggiungere supporto a più database
  - [ ] Creare sistema di migrazioni
- [ ] Sistema di autenticazione (futuro)
  - [ ] Login/Logout
  - [ ] Gestione permessi
  - [ ] Recupero password

## Note Importanti
- **Struttura Progetto**:
  - Codice sorgente organizzato in `/html/app/`
  - Namespace allineati con la struttura delle cartelle
  - Autoloader PSR-4 funzionante
  - Template engine configurato correttamente
- **Configurazione**:
  - File di configurazione in `/html/app/config/`
  - Da verificare la gestione delle variabili d'ambiente
- **Sicurezza**:
  - Da implementare escape automatico XSS
  - Sistema di permessi da sviluppare
- **Prestazioni**:
  - Cache da implementare
  - Sistema di logging da sviluppare
- **Documentazione**:
  - Aggiornare `PLAN.md` con lo stato reale
  - Aggiornare `CONTEXT.md` con la struttura del progetto

## Prossimi Task

### 1. Sistema di Migrazione
- [ ] Implementare classe Config
- [ ] Creare sistema di migrazioni
- [ ] Aggiungere supporto per rollback
- [ ] Documentare il sistema di migrazione

### 2. Sistema di Debug
- [ ] Implementare sistema di logging
- [ ] Aggiungere debug bar
- [ ] Creare pagine di errore dettagliate
- [ ] Aggiungere variabili d'ambiente di sviluppo

### 3. Layer Database
- [ ] Implementare Query Builder
- [ ] Aggiungere supporto a più database
- [ ] Creare sistema di migrazioni
- [ ] Implementare sistema di seed

### 4. Miglioramenti Template
- [x] Aggiungere ereditarietà layout
- [x] Implementare escape automatico XSS
- [x] Aggiungere helper form/URL
- [x] Creare sistema sezioni
- [x] Supporto template parziali

### 2. Routing Avanzato
- [x] Supporto parametri rotta (es: `/users/:id`)
- [x] Gestione metodi HTTP (GET, POST, ecc.)
- [x] Middleware validazione/auth
- [x] Gruppi di rotte
- [x] Nomi rotte per URL sicuri

### 3. Configurazione
- [x] Tabella `config` creata
- [ ] Classe Config implementata
- [ ] Cache impostazioni attiva
- [ ] Interfaccia admin impostazioni

### 4. Problemi Aperti
- [ ] Risolvere errore classe MigrationManager
- [ ] Verificare e correggere autoloader
- [ ] Allineare namespace con la struttura delle cartelle
- [ ] Correggere il mapping dell'autoloader in produzione

## Roadmap

### 1. Migrazione Genie
- [ ] Analisi codice sorgente
- [ ] Identificazione componenti
- [ ] Progettazione architettura
- [ ] Migrazione modelli
- [ ] Migrazione viste/template
- [ ] Integrazione auth
- [ ] Test regressione

### 2. Layer Database
- [ ] Classe Database
  - [ ] Connessioni PDO
  - [ ] Prepared statements
  - [ ] Gestione errori
  - [ ] Logging query
- [ ] Query Builder
  - [ ] Operazioni CRUD
  - [ ] Clausole avanzate
  - [ ] Subquery
- [ ] Transazioni
  - [ ] Commit/Rollback
  - [ ] Gestione deadlock
- [ ] Repository
  - [ ] Interfaccia base
  - [ ] Repository specifici
  - [ ] Caching
- [ ] Migrazioni
  - [ ] Struttura base
  - [ ] Comandi CLI
  - [ ] Storicizzazione
  - [ ] Gestione errori e rollback

### 3. Debug System
- [ ] Pannello Debug
  - [ ] UI fluttuante/responsive
  - [ ] Toggle attivazione
- [ ] Logging
  - [ ] Intercettazione log
  - [ ] Filtri e ricerca
- [ ] Info Sistema
  - [ ] Performance
  - [ ] Utilizzo risorse
  - [ ] Query e tempi
  - [ ] Stato sessione
- [ ] Configurazione
  - [ ] Pagina impostazioni
  - [ ] Salvataggio preferenze
  - [x] Performance
  - [x] Utilizzo risorse
  - [x] Query e tempi
  - [x] Stato sessione
- [x] Configurazione
  - [x] Pagina impostazioni
  - [x] Salvataggio preferenze

### 4. Autenticazione
- [x] Sistema login/logout
- [x] Gestione ruoli/permessi
- [x] Integrazione Genie

## Prossimi Passi
1. **Priorità Alta**
   - [ ] Correggere l'autoloader in produzione
   - [ ] Completare la struttura base delle cartelle
   - [ ] Implementare il sistema di migrazione base

2. **Documentazione**
   - [ ] Aggiornare `CONTEXT.md` con la struttura del progetto
   - [ ] Aggiornare `PLAN.md` con lo stato reale
   - [ ] Documentare le convenzioni di codice

3. **Sviluppo**
   - [ ] Implementare il sistema di base
   - [ ] Sviluppare i componenti core
   - [ ] Creare i test di base

## Note Finali
- Mantenere traccia accurata dello stato reale del progetto
- Documentare tutte le modifiche e le decisioni prese
- Eseguire test dopo ogni modifica significativa
- Rispettare le convenzioni PSR-4 per i namespace
- Mantenere aggiornato il file PLAN.md con lo stato effettivo
