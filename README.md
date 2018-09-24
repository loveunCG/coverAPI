# EasyCover App server

## This is job managerment platform using Laravel Framework with JWT, FCM, TWILLO and Reactjs(for admin portal)

This is api server and admin portal for easycover app 

[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)](https://github.com/loveunCG/coverAPI.git)


## installation 

 - composer install

 - php artisan key:generate
 (before do this, create .env file from .env.example file then set server config such as database account and paypal, jwt, FCM account, Twillo account etc.. ).
 
 - php artisan migrate

 - php artisan storage:link
 
 - php artisan db:seed
 
 - install nodejs (recommanded version is 8.11.2)
 - npm i -save
 - npm run watch for development(npm run production for production)

 - run server (for development) php artisan serve.
 

 Note:
  please set document root as project root dirctory (no public folder)
  if you want change this, please change parameter of assets function.
  like this
  ```html
  <script type="text/javascript" src="{{asset('public/js/jquery.min.js')}}"></script>

  ```
  change to

 ```html
  <script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>

  ```

## License

This Project is open-sourced software licensed under the MIT license.
