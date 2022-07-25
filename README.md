### arco-design-pro-vite 后台

> 缓存使用redis,队列使用rabbitmq

### 队列

```angular2html
php artisan queue:work --queue=sms,pusher,default
```

### soketi docker 配置:docker-compose.yml

```yml
version: '3'
services:
    soketi:
        image: 'quay.io/soketi/soketi:latest-16-alpine'
        environment:
            SOKETI_DEBUG: '1'
            SOKETI_METRICS_SERVER_PORT: '9601'
            DEBUG: '1'
            SOKETI_DEFAULT_APP_ID: '845237'
            SOKETI_DEFAULT_APP_KEY: '8B9SPpgntbv2T6Fo'
            SOKETI_DEFAULT_APP_SECRET: 'PdULBP8C75vSfAUL'
        ports:
            - '${SOKETI_PORT:-6001}:6001'
            - '${SOKETI_METRICS_SERVER_PORT:-9601}:9601'
        networks:
            - sail

networks:
    sail:
        driver: bridge
```

### nginx 反代ws

```shell
    proxy_pass http://127.0.0.1:6001;
    proxy_set_header Host $host;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header REMOTE-HOST $remote_addr;
```

### laravel配置

```dotenv
PUSHER_APP_ID=845237
PUSHER_APP_KEY=8B9SPpgntbv2T6Fo
PUSHER_APP_SECRET=PdULBP8C75vSfAUL
PUSHER_HOST=pusher.demo.com
PUSHER_PORT=443
PUSHER_SCHEME=https
```
