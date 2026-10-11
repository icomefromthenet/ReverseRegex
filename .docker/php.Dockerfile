ARG BASE=php:7.4-cli-alpine
FROM $BASE

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apk add --no-cache python3 git

RUN addgroup -g 1000 appuser && \
    adduser -D -u 1000 -G appuser -h /home/appuser appuser

USER 1000:1000
