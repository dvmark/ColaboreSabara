Colabore Sabará
===============================

Table
-------------------------------
- Introdução
- Tecnologias Utilizadas
- Estrutura do Projeto
- Pré-requisitos
- Como Executar com Docker


Introdução
-------------------------------
O Colabore Sabará é um sistema PHP integrado com MySQL para gerenciamento de ocorrências e solicitações da cidade de Sabará. 
O projeto utiliza Docker para padronizar o ambiente e permitir execução rápida com apenas um comando.

O ambiente contém:
- Container MySQL 8
- Container PHP + Apache
- Execução automática do script init.sql


Tecnologias Utilizadas
-------------------------------
- PHP 8 + Apache
- MySQL 8
- Docker & Docker Compose
- HTML, CSS, JavaScript


Estrutura do Projeto


<img width="293" height="629" alt="{C970D19B-8A69-440E-8CF0-0A22A1EEC7B7}" src="https://github.com/user-attachments/assets/a88a3c31-380f-42cd-8fbb-a184da3d4a09" />



Pré-requisitos
-------------------------------
- Docker
- Docker Compose


Como Executar com Docker
-------------------------------

1. Na raiz do projeto, execute:

    docker compose up

Isso irá:
- Criar o container MySQL
- Rodar automaticamente o cs.sql (Database)
- Subir o container PHP + Apache
- Montar o código PHP dentro do container

2. Acessar o sistema no navegador:

    http://localhost

3. Parar os containers:

    docker compose down

Para resetar o banco:

    docker compose down -v
===============================
