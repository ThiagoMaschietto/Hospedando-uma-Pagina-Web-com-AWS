#Atualiza o gerenciador de pacotes apt.
sudo apt upgrade -y

#Instala o php-mysqli,php-json e php-devel
sudo apt-get install php8.2-mysql
sudo apt install  wget php8.3-cli php libapache2-mod-php

#Adicionamos o repositório do php-fpm
sudo add-apt-repository ppa:ondrej/php
sudo apt update

#Instalamos o php-fpm
sudo apt install -y wget php-fpm

#Adicionamos o repositório do mariadb
sudo add-apt-repository universe
sudo apt update

#Instalamos o mariadb
sudo apt install mariadb-server mariadb-client

#Iniciamos o servidor apache
sudo systemctl start apache2
sudo systemctl enable apache2

#Verificamos se esta enabled
sudo systemctl is-enabled apache2

#Na pagina Web Caso não funcione troque o protocolo na url por HTTP
#A Imagem a ser mostrada deve ser algo parecido com isso

CONFIGURAÇÃO DA INSTANCIA NO AWS
#Criamos o Grupo Apache
sudo addgroup apache

#Adicionamos o Usuário ao grupo do apache
sudo usermod -a -G apache ubuntu

#Verificamos 
groups ubuntu

#Alteramos o Proprietário do diretório /var/www e seu conteúdo para o usuário ubuntu e grupo apache
sudo chown -R ubuntu:apache /var/www

#Para definirmos as permissões os comandos são iguais ao do amazon Linux
sudo chmod 2775 /var/www && find /var/www -type d -exec sudo chmod 2775 {} \;
find /var/www -type f -exec sudo chmod 0664 {} \;

#Iniciamos o banco de dados, novamente igual a configuração do amazon linux
sudo systemctl start mariadb
sudo mysql_secure_installation
sudo systemctl enable mariadb

#Habilitamos e Instalamos o ssl
sudo apt install apache2 openssl
sudo a2enmod ssl
systemctl restart apache2

#Utilizamos o comando para criar a chave privada e o certificado nas pastas criadas e reiniciamos o apache
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/apache-selfsigned.key -out /etc/ssl/certs/apache-selfsigned.crt
sudo systemctl restart apache2

#Agora temos que mudar no arquivo de config do apache2 para que ele leia o nosso arquivo de chave privada e o nosso arquivo de certificado
sudo nano /etc/apache2/sites-available/default-ssl.conf

#No arquivo Mude Esses dois parametros para esses dois valores:
SSLCertificateFile /etc/ssl/certs/apache-selfsigned.crt
SSLCertificateKeyFile /etc/ssl/private/apache-selfsigned.key 
