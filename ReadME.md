A web-sided server browser for the game [Team Fortress 2](https://www.teamfortress.com/). I made this back in 2015/2016 when I was in High School and had the domain `browser.tf` up until mid-2022. Unfortunately, I couldn't afford the domain at the time and lost it. Afterwards, I tried getting it back, but another company had already taken it. Apparently the new company is selling the domain, but they won't reply to anything I send them.

This website was actively used by quite a few people in the past!

The website is currently up on [btf.bestservers.io](https://btf.bestservers.io). The domain `bestservers.io` will be used for a new, modern, and global server browser in the future that'll support multiple games and much more!

**Warning** - I no longer support this project and the code is quite outdated, but simple at the same time.

## Deploying
### Docker Compose
The website's Docker Compose config may found below.

```yaml
version: '3.9'
services:
  nginx:
    image: nginx:latest
    ports:
      - 8001:80
    volumes:
      - ./app:/app
      - ./nginx.conf:/etc/nginx/conf.d/default.conf
    links:
      - php
  php:
    image: php:8-fpm
    volumes:
      - ./app:/app
```

The NGINX config copied into the NGINX Docker container may be found below.

```
server {
    index index.php;
    root /app;

    server_name btf.browser.tf;

    error_log /var/log/nginx/error.log;
    access_log off;
    
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
}
```

You will need to create another web server config (e.g. NGINX) on the host machine redirecting to port `8001` using the above configuration.

## Credits
* [Christian Deacon](https://github.com/gamemann)
* [Dr. McKay](http://steamcommunity.com/id/DoctorMcKay/)
