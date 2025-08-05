<?php
// Extract variables from the data array passed to the template
extract($this->data ?? []);
?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="max-w-md w-full text-center">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <div class="text-red-500 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Errore del server</h1>
            <p class="text-gray-600 mb-6">Si è verificato un errore imprevisto. Il nostro team è stato avvisato e sta lavorando per risolvere il problema.</p>
            <?php if (isset($error_message)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 text-left">
                <p class="text-sm text-red-700"><?= htmlspecialchars($error_message) ?></p>
            </div>
            <?php endif; ?>
            <a href="/" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                Torna alla home
            </a>
        </div>
    </div>
</div>
