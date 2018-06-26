<?php

return [
    'help'            => 'Hilfe',
    'intro'           => 'Auf dieser Seite finden Sie Hinweise zur Verwendung der Anwendung.',
    'general_heading' => 'Allgemeine Hinweise',
    'general'         => 'Die <strong>Sharebox</strong> ist eine Anwendung, mit der sich Dateien vom eigenen Server im Internet freigeben lassen. Dazu werden einzigartige Links generiert, die geteilt werden können und so den Download nur für Besitzer des Links ermöglichen.<br>Alle Links führen zu einer sogenannten <strong>Landing-Page</strong>, auf der ein Button zum Herunterladen dargestellt wird. Die Darstellung der Landing-Page lässt sich individuell pro Freigabe einstellen, ansonsten wird eine Standardvorlage verwendet. So ist es möglich, die Seite auf die Zielgruppe auszurichten, ohne Kompromisse eingehen zu müssen.<br>Freigegebene Dateien können sehr groß sein, was ein Problem darstellt; üblicherweise muss PHP Daten für einen Download vollständig einlesen. Da die meisten Server den zur Verfügung stehenden Arbeitsspeicher limitieren, <em>streamt</em> die Sharebox den Download vom Quellserver zum Client. Dadurch sind quasi unbegrenzt große Dateien übertragbar.',
    'templates'       => [
        'heading'                    => 'Templates',
        'intro'                      => '<em>Templates</em> sind Vorlagen, die für die Darstellung der Download-Seiten verwendet werden. Dadurch kann je nach Anwendungsfall eine unterschiedlich gestaltete Seite verwendet werden: Designorientiert, seriös oder auch weihnachtlich.<br>Dabei wird zwischen sogenannten <strong>Standard-Templates</strong> und normalen Templates unterschieden. Es gibt aktuelle drei Standard-Templates: Eines für die Standard-Download-Seite, eines für nicht (mehr) existierende Downloads und eines für Downloads, die abgelaufen sind.',
        'types_markup'               => 'Der Inhalt eines Templates kann dabei mit drei Feldern angepasst werden:
                                <strong>head</strong>, <strong>body</strong> und <strong>footer</strong>. Der Inhalt
                                dieser Felder wird an den entsprechenden Stellen in der HTML-Seite dargestellt. Dadurch
                                kann die Funktionsweise und das Design des Templates angepasst werden, ohne dass die
                                Funktionalität versehentlich beeinträchtigt werden könnte: Jedes Template stellt immer
                                die grundlegende, korrekte HTML-Seite sowie eine JavaScript-API für den Download bereit.<br>
                                Die "Minimalausstattung" für ein Download-Template besteht dabei aus dem Download-Button wie folgt:
                            <pre><code class="html">&lt;button data-start-download&gt;Beliebiger Button-Text&lt;/button&gt;</code></pre>
                            Dieser <strong>muss</strong> das Data-Attribut <code>data-start-download</code> haben. Daran macht die JavaScript-API fest, dass es sich bei diesem Button um den eigentlichen Download-Button handelt.',
        'button_attributes'          => 'Zusätzlich ist noch eine Reihe von weiteren Data-Attributen verfügbar, mittels derer das Verhalten des Buttons gesteuert werden kann:
                            <ul><li>
                                    <strong><code>data-loading-text</code></strong><br>
                                    <span>Mit diesem Attribut kann ein abweichender Button-Text gesetzt werden, sobald der Download gestartet wurde. Insbesondere bei größeren Dateien kann die Vermittlung des Download-Streams einige Sekunden dauern, sodass dem Besucher hiermit ein visuelles Feedback gegeben werden kann.</span>
                                </li></ul>',
        'how_downloads_work_heading' => 'Funktionsweise von Downloads',
        'how_downloads_work'         => 'Grundsätzlich sind alle freigegebenen Dateien unter ihrem eindeutigen Link abrufbar. Um sowohl Direktlinks auf die Datei als auch eine eventuelle Suchmaschinen-Indexierung zu vermeiden, wurden sogenannte <em>Tickets</em> implementiert.<br>Das funktioniert folgendermaßen: Bei der Anforderung einer Download-Seite wird ein neues Download-Ticket generiert, das zum Abruf der Datei berechtigt, mehr oder weniger ein zufälliges Passwort. Dieses Ticket-Passwort wird dann auf der Download-Seite eingebettet. Wenn der Download-Button geklickt wird, wird eine spezielle URL aufgerufen, in der sowohl die eindeutige Download-ID, als auch das Ticket übergeben werden.<br>Dadurch ist gewährleistet, dass die Datei nur heruntergeladen werden kann, wenn vorher die Download-Seite aufgerufen und der eigentliche Datei-Download auch hier gestartet wurde.',
        'ticket_expiration'          => 'Alle Download-Tickets sind für einen bestimmten (in der <code>.env</code>-Datei konfigurierbaren) Zeitraum gültig, in der Voreinstellung 30 Minuten. Das bedeutet, dass sich der Besucher zwischen dem Öffnen der Download-Seite und dem Klick auf <em>Herunterladen</em> 30 Minuten Zeit lassen kann; ist diese Zeit abgelaufen, wird die Seite neu geladen und ein neues Ticket erzeugt.',
        'javascript_api_heading'     => 'JavaScript-API',
        'javascript_api'             => 'Auf jeder Download-Seite steht auch die Standard-JavaScript-API zur Verfügung. <strong>Wenn</strong> sich auf der Seite ein Button mit dem <code>data-start-download</code>-Attribut befindet, werden entsprechende Event-Listener an den Button angehängt, um den Download automatisch zu starten, sobald er betätigt wird.<br>Diese API muss allerdings nicht verwendet werden: Wenn anderes Markup verwendet wird, greifen die Event-Listener einfach nicht. Um den Download dann trotzdem starten zu können, muss die folgende URL aufgerufen werden:
         <pre><code>:url?ticket={Ticket-ID}</code></pre>Dadurch wird der Download gestartet, das Ticket invalidiert und der Download-Zähler erhöht.<br>Wie das geschieht, ist letztlich egal; die Standard-Implementierung lädt die Datei mittels eines Download-Links herunter, was für den Besucher eine angenehme Benutzererfahrung darstellt (keine neuen Fenster, einfach nur ein Download).',
        'variables_heading'          => 'Template-Variablen',
        'variables'                  => 'In allen Templates stehen eine Reihe von Variablen zur Verfügung, mittels derer dynamisch generierte Informationen in die Seite eingefügt werden können. Außerdem werden alle Templates mit der <a href="https://laravel.com/docs/5.6/blade" target="_blank">Blade-Template-Engine</a> geparst, sodass hier der volle Umfang von PHP 7+ zur Verfügung steht, falls es nötig ist.<br>Die folgenden Variablen sind immer verfügbar:
           <ul><li>
           <strong><code>id</code></strong>
           <p>Die einzigartige ID des Downloads (UUIDv4), die auch im Download-Link enthalten ist.</p>
           </li>
           <li>
           <strong><code>filename</code></strong>
           <p>Der Name der Datei, die heruntergeladen werden soll. Hier wird der Freigaben-Name verwendet, wenn angegeben, ansonsten der tatsächliche Dateiname.</p>
           </li>
           <li>
           <strong><code>filesize</code></strong>
           <p>Die Größe der herunterzuladenden Datei, in menschenlesbare Einheiten konviertiert (2KB, 510MB, 2GB, etc.)</p>
           </li>
           <li>
           <strong><code>expires</code></strong>
           <p>Das Ablaufdatum der Datei, als lesbarer Zeitabstand. Könnte etwa <em>in 10 Stunden</em> oder <em>in vier Tagen</em> sein. Hat die Datei kein Ablaufdatum, so lautet der Wert <em>:never</em>.</p>
           </li>
           <li>
           <strong><code>ticket</code></strong>
           <p>Das Download-Ticket für diesen Besucher (UUIDv4), das als Parameter in der Download-URL übergeben werden muss.</p>
           </li>
           <li>
           <strong><code>hasPassword</code></strong>
           <p>Ob für diese Freigabe ein Passwort festgelegt wurde. Dementsprechend muss in der Anfrage an die Download-URL auch das Passwort übergeben, also auf der Download-Seite ein Eingabefeld dargestellt werden. Das Passwort wird im Authorization-Header versandt (bei HTTPS-Verbindungen kein Problem).</p>
           </li>
           
           </ul>'
    ],

    'user_management' => [
        'heading'     => 'Benutzerverwaltung',
        'intro'       => 'Die Sharebox stellt eine vollständige Benutzerverwaltung bereit, um Konten für administrative Benutzer zu erstellen. Um eine freigegebene Datei herunterzuladen, ist <strong>kein</strong> Benutzerkonto erforderlich; Konten werden nur benötigt, um alle Freigaben, Benutzer und Templates zu verwalten.',
        'permissions' => 'Grundsätzlich haben registrierte Benutzer die Möglichkeit, alle Freigaben, Benutzer und Templates zu ändern, löschen oder neu hinzuzufügen. Unterschiedliche Berechtigungsstufen sind derzeit nicht vorgesehen.'
    ]
];
