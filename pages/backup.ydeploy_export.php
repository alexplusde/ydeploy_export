<?php

use Alexplusde\YDeployExport\YDeployExport;

$addon = rex_addon::get('ydeploy_export');

$form = rex_config_form::factory($addon->getName());
$form->addParam('export', '1');

$tables_never = [rex::getTable('action') => null, rex::getTable('module') => null, rex::getTable('module_action') => null, rex::getTable('template') => null];
$ydeploy_tables_never = [];


if (\rex::isBackend() && \rex_addon::get('ydeploy')->isAvailable()) {
    $config = \rex_addon::get('ydeploy')->getProperty('config');
    $tables = $config['fixtures']['tables'];
    foreach ($tables as $table => $filter) {
        $ydeploy_tables_never[rex::getTablePrefix() . $table] = null;
    }
}

// Alle Tabellen aus der Datenbank holen
$tables_all = rex_sql::factory()->getTables(rex::getTablePrefix());
$tables_options = [];

$field = $form->addSelectField('tables');
$field->setLabel($this->i18n('ydeploy_export_tables'));
$field->setNotice($this->i18n('ydeploy_export_tables_notice', implode(', ', array_keys($tables_never))));

$select = $field->getSelect();
$select->setSize(count($tables_all));
$select->setMultiple(true);

// Alle aus $tables_all, die nicht in $ydeploy_tables_never sind - diese als "disabled" ausgeben
foreach ($tables_all as $table) {
    if (array_key_exists($table, $ydeploy_tables_never)) {
        $select->addOption($table, $table, 0, 0, ['disabled' => 'disabled']);
    } elseif (array_key_exists($table, $tables_never)) {
        $select->addOption($table, $table, 0, 0, ['disabled' => 'disabled']);
    } elseif (rex::getTablePrefix() . 'tmp_' == substr($table, 0, strlen(rex::getTablePrefix() . 'tmp_'))) {
        $select->addOption($table, $table, 0, 0, ['disabled' => 'disabled']);
    } else {
        $select->addOption($table, $table);
    }
}


$fragment = new rex_fragment();
$fragment->setVar('class', 'info', false);
$fragment->setVar('title', $this->i18n('ydeploy_export.title'), false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');

if (rex_get('export', 'int', 0) === 1) {

    $tables = array_filter(explode('|', rex_config::get('ydeploy_export', 'tables', '')));
    if($tables !== []) {
        $filename = date('Y-m-d His') . '_ydeploy_export';
        YDeployExport::forceBackup($filename, $tables);
        rex_response::sendFile(rex_backup::getDir() . $filename . '.sql', 'application/octet-stream', 'attachment');
        exit;
    }
}
