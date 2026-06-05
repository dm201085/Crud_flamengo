# Usa a imagem oficial do PHP com Apache
FROM php:8.0-apache

# Habilita extensões do banco de dados (necessário para conectar com o MySQL)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Opcional: Ativa o mod_rewrite do Apache se você for usar rotas
RUN a2enmod rewrite