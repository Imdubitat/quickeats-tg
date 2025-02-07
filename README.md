# quickeats-tg
Repositório dedicado ao desenvolvimento do projeto de graduação do curso de Análise e Desenvolvimento de Sistemas.

## ‍ 📝 Sobre o sistema
Este projeto tem por objetivo desenvolver uma plataforma de delivery que proporcione uma experiência acessível e personalizada para todos os públicos e tamanhos de estabelecimentos, com foco em atender o máximo de nichos possíveis, aliando práticas sustentáveis e uma relação humanizada com nossos clientes, estabelecimentos parceiros e entregadores.

## 📒 Documentação
Toda a documentação, análise de viabilidade e planejamento deste projeto está disponível no link do notion a seguir:
https://delicate-weight-9bf.notion.site/QuickEats-Sistema-de-Delivery-de-Alimentos-Um-Novo-Conceito-12f801a68e734bf6bdafcc594fd39556?pvs=4

## 📒 Como executar o projeto na sua máquina

### Pré-requisitos
Antes de começar, certifique-se de que você tem os seguintes softwares instalados em sua máquina:

- PHP >= 8.2
- Composer (https://getcomposer.org/)
- MySQL ou PostgreSQL (ou outro banco de dados compatível)


```bash
  git clone https://github.com/Imdubitat/quickeats-tg.git
```

Vá até o diretório do projeto

```bash
  cd quickeats
```

Instale o composer:

```bash
  composer install
```

Copie o arquivo .env.example

```bash
  cp .env.example .env
```

Gere a chave da aplicação
```bash
  php artisan key:generate
```

Configure o banco de dados no arquivo de configuração .env
```bash
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=quickeats
  DB_USERNAME=root
  DB_PASSWORD=
```

Configure o gerenciamento de sessão no arquivo de cofiguração .env
```bash
SESSION_DRIVER=file
```

Inicie o servidor de desenvolvimento
```bash
  php artisan serve
```