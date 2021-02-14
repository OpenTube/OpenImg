# OpenImg
Simple php image upload and hosting service

### setup server

    sqlite3 users.db < table.sql
    php -S localhost:8080


### upload image

    curl -F 'file=@image.png;type=image/png' http://localhost:8080/upload.php

