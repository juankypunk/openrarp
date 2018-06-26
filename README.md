# openrarp
<img src="images/openrarp_logo_large.png" width="150px" alt="openrarp logo"/>

## Open Residential Area Resource Planning
A web application to manage the payment of fees in a neighborhood community. Reading of water meters, statistics of water consumption and billing. Historical of readings. Creation of remittances (SEPA direct debit files). CSV exports. Spanish language.

## Previous requirements
- Apache2 Web Server
- PostgreSQL Server
- PHP7 and PHP7 modules:
  - libapache2-mod-php7.0
  - php7.0-common
  - php7.0-pgsql
  - php7.0-json

## HowTo Install
Create the openrarp database:

```sh
$ su postgres
$ createdb openrarp
$ psql -e -d openrarp < openrarp.sql
```

Copy the openrarp directory into the DOCUMENT_ROOT

Rename the file 'config-default.php' to 'config.php' and edit the file to suit your needs

Open a browser and enter the following url: 
http://localhost/openrarp

Email: admin@myopenrarp.com

Passwd: openrarp

## ToDo
- Add new neighbour
- Add new water meter
- Setup dialog
- Change user password
