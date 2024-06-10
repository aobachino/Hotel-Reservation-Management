# Hotel-Reservation-Management
For sse group project
- Reservation
![alt text](https://github.com/WailanTirajoh/laravel_hotel/blob/main/example-b.png?raw=true)

- Dashboard
![alt text](https://github.com/WailanTirajoh/laravel_hotel/blob/main/example.png?raw=true)

- Login
![alt text](image.png)

- Register Customer User
![alt text](image-1.png)

## Instalation 

### Init DB
- Create DB Name: hotel_app
or via terminal
```
mysql -u root -p
```
enter your db credential
```
create database hotel_app;
exit;
```
### Init Commands:
```
cp .env.example .env // after that start filling credential at .env

composer install
npm install 
npm run dev
php artisan migrate:fresh --seed
php artisan serv                => Terminal 1
php artisan websockets:serv     => Terminal 2   //run the websocket server for realtime notification
```

### Development build
```
npm run dev
```

### Production Build
```
// run this on your terminal to generate production build
npm run build
```

### Login:
    Add user into "UserSeeder.php", and you can login email and password.

## System Flow
![image](https://github.com/aobachino/Hotel-Reservation-Management/assets/45359669/810323de-a248-45b9-a742-0cb8be082189)



## Entity Relationship Diagram
![alt text](https://github.com/WailanTirajoh/laravel_hotel/blob/main/erd.PNG?raw=true)
