### Symfony 4 DDD Approach

Domain Driven Design Skeleton for Symfony 4.

I created this repository to create DDD Applications with Symfony faster.

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

#### how to? 

``` 
git clone https://github.com/bencagri/symfony4-ddd-skeleton.git
cd symfony4-ddd-skeleton
bin/console doctrine:schema:update --force
bin/console doctrine:fixtures:load 
php -S 127.0.0.1:9002 -t public

```

then visit `127.0.0.1:9002/api/doc` 
