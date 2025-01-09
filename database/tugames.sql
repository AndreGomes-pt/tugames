-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 21-Nov-2024 às 09:42
-- Versão do servidor: 5.7.44-log-cll-lve
-- versão do PHP: 8.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `valdirpr_tugames`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `id_carrinho` int(11) NOT NULL,
  `id_utilizador` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT '1',
  `adicionado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `carrinho_utilizador`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `carrinho_utilizador` (
`id_carrinho` int(11)
,`quantidade` int(11)
,`nome_produto` varchar(100)
,`primeira_foto` mediumtext
,`preco` decimal(10,2)
,`id_utilizador` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nome_categoria` varchar(100) NOT NULL,
  `capa` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nome_categoria`, `capa`) VALUES
(16, 'PlayStation', '6707bf7d70152.png'),
(17, 'Xbox', '6707be0823567.png'),
(18, 'Nintendo Switch', '6707be0341a70.png'),
(19, 'PC', '6707bdfdc5067.png');

-- --------------------------------------------------------

--
-- Estrutura da tabela `dadosenvio`
--

CREATE TABLE `dadosenvio` (
  `id_dadosenvio` int(11) NOT NULL,
  `nome_cliente` varchar(100) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `morada` varchar(255) DEFAULT NULL,
  `id_utilizador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `encomendas`
--

