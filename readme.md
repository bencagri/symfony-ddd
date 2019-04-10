<p align="center"><img src="http://oi66.tinypic.com/296gq6h.jpg"></p>

### Symfony 4 DDD Approach

Article application with Symfony 4 and DDD Approach. 

Advantages of Domain-Driven Design

* Eases Communication: With an early emphasis on establishing a common and ubiquitous language related to the domain model of the project, teams will often find communication throughout the entire development life cycle to be much easier. Typically, DDD will require less technical jargon when discussing aspects of the application, since the ubiquitous language established early on will likely define simpler terms to refer to those more technical aspects.
* Improves Flexibility: Since DDD is so heavily based around the concepts of object-oriented analysis and design, nearly everything within the domain model will be based on an object and will, therefore, be quite modular and encapsulated. This allows for various components, or even the entire system as a whole, to be altered and improved on a regular, continuous basis.
* Emphasizes Domain Over Interface: Since DDD is the practice of building around the concepts of domain and what the domain experts within the project advise, DDD will often produce applications that are accurately suited for and representative of the domain at hand, as opposed to those applications which emphasize the UI/UX first and foremost. While an obvious balance is required, the focus on domain means that a DDD approach can produce a product that resonates well with the audience associated with that domain.


[Read more about DDD](https://airbrake.io/blog/software-design/domain-driven-design)

#### Folder Structure

```
├── bin
├── config
│   ├── packages
│   │   ├── dev
│   │   ├── prod
│   │   └── test
│   └── routes
│       └── dev
├── public
├── src
│   ├── Authorization
│   │   ├── Console
│   │   ├── Controller
│   │   └── Entity
│   │       └── Oauth2
│   ├── DataFixtures
│   ├── Project
│   │   ├── App
│   │   │   ├── EventListener
│   │   │   ├── Interface
│   │   │   ├── Support
│   │   │   └── Trait
│   │   ├── Console
│   │   ├── Domain
│   │   │   ├── Article
│   │   │   │   └── Entity
│   │   │   └── User
│   │   │       ├── Contract
│   │   │       └── Entity
│   │   ├── Http
│   │   │   └── Controller
│   │   ├── Infrastructure
│   │   │   ├── Article
│   │   │   └── User
│   │   └── Resources
│   │       ├── config
│   │       ├── doctrine
│   │       │   └── mapping
│   │       └── routing
├── templates
├── tests
│   ├── functional
│   ├── integration
│   └── unit
├── translations
└── var
    ├── cache
    └── log
```



#### Lets make it work 

```bash
git clone https://github.com/bencagri/symfony4-ddd-skeleton.git
cd symfony4-ddd-skeleton
composer install
bin/console doctrine:database:create
bin/console doctrine:schema:update --force
bin/console doctrine:fixtures:load 
php -S 127.0.0.1:9002 -t public

```

then visit `127.0.0.1:9002/api/doc`  you should see the documentation now.

### Making Request
When you try to make a request you will get an error. Because to make a request you need `Access Token`.

Lets try making a request;
```bash
curl -X GET "http://127.0.0.1:9002/api/articles?page=1&per_page=10" -H "accept: application/json"
```

It should say;
```bash
{
  "success": false,
  "error": {
    "code": 0,
    "message": "A Token was not found in the TokenStorage.",
    
    ...
```
This project is using Oauth2. So, first you need is to create a client. For this, it also has a command to create a oauth client.

```bash
bin/console oauth:client:create 
``` 

you should see something like;
```
Added a new client with  public id 6_2x0l2r8t6e2o4sggww08wwk88gs8sggsog0wk8cow4w0gso0s0.
```

Go and check your `oauth_client` table on database. you will see your secret also. 
Basically we have `Authorization code`, `Password`, `Client Credentials` and `Refresh Token` grant types. You can read more on [Oauth Grant Types](https://oauth.net/2/grant-types/).

In this case, I prefer to go with `Client Credentials` grant type. For this, I need `client_id` and `client_secret`

I already know client id, it produced a client and told me the ID when I run the command. And my secret is on database.

So, lets take an `Access Token` to make a request to api.

```bash

curl -X GET \
  'http://127.0.0.1:9002/oauth/v2/token?client_id=6_2x0l2r8t6e2o4sggww08wwk88gs8sggsog0wk8cow4w0gso0s0&client_secret=4biwdp70w2yog4gs0s0c0808kww0c88sowoggggsg0swk48w08&grant_type=client_credentials' \
  -H 'cache-control: no-cache' \
  -H 'content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW' \
  -F client_id=1_4v0w0kiec9s0osgs8w40og8o4okc4ws08cos84c0gw4csoc8ws \
  -F client_secret=4biwdp70w2yog4gs0s0c0808kww0c88sowoggggsg0swk48w08 \
  -F grant_type=client_credentials
  
```

And The Response
```bash
{
    "access_token": "OWUyMTMxYzJjN2I5Nzg0ZTQ1N2NlZDNkMWYxZjFiZGE5N2RjMTA4ZmI1ZTU4ZGE0YWI4NmU3YmQxZjgyNTJkZg",
    "expires_in": 3600,
    "token_type": "bearer",
    "scope": null
}
```

great. I have an access token to make a request.

lets try to get an article now.

```bash 
curl -X GET \
  http://127.0.0.1:9002/api/article/1 \
  -H 'authorization: Bearer OWUyMTMxYzJjN2I5Nzg0ZTQ1N2NlZDNkMWYxZjFiZGE5N2RjMTA4ZmI1ZTU4ZGE0YWI4NmU3YmQxZjgyNTJkZg' \
  -H 'cache-control: no-cache' \
  -H 'content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW' \
  -H 'postman-token: 8635975a-6197-b8c6-c055-907a4668c503' \
  -F client_id=1_4v0w0kiec9s0osgs8w40og8o4okc4ws08cos84c0gw4csoc8ws \
  -F client_secret=4biwdp70w2yog4gs0s0c0808kww0c88sowoggggsg0swk48w08 \
  -F grant_type=client_credentials
```

And the response is;
```bash
{
    "success": true,
    "data": {
        "type": "article",
        "id": "1",
        "attributes": {
            "title": "My Test Article 0",
            "body": "lorem ipsum dolor sit amet.lorem ipsum dolor sit amet.lorem ipsum dolor sit amet.lorem ipsum dolor sit amet.lorem ipsum dolor sit amet.lorem ipsum dolor sit amet.lorem ipsum dolor sit amet.lorem ipsum dolor sit amet.lorem ipsum dolor sit amet.lorem ipsum dolor sit amet.",
            "tags": {},
            "createdAt": "2018-03-29"
        },
        "links": {
            "self": "http://127.0.0.1:9002/api/article/1"
        }
    }
}
```

Great, I got the article information with a request to `GET /api/article/1`

This package was built around [JSON API](http://jsonapi.org/) to take advantage of its features around efficiently caching responses, sometimes eliminating network requests entirely.

#### Packages
- Symfony Flex
- Doctrine ORM Bundle
- Doctrine Fixtures
- Twig Bundle
- Fos Oauth Server Bundle
- Nelmio Doc Bundle
- Symfony Profiler (dev)
- Framework Extra Bundle
- Fractal (PhpLeague)
- PagerFanta for Doctrine