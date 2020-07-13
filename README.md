# insly-back

## Project setup
```
composer install
composer update
```

### Compiles and hot-reloads for development
```
php artisan serve
```


### Context
Api Rest developed using the Laravel framework, with OOP and MVC concepts, where View is in charge of the front application, which accesses the api's methods through routes. The default route when the api server is online is (http: // localhost: 8000 /).
The following were developed:
Models:

#### Models
* Calc.php
* CalculateInsurance.php
* CalculateIntalment.php

#### Controllers:
* CalcController.php

The 'make-calc' route was implemented in the api.php file in "../insly-back/routes/api.php" which accesses the makeCalc method in CalcController.php.
This method validates the form (good practice) and uses the methods in the Models to perform the value calculation.
The logic regarding the calculation is in the models (good practices).
The class diagram follows the following logic:
Calc has a CalculateInsurance, which has a set of CalculateIntalment.
