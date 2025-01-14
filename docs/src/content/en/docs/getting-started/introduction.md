---
title: "Introduction"
description: "Introduction."
lead: "Introduction."
date: 2020-10-13T15:21:01+02:00
lastmod: 2020-10-13T15:21:01+02:00
draft: false
images: []
menu:
  docs:
    parent: "getting-started"
weight: 10
toc: true
---

# Flow

## Why ?

Flow concept aims to solve

- Adopt asynchronous as native implementation
- Build your code with functional programming and monoids
- Assemble your code visually

## Installation

PHP 8.3 is the minimal version to use _Flow_
The recommended way to install it through [Composer](http://getcomposer.org/) and execute

```bash
composer require darkwood/flow
```

## Usage

A working script is available in the bundled `examples` directory

- Run Flow : `php examples/flow.php`
- Start Server : `php examples/server.php`
- Start Client(s) : `php examples/client.php`

Messaging part require to install [Docker](https://www.docker.com) and execute `docker-compose up -d`

## Documentation

[https://darkwood-com.github.io/flow](https://darkwood-com.github.io/flow)

## License

_Flow_ is released under the MIT License.
