<?php
return [
    // When false, registration/approval emails are NOT sent (code is ready; flip after SMTP is set up).
    'notifications_enabled' => env('DOCUMENTS_MAIL_ENABLED', false),
    // Admin recipient for "new pending account" notifications.
    'admin_email' => env('DOCUMENTS_ADMIN_EMAIL', 'tsudol.fabtec@yahoo.com'),
    'max_upload_kb' => 20480, // 20 MB
];
