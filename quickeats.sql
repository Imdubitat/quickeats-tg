-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 22/06/2025 às 17:28
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `atualizar_estabelecimento` (IN `p_id_estab` INT, IN `p_telefone` VARCHAR(15), IN `p_email` VARCHAR(255))   BEGIN
    UPDATE estabelecimentos 
    SET telefone = p_telefone, email = p_email 
    WHERE id_estab = p_id_estab; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `avaliar_pedido` (IN `p_id_cliente` INT, IN `p_id_pedido` INT, IN `p_nota` INT)   BEGIN
    -- Verifica se o pedido pertence ao cliente antes de inserir a avaliação
    IF EXISTS (
        SELECT 1 
        FROM pedidos 
        WHERE id_pedido = p_id_pedido AND id_cliente = p_id_cliente
    ) THEN
        INSERT INTO avaliacoes (id_pedido, nota) VALUES (p_id_pedido, p_nota);
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `cadastrar_endereco` (IN `p_id_cliente` INT, IN `p_logradouro` VARCHAR(100), IN `p_numero` VARCHAR(255), IN `p_bairro` VARCHAR(100), IN `p_cidade` VARCHAR(100), IN `p_estado` VARCHAR(2), IN `p_CEP` VARCHAR(9))   BEGIN
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `calcular_media_avaliacoes` (IN `p_id_estab` INT)   BEGIN
    SELECT 
        COALESCE(ROUND(AVG(a.nota), 1), 0) AS media_avaliacao
    FROM avaliacoes a
    INNER JOIN pedidos pe ON pe.id_pedido = a.id_pedido
    WHERE EXISTS (
        SELECT 1 
        FROM itens_pedidos ip
        INNER JOIN produtos p ON p.id_produto = ip.id_produto
        WHERE ip.id_pedido = pe.id_pedido
        AND p.id_estab = p_id_estab
    );
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `consulta_grade_horaria` (IN `p_id_estab` INT)   BEGIN
    SELECT 
    	id_grade,
        dia_semana,
        inicio_expediente,
        termino_expediente
    FROM 
        grades_horario
    WHERE 
        id_estab = p_id_estab
    ORDER BY dia_semana ASC;  
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `contagem_pedidos_c_mes` (IN `p_id_estab` INT)   BEGIN
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
    AND 
    	p.status_entrega = 7
    GROUP BY 
        ano, mes
    ORDER BY 
        ano, mes;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `contagem_pedidos_f_mes` (IN `p_id_estab` INT)   BEGIN
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
    AND 
    	p.status_entrega = 5
    GROUP BY 
        ano, mes
    ORDER BY 
        ano, mes;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `escolher_plano` (IN `p_id_estab` INT, IN `p_id_plano` INT)   BEGIN
    DECLARE v_plano_atual INT;

    -- Verifica se o estabelecimento já tem um plano ativo
    SELECT id_plano INTO v_plano_atual
    FROM planos_estabelecimentos
    WHERE id_estab = p_id_estab AND ativo = 1
    LIMIT 1;

    -- Se já tiver um plano ativo, desativa ele
    IF v_plano_atual IS NOT NULL THEN
        UPDATE planos_estabelecimentos
        SET ativo = 0
        WHERE id_estab = p_id_estab;
    END IF;

    -- Insere ou atualiza o novo plano como ativo
    INSERT INTO planos_estabelecimentos (id_estab, id_plano, ativo)
    VALUES (p_id_estab, p_id_plano, 1)
    ON DUPLICATE KEY UPDATE id_plano = VALUES(id_plano), ativo = 1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `exibir_categorias_mais_populares_por_estabelecimento` (IN `p_id_estab` INT)   BEGIN
    SELECT 
        c.descricao, 
        COUNT(DISTINCT ip.id_pedido) AS total_vendas
    FROM 
        pedidos p
    INNER JOIN 
        itens_pedidos ip ON p.id_pedido = ip.id_pedido
    INNER JOIN 
        produtos pr ON ip.id_produto = pr.id_produto
    INNER JOIN 
        categorias_produtos c ON pr.id_categoria = c.id_categoria
    WHERE 
        pr.id_estab = p_id_estab
    GROUP BY 
        c.descricao
    ORDER BY 
        total_vendas DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `exibir_enderecos_cliente` (IN `p_id_cliente` INT)   BEGIN
    SELECT 
        e.id_endereco, 
        e.logradouro, 
        e.numero, 
        e.bairro, 
        e.cidade, 
        e.estado, 
        e.cep 
    FROM enderecos e
    INNER JOIN enderecos_clientes ec 
        ON ec.id_endereco = e.id_endereco
    WHERE ec.id_cliente = p_id_cliente;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `exibir_pedidos_cliente` (IN `p_id_cliente` INT)   BEGIN
    SELECT 
        p.id_pedido,
        c.nome AS nome_cliente,
        p.data_compra,
        p.status_entrega,
        p.valor_total,
        GROUP_CONCAT(DISTINCT CONCAT(pr.nome, ' x ', ip.qtd_produto) SEPARATOR ', ') AS produtos,
        f.descricao AS forma_pagamento,
        CONCAT(e.logradouro, ', ', e.numero, ', ', e.bairro, ', ', e.cidade, ' - ', e.estado, ', ', e.cep) AS endereco
    FROM pedidos AS p
    INNER JOIN clientes AS c ON p.id_cliente = c.id_cliente
    INNER JOIN itens_pedidos AS ip ON p.id_pedido = ip.id_pedido
    INNER JOIN produtos AS pr ON ip.id_produto = pr.id_produto
    INNER JOIN formas_pagamentos AS f ON p.forma_pagamento = f.id_formapag
    INNER JOIN enderecos_clientes AS ec ON ec.id_cliente = p.id_cliente
    INNER JOIN enderecos AS e ON p.endereco = e.id_endereco
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
        GROUP_CONCAT(DISTINCT CONCAT(pr.nome, ' x ', ip.qtd_produto) SEPARATOR ', ') AS produtos,
        f.descricao AS forma_pagamento,
        CONCAT(e.logradouro, ', ', e.numero, ', ', e.bairro, ', ', e.cidade, ' - ', e.estado, ', ', e.cep) AS endereco
    FROM pedidos AS p
    INNER JOIN clientes AS c ON p.id_cliente = c.id_cliente
    INNER JOIN itens_pedidos AS ip ON p.id_pedido = ip.id_pedido
    INNER JOIN produtos AS pr ON ip.id_produto = pr.id_produto
    INNER JOIN formas_pagamentos AS f ON p.forma_pagamento = f.id_formapag
    INNER JOIN enderecos_clientes AS ec ON c.id_cliente = ec.id_cliente
    INNER JOIN enderecos AS e ON ec.id_endereco = e.id_endereco
    WHERE pr.id_estab = p_id_estab
    GROUP BY p.id_pedido
    ORDER BY p.data_compra DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `exibir_pedidos_f_estabelecimento` (IN `p_id_estab` INT)   BEGIN
    SELECT 
        p.id_pedido,
        c.nome AS nome_cliente,
        p.data_compra,
        p.status_entrega,
        p.valor_total,
        GROUP_CONCAT(DISTINCT CONCAT(pr.nome, ' x ', ip.qtd_produto) SEPARATOR ', ') AS produtos,
        f.descricao AS forma_pagamento,
        CONCAT(e.logradouro, ', ', e.numero, ', ', e.bairro, ', ', e.cidade, ' - ', e.estado, ', ', e.cep) AS endereco
    FROM pedidos AS p
    INNER JOIN clientes AS c ON p.id_cliente = c.id_cliente
    INNER JOIN itens_pedidos AS ip ON p.id_pedido = ip.id_pedido
    INNER JOIN produtos AS pr ON ip.id_produto = pr.id_produto
    INNER JOIN formas_pagamentos AS f ON p.forma_pagamento = f.id_formapag
    INNER JOIN enderecos_clientes AS ec ON c.id_cliente = ec.id_cliente
    INNER JOIN enderecos AS e ON ec.id_endereco = e.id_endereco
    WHERE pr.id_estab = p_id_estab
    AND p.status_entrega = 5
    GROUP BY p.id_pedido
    ORDER BY p.data_compra DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `exibir_planos` ()   BEGIN
    SELECT e.id_estab, e.nome_fantasia, 
           p.id_plano, p.nome AS nome_plano, p.valor
    FROM planos_estabelecimentos pe
    JOIN estabelecimentos e ON pe.id_estab = e.id_estab
    JOIN planos p ON pe.id_plano = p.id_plano
    WHERE pe.ativo = 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `exibir_produtos_cat` (IN `p_id_categoria` INT)   BEGIN
