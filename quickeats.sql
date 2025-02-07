-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 17/01/2025 às 16:29
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `quickeats`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `alterar_senha` (IN `p_id` INT, IN `p_nova_senha` VARCHAR(255), IN `p_tipo_usuario` ENUM('cliente','estabelecimento'))   BEGIN
    -- Verificar e atualizar a senha de um cliente
    IF p_tipo_usuario = 'cliente' THEN
        IF EXISTS (SELECT 1 FROM clientes WHERE id_cliente = p_id) THEN
            UPDATE clientes
            SET senha = p_nova_senha
            WHERE id_cliente = p_id;
            SELECT 'Senha alterada com sucesso para o cliente' AS mensagem;
        ELSE
            SELECT 'E-mail não encontrado na tabela de clientes' AS mensagem;
        END IF;

    -- Verificar e atualizar a senha de um estabelecimento
    ELSEIF p_tipo_usuario = 'estabelecimento' THEN
        IF EXISTS (SELECT 1 FROM estabelecimentos WHERE id_estab = p_id) THEN
            UPDATE estabelecimentos
            SET senha = p_nova_senha
            WHERE id_estab = p_id;
            SELECT 'Senha alterada com sucesso para o estabelecimento' AS mensagem;
        ELSE
            SELECT 'E-mail não encontrado na tabela de estabelecimentos' AS mensagem;
        END IF;

    -- Caso o tipo de usuário não seja válido
    ELSE
        SELECT 'Tipo de usuário inválido. Use "cliente" ou "estabelecimento"' AS mensagem;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `atualizar_cliente` (IN `p_id_cliente` INT, IN `p_telefone` VARCHAR(15), IN `p_email` VARCHAR(255))   BEGIN
UPDATE clientes SET telefone = p_telefone, email = p_email WHERE id_cliente = p_id_cliente; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `atualizar_estabelecimento` (IN `p_id_estab` INT, IN `p_nome_fantasia` VARCHAR(40), IN `p_cpf_titular` VARCHAR(14), IN `p_rg_titular` VARCHAR(12), IN `p_telefone` VARCHAR(15), IN `p_logradouro` VARCHAR(40), IN `p_numero` INT, IN `p_bairro` VARCHAR(40), IN `p_cidade` VARCHAR(40), IN `p_estado` VARCHAR(2), IN `p_cep` VARCHAR(9), IN `p_inicio_expediente` TIME, IN `p_termino_expediente` TIME, IN `p_email` VARCHAR(100))   BEGIN  
UPDATE estabelecimentos SET nome_fantasia = p_nome_fantasia, cpf_titular = p_cpf_titular, rg_titular = p_rg_titular, telefone = p_telefone, logradouro = p_logradouro, numero = p_numero, bairro = p_bairro, cidade = p_cidade, estado = p_estado, cep = p_cep, inicio_expediente = p_inicio_expediente, termino_expediente = p_termino_expediente, email = p_email WHERE id_estab = p_id_estab;   
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `cadastrar_endereco` (IN `p_id_cliente` INT, IN `p_logradouro` VARCHAR(100), IN `p_numero` INT, IN `p_bairro` VARCHAR(100), IN `p_cidade` VARCHAR(100), IN `p_estado` VARCHAR(2), IN `p_CEP` VARCHAR(9))   BEGIN
    DECLARE p_id_endereco INT;

    -- Inserir o endereço
    INSERT INTO enderecos (logradouro, numero, bairro, cidade, estado, CEP) 
    VALUES (p_logradouro, p_numero, p_bairro, p_cidade, p_estado, p_CEP);

    -- Capturar o ID do endereço inserido
    SET p_id_endereco = LAST_INSERT_ID();

    -- Relacionar o endereço com o cliente
    INSERT INTO enderecos_clientes (id_cliente, id_endereco) 
    VALUES (p_id_cliente, p_id_endereco);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `cadastrar_produto` (IN `p_nome` VARCHAR(60), IN `p_valor` DECIMAL(10,2), IN `p_id_categoria` INT, IN `p_id_estab` INT, IN `p_qtd_estoque` INT)   BEGIN 
