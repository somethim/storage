<?php

if ($argc !== 2) {
    die("Usage: php version.php [major|minor|patch]\n");
}

$composerFile = __DIR__ . '/../composer.json';
$composer = json_decode(file_get_contents($composerFile), true);
$version = explode('.', $composer['version']);

switch ($argv[1]) {
    case 'major':
        $version[0]++;
        $version[1] = 0;
        $version[2] = 0;
        break;
    case 'minor':
        $version[1]++;
        $version[2] = 0;
        break;
    case 'patch':
        $version[2]++;
        break;
    default:
        die("Invalid version type. Use major, minor, or patch.\n");
}

$composer['version'] = implode('.', $version);
file_put_contents($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "Version updated to " . $composer['version'] . "\n";
