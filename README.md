1) baixando a aplicação

git clone https://github.com/FelipeFadel/Trabalho2DevOps1.git

2) Criar o container do banco de dados

- Subir o container usando:

docker container run -d -p 3306:3306 -e MYSQL_ROOT_PASSWORD=hadiobanco123 --name db -v ./DevOpsTrabalhoUm/produtos.sql:/docker-entrypoint-initdb.d/produtos.sql mysql:8.0-debian

- Para acessar o banco de dados, usar:

docker container exec -it db mysql -u hadiouser -p
(Com a senha proposta no produtos.sql -> user123)

3) Criar um container para o servidor Web

- Subir o container usando uma imagem com apache no php:

docker run -d --name web -p 80:80 -v ./DevOpsTrabalhoUm:/var/www/html php:8.2-apache
	
- Acessar o container via:

docker exec -it web bash

- Nele se deve instalar o pdo

docker-php-ext-install pdo pdo_mysql
apachectl restart

Tcharaaammm está funcionando!!!

4) Fazer um container para o grafana

docker run -d --name grafana -p 3000:3000 grafana/grafana

abrir http://10.98.156.246:3000/ e configurar tudo pelo navegador mesmo

