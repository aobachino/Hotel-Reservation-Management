# Hotel-Reservation-Management - For sse group project
- Reservation
![alt text](https://github.com/WailanTirajoh/laravel_hotel/blob/main/example-b.png?raw=true)

- Dashboard
![alt text](https://github.com/WailanTirajoh/laravel_hotel/blob/main/example.png?raw=true)

- Login
![alt text](image.png)

- Login - failed
  ![image](https://github.com/aobachino/Hotel-Reservation-Management/assets/45359669/e9440d7c-b5fe-480b-af9d-64a3cd5b1299)

- Register Customer User
    ![image](https://github.com/aobachino/Hotel-Reservation-Management/assets/45359669/15eeb940-e2e8-4926-af17-eb03cb7fe6f0)

- Register Varidation
  ![image](https://github.com/aobachino/Hotel-Reservation-Management/assets/45359669/42b279ba-1e5b-4cb3-89a3-f55fcea9aff3)
  ![image](https://github.com/aobachino/Hotel-Reservation-Management/assets/45359669/3c3466ca-d21b-4604-990a-a70473072cd4)
  ![image](https://github.com/aobachino/Hotel-Reservation-Management/assets/45359669/66a20699-30d5-4733-840d-3d8c3b4f7f88)



## Instalation 

### Prerequisites
- Docker: [Install Docker](https://docs.docker.com/engine/install/)

### Build and Run with Docker Compose First time
```
docker-compose up -d --build
```

### Access
- [http://localhost:8000/login](http://localhost:8000/login)


### Login:
 - username: chino@graduate.utm.my
 - password: aoba    

### Run from next time
```
docker-compose up -d (back ground)
```
or 
```
docker-compose up
```

### Stop
```
docker-compose stop
```

### Remove Container
```
docker-compose down
```

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
