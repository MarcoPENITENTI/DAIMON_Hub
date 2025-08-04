<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $this->e($page_title) . ' | ' : '' ?>DAIMON Hub</title>
    <link href="/assets/css/app.css" rel="stylesheet">
    <?= $styles ?? '' ?>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <?php 
        // If content is a callable (function), call it
        if (isset($content) && is_callable($content)) {
            $content();
        } 
        // If content is a string, output it directly
        elseif (isset($content)) {
            echo $content;
        }
        ?>
    </div>
    
    <script src="/assets/js/vendor/jquery.min.js"></script>
    <script src="/assets/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
    <?= $scripts ?? '' ?>
</body>
</html>
