# Contesto del Progetto DAIMON Hub

## Stack Tecnologico
- **Backend**: PHP 8.1+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript (jQuery)
- **CSS Framework**: TailwindCSS (NON Bootstrap)
- **Asset Management**: Dipendenze scaricate localmente (nessuna CDN esterna)

## Principi di Sviluppo

### 1. Programmazione a Oggetti
- Utilizzare il paradigma OOP in modo coerente
- Preferire la composizione all'ereditarietà
- Utilizzare il pattern Repository per l'accesso ai dati
- Implementare Design Pattern appropriati (Factory, Strategy, Observer, ecc.)
- Utilizzare Dependency Injection per una migliore testabilità

### 2. Struttura del Codice
- **`src/`**: Codice sorgente PHP
  - `Core/`: Componenti fondamentali del framework
  - `Models/`: Modelli di dati
  - `Repositories/`: Accesso ai dati
  - `Services/`: Logica di business
  - `Controllers/`: Gestione delle richieste HTTP
  - `Middleware/`: Middleware per la pipeline HTTP
  - `Exceptions/`: Eccezioni personalizzate
  - `Interfaces/`: Interfacce per i contratti
  - `Traits/`: Traits per la condivisione di codice
- **`public/`**: File accessibili pubblicamente
- **`views/`**: Template delle viste
  - `layouts/`: Layout principali
  - `components/`: Componenti riutilizzabili
  - `partials/`: Parti di template riutilizzabili
- **`config/`**: File di configurazione
- **`assets/`**: Asset frontend
  - `css/`: Stili globali e componenti
  - `js/`: Script JavaScript
  - `images/`: Immagini
  - `fonts/`: Font personalizzati