SELECT nome, valor, id_estab
FROM produtos
WHERE id_categoria = p_id_categoria;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `exibir_produtos_estab` (IN `p_id_estab` INT)   BEGIN
    SELECT 
        p.id_produto, 
        p.nome as nome_produto,
        p.descricao,
        p.valor, 
        p.id_categoria, 
        c.descricao AS categoria,
        p.id_estab, 
        p.qtd_estoque,
        e.nome_fantasia as estab,
        p.imagem_produto,
        e.imagem_perfil
    FROM produtos AS p
    INNER JOIN categorias_produtos AS c ON p.id_categoria = c.id_categoria
    INNER JOIN estabelecimentos AS e ON p.id_estab = e.id_estab
    WHERE p.qtd_estoque > 0 AND p.id_estab = p_id_estab;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `exibir_produtos_favoritos` (IN `p_id_cliente` INT)   BEGIN
    SELECT p.id_produto, 
	p.nome, 
    p.descricao, 
    p.valor, 
    p.id_estab,
    p.imagem_produto,
    e.nome_fantasia AS estab
    FROM produtos p
    INNER JOIN produtos_favoritos pf ON p.id_produto = pf.id_produto
    INNER JOIN estabelecimentos AS e ON p.id_estab = e.id_estab
    WHERE pf.id_cliente = p_id_cliente
    AND p.qtd_estoque > 0
    AND e.email_verificado = 1 
    AND e.perfil_ativo = 1;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_estab` ()   SELECT 
        e.id_estab, 
        e.nome_fantasia, 
        e.telefone, 
        e.logradouro, 
        e.numero, 
        e.bairro, 
        e.cidade, 
        e.estado, 
        e.cep, 
        e.email,
        e.imagem_perfil AS imagem,
        GROUP_CONCAT(
            CONCAT('Dia ', g.dia_semana, ': ', TIME_FORMAT(g.inicio_expediente, '%H:%i'), ' - ', TIME_FORMAT(g.termino_expediente, '%H:%i'))
            ORDER BY g.dia_semana SEPARATOR ' | '
        ) AS horarios
    FROM estabelecimentos AS e
    LEFT JOIN grades_horario AS g ON g.id_estab = e.id_estab
    WHERE e.email_verificado = 1 AND e.perfil_ativo = 1
    GROUP BY e.id_estab
    ORDER BY e.nome_fantasia$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_produtos` ()   BEGIN
    SELECT 
        p.id_produto, 
        p.nome AS nome_produto, 
        p.descricao,
        p.valor, 
        p.id_categoria, 
        c.descricao AS categoria,
        p.id_estab, 
        p.qtd_estoque,
        e.nome_fantasia AS estab,
        p.imagem_produto AS imagem
    FROM produtos AS p
    INNER JOIN categorias_produtos AS c ON p.id_categoria = c.id_categoria
    INNER JOIN estabelecimentos AS e ON p.id_estab = e.id_estab
    WHERE p.qtd_estoque > 0
    AND e.email_verificado = 1 
    AND e.perfil_ativo = 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `produtos_carrinho` (IN `p_id_cliente` INT)   BEGIN
    SELECT ca.id_cliente AS cliente, 
           ca.id_produto AS id_produto,
           p.nome AS nome_produto, 
           ca.qtd_produto AS qtd_produto, 
           p.valor AS valor,
           p.id_estab,
           p.imagem_produto AS imagem
    FROM carrinho ca
    INNER JOIN produtos p ON p.id_produto = ca.id_produto
    WHERE ca.id_cliente = p_id_cliente;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `realizar_pedido` (IN `p_id_cliente` INT, IN `p_endereco` INT, IN `p_forma_pagamento` INT, IN `p_payment_intent_id` VARCHAR(255))   BEGIN
    -- Declara variáveis
    DECLARE p_id_pedido INT;
    DECLARE p_valor_total DECIMAL(10,2);

    -- Criar o pedido na tabela pedidos (id_status será sempre 1)
    INSERT INTO pedidos (
        id_cliente, endereco, forma_pagamento, status_entrega, data_compra, valor_total, payment_intent_id
    ) 
    VALUES (
        p_id_cliente, p_endereco, p_forma_pagamento, 2, NOW(), 0, p_payment_intent_id
    );

    -- Recupera o último ID gerado para o pedido
    SET p_id_pedido = LAST_INSERT_ID();

    -- Inserir os itens do carrinho na tabela itens_pedidos
    INSERT INTO itens_pedidos (id_pedido, id_produto, qtd_produto)
    SELECT p_id_pedido, id_produto, qtd_produto
    FROM carrinho
    WHERE id_cliente = p_id_cliente;

    -- Calcular o valor total do pedido
    SELECT SUM(ip.qtd_produto * p.valor) 
    INTO p_valor_total
    FROM itens_pedidos ip
    JOIN produtos p ON ip.id_produto = p.id_produto
    WHERE ip.id_pedido = p_id_pedido;

    -- Atualizar o valor total do pedido na tabela pedidos
    UPDATE pedidos
    SET valor_total = p_valor_total
    WHERE id_pedido = p_id_pedido;

    -- Atualizar o estoque aqui mesmo!
    UPDATE produtos p
    JOIN itens_pedidos ip ON p.id_produto = ip.id_produto
    SET p.qtd_estoque = p.qtd_estoque - ip.qtd_produto
    WHERE ip.id_pedido = p_id_pedido;

    -- Esvaziar carrinho
    DELETE FROM carrinho WHERE id_cliente = p_id_cliente;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `administradores`
--

CREATE TABLE `administradores` (
  `id_admin` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `administradores`
--

INSERT INTO `administradores` (`id_admin`, `nome`, `email`, `senha`) VALUES
(1, 'Rodrigo', 'admin@teste.com', '$2y$12$Ub31tTUILWzDzy7lEsGqnO7c26.4FQ5/jZjGAKL1LqsuKIG8nhAp6');

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacoes`
--

CREATE TABLE `avaliacoes` (
  `id_avaliacao` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `nota` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Estrutura para tabela `categorias_chamado`
--

CREATE TABLE `categorias_chamado` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categorias_chamado`
--

INSERT INTO `categorias_chamado` (`id`, `nome`) VALUES
(1, 'Problema com pedido (atraso, erro, item faltando)'),
(2, 'Pedido cancelado sem motivo'),
(3, 'Problema com pagamento'),
(4, 'Cupom de desconto não aplicado'),
(5, 'Entrega atrasada'),
(6, 'Pedido entregue no endereço errado'),
(7, 'Produto chegou danificado'),
(8, 'Pedido não foi entregue'),
(9, 'Informação errada no cardápio'),
(10, 'Restaurante não respondeu à solicitação'),
(11, 'Produto indisponível no pedido'),
(12, 'Problema para fazer login'),
(13, 'Esqueci minha senha'),
(14, 'E-mail ou telefone não reconhecido'),
(15, 'Conta bloqueada ou desativada'),
(16, 'Problema na localização do cliente'),
(17, 'Pedido cancelado pelo cliente após retirada'),
(18, 'Problema com pagamento do entregador'),
(19, 'Sugestão de melhoria'),
(20, 'Problema técnico no aplicativo/site'),
(21, 'Dúvida sobre o serviço');

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
(1, 'Lanches'),
(2, 'Pizza'),
(3, 'Comida Brasileira'),
(4, 'Comida Japonesa'),
(5, 'Comida Chinesa'),
(6, 'Massas'),
(7, 'Marmitas'),
(8, 'Doces e Sobremesas'),
(9, 'Bebidas'),
(10, 'Açaí'),
(11, 'Salgados'),
(12, 'Cafeteria'),
(13, 'Padaria'),
(14, 'Vegetariana'),
(15, 'Vegana'),
(16, 'Saudável'),
(17, 'Frutos do Mar'),
(18, 'Churrasco'),
(19, 'Comida Mexicana'),
(20, 'Mercado');

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
  `email_verificado` tinyint(1) NOT NULL,
  `perfil_ativo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nome`, `cpf`, `data_nasc`, `telefone`, `email`, `senha`, `email_verificado`, `perfil_ativo`) VALUES
