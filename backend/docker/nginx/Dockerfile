FROM mirror.gcr.io/nginx:1.23.4

RUN apt-get update && apt-get install -y gettext

WORKDIR /nginx

COPY [".", "."]

RUN set -a && \
    . /nginx/.env && \
    set +a && \
    mkdir -p /etc/nginx/conf.d && \
    envsubst '${SERVER_NAME}' < /nginx/conf/default.conf > /etc/nginx/conf.d/default.conf