- **`tests/`**: Test automatici
- **`migrations/**: Migrazioni del database
- **`storage/`**: File generati dall'applicazione
  - `cache/`
  - `logs/`
  - `sessions/`

### 3.1 Programmazione a Oggetti
- **Preferire la composizione all'ereditarietà**:
  - Utilizzare l'ereditarietà solo quando esiste una relazione "è un" molto chiara
  - Preferire la composizione ("ha un") per una maggiore flessibilità
  - Esempio: invece di `class AdminUser extends User`, usare `class User { private $roles = []; }`
  - Utilizzare interfacce per definire contratti chiari
  - Utilizzare trait per condividere comportamento orizzontale

### 3.2 Pattern Repository
- Astrazione tra il dominio e il layer di persistenza
- Permette di cambiare sistema di storage senza modificare la logica di business
- Esempio di interfaccia:
  ```php
  interface UserRepository {
      public function findById($id): User;
      public function save(User $user): void;
      // Altri metodi specifici del dominio
  }
  ```
- Implementazione concreta (es. `MySqlUserRepository`)
- Centralizza le query SQL
- Migliore testabilità (si possono creare mock per i test)

### 3.3 Convenzioni di Codice
- **PHP**:
  - Nomi di classi in `PascalCase`
  - Metodi e proprietà in `camelCase`
  - Costanti in `UPPER_SNAKE_CASE`
  - Type hinting e return types ovunque possibile
  - Commenti PHPDoc per classi, metodi e proprietà
  - Namespace coerenti con la struttura delle cartelle
  - Utilizzare dichiarazioni di tipo strict
  - Utilizzare le funzionalità moderne di PHP 8.1+ (enum, readonly properties, ecc.)

- **JavaScript**:
  - `camelCase` per variabili e funzioni
  - `PascalCase` per componenti
  - Utilizzare `const` e `let` invece di `var`
  - Utilizzare arrow functions quando appropriato
  - Utilizzare async/await per operazioni asincrone
  - Commenti JSDoc per funzioni complesse

- **CSS/Tailwind**:
  - Utilizzare le classi di utilità di Tailwind quando possibile
  - Creare componenti riutilizzabili con `@apply` quando necessario
  - Mantenere un file CSS principale per le personalizzazioni globali
  - Utilizzare variabili CSS per i temi e i colori
  - Organizzare le classi in ordine logico (layout > typography > colors > etc.)

### 4. Gestione degli Asset
- Utilizzare Vite per il bundling degli asset
- Minificare CSS e JavaScript in produzione
- Utilizzare code splitting per il caricamento lazy
- Implementare il versioning degli asset per il cache busting
- Utilizzare import ES6 per i moduli JavaScript

### 5. Database
- Utilizzare prepared statements per tutte le query
- Implementare un sistema di migrazioni
- Utilizzare transaction per operazioni multiple
- Implementare il pattern Repository
- Utilizzare DTO (Data Transfer Objects) per il passaggio dei dati

### 6. Sicurezza
- Validare tutti gli input
- Utilizzare prepared statements
- Implementare CSRF protection
- Sanificare l'output
- Utilizzare password hashing forte (bcrypt/Argon2)
- Implementare rate limiting
- Utilizzare HTTPS
- Headers di sicurezza (CSP, HSTS, XSS Protection, etc.)

### 7. Performance
- Implementare la cache dove appropriato
- Ottimizzare le query SQL
- Utilizzare la paginazione per i risultati lunghi
- Minimizzare le richieste HTTP
- Utilizzare il caricamento lazy per immagini e componenti
- Implementare la compressione GZIP/Brotli

### 8. Testing
- Scrivere test unitari per la logica di business
- Scrivere test di integrazione per le API
- Utilizzare PHPUnit per il backend
- Utilizzare Jest o Vitest per il frontend
- Implementare test end-to-end critici
- Utilizzare GitHub Actions per CI/CD

### 9. Documentazione
- Documentare le API con OpenAPI/Swagger
- Mantenere un CHANGELOG.md
- Documentare le dipendenze
- Documentare le decisioni architetturali (ADR)
- Utilizzare commenti nel codice in modo appropriato

### 10. Workflow di Sviluppo
- Utilizzare Git per il versionamento
- Seguire il Git Flow
- Scrivere commit message significativi
- Fare code review prima del merge
- Utilizzare pull request
- Mantenere il repository pulito e organizzato

## Convenzioni Specifiche Tailwind
- Utilizzare le classi di utilità direttamente nell'HTML
- Creare componenti riutilizzabili con `@apply` solo quando necessario
- Utilizzare le variabili di configurazione per temi e colori
- Mantenere l'ordine delle classi consistente
- Utilizzare i plugin ufficiali Tailwind quando disponibili
- Implementare dark mode utilizzando la classe `dark:`
- Utilizzare le direttive `@layer` per organizzare gli stili personalizzati

## Linee Guida per le API
- Utilizzare JSON come formato di scambio
- Implementare versioning delle API
- Utilizzare i codici di stato HTTP appropriati
- Implementare la paginazione
- Utilizzare il formato ISO 8601 per le date
- Documentare tutte le endpoint
- Implementare rate limiting
- Utilizzare l'autenticazione JWT

## Strumenti di Sviluppo
- Editor: PHPStorm o VS Code
- PHP_CodeSniffer per lo stile del codice (opzionale, da usare localmente)
- Xdebug per il debugging (opzionale, da usare localmente)

### Note sulle Dipendenze Frontend
Tutte le dipendenze frontend (jQuery, TailwindCSS, ecc.) devono essere:
1. Scaricate localmente nella cartella `assets/vendor/`
2. Incluse nel repository del progetto
3. Versionate per garantire la riproducibilità
4. Minificate per la produzione

Questo approccio garantisce:
- Conformità GDPR (nessuna richiesta a server di terze parti)
- Maggiore affidabilità (nessun punto di fallimento esterno)
- Prestazioni più prevedibili
- Funzionamento offline in fase di sviluppo

## Convenzioni per i Commit
- Prefissi:
  - `feat:` Nuove funzionalità
  - `fix:` Correzioni di bug
  - `docs:` Aggiornamenti alla documentazione
  - `style:` Formattazione, punto e virgola mancanti, ecc.
  - `refactor:` Modifiche al codice che non correggono bug né aggiungono funzionalità
  - `perf:` Miglioramenti alle prestazioni
  - `test:` Aggiunta o modifica di test
  - `chore:` Aggiornamenti alle attività di build, configurazioni, ecc.

## Sistema di Debug
Un sistema di debug integrato mostrerà informazioni utili in una box espandibile quando la modalità debug è attiva.

### Cosa mostrare:
- Errori e warning PHP
- Query SQL eseguite con tempi di esecuzione
- Utilizzo della memoria
- Tempo di esecuzione dello script
- Stack trace degli errori
- Dump delle variabili di sessione
- Log personalizzati

### Implementazione:
```php
class Debug {
    private static $startTime;
    private static $queries = [];
    
    public static function startTimer() {
        self::$startTime = microtime(true);
    }
    
    public static function logQuery($sql, $params = []) {
        self::$queries[] = [
            'sql' => $sql,
            'params' => $params,
            'time' => microtime(true)
        ];
    }
    
    public static function renderDebugBar() {
        if (!DEBUG_MODE) return;
        
        $executionTime = microtime(true) - self::$startTime;
        $memoryUsage = memory_get_peak_usage(true) / 1024 / 1024; // MB
        
        // Output della barra di debug
        include __DIR__ . '/views/debug/debug_bar.php';
    }
}
```

## Configurazione Iniziale
1. Clonare il repository
2. Copiare `.env.example` in `.env` e configurare
3. Caricare le dipendenze frontend nella cartella `assets/vendor/`
4. Generare la chiave dell'applicazione
5. Configurare il database in `.env`
6. Eseguire le migrazioni del database
7. Verificare i permessi delle cartelle `cache/` e `logs/`

## Linee Guida per i Pull Request
- Una funzionalità/un bugfix per PR
- Includere test quando possibile
- Aggiornare la documentazione
- Assicurarsi che i test passino
- Richiedere la revisione a un altro sviluppatore
- Risolvere eventuali conflitti

## Ambiente di Produzione
- Utilizzare PHP-FPM con Nginx/Apache
- Configurare il task scheduler per i job in coda
- Impostare i backup del database
- Monitorare le performance
- Configurare i log
- Implementare il monitoring
- Configurare gli allarmi
