### EasyLearning - online platform for studying

To add description


### Development
For Developers

#### How to run using ```docker compose```
1. Ensure docker compose is set up on your device: <br>
```docker compose version ```
2. Go to project files. Change database host to mysql container name in file db.php
    ```   
    private $host = 'easylearning-mysqldb-1';
    ```
3. Run docker compose <br>
```docker compose up```