Simple Guide to accessing Donlisa Recharge and bills payment Apis

// register new user using POST request

``` bash
donlisa.com/api/register

-name
-email
-phone
-password
```

// login a new user using POST request
``` bash
donlisa.com/api/login

-email(this field can be email or phone but end the value with the {email} property)
-password
```

// check authenticated user
``` bash 
donlisa.com/api/user

headers
Authorization = Bearer token

where token is the user unique token generated when they login
```