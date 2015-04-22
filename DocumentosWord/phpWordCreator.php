<?php
/**
 * Header file
 */
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory;

error_reporting(E_ALL);
define('CLI', (PHP_SAPI == 'cli') ? true : false);
define('EOL', CLI ? PHP_EOL : '<br />');
define('SCRIPT_FILENAME', basename($_SERVER['SCRIPT_FILENAME'], '.php'));
define('IS_INDEX', SCRIPT_FILENAME == 'index');

require_once __DIR__ . '/PhpWord/Autoloader.php';
Autoloader::register();
Settings::loadConfig();

// Set writers
$writers = array('Word2007' => 'docx');


// Return to the caller script when runs by CLI
if (CLI) {
    return;
}


/**
 * Write documents
 *
 * @param \PhpOffice\PhpWord\PhpWord $phpWord
 * @param string $filename
 * @param array $writers
 */
function write($phpWord, $filename, $writers)
{
    $result = '';

    // Write documents
    foreach ($writers as $writer => $extension) {
        $result .= date('H:i:s') . " Write to {$writer} format";
        if (!is_null($extension)) {
            $xmlWriter = IOFactory::createWriter($phpWord, $writer);
            $xmlWriter->save(__DIR__ . "/{$filename}.{$extension}");
            rename(__DIR__ . "/{$filename}.{$extension}", __DIR__ . "/results/{$filename}.{$extension}");
        }
        $result .= EOL;

    }

    //$result .= getEndingNotes($writers);

    //return $result;
}

/**
 * Get ending notes
 *
 * @param array $writers
 */
function getEndingNotes($writers)
{
    $result = '';
    // Return
    if (CLI) {
        $result .= 'The results are stored in the "results" subdirectory.' . EOL;
    } else {
        if (!IS_INDEX) {
            $types = array_values($writers);
            $result .= '<p>&nbsp;</p>';
            $result .= '<p>Results: ';
            foreach ($types as $type) {
                if (!is_null($type)) {
                    $resultFile = 'results/' . SCRIPT_FILENAME . '.' . $type;
                    if (file_exists($resultFile)) {
                        $result .= "<a href='{$resultFile}' class='btn btn-primary'>{$type}</a> ";
                    }
                }
            }
            $result .= '</p>';
        }
    }

    return $result;
}
?>
