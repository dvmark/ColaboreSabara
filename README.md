Colabore Sabará
===============================

Table
-------------------------------
- Introdução
- Tecnologias Utilizadas
- Estrutura do Projeto
- Pré-requisitos
- Como Executar com Docker
- Configuração do Banco de Dados MySQL
- Configuração de Variáveis no PHP
- Contato


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
-------------------------------
/
├── uploads/
│   └── fotos/
├── cadastro.php
├── conexao.php
├── config.php
├── dashboard.php
├── debug.php
├── footer.php
├── header.php
├── index.php
├── cs.sql
├── logout.php
├── minhasocorrencias.php
├── ocorrencias.php
├── perfil.php
├── sidebar.php
├── upvote.php
├── style.css
├── CSLOGO.png
├── docker-compose.yml
├── Dockerfile
└── README.md


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


Configuração do Banco de Dados MySQL
-------------------------------
Variáveis definidas no docker-compose.yml:

MYSQL_ROOT_PASSWORD = root123  
MYSQL_DATABASE      = colabore_sabara  
MYSQL_USER          = colabore  
MYSQL_PASSWORD      = colabore123  
Porta externa       = 3307

O arquivo cs.sql será carregado automaticamente ao iniciar pela primeira vez.


Configuração de Variáveis no PHP
-------------------------------
As variáveis são enviadas ao container PHP:

DB_HOST=db  
DB_USER=colabore  
DB_PASSWORD=colabore123  
DB_NAME=colabore_sabara  

Exemplo de conexao.php:

<?php
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$db   = getenv('DB_NAME');

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>


===============================
