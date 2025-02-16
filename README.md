# YDeployExport - Live-Daten komfortabel exportieren und zurückspielen

Wer YDeploy nutzt, kennt das Problem: Der lokale Datenbestand ist nicht identisch mit dem Live-System. Das kann zu Problem führen.

Mit YDeployExport können Sie den Datenbestand Ihres Live-Systems exportieren und auf Ihr lokales System importieren. So haben Sie immer die gleichen Daten auf beiden Systemen.

Dabei werden Tabellen, die in der Konfiguration von YDeploy angegeben sind, automatisch ausgeschlossen. Ausgeschlossen werden auch `rex_tmp_*`-Tabellen sowie die Tabellen `rex_action`, `rex_config`, `rex_module` und `rex_template`. Diese Tabellen werden nicht exportiert, da sie in der Regel nicht zwischen den Systemen synchronisiert werden müssen.

> **Hinweis:** YDeployExport exportiert genau das, was ausgewählt wird. Beachten Sie, dass Sie auch sensible Daten exportiert werden können. Seien Sie also vorsichtig, wem Sie die Export-Dateien geben.
>
> **Hinweis:** YDeployExport exportiert genau das, was ausgewählt wird. Dabei können lokale Daten überschrieben werden. Seien Sie also vorsichtig, welche Daten Sie für den Export und anschließenden Import auswählen.

## Lizenz

MIT Lizenz, siehe [LICENSE.md](https://github.com/alexplusde/ydeploy_export/blob/master/LICENSE.md)  

## Autoren

**Alexander Walther**  
<http://www.alexplus.de>  
<https://github.com/alexplusde>  

**Projekt-Lead**  
[Alexander Walther](https://github.com/alexplusde)

## Credits
