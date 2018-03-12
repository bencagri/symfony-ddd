### Symfony 4 DDD Approach

Domain Driven Design Skeleton for Symfony 4.

I created this repository to create DDD Applications with Symfony faster.

#### Packages
- Symfony Flex
- Doctrine ORM Bundle
- Twig Bundle
- Fos Oauth Server Bundle
- Nelmio Doc Bundle
- Symfony Profiler

#### how to? 

``` 
git clone https://github.com/bencagri/symfony4-ddd-skeleton.git
cd symfony4-ddd-skeleton
bin/console doctrine:schema:update --force
php -S 127.0.0.1:9002 -t public

```

then visit `127.0.0.1:9002/api/doc` 