INSERT INTO produtos (nome, valor, id_categoria, id_estab, qtd_estoque) VALUES (p_nome, p_valor, p_id_categoria, p_id_estab, p_qtd_estoque);  
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `clientes_por_estabelecimento` (IN `p_id_estab` INT)   BEGIN
    SELECT DISTINCT 
        c.id_cliente, 
        c.nome, 
        c.email, 
        c.telefone
    FROM pedidos AS p
    INNER JOIN clientes AS c ON p.id_cliente = c.id_cliente
    WHERE p.id_pedido IN (
        SELECT i.id_pedido 
        FROM itens_pedidos AS i
        INNER JOIN produtos AS pr ON i.id_produto = pr.id_produto
        WHERE pr.id_estab = p_id_estab
    )
    ORDER BY c.nome;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `contagem_pedidos_mes` (IN `p_id_estab` INT)   BEGIN
    SELECT 
        MONTH(p.data_compra) AS mes,
        YEAR(p.data_compra) AS ano,
        COUNT(DISTINCT p.id_pedido) AS total_pedidos
    FROM 
        pedidos p
    INNER JOIN 
        itens_pedidos ip ON p.id_pedido = ip.id_pedido
    INNER JOIN 
        produtos pr ON ip.id_produto = pr.id_produto
    WHERE 
        pr.id_estab = p_id_estab
    GROUP BY 
        ano, mes
    ORDER BY 
        ano, mes;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `exibir_pedidos_cliente` (IN `p_id_cliente` INT)   BEGIN
    SELECT 
        p.id_pedido,
        p.data_compra,
        p.status_entrega,
        p.valor_total,
        GROUP_CONCAT(pr.nome SEPARATOR ', ') AS produtos
    FROM pedidos AS p
    LEFT JOIN itens_pedidos AS ip ON p.id_pedido = ip.id_pedido
    LEFT JOIN produtos AS pr ON ip.id_produto = pr.id_produto
    WHERE p.id_cliente = p_id_cliente
    GROUP BY p.id_pedido
    ORDER BY p.data_compra DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `exibir_pedidos_estabelecimento` (IN `p_id_estab` INT)   BEGIN
    SELECT 
        p.id_pedido,
        c.nome AS nome_cliente,
        p.data_compra,
        p.status_entrega,
        p.valor_total,
        GROUP_CONCAT(pr.nome SEPARATOR ', ') AS produtos
    FROM pedidos AS p
    INNER JOIN clientes AS c ON p.id_cliente = c.id_cliente
    LEFT JOIN itens_pedidos AS ip ON p.id_pedido = ip.id_pedido
    LEFT JOIN produtos AS pr ON ip.id_produto = pr.id_produto
    WHERE pr.id_estab = p_id_estab
    GROUP BY p.id_pedido
    ORDER BY p.data_compra DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `exibir_produtos_cat` (IN `p_id_categoria` INT)   BEGIN
SELECT nome, valor, id_estab
FROM produtos
WHERE id_categoria = p_id_categoria;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `exibir_produtos_mais_populares_por_estabelecimento` (IN `p_id_estab` INT)   BEGIN
  SELECT 
    p.nome AS produto,
    SUM(ip.qtd_produto) AS total_vendas
  FROM 
    produtos p
  INNER JOIN itens_pedidos ip ON p.id_produto = ip.id_produto
  INNER JOIN pedidos pe ON ip.id_pedido = pe.id_pedido
  WHERE p.id_estab = p_id_estab
  GROUP BY p.id_produto
  ORDER BY total_vendas DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `faturamento_estabelecimento` (IN `p_id_estab` INT)   BEGIN
        SELECT 
        YEAR(p.data_compra) AS ano,
        MONTH(p.data_compra) AS mes,
        'pedidos' AS origem,
        SUM(p.valor_total) AS faturamento
    FROM 
        pedidos p
    WHERE 
        p.id_pedido IN (
            SELECT i.id_pedido
            FROM itens_pedidos i
            INNER JOIN produtos pr ON i.id_produto = pr.id_produto
            WHERE pr.id_estab = p_id_estab
        )
    GROUP BY 
        YEAR(p.data_compra), MONTH(p.data_compra);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_estab` ()   SELECT e.id_estab, e.nome_fantasia, e.telefone, e.logradouro, e.email,
e.numero, e.bairro, e.cidade, e.estado, e.cep
FROM estabelecimentos as e 
ORDER BY e.nome_fantasia$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `realizar_pedido` (IN `p_id_cliente` INT, IN `p_id_produto` INT, IN `p_endereco` INT, IN `p_forma_pagamento` INT, IN `p_qtd_produto` INT)   BEGIN
    -- Declara a variável para armazenar o id_pedido gerado automaticamente
    DECLARE p_id_pedido INT;
    DECLARE p_valor_total DECIMAL(10,2);

    -- Inserir o pedido na tabela pedidos (id_pedido é auto_increment e id_status será sempre 1)
    INSERT INTO pedidos (
        id_cliente, endereco, forma_pagamento, status_entrega, data_compra, valor_total
    )
    VALUES (
        p_id_cliente, p_endereco, p_forma_pagamento, 1, NOW(), 0
    );

    -- Recupera o último id_pedido gerado automaticamente
    SET p_id_pedido = LAST_INSERT_ID();

    -- Inserir o item no pedido
    INSERT INTO itens_pedidos (id_pedido, id_produto, qtd_produto) 
    VALUES (p_id_pedido, p_id_produto, p_qtd_produto);
    
    -- Calcular o valor total baseado na soma do preço dos produtos e quantidades
    SELECT SUM(ip.qtd_produto * p.valor) 
    INTO p_valor_total
    FROM itens_pedidos ip
    JOIN produtos p ON ip.id_produto = p.id_produto
    WHERE ip.id_pedido = p_id_pedido;
    
    -- Atualizar o valor total do pedido na tabela pedidos
    UPDATE pedidos
    SET valor_total = p_valor_total
    WHERE id_pedido = p_id_pedido;
    
    -- Atualizar o estoque dos produtos
    UPDATE produtos p
    JOIN itens_pedidos ip ON p.id_produto = ip.id_produto
    SET p.qtd_estoque = p.qtd_estoque - ip.qtd_produto
    WHERE ip.id_pedido = p_id_pedido;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `id_cliente` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `qtd_produto` int(11) NOT NULL,
  `data_adicao` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias_produtos`
