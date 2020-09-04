-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 07-Jun-2019 às 13:16
-- Versão do servidor: 5.7.16
-- PHP Version: 5.6.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_min`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `menu` varchar(20) NOT NULL,
  `cadastrado` datetime NOT NULL,
  `modificado` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `menus`
--

INSERT INTO `menus` (`id`, `menu`, `cadastrado`, `modificado`) VALUES
(1, 'Barra de menu', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Cadastros', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Configurações', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Subpasta', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Não aplicável', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'Relatórios', '2019-06-07 00:00:00', '2019-06-07 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `perfil_tela`
--

CREATE TABLE `perfil_tela` (
  `id` int(11) NOT NULL,
  `cadastrado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  `perfil` int(11) NOT NULL,
  `tela` int(11) NOT NULL,
  `permissao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `perfil_tela`
--

INSERT INTO `perfil_tela` (`id`, `cadastrado`, `modificado`, `perfil`, `tela`, `permissao`) VALUES
(1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 1, 4),
(2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 2, 4),
(3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 3, 4),
(4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, 4),
(5, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 5, 4),
(6, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 11, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `perfis`
--

CREATE TABLE `perfis` (
  `id` int(11) NOT NULL,
  `perfil` varchar(20) NOT NULL,
  `cadastrado` datetime NOT NULL,
  `modificado` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `perfis`
--

INSERT INTO `perfis` (`id`, `perfil`, `cadastrado`, `modificado`) VALUES
(1, 'Developer', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `permissoes`
--

CREATE TABLE `permissoes` (
  `id` int(11) NOT NULL,
  `permissao` varchar(40) NOT NULL,
  `cadastrado` datetime NOT NULL,
  `modificado` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `permissoes`
--

INSERT INTO `permissoes` (`id`, `permissao`, `cadastrado`, `modificado`) VALUES
(1, 'Somente leitura', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Leitura e escrita', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Leitura, escrita e edição', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Controle total', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `cadastrado` datetime NOT NULL,
  `modificado` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `status`
--

INSERT INTO `status` (`id`, `status`, `cadastrado`, `modificado`) VALUES
(1, 'Ativo', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Desativado', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `telas`
--

CREATE TABLE `telas` (
  `id` int(11) NOT NULL,
  `tela` varchar(40) NOT NULL,
  `identificador` varchar(40) NOT NULL,
  `cadastrado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  `menu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `telas`
--

INSERT INTO `telas` (`id`, `tela`, `identificador`, `cadastrado`, `modificado`, `menu`) VALUES
(1, 'Menus', 'menus', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 5),
(2, 'Telas', 'telas', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 3),
(3, 'Perfis', 'perfis', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 3),
(4, 'Telas do perfil', 'perfil_tela', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 4),
(5, 'Usuários', 'usuarios', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 3),
(6, 'Status', 'status', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 5),
(7, 'Permissões', 'permissoes', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 5),
(8, 'Dashboard', 'dashboard', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(9, 'Minha conta', 'minha_conta', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(10, 'Mudar foto', 'mudar_foto', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(11, 'Temas', 'temas', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `temas`
--

CREATE TABLE `temas` (
  `id` int(11) NOT NULL,
  `tema` varchar(20) NOT NULL,
  `identificador` varchar(20) NOT NULL,
  `cor_primaria` varchar(6) NOT NULL,
  `cor_secundaria` varchar(6) NOT NULL,
  `cor_texto` varchar(6) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `cadastrado` datetime NOT NULL,
  `modificado` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `temas`
--

INSERT INTO `temas` (`id`, `tema`, `identificador`, `cor_primaria`, `cor_secundaria`, `cor_texto`, `foto`, `cadastrado`, `modificado`) VALUES
(1, 'Dark blue grey', 'gz-dark-blue-grey', '455a64', '607d8b', 'ffffff', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(120) NOT NULL,
  `email` varchar(120) NOT NULL,
  `senha` varchar(20) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `cadastrado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  `perfil` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `tema` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `email`, `senha`, `foto`, `cadastrado`, `modificado`, `perfil`, `status`, `tema`) VALUES
(1, 'Developer', 'root@wtag.com.br', 'admin', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `perfil_tela`
--
ALTER TABLE `perfil_tela`
  ADD PRIMARY KEY (`id`),
  ADD KEY `perfil` (`perfil`),
  ADD KEY `tela` (`tela`),
  ADD KEY `permissao` (`permissao`);

--
-- Indexes for table `perfis`
--
ALTER TABLE `perfis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissoes`
--
ALTER TABLE `permissoes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `telas`
--
ALTER TABLE `telas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu` (`menu`);

--
-- Indexes for table `temas`
--
ALTER TABLE `temas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `perfil` (`perfil`),
  ADD KEY `tema` (`tema`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `perfil_tela`
--
ALTER TABLE `perfil_tela`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `perfis`
--
ALTER TABLE `perfis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `permissoes`
--
ALTER TABLE `permissoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `telas`
--
ALTER TABLE `telas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `temas`
--
ALTER TABLE `temas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `perfil_tela`
--
ALTER TABLE `perfil_tela`
  ADD CONSTRAINT `perfil_tela_ibfk_1` FOREIGN KEY (`perfil`) REFERENCES `perfis` (`id`),
  ADD CONSTRAINT `perfil_tela_ibfk_2` FOREIGN KEY (`permissao`) REFERENCES `permissoes` (`id`),
  ADD CONSTRAINT `perfil_tela_ibfk_3` FOREIGN KEY (`tela`) REFERENCES `telas` (`id`);

--
-- Limitadores para a tabela `telas`
--
ALTER TABLE `telas`
  ADD CONSTRAINT `telas_ibfk_1` FOREIGN KEY (`menu`) REFERENCES `menus` (`id`);

--
-- Limitadores para a tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_3` FOREIGN KEY (`status`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `usuarios_ibfk_6` FOREIGN KEY (`perfil`) REFERENCES `perfis` (`id`),
  ADD CONSTRAINT `usuarios_ibfk_7` FOREIGN KEY (`tema`) REFERENCES `temas` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
