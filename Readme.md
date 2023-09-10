## Simple API ##
This is a simple API for saving messages in JSON format to a file.


### Docker install ###

```
docker compose up -d
docker exec -u root -it api-php-fpm /bin/bash
composer install
npm install
yarn install
yarn build
```

### API Examples ###

If you want to test the API, use the `api.http` file, there are examples of saving, reading a list of saved files and a single file.

In addition, on the list of saved files, the possibility to sort by `UUID` and file `createAt`, descending and ascending, has been added.


.......

### Pages view on VUEJS ###

* http://localhost (listed added files and possibility to view details)
* http://localhost/add-message (add new message) 

....