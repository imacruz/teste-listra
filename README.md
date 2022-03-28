# Teste Listra
### Simulador de Financiamento
Ambiente utilizando [Laravel Sail](https://laravel.com/docs/8.x/sail#main-content)
#### Passos para funcionamento
1. Clone este repositório 
2. Acesse a pasta
3. Execute o comando abaixo para instalar as dependências:
    <br>
    ```
    docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
    ```
    <br>
4. Dentro no container `teste-listra-laravel.test-1` execute os seguintes comandos:
    <br>
    ```
    > php artisan migrate && php artisan db:seed
    > npm install && npm run dev
    ```