CREATE TABLE `encomendas` (
  `id_encomenda` varchar(10) NOT NULL,
  `id_dadosenvio` int(11) NOT NULL,
  `data_encomenda` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `preco_total` decimal(10,2) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `data_entrega` timestamp NULL DEFAULT NULL,
  `id_utilizador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Acionadores `encomendas`
--
DELIMITER $$
CREATE TRIGGER `before_insert_encomenda` BEFORE INSERT ON `encomendas` FOR EACH ROW BEGIN
    -- Define a data de entrega como 15 dias a partir da data atual
    SET NEW.data_entrega = CURDATE() + INTERVAL 15 DAY;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `historicocompras`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `historicocompras` (
`nome_utilizador` varchar(50)
,`id_encomenda` varchar(10)
,`data_encomenda` timestamp
,`nome_produto` varchar(100)
,`quantidade` int(11)
,`preco_total` decimal(20,2)
);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id_produto` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `stock` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `id_categoria` int(11) DEFAULT '1',
  `fotos` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id_produto`, `nome`, `descricao`, `stock`, `preco`, `id_categoria`, `fotos`) VALUES
(126, 'The Last of Us Part II', 'Uma narrativa poderosa em um mundo pós-apocalíptico, onde a jornada de Ellie se entrelaça com temas de vingança e redempção. Explore ambientes impressionantes e enfrente perigos tanto humanos quanto infectados neste emocionante jogo de ação e aventura.', 20, 59.99, 16, 'the_last_of_us_part_ii_6707c96995d74.jpg'),
(127, 'God of War', 'Uma aventura épica onde Kratos, o Deus da Guerra, embarca em uma jornada com seu filho Atreus através da mitologia nórdica. O jogo combina combate intenso com uma narrativa profunda sobre paternidade e redenção.', 10, 49.99, 16, 'god_of_war_6707c96465d74.jpg'),
(128, 'Spider-Man: Miles Morales', 'Uma nova aventura emocionante com o Homem-Aranha, onde Miles Morales assume o manto e enfrenta novos desafios em Nova York. Aproveite poderes únicos e explore uma cidade vibrante enquanto luta contra vilões.', 15, 49.99, 16, 'spider-man_miles_morales_6707c9601dffb.JPG'),
(129, 'Demon’s Souls', 'Um remake do clássico jogo de ação e RPG, onde os jogadores devem enfrentar desafios imensos e inimigos poderosos em um mundo gótico. Cada batalha testará suas habilidades e táticas.', 8, 69.99, 16, 'demons_souls_6707c95ba992c.jpg'),
(130, 'Ratchet & Clank: Rift Apart', 'Uma nova aventura interdimensional com Ratchet e Clank, recheada de humor e ação. Utilize armas criativas enquanto viaja entre mundos em uma narrativa cheia de reviravoltas.', 12, 59.99, 16, 'ratchet__clank_rift_apart_6707c9561f4e0.jpg'),
(131, 'Halo Infinite', 'A nova saga do Master Chief, repleta de ação e exploração em um vasto mundo aberto. Junte-se à luta contra forças alienígenas e descubra os segredos de Zeta Halo.', 25, 59.99, 17, 'halo_infinite_6707ca05a74e6.jpg'),
(132, 'Forza Horizon 5', 'Um emocionante jogo de corrida em um mundo aberto que recria com detalhes o México. Personalize seus carros e participe de competições em paisagens deslumbrantes.', 30, 49.99, 17, 'forza_horizon_5_6707ca0143cb7.jpg'),
(133, 'Gears 5', 'A nova aventura da série Gears of War, onde os jogadores devem lutar pela sobrevivência em um mundo devastado. Desfrute de uma campanha envolvente e modos multiplayer emocionantes.', 15, 39.99, 17, 'gears_5_6707c9fbdd0ea.png'),
(134, 'Sea of Thieves', 'Uma aventura multiplayer de piratas que permite aos jogadores explorar ilhas, buscar tesouros e se envolver em combates navais épicos com outros jogadores.', 10, 39.99, 17, 'sea_of_thieves_6707c9e7cc501.jpeg'),
(135, 'State of Decay 2', 'Um jogo de sobrevivência em um mundo pós-apocalíptico, onde os jogadores devem construir uma comunidade e gerenciar recursos enquanto enfrentam hordas de zumbis.', 5, 29.99, 17, 'state_of_decay_2_6707c9e151993.png'),
(136, 'The Legend of Zelda: Breath of the Wild', 'Um jogo de ação e aventura em um vasto mundo aberto, onde Link deve salvar a princesa Zelda e derrotar Calamity Ganon. Explore, resolva quebra-cabeças e descubra segredos em Hyrule.', 4, 59.99, 18, 'the_legend_of_zelda_breath_of_the_wild_6707caa3c8539.jpg'),
(137, 'Animal Crossing: New Horizons', 'Construa e gerencie sua própria ilha, interagindo com adoráveis habitantes. O jogo oferece uma experiência relaxante de simulação de vida e personalização.', 10, 49.99, 18, 'animal_crossing_new_horizons_6707caa794d21.jpg'),
(138, 'Super Mario Odyssey', 'Uma aventura de plataforma com Mario viajando por diversos mundos em busca de sua princesa. O jogo combina jogabilidade clássica com novos poderes e mecânicas inovadoras.', 12, 49.99, 18, 'super_mario_odyssey_6707ca9d6163d.jpg'),
(139, 'Splatoon 2', 'Um jogo de tiro multiplayer colorido e divertido, onde equipes competem em batalhas de tinta em arenas vibrantes. Utilize armas únicas e táticas para dominar o campo de batalha.', 20, 39.99, 18, 'splatoon_2_6707ca9826664.jpg'),
(140, 'Metroid Dread', 'Uma nova aventura de Metroid em 2D, onde os jogadores exploram ambientes variados e enfrentam inimigos desafiadores. Descubra segredos e obtenha novos poderes enquanto luta pela sobrevivência.', 8, 59.99, 18, 'metroid_dread_6707ca923ba69.png'),
(141, 'The Witcher 3: Wild Hunt', 'Um RPG de mundo aberto baseado em um mundo de fantasia, onde o jogador assume o papel de Geralt de Rivia em uma busca por sua filha. Envolva-se em missões ricas e um mundo repleto de escolhas.', 25, 39.99, 19, 'the_witcher_3_wild_hunt_6707c88aa4dd1.jpg'),
(142, 'Cyberpunk 2077', 'Um RPG de ação ambientado em um futuro distópico, onde os jogadores exploram um mundo aberto e tomam decisões que afetam o desenrolar da história. Personalize seu personagem e descubra segredos.', 12, 29.99, 19, 'cyberpunk_2077_6707c69030bf4.jpg'),
(143, 'Dota 2', 'Um jogo de batalha em equipe e estratégia, onde os jogadores lutam em intensos confrontos. Escolha entre uma variedade de heróis e desenvolva estratégias para vencer as partidas.', 20, 0.00, 19, 'dota_2_6707c68ac6ea4.jpg'),
(144, 'Counter-Strike: Global Offensive', 'Um jogo de tiro em primeira pessoa altamente competitivo, onde equipes se enfrentam em modos variados. Utilize habilidades táticas e trabalho em equipe para dominar o campo de batalha.', 10, 14.99, 19, 'counter-strike_global_offensive_6707c89074f3a.jpg'),
(145, 'League of Legends', 'Um dos jogos mais populares de estratégia e batalha em equipe, onde os jogadores controlam campeões únicos e se enfrentam em arenas. Envolva-se em jogabilidade estratégica e intensa.', 15, 0.00, 19, 'league_of_legends_6707c68008970.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos_encomendas`
--

CREATE TABLE `produtos_encomendas` (
  `encomenda_produtoId` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_encomenda` varchar(10) DEFAULT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
--

CREATE TABLE `utilizadores` (
  `id_utilizador` int(11) NOT NULL,
  `adm` tinyint(1) NOT NULL DEFAULT '0',
  `nome` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `palavra_passe` varchar(255) NOT NULL,
  `data_adesao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `utilizadores`
--

INSERT INTO `utilizadores` (`id_utilizador`, `adm`, `nome`, `email`, `palavra_passe`, `data_adesao`) VALUES
(11, 0, 'demo', 'demo@demo.com', '$2y$10$LJ1JJFaw7dNXy74IOEXHa.MhhUuUaqD/N8euvmrMPB2YzCt/UElXq', '2024-11-21 09:41:32'),
(12, 1, 'adminDemo', 'adminDemo@adminDemo.com', '$2y$10$t2MhpTqWMnfI5G7CtLsJw.mK/yGebh/lWedmMTw5D7VzVIc7iloSq', '2024-11-21 09:41:47');

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `view_produtos`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `view_produtos` (
`id_produto` int(11)
,`nome_produto` varchar(100)
,`descricao` text
,`preco` decimal(10,2)
,`stock` int(11)
,`todas_fotos` text
,`id_categoria` int(11)
,`nome_categoria` varchar(100)
);

-- --------------------------------------------------------

--
-- Estrutura para vista `carrinho_utilizador`
--
DROP TABLE IF EXISTS `carrinho_utilizador`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `carrinho_utilizador`  AS SELECT `c`.`id_carrinho` AS `id_carrinho`, `c`.`quantidade` AS `quantidade`, `p`.`nome` AS `nome_produto`, substring_index(`p`.`fotos`,',',1) AS `primeira_foto`, `p`.`preco` AS `preco`, `c`.`id_utilizador` AS `id_utilizador` FROM (`carrinho` `c` join `produtos` `p` on((`c`.`id_produto` = `p`.`id_produto`))) ;

-- --------------------------------------------------------

--
-- Estrutura para vista `historicocompras`
--
DROP TABLE IF EXISTS `historicocompras`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `historicocompras`  AS SELECT `u`.`nome` AS `nome_utilizador`, `e`.`id_encomenda` AS `id_encomenda`, `e`.`data_encomenda` AS `data_encomenda`, `p`.`nome` AS `nome_produto`, `ei`.`quantidade` AS `quantidade`, (`ei`.`quantidade` * `p`.`preco`) AS `preco_total` FROM (((`utilizadores` `u` join `encomendas` `e` on((`u`.`id_utilizador` = `e`.`id_utilizador`))) join `produtos_encomendas` `ei` on((`e`.`id_encomenda` = `ei`.`id_encomenda`))) join `produtos` `p` on((`ei`.`id_produto` = `p`.`id_produto`))) ;

-- --------------------------------------------------------

--
-- Estrutura para vista `view_produtos`
--
DROP TABLE IF EXISTS `view_produtos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_produtos`  AS SELECT `p`.`id_produto` AS `id_produto`, `p`.`nome` AS `nome_produto`, `p`.`descricao` AS `descricao`, `p`.`preco` AS `preco`, `p`.`stock` AS `stock`, `p`.`fotos` AS `todas_fotos`, `c`.`id_categoria` AS `id_categoria`, `c`.`nome_categoria` AS `nome_categoria` FROM (`produtos` `p` join `categorias` `c` on((`p`.`id_categoria` = `c`.`id_categoria`))) ;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id_carrinho`),
  ADD KEY `id_utilizador` (`id_utilizador`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices para tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Índices para tabela `dadosenvio`
--
ALTER TABLE `dadosenvio`
  ADD PRIMARY KEY (`id_dadosenvio`),
  ADD KEY `fk_dadosenvio_utilizador` (`id_utilizador`);

--
-- Índices para tabela `encomendas`
--
ALTER TABLE `encomendas`
  ADD PRIMARY KEY (`id_encomenda`),
  ADD KEY `id_dadosenvio` (`id_dadosenvio`),
  ADD KEY `fk_id_utilizador` (`id_utilizador`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_produto`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Índices para tabela `produtos_encomendas`
--
ALTER TABLE `produtos_encomendas`
  ADD PRIMARY KEY (`encomenda_produtoId`),
  ADD KEY `id_produto` (`id_produto`),
  ADD KEY `id_encomenda` (`id_encomenda`);

--
-- Índices para tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id_utilizador`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id_carrinho` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `dadosenvio`
--
ALTER TABLE `dadosenvio`
  MODIFY `id_dadosenvio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT de tabela `produtos_encomendas`
--
ALTER TABLE `produtos_encomendas`
  MODIFY `encomenda_produtoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id_utilizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `fk_carrinho_produto` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_carrinho_utilizador` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `dadosenvio`
--
ALTER TABLE `dadosenvio`
  ADD CONSTRAINT `fk_dadosenvio_utilizador` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `encomendas`
--
ALTER TABLE `encomendas`
  ADD CONSTRAINT `fk_encomendas_dadosenvio` FOREIGN KEY (`id_dadosenvio`) REFERENCES `dadosenvio` (`id_dadosenvio`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_encomendas_utilizador` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `produtos_encomendas`
--
ALTER TABLE `produtos_encomendas`
  ADD CONSTRAINT `fk_produtos_encomendas_encomenda` FOREIGN KEY (`id_encomenda`) REFERENCES `encomendas` (`id_encomenda`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_produtos_encomendas_produto` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
