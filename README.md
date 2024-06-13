# Hotel-Reservation-Management
For sse group project
- Reservation
![alt text](https://github.com/WailanTirajoh/laravel_hotel/blob/main/example-b.png?raw=true)

- Dashboard
![alt text](https://github.com/WailanTirajoh/laravel_hotel/blob/main/example.png?raw=true)

- Login
![alt text](image.png)

- Login - failed
 <img width="770" alt="image" src="https://github.com/aobachino/Hotel-Reservation-Management/assets/45359669/e9440d7c-b5fe-480b-af9d-64a3cd5b1299">

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

## Static code analysis:
To conduct code analysis, SonarQube was chosen as our tool of choice. It offers comprehensive insights into software quality, enabling early detection and resolution of potential issues. With its ability to assess reliability, maintainability, security, and test coverage, SonarQube streamlines code review processes and prioritizes areas for improvement.
 
### Figure 3 SonarQube dashboard
![image](https://github.com/aobachino/Hotel-Reservation-Management/assets/45359669/8635827c-c918-4ae6-bf66-2a78eeb075a0)

- Quality Gate Status: The dashboard shows that the Quality Gate Status has “Passed”, suggesting that the analyzed code meets certain predefined criteria for quality.
- Metrics: There are four circular indicators representing different aspects of the code:
  - Security: 0 open issues is indicating no issues found.
  - Reliability: 78 open issues are indicating some concerns.
  - Maintainability: 93 open issues are indicating some concerns.
  - Coverage: 0.0% with a red open circle indicating no coverage data available.
- Additional Metrics: Below these indicators, there are additional metrics:
  - Duplications: 5.0%, represented by two overlapping squares icon.
  - Debt Ratio: 0.992%, represented by a clock icon.
  - Security Hotspots: 3 issues found.
 
### Figure 4  Security Issue 1
![image](https://github.com/aobachino/Hotel-Reservation-Management/assets/45359669/86609670-19c1-4a49-abb8-2e01e0359fd8)

- Security Hotspots: The highlighted issue relates to Cross-Origin Resource Sharing (CORS) policy and ensuring resource integrity. CORS is a mechanism that enables the request of various web page resources, such as fonts and JavaScript, from a domain outside its origin domain.
- Priority: The issue has been marked with a low review priority.

 
### Figure 5 Security Issue 2
![image](https://github.com/aobachino/Hotel-Reservation-Management/assets/45359669/d04a95e8-3c12-4bdc-9540-c75c4af16bd7)

- Security Hotspots: The highlighted issue relates to that not using resource integrity feature. Resource integrity is a security feature that ensures that the resources fetched by your website are delivered without unexpected manipulation.
- Priority: The issue has been marked with a low review priority.
