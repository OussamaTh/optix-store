<?php

// Dynamically build the required folders inside Vercel's writable /tmp directory
if (!is_dir('/tmp/storage/framework/views')) {
    mkdir('/tmp/storage/framework/views', 0755, true);
}

require __DIR__ . '/../public/index.php';