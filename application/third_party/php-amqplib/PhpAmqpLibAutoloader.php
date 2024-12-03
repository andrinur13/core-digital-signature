<?php
spl_autoload_register(function ($class) {
    // Define the namespace prefix and base directory
    $prefix = 'PhpAmqpLib\\';
    $base_dir = __DIR__ . '/PhpAmqpLib/';

    // Check if the class uses the namespace prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // Not a class from this namespace
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace namespace separators with directory separators and append .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, include it
    if (file_exists($file)) {
        require $file;
    }
});
