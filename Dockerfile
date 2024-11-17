# Usando a imagem oficial do PHP com o Apache
FROM php:8.1-apache

# Instalando dependências do sistema
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git libxml2-dev libicu-dev

# Instalando as extensões do PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql xml intl

# Habilitando mod_rewrite para o Apache
RUN a2enmod rewrite

# Definindo o diretório de trabalho
WORKDIR /var/www/html

# Copiando os arquivos da aplicação para o container
COPY . .

# Instalando as dependências do Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Instalando as dependências do Laravel
RUN composer install

# Expondo a porta 8000
EXPOSE 8000

# Iniciando o servidor PHP
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
