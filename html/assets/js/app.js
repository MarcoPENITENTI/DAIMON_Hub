// Main JavaScript file
console.log('DAIMON Hub initialized');

// Document ready
$(document).ready(function() {
    console.log('jQuery is working');
    
    // Enable Bootstrap tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Enable Bootstrap popovers
    $('[data-toggle="popover"]').popover();
});

// Global error handler
window.onerror = function(message, source, lineno, colno, error) {
    console.error('Global error:', { message, source, lineno, colno, error });
    return false; // Don't trigger default error handling
};
