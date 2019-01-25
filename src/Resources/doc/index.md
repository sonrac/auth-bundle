Bundle configuration:

```
sonrac_oauth:
    repository:
        access_token:
        auth_code:
        client:
        refresh_token:
        scope:
        user:
    grant_types:
        authorization_code:
        client_credentials:
        implicit:
        password:
        refresh_token:
    tokens_ttl:
        auth_code:
        access_token:
        refresh_token:
    keys:
        encryption:
        pair:
            path:
            private_key_name:
            public_key_name:
            passphrase:
    default_scopes:
    swagger_constants
```

Security configuration:

```
sonrac_oauth:
    authorization_validator:
    scope_validator:
    paths:
        authorization:
        token:
```