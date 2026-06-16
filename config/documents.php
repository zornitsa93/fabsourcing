<?php
return [
    // Master switch for the whole Documents/Téléchargements feature. When false, the public
    // pages (register/login/documents) return 404 and the footer link is hidden. Admin
    // management stays available so documents can be prepared before going live.
    'enabled' => env('DOCUMENTS_ENABLED', false),

    // When false, registration/approval emails are NOT sent (code is ready; flip after SMTP is set up).
    'notifications_enabled' => env('DOCUMENTS_MAIL_ENABLED', false),
    // Admin recipient for "new pending account" notifications.
    'admin_email' => env('DOCUMENTS_ADMIN_EMAIL', 'thierry.sudol@fab-sourcing.fr'),
    'max_upload_kb' => 20480, // 20 MB
];
