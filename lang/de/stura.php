<?php

return [

    'welcome' => [
        'title_1' => 'Willkommen beim Antragsportal',
        'title_2' => 'des Studierendenrates der BTU Cottbus-Senftenberg!',
        'description' => 'Hier kannst du Anträge an den StuRa der BTU Cottbus-Senftenberg stellen, wie bspw. die Befreiung vom Deutschlandsemesterticket.',

        'login' => 'Weiter mit BTU Account',

        'no_btu_account' => 'Du hast keinen Zugriff mehr auf deinen BTU Account? Dann schicke bitte deine Exmatrikulationsbescheinigung an office@stura-btu.de',
    ],

    'dashboard' => [
        'title' => 'Dashboard',
        'hello' => 'Hallo',
        'description' => 'Du kannst hier deine StuRa Anträge einreichen!',
        'dticket_request' => 'Befreiung vom Semesterticket beantragen',
        'dticket_section_title' => 'Deine eingereichten Semesterticketbefreiungen',
    ],

    'dticket' => [

        'alerts' => [
            'one_time_token' => [
                'title' => 'Einmallink zur Einreichung verwendet!',
                'message' => 'Dieser Antrag wurde mit einem Einmallink eingereicht und erfordert deswegen eine Stärkere Prüfung der Unterlagen und des Sachverhalts.',
            ],
            'has_login_link' => [
                'title' => 'Existierender Anmeldelink!',
                'message' => 'Es existiert ein Anmeldelink für den Studierenden, über den sich angemeldet werden konnte. Es handelt sich ggf. um eine Person mit keinem Zugriff auf den BTU Account. Bei externen Accounts kann keine Berechtigung für das Semesterticket überprüft werden.',
            ],
            'no_dticket_entitlement' => [
                'title' => 'Keine Berechtigung für das Semesterticket!',
                'message' => 'Es konnte keine Berechtigung für das Deutschlandsemesterticket gefunden werden. Bitte prüfe, ob der Studierende auf eine Befreiung berechtigt ist.',
            ],
            'multiple_submissions' => [
                'title' => 'Mehrfache Einreichungen für das gleiche Semester!',
                'message' => 'Es wurden bereits mehrere Anträge für das gleiche Semester eingereicht. Bitte prüfe, ob der erneute Antrag notwendig ist.',
            ],
        ],

        'about' => [
            'title' => 'Angaben über dich',
            'description' => 'Die folgenden Angaben werden automatisch aus deinem BTU Account übernommen. Bei Fehlern, wende dich bitte an den Studierendenservice.',
        ],

        'missing_config' => [
            'title' => 'Semester noch nicht konfiguriert',
            'description' => 'Für das ausgewählte Semester ist noch keine Konfiguration hinterlegt.',
            'message' => 'Die Konfiguration für das ausgewählte Semester wurde noch nicht hinterlegt. Bitte versuche es später erneut oder wende dich an semesterticket@stura-btu.de, so dass wir die Konfiguration für das Semester hinterlegen können.',
        ],

        'not_eligable' => [
            'title' => 'Nicht berechtigt',
            'description' => 'Du bist nicht berechtigt, eine Befreiung zu beantragen.',
            'message' => 'Wir konnten bei dir keine Berechtigung/Bezugspflicht für das Deutschlandsemesterticket feststellen, daher kannst du auch keine Befreiung beantragen. Sollte dies aus deiner Sicht ein Fehler sein, wende dich bitte an semesterticket@stura-btu.de',
        ],

        'exemption' => [
            'title' => 'Angaben zur Befreiung',
            'description' => 'Die Nichtausnutzung des Deutschlandsemestertickets begründet keinen Anspruch auf Erstattung von Beförderungsentgelt.',

            'months_help_text' => 'Nur volle ungenutzte Monate können erstattet werden. (Ausnahme bildet der April und Mai im SoSe 2024, wenn der Antrag bis zum 05.05.2024 gestellt wird.)',

            'reason_help_text' => 'Anträge für a) und b) müssen bis zum 05.05.2024 gestellt werden, ansonsten werden diese abgelehnt.',
        ],

        'banking' => [
            'title' => 'Angaben zur Erstattung',
        ],

        'consent' => [
            'title' => 'Einwilligungen',

            'privacy_title' => 'Speicherung und Verarbeitung der Daten',
            'privacy' => 'Ich willige ein, dass meine Daten zur Bearbeitung der Befreiung und Auszahlung gespeichert und verarbeitet werden dürfen.',

            'remove_permission_title' => 'Entfall der Ticketberechtigung',
            'remove_permission' => 'Ich willige ein, dass meine Ticketberechtigung in dem gewählten Zeitraum sofort nach Abschicken des Formulars entfällt. Bei einer Ablehnung des Antrages wird die Ticketberechtigung wiederhergestellt.',

            'truth_title' => 'Richtigkeit der Angaben',
            'truth' => 'Ich bestätige, dass alle Angaben der Wahrheit entsprechen und ich keine zwei Befreiungs-Anträge für das gleiche Semester stelle.',
        ],

        'status' => [
            'title' => 'Status',
            'description' => 'Verfolge hier den Status deiner Befreiung. Du erhältst eine E-Mail, sobald sich der Status ändert.',

            'pending' => 'In Bearbeitung',
            'approved' => 'Angenommen',
            'paid' => 'Ausgezahlt',
            'rejected' => 'Abgelehnt',
        ],

    ],

    'fields' => [
        'name' => 'Name',
        'email' => 'E-Mail',
        'btu_id' => 'BTU-ID',
        'entitlements' => 'Berechtigungen',
        'is_admin' => 'Admin-Rechte',
        'created_at' => 'Eingereicht',
        'status' => 'Status',
        'semester' => 'Semester',
        'months' => 'Zu befreiender Zeitraum',
        'reason' => 'Grund',
        'comment' => 'Kommentar',
        'attachments' => 'Anhänge',
        'banking_name' => 'Kontoinhaber',
        'banking_iban' => 'IBAN',
        'banking_bic' => 'BIC',
        'file' => 'Datei',
        'reason_for_rejection' => 'Grund der Ablehnung',
        'exclude_starts_at' => 'Befreiung beginnt am',
        'exclude_ends_at' => 'Befreiung endet am',
        'banking_amount' => 'Erstattungsbetrag',
        'banking_reference' => 'Verwendungszweck',
    ],

    'notifications' => [

        'submitted' => [
            'subject' => 'Eingangsbestätigung: Antrag auf Befreiung vom Semesterticket',
            'greeting' => 'Hallo :name!',
            'line_1' => 'Dein Antrag auf Befreiung vom Semesterticket ist bei uns eingegangen.',
            'action' => 'Antrag ansehen',
            'line_2' => 'Wir werden deinen Antrag so schnell wie möglich bearbeiten und dich über den Status informieren.',
            'line_3' => 'Aufgrund der hohen Anzahl an Anfragen und Anträgen rund ums Semesterticket kann die Bearbeitung einige Zeit in Anspruch nehmen. Wir bitten dafür um Verständnis.',
            'salutation' => "Viele Grüße  \nDein StuRa der BTU Cottbus-Senftenberg",
        ],

        'status_changed' => [
            'subject' => 'Statusänderung: Antrag auf Befreiung vom Semesterticket',
            'greeting' => 'Hallo :name!',
            'line_1' => 'Der Status deines Antrags auf Befreiung vom Semesterticket hat sich zu ":status" geändert.',
            'action' => 'Antrag ansehen',
            'line_2' => 'Du hast einen Monat Zeit gegen diesen Bescheid Einspruch zu erheben. Bitte wende dich bei Fragen an semesterticket@stura-btu.de',
            'line_3' => 'Wir bitten um Verständnis, dass wir aufgrund der hohen Anzahl an Anfragen und Anträgen rund ums Semesterticket nicht sofort auf jede Anfrage antworten können.',
            'salutation' => "Viele Grüße  \nDein StuRa der BTU Cottbus-Senftenberg",
        ],

        'one_time_link_created' => [
            'subject' => 'Anmeldelink zum StuRa BTU Antagsportal',
            'greeting' => 'Hallo :name!',
            'line_1' => 'Du hast einen Anmeldelink erhalten, um Anträge im Antragsportal des StuRa der BTU Cottbus-Senftenberg zu stellen..',
            'action' => 'Antrag stellen',
            'line_2' => 'Bitte beachte, dass dieser Link nur nur bis zum :expires_at gültig ist. Nach Ablauf der Frist musst du einen neuen Link bei office@stura-btu.de anfordern.',
            'salutation' => "Viele Grüße  \nDein StuRa der BTU Cottbus-Senftenberg",
        ],

    ],

];
