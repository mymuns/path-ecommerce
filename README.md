# Kurulum

## Docker kurulumu
```
docker/docker-compose up -d
```

## Symfony kurulumu
```
composer install 
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```
## Komutlar

### Üye ekleme
```
php bin/console add-user [username] [password]
```
### Ürün ekleme
```
php bin/console add-product [name] [price] [description]
```


## Dökümantasyon

### Customer
Make things easier for your teammates with a complete folder description.
```
Authorization
Bearer Token
Token
GET
```
### Get My Order
http://localhost:8000//customer/order
---
Make things easier for your teammates with a complete request description.
```
Authorization
Bearer Token
Token
POST
```

### Create Order
http://localhost:8000//customer/create
---
Make things easier for your teammates with a complete request description.
```
Authorization
Bearer Token
Token
Bodyraw (json)
```
```
json
{
"productId": 1,
"quantity": 5,
"address": "deneme"
}
```

### Update Order
http://localhost:8000//customer/update/29
---
Make things easier for your teammates with a complete request description.
```
Authorization
Bearer Token
Token
Bodyraw (text)
```
json
{
"productId": 1,
"quantity": 4,
"address": "deneme"
}

### Auth Login
http://localhost:8000//{{token_path}}
---
Make things easier for your teammates with a complete request description.
Bodyraw (json)
```
json
{
"username": "musteri1",
"password": "123456"
}
```


