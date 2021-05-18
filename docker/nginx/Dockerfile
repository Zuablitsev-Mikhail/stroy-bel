FROM alpine:3.10

RUN apk add --update --no-cache \
    nginx

# copy modified nginx config
RUN mkdir /var/www/ssl/

COPY ./certs/* /var/www/ssl/

COPY ./conf/nginx.conf /etc/nginx/
COPY ./conf/bcp.conf /etc/nginx/conf.d/
COPY ./conf/upstream.conf /etc/nginx/conf.d/

# run nginx
CMD ["nginx", "-g", "daemon off;"]

RUN mkdir /var/cache/nginx

RUN adduser -D -g '' -G www-data www-data \
    && chown -R www-data:www-data /var/lib/nginx \
    && chown -R www-data:www-data /var/www \
    && chown -R www-data:www-data /var/www/ssl \
    && chown -R www-data:www-data /var/tmp/nginx

EXPOSE 8080
