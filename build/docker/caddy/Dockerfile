FROM caddy:alpine

RUN set -eux; \
	apk --update add --no-cache \
	ca-certificates \
	mailcap \
	libcap

ENV BUILD_DEPS="gettext"  \
    RUNTIME_DEPS="libintl"

RUN set -x && \
    apk add --update $RUNTIME_DEPS && \
    apk add --virtual build_deps $BUILD_DEPS &&  \
    apk add ca-certificates mailcap libcap && \
    cp /usr/bin/envsubst /usr/local/bin/envsubst && \
    apk del build_deps

RUN addgroup -S caddy && \
    adduser -D -S -s /sbin/nologin -G caddy caddy && \
    setcap cap_net_bind_service=+ep `readlink -f /usr/bin/caddy` && \
    /usr/bin/caddy version

COPY ./build/docker/caddy/conf/Caddyfile /etc/caddy/Caddyfile.conf

ARG PHP_UPSTREAM_CONTAINER=php
ARG PHP_UPSTREAM_PORT=8000

RUN envsubst '$$PHP_UPSTREAM_CONTAINER $$PHP_UPSTREAM_PORT' < /etc/caddy/Caddyfile.conf > /etc/caddy/Caddyfile

RUN mkdir -p /var/www/app && chown -R caddy:caddy /var/www/app
COPY ./app/public /var/www/app/public

USER caddy

WORKDIR /var/www/app

CMD ["caddy", "run", "--config", "/etc/caddy/Caddyfile"]
