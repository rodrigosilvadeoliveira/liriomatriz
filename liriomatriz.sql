-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22-Abr-2025 às 17:14
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
-- Banco de dados: `liriomatriz`
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
(4, 'Rodrigo Oliveira', 'teste', '1212', 'qarodrigo@gmail.com', '1122233225', '11982780186', 'admin'),
(5, 'teste', 'teste1', 'teste', 'teste@gmail.com', '22222222', '1112233', 'admin'),
(6, 'Organizador Silva de Oliveira', 'midias', '1221', 'org@gmail.com', '1123245566', '11982780011', 'midia'),
(7, 'RODRIGO SILVA DE OLIVEIRA', 'org', '123123', 'tstrodrigoso@gmail.com', '11982780186', '55555556', 'secretaria'),
(8, 'RODRIGO SILVA DE OLIVEIRA', 'master', 'Digodw19', 'tstrodrigoso@gmail.com', '11982780189', '11982780186', 'master');

-- --------------------------------------------------------

--
-- Estrutura da tabela `evento`
--

CREATE TABLE `evento` (
  `id` int(5) NOT NULL,
  `imagem` varchar(50) NOT NULL,
  `cartaz` varchar(12) NOT NULL,
  `inscricao` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `links` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomeevento` varchar(30) NOT NULL,
  `inicio` date DEFAULT NULL,
  `fim` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `evento`
--

INSERT INTO `evento` (`id`, `imagem`, `cartaz`, `inscricao`, `links`, `nomeevento`, `inicio`, `fim`) VALUES
(7, './img/porvoce.jpeg', 'programacao', '', '', '', NULL, NULL),
(8, './img/lirio_igreja.jpg', 'programacao', '', '', '', NULL, NULL),
(9, './img/pra.jpeg', '', '', '', '', NULL, NULL),
(18, './img/gcmulheres.jpeg', 'programacao', '', '', '', NULL, NULL),
(20, './img/gchomens.jpeg', 'programacao', '', '', 'Quinta be power 10.02', NULL, NULL),
(28, './img/lirio_igreja.jpg', 'home', '', 'videosSite.php', '', NULL, NULL),
(29, './img/porvoce.jpeg', 'home', '', 'novoComecoSite.php', '', NULL, NULL),
(31, './img/Volutariado.jpeg', 'home', '', 'voluntariadoSite.php', '', NULL, NULL),
(50, '', 'formulario', 'curso_arena', 'https://docs.google.com/forms/d/e/1FAIpQLSfIQWHrh-sMw6rVoDg-PSh2syVPn36i91ZUI67nbi2Yn9-yOA/viewform?embedded=true', '', '2025-03-12', '2025-03-28'),
(55, '', 'formulario', 'voluntariado_midias', 'https://docs.google.com/forms/d/e/1FAIpQLSfIQWHrh-sMw6rVoDg-PSh2syVPn36i91ZUI67nbi2Yn9-yOA/viewform?embedded=true', '', '2025-03-14', '2025-03-26'),
(59, './img/dizimos.jpeg', 'carrousel', '', '', 'Contribuir com Amor', NULL, NULL),
(62, './img/AmoraCasa.jpeg', 'carrousel', '', '', 'Amor a Casa', NULL, NULL),
(64, './img/fervor2025.jpeg', 'carrousel', '', '', 'Fervor 2025', NULL, NULL),
(73, './img/Progamacao.jpeg', 'home', '', 'programacaoSite.php', 'promogramaçaoHome', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `log_login`
--

CREATE TABLE `log_login` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nivel_acesso` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_login` date NOT NULL,
  `hora_login` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `log_login`
--

INSERT INTO `log_login` (`id`, `usuario`, `nivel_acesso`, `data_login`, `hora_login`) VALUES
(1, 'org', 'organizador', '2025-04-21', '00:00:00'),
(2, 'midias', 'midia', '2025-04-22', '00:25:53'),
(3, 'master', 'master', '2025-04-22', '00:28:05'),
(4, 'teste', 'admin', '2025-04-22', '15:31:35'),
(5, 'teste', 'admin', '2025-04-22', '15:35:33'),
(6, 'org', 'secretaria', '2025-04-22', '15:51:02'),
(7, 'teste', 'admin', '2025-04-22', '16:06:01'),
(8, 'midias', 'midia', '2025-04-22', '16:41:40'),
(9, 'teste', 'admin', '2025-04-22', '17:13:08'),
(10, 'org', 'secretaria', '2025-04-22', '17:13:18'),
(11, 'midias', 'midia', '2025-04-22', '17:13:32');

-- --------------------------------------------------------

--
-- Estrutura da tabela `membros`
--

CREATE TABLE `membros` (
  `id` int(5) NOT NULL,
  `nome` text NOT NULL,
  `sobrenome` text NOT NULL,
  `nascimento` date NOT NULL,
  `batizado` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `datas` date NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `email` varchar(40) NOT NULL,
  `voluntario` varchar(5) NOT NULL,
  `lider` varchar(15) NOT NULL,
  `departamentoum` varchar(15) NOT NULL,
  `departamentodois` varchar(15) NOT NULL,
  `departamentotres` varchar(15) NOT NULL,
  `status` varchar(8) NOT NULL,
  `idade` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsavel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `membros`
--

INSERT INTO `membros` (`id`, `nome`, `sobrenome`, `nascimento`, `batizado`, `datas`, `telefone`, `email`, `voluntario`, `lider`, `departamentoum`, `departamentodois`, `departamentotres`, `status`, `idade`, `responsavel`, `foto`) VALUES
(1, 'RODRIGO', 'DE OLIVEIRA', '2021-09-08', 'sim', '2020-01-01', '2147483647', 'tstrodrigoso@gmail.com', 'não', 'não', 'recepcao', 'teatro', 'midias', 'ativo', '3', '', ''),
(17, 'RODRIGO', 'batismo', '2021-04-02', 'sim', '0000-00-00', '119827809736', 'tstrodrigoso@gmail.com', 'sim', 'não', 'recepcao', 'staff', '', 'ativo', '4', '', ''),
(26, 'RODRIGO', 'DE OLIVEIRA', '2001-02-12', 'sim', '0000-00-00', '11982780186', 'tstrodrigoso@gmail.com', 'não', 'não', '', '', '', 'ativo', '24', 'teste', ''),
(27, 'RODRIGO', 'DE OLIVEIRA', '2012-12-12', 'não', '2024-07-02', '11982780186', 'tstrodrigoso@gmail.com', 'não', 'teatro', '', '', '', 'ativo', '12', 'novo teste', ''),
(28, 'Maria', 'Teresa Santos', '2010-10-01', 'não', '2012-02-09', '11983377792', '', 'sim', 'teatro', 'visitas', '', '', 'ativo', '14', 'teste sdfghjk', ''),
(29, 'RODRIGO', 'DE OLIVEIRA', '2025-04-08', 'não', '2025-04-08', '11982780186', 'tstrodrigoso@gmail.com', 'sim', 'não', 'recepcao', 'som', 'sonoplastia', 'ativo', '0', 'Maria de Lourdes da silva de Oliveira', 'foto_67feba4ab0f0a.jpg'),
(30, 'Teste Foto perfil dois', 'DE OLIVEIRA', '2024-09-03', 'sim', '2024-11-08', '1133333333', 'fotoperfil@gmail.com', 'não', 'salavoluntarios', 'Staff', 'Teatro', 'Som', 'nãoAtivo', '0', 'teste', 'foto_67febd1198aa4.jpg'),
(31, 'RODRIGO', 'Silva DE OLIVEIRA', '2025-04-03', 'não', '2025-04-07', '11982780186', 'tstrodrigoso@gmail.com', 'sim', 'não', 'GC_casados', 'gcjovens', 'loja', 'ativo', '0', '', 'foto_67ffa10b56442.jpg'),
(32, 'Suellen', 'Martins de Oliveira', '1990-02-10', 'sim', '2020-04-11', '11983377337', 'teste@teste.com', 'sim', 'criativo', 'Consagracao', 'Consagracao', 'Sala_voluntario', 'ativo', '35', 'Elizete', 'foto_67ffb1b36f6bd.jpg'),
(33, 'João', 'Marino', '2010-10-10', 'não', '2021-01-21', '1192883777', '', 'sim', 'consagracao', 'Coral', 'Coral', 'GC_Homens', 'ativo', '14', '', 'foto_6800170fcb454.jpg'),
(34, 'Magda', 'Ramos Luoreiro', '1900-11-01', 'sim', '2010-03-02', '1198227733', 'testelirio@gmail.com', 'sim', 'não', 'GC_Homens', 'Coral', 'Staff', 'ativo', '124', '', 'foto_68010951d56a4.jpg'),
(35, 'Lucas', 'oliveira', '2001-10-01', 'não', '2022-11-27', '11982780186', 'tstrodrigoso@gmail.com', 'sim', 'não', 'Criativo', 'Criativo', 'Criativo', 'ativo', '23', 'Maria de Lourdes da silva de Oliveira', 'foto_680137dfe1b2d.jpg'),
(36, 'Mauro', 'Pereira prado', '1999-01-21', 'não', '2002-10-01', '9817722277', 'testedoemail@email.com', 'sim', 'loja', 'Criativo', '', 'Criativo', 'ativo', '26', '', 'foto_6804164c46b22.jpg'),
(37, 'Paulo', 'Henrique', '1980-02-11', 'sim', '2000-06-11', '1988277733', 'teste@gmail.com', 'sim', 'louvor', 'Kids', 'Oficiais', '', 'ativo', '45', '', 'foto_6804ffac3a919.jpg');

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
(11, '40.70', 'debito', '2023-09-17', '16:44:21'),
(12, '75.92', 'pix', '2025-02-16', '11:43:27'),
(13, '75.92', 'pix', '2025-03-12', '10:22:02'),
(14, '40.70', 'credito', '2025-03-24', '14:29:52'),
(15, '75.92', 'pix', '2025-03-24', '14:32:32'),
(16, '75.92', 'credito', '2025-03-24', '14:39:23'),
(17, '122.10', 'dinheiro', '2025-03-24', '15:24:25'),
(18, '40.70', 'credito', '2025-03-24', '15:26:27'),
(19, '70.62', 'pix', '2025-04-20', '10:15:55');

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
  `carroussel` varchar(15) NOT NULL,
  `imagem` varchar(50) NOT NULL,
  `datas` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `barra`, `produto`, `modelo`, `tamanho`, `categoria`, `valordevenda`, `estoque`, `valordecompra`, `fornecedor`, `carroussel`, `imagem`, `datas`, `hora`) VALUES
(1, '123456789', 'Camisa Honra', 'Manga curta', 'M 16', 'Vestuário', '40.70', 28, '20.45', 'camisas gospel', '', '', '2023-08-31', '15:37:28'),
(4, '3232559654', 'Biblia', 'Capa dura amarela', 'GG', 'Biblias', '35.22', 20, '10.00', 'Biblias Lirio', '', './img/porvoce.jpeg', '2023-08-31', '08:53:23'),
(5, '556688994', 'Livro a vida de Jesus', 'Capa dura', 'Médi', 'Livros', '35.40', 0, '10.20', 'Livraria', 'carrousel', './img/louvor.jpeg', '2023-08-30', '22:36:12'),
(6, '565231458', 'Pulseira', 'dourado estrela', 'P', 'Acessorios', '5.00', 2, '0.50', 'teste', 'carrousel', './img/fervor.jpeg', '2023-08-31', '09:19:35'),
(8, '65236987744', 'Bone', 'azul', 'Unico', 'Vestuario', '11.99', 22, '5.50', 'Bones', 'carrousel', './img/teatro.jpeg', '2023-09-05', '20:19:05'),
(12, '', 'Teste', '', '', '', '0.00', 0, '0.00', '', '', '', '2024-12-28', '16:03:03'),
(13, '5666598', 'Teste', 'ttouou', 'm', 'camisa', '16.00', 8, '5.00', 'teste', '', '', '2024-12-28', '16:12:55'),
(14, '88377488', 'novo teste', 'teste', 'M', '', '0.00', 0, '0.00', 'testeForn', '', '', '2024-12-28', '16:14:42');

-- --------------------------------------------------------

--
-- Estrutura da tabela `totalmembros`
--

CREATE TABLE `totalmembros` (
  `id` int(2) NOT NULL,
  `idademenor` int(5) NOT NULL,
  `idademaior` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `totalmembros`
--

INSERT INTO `totalmembros` (`id`, `idademenor`, `idademaior`) VALUES
(1, 5, 8);

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
(1, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', '', '40.70', '20.45', 'teste', '0000-00-00', '22:14:51'),
(21, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', 'Vestuário', '40.70', '20.45', 'teste', '2025-03-12', '10:22:02'),
(22, 2147483647, 'Biblia', 'Capa dura amarela', 'GG', 'Biblias', '35.22', '10.00', 'teste', '2025-03-12', '10:22:02'),
(23, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', 'Vestuário', '40.70', '20.45', 'rodrigo', '2025-03-24', '14:29:53'),
(24, 2147483647, 'Biblia', 'Capa dura amarela', 'GG', 'Biblias', '35.22', '10.00', 'rodrigo', '2025-03-24', '14:32:33'),
(25, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', 'Vestuário', '40.70', '20.45', 'rodrigo', '2025-03-24', '14:32:33'),
(26, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', 'Vestuário', '40.70', '20.45', 'rodrigo', '2025-03-24', '14:39:23'),
(27, 2147483647, 'Biblia', 'Capa dura amarela', 'GG', 'Biblias', '35.22', '10.00', 'rodrigo', '2025-03-24', '14:39:23'),
(28, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', 'Vestuário', '40.70', '20.45', 'rodrigo', '2025-03-24', '15:24:25'),
(29, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', 'Vestuário', '40.70', '20.45', 'rodrigo', '2025-03-24', '15:24:25'),
(30, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', 'Vestuário', '40.70', '20.45', 'rodrigo', '2025-03-24', '15:24:25'),
(31, 123456789, 'Camisa Honra', 'Manga curta', 'M 16', 'Vestuário', '40.70', '20.45', 'rodrigo', '2025-03-24', '15:26:27'),
(32, 2147483647, 'Biblia', 'Capa dura amarela', 'GG', 'Biblias', '35.22', '10.00', 'teste', '2025-04-20', '10:15:55');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cadastroadm`
--
ALTER TABLE `cadastroadm`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `log_login`
--
ALTER TABLE `log_login`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `membros`
--
ALTER TABLE `membros`
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
-- Índices para tabela `totalmembros`
--
ALTER TABLE `totalmembros`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `evento`
--
ALTER TABLE `evento`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT de tabela `log_login`
--
ALTER TABLE `log_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `membros`
--
ALTER TABLE `membros`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de tabela `pagamento`
--
ALTER TABLE `pagamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `totalmembros`
--
ALTER TABLE `totalmembros`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
