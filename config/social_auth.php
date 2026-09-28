<?php

// OAuth credentials are environment-specific. Enable each provider only after
// configuring its client ID, secret, and callback URL with that provider.
return [
    'google' => ['enabled' => false, 'client_id' => '', 'client_secret' => ''],
    // Apple client_secret is a signed JWT and must be rotated before expiry.
    'apple' => ['enabled' => false, 'client_id' => '', 'client_secret' => ''],
    // 'consumers' accepts personal Outlook accounts. Use 'common' for both.
    'microsoft' => ['enabled' => false, 'client_id' => '', 'client_secret' => '', 'tenant' => 'consumers'],
];
