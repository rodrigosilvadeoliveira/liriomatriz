-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 17-Set-2023 às 23:47
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `lojalirio`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cadastroadm`
--

CREATE TABLE `cadastroadm` (
  `id` int(11) NOT NULL,
  `nome` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `celular` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nivel_acesso` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `cadastroadm`
--

INSERT INTO `cadastroadm` (`id`, `nome`, `usuario`, `senha`, `email`, `telefone`, `celular`, `nivel_acesso`) VALUES
(3, 'Rodrigo Silva', 'rodrigo', '12355', 'qarodrigo', '1122233225', '11982780186', 'voluntario'),
(4, 'Rodrigo Oliveira', 'teste', '1212', 'qarodrigo', '1122233225', '11982780186', 'Admin'),
(5, 'teste', 'teste1', 'teste', 'teste@gmail.com', '22222222', '1112233', 'Admin');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cadastrovol`
--

CREATE TABLE `cadastrovol` (
  `id` int(11) NOT NULL,
  `nome` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `celular` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `cadastrovol`
--

INSERT INTO `cadastrovol` (`id`, `nome`, `usuario`, `senha`, `email`, `telefone`, `celular`) VALUES
(1, 'Rodrigo teste', 'vol', '2525', 'qarodrigo@gmail.com', '1122233225', '11982780186'),
(2, 'Rodrigo Voluntario', 'voluntario', '5555', '5556@gmail.com', '555555', '55555556'),
(3, 'Roberta', 'Roberta', '1414', 'teste@teste.com', '1196326558', '1123265698');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pagamento`
--

CREATE TABLE `pagamento` (
  `id` int(11) NOT NULL,
  `valorTotal` decimal(10,2) NOT NULL,
  `tipodePagamento` varchar(15) NOT NULL,
  `datas` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `pagamento`
--

INSERT INTO `pagamento` (`id`, `valorTotal`, `tipodePagamento`, `datas`, `hora`) VALUES
(1, '75.92', 'credito', '2023-08-24', '22:14:50'),
(2, '116.62', 'pix', '2023-08-24', '22:15:41'),
(3, '81.40', 'pix', '2023-08-28', '11:59:10'),
(4, '40.70', 'debito', '2023-08-28', '19:58:39'),
(5, '40.70', 'dinheiro', '2023-08-30', '15:30:33'),
(6, '40.70', 'pix', '2023-08-30', '15:33:02'),
(7, '40.70', 'credito', '2023-08-30', '15:35:45'),
(8, '40.70', 'pix', '2023-08-30', '15:37:28'),
(9, '40.70', 'debito', '2023-08-31', '09:05:13'),
(10, '146.90', 'pix', '2023-09-05', '20:07:39'),
(11, '40.70', 'debito', '2023-09-17', '16:44:21');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(7) NOT NULL,
  `barra` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `produto` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `modelo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tamanho` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `valordevenda` decimal(10,2) NOT NULL,
  `estoque` int(5) NOT NULL,
  `valordecompra` decimal(10,2) NOT NULL,
  `fornecedor` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `datas` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `barra`, `produto`, `modelo`, `tamanho`, `categoria`, `valordevenda`, `estoque`, `valordecompra`, `fornecedor`, `datas`, `hora`) VALUES
(1, '123456789', 'Camisa Honra', 'Manga curta', 'M 16', 'Vestuário', '40.70', 37, '20.45', 'camisas gospel', '2023-08-31', '15:37:28'),
(4, '3232559654', 'Biblia', 'Capa dura amarela', 'GG', 'Biblias', '35.22', 25, '10.00', 'Biblias Lirio', '2023-08-31', '08:53:23'),
(5, '556688994', 'Livro a vida de Jesus', 'Capa dura', 'Médi', 'Livros', '35.40', 0, '10.20', 'Livraria', '2023-08-30', '22:36:12'),
(6, '565231458', 'Pulseira', 'dourado estrela', 'P', 'Acessorios', '5.00', 2, '0.50', 'teste', '2023-08-31', '09:19:35'),
(8, '65236987744', 'Bone', 'azul', 'Unico', 'Vestuario', '11.99', 22, '5.50', 'Bones', '2023-09-05', '20:19:05');

-- --------------------------------------------------------

--
-- Estrutura da tabela `vendas`
--

CREATE TABLE `vendas` (
  `id` int(11) NOT NULL,
  `barra` int(15) NOT NULL,
  `produto` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `modelo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tamanho` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` varchar(30) NOT NULL,
  `valordevenda` decimal(10,2) NOT NULL,
  `valordecompra` decimal(10,2) NOT NULL,
  `usuario` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `datas` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `vendas`
--

INSERT INTO `vendas` (`id`, `barra`, `produto`, `modelo`, `tamanho`, `categoria`, `valordevenda`, `valordecompra`, `usuario`, `datas`, `hora`) VALUES
(1, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', '', '40.70', '20.45', 'teste', '2023-08-24', '22:14:51'),
(2, 2147483647, 'Biblia', 'Capa dura amarela', 'GG', '', '35.22', '10.00', 'teste', '2023-08-24', '22:14:51'),
(3, 2147483647, 'Biblia', 'Capa dura amarela', 'GG', '', '35.22', '10.00', 'teste', '2023-08-24', '22:15:41'),
(4, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', '', '40.70', '20.45', 'teste', '2023-08-24', '22:15:41'),
(5, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', '', '40.70', '20.45', 'teste', '2023-08-24', '22:15:41'),
(6, 123456789, 'Camisa Honra', 'Manga curta', 'M 17', 'Camisas Honra', '40.70', '20.45', 'vol', '2023-08-28', '11:59:10'),
(8, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', '', '40.70', '20.45', 'teste', '2023-08-28', '19:58:39'),
(9, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', '', '40.70', '20.45', 'teste', '2023-08-30', '15:30:33'),
(10, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', '', '40.70', '20.45', 'teste', '2023-08-30', '15:33:02'),
(11, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', '', '40.70', '20.45', 'teste', '2023-08-30', '15:35:45'),
(12, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', 'Vestuário', '40.70', '20.45', 'teste', '2023-08-30', '15:37:28'),
(13, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', 'Vestuário', '40.70', '20.45', 'novo', '2023-08-31', '09:05:13'),
(14, 123456789, '', 'Manga curta', 'M 16', 'Vestuário', '40.90', '20.45', '', '2023-09-05', '20:07:39'),
(15, 556688994, 'Livro a vida de Jesus', 'Capa dura', 'Médi', 'Livros', '35.40', '10.20', 'teste', '2023-09-05', '20:07:39'),
(16, 556688994, 'Livro a vida de Jesus', 'Capa dura', 'Médi', 'Livros', '35.40', '10.20', 'teste', '2023-09-05', '20:07:39'),
(17, 556688994, 'Livro a vida de Jesus', 'Capa dura', 'Médi', 'Livros', '35.40', '10.20', 'teste', '2023-09-05', '20:07:39'),
(18, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', 'Vestuário', '40.70', '20.45', 'teste', '2023-09-17', '16:44:21');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cadastroadm`
--
ALTER TABLE `cadastroadm`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `cadastrovol`
--
ALTER TABLE `cadastrovol`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `pagamento`
--
ALTER TABLE `pagamento`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cadastroadm`
--
ALTER TABLE `cadastroadm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `cadastrovol`
--
ALTER TABLE `cadastrovol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `pagamento`
--
ALTER TABLE `pagamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
