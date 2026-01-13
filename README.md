# ServiceHub — Setup Local com Laravel Sail

Este documento descreve o passo a passo para rodar a aplicação **ServiceHub** localmente utilizando **Laravel Sail**, que fornece um ambiente Docker completo para desenvolvimento Laravel.

---

## Pré-requisitos

Antes de começar, certifique-se de ter instalado em sua máquina:

-   **Docker** (versão recente)
-   **Docker Compose** (v2 ou superior)
-   **Git**
-   Sistema operacional Linux ou macOS

    > No Windows, recomenda-se usar **WSL2**

> Não é necessário ter PHP, Composer ou Node.js instalados localmente.

---

## Clonando o repositório

Clone o projeto e acesse o diretório da aplicação:

```bash
git clone https://github.com/IagoMachado000/servicehub.git
cd servicehub
```

---

## Configurando variáveis de ambiente

Crie o arquivo `.env` a partir do exemplo:

```bash
cp .env.example .env
```

Edite o arquivo `.env` e configure as seguintes variáveis:

### Banco de Dados

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=servicehub
DB_PASSWORD=servicehub
```

> O valor `DB_HOST=mysql` é obrigatório para funcionar corretamente com o container do Sail.

---

### Configuração de E-mail (ambiente local)

```env
MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

> No ambiente local, os e-mails não são enviados de fato — eles serão registrados no log da aplicação.

---

## Instalando dependências PHP (Composer)

Use a imagem oficial do Laravel Sail para instalar as dependências PHP **sem precisar do Composer local**:

```bash
docker run --rm \
  -u "$(id -u):$(id -g)" \
  -v "$(pwd)":/var/www/html \
  -w /var/www/html \
  laravelsail/php84-composer:latest \
  composer install
```

Esse comando irá:

-   Criar a pasta `vendor/`
-   Preparar o projeto para uso com o Sail

---

## Subindo os containers com Sail

Após instalar as dependências, inicie os containers:

```bash
./vendor/bin/sail up -d
```

> Na primeira execução, o Docker pode levar alguns minutos para baixar as imagens.

---

## Gerando a chave da aplicação

Com os containers rodando, gere a chave da aplicação Laravel:

```bash
./vendor/bin/sail artisan key:generate
```

---

## Criando e populando o banco de dados

Execute as migrations e seeders:

```bash
./vendor/bin/sail php artisan migrate:fresh --seed
```

Esse comando irá:

-   Apagar o banco (caso exista)
-   Criar todas as tabelas
-   Popular o banco com dados iniciais

---

## Instalando dependências Front-end

Instale as dependências JavaScript:

```bash
./vendor/bin/sail npm install
```

---

## Rodando o front-end em modo desenvolvimento

Inicie o Vite para desenvolvimento:

```bash
./vendor/bin/sail npm run dev
```

> Esse comando mantém um processo ativo para hot reload de assets.

---

## Executando os testes automatizados

Para rodar todos os testes da aplicação:

```bash
./vendor/bin/sail php artisan test
```

---

## Processando filas (Jobs)

Para iniciar o worker de filas:

```bash
./vendor/bin/sail php artisan queue:work
```

> Esse comando deve ser executado em um terminal separado.

---

## Aplicação pronta

Após executar todos os passos acima, a aplicação estará disponível em:

```
http://localhost
```

_(ou na porta configurada no `docker-compose.yml`)_

---

## Comandos úteis do Sail

```bash
./vendor/bin/sail down            # Para os containers
./vendor/bin/sail restart         # Reinicia os containers
./vendor/bin/sail ps              # Lista containers ativos
./vendor/bin/sail logs            # Visualiza logs
./vendor/bin/sail shell           # Acessa o container da aplicação
```

---

## Observações importantes

-   Sempre utilize `./vendor/bin/sail` para rodar:

    -   Artisan
    -   NPM
    -   Composer

-   Não execute comandos diretamente no host (ex: `php artisan`).
-   Caso tenha problemas com banco ou cache:

    ```bash
    ./vendor/bin/sail down -v
    ./vendor/bin/sail up -d
    ```
