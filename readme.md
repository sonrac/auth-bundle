[![Build Status](https://travis-ci.org/sonrac/auth-bundle.svg?branch=master)](https://travis-ci.org/sonrac/auth-bundle) 
[![StyleCI](https://styleci.io/repos/138038395/shield?branch=master&style=flat)](https://styleci.io/repos/138038395)

[![codecov](https://codecov.io/gh/sonrac/auth-bundle/branch/master/graph/badge.svg)](https://codecov.io/gh/sonrac/auth-bundle)

![Scrutinizer Build](https://scrutinizer-ci.com/g/sonrac/auth-bundle/badges/build.png?b=master)
![Scrutinizer](https://scrutinizer-ci.com/g/sonrac/auth-bundle/badges/quality-score.png?b=master)
![Scrutinizer Code Coverage](https://scrutinizer-ci.com/g/sonrac/auth-bundle/badges/coverage.png?b=master)
[![codecov](https://codecov.io/gh/sonrac/auth-bundle/branch/master/graph/badge.svg)](https://codecov.io/gh/sonrac/auth-bundle)
![Packagist](https://poser.pugx.org/sonrac/auth-bundle/v/stable.svg)
[![Latest Unstable Version](https://poser.pugx.org/sonrac/auth-bundle/v/unstable)](https://packagist.org/packages/sonrac/auth-bundle)
![License](https://poser.pugx.org/sonrac/auth-bundle/license.svg)
[![Total Downloads](https://poser.pugx.org/sonrac/auth-bundle/downloads)](https://packagist.org/packages/sonrac/auth-bundle)
[![Monthly Downloads](https://poser.pugx.org/sonrac/auth-bundle/d/monthly)](https://packagist.org/packages/sonrac/auth-bundle)
[![Daily Downloads](https://poser.pugx.org/sonrac/auth-bundle/d/daily)](https://packagist.org/packages/sonrac/auth-bundle)
[![composer.lock](https://poser.pugx.org/sonrac/auth-bundle/composerlock)](https://packagist.org/packages/sonrac/auth-bundle)

Symfony oauth2 bundle based on [league oauth2 server](https://oauth2.thephpleague.com)

# Installation

With orm:

```bash
composer require sonrac/auth-bundle:^orm-1.0
```

Without orm dependency:

```bash
composer require sonrac/auth-bundle:^1.0
```

# Run locally from package

* Test application are located in tests/App


## Run from docker with traefik

*  [Read this for setting up traefik and dev host](https://github.com/sonrac/instructions/blob/master/TRAEFIK.MD)

* Deploy containers to swarm

```bash
docker stack deploy -c docker-compose.yml auth-bundle
```

* [Open local application with bundle for test](http://auth.devinf)


TODO:
1. security: add authentication entry point
2. clear-tokens-command: refactor and extend bundle repository interfaces
3. User repository bridge: add optional validation for grant type and client.
4. Scope repository bridge: implement finalizeScopes.
5. security-authorization-handler: implement(low priority)
6. add documentation for bundle configuration
7. AuthorizeTest - restore commented 
8. oauth path config: check if needed
9. check if logout handler is needed with revoke token
