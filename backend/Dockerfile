# Usamos uma imagem base com PHP 8.2 e Composer já instalados
FROM laravelsail/php82-composer:latest

# Define o diretório de trabalho
WORKDIR /var/www/html

# Instala extensões PHP necessárias para o Laravel e Websockets
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_mysql zip sockets

# Copia o código da aplicação para o diretório de trabalho
COPY . .

# Instala as dependências do Composer
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist

# Define a permissão de escrita para as pastas de storage e cache do Laravel.
# Isso corrige o erro 'ERR_EMPTY_RESPONSE' automaticamente durante a construção da imagem.
RUN chmod -R 777 storage bootstrap/cache

# Expõe a porta 8000 onde o 'artisan serve' vai rodar
EXPOSE 8000

# O comando para iniciar o servidor do Laravel.
# O '--host=0.0.0.0' é crucial para que o servidor seja acessível de fora do container.
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]