--

CREATE TABLE `categorias_produtos` (
  `id_categoria` int(11) NOT NULL,
  `descricao` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categorias_produtos`
--

INSERT INTO `categorias_produtos` (`id_categoria`, `descricao`) VALUES
(1, 'alimentos'),
(2, 'comidas preparadas'),
(3, 'higiene e beleza');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `data_nasc` date NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `email_verificado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nome`, `cpf`, `data_nasc`, `telefone`, `email`, `senha`, `email_verificado`) VALUES
(1, 'Adenor Gonçalves da Silva', '716.802.680-15', '2000-01-07', '971794122', 'adenor@teste.com', 'NovaSenhaSegura1', 1),
(2, 'Mariana Oliveira Santos', '437.215.098-76', '1995-06-15', '(11) 98765-4321', 'mariana.santos@teste.com', 'senhaSegura123', 1),
(3, 'Roberto Almeida Costa', '152.987.654-01', '1988-12-03', '(21) 99988-7766', 'roberto.almeida@teste.com', 'minhaSenha456', 1),
(4, 'Carla Beatriz Ferreira', '254.632.198-87', '1992-04-22', '(31) 91234-5678', 'carla.ferreira@teste.com', 'outraSenha789', 1),
(5, 'Pedro Henrique Souza', '345.768.902-65', '2001-09-10', '(41) 96543-2109', 'pedro.souza@teste.com', 'senhaPedro123', 1);

--
-- Acionadores `clientes`
--
DELIMITER $$
CREATE TRIGGER `atualizacao_cliente` AFTER UPDATE ON `clientes` FOR EACH ROW BEGIN 
-- Verifica se o campo 'telefone' foi alterado 
    IF OLD.telefone != NEW.telefone THEN 
        INSERT INTO historico_clientes (id_cliente, campo_alterado, valor_antigo, valor_novo, data_alteracao) 
        VALUES (NEW.id_cliente , 'telefone', OLD.telefone, NEW.telefone, NOW()); 
    END IF;
    -- Verifica se o campo 'email' foi alterado 
    IF OLD.email != NEW.email THEN 
        INSERT INTO historico_clientes (id_cliente,  campo_alterado, valor_antigo, valor_novo, data_alteracao) 
        VALUES (NEW.id_cliente , 'email', OLD.email, NEW.email, NOW()); 
    END IF; 
    -- Verifica se o campo 'senha' foi alterado 
    IF OLD.senha != NEW.senha THEN 
        INSERT INTO historico_clientes (id_cliente, campo_alterado, valor_antigo, valor_novo, data_alteracao) 
        VALUES (NEW.id_cliente , 'senha', OLD.senha, NEW.senha, NOW()); 
    END IF; 
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `confirmacoes_emails`
--

CREATE TABLE `confirmacoes_emails` (
  `id_usuario` int(11) NOT NULL,
  `tipo_usuario` enum('cliente','estabelecimento') NOT NULL,
  `email` varchar(255) NOT NULL,
  `criado_em` datetime NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `enderecos`
--

CREATE TABLE `enderecos` (
  `id_endereco` int(11) NOT NULL,
  `logradouro` varchar(100) NOT NULL,
  `numero` int(11) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `cep` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `enderecos`
--

INSERT INTO `enderecos` (`id_endereco`, `logradouro`, `numero`, `bairro`, `cidade`, `estado`, `cep`) VALUES
(1, 'Rua das Palmeiras', 123, 'Centro', 'São Paulo', 'SP', '01010-010'),
(2, 'Avenida Brasil', 456, 'Zona Sul', 'Rio de Janeiro', 'RJ', '20020-020'),
(3, 'Rua do Sol', 789, 'Jardins', 'Curitiba', 'PR', '80030-030'),
(4, 'Rua das Flores', 101, 'Centro', 'Porto Alegre', 'RS', '90040-040'),
(5, 'Rua das Margaridas', 202, 'Bairro Alto', 'Fortaleza', 'CE', '60050-050');

-- --------------------------------------------------------

--
-- Estrutura para tabela `enderecos_clientes`
--

CREATE TABLE `enderecos_clientes` (
  `id_endereco` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `enderecos_clientes`
--

INSERT INTO `enderecos_clientes` (`id_endereco`, `id_cliente`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `estabelecimentos`
--

CREATE TABLE `estabelecimentos` (
  `id_estab` int(11) NOT NULL,
  `razao_social` varchar(255) NOT NULL,
  `nome_fantasia` varchar(255) NOT NULL,
  `cnpj` varchar(18) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `cpf_titular` varchar(14) NOT NULL,
  `rg_titular` varchar(12) NOT NULL,
  `cnae` varchar(9) NOT NULL,
  `logradouro` varchar(100) NOT NULL,
  `numero` int(11) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `inicio_expediente` time NOT NULL,
  `termino_expediente` time NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estabelecimentos`
--

INSERT INTO `estabelecimentos` (`id_estab`, `razao_social`, `nome_fantasia`, `cnpj`, `telefone`, `cpf_titular`, `rg_titular`, `cnae`, `logradouro`, `numero`, `bairro`, `cidade`, `estado`, `cep`, `inicio_expediente`, `termino_expediente`, `email`, `senha`, `tipo`) VALUES
(1, 'Restaurante Sabor Caseiro Ltda.', 'Novo Sabor Caseiro', '12.345.678/0001-90', '(11) 99999-9999', '987.654.321-00', '98.765.432-1', '5611-2', 'Rua Nova Esperança', 321, 'Jardim Paulista', 'Belo Horizonte', 'MG', '01111-111', '08:00:00', '21:00:00', 'novocontato@saborcaseiro.com', 'NovaSenhaSegura2', 1),
(2, 'Mercado Bom Preço Ltda.', 'Bom Preço', '23.456.789/0001-91', '(21) 99876-5432', '987.654.321-00', '98.765.432-1', '4711-3', 'Avenida Brasil', 456, 'Zona Sul', 'Rio de Janeiro', 'RJ', '20000-000', '07:00:00', '23:00:00', 'contato@bompreco.com', 'senhaSegura456', 2),
(3, 'Restaurante Delícias do Campo Ltda.', 'Delícias do Campo', '34.567.890/0001-92', '(31) 91234-5678', '654.321.987-00', '65.432.198-7', '5611-2', 'Rua Tranquila', 789, 'Bairro Novo', 'Belo Horizonte', 'MG', '30000-000', '11:00:00', '23:00:00', 'contato@deliciasdocampo.com', 'senhaSegura789', 1),
(4, 'Supermercado Sempre Fresco Ltda.', 'Sempre Fresco', '45.678.901/0001-93', '(41) 98765-6789', '321.987.654-00', '32.198.765-4', '4711-3', 'Rua Principal', 321, 'Zona Norte', 'Curitiba', 'PR', '80000-000', '08:00:00', '22:00:00', 'contato@semprefresco.com', 'senhaSuper123', 2),
(5, 'Restaurante Sabores do Mar Ltda.', 'Sabores do Mar', '56.789.012/0001-94', '(51) 97654-3210', '210.987.654-00', '21.098.765-4', '5611-2', 'Rua da Praia', 654, 'Centro', 'Porto Alegre', 'RS', '90000-000', '12:00:00', '23:00:00', 'contato@saboresdomar.com', 'senhaSabores123', 1);

--
-- Acionadores `estabelecimentos`
--
DELIMITER $$
CREATE TRIGGER `atualizacao_estabelecimento` AFTER UPDATE ON `estabelecimentos` FOR EACH ROW BEGIN 
    -- Verifica se o campo nome_fantasia foi alterado 
    IF OLD.nome_fantasia != NEW.nome_fantasia THEN 
        INSERT INTO historico_estabelecimentos (id_estab, campo_alterado, valor_antigo, valor_novo, data_alteracao) 
        VALUES (NEW.id_estab, 'nome_fantasia', OLD.nome_fantasia, NEW.nome_fantasia, NOW()); 
    END IF; 

    -- Verifica se o campo telefone foi alterado 
    IF OLD.telefone != NEW.telefone THEN 
        INSERT INTO historico_estabelecimentos (id_estab, campo_alterado, valor_antigo, valor_novo, data_alteracao) 
        VALUES (NEW.id_estab, 'telefone', OLD.telefone, NEW.telefone, NOW()); 
    END IF;

    -- Verifica se o campo cpf_titular foi alterado 
    IF OLD.cpf_titular != NEW.cpf_titular THEN 
        INSERT INTO historico_estabelecimentos (id_estab, campo_alterado, valor_antigo, valor_novo, data_alteracao) 
        VALUES (NEW.id_estab, 'cpf_titular', OLD.cpf_titular, NEW.cpf_titular, NOW()); 
    END IF;

    -- Verifica se o campo rg_titular foi alterado 
    IF OLD.rg_titular != NEW.rg_titular THEN 
        INSERT INTO historico_estabelecimentos (id_estab, campo_alterado, valor_antigo, valor_novo, data_alteracao) 
        VALUES (NEW.id_estab, 'rg_titular', OLD.rg_titular, NEW.rg_titular, NOW()); 
    END IF; 

    -- Verifica se o campo logradouro foi alterado 
    IF OLD.logradouro != NEW.logradouro THEN 
        INSERT INTO historico_estabelecimentos (id_estab, campo_alterado, valor_antigo, valor_novo, data_alteracao) 
        VALUES (NEW.id_estab, 'logradouro', OLD.logradouro, NEW.logradouro, NOW()); 
    END IF; 

    -- Verifica se o campo numero foi alterado 
    IF OLD.numero != NEW.numero THEN 
        INSERT INTO historico_estabelecimentos (id_estab, campo_alterado, valor_antigo, valor_novo, data_alteracao) 
        VALUES (NEW.id_estab, 'numero', OLD.numero, NEW.numero, NOW()); 
    END IF; 

    -- Verifica se o campo bairro foi alterado 
    IF OLD.bairro != NEW.bairro THEN 
        INSERT INTO historico_estabelecimentos (id_estab, campo_alterado, valor_antigo, valor_novo, data_alteracao) 
        VALUES (NEW.id_estab, 'bairro', OLD.bairro, NEW.bairro, NOW()); 
    END IF;
                
    -- Verifica se o campo cidade foi alterado 
    IF OLD.cidade != NEW.cidade THEN 
        INSERT INTO historico_estabelecimentos (id_estab, campo_alterado, valor_antigo, valor_novo, data_alteracao) 
        VALUES (NEW.id_estab, 'cidade', OLD.cidade, NEW.cidade, NOW()); 
    END IF;
                
    -- Verifica se o campo estado foi alterado 
    IF OLD.estado != NEW.estado THEN 
        INSERT INTO historico_estabelecimentos (id_estab, campo_alterado, valor_antigo, valor_novo, data_alteracao) 
        VALUES (NEW.id_estab, 'estado', OLD.estado, NEW.estado, NOW()); 
    END IF;
                
    -- Verifica se o campo cep foi alterado 
    IF OLD.cep != NEW.cep THEN 
        INSERT INTO historico_estabelecimentos (id_estab, campo_alterado, valor_antigo, valor_novo, data_alteracao) 
        VALUES (NEW.id_estab, 'cep', OLD.cep, NEW.cep, NOW()); 
    END IF; 

    -- Verifica se o campo inicio_expediente foi alterado 
    IF OLD.inicio_expediente != NEW.inicio_expediente THEN 
        INSERT INTO historico_estabelecimentos (id_estab, campo_alterado, valor_antigo, valor_novo, data_alteracao) 
        VALUES (NEW.id_estab, 'inicio_expediente', OLD.inicio_expediente, NEW.inicio_expediente, NOW()); 
    END IF;

    -- Verifica se o campo final_expediente foi alterado 
    IF OLD.termino_expediente != NEW.termino_expediente THEN 
        INSERT INTO historico_estabelecimentos (id_estab, campo_alterado, valor_antigo, valor_novo, data_alteracao) 
        VALUES (NEW.id_estab, 'final_expediente', OLD.termino_expediente, NEW.termino_expediente, NOW()); 
    END IF; 

    -- Verifica se o campo email foi alterado 
    IF OLD.email != NEW.email THEN 
        INSERT INTO historico_estabelecimentos (id_estab, campo_alterado, valor_antigo, valor_novo, data_alteracao) 
        VALUES (NEW.id_estab, 'email', OLD.email, NEW.email, NOW()); 
    END IF; 

    -- Verifica se o campo senha foi alterado 
    IF OLD.senha != NEW.senha THEN 
        INSERT INTO historico_estabelecimentos (id_estab, campo_alterado, valor_antigo, valor_novo, data_alteracao) 
        VALUES (NEW.id_estab, 'senha', OLD.senha, NEW.senha, NOW()); 
    END IF; 
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `formas_pagamentos`
--

CREATE TABLE `formas_pagamentos` (
  `id_formapag` int(11) NOT NULL,
  `descricao` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `formas_pagamentos`
--

INSERT INTO `formas_pagamentos` (`id_formapag`, `descricao`) VALUES
(1, 'pix'),
(2, 'credito'),
(3, 'debito');

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_clientes`
--

CREATE TABLE `historico_clientes` (
  `id_alteracao` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `campo_alterado` varchar(30) NOT NULL,
  `valor_antigo` varchar(255) NOT NULL,
  `valor_novo` varchar(255) NOT NULL,
  `data_alteracao` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `historico_clientes`
--

INSERT INTO `historico_clientes` (`id_alteracao`, `id_cliente`, `campo_alterado`, `valor_antigo`, `valor_novo`, `data_alteracao`) VALUES
(1, 1, 'telefone', '(79) 2687-5718', '', '2025-01-16 09:16:35'),
(2, 1, 'email', 'adenor@teste.com', '(19) 97179-4122', '2025-01-16 09:16:35'),
(3, 1, 'telefone', '', '971794122', '2025-01-16 09:18:33'),
(4, 1, 'email', '(19) 97179-4122', 'adenor@teste.com', '2025-01-16 09:18:33'),
(5, 1, 'senha', '123456789', 'NovaSenhaSegura1', '2025-01-16 09:42:21');

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_estabelecimentos`
--

CREATE TABLE `historico_estabelecimentos` (
  `id_alteracao` int(11) NOT NULL,
  `id_estab` int(11) NOT NULL,
  `campo_alterado` varchar(30) NOT NULL,
  `valor_antigo` varchar(255) NOT NULL,
  `valor_novo` varchar(255) NOT NULL,
  `data_alteracao` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `historico_estabelecimentos`
--

INSERT INTO `historico_estabelecimentos` (`id_alteracao`, `id_estab`, `campo_alterado`, `valor_antigo`, `valor_novo`, `data_alteracao`) VALUES
(1, 1, 'nome_fantasia', 'Sabor Caseiro', 'Novo Sabor Caseiro', '2025-01-16 09:28:37'),
(2, 1, 'telefone', '(11) 98765-4321', '(11) 99999-9999', '2025-01-16 09:28:37'),
(3, 1, 'cpf_titular', '123.456.789-00', '987.654.321-00', '2025-01-16 09:28:37'),
(4, 1, 'rg_titular', '12.345.678-9', '98.765.432-1', '2025-01-16 09:28:37'),
(5, 1, 'logradouro', 'Rua das Flores', 'Rua Nova Esperança', '2025-01-16 09:28:37'),
(6, 1, 'numero', '123', '321', '2025-01-16 09:28:37'),
(7, 1, 'bairro', 'Centro', 'Jardim Paulista', '2025-01-16 09:28:37'),
(8, 1, 'cep', '01000-000', '01111-111', '2025-01-16 09:28:37'),
(9, 1, 'inicio_expediente', '09:00:00', '08:00:00', '2025-01-16 09:28:37'),
(10, 1, 'final_expediente', '22:00:00', '21:00:00', '2025-01-16 09:28:37'),
(11, 1, 'email', 'contato@saborcaseiro.com', 'novocontato@saborcaseiro.com', '2025-01-16 09:28:37'),
(12, 1, 'cidade', 'São Paulo', 'Belo Horizonte', '2025-01-16 09:34:35'),
(13, 1, 'estado', 'SP', 'MG', '2025-01-16 09:34:35'),
(14, 1, 'senha', 'senhaSegura123', 'NovaSenhaSegura2', '2025-01-16 09:44:47');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedidos`
--

CREATE TABLE `itens_pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `qtd_produto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens_pedidos`
--

INSERT INTO `itens_pedidos` (`id_pedido`, `id_produto`, `qtd_produto`) VALUES
(1, 3, 2),
(2, 1, 1),
(3, 4, 3),
(4, 2, 5),
(5, 5, 4),
(6, 6, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs_tokens`
--

CREATE TABLE `logs_tokens` (
  `id_token` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `motivo` enum('confirmação de email','redefinição de senha') NOT NULL,
  `tipo_usuario` enum('cliente','estabelecimento') NOT NULL,
  `token` varchar(255) NOT NULL,
  `criado_em` datetime NOT NULL,
  `usado_em` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `forma_pagamento` int(11) NOT NULL,
  `data_compra` datetime NOT NULL,
  `status_entrega` int(11) NOT NULL,
  `endereco` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_cliente`, `valor_total`, `forma_pagamento`, `data_compra`, `status_entrega`, `endereco`) VALUES
(1, 1, 79.80, 1, '2025-01-16 12:31:16', 1, 2),
(2, 2, 23.90, 2, '2025-01-16 12:31:16', 1, 3),
(3, 3, 149.70, 1, '2025-01-16 12:31:16', 1, 1),
(4, 4, 42.50, 3, '2025-01-16 12:31:16', 1, 4),
(5, 5, 51.60, 2, '2025-01-16 12:31:16', 1, 2),
(6, 1, 7.98, 1, '2025-01-16 12:33:00', 1, 2);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `pedidos_estabelecimento`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `pedidos_estabelecimento` (
`id_estab` int(11)
,`nome_fantasia` varchar(255)
,`id_pedido` int(11)
,`cliente` varchar(255)
,`valor_total` decimal(10,2)
,`forma_pagamento` varchar(60)
,`data_compra` datetime
,`status_pedido` varchar(60)
,`endereco_completo` varchar(332)
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id_produto` int(11) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_estab` int(11) NOT NULL,
  `qtd_estoque` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id_produto`, `nome`, `valor`, `id_categoria`, `id_estab`, `qtd_estoque`) VALUES
(1, 'Arroz Branco 5kg', 23.90, 1, 2, 49),
(2, 'Feijão Carioca 1kg', 8.50, 1, 2, 95),
(3, 'Pizza Calabresa', 39.90, 2, 1, 18),
(4, 'Peixe Grelhado', 49.90, 2, 5, 12),
(5, 'Sabonete Líquido 500ml', 12.90, 3, 4, 26),
(6, 'Macarrão Instantâneo 80g', 3.99, 1, 3, 188);

-- --------------------------------------------------------

--
-- Estrutura para tabela `resets_senhas`
--

CREATE TABLE `resets_senhas` (
  `id_usuario` int(11) NOT NULL,
  `tipo_usuario` enum('cliente','estabelecimento') NOT NULL,
  `email` varchar(255) NOT NULL,
  `criado_em` datetime NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `status_pedidos`
--

CREATE TABLE `status_pedidos` (
  `id_status` int(11) NOT NULL,
  `descricao` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `status_pedidos`
--

INSERT INTO `status_pedidos` (`id_status`, `descricao`) VALUES
(1, 'aguardando pagamento'),
(2, 'em preparação'),
(3, 'em rota de entrega');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo_estabelecimentos`
--

CREATE TABLE `tipo_estabelecimentos` (
  `id_tipo` int(11) NOT NULL,
  `descricao` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tipo_estabelecimentos`
--

INSERT INTO `tipo_estabelecimentos` (`id_tipo`, `descricao`) VALUES
(1, 'restaurante'),
(2, 'mercado');

-- --------------------------------------------------------

--
-- Estrutura para view `pedidos_estabelecimento`
--
DROP TABLE IF EXISTS `pedidos_estabelecimento`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pedidos_estabelecimento`  AS SELECT `e`.`id_estab` AS `id_estab`, `e`.`nome_fantasia` AS `nome_fantasia`, `p`.`id_pedido` AS `id_pedido`, `c`.`nome` AS `cliente`, `p`.`valor_total` AS `valor_total`, `fp`.`descricao` AS `forma_pagamento`, `p`.`data_compra` AS `data_compra`, `sp`.`descricao` AS `status_pedido`, concat(`en`.`logradouro`,', ',`en`.`numero`,', ',`en`.`bairro`,', ',`en`.`cidade`,', ',`en`.`estado`,', ',`en`.`cep`) AS `endereco_completo` FROM (((((((`pedidos` `p` join `itens_pedidos` `ip` on(`ip`.`id_pedido` = `p`.`id_pedido`)) join `produtos` `prod` on(`prod`.`id_produto` = `ip`.`id_produto`)) join `estabelecimentos` `e` on(`prod`.`id_estab` = `e`.`id_estab`)) join `clientes` `c` on(`c`.`id_cliente` = `p`.`id_cliente`)) join `formas_pagamentos` `fp` on(`fp`.`id_formapag` = `p`.`forma_pagamento`)) join `status_pedidos` `sp` on(`sp`.`id_status` = `p`.`status_entrega`)) join `enderecos` `en` on(`en`.`id_endereco` = `p`.`endereco`)) ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categorias_produtos`
--
ALTER TABLE `categorias_produtos`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Índices de tabela `enderecos`
--
ALTER TABLE `enderecos`
  ADD PRIMARY KEY (`id_endereco`);

--
-- Índices de tabela `enderecos_clientes`
--
ALTER TABLE `enderecos_clientes`
  ADD PRIMARY KEY (`id_endereco`),
  ADD KEY `fk_endereco_cliente` (`id_cliente`);

--
-- Índices de tabela `estabelecimentos`
--
ALTER TABLE `estabelecimentos`
  ADD PRIMARY KEY (`id_estab`),
  ADD KEY `fk_estabelecimentos_tipos` (`tipo`);

--
-- Índices de tabela `formas_pagamentos`
--
ALTER TABLE `formas_pagamentos`
  ADD PRIMARY KEY (`id_formapag`);

--
-- Índices de tabela `historico_clientes`
--
ALTER TABLE `historico_clientes`
  ADD PRIMARY KEY (`id_alteracao`),
  ADD KEY `fk_cliente_historico` (`id_cliente`);

--
-- Índices de tabela `historico_estabelecimentos`
--
ALTER TABLE `historico_estabelecimentos`
  ADD PRIMARY KEY (`id_alteracao`),
  ADD KEY `fk_estabelecimento_historico` (`id_estab`);

--
-- Índices de tabela `itens_pedidos`
--
ALTER TABLE `itens_pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `fk_itens_produtos` (`id_produto`);

--
-- Índices de tabela `logs_tokens`
--
ALTER TABLE `logs_tokens`
  ADD PRIMARY KEY (`id_token`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `fk_pedidos_clientes` (`id_cliente`),
  ADD KEY `fk_pagamento_pedido` (`forma_pagamento`),
  ADD KEY `fk_status_pedidos` (`status_entrega`),
  ADD KEY `fk_endereco_pedidos` (`endereco`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_produto`),
  ADD KEY `fk_produtos_categoria` (`id_categoria`),
  ADD KEY `fk_produtos_estab` (`id_estab`);

--
-- Índices de tabela `status_pedidos`
--
ALTER TABLE `status_pedidos`
  ADD PRIMARY KEY (`id_status`);

--
-- Índices de tabela `tipo_estabelecimentos`
--
ALTER TABLE `tipo_estabelecimentos`
  ADD PRIMARY KEY (`id_tipo`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias_produtos`
--
ALTER TABLE `categorias_produtos`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `enderecos`
--
ALTER TABLE `enderecos`
  MODIFY `id_endereco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `estabelecimentos`
--
ALTER TABLE `estabelecimentos`
  MODIFY `id_estab` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `formas_pagamentos`
--
ALTER TABLE `formas_pagamentos`
  MODIFY `id_formapag` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `historico_clientes`
--
ALTER TABLE `historico_clientes`
  MODIFY `id_alteracao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `historico_estabelecimentos`
--
ALTER TABLE `historico_estabelecimentos`
  MODIFY `id_alteracao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `itens_pedidos`
--
ALTER TABLE `itens_pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `logs_tokens`
--
ALTER TABLE `logs_tokens`
  MODIFY `id_token` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `status_pedidos`
--
ALTER TABLE `status_pedidos`
  MODIFY `id_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tipo_estabelecimentos`
--
ALTER TABLE `tipo_estabelecimentos`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `enderecos_clientes`
--
ALTER TABLE `enderecos_clientes`
  ADD CONSTRAINT `fk_endereco_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `fk_enderecos` FOREIGN KEY (`id_endereco`) REFERENCES `enderecos` (`id_endereco`);

--
-- Restrições para tabelas `estabelecimentos`
--
ALTER TABLE `estabelecimentos`
  ADD CONSTRAINT `fk_estabelecimentos_tipos` FOREIGN KEY (`tipo`) REFERENCES `tipo_estabelecimentos` (`id_tipo`);

--
-- Restrições para tabelas `historico_clientes`
--
ALTER TABLE `historico_clientes`
  ADD CONSTRAINT `fk_cliente_historico` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);

--
-- Restrições para tabelas `historico_estabelecimentos`
--
ALTER TABLE `historico_estabelecimentos`
  ADD CONSTRAINT `fk_estabelecimento_historico` FOREIGN KEY (`id_estab`) REFERENCES `estabelecimentos` (`id_estab`);

--
-- Restrições para tabelas `itens_pedidos`
--
ALTER TABLE `itens_pedidos`
  ADD CONSTRAINT `fk_itens_produtos` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_endereco_pedidos` FOREIGN KEY (`endereco`) REFERENCES `enderecos_clientes` (`id_endereco`),
  ADD CONSTRAINT `fk_pagamento_pedido` FOREIGN KEY (`forma_pagamento`) REFERENCES `formas_pagamentos` (`id_formapag`),
  ADD CONSTRAINT `fk_pedidos_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `fk_status_pedidos` FOREIGN KEY (`status_entrega`) REFERENCES `status_pedidos` (`id_status`);

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_produtos_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias_produtos` (`id_categoria`),
  ADD CONSTRAINT `fk_produtos_estab` FOREIGN KEY (`id_estab`) REFERENCES `estabelecimentos` (`id_estab`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;