<?php
// Extract variables from the data array passed to the template
extract($this->data ?? []);
?>

<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Pagina non trovata</h2>
        <p class="text-gray-600 mb-8">La pagina che stai cercando non esiste o è stata spostata.</p>
        <a href="/" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
            Torna alla home
        </a>
    </div>
</div>
