# DAIMON Hub - Guida alla sintassi dei template

## Introduzione
Questo documento descrive la sintassi da utilizzare nei template PHP del DAIMON Hub. Il sistema utilizza un motore di template leggero e senza dipendenze esterne.

## Struttura base

### File PHP
I template sono semplici file PHP con estensione `.php`. Ecco un esempio base:

```php
<!DOCTYPE html>
<html>
<head>
    <title><?= $this->escape($page_title) ?> | DAIMON Hub</title>
</head>
<body>
    <?= $this->include('partials/header.php') ?>
    
    <main>
        <h1><?= $this->escape($page_title) ?></h1>
        <?= $content ?>
    </main>
    
    <?= $this->include('partials/footer.php') ?>
</body>
</html>
```

## Funzioni disponibili

### `$this->escape($string)`
Esegue l'escape di una stringa per l'output HTML sicuro.

```php
<p><?= $this->escape($user_input) ?></p>
```

### `$this->include($template, $data = [])`
Include un altro template all'interno del template corrente.

```php
<!-- Includi header con dati aggiuntivi -->
<?= $this->include('partials/header.php', ['show_banner' => true]) ?>
```

### `$this->e($string)`
Alias di `escape()`. Utile per risparmiare caratteri.

```php
<p><?= $this->e($user_input) ?></p>
```

## Variabili
Le variabili vengono passate al template tramite l'array associativo nel metodo `render()`.

```php
// Nel controller
$data = [
    'page_title' => 'Dashboard',
    'user' => ['name' => 'Mario Rossi']
];
$templateEngine->render('dashboard.php', $data);
```

```php
<!-- Nel template -->
<h1>Benvenuto, <?= $this->e($user['name']) ?></h1>
```

## Struttura delle directory

```
views/
├── layouts/
│   └── default.php    # Layout base
├── partials/         # Componenti riutilizzabili
│   ├── header.php
│   └── footer.php
└── pages/            # Pagine specifiche
    ├── home.php
    └── dashboard.php
```

## Best practice

1. **Sicurezza**: Usa sempre `$this->escape()` o `$this->e()` per l'output di variabili non affidabili.
2. **Organizzazione**: Mantieni i template piccoli e modulari.
3. **Logica**: Limita la logica complessa ai controller.
4. **Performance**: Evita query al database direttamente nei template.

## Esempi avanzati

### Cicli
```php
<ul>
    <?php foreach ($items as $item): ?>
        <li><?= $this->e($item['name']) ?></li>
    <?php endforeach; ?>
</ul>
```

### Condizioni
```php
<?php if ($is_logged_in): ?>
    <p>Bentornato, <?= $this->e($user_name) ?></p>
<?php else: ?>
    <a href="/login">Accedi</a>
<?php endif; ?>
```

### Filtri personalizzati (opzionale)
Puoi estendere la classe TemplateEngine per aggiungere filtri personalizzati.

## Note sulla migrazione da Twig
- Sostituisci `{{ variabile }}` con `<?= $this->e($variabile) ?>`
- Sostituisci `{% include 'template.twig' %}` con `<?= $this->include('template.php') ?>`
- Sostituisci i cicli e le condizioni con la sintassi PHP nativa
