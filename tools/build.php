<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$output = $argv[1] ?? $root . '/build/InventoryLibs-0.1.1.phar';
if(file_exists($output)){
	throw new RuntimeException("Output already exists: $output");
}
if(!is_dir(dirname($output)) && !mkdir(dirname($output), 0777, true)){
	throw new RuntimeException('Cannot create output directory');
}
$phar = new Phar($output);
$phar->startBuffering();
foreach(['plugin.yml', 'LICENSE', 'NOTICE.md'] as $file){
	$phar->addFile($root . '/' . $file, $file);
}
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/src', FilesystemIterator::SKIP_DOTS));
foreach($files as $file){
	if($file->isFile()){
		$relative = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
		$phar->addFile($file->getPathname(), $relative);
	}
}
$phar->setStub('<?php __HALT_COMPILER();');
$phar->setSignatureAlgorithm(Phar::SHA256);
$phar->stopBuffering();
echo $output . PHP_EOL;
