## DDD & Event Sourcing in PHP using Broadway, SimpleBus and Symfony 6

### Generate the SSL keys:

```
mkdir -p config/jwt
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

```mermaid

```
