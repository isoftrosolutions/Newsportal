<?php
/**
 * Production Deployment Script
 *
 * Usage: php deploy.php [branch]
 * Default branch: main
 */

$repo   = 'https://github.com/isoftrosolutions/Newsportal.git';
$branch = $argv[1] ?? 'main';
$dir    = __DIR__;

echo "=== Production Deploy ===\n";
echo "Target: $dir\n";
echo "Branch: $branch\n\n";

// 1. Verify git is available
exec('git --version 2>&1', $out, $code);
if ($code !== 0) {
    die("ERROR: git not found.\n");
}
echo "[OK] Git available\n";

// 2. Init git repo if not already one
if (!is_dir("$dir/.git")) {
    echo "[..] Not a git repo. Initializing...\n";
    exec("git init 2>&1", $out, $code);
    if ($code !== 0) {
        die("ERROR: git init failed.\n" . implode("\n", $out) . "\n");
    }
    exec("git remote add origin $repo 2>&1", $out, $code);
    if ($code !== 0) {
        die("ERROR: Failed to add remote.\n" . implode("\n", $out) . "\n");
    }
    echo "[OK] Git repo initialized with remote: $repo\n";
} else {
    exec("git remote set-url origin $repo 2>&1", $out, $code);
    if ($code !== 0) {
        die("ERROR: Failed to set remote URL.\n" . implode("\n", $out) . "\n");
    }
    echo "[OK] Remote URL set to: $repo\n";
}

// 4. Fetch latest
echo "\nFetching from remote...\n";
exec("git fetch origin 2>&1", $out, $code);
if ($code !== 0) {
    die("ERROR: Fetch failed.\n" . implode("\n", $out) . "\n");
}
echo "[OK] Fetch complete\n";

// 5. Stash any local changes
exec("git stash 2>&1", $out, $code);

// 6. Pull latest code
echo "\nPulling $branch...\n";
exec("git pull origin $branch 2>&1", $out, $code);
echo implode("\n", $out) . "\n";

if ($code !== 0) {
    echo "\nWARNING: Pull had issues. Try resolving manually.\n";
    exit(1);
}

echo "\n=== Deploy complete ===\n";
