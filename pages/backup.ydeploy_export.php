<?php

use Alexplusde\YDeployExport\YDeployExport;

$addon = rex_addon::get('ydeploy_export');

$form = rex_config_form::factory($addon->getName());

$tables_never = [rex::getTable('action') => null, rex::getTable('module') => null, rex::getTable('module_action') => null, rex::getTable('template') => null];
$ydeploy_tables_never = [];


if (\rex::isBackend() && \rex_addon::get('ydeploy')->isAvailable()) {
    $config = \rex_addon::get('ydeploy')->getProperty('config');
    $tables = $config['fixtures']['tables'];
    foreach ($tables as $table => $filter) {
        $ydeploy_tables_never[rex::getTablePrefix().$table] = null;
    }
}

// Alle Tabellen aus der Datenbank holen
$tables_all = rex_sql::factory()->getTables(rex::getTablePrefix());
$tables_options = [];

$field = $form->addSelectField('table');
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

$form->addParam('export', '1');

$fragment = new rex_fragment();
$fragment->setVar('class', 'info', false);
$fragment->setVar('title', $this->i18n('ydeploy_export.title'), false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');

// in $_POST befindet sich 'table' mit den ausgewählten Tabellen in einem zufälligen Schlüssel:
// ['123'] => ['table' => ['rex_article', 'rex_article_slice']]

$tables = [];
foreach($_POST as $key => $value) {
    if (is_array($value) && isset($value['table'])) {
        $tables = $value['table'];
        break;
    }
}

if (rex_get('export', 'int', 0) === 1) {
    $filename = date('Y-m-d His').'_ydeploy_export';
    YDeployExport::forceBackup($filename, $tables);
    rex_response::sendFile(rex_backup::getDir().$filename.'.sql', 'application/octet-stream', 'attachment');
    exit;
}
