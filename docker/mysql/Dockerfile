FROM mysql:5.7

RUN ln -fs /usr/share/zoneinfo/Europe/Berlin /etc/localtime && dpkg-reconfigure -f noninteractive tzdata

COPY config/my.cnf /etc/mysql/conf.d/my.cnf
RUN chmod 0644 /etc/mysql/conf.d/my.cnf

#Если не запускается из-за ошибки записи в файл ... в папке /tmp
RUN chmod 1777 tmp/
RUN chown 1000:1000 tmp/