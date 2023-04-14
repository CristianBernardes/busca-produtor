# Busca Produtor API

A plataforma "Busca Produtor" tem como objetivo primordial gerenciar a busca de produtores para um cliente específico, permitindo-lhe obter informações relevantes com base em sua localização, como a proximidade dos produtores.Somente usuários com perfil administrativo poderão manipular dados do sistema de maneira mais abrangente, podendo excluir, editar e adicionar novos registros às tabelas de clientes, usuários e produtores.
Usuários com perfis de cliente terão acesso apenas à visualização de informações sobre produtores, baseadas em sua localização atual, com um limite de até 500 km² de raio.

## Observação

Para que o projeto funcione em sua maquina, é necessário que tenha o Docker instalado e também o Docker Compose.

## Instalação

Clone o repositório com o comando a seguir:

```bash
  git clone https://CristianBernardesConvicti@bitbucket.org/via-group/buscaprodutores-api.git
```

Abra a pasta do projeto com o comando:

```bash
  cd buscaprodutores-api
```

Copie o arquivo todos os arquivos que tenham extensão .example para o seu nome sem o .example com o seguinte comando:

```bash
  cp .env.example .env
  cp .editorconfig.example .editorconfig
  cp docker-compose.yml.example docker-compose.yml
  cp Dockerfile.example Dockerfile
```

Supondo que você tem o docker e o docker compose instalados em sua maquina, inicie os containers com o comando a seguir:

```bash
docker compose up -d
```

ou

```bash
docker-compose up -d
```

Após iniciar o containers, execute o seguinte comando para conectar ao container via ssh:

```bash
docker exec -it buscaprodutores-app sh
```

Já dentro do container do app, execute o seguinte comando para instalar todas as dependencias:

```bash
composer install
```

Ainda dentro do container, execute os seguintes comandos para executar as migrations com os seeders e realizar alguns testes unitarios:

```bash
php artisan migrate --seed
```

ou caso já tenha uma base populada, execute o seguinte comando:

```bash
php artisan migrate:fresh --seed
```

Este comando ira popular a base de dados com os dados necessários!
