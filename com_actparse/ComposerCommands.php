<?php

namespace Bakual\Actparse;

class ComposerCommands
{	public static function cleanup()
{
	self::deleteDirectory(__DIR__ . '/admin/vendor/bozhinov/pchart/examples');
	$files = array(
		'bozhinov\pchart\buildAll.cmd',
		'bozhinov\pchart\change.log',
		'bozhinov\pchart\composer.json',
		'bozhinov\pchart\index.php',
		'bozhinov\pchart\LICENSE',
		'bozhinov\pchart\php.webserver.cmd',
		'bozhinov\pchart\README.md',
		'bozhinov\pchart\readme.txt',
	);

	foreach ($files as $file)
	{
		$file = __DIR__ . '/admin/vendor/' . $file;

		if (file_exists($file))
		{
			unlink($file);
			echo "File deleted! ({$file})\n";
		}
	}

	// Delete AWS files not needed for S3
	self::scanDirectory(__DIR__ . '/admin/vendor/aws/aws-sdk-php/src/data', 's3');
}

	private static function scanDirectory($dir, $except)
	{
		$dir_handle = is_dir($dir) ? opendir($dir) : false;

		// Falls Verzeichnis nicht geoeffnet werden kann, mit Fehlermeldung terminieren
		if (!$dir_handle)
		{
			return false;
		}

		while ($file = readdir($dir_handle))
		{
			if ($file != "." && $file != "..")
			{
				if (is_dir($dir . "/" . $file) && (strpos($file, $except) === false))
				{
					echo "Directory: ({$dir}) matches " . (int) strpos($dir, $except) . "\n";

					self::deleteDirectory($dir . '/' . $file);
				}
			}
		}
	}

	private static function deleteDirectory($dir)
	{
		$dir_handle = is_dir($dir) ? opendir($dir) : false;

		// Falls Verzeichnis nicht geoeffnet werden kann, mit Fehlermeldung terminieren
		if (!$dir_handle)
		{
			return false;
		}

		while ($file = readdir($dir_handle))
		{
			if ($file != "." && $file != "..")
			{
				if (!is_dir($dir . "/" . $file))
				{
					//Datei loeschen
					@unlink($dir . "/" . $file);
				}
				else
				{
					//Falls es sich um ein Verzeichnis handelt, "deleteDirectory" aufrufen
					self::deleteDirectory($dir . '/' . $file);
				}
			}
		}

		closedir($dir_handle);

		//Verzeichnis löschen
		rmdir($dir);

		echo "Directory deleted! ({$dir})\n";

		return true;
	}
}