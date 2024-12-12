FROM centos:7

# Installer Apache, PHP et les modules nécessaires
RUN yum -y update && \
    yum -y install httpd epel-release && \
    yum -y install https://rpms.remirepo.net/enterprise/remi-release-7.rpm && \
    yum -y install yum-utils && \
    yum-config-manager --enable remi-php56 && \
    yum -y install php php-mysqli mod_ssl mod_auth_openidc && \
    yum clean all

# Copier la configuration Apache
COPY conf/apache.conf /etc/httpd/conf.d/default.conf

# Exposer le port 80
EXPOSE 80

# Lancer Apache
CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]