(1, 'Ana Paula Souza', '123.456.789-00', '1990-05-10', '(11) 91234-5678', 'ana.paula@teste.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1),
(2, 'Carlos Eduardo Lima', '234.567.890-11', '1985-07-20', '(21) 99876-5432', 'carlos.lima@teste.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1),
(3, 'Fernanda Oliveira', '345.678.901-22', '1992-03-15', '(31) 98765-4321', 'fernanda.oliveira@teste.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1),
(4, 'Rafael Silva Costa', '456.789.012-33', '1988-12-05', '(41) 91234-1234', 'rafael.costa@teste.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1),
(5, 'Juliana Mendes', '567.890.123-44', '1995-09-25', '(51) 99876-1111', 'juliana.mendes@teste.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1),
(6, 'Marcos Vinicius', '678.901.234-55', '1987-06-30', '(61) 91234-2222', 'marcos.vinicius@teste.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1),
(7, 'Patrícia Ramos', '789.012.345-66', '1991-02-08', '(62) 99876-3333', 'patricia.ramos@teste.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1),
(8, 'Lucas Fernandes', '890.123.456-77', '1993-11-12', '(71) 98765-4444', 'lucas.fernandes@teste.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1),
(9, 'Bruna Castro', '901.234.567-88', '1989-08-19', '(81) 99876-5555', 'bruna.castro@teste.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1),
(10, 'Diego Martins', '012.345.678-99', '1994-04-22', '(91) 91234-6666', 'diego.martins@teste.com.br', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1);

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
  `numero` varchar(255) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `cep` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `enderecos`
--

INSERT INTO `enderecos` (`id_endereco`, `logradouro`, `numero`, `bairro`, `cidade`, `estado`, `cep`) VALUES
(1, 'Rua das Flores', '123', 'Jardim Primavera', 'São Paulo', 'SP', '01001-000'),
(2, 'Avenida Central', '456', 'Centro', 'Rio de Janeiro', 'RJ', '20010-000'),
(3, 'Rua da Paz', '789', 'Boa Vista', 'Belo Horizonte', 'MG', '30140-000'),
(4, 'Rua das Acácias', '321', 'Águas Claras', 'Brasília', 'DF', '70297-400'),
(5, 'Travessa da Alegria', '654', 'Copacabana', 'Rio de Janeiro', 'RJ', '22060-001'),
(6, 'Rua do Sol', '987', 'Vila Mariana', 'São Paulo', 'SP', '04117-001'),
(7, 'Rua das Laranjeiras', '159', 'Laranjeiras', 'Salvador', 'BA', '40140-280'),
(8, 'Avenida Brasil', '753', 'Jardim América', 'Curitiba', 'PR', '80210-000'),
(9, 'Rua da Esperança', '852', 'Ponta Verde', 'Maceió', 'AL', '57035-180'),
(10, 'Rua São Jorge', '147', 'Centro', 'Belém', 'PA', '66015-000');

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
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);

-- --------------------------------------------------------

--
-- Estrutura para tabela `estabelecimentos`
--

CREATE TABLE `estabelecimentos` (
  `id_estab` int(11) NOT NULL,
  `razao_social` varchar(255) DEFAULT NULL,
  `nome_fantasia` varchar(255) NOT NULL,
  `cnpj` varchar(18) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `cpf_titular` varchar(14) DEFAULT NULL,
  `rg_titular` varchar(12) DEFAULT NULL,
  `cnae` varchar(9) DEFAULT NULL,
  `logradouro` varchar(100) NOT NULL,
  `numero` varchar(50) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` enum('AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO') NOT NULL,
  `cep` varchar(9) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `email_verificado` tinyint(4) NOT NULL,
  `perfil_ativo` tinyint(1) NOT NULL,
  `imagem_perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estabelecimentos`
--

INSERT INTO `estabelecimentos` (`id_estab`, `razao_social`, `nome_fantasia`, `cnpj`, `telefone`, `cpf_titular`, `rg_titular`, `cnae`, `logradouro`, `numero`, `bairro`, `cidade`, `estado`, `cep`, `email`, `senha`, `email_verificado`, `perfil_ativo`, `imagem_perfil`) VALUES
(1, 'Padaria Pão Quente', 'Pão Quente', '12.345.678/0001-00', '(11) 98888-0001', '123.456.789-00', '12.345.678-9', '4721101', 'Rua das Massas', '100', 'Centro', 'São Paulo', 'SP', '01010-000', 'contato@paoquente.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1, '1750535912_pao_quente.jpg'),
(2, 'Restaurante Sabor & Cia', 'Sabor & Cia', '23.456.789/0001-11', '(21) 97777-0002', '234.567.890-11', '23.456.789-0', '5611203', 'Avenida Gourmet', '200', 'Jardins', 'Rio de Janeiro', 'RJ', '20020-000', 'contato@saborcia.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1, '1750535962_saborecia.jpg'),
(3, 'Açaí da Hora LTDA', 'Açaí da Hora', '34.567.890/0001-22', '(31) 96666-0003', '345.678.901-22', '34.567.890-1', '4729601', 'Rua Tropical', '300', 'Centro', 'Belo Horizonte', 'MG', '30130-000', 'contato@acaihora.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1, '1750535997_acaidahora.jpg'),
(4, 'Pizza & Ponto ME', 'Pizza & Ponto', '45.678.901/0001-33', '(41) 95555-0004', '456.789.012-33', '45.678.901-2', '5611201', 'Rua das Pizzas', '400', 'Santa Felicidade', 'Curitiba', 'PR', '80230-000', 'contato@pizzaponto.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1, '1750536029_pizzaeponto.jpg'),
(5, 'Grãos do Cerrado', 'Grãos do Cerrado', '56.789.012/0001-44', '(61) 94444-0005', '567.890.123-44', '56.789.012-3', '4721102', 'Rua do Café', '500', 'Asa Sul', 'Brasília', 'DF', '70300-000', 'contato@graoscerrado.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1, '1750536130_graosdocerrado.jpg'),
(6, 'Tempero Nordestino', 'Tempero Nordestino', '67.890.123/0001-55', '(71) 93333-0006', '678.901.234-55', '67.890.123-4', '5611203', 'Rua do Sertão', '120', 'Liberdade', 'Salvador', 'BA', '40025-000', 'contato@tempero.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1, '1750536187_temperonordestino.jpg'),
(7, 'Café na Praça ME', 'Café na Praça', '78.901.234/0001-66', '(51) 92222-0007', '789.012.345-66', '78.901.234-5', '5611204', 'Praça Central', '45', 'Moinhos de Vento', 'Porto Alegre', 'RS', '90520-040', 'cafe@napraca.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1, '1750536255_cafenapraca.jpg'),
(8, 'Delícias Caseiras Ltda', 'Delícias Caseiras', '89.012.345/0001-77', '(81) 91111-0008', '890.123.456-77', '89.012.345-6', '5611201', 'Rua das Receitas', '88', 'Boa Viagem', 'Recife', 'PE', '51020-200', 'delicias@caseiras.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1, '1750536292_deliciascaseiras.jpg'),
(9, 'Sushi Yamada', 'Sushi Yamada', '90.123.456/0001-88', '(85) 90000-0009', '901.234.567-88', '90.123.456-7', '5611205', 'Avenida Japão', '201', 'Aldeota', 'Fortaleza', 'CE', '60160-000', 'contato@sushiyamada.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1, '1750536332_sushiyamada.jpg'),
(10, 'Tapiocaria da Esquina', 'Tapiocaria da Esquina', '01.234.567/0001-99', '(95) 98888-0010', '012.345.678-99', '01.234.567-8', '5611206', 'Rua do Norte', '19', 'São Francisco', 'Boa Vista', 'RR', '69300-000', 'contato@tapioca.com', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', 1, 1, '1750536373_tapiocaesquina.jpg');

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
-- Estrutura stand-in para view `estabelecimentos_populares`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `estabelecimentos_populares` (
`id` int(11)
,`nome_fantasia` varchar(255)
,`total_agendamentos` bigint(21)
,`imagem` varchar(255)
);

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
(1, 'Pix'),
(2, 'Cartão de crédito'),
(3, 'Cartão de débito');

-- --------------------------------------------------------

--
-- Estrutura para tabela `grades_horario`
--

CREATE TABLE `grades_horario` (
  `id_grade` int(11) NOT NULL,
  `id_estab` int(11) NOT NULL,
  `dia_semana` enum('1','2','3','4','5','6','7') NOT NULL,
  `inicio_expediente` time NOT NULL,
  `termino_expediente` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `grades_horario`
--

INSERT INTO `grades_horario` (`id_grade`, `id_estab`, `dia_semana`, `inicio_expediente`, `termino_expediente`) VALUES
(1, 1, '1', '10:00:00', '22:00:00'),
(2, 1, '2', '10:00:00', '22:00:00'),
(3, 1, '3', '10:00:00', '22:00:00'),
(4, 1, '4', '10:00:00', '22:00:00'),
(5, 1, '5', '10:00:00', '22:00:00'),
(6, 1, '6', '10:00:00', '22:00:00'),
(7, 2, '1', '10:00:00', '22:00:00'),
(8, 2, '2', '10:00:00', '22:00:00'),
(9, 2, '3', '10:00:00', '22:00:00'),
(10, 2, '4', '10:00:00', '22:00:00'),
(11, 2, '5', '10:00:00', '22:00:00'),
(12, 2, '6', '10:00:00', '22:00:00'),
(13, 3, '1', '10:00:00', '22:00:00'),
(14, 3, '2', '10:00:00', '22:00:00'),
(15, 3, '3', '10:00:00', '22:00:00'),
(16, 3, '4', '10:00:00', '22:00:00'),
(17, 3, '5', '10:00:00', '22:00:00'),
(18, 3, '6', '10:00:00', '22:00:00'),
(19, 4, '1', '10:00:00', '22:00:00'),
(20, 4, '2', '10:00:00', '22:00:00'),
(21, 4, '3', '10:00:00', '22:00:00'),
(22, 4, '4', '10:00:00', '22:00:00'),
(23, 4, '5', '10:00:00', '22:00:00'),
(24, 4, '6', '10:00:00', '22:00:00'),
(25, 5, '1', '10:00:00', '22:00:00'),
(26, 5, '2', '10:00:00', '22:00:00'),
(27, 5, '3', '10:00:00', '22:00:00'),
(28, 5, '4', '10:00:00', '22:00:00'),
(29, 5, '5', '10:00:00', '22:00:00'),
(30, 5, '6', '10:00:00', '22:00:00'),
(31, 6, '1', '10:00:00', '22:00:00'),
(32, 6, '2', '10:00:00', '22:00:00'),
(33, 6, '3', '10:00:00', '22:00:00'),
(34, 6, '4', '10:00:00', '22:00:00'),
(35, 6, '5', '10:00:00', '22:00:00'),
(36, 6, '6', '10:00:00', '22:00:00'),
(37, 7, '1', '10:00:00', '22:00:00'),
(38, 7, '2', '10:00:00', '22:00:00'),
(39, 7, '3', '10:00:00', '22:00:00'),
(40, 7, '4', '10:00:00', '22:00:00'),
(41, 7, '5', '10:00:00', '22:00:00'),
(42, 7, '6', '10:00:00', '22:00:00'),
(43, 8, '1', '10:00:00', '22:00:00'),
(44, 8, '2', '10:00:00', '22:00:00'),
(45, 8, '3', '10:00:00', '22:00:00'),
(46, 8, '4', '10:00:00', '22:00:00'),
(47, 8, '5', '10:00:00', '22:00:00'),
(48, 8, '6', '10:00:00', '22:00:00'),
(49, 9, '1', '10:00:00', '22:00:00'),
(50, 9, '2', '10:00:00', '22:00:00'),
(51, 9, '3', '10:00:00', '22:00:00'),
(52, 9, '4', '10:00:00', '22:00:00'),
(53, 9, '5', '10:00:00', '22:00:00'),
(54, 9, '6', '10:00:00', '22:00:00'),
(55, 10, '1', '18:00:00', '22:00:00'),
(56, 10, '2', '18:00:00', '22:00:00'),
(57, 10, '3', '18:00:00', '22:00:00'),
(58, 10, '4', '18:00:00', '22:00:00'),
(59, 10, '5', '18:00:00', '22:00:00'),
(60, 10, '6', '18:00:00', '22:00:00'),
(61, 1, '7', '09:00:00', '18:00:00'),
(62, 2, '7', '10:00:00', '19:00:00'),
(63, 3, '7', '08:30:00', '17:30:00'),
(64, 4, '7', '09:00:00', '16:00:00'),
(65, 5, '7', '11:00:00', '20:00:00'),
(66, 6, '7', '07:00:00', '15:00:00'),
(67, 7, '7', '12:00:00', '21:00:00'),
(68, 8, '7', '09:30:00', '17:30:00'),
(69, 9, '7', '10:00:00', '18:00:00'),
(70, 10, '7', '08:00:00', '14:00:00');

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
(24, 10, 'senha', '$2b$12$F0YhKviOSZ9aJDuPrZuXSuao6cxXycmS/8fbTmL13Uv.rbIaiyAeW', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 16:43:55'),
(25, 1, 'senha', '$2b$12$rAGqNtTPikXKghBi2mDs8.Kuy3w46xUYaNG7KFM0xq2L9XiyGUF/2', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 16:46:10'),
(26, 2, 'senha', '$2b$12$BxsbIfeqZWCSwwr/8h7WUuPGQ6mJB5ge2WzY5bkUkczIL2tFs6r9C', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 16:46:10'),
(27, 3, 'senha', '$2b$12$RQlTIohljdsNkrA2c7hO2ubIEAZ124EhMwXe6JIrLw1iOPoRPGmMC', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 16:46:10'),
(28, 4, 'senha', '$2b$12$h2u.M8FblWg7RkLcj/Ggd.Gr0TJqRN1hTebcVHo6vvp7GShgcFd0W', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 16:46:10'),
(29, 5, 'senha', '$2b$12$QjrvIwZNg0OD2WeeOfYOk.e0lPzlLfGuQH6RPcy/uB0skwytgY2TW', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 16:46:10'),
(30, 6, 'senha', '$2b$12$fkH8AK2wnDKeFeZvc6Xo2eWOwbdwX1cb1z7LgC0WL6v4VtJ.B0cq2', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 16:46:10'),
(31, 7, 'senha', '$2b$12$7OtWn8MJgEEXvTXJEdRk..KerGx/S8krUVilUHJlMjlV4bTX6L55e', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 16:46:10'),
(32, 8, 'senha', '$2b$12$hdEKGRLoICMyh8ZRii5iWei1/2fBZsNpzlRoIrmkh8EU6E1pv94zy', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 16:46:10'),
(33, 9, 'senha', '$2b$12$x5j38rve5mcrsk3AL7wfmePScVzTMaJA7vYfmhGyv5wavUAA1wnnK', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 16:46:10'),
(34, 10, 'email', 'diego.martins@teste.com', 'diego.martins@teste.com.br', '2025-06-20 18:22:42');

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
(43, 1, 'senha', 'HASH_AQUI', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 17:04:13'),
(44, 2, 'senha', 'HASH_AQUI', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 17:04:13'),
(45, 3, 'senha', 'HASH_AQUI', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 17:04:13'),
(46, 4, 'senha', 'HASH_AQUI', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 17:04:13'),
(47, 6, 'senha', 'HASH_AQUI', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 17:04:13'),
(48, 7, 'senha', 'HASH_AQUI', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 17:04:13'),
(49, 8, 'senha', 'HASH_AQUI', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 17:04:13'),
(50, 9, 'senha', 'HASH_AQUI', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 17:04:13'),
(51, 10, 'cpf_titular', '012.345.678-99', '', '2025-06-20 17:04:13'),
(52, 10, 'senha', 'HASH_AQUI', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-20 17:05:19'),
(53, 10, 'cpf_titular', '', '012.345.678-99', '2025-06-20 17:09:34'),
(54, 5, 'senha', 'HASH_AQUI', '$2y$12$uiBNqJTsA.CTOrh3d0Ngbu.9xteriN6xlhhEAU.WHvzaw91hYydm.', '2025-06-21 17:01:44');

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
(1, 6, 1),
(1, 7, 2),
(1, 8, 1),
(1, 9, 3),
(1, 10, 1),
(2, 1, 3),
(2, 2, 1),
(2, 3, 2),
(2, 4, 1),
(2, 5, 4),
(3, 11, 1),
(3, 12, 2),
(3, 13, 1),
(3, 14, 1),
(3, 15, 2),
(4, 16, 1),
(4, 17, 3),
(4, 18, 1),
(4, 19, 2),
(5, 21, 2),
(5, 22, 1),
(5, 23, 3),
(5, 24, 1),
(6, 26, 1),
(6, 29, 1),
(7, 39, 3),
(7, 40, 1),
(8, 41, 2),
(8, 42, 1),
(8, 43, 1),
(8, 44, 2),
(8, 45, 1),
(9, 46, 1),
(9, 47, 2),
(9, 48, 1),
(9, 49, 3),
(9, 50, 1),
(10, 1, 8),
(10, 3, 1),
(10, 4, 1),
(11, 7, 1),
(11, 10, 1),
(12, 14, 2),
(12, 15, 3),
(13, 16, 2),
(13, 17, 1),
(13, 19, 1),
(13, 20, 2),
(14, 22, 2),
(14, 23, 2),
(15, 28, 1),
(15, 29, 2),
(16, 37, 1),
(16, 39, 3),
(17, 41, 1),
(17, 44, 3),
(17, 45, 2),
(18, 46, 3),
(18, 47, 1),
(18, 48, 2),
(18, 49, 1),
(19, 1, 4),
(20, 3, 2),
(21, 2, 1),
(21, 4, 3),
(21, 1, 6),
(22, 2, 2),
(22, 3, 1),
(22, 5, 1),
(22, 4, 2),
(23, 8, 1),
(23, 10, 1),
(24, 40, 3),
(25, 11, 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs_tokens`
--

CREATE TABLE `logs_tokens` (
  `id_token` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `motivo` enum('confirmação de email','redefinição de senha','token expirado - confirmação de email','token expirado - redefinição de senha') NOT NULL,
  `tipo_usuario` enum('cliente','estabelecimento') NOT NULL,
  `token` varchar(255) NOT NULL,
  `criado_em` datetime NOT NULL,
  `usado_em` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens_cliente`
--

CREATE TABLE `mensagens_cliente` (
  `id_mensagem` int(11) NOT NULL,
  `id_chat` char(36) NOT NULL,
  `id_remetente` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL,
  `categoria` enum('Problema com pedido (atraso, erro, item faltando)','Pedido cancelado sem motivo','Problema com pagamento','Cupom de desconto não aplicado','Entrega atrasada','Pedido entregue no endereço errado','Produto chegou danificado','Pedido não foi entregue','Informação errada no cardápio','Restaurante não respondeu à solicitação','Produto indisponível no pedido','Problema para fazer login','Esqueci minha senha','E-mail ou telefone não reconhecido','Conta bloqueada ou desativada','Problema na localização do cliente','Pedido cancelado pelo cliente após retirada','Problema com pagamento do entregador','Sugestão de melhoria','Problema técnico no aplicativo/site','Dúvida sobre o serviço') NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` datetime NOT NULL,
  `ativo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens_estab`
--

CREATE TABLE `mensagens_estab` (
  `id_mensagem` int(11) NOT NULL,
  `id_chat` char(36) NOT NULL,
  `id_remetente` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL,
  `categoria` enum('Problema com pedido (atraso, erro, item faltando)','Pedido cancelado sem motivo','Problema com pagamento','Cupom de desconto não aplicado','Entrega atrasada','Pedido entregue no endereço errado','Produto chegou danificado','Pedido não foi entregue','Informação errada no cardápio','Restaurante não respondeu à solicitação','Produto indisponível no pedido','Problema para fazer login','Esqueci minha senha','E-mail ou telefone não reconhecido','Conta bloqueada ou desativada','Problema na localização do cliente','Pedido cancelado pelo cliente após retirada','Problema com pagamento do entregador','Sugestão de melhoria','Problema técnico no aplicativo/site','Dúvida sobre o serviço') NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` datetime NOT NULL,
  `ativo` tinyint(1) NOT NULL
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
  `endereco` int(11) NOT NULL,
  `payment_intent_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_cliente`, `valor_total`, `forma_pagamento`, `data_compra`, `status_entrega`, `endereco`, `payment_intent_id`) VALUES
(1, 2, 160.80, 2, '2025-06-22 11:07:26', 2, 2, 'pi_3RcoYA4U9pgCLCrt04zB9FAh'),
(2, 1, 50.30, 2, '2025-06-22 11:08:22', 2, 1, 'pi_3RcoZ04U9pgCLCrt0IfL6OxR'),
(3, 3, 92.00, 2, '2025-06-22 11:09:23', 2, 3, 'pi_3Rcoa04U9pgCLCrt0qr0LeM0'),
(4, 4, 209.00, 2, '2025-06-22 11:10:10', 2, 4, 'pi_3Rcoak4U9pgCLCrt0bxUN0OC'),
(5, 5, 32.00, 2, '2025-06-22 11:11:02', 2, 5, 'pi_3Rcobd4U9pgCLCrt1mlzm1D2'),
(6, 6, 28.00, 2, '2025-06-22 11:24:16', 2, 6, 'pi_3RcooQ4U9pgCLCrt0L1bn8jc'),
(7, 8, 56.00, 2, '2025-06-22 11:25:50', 2, 8, 'pi_3Rcopw4U9pgCLCrt1LtkyCMU'),
(8, 9, 153.00, 2, '2025-06-22 11:26:23', 2, 9, 'pi_3RcoqW4U9pgCLCrt1FfsBkti'),
(9, 10, 75.00, 2, '2025-06-22 11:27:10', 2, 10, 'pi_3RcorB4U9pgCLCrt05Qucjaa'),
(10, 1, 11.80, 3, '2025-06-22 11:46:43', 2, 1, 'pi_3RcpA64U9pgCLCrt1lJyPlBK'),
(11, 2, 32.90, 3, '2025-06-22 11:47:31', 2, 2, 'pi_3RcpAw4U9pgCLCrt0nsx0fo6'),
(12, 3, 65.00, 3, '2025-06-22 11:48:20', 2, 3, 'pi_3RcpBk4U9pgCLCrt0SI6Yc60'),
(13, 4, 197.00, 3, '2025-06-22 11:49:12', 2, 4, 'pi_3RcpCY4U9pgCLCrt0O5t4Dxk'),
(14, 5, 23.00, 3, '2025-06-22 11:50:06', 2, 5, 'pi_3RcpDR4U9pgCLCrt0edbK9ce'),
(15, 6, 24.00, 3, '2025-06-22 11:52:16', 2, 6, 'pi_3RcpFS4U9pgCLCrt0Mp112cB'),
(16, 8, 57.00, 3, '2025-06-22 11:53:50', 2, 8, 'pi_3RcpGz4U9pgCLCrt01VMFDmi'),
(17, 9, 105.00, 3, '2025-06-22 11:55:54', 2, 9, 'pi_3RcpJ44U9pgCLCrt0dAqDpEi'),
(18, 10, 61.00, 3, '2025-06-22 11:57:39', 2, 10, 'pi_3RcpKf4U9pgCLCrt1oBzIllI'),
(19, 1, 2.40, 2, '2025-06-22 12:03:10', 2, 1, 'pi_3RcpQ54U9pgCLCrt0fvowPnW'),
(20, 1, 8.00, 2, '2025-06-22 12:05:36', 2, 1, 'pi_3RcpSN4U9pgCLCrt0M4aFgfj'),
(21, 1, 18.10, 2, '2025-06-22 12:06:45', 2, 1, 'pi_3RcpTa4U9pgCLCrt0q7UAbcp'),
(22, 1, 29.00, 2, '2025-06-22 12:12:29', 2, 1, 'pi_3RcpZ04U9pgCLCrt0cFUej3j'),
(23, 1, 35.00, 2, '2025-06-22 12:13:24', 2, 1, 'pi_3RcpZu4U9pgCLCrt19ZVJ9w8'),
(24, 4, 15.00, 3, '2025-06-22 12:14:30', 2, 4, 'pi_3Rcpat4U9pgCLCrt0zZaxxpo'),
(25, 10, 60.00, 2, '2025-06-22 12:15:52', 2, 10, 'pi_3Rcpbt4U9pgCLCrt1ln84IAG');

--
-- Acionadores `pedidos`
--
DELIMITER $$
CREATE TRIGGER `devolve_estoque_pedido` AFTER UPDATE ON `pedidos` FOR EACH ROW BEGIN
    -- Pedido recusado direto (de 2 para 8)
    IF OLD.status_entrega = 2 AND NEW.status_entrega = 8 THEN
        UPDATE produtos p
        JOIN itens_pedidos ip ON p.id_produto = ip.id_produto
        SET p.qtd_estoque = p.qtd_estoque + ip.qtd_produto
        WHERE ip.id_pedido = NEW.id_pedido;
    END IF;

    -- Pedido cancelado após andamento (de 6 para 7)
    IF OLD.status_entrega = 6 AND NEW.status_entrega = 7 THEN
        UPDATE produtos p
        JOIN itens_pedidos ip ON p.id_produto = ip.id_produto
        SET p.qtd_estoque = p.qtd_estoque + ip.qtd_produto
        WHERE ip.id_pedido = NEW.id_pedido;
    END IF;
END
$$
DELIMITER ;

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
,`endereco_completo` text
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `planos`
--

CREATE TABLE `planos` (
  `id_plano` int(11) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `beneficios` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `planos`
--

INSERT INTO `planos` (`id_plano`, `nome`, `valor`, `beneficios`) VALUES
(1, 'Básico', 49.90, '- Relatórios de vendas\r\n- Taxa por pedido reduzida (5%)'),
(2, 'Premium', 99.90, '- Relatórios de vendas\r\n- Destaque na busca\r\n- Taxa reduzida por pedido (3%)');

-- --------------------------------------------------------

--
-- Estrutura para tabela `planos_estabelecimentos`
--

CREATE TABLE `planos_estabelecimentos` (
  `id_estab` int(11) NOT NULL,
  `id_plano` int(11) NOT NULL,
  `ativo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `planos_estabelecimentos`
--

INSERT INTO `planos_estabelecimentos` (`id_estab`, `id_plano`, `ativo`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 1, 1),
(4, 2, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id_produto` int(11) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_estab` int(11) NOT NULL,
  `qtd_estoque` int(11) NOT NULL,
  `imagem_produto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id_produto`, `nome`, `descricao`, `valor`, `id_categoria`, `id_estab`, `qtd_estoque`, `imagem_produto`) VALUES
(1, 'Pão Francês', 'Pão crocante assado na hora.', 0.60, 13, 1, 279, '1750554801.jpg'),
(2, 'Croissant de Presunto e Queijo', 'Massa folhada recheada com presunto e queijo.', 5.50, 13, 1, 40, '1750554826.jpg'),
(3, 'Bolo de Cenoura com Chocolate', 'Fatia de bolo caseiro com cobertura de chocolate.', 4.00, 8, 1, 34, '1750554846.jpg'),
(4, 'Café com Leite', 'Café com leite quente.', 3.00, 12, 1, 73, '1750554875.jpg'),
(5, 'Sonho de Creme', 'Doce frito recheado com creme de baunilha.', 8.00, 8, 1, 24, '1750554903.jpg'),
(6, 'Feijoada Completa', 'Feijoada com acompanhamentos tradicionais.', 25.00, 3, 2, 19, '1750554953.jpg'),
(7, 'Bife à Parmegiana', 'Bife empanado com queijo e molho de tomate.', 27.90, 3, 2, 12, '1750554976.jpg'),
(8, 'Moqueca de Peixe', 'Moqueca com arroz e pirão.', 30.00, 17, 2, 6, '1750554987.jpg'),
(9, 'Marmita Executiva', 'Arroz, feijão, carne e salada.', 15.00, 7, 2, 37, '1750555001.jpg'),
(10, 'Coca-cola lata 220ml', 'Refrigerante típico brasileiro.', 5.00, 9, 2, 57, '1750555128.jpg'),
(11, 'Açaí Tradicional 300ml', 'Açaí puro com granola.', 10.00, 10, 3, 43, '1750555179.jpg'),
(12, 'Açaí com Banana e Leite Condensado 300ml', 'Açaí com banana, granola e leite condensado.', 13.00, 10, 3, 38, '1750555222.jpg'),
(13, 'Açaí com Morango e Nutella 250ml', 'Açaí com morango fresco e Nutella.', 15.00, 10, 3, 28, '1750555244.jpg'),
(14, 'Suco de Cupuaçu', 'Suco natural de cupuaçu.', 7.00, 9, 3, 21, '1750555255.jpg'),
(15, 'Tigela Power', 'Açaí com mix de frutas e castanhas.', 17.00, 10, 3, 15, '1750555270.jpg'),
(16, 'Pizza Margherita', 'Molho de tomate, mussarela e manjericão.', 35.00, 2, 4, 16, '1750555492.jpg'),
(17, 'Pizza Calabresa', 'Calabresa fatiada com cebola e queijo.', 38.00, 2, 4, 21, '1750555350.jpg'),
(18, 'Pizza Quatro Queijos', 'Mussarela, provolone, gorgonzola e parmesão.', 42.00, 2, 4, 14, '1750555508.jpg'),
(19, 'Coca-cola 2L', 'Bebida gaseificada de 2 litros.', 9.00, 9, 4, 27, '1750555533.jpg'),
(20, 'Pizza Chocolate com Morango', 'Pizza doce com chocolate e morango.', 40.00, 8, 4, 8, '1750555554.jpg'),
(21, 'Café Expresso', 'Café forte e encorpado.', 4.00, 12, 5, 98, '1750555600.jpg'),
(22, 'Capuccino', 'Café com leite vaporizado e chocolate.', 6.50, 12, 5, 57, '1750555609.jpg'),
(23, 'Bolo de Fubá com Goiabada', 'Fatia de bolo típico do cerrado.', 5.00, 8, 5, 25, '1750555727.jpg'),
(24, 'Pão de Queijo', 'Pão de queijo mineiro.', 2.50, 11, 5, 99, '1750555740.jpg'),
(25, 'Chá de Hibisco', 'Chá natural gelado.', 5.00, 9, 5, 50, '1750555752.jpg'),
(26, 'Baião de Dois', 'Arroz, feijão verde, queijo coalho e carne seca.', 22.00, 3, 6, 19, '1750555819.jpg'),
(27, 'Carne de Sol com Macaxeira', 'Prato regional nordestino.', 26.00, 3, 6, 15, '1750555832.jpg'),
(28, 'Cuscuz Nordestino', 'Cuscuz de milho com ovos e queijo.', 12.00, 3, 6, 29, '1750555850.jpg'),
(29, 'Suco de Cajá', 'Bebida típica do nordeste.', 6.00, 9, 6, 22, '1750555864.jpg'),
(30, 'Marmita Nordestina', 'Marmita com carne seca, arroz e feijão.', 18.00, 7, 6, 40, '1750557793.jpg'),
(31, 'Café Pingado', 'Café com um toque de leite.', 3.00, 12, 7, 100, '1750555951.jpg'),
(32, 'Torrada com Manteiga', 'Pão torrado com manteiga.', 4.00, 13, 7, 50, '1750555961.jpg'),
(33, 'Pão de Mel', 'Doce recheado com doce de leite.', 5.00, 8, 7, 40, '1750555972.jpg'),
(34, 'Café Gelado com Leite', 'Bebida gelada à base de café.', 6.50, 9, 7, 30, '1750555984.jpg'),
(35, 'Torta de Limão', 'Fatia de torta com cobertura de limão.', 6.00, 8, 7, 20, '1750555995.jpg'),
(36, 'Escondidinho de Carne', 'Purê de mandioca com carne seca.', 20.00, 3, 8, 20, '1750556051.jpg'),
(37, 'Doce de Leite Caseiro', 'Doce tradicional feito em casa.', 6.00, 8, 8, 49, '1750556095.jpg'),
(38, 'Torta de Frango', 'Torta salgada com recheio de frango.', 12.00, 11, 8, 30, '1750556105.jpg'),
(39, 'Marmita Caseira', 'Arroz, feijão, bife acebolado e salada.', 17.00, 7, 8, 29, '1750556118.jpg'),
(40, 'Bolo de Milho Verde', 'Bolo típico do nordeste.', 5.00, 8, 8, 21, '1750556132.jpg'),
(41, 'Sushi de Salmão', '6 unidades de sushi fresco.', 22.00, 4, 9, 27, '1750556193.jpg'),
(42, 'Temaki de Atum', 'Cone de alga com arroz e atum.', 18.00, 4, 9, 19, '1750556203.jpg'),
(43, 'Combinado 20 peças', 'Sushis variados.', 45.00, 4, 9, 19, '1750556230.jpg'),
(44, 'Missoshiru', 'Sopa de missô.', 9.00, 4, 9, 35, '1750556248.jpg'),
(45, 'Sashimi de Salmão', '10 fatias de sashimi fresco.', 28.00, 4, 9, 12, '1750556260.jpg'),
(46, 'Tapioca de Frango com Catupiry', 'Tapioca recheada com frango e catupiry.', 10.00, 3, 10, 26, '1750556331.jpg'),
(47, 'Tapioca de Coco com Leite Condensado', 'Tapioca doce tradicional.', 9.00, 8, 10, 37, '1750556341.jpg'),
(48, 'Suco Natural de Abacaxi', 'Suco sem adição de açúcar.', 5.50, 9, 10, 27, '1750556352.jpg'),
(49, 'Tapioca de Carne Seca', 'Tapioca nordestina recheada.', 11.00, 3, 10, 16, '1750556366.jpg'),
(50, 'Cuscuz com Ovo e Queijo', 'Prato regional nordestino.', 8.50, 3, 10, 29, '1750556387.jpg');

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `produtos_disponiveis`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `produtos_disponiveis` (
`id_produto` int(11)
,`nome_produto` varchar(60)
,`valor` decimal(10,2)
,`id_estab` int(11)
,`estab` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos_favoritos`
--

CREATE TABLE `produtos_favoritos` (
  `id_produto` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos_favoritos`
--

INSERT INTO `produtos_favoritos` (`id_produto`, `id_cliente`) VALUES
(1, 1),
(4, 1),
(6, 2),
(9, 2),
(12, 3),
(14, 3),
(16, 4),
(20, 4),
(21, 5),
(23, 5),
(26, 6),
(29, 6),
(31, 7),
(33, 7),
(36, 8),
(39, 8),
(41, 9),
(43, 9),
(46, 10),
(48, 10);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `produtos_populares`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `produtos_populares` (
`id` int(11)
,`nome_produto` varchar(60)
,`id_estab` int(11)
,`categoria` int(11)
,`total_pedidos` bigint(21)
,`imagem` varchar(255)
);

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
(2, 'aguardando aprovação'),
(3, 'em preparação'),
(4, 'em rota de entrega'),
(5, 'finalizado'),
(6, 'aguardando cancelamento'),
(7, 'cancelado'),
(8, 'recusado');

-- --------------------------------------------------------

--
-- Estrutura para view `estabelecimentos_populares`
--
DROP TABLE IF EXISTS `estabelecimentos_populares`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `estabelecimentos_populares`  AS SELECT `e`.`id_estab` AS `id`, `e`.`nome_fantasia` AS `nome_fantasia`, count(distinct `p`.`id_pedido`) AS `total_agendamentos`, `e`.`imagem_perfil` AS `imagem` FROM (((`estabelecimentos` `e` join `produtos` `pr` on(`pr`.`id_estab` = `e`.`id_estab`)) join `itens_pedidos` `ip` on(`ip`.`id_produto` = `pr`.`id_produto`)) join `pedidos` `p` on(`p`.`id_pedido` = `ip`.`id_pedido`)) GROUP BY `e`.`id_estab`, `e`.`nome_fantasia`, `e`.`imagem_perfil` ORDER BY count(`p`.`id_pedido`) DESC LIMIT 0, 3 ;

-- --------------------------------------------------------

--
-- Estrutura para view `pedidos_estabelecimento`
--
DROP TABLE IF EXISTS `pedidos_estabelecimento`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pedidos_estabelecimento`  AS SELECT `e`.`id_estab` AS `id_estab`, `e`.`nome_fantasia` AS `nome_fantasia`, `p`.`id_pedido` AS `id_pedido`, `c`.`nome` AS `cliente`, `p`.`valor_total` AS `valor_total`, `fp`.`descricao` AS `forma_pagamento`, `p`.`data_compra` AS `data_compra`, `sp`.`descricao` AS `status_pedido`, concat(`en`.`logradouro`,', ',`en`.`numero`,', ',`en`.`bairro`,', ',`en`.`cidade`,', ',`en`.`estado`,', ',`en`.`cep`) AS `endereco_completo` FROM (((((((`pedidos` `p` join `itens_pedidos` `ip` on(`ip`.`id_pedido` = `p`.`id_pedido`)) join `produtos` `prod` on(`prod`.`id_produto` = `ip`.`id_produto`)) join `estabelecimentos` `e` on(`prod`.`id_estab` = `e`.`id_estab`)) join `clientes` `c` on(`c`.`id_cliente` = `p`.`id_cliente`)) join `formas_pagamentos` `fp` on(`fp`.`id_formapag` = `p`.`forma_pagamento`)) join `status_pedidos` `sp` on(`sp`.`id_status` = `p`.`status_entrega`)) join `enderecos` `en` on(`en`.`id_endereco` = `p`.`endereco`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `produtos_disponiveis`
--
DROP TABLE IF EXISTS `produtos_disponiveis`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `produtos_disponiveis`  AS SELECT `p`.`id_produto` AS `id_produto`, `p`.`nome` AS `nome_produto`, `p`.`valor` AS `valor`, `e`.`id_estab` AS `id_estab`, `e`.`nome_fantasia` AS `estab` FROM (`produtos` `p` join `estabelecimentos` `e` on(`e`.`id_estab` = `p`.`id_estab`)) WHERE `p`.`qtd_estoque` > 0 ;

-- --------------------------------------------------------

--
-- Estrutura para view `produtos_populares`
--
DROP TABLE IF EXISTS `produtos_populares`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `produtos_populares`  AS SELECT `pr`.`id_produto` AS `id`, `pr`.`nome` AS `nome_produto`, `pr`.`id_estab` AS `id_estab`, `pr`.`id_categoria` AS `categoria`, count(distinct `p`.`id_pedido`) AS `total_pedidos`, `pr`.`imagem_produto` AS `imagem` FROM ((`produtos` `pr` join `itens_pedidos` `ip` on(`ip`.`id_produto` = `pr`.`id_produto`)) join `pedidos` `p` on(`p`.`id_pedido` = `ip`.`id_pedido`)) GROUP BY `pr`.`id_produto`, `pr`.`nome`, `pr`.`id_estab`, `pr`.`imagem_produto`, `pr`.`id_categoria` ORDER BY count(`p`.`id_pedido`) DESC LIMIT 0, 3 ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id_admin`);

--
-- Índices de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD PRIMARY KEY (`id_avaliacao`),
  ADD KEY `fk_pedido_avaliacao` (`id_pedido`);

--
-- Índices de tabela `categorias_chamado`
--
ALTER TABLE `categorias_chamado`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `fk_endereco_cliente` (`id_cliente`),
  ADD KEY `fk_endereco_cliente1` (`id_endereco`);

--
-- Índices de tabela `estabelecimentos`
--
ALTER TABLE `estabelecimentos`
  ADD PRIMARY KEY (`id_estab`);

--
-- Índices de tabela `formas_pagamentos`
--
ALTER TABLE `formas_pagamentos`
  ADD PRIMARY KEY (`id_formapag`);

--
-- Índices de tabela `grades_horario`
--
ALTER TABLE `grades_horario`
  ADD PRIMARY KEY (`id_grade`),
  ADD KEY `fk_horario_estab` (`id_estab`);

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
  ADD KEY `fk_itens_produtos` (`id_produto`),
  ADD KEY `fk_itens_produto1` (`id_pedido`);

--
-- Índices de tabela `logs_tokens`
--
ALTER TABLE `logs_tokens`
  ADD PRIMARY KEY (`id_token`);

--
-- Índices de tabela `mensagens_cliente`
--
ALTER TABLE `mensagens_cliente`
  ADD PRIMARY KEY (`id_mensagem`);

--
-- Índices de tabela `mensagens_estab`
--
ALTER TABLE `mensagens_estab`
  ADD PRIMARY KEY (`id_mensagem`);

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
-- Índices de tabela `planos`
--
ALTER TABLE `planos`
  ADD PRIMARY KEY (`id_plano`);

--
-- Índices de tabela `planos_estabelecimentos`
--
ALTER TABLE `planos_estabelecimentos`
  ADD KEY `fk_planos_estabelecimento` (`id_estab`),
  ADD KEY `fk_planos` (`id_plano`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_produto`),
  ADD KEY `fk_produtos_categoria` (`id_categoria`),
  ADD KEY `fk_produtos_estab` (`id_estab`);

--
-- Índices de tabela `produtos_favoritos`
--
ALTER TABLE `produtos_favoritos`
  ADD KEY `fk_produto_favorito` (`id_produto`),
  ADD KEY `fk_cliente_favorito` (`id_cliente`);

--
-- Índices de tabela `status_pedidos`
--
ALTER TABLE `status_pedidos`
  ADD PRIMARY KEY (`id_status`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  MODIFY `id_avaliacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categorias_chamado`
--
ALTER TABLE `categorias_chamado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `categorias_produtos`
--
ALTER TABLE `categorias_produtos`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `enderecos`
--
ALTER TABLE `enderecos`
  MODIFY `id_endereco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `estabelecimentos`
--
ALTER TABLE `estabelecimentos`
  MODIFY `id_estab` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `formas_pagamentos`
--
ALTER TABLE `formas_pagamentos`
  MODIFY `id_formapag` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `grades_horario`
--
ALTER TABLE `grades_horario`
  MODIFY `id_grade` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de tabela `historico_clientes`
--
ALTER TABLE `historico_clientes`
  MODIFY `id_alteracao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de tabela `historico_estabelecimentos`
--
ALTER TABLE `historico_estabelecimentos`
  MODIFY `id_alteracao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de tabela `itens_pedidos`
--
ALTER TABLE `itens_pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `logs_tokens`
--
ALTER TABLE `logs_tokens`
  MODIFY `id_token` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de tabela `mensagens_cliente`
--
ALTER TABLE `mensagens_cliente`
  MODIFY `id_mensagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `mensagens_estab`
--
ALTER TABLE `mensagens_estab`
  MODIFY `id_mensagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `planos`
--
ALTER TABLE `planos`
  MODIFY `id_plano` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de tabela `status_pedidos`
--
ALTER TABLE `status_pedidos`
  MODIFY `id_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD CONSTRAINT `fk_pedido_avaliacao` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`);

--
-- Restrições para tabelas `enderecos_clientes`
--
ALTER TABLE `enderecos_clientes`
  ADD CONSTRAINT `fk_endereco_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `fk_enderecos` FOREIGN KEY (`id_endereco`) REFERENCES `enderecos` (`id_endereco`);

--
-- Restrições para tabelas `grades_horario`
--
ALTER TABLE `grades_horario`
  ADD CONSTRAINT `fk_horario_estab` FOREIGN KEY (`id_estab`) REFERENCES `estabelecimentos` (`id_estab`);

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
  ADD CONSTRAINT `fk_itens_produto1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `fk_itens_produtos` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_endereco_pedidos` FOREIGN KEY (`endereco`) REFERENCES `enderecos` (`id_endereco`),
  ADD CONSTRAINT `fk_pagamento_pedido` FOREIGN KEY (`forma_pagamento`) REFERENCES `formas_pagamentos` (`id_formapag`),
  ADD CONSTRAINT `fk_pedidos_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `fk_status_pedidos` FOREIGN KEY (`status_entrega`) REFERENCES `status_pedidos` (`id_status`);

--
-- Restrições para tabelas `planos_estabelecimentos`
--
ALTER TABLE `planos_estabelecimentos`
  ADD CONSTRAINT `fk_planos` FOREIGN KEY (`id_plano`) REFERENCES `planos` (`id_plano`),
  ADD CONSTRAINT `fk_planos_estabelecimento` FOREIGN KEY (`id_estab`) REFERENCES `estabelecimentos` (`id_estab`);

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_produtos_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias_produtos` (`id_categoria`),
  ADD CONSTRAINT `fk_produtos_estab` FOREIGN KEY (`id_estab`) REFERENCES `estabelecimentos` (`id_estab`);

--
-- Restrições para tabelas `produtos_favoritos`
--
ALTER TABLE `produtos_favoritos`
  ADD CONSTRAINT `fk_cliente_favorito` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `fk_produto_favorito` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;