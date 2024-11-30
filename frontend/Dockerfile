FROM mirror.gcr.io/node:20.15.0-buster-slim

WORKDIR /frontend

RUN npm install yarn

COPY [".", "."]

COPY ./docker-entrypoint.sh /docker-entrypoint.sh

RUN chmod +x /docker-entrypoint.sh
ENTRYPOINT ["/docker-entrypoint.sh"]