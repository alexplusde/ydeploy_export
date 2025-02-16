<?php

namespace Alexplusde\YDeployExport;

use DateTimeImmutable;
use rex_backup;

class YDeployExport
{
    public static function forceBackup($filename = '', $tables = ['rex_article', 'rex_article_slice'])
    {
        $dir = rex_backup::getDir();

        if (!$filename) {
            $now = new DateTimeImmutable();
            $filename = $now->format('Y') . '-' . $now->format('m') . '-' . $now->format('d') . '_' . $now->format('H') . '-' . $now->format('i') . '-' . $now->format('s');
        }

        $exportFilePath = $dir . $filename . '.sql';


        if (rex_backup::exportDb($exportFilePath, $tables)) {
            return true;
        }
        return false;
    }
}
