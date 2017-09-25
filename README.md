# Polidog.Todo

最新のコードはこっち[https://github.com/koriym/Polidog.Todo](https://github.com/koriym/Polidog.Todo)



[![Build Status](https://travis-ci.org/polidog/Polidog.Todo.svg?branch=master)](https://travis-ci.org/polidog/Polidog.Todo)

This is a "Todos" example app built on the principles described in the [Coding Guide](http://bearsunday.github.io/manuals/1.0/en/coding-guide.html).

## Getting Started
    git clone git@github.com:koriym/Polidog.Todo.git
    cd Polidog.Todo
    composer install
    composer setup
    COMPOSER_PROCESS_TIMEOUT=0 composer serve
    COMPOSER_PROCESS_TIMEOUT=0 composer serve-api // API + API doc server

Open http://127.0.0.1:8080/ for web page

## Web access with curl

Return 405 response when unavailable method is requested. 

```
curl -i -X DELETE http://127.0.0.1:8080/
```

```
HTTP/1.1 405 Method Not Allowed
Host: 127.0.0.1:8080
Date: Wed, 31 May 2017 23:09:17 +0200
content-type: application/json

{
    "message": "Method Not Allowed"
}
```

OPTIONS request supported ([RFC2616](https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.2))

> The OPTIONS method represents a request for information about the communication options available on the request/response chain identified by the Request-URI. This method allows the client to determine the options and/or requirements associated with a resource, or the capabilities of a server, without implying a resource action or initiating a resource retrieval.
 
```
curl -i -X OPTIONS http://127.0.0.1:8080/
```

```
HTTP/1.1 200 OK
Host: 127.0.0.1:8080
Date: Wed, 31 May 2017 23:04:50 +0200
Connection: close
X-Powered-By: PHP/7.1.4
Content-Type: application/json
Allow: GET, POST

{
    "GET": {
        "summary": "Todos list",
        "description": "Returns the todos list specified by status",
        "request": {
            "parameters": {
                "status": {
                    "type": "string",
                    "description": "todo status"
                }
            }
        }
    },
    "POST": {
        "summary": "Create todo",
        "description": "Create todo and add to todos list",
        "request": {
            "parameters": {
                "title": {
                    "type": "string",
                    "description": "todo title"
                }
            },
            "required": [
                "title"
            ]
        }
    }
}
```

[`Etag`](https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.19) and [`Last-modifed`](https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.29)  heaeder supported in @Cacheable GET request.

> The ETag response-header field provides the current value of the entity tag for the requested variant. 

> HTTP/1.1 servers SHOULD send Last-Modified whenever feasible.

```
curl -i http://127.0.0.1:8080/
```

```
HTTP/1.1 200 OK
Host: 127.0.0.1:8080
Date: Wed, 31 May 2017 23:19:49 +0200
Connection: close
X-Powered-By: PHP/7.1.4
ETag: 3652022809
Last-Modified: Wed, 31 May 2017 21:04:45 GMT
content-type: text/html; charset=utf-8

<!DOCTYPE html>
<html>
...
```

Return [`304`](https://tools.ietf.org/html/rfc7232#section-4.1) (Not modifed) response supported on conditional `GET` request. 

> The If-None-Match request-header field is used with a method to make it conditional. A client that has one or more entities previously obtained from the resource can verify that none of those entities is current by including a list of their associated entity tags in the If-None-Match header field. 

```
curl -i -H 'If-None-Match: 3652022809' http://127.0.0.1:8080/
```

```
HTTP/1.1 304 Not Modified
Host: 127.0.0.1:8080
Date: Wed, 31 May 2017 23:56:31 +0200
Connection: close
X-Powered-By: PHP/7.1.4
```
## Content-Negotiation for language

prefer English

```
curl -H 'Accept-Language: en' http://127.0.0.1:8080/
```

prefer Japanese

```
curl -H 'Accept-Language: ja' http://127.0.0.1:8080/
```

## Hypermedia API

The API is a **self documented** and **discoverable** RESTful HAL(Hyper Application Language) API. HAL is a format based on json that establishes conventions for representing links. For example:

```
{
    "_links": {
        "self": { "href": "/orders" },
        "next": { "href": "/orders?page=2" }
    }
}
```
More detail about HAL can be found at http://stateless.co/hal_specification.html.

Hypermedia API navigate around the resources by following links. Start by request the URI (/) of the route in the same way as the web site. 

```
curl -i http://127.0.0.1:8081/
```

```
HTTP/1.1 200 OK
Host: 127.0.0.1:8081
Date: Sat, 12 Aug 2017 11:57:05 +0200
Connection: close
X-Powered-By: PHP/7.1.4
content-type: application/hal+json

{
    "message": "Welcome to the Polidog.Todo API ! Our hope is to be as self-documenting and RESTful as possible.",
    "_links": {
        "self": {
            "href": "/"
        },
        "curies": [
            {
                "href": "http://localhost:8081/rels/{?rel}",
                "name": "pt",
                "templated": true
            }
        ],
        "pt:todo": {
            "href": "/todo",
            "title": "todo item"
        },
        "pt:todos": {
            "href": "/todos",
            "title": "todo list"
        }
    }
}

```

**CURIE** help providing links to resource documentation. It gives you a reserved link relation `curies` which you can use to hint at the location of resource documentation.

Links in turn can then prefix their `rel` with a CURIE name. Associating the `todo` link with the doc documentation CURIE results in a link `rel` set to `pt:todo`.

To retrieve documentation about the `todo` resource, the client will expand the associated CURIE link with the actual link's `rel`. This would result in a URL http://127.0.0.1:8081/rels/?rel=todo which is expected to return documentation about this resource.

Create TODO according to the document.

```
curl -i -X POST http://127.0.0.1:8081/todo -d "title=walking"
```

```
HTTP/1.1 201 Created
Host: 127.0.0.1:8081
Date: Sat, 12 Aug 2017 12:07:08 +0200
Connection: close
X-Powered-By: PHP/7.1.4
Location: /todo?id=2
Content-type: text/html; charset=UTF-8

{
    "todo": {
        "id": "2",
        "title": "walking",
        "status": "1",
        "created": "2017-08-12 12:07:08",
        "updated": "2017-08-12 12:07:08",
        "status_name": "Complete"
    },
    "_links": {
        "self": {
            "href": "/todo?id=2"
        }
    }
}
```

or by JSON (Reuqest condent-renogotiation with is default)

```
curl -i http://127.0.0.1:8081/todo -X POST -H 'Content-Type: application/json' -d '{"title":"think" }'
```

The API is fully compatible with [mikekelly/hal-browser](https://github.com/mikekelly/hal-browser) REST API browser.

![image](https://user-images.githubusercontent.com/529021/29247668-d00d403e-8044-11e7-9dff-65d5d98535b4.png)

## Content-Negotiation for API media type


prefer HAL+JSON API

```
curl -i -H 'Accept: application/hal+json' http://127.0.0.1:8081/todos
```

prefer JSON API

```
curl -i -H 'Accept: application/json' http://127.0.0.1:8081/todos
```

## Console acess

    composer web get /
    composer api options /

## QA

    composer test       // phpunit
    composer coverage   // test coverate
    composer cs         // lint
    composer cs-fix     // lint fix
    vendor/bin/phptest  // test + cs
    vendor/bin/phpbuild // phptest + doc + qa
   
## Deploy

Edit `bin/deploy/server.yml` for server setting.

    composer deploy-stage
    composer deploy-prod

## Optional modules:

  * [ray/aura-sql-module](https://github.com/ray-di/Ray.AuraSqlModule) - Extended PDO ([Aura.Sql](https://github.com/auraphp/Aura.Sql))
  * [ray/web-form-module](https://github.com/ray-di/Ray.WebFormModule) - Web form ([Aura.Input](https://github.com/auraphp/Aura.Input))
  * [madapaja/twig-module](https://github.com/madapaja/Madapaja.TwigModule) - Twig template engine
  * [koriym/now](https://github.com/koriym/Koriym.Now) - Current datetime
  * [koriym/query-locator](https://github.com/koriym/Koriym.QueryLocator) - SQL locator


![](/docs/bear.png)

## Prerequirests

  * php 7.1+

* The text of this README is partly taken from https://github.com/mikekelly/hal-browser, http://stateless.co/hal_specification.html and modified.
