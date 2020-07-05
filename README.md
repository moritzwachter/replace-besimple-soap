Replacing BeSimpleSoapBundle
========================

I wrote a blog post about how I replaced BeSimpleSoapBundle by a native PHP implementation. 
The last maintained Symfony version for `besimple/soap` is Symfony 2.8. This is why this repository 
looks a bit old-fashioned for the year 2020. This is a showcase how I moved from the original (exemplary) implementation
to handling SOAP without extra dependencies. 

How to install
--------------
Pull the repo and run 
```bash
docker-compose run --rm php-cli
```
and install the composer dependencies.

To access the webservices from your browser, visit http://app.localtest.me.

What's inside?
--------------
You'll find two controllers inside the AppBundle which are running with `BeSimpleSoapBundle`. They execute some sample 
code which is mostly faked to avoid bloating the repository with irrelevant code.
Additionally, there are two test classes with two SOAP clients, so you can test the functionality of the SOAP services 
by running `bin/phpunit`.
