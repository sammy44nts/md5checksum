<?php
/**
 * md5checksum.php - compute and check MD5 message digest
 * 
 * author : Sammy Tahtah
 */
$options = getopt("c:", array("check:"));

function md5_checksum($file) {
  if (is_file($file)) {
    return md5_file($file)."  ".$file;
  } else {
    error_log( '"'.$file.'"'." is not a valid path to a file.");
  }
  return null;
}

function read_filelist($filelist) {
 $md5hash;
 $path;

 foreach (file($filelist, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES) as $line) {
   $md5hash = substr($line, 0, strpos($line, "  "));
   $path = substr($line, strpos($line, "  ") + 2);
   $line = md5_checksum($path);
   if (!empty($line)) {
     if (!strcmp($md5hash, substr($line, 0, strpos($line, "  "))))
       echo $path.": OK ";
     else
       echo "\r\n[FAIL] ".$path."\r\n";
   }
 }
}

function read_array_filelist($files) {
  foreach ($files as $file) {
    if (is_file($file))
	read_filelist($file);
    else
      error_log($file." is not a valid file.");
  }
}

if (isset($argv[1]) && empty($options))
  echo md5_checksum($argv[1])."\n";
elseif (!empty($options) && (!empty($options['c']) ||
	  !empty($options['check']))) {

  if (is_file($options['c']) || is_file($options['check']))
    (is_file($options['c']) ? read_filelist($options['c']) :
				read_filelist($options['check']));
  else
    (!empty($options['c']) ? read_array_filelist($options['c']) :
				read_array_filelist($options['check']));
} else
  echo "md5checksum.php [OPTION] [FILE]\r\n\r\nPrint or check MD5 (128 bits) checksums.\r\n-c, --check\r\n\tread MD5 sums from FILE and compare it to the related files.\r\n\r\n";

?>

