-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 15, 2020 at 09:56 PM
-- Server version: 10.3.23-MariaDB-log
-- PHP Version: 7.3.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pop_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `back_AdmArea`
--

CREATE TABLE `back_AdmArea` (
  `area` int(11) NOT NULL,
  `nome` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `cor` varchar(7) CHARACTER SET utf8 DEFAULT NULL,
  `hidden` tinyint(4) DEFAULT 0,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `back_AdmArea`
--

INSERT INTO `back_AdmArea` (`area`, `nome`, `cor`, `hidden`, `isUsed`) VALUES
(1, 'Admin', '#ffffff', 1, 1),
(2, 'Desenvolvimento', '#037F4C', 0, 1),
(3, 'Interface', '#A25DDC', 0, 1),
(4, 'Atendimento', '#579BFC', 0, 1),
(5, 'Vendas', '#FDAB3D', 0, 1),
(6, 'Conteúdo', '#F8BD0B', 0, 1),
(7, 'Front-End', '#9d2121', 0, 1),
(8, 'Infra', '#0a0f65', 0, 1),
(9, 'Criação', '#CD7CB1', 0, 1),
(10, 'Mídia', '#fd7f41', 0, 1),
(11, 'Criação Revisar', '#CD7CB1', 1, 1),
(12, 'Conteúdo Revisar', '#F8BD0B', 1, 1),
(13, 'Interface Revisar', '#A25DDC', 1, 1),
(14, 'SEO', '#60B31D', 0, 1),
(15, 'Planejamento', '#60B31D', 0, 1),
(16, 'Desenvolvimento Revisar', '#037F4C', 1, 0),
(17, 'Sites', '#00e1ff', 0, 1),
(18, 'EXH', '#1e9600', 0, 0),
(19, 'Administrativo', '#0075ff', 0, 1),
(20, 'Videos', '#f4cd00', 0, 0),
(21, 'Videos Revisar', '#f4cd00', 1, 0),
(22, 'Sistema e Automacao', '#804000', 1, 1),
(23, 'Monitoramento', '#dc05ff', 0, 1),
(24, 'Looks Creative Studio ', '#ef9714', 0, 0),
(25, 'Inbound', '#bcff00', 0, 1),
(26, 'BI', '#42ed71', 0, 1),
(27, 'Mídia Revisar', '#1a2ddb', 1, 1),
(28, 'BUGs & Ajustes', '#000000', 0, 1),
(29, 'Marketing', '#0092ff', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `back_AdmAreaUsuario`
--

CREATE TABLE `back_AdmAreaUsuario` (
  `id` int(11) NOT NULL,
  `usuarioNerdweb` int(11) NOT NULL,
  `area` int(11) NOT NULL,
  `nivel` int(11) DEFAULT 1,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `back_AdmAreaUsuario`
--

INSERT INTO `back_AdmAreaUsuario` (`id`, `usuarioNerdweb`, `area`, `nivel`, `isUsed`) VALUES
(98, 28, 6, 1, 1),
(106, 27, 6, 1, 1),
(147, 12, 6, 1, 1),
(148, 12, 10, 1, 1),
(241, 33, 10, 1, 1),
(409, 25, 9, 1, 1),
(440, 39, 10, 1, 1),
(482, 17, 19, 1, 1),
(483, 17, 4, 1, 1),
(484, 17, 18, 1, 1),
(485, 17, 17, 1, 1),
(486, 17, 5, 1, 1),
(493, 32, 4, 1, 1),
(535, 31, 15, 1, 1),
(582, 44, 6, 1, 1),
(606, 47, 15, 1, 1),
(607, 48, 15, 1, 1),
(662, 38, 4, 1, 1),
(663, 38, 17, 1, 1),
(664, 51, 4, 1, 1),
(665, 51, 17, 1, 1),
(675, 54, 15, 1, 1),
(702, 56, 6, 1, 1),
(786, 57, 7, 1, 1),
(787, 10, 2, 1, 1),
(788, 10, 16, 1, 1),
(857, 60, 4, 1, 1),
(858, 60, 17, 1, 1),
(881, 63, 15, 1, 1),
(884, 64, 9, 1, 1),
(939, 66, 9, 1, 1),
(944, 68, 6, 1, 1),
(950, 70, 4, 1, 1),
(951, 70, 17, 1, 1),
(959, 71, 6, 1, 1),
(960, 71, 23, 1, 1),
(1005, 3, 2, 1, 1),
(1006, 3, 16, 1, 1),
(1010, 75, 6, 1, 1),
(1018, 77, 4, 1, 1),
(1031, 78, 6, 1, 1),
(1032, 78, 12, 1, 1),
(1044, 80, 7, 1, 1),
(1048, 52, 4, 1, 1),
(1049, 52, 5, 1, 1),
(1057, 81, 4, 1, 1),
(1058, 81, 17, 1, 1),
(1064, 55, 6, 1, 1),
(1065, 55, 12, 1, 1),
(1066, 82, 6, 1, 1),
(1067, 82, 12, 1, 1),
(1072, 88, 24, 1, 1),
(1073, 89, 24, 1, 1),
(1077, 53, 10, 1, 1),
(1078, 26, 9, 1, 1),
(1079, 26, 11, 1, 1),
(1086, 87, 24, 1, 1),
(1095, 36, 2, 1, 1),
(1096, 36, 16, 1, 1),
(1097, 36, 7, 1, 1),
(1098, 79, 2, 1, 1),
(1101, 91, 4, 1, 1),
(1104, 93, 4, 1, 1),
(1135, 94, 6, 1, 1),
(1136, 94, 12, 1, 1),
(1137, 94, 14, 1, 1),
(1152, 29, 28, 1, 1),
(1153, 29, 2, 1, 1),
(1154, 29, 7, 1, 1),
(1155, 6, 28, 1, 1),
(1156, 6, 2, 1, 1),
(1157, 6, 8, 1, 1),
(1162, 86, 28, 1, 1),
(1163, 86, 2, 1, 1),
(1164, 86, 7, 1, 1),
(1165, 85, 28, 1, 1),
(1166, 85, 2, 1, 1),
(1167, 85, 7, 1, 1),
(1168, 92, 28, 1, 1),
(1169, 92, 2, 1, 1),
(1170, 92, 7, 1, 1),
(1171, 61, 28, 1, 1),
(1172, 61, 3, 1, 1),
(1173, 61, 13, 1, 1),
(1174, 49, 28, 1, 1),
(1175, 49, 3, 1, 1),
(1177, 74, 28, 1, 1),
(1178, 74, 3, 1, 1),
(1183, 95, 15, 1, 1),
(1184, 96, 10, 1, 1),
(1200, 97, 25, 1, 1),
(1201, 83, 26, 1, 1),
(1202, 83, 25, 1, 1),
(1203, 98, 9, 1, 1),
(1210, 101, 26, 1, 1),
(1236, 103, 23, 1, 1),
(1238, 99, 4, 1, 1),
(1239, 99, 17, 1, 1),
(1240, 21, 10, 1, 1),
(1241, 73, 10, 1, 1),
(1242, 102, 10, 1, 1),
(1243, 100, 10, 1, 1),
(1244, 104, 15, 1, 1),
(1255, 62, 6, 1, 1),
(1256, 62, 12, 1, 1),
(1257, 62, 25, 1, 1),
(1258, 62, 14, 1, 1),
(1259, 105, 26, 1, 1),
(1261, 106, 28, 1, 1),
(1262, 106, 7, 1, 1),
(1265, 107, 28, 1, 1),
(1266, 107, 7, 1, 1),
(1286, 108, 10, 1, 1),
(1287, 43, 4, 1, 1),
(1288, 43, 28, 1, 1),
(1289, 43, 2, 1, 1),
(1290, 43, 7, 1, 1),
(1291, 43, 3, 1, 1),
(1292, 43, 17, 1, 1),
(1293, 109, 19, 1, 1),
(1294, 110, 5, 1, 1),
(1306, 111, 6, 1, 1),
(1307, 111, 23, 1, 1),
(1308, 72, 6, 1, 1),
(1309, 72, 14, 1, 1),
(1313, 113, 10, 1, 1),
(1314, 90, 9, 1, 1),
(1315, 90, 11, 1, 1),
(1316, 112, 10, 1, 1),
(1321, 114, 9, 1, 1),
(1322, 115, 19, 1, 1),
(1323, 9, 10, 1, 1),
(1324, 9, 27, 1, 1),
(1325, 9, 29, 1, 1),
(1327, 116, 6, 1, 1),
(1328, 116, 14, 1, 1),
(1339, 117, 25, 1, 1),
(1352, 8, 19, 1, 1),
(1353, 8, 28, 1, 1),
(1354, 8, 9, 1, 1),
(1355, 8, 11, 1, 1),
(1356, 8, 2, 1, 1),
(1357, 8, 7, 1, 1),
(1358, 8, 8, 1, 1),
(1359, 8, 3, 1, 1),
(1360, 8, 13, 1, 1),
(1361, 8, 17, 1, 1),
(1362, 15, 7, 1, 1),
(1363, 15, 8, 1, 1),
(1364, 118, 6, 1, 1),
(1365, 119, 6, 1, 1),
(1366, 120, 9, 1, 1),
(1367, 121, 15, 1, 1),
(1368, 122, 15, 1, 1),
(1369, 42, 26, 1, 1),
(1370, 42, 6, 1, 1),
(1371, 42, 12, 1, 1),
(1372, 42, 11, 1, 1),
(1373, 42, 25, 1, 1),
(1374, 42, 29, 1, 1),
(1375, 42, 10, 1, 1),
(1376, 42, 27, 1, 1),
(1377, 42, 14, 1, 1),
(1378, 11, 4, 1, 1),
(1379, 11, 29, 1, 1),
(1380, 11, 17, 1, 1),
(1381, 123, 4, 1, 1),
(1382, 124, 4, 1, 1),
(1385, 125, 6, 1, 1),
(1386, 125, 25, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `back_AdmGrupo`
--

CREATE TABLE `back_AdmGrupo` (
  `grupo` int(11) NOT NULL,
  `nome` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `descricao` tinytext CHARACTER SET utf8 DEFAULT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `back_AdmGrupo`
--

INSERT INTO `back_AdmGrupo` (`grupo`, `nome`, `descricao`, `isUsed`) VALUES
(1, 'Sem Permissão', 'Grupo sem permissões', 1),
(6, 'Administrador', 'Usuário administrador, acesso a todas as funcionalidades do sistema.', 1),
(7, 'Grupo Teste', 'Grupo de teste de permissões.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `back_AdmGrupoUsuario`
--

CREATE TABLE `back_AdmGrupoUsuario` (
  `grupoUsuario` int(11) NOT NULL,
  `usuarioNerdweb` int(11) NOT NULL,
  `grupo` int(11) NOT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `back_AdmGrupoUsuario`
--

INSERT INTO `back_AdmGrupoUsuario` (`grupoUsuario`, `usuarioNerdweb`, `grupo`, `isUsed`) VALUES
(30, 7, 6, 0),
(32, 6, 6, 0),
(34, 8, 6, 0),
(36, 10, 6, 0),
(37, 11, 6, 0),
(38, 12, 6, 0),
(39, 13, 6, 0),
(40, 15, 6, 0),
(41, 17, 6, 0),
(42, 9, 6, 0),
(43, 18, 6, 0),
(44, 20, 6, 0),
(45, 21, 6, 0),
(53, 1, 6, 0),
(55, 3, 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `back_AdmPermissao`
--

CREATE TABLE `back_AdmPermissao` (
  `permissao` int(11) NOT NULL,
  `nome` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `descricao` tinytext CHARACTER SET utf8 DEFAULT NULL,
  `arquivo` varchar(50) CHARACTER SET utf8 NOT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `back_AdmPermissao`
--

INSERT INTO `back_AdmPermissao` (`permissao`, `nome`, `descricao`, `arquivo`, `isUsed`) VALUES
(1, 'FAQ', 'Gerenciamento de FAQs do website da Nerdweb', 'faq.php', 1),
(2, 'Visualização Facebook', 'Visualização de gráficos e dados de Facebook dos clientes.', 'fb-visualizacao.php', 1),
(3, 'Facebook PageLevel', 'Permissão de importação Facebook PageLevel', 'fb-page-import.php', 1),
(4, 'Facebook PostLevel', 'Permissão para importação Facebook PostLevel', 'fb-post-import.php', 1),
(5, 'Visualização Web Analytics', 'Visualização dos gráficos e dados de Web Analytics dos clientes', 'ga-visualizacao.php', 1),
(6, 'Cadastro Código Web Analytics', 'Cadastro de ID de acompanhamento e ID de propriedade do Web Analytics dos Clientes', 'ga-codigo.php', 1),
(7, 'Importação Web Analytics', 'Permissão para importar dados do Google Web Analytics', 'ga-import.php', 1),
(9, 'Usuários do Sistema', 'Gerenciamento de usuários do sistema backend da Nerdweb.', 'usuarios.php', 1),
(10, 'Grupos do Sistema', 'Gerenciamento de grupos de usuários do sistema backend da Nerdweb.', 'grupos.php', 1),
(11, 'Permissões do Sistema', 'Gerenciamento das permissões de acesso ao sistema backend da Nerdweb.', 'permissoes.php', 1),
(13, 'Clientes', 'Clientes', 'clientes.php', 1),
(14, 'TESTE#', 'TESTE#', 'teste.php', 1),
(15, 'Categorias de FAQ', 'Permissão para gerenciamento das categorias de FAQ', 'faq-categoria.php', 1);

-- --------------------------------------------------------

--
-- Table structure for table `back_AdmPermissaoGrupo`
--

CREATE TABLE `back_AdmPermissaoGrupo` (
  `permissaoGrupo` int(11) NOT NULL,
  `permissao` int(11) NOT NULL,
  `grupo` int(11) NOT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `back_AdmPermissaoGrupo`
--

INSERT INTO `back_AdmPermissaoGrupo` (`permissaoGrupo`, `permissao`, `grupo`, `isUsed`) VALUES
(17, 1, 7, 1),
(18, 9, 7, 1),
(19, 10, 7, 1),
(20, 11, 7, 1),
(32, 1, 6, 1),
(33, 2, 6, 1),
(34, 3, 6, 1),
(35, 4, 6, 1),
(36, 5, 6, 1),
(37, 6, 6, 1),
(38, 7, 6, 1),
(39, 9, 6, 1),
(40, 10, 6, 1),
(41, 11, 6, 1),
(42, 13, 6, 1),
(43, 15, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `back_AdmUsuario`
--

CREATE TABLE `back_AdmUsuario` (
  `usuarioNerdweb` int(11) NOT NULL,
  `nome` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(256) CHARACTER SET utf8 DEFAULT NULL,
  `senha` varchar(65) CHARACTER SET utf8 DEFAULT NULL,
  `imagem` text CHARACTER SET utf8 DEFAULT NULL,
  `area` int(11) DEFAULT NULL,
  `administrador` tinyint(1) DEFAULT 0,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `back_AdmUsuario`
--

INSERT INTO `back_AdmUsuario` (`usuarioNerdweb`, `nome`, `email`, `senha`, `imagem`, `area`, `administrador`, `ativo`, `isUsed`) VALUES
(1, 'Admin', 'infra@nerdweb.com.br', '', 'https://www.gravatar.com/avatar/0c8bae83c54033555f2e32ce10637861', 1, 1, 1, 1),
(3, 'Giovane Ferreira', 'giovane.ferreira@nerdweb.com.br', '26d91c3898867500566d1b39469fab5b3c8ea0a3bc46e2704380db6a18ee1c02', '/backoffice/uploads/usuarios/giovane-ferreira-1527188538.png', 2, 1, 0, 1),
(6, 'Rafael Rotelok', 'rotelok@nerdweb.com.br', '1739f86f0df066bffef79770d34577755be2141feb5d0e8056f0de9b5a99ac7e', '/backoffice/uploads/usuarios/rafael-rotelok.jpg', 2, 1, 1, 1),
(7, 'Bruna Zilio Moreira', 'bruna.moreira@nerdweb.com.br', '7b8a9f071c00f2ee0a2e242ce4f6e312e4ce02f0cd647d23433650ad361448fe', '/backoffice/uploads/usuarios/bruna-moreira.jpg', 3, 0, 0, 1),
(8, 'Tim Trauer', 'tim@nerdweb.com.br', '717bff5635a3aa5c81466df51fa98a01e048d4067041d7d62d034d5b5a35da59', '/backoffice/uploads/usuarios/tim-trauer-1527255974.png', 9, 1, 1, 1),
(9, 'Francis Trauer', 'francis@nerdweb.com.br', '38f001d856f36b8a7e5a417eb29cb99e7a71eceee55ae7bd2ccea650a08965b6', '/backoffice/uploads/usuarios/francis-trauer-1530200196.png', 2, 1, 1, 1),
(10, 'Junior Neves', 'junior@nerdweb.com.br', '5f509ccfd36d76f69486fcd924a456fb579d61eab769c9bace217a7984a46c5a', '/backoffice/uploads/usuarios/junior-neves.jpg', 2, 1, 0, 1),
(11, 'Bruna Ferreira Trauer', 'bruna@nerdweb.com.br', '011e2e16baa1da48f75277e54f27bcf1896dcc5a911ca75f1f43a5604e67b4b6', '/backoffice/uploads/usuarios/bruna-trauer.jpg', 4, 1, 1, 1),
(12, 'Lorraine Galhardone', 'lorraine.galhardone@nerdweb.com.br', '', '/backoffice/uploads/usuarios/lorraine-galhardone.jpg', 6, 0, 0, 1),
(13, 'Ana Claudia Guimarães', 'ana.guimaraes@nerdweb.com.br', '7673d71face94e55fe0c1b250c64c2c2ce9fd2647c34e96faf12d73d1d00b17a', '/backoffice/uploads/usuarios/ana-guimaraes.jpg', 3, 0, 0, 1),
(15, 'Jean M H Romano', 'jean@nerdweb.com.br', 'de1a19dff43b59329b5f41d03f0aff23f2035f0c55e88038d728b234fd7c5082', '/backoffice/uploads/usuarios/jean-romano.jpg', 8, 1, 1, 1),
(17, 'Eduardo Lobo', 'eduardo.lobo@nerdweb.com.br', '6b13fe7a264353d35826765312772f556adb85484ac8c6b56b0bbac5574323b7', '/backoffice/uploads/usuarios/eduardo-lobo.jpg', 5, 1, 1, 1),
(18, 'Alef Generoso', 'alef.generoso@nerdweb.com.br', '', '/backoffice/uploads/usuarios/alef-generoso.jpg', 4, 0, 0, 1),
(20, 'Emerson Martins', 'emerson.martins@nerdweb.com.br', 'c096b99de0cdf091c0652fbc72357ce3490c7a5c2e5cbfe268d56ec80283805a', '/backoffice/uploads/usuarios/emerson-martins.png', 3, 0, 0, 1),
(21, 'Rodolfo Farias', 'rodolfo.farias@nerdweb.com.br', '3e412fddfae887250cd91cbe9cfcb5c064fb06e2bb7214b42c69a502c925b7f2', '/backoffice/uploads/usuarios/rodolfo-farias.png', 10, 0, 1, 1),
(22, 'Douglas Vinícius', 'douglas.vinicius@nerdweb.com.br', '9acad4ddec853edf1fc0798f392e92fa10b68bac8c6110de4ca751f0c3b07fba', '/backoffice/uploads/usuarios/douglas-vinicius.png', 3, 0, 0, 1),
(23, 'Debora Caldeiras', 'debora.caldeiras@nerdweb.com.br', 'eb33eed0134d500b536937513ce906fc310b5a1e8c056ba56f0f0a112c9cb79c', '/backoffice/uploads/usuarios/debora-caldeiras.png', 6, 0, 0, 1),
(24, 'Roes Vendruscolo', 'roes.vendruscolo@nerdweb.com.br', '350751550e8c05e7159a20f34c26c6631a15929e0d9c9482e092150a', '/backoffice/uploads/usuarios/roes-vendrusculo.png', 2, 0, 0, 1),
(25, 'Thiele Suzuki', 'thiele.suzuki@nerdweb.com.br', '032f4b672c436e651c195bffb09eff990de06a2b4797d84628b98060d2bc6dc4', '/backoffice/uploads/usuarios/thiele-suzuki.jpg', 9, 0, 1, 1),
(26, 'Larissa Prado', 'larissa.prado@nerdweb.com.br', '04cdedb7378096371e0abfa7a6d545a78774b3d5be5bc5c14ebe3e78e7098344', '/backoffice/uploads/usuarios/larissa-prado.jpg', 9, 1, 1, 1),
(27, 'Rodrigo Novack', 'rodrigo.novack@nerdweb.com.br', '', '/backoffice/uploads/usuarios/rodrigo-novack.jpg', 6, 0, 0, 1),
(28, 'Iago Salvador', 'iago.salvador@nerdweb.com.br', '2b1930a9c56ed152083c43f4e128f95ab5912da584ef527525fc2432ea169d46', '/backoffice/uploads/usuarios/iago-salvador.jpg', 6, 0, 0, 1),
(29, 'Adriano Buba', 'adriano.buba@nerdweb.com.br', '1868688385b4c9cd037e5c49e491ec3ef88d773600e39a8e977a21c51d0ef188', '/backoffice/uploads/usuarios/adriano-buba.jpg', 2, 1, 1, 1),
(30, 'Anne Sovierzoski', 'anne.sovierzoski@nerdweb.com.br', '4afb8c30a210adb66ac31ca3d6865b1aa17311bdf782f006fc53d0dc12ace37d', '/backoffice/uploads/usuarios/anne-sovierzoski.jpg', 3, 0, 0, 1),
(31, 'Bruno Oliveira', 'bruno.oliveira@nerdweb.com.br', '63afe442e453893d707936c152654f126b7bb7c82f6a3ce31214336947692017', '/backoffice/uploads/usuarios/bruno-oliveira.png', 15, 0, 1, 1),
(32, 'Silvana Fabre', 'silvana.fabre@nerdweb.com.br', '3e5ffe9e8933abd8488c3c9d7cf367216b85c8760fa85f14e0d1f0a52a2bdd5d', '/backoffice/uploads/usuarios/silvana-fabre.jpg', 4, 0, 0, 1),
(33, 'Marcelo Oliveira', 'marcelo.oliveira@nerdweb.com.br', '64a5f41dd73f22295292cb0bb79f4d40d417e57b0010978f32bc307b44d07ade', '/backoffice/uploads/usuarios/marcelo-oliveira.jpg', NULL, 0, 0, 1),
(34, 'Gabriela Oneda', 'gabriela.oneda@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', '/backoffice/uploads/usuarios/user-girl.jpg', NULL, 0, 1, 0),
(35, 'Guilherme Martins', 'guilherme.martins@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', '/backoffice/uploads/usuarios/guilherme-martins.jpg', NULL, 0, 0, 1),
(36, 'André Calistro', 'andre.calistro@nerdweb.com.br', 'a60a2d48e8dcefcf4cd4d017c4069fcefc261d47e35e6cd47aa1b71de7291dd9', '/backoffice/uploads/usuarios/andre-calistro.jpg', NULL, 0, 0, 1),
(37, 'Revisor', 'revisornerdweb@gmail.com', 'dbdb80de4a70f132adc98c39b6d1350a4bb637757b9829f812bb5e51a924129a', '/backoffice/uploads/usuarios/user-boy.png', NULL, 0, 0, 1),
(38, 'Camila Kelczeski Cathcarth Amado', 'camila.kelczeski@nerdweb.com.br', '1665d3b888be037c3ec117aa72dcecc5bfb2848d003d47b5573012497bb09341', '/backoffice/uploads/usuarios/camila-kelczeski.jpg', NULL, 0, 0, 1),
(39, 'Marco Nasguewitz', 'marco.nasguewitz@nerdweb.com.br', '4b5b5c3141af9f11b30ee373c54f2eb1b0624a7c353a47734e1ae6eda602ab63', '/backoffice/uploads/usuarios/marco-nasguewitz-1543926369.png', 10, 0, 0, 1),
(40, 'Cynthia Costa', 'cynthia.costa@nerdweb.com.br', 'acfa5b7f23e2799c43f6cdabe49113af4013c0b87acd23fe96871bf324e61012', '/backoffice/uploads/usuarios/cynthia-costa.jpg', 19, 0, 0, 1),
(41, 'Gustavo Abrao', 'gustavo.abrao@nerdweb.com.br', '4fda4258dae071ae64dcfe91f3c770826a5ed636398837ca4b7f3288aa8d94d7', '/backoffice/uploads/usuarios/gustavo-abrao.png', NULL, 0, 0, 0),
(42, 'Gustavo Abrao', 'gustavo@nerdweb.com.br', '6bbdaa39f20ff404f35f480cc4219ce4f8962d8c4ac6d6be50745d27a75e8576', '/backoffice/uploads/usuarios/gustavo-abrao.png', 6, 1, 1, 1),
(43, 'Mariele Figueiro', 'mariele.figueiro@nerdweb.com.br', 'c841a2c8751c5e3eae6eb3a7a9343d2d054c146b8a2ef13af67ddb87df36ab38', '/backoffice/uploads/usuarios/mariele-figueiro-1538168247.png', 4, 1, 1, 1),
(44, 'Giovana Bonatto', 'giovana.bonatto@nerdweb.com.br', '3212d3d6eb630c21645d2cc8bc8bb66cd4b71cf648af649a1f6a479d6284a70c', '/backoffice/uploads/usuarios/giovana-bonatto.jpg', NULL, 0, 0, 1),
(45, 'Thaís Prado', 'thais.prado@nerdweb.com.br', 'cd50eb2cf6e34bde87c5fb237125c6e6ce0952d27725a64240a94a8abeba17b6', '/backoffice/uploads/usuarios/thais-prado.jpg', NULL, 0, 0, 1),
(46, 'Ezequiel Junior', 'ezequiel.junior@nerdweb.com.br', '2657ddd21ef901888d46493f11c847a7ae50065305126145b19125e859c386be', '/backoffice/uploads/usuarios/ezequiel-junior.jpg', NULL, 0, 0, 1),
(47, 'Israel Fuertes', 'israel.fuertes@nerdweb.com.br', '3212d3d6eb630c21645d2cc8bc8bb66cd4b71cf648af649a1f6a479d6284a70c', NULL, NULL, 0, 0, 0),
(48, 'Israel Fuentes', 'israel.fuentes@nerdweb.com.br', '1c20a945ed0d0189ba35e0736a44f983aad4c400f678f469b8da4a31b6ba8cf2', '/backoffice/uploads/usuarios/israel-fuentes.png', 15, 0, 1, 1),
(49, 'Fernanda Toledo', 'fernanda.toledo@nerdweb.com.br', '3a75b3d73a31399a9ec2f28ff9de162bd82bd785f2d51ca65954b2c89c460ca8', '/backoffice/uploads/usuarios/fernanda-toledo-1575988259.png', 3, 0, 1, 1),
(50, 'Homero Meyer', 'homero.meyer@nerdweb.com.br', '6f54aff0fb9e378639bf29bee19dd8e1a6993a05e4e18c771c501e57d2181000', '/backoffice/uploads/usuarios/homero-meyer.jpg', NULL, 0, 0, 1),
(51, 'Ana Paula Katsuki', 'ana.katsuki@nerdweb.com.br', '9be09c46b7e6a1bb67ffc4297accd48420f8f626d8f6a78f4c02034854dd1099', '/backoffice/uploads/usuarios/ana-katsuki.png', NULL, 0, 0, 1),
(52, 'Luiz Felipe de Almeida', 'luiz.almeida@nerdweb.com.br', 'ea48648733df3ec0230f0798250268f0064561d09f164e930fe3aaa139bdd72e', '/backoffice/uploads/usuarios/luiz-almeida.jpg', 5, 0, 1, 1),
(53, 'Dominique Morais', 'dominique.morais@nerdweb.com.br', '6173b2190bc8b9fdf322f5cbaa5a353666cff99e083b0c74a666fc879e6c3787', '/backoffice/uploads/usuarios/dominiqqqqq.jpeg', NULL, 0, 0, 1),
(54, 'Stefany Guedes', 'stefany.guedes@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', '/backoffice/uploads/usuarios/stefany-guedes.png', NULL, 0, 0, 1),
(55, 'Vinicius Souza', 'vinicius.souza@nerdweb.com.br', '29fa38d579e031a0de0d99f2fc0ca8cb4623bb59dd04d0c79cd882fba4d03352', '/backoffice/uploads/usuarios/vinicius-souza.png', 6, 1, 1, 1),
(56, 'USUARIO INVALIDO', 'invalido@nerdweb.com.br', 'ae3784314f375645157903c0ce188a2b0bef88b5e9476f4c72513c1015f0b875', '/backoffice/uploads/usuarios/user-boy.png', NULL, 0, 0, 0),
(57, 'Robson Moura', 'robson.moura@nerdweb.com.br', '31933ca527b5cffe2839b2e7ae87d6cc26ff0bbb6c19fbc1091ae9af826a061e', '/backoffice/uploads/usuarios/robson-moura.jpg', NULL, 0, 0, 1),
(58, 'Raphael de Jesus', 'raphael.jesus@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', '/backoffice/uploads/usuarios/raphael-de-jesus.jpg', NULL, 0, 0, 1),
(59, 'Ricardo Jug', 'ricardo.jug@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', '/backoffice/uploads/usuarios/ricardo-jug.jpg', NULL, 0, 0, 1),
(60, 'Nayane Harcar', 'nayane.harcar@nerdweb.com.br', 'e428bdfeace27847745c9c0dc693e95e0c764cad9ddb5ef8f2d21b17d500724f', '/backoffice/uploads/usuarios/nayane-harcar.jpg', 4, 0, 1, 1),
(61, 'Venancio Faria', 'venancio.faria@nerdweb.com.br', '0a2d48e29ac8bc671c314f559fe4ad73b4a8fcc84d78296fcd51140a26097e9d', '/backoffice/uploads/usuarios/venancio-faria.jpg', 3, 1, 1, 1),
(62, 'Carlos Zuchi', 'carlos.zuchi@nerdweb.com.br', '764f3abe8bc76a781bc7b1009f67c8fa25de7dc11620a6a958a2367a1f7de1ec', '/backoffice/uploads/usuarios/carlos-zuchi.jpg', 14, 0, 1, 1),
(63, 'Carlos Zuchi', 'carlos.zuchi@nerdweb.com.br', '764f3abe8bc76a781bc7b1009f67c8fa25de7dc11620a6a958a2367a1f7de1ec', '/backoffice/uploads/usuarios/user-boy.png', NULL, 0, 1, 0),
(64, 'Jessica Liduario', 'jessica.liduario@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', '/backoffice/uploads/usuarios/jessica-liduario.jpg', 9, 0, 1, 1),
(65, 'Rafael Ribeiro', 'rafael.ribeiro@nerdweb.com.br', '04c40e17f3718bd27f901b9820739c8c71591291820942d313d23085638abd65', '/backoffice/uploads/usuarios/rafael-ribeiro.jpg', NULL, 0, 0, 1),
(66, 'Leandro Battaglia', 'leandro.battaglia@nerdweb.com.br', '06e2b48ed6a0f539692f01e5bc0971831c147582bec4eefba5a96286b6d98bfc', '/backoffice/uploads/usuarios/leandro-battaglia.jpg', 9, 0, 1, 1),
(67, 'Leandro Battaglia', 'leandro.battaglia@nerdweb.com.br', '06e2b48ed6a0f539692f01e5bc0971831c147582bec4eefba5a96286b6d98bfc', '/backoffice/uploads/usuarios/user-boy.png', NULL, 0, 0, 0),
(68, 'Caroline Lamega', 'caroline.lamega@nerdweb.com.br', '56a58e7dff86e98d63d02dccb8aca5c4735aa2bbe4b868fd9c3f1354a683e7d7', '/backoffice/uploads/usuarios/caroline-lamega.jpg', 6, 0, 1, 1),
(69, 'Rennan Harkusz', 'rennan.harkusz@nerdweb.com.br', '980c618e4e95579c5cec124d1fb76f55691d7a5504585a844102837bc68d83a3', '/backoffice/uploads/usuarios/rennan-harkusz.jpg', NULL, 0, 0, 1),
(70, 'Guilherme Campos', 'guilherme.campos@nerdweb.com.br', 'af0e4c601b4181e3510edb0b3159d4456e9cb5311ff5ab71930ae978310fcab4', '/backoffice/uploads/usuarios/guilherme-campos.jpg', 4, 0, 1, 1),
(71, 'Iris Fernandes', 'iris.fernandes@nerdweb.com.br', '428664b3ed7098719c23a3e6a46871a7dafc79e68fe2fce3fd67e9852cf55e3f', '/backoffice/uploads/usuarios/iris-fernandes-1557443620.png', 23, 0, 1, 1),
(72, 'Rafaela Romanine', 'rafaela.romanine@nerdweb.com.br', '6b062337fba68164e0896e46c60aae43d013a7fc78d56f0a142a18b42d27e688', '/backoffice/uploads/usuarios/rafaela-romanine.jpg', 23, 0, 0, 1),
(73, 'Ronaldo Ribeiro', 'ronaldo.ribeiro@nerdweb.com.br', '399cb4fca2bfcd550d428c45c5c48bdf867c30fb6718cb77ad66119638cabc20', '/backoffice/uploads/usuarios/ronaldo-ribeiro-1529322887.png', 10, 0, 1, 1),
(74, 'Thiago Exterkotter', 'thiago.exterkotter@nerdweb.com.br', 'f4eea15c3e2958aaf95b4fbfd6fc6b403fd2340a8351e13b5123dd1a52a1ae4e', '/backoffice/uploads/usuarios/thiago-exterkotter-1529323623.png', 3, 0, 0, 1),
(75, 'Malu Bonett', 'malu.bonett@nerdweb.com.br', 'e1213cd3ee7b59428a54cceef0735f8b582269132dbf5bb2e1ad2c1a0519c576', '/backoffice/uploads/usuarios/malu-bonett-1562949027.png', 6, 0, 1, 1),
(76, 'Patricia Sznaider', 'patricia.sznaider@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', '/backoffice/uploads/usuarios/patricia-sznaider-1538581059.png', NULL, 0, 0, 1),
(77, 'Evilazio Coelho', 'evilazio.coelho@nerdweb.com.br', 'c880d8d6899992095bfd9472cb33350433b353328ba485dd8425690279073079', '/backoffice/uploads/usuarios/evilazio-coelho-1531331273.png', NULL, 0, 0, 1),
(78, 'Beatriz Smaal', 'beatriz.smaal@nerdweb.com.br', '23de5ac546fbb18e178c0c25ec6de945d145408906516a275d7c71eb3ffde974', '/backoffice/uploads/usuarios/beatriz-smaal-1530651799.png', 6, 0, 1, 1),
(79, 'Vald Fernandes', 'vald@nerdweb.com.br', '659d9ab66d2efc4812e3fd5e1750579ca8260acdffc1ba91ec237f92bc0a9330', '/backoffice/uploads/usuarios/vald-fernandes-1536071117.png', NULL, 0, 0, 1),
(80, 'Lucas Klasa', 'lucas.klasa@nerdweb.com.br', 'ae3784314f375645157903c0ce188a2b0bef88b5e9476f4c72513c1015f0b875', '/backoffice/uploads/usuarios/lucas-klasa-1536858656.png', NULL, 0, 0, 1),
(81, 'Michell Wilker', 'michell.wilker@nerdweb.com.br', 'beb149a9858cbf6c69ebc249fe2227d3605d7726c0e227508b928214f81c64d6', '/backoffice/uploads/usuarios/michell-wilker-1535050093.png', NULL, 0, 0, 1),
(82, 'Vinicius Karasinski', 'vinicius.karasinski@nerdweb.com.br', '0276264f868e25a9fb2ae21d387e344895905cd87836ed07e6f27e8b5dc9a7c4', '/backoffice/uploads/usuarios/vinicius-karasinski-1536857901.png', 6, 0, 1, 1),
(83, 'Eliel Laynes', 'eliel.laynes@nerdweb.com.br', 'ae3784314f375645157903c0ce188a2b0bef88b5e9476f4c72513c1015f0b875', '/backoffice/uploads/usuarios/eliel-laynes-1536857771.png', 26, 0, 0, 1),
(84, 'Eliel Laynes', 'eliel.laynes@nerdweb.com.br', 'ae3784314f375645157903c0ce188a2b0bef88b5e9476f4c72513c1015f0b875', '/backoffice/uploads/usuarios/user-boy.png', NULL, 0, 0, 1),
(85, 'Leonardo Roberto Rocha', 'leonardo.rocha@nerdweb.com.br', 'df473abf71c2208a432df27819180ab40d1a87087c9abbb4fbd1b2a17ced70df', '/backoffice/uploads/usuarios/leonardo-roberto-rocha-1537366672.png', 7, 0, 0, 1),
(86, 'Hiago Alves Klapowsko', 'hiago.klapowsko@nerdweb.com.br', '7fff9a6ca0f7c18e367b633fbe526b961225570ff3da3e138cd6034bb544e613', '/backoffice/uploads/usuarios/hiago-alves-klapowsko-1537211663.png', 2, 0, 1, 1),
(87, 'Felipe Vieira Warzensaky', 'felipe.vieira@lookscs.com.br', 'ae3784314f375645157903c0ce188a2b0bef88b5e9476f4c72513c1015f0b875', '/backoffice/uploads/usuarios/user-boy.png', NULL, 0, 0, 1),
(88, 'João Vitor Miguel', 'joao.vitor@lookscs.com.br ', '0a71c30d5143343a7fe913a8f50486e766ac28a4007d19277e9537f870d26fab', '/backoffice/uploads/usuarios/user-boy.png', NULL, 0, 0, 1),
(89, 'Camila Barbieri', 'camila.barbieri@lookcs.com.br', 'ae3784314f375645157903c0ce188a2b0bef88b5e9476f4c72513c1015f0b875', '/backoffice/uploads/usuarios/user-boy.png', NULL, 0, 0, 1),
(90, 'Leonardo Gonsalves', 'leonardo.oliveira@nerdweb.com.br', '4692bf4059d3b791de8b8fbd2df8eaf5e30fbf4ccbd7d88356439666fbf1a591', '/backoffice/uploads/usuarios/leonardo-gonsalves-1541444099.png', 9, 1, 1, 1),
(91, 'Bruno Guarezi Kolbe', 'bruno.kolbe@nerdweb.com.br', '3ca245b282ed17f4c92815c740fb91fa401dee9d6373dfd25d615b190598d299', '/backoffice/uploads/usuarios/bruno-guarezi-kolbe-1544631865.png', 4, 0, 1, 1),
(92, 'Wilson Neto', 'wilson.neto@nerdweb.com.br', '26a98ec4c521b0953f70f2b2408da19c103e2c2f4d3f3e31ed5beeceac73e886', '/backoffice/uploads/usuarios/wilson-neto-1550944152.png', 7, 0, 1, 1),
(93, 'Ana Tereza Sabbatini Beckert Maito', 'ana.maito@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', '/backoffice/uploads/usuarios/ana-tereza-sabbatini-beckert-maito-1545326908.png', 4, 0, 1, 1),
(94, 'Patricia Suchodolak', 'patricia.suchodolak@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', '/backoffice/uploads/usuarios/patricia-suchodolak-1574799419.png', 14, 0, 1, 1),
(95, 'Nelson Gonçalves Neto', 'nelson.neto@nerdweb.com.br', '4022103774f5a3b9b505f26c4158979d462edb4c39be9344a73b1fdc34898e1e', '/backoffice/uploads/usuarios/nelson-goncalves-neto-1558547291.png', 15, 0, 0, 1),
(96, 'Nathalia dos Santos', 'nathalia.santos@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', 'https://nerdweb.popflow.com.br/backoffice/uploads/usuarios/user-boy.png', NULL, 0, 0, 1),
(97, 'Caroline Giraldes Rodrigues', 'caroline.rodrigues@nerdweb.com.br', '21d1204c157fb46428a89ef35abb2a3c06397251bd32f4b7cc34d89618fd587c', '/backoffice/uploads/usuarios/caroline-giraldes-rodrigues-1554756131.png', 25, 0, 1, 1),
(98, 'Andre Charneski', 'andre.charneski@nerdweb.com.br', '942d760b179fe1963e3c0ea28f1c992adae3863b2fb7a6514f33dd93656ff227', '/backoffice/uploads/usuarios/andre-charneski-1555962749.png', NULL, 0, 1, 1),
(99, 'Lucas Carneiro Morais', 'lucas.morais@nerdweb.com.br', '5d7b4c983396684efb0ea9c7611c95a6ca335f98cd4ac9b5fdfa63795b8bfef8', '/backoffice/uploads/usuarios/lucas-carneiro-morais-1558385165.png', NULL, 0, 1, 1),
(100, 'Monique Cellarius', 'monique.cellarius@nerdweb.com.br', '8d0b08135a41e668bf7cc8e1c1a778352c9d8a0413ca8a9c439c9e1cd45fa466', '/backoffice/uploads/usuarios/monique-cellarius-1560533416.png', NULL, 0, 0, 1),
(101, 'Layla Solak', 'layla.solak@nerdweb.com.br', '9f7860662a510c0d79f3c4d409a6dfef20176d75679c3ea55ebfb167f22417dc', '/backoffice/uploads/usuarios/layla-solak-1562692445.png', NULL, 0, 1, 1),
(102, 'Hannah Sophia Gonçalves Wronski', 'hannah.wronski@nerdweb.com.br', NULL, 'https://nerdweb.popflow.com.br/backoffice/uploads/usuarios/user-boy.png', NULL, 0, 0, 1),
(103, 'Matheus Gonsalves Das Neves', 'matheus.neves@nerdweb.com.br', '95285b0b084c11c4bde2ae882f82c819e502d07a750871d1e5e807f325e5ff50', '/backoffice/uploads/usuarios/matheus-gonsalves-das-neves-1567008364.png', NULL, 0, 1, 1),
(104, 'Ana  Mulatti', 'ana.mulatti@nerdweb.com.br', '7973166d1c1a12cfb3db70410c44dc154ebadbc48e4e92060b03615ef61b60f4', '/backoffice/uploads/usuarios/ana-mulatti-1565198353.png', NULL, 0, 1, 1),
(105, 'Letícia Grobério', 'leticia.groberio@nerdweb.com.br', '835a733ca962898ffed8d7b9b1e54baac9f52098725f0144ee48589aafb65784', '/backoffice/uploads/usuarios/leticia-groberio-1571419911.png', NULL, 0, 1, 1),
(106, 'João Benine', 'joao.benine@nerdweb.com.br', 'c369d37e19fd1c24953cd346b015b646015ec4aa25f1a6c5c0b3a69b0d5e57ce', '/backoffice/uploads/usuarios/joao-benine-1577968187.png', NULL, 0, 1, 1),
(107, 'Lucas Chan', 'lucas.padilha@nerdweb.com.br', '751fa50297c0fcbac9137849f963b33159fb93622f6a7f89f6bbe8352754791a', '/backoffice/uploads/usuarios/lucas-chan-1578062894.png', NULL, 0, 1, 1),
(108, 'Gabriela Rocha', 'gabriela.rocha@nerdweb.com.br', '68c4b65fa24aa2dc86d8a16a6a106b5b23d9529dc1546ebe7272ff6b098690a1', '/backoffice/uploads/usuarios/gabriela-rocha-1579201759.png', NULL, 0, 1, 1),
(109, 'Larissa Gomes', 'larissa.gomes@nerdweb.com.br', '95ee4b86cd1c81f7120424a9e437b07a06d6b36fecc3d1cc93b6be5c3f91eb77', '/backoffice/uploads/usuarios/larissa.gomes.png', NULL, 1, 1, 1),
(110, 'Eduardo Siqueira', 'eduardo.siqueira@nerdweb.com.br', 'e41a533015a63396e093ff423d771703426a95a5da82188385294382d048e131', '/backoffice/uploads/usuarios/eduardo-siqueira-1574186341.png', NULL, 0, 1, 1),
(111, 'Julia Mansur Hilu', 'julia.hilu@nerdweb.com.br', 'ee6479ed9825247151dd75a4b341542f8ca3c3af8e51361e48ae88bb9e1dddd4', '/backoffice/uploads/usuarios/julia-mansur-hilu-1574692945.png', NULL, 0, 1, 1),
(112, 'Lucas Karasinski', 'lucas.karasinski@nerdweb.com.br', '6b62491545f6bf866164c0c734afae018c67ad7823951501899ea108e036c1e1', '/backoffice/uploads/usuarios/lucas-karasinski-1576156765.png', NULL, 0, 1, 1),
(113, 'Victoria Bolzzoni', 'victoria.bolzzoni@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', '/backoffice/uploads/usuarios/victoria-bolzzoni-1576099898.png', NULL, 0, 1, 1),
(114, 'Joyce Moraes', 'joyce.moraes@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', '/backoffice/uploads/usuarios/joyce-moraes-1578405225.png', NULL, 0, 1, 1),
(115, 'Clecia Santana', 'clecia.santana@nerdweb.com.br', 'e72388874719ea973a14a4d33a7f738722100ffa7a695e43e9a0c3ea30835913', 'https://nerdweb.popflow.com.br/backoffice/uploads/usuarios/user-boy.png', NULL, 0, 1, 1),
(116, 'Alexsandro Bueno Junior', 'alexsandro.bueno@nerdweb.com.br', '7a1bc5a11f6619b6c7f23ac13905be76b4cbdf5a902426104664b5d8b0621a36', '/backoffice/uploads/usuarios/alexsandro-bueno-junior-1578936176.png', NULL, 0, 1, 1),
(117, 'Tiago Machado', 'tiago.silva@nerdweb.com.br', '1fa55c09c62fd299ba0098af3c48f42e8f6115d42fd94f578254b303813aa8fd', 'https://nerdweb.popflow.com.br/backoffice/uploads/usuarios/user-boy.png', NULL, 0, 1, 1),
(118, 'Lucas Liberti Wechinewsky', 'lucas.wechinewsky@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', 'https://nerdweb.popflow.com.br/backoffice/uploads/usuarios/user-boy.png', NULL, 0, 1, 1),
(119, 'Camila Nicolellis', 'camila.nicolellis@nerdweb.com.br', '9a3e7fdbb3b5ad54983ad52d0aa4545617ff5d63a1df0c4594e449230c760666', 'https://nerdweb.popflow.com.br/backoffice/uploads/usuarios/user-boy.png', NULL, 0, 1, 1),
(120, 'Louise Schinzel', 'louise.schinzel@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', '/backoffice/uploads/usuarios/louise-schinzel-1581710093.png', NULL, 0, 1, 1),
(121, 'Emanuele Zuchi', 'emanuele.zuchi@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', '/backoffice/uploads/usuarios/emanuele-zuchi-1581968825.png', NULL, 0, 1, 1),
(122, 'Ricardo Cruz', 'ricardo.cruz@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', '/backoffice/uploads/usuarios/ricardo-cruz-1581952025.png', NULL, 0, 1, 1),
(123, 'Sofia Serra', 'sofia.serra@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', '/backoffice/uploads/usuarios/sofia-serra-1582058962.png', NULL, 0, 1, 1),
(124, 'Camila Casarin', 'camila.casarin@nerdweb.com.br', 'a829e2f4f69e033e40ebbe11f52fd287119759c026a7e232d18ac0b840ff574b', 'https://nerdweb.popflow.com.br/backoffice/uploads/usuarios/user-boy.png', NULL, 0, 1, 1),
(125, 'Wikerson Paz Landim', 'wikerson.landim@nerdweb.com.br', 'a3cd647293ce1e9efa6c51bc9218f04cb59c94171338be6c6ba4705e51f0ecca', 'https://nerdweb.popflow.com.br/backoffice/uploads/usuarios/user-boy.png', NULL, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `back_Cliente`
--

CREATE TABLE `back_Cliente` (
  `cliente` int(11) NOT NULL,
  `nomeFantasia` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `responsavel` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `contato` text CHARACTER SET utf8 DEFAULT NULL,
  `logo` varchar(255) DEFAULT 'default.png',
  `logo_full` varchar(255) DEFAULT 'default.png',
  `dataEntrada` date DEFAULT NULL,
  `dataCriacao` datetime DEFAULT NULL,
  `observacao` text CHARACTER SET utf8 DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `whmcsId` int(11) DEFAULT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `back_Cliente`
--

INSERT INTO `back_Cliente` (`cliente`, `nomeFantasia`, `responsavel`, `email`, `contato`, `logo`, `logo_full`, `dataEntrada`, `dataCriacao`, `observacao`, `ativo`, `whmcsId`, `isUsed`) VALUES
(1, 'Nerdweb', 'Francis Trauer', 'francis@nerdweb.com.br', 'francis@nerdweb.com.br', 'cliente-1500673513.png', 'cliente-full-188fff7a79cf251.jpg', '2016-08-01', NULL, '', 1, 1, 1),
(3, 'Vascullare', '', '', '', 'cliente-1500673543.png', 'cliente-full-ca4e43af9c86d15.png', '2016-08-01', NULL, '', 2, 1, 1),
(4, 'RJ Movelaria', '', '', '', 'cliente-1500056254.png', 'cliente-full-fffd02d6a0415b8.png', '2016-08-01', NULL, '', 2, 1, 1),
(5, 'Danielle Krizanovski', '', '', '', 'default.png', 'default.png', '2016-08-01', NULL, '', 2, 1, 1),
(6, 'Vapza Alimentos', '', 'contato@vapza.com.br', 'contato@vapza.com.br', 'cliente-1500673644.png', 'cliente-full-a38ad1ea784cce2.png', '2016-08-01', NULL, '', 1, 1, 1),
(7, 'Taxi ADV', '', '', '', 'cliente-1500323852.png', 'cliente-full-8ecce75086b8ad4.png', '2016-08-01', NULL, '', 2, 1, 1),
(8, 'Casa da Mae Joana - Maringa', '', '', '', 'cliente-1500323808.png', 'cliente-full-bbfa201fbcaa19a.jpg', '2016-08-01', NULL, '', 1, 1, 1),
(9, 'Polo Royal', '', '', '', 'cliente-1500055499.png', 'cliente-full-6fda7c00d8db664.png', '2016-08-01', NULL, '', 1, 1, 1),
(10, 'Oven Pizza', '', '', '', 'cliente-1500055411.png', 'cliente-full-54eb904c9eceb3a.png', '2016-08-01', NULL, '', 1, 1, 1),
(11, 'Goodies', '', '', '', 'cliente-1500056290.png', 'cliente-full-40e5a8cab75f98e.png', '2016-08-01', NULL, '', 1, 1, 1),
(12, '3A Parking', '', '', '', 'cliente-1500323989.png', 'cliente-full-d2860cbc7c7d0c8.png', '2016-08-01', NULL, '', 2, 1, 1),
(13, 'Canova do Brasil', '', '', '', 'cliente-1500303287.png', 'cliente-full-6a7a10d36e13647.png', '2016-08-01', NULL, '', 1, 1, 1),
(14, 'BaraQuias', '', 'francis@nerdweb.com.br', 'francis@nerdweb.com.br', 'cliente-1500055580.png', 'cliente-full-7efd5177bca9901.png', '2016-08-01', NULL, '', 1, 1, 1),
(15, 'Casa da Mae Joana - SJDP', '', '', '', 'cliente-1500324006.png', 'cliente-full-ca328fd89968f22.jpg', '2016-08-01', NULL, '', 2, 1, 1),
(16, 'Castell-Dentista', '', '', '', 'cliente-1500497308.png', 'cliente-full-f83ae1dfeb8039e.png', '2016-08-01', NULL, '', 2, 1, 1),
(17, 'Castell-Odonto', '', '', '', 'cliente-1500324096.png', 'cliente-full-abfdd1a91f609a7.png', '2016-08-01', NULL, '', 1, 1, 1),
(18, 'Construtora Florenzano', '', '', '', 'cliente-1500324147.png', 'cliente-full-648ca5c662a1094.png', '2016-08-01', NULL, '', 1, 1, 1),
(19, 'Contabilista', '', '', '', 'cliente-1500683977.png', 'cliente-full-954bf8aa0911738.jpg', '2016-08-01', NULL, '', 1, 1, 1),
(20, 'E-Point', '', '', '', 'cliente-1500497410.png', 'cliente-full-e1ecb1623a7d460.png', '2016-08-01', NULL, '', 2, 1, 1),
(21, 'Imprime Capas', '', '', '', 'cliente-1500324175.png', 'cliente-full-ab138f418b8eff8.png', '2016-08-01', NULL, '', 1, 1, 1),
(22, 'Master Grill', '', '', '', 'cliente-1500303022.png', 'cliente-full-e6cd830eb14bf5a.png', '2016-08-01', NULL, '', 1, 1, 1),
(23, 'Grupo Joma', '', '', '', 'default.png', 'default.png', '2016-08-01', NULL, '', 2, 1, 1),
(24, 'MyMob', '', '', '', 'cliente-1500055321.png', 'cliente-full-13412e84eac1b51.png', '2016-08-01', NULL, '', 1, 1, 1),
(25, 'Residencial Palermo', '', '', '', 'default.png', 'default.png', '2016-08-01', NULL, '', 2, 1, 1),
(26, 'Construtora San Remo', '', '', '', 'cliente-1500302649.png', 'cliente-full-7e62b3d01423b30.png', '2016-08-01', NULL, '', 2, 1, 1),
(27, '6 Sentidos', '', '', '', 'cliente-1500672331.png', 'cliente-full-4872ca389bb73b5.png', '2016-08-01', NULL, '', 2, 1, 1),
(28, 'Abreu Correa Odontologia', '', '', '', 'cliente-1500325177.png', 'cliente-full-591f53fa915840f.png', '2016-08-01', NULL, '', 2, 1, 1),
(29, 'Altez', '', '', '', 'cliente-1500325307.png', 'cliente-full-273ce4fbfd3ef3e.jpg', '2016-08-01', NULL, '', 2, 1, 1),
(30, 'Brasil Acesso', '', '', '', 'cliente-1500325330.png', 'cliente-full-5fc5b94f35fd3ce.png', '2016-08-01', NULL, '', 2, 1, 1),
(31, 'Brisa Consulting', '', '', '', 'cliente-1500324245.png', 'cliente-full-b0f0d6c4d976700.png', '2016-08-01', NULL, '', 1, 1, 1),
(32, 'Cake Me Home Bakery', '', '', '', 'cliente-1500055625.png', 'cliente-full-0f6ccd51807fa6d.png', '2016-08-01', NULL, '', 2, 1, 1),
(33, 'Cenario Espaco Arte', '', '', '', 'cliente-1500302613.png', 'cliente-full-0232cc8b1baf945.png', '2016-08-01', NULL, '', 2, 1, 1),
(34, 'Clau Chef Fit', '', '', '', 'cliente-1500325374.png', 'cliente-full-b163bc244113c7f.png', '2016-08-01', NULL, '', 3, 1, 1),
(35, 'Efficienza', '', '', '', 'cliente-1500055087.png', 'cliente-full-bfc82d90e7835b0.png', '2016-08-01', NULL, '', 1, 1, 1),
(36, 'Emporio Cardamomo', '', '', '', 'cliente-1500325419.png', 'cliente-full-b9330a158ab88c4.png', '2016-08-01', NULL, '', 2, 1, 1),
(37, 'FeraPlan', '', '', '', 'cliente-1500325452.png', 'cliente-full-58bba1693f35376.png', '2016-08-01', NULL, '', 1, 1, 1),
(38, 'Fernanda Liz', '', '', '', 'cliente-1500325478.png', 'cliente-full-db3b0ea8ba9d46f.png', '2016-08-01', NULL, '', 3, 1, 1),
(39, 'Fred Kendi', '', '', '', 'cliente-1500325506.png', 'cliente-full-25296d482958754.jpg', '2016-08-01', NULL, '', 2, 1, 1),
(40, 'Gilherme Pinheiro Odontologia', '', '', '', 'cliente-1500325531.png', 'cliente-full-3b6a19d4f22f241.png', '2016-08-01', NULL, '', 2, 1, 1),
(41, 'HardCore Training', '', '', '', 'cliente-1500302846.png', 'cliente-full-1bdea00a40efe90.png', '2016-08-01', NULL, '', 1, 1, 1),
(42, 'HB Advogados', '', '', '', 'cliente-1500325614.png', 'cliente-full-3a44e0588aed3ad.png', '2016-08-01', NULL, '', 2, 1, 1),
(43, 'Sage', '', '', '', 'cliente-1500325717.png', 'cliente-full-ff876975db7e18f.png', '2016-08-01', NULL, '', 1, 1, 1),
(44, 'Lideranca Feminina', '', '', '', 'cliente-1502311787.png', 'cliente-full-4eec9c7004249a7.jpg', '2016-08-01', NULL, '', 1, 1, 1),
(45, 'Marketing Mix Agro', '', '', '', 'cliente-1500325814.png', 'cliente-full-035cac6fbb179c3.png', '2016-08-01', NULL, '', 2, 1, 1),
(46, 'Minerphos', '', '', '', 'cliente-1500055738.png', 'cliente-full-e175f8fd34ac299.png', '2016-08-01', NULL, '', 1, 1, 1),
(47, 'Palazzo Lumini', '', '', '', 'default.png', 'default.png', '2016-08-01', NULL, '', 2, 1, 1),
(48, 'Queen Victoria', '', '', '', 'default.png', 'default.png', '2016-08-01', NULL, '', 2, 1, 1),
(49, 'SpinFlip', '', '', '', 'cliente-1500498764.png', 'cliente-full-371bb56ae12f5b6.png', '2016-08-01', NULL, '', 1, 1, 1),
(50, 'Digital Fotos', '', '', '', 'cliente-1500498186.png', 'cliente-full-104a6590c5da649.png', '2016-08-01', NULL, '', 1, 1, 1),
(51, 'Jeff Mattos', '', '', '', 'cliente-1500055377.png', 'cliente-full-d4681c2d137d003.png', '2016-08-01', NULL, '', 1, 1, 1),
(52, 'Rogério Cordoni', '', '', '', 'cliente-1500303155.png', 'cliente-full-62c4375c80dfa79.png', '2016-08-01', NULL, '', 1, 1, 1),
(53, 'SBZ', '', '', '', 'cliente-1500498215.png', 'cliente-full-a64740cb33f2072.png', '2016-08-01', NULL, '', 1, 1, 1),
(54, 'Fujita Ink', '', '', '', 'cliente-1500302811.png', 'cliente-full-ced4909dfca3f10.png', '2016-08-01', NULL, '', 2, 1, 1),
(55, 'Transgires', '', '', '', 'cliente-1500303216.png', 'cliente-full-dee24324c748bf9.png', '2016-08-01', NULL, '', 1, 1, 1),
(56, 'Pet Shop da Dinda', '', '', '', 'default.png', 'default.png', '2016-08-01', NULL, '', 2, 1, 1),
(57, 'Dois Olhares', '', '', '', 'default.png', 'default.png', '2016-08-01', NULL, '', 2, 1, 1),
(58, 'Sistar', '', '', '', 'default.png', 'default.png', '2016-08-01', NULL, '', 2, 1, 1),
(59, 'Xplace Games', '', '', '', 'default.png', 'default.png', '2016-08-01', NULL, '', 2, 1, 1),
(60, 'Josemar Perussolo', '', '', '', 'default.png', 'default.png', '2016-08-01', NULL, '', 2, 1, 1),
(61, 'BM Pre Moldados', '', '', '', 'cliente-1502322804.png', 'cliente-full-0d346932e3a1c45.png', '2016-08-01', NULL, '', 1, 1, 1),
(62, 'Brigaderia Prosa Poesia', '', '', '', 'default.png', 'default.png', '2016-08-01', NULL, '', 2, 1, 1),
(63, 'Index Ambiental', '', '', '', 'cliente-1500498698.png', 'cliente-full-735e2c0a6e6ee43.png', '2016-08-01', NULL, '', 2, 1, 1),
(64, 'Index Florestal', '', '', '', 'cliente-1500498709.png', 'cliente-full-e18911766881007.png', '2016-08-01', NULL, '', 2, 1, 1),
(65, 'Grupo Index', '', '', '', 'cliente-1500302829.png', 'cliente-full-efbdb42961009bd.png', '2016-08-01', NULL, '', 2, 1, 1),
(66, 'ArcTerra', '', '', '', 'default.png', 'default.png', '2016-08-01', NULL, '', 1, 1, 1),
(67, 'Klemtz Mercantil', '', '', '', 'default.png', 'default.png', '2016-08-01', NULL, '', 2, 1, 1),
(68, 'RJ Empreiteira', '', '', '', 'cliente-1500302785.png', 'cliente-full-6c7c4e30b630a05.png', '2016-08-01', NULL, '', 2, 1, 1),
(69, 'MAZO Leilões', 'Guilherme Simões', 'adm1@simoesdeassis.com.br​', 'adm1@simoesdeassis.com.br​', 'cliente-1500498093.png', 'cliente-full-6e8355a42569076.png', '2016-08-03', '2016-08-04 18:00:18', '', 2, 484, 1),
(70, 'Mobiliário Vintage', 'Guilherme Simões', 'adm@simgaleria.com', 'adm@simgaleria.com', 'default.png', 'default.png', '2016-08-03', '2016-08-04 18:01:36', '', 2, 484, 1),
(71, 'SIM Galeria', 'Guilherme Simões', 'adm@simgaleria.com', 'adm@simgaleria.com', 'cliente-1500498538.png', 'cliente-full-f9eb67fab203589.png', '2016-08-03', '2016-08-04 18:02:08', '', 2, 483, 1),
(72, 'Promosat', 'Zuher Handar - Alexandre Handar', 'alexandrehandar@gmail.com', 'alexandrehandar@gmail.com', 'cliente-1500498385.png', 'cliente-full-980c27df1e5191e.jpg', '2016-08-31', '2016-08-26 13:34:51', '', 1, 0, 1),
(73, 'Lab Biolabor Vet', 'Vinicus Taliberti', 'vinicius@labbiolabor.com.br', 'vinicius@labbiolabor.com.br', 'cliente-1500498502.png', 'cliente-full-673659d0024743b.png', '2016-09-02', '2016-09-02 17:10:37', '', 2, 0, 1),
(74, 'Manivet', 'Guilherme Blumel', 'guilhermeblumel@yahoo.com.br', 'contato@manivet.com.br - Sócia senhorita polyana', 'default.png', 'default.png', '2016-09-14', '2016-09-14 11:43:18', '', 2, 0, 1),
(75, 'Invista Cred', 'Andrea Fernanda Rhenius Mendes de MOraes', 'moraesandrea560@gmail.com', 'moraesandrea560@gmail.com', 'cliente-1500497903.png', 'cliente-full-625320dc27f53b0.png', '2016-09-21', '2016-09-20 18:13:35', '', 2, 0, 1),
(76, 'Network Psicologia', 'Jucele Antunes', '	jucele.antunes@networkpsicologia.com.br', '	jucele.antunes@networkpsicologia.com.br', 'cliente-1500497845.png', 'cliente-full-8ead25c4dc94af0.png', '2016-09-23', '2016-09-23 16:23:10', '', 1, 0, 1),
(77, 'FELPE Odontologia', 'Jose Carlos Dias Passos', 'clinicpasso@gmail.com', 'clinicpasso@gmail.com', 'cliente-1500498050.png', 'cliente-full-967f237a99cae6b.png', '2016-09-27', '2016-09-27 10:58:00', '', 1, 0, 1),
(78, 'Baden Banho', 'Marcelo', 'baden@badenbanho.com.br', 'baden@badenbanho.com.br', 'cliente-1500498586.png', 'cliente-full-9be85a1c2bb7a6f.png', '2016-09-01', '2016-09-27 17:27:30', '', 2, 0, 1),
(79, 'Flavia Perussolo', 'Flavia Perussolo', '	flaperussolo@hotmail.com', '41-3335-6158 \\ 9997-5957', 'default.png', 'default.png', '2016-09-29', '2016-09-29 16:39:13', '', 1, 0, 1),
(80, 'Segunda Casa Barbearia', 'Frederico Louveira Ayres', '2casabarbearia@gmail.com', '', 'default.png', 'default.png', '2016-10-03', '2016-10-04 11:18:26', '', 2, 0, 1),
(81, 'Trend2U', 'Deyse Oppitz', 'deyse@verdco.com.br', 'deyse@verdco.com.br', 'cliente-1500497991.png', 'cliente-full-a91f24e485647c3.jpg', '2016-10-07', '2016-10-07 10:50:23', '', 2, 0, 1),
(82, 'Hitech Eletric', 'Rodrigo Scheffer Contin', 'rodrigo@hitechracing.com.br', 'rodrigo@hitechracing.com.br', 'cliente-1502195033.png', 'cliente-full-482075fbe931437.png', '2016-10-13', '2016-10-11 18:34:57', '', 2, 0, 1),
(83, 'Sady Pezzi', 'Sady Pezzi', 'sady@sadypezzi.com.br', 'sady@sadypezzi.com.br', 'cliente-1500303169.png', 'cliente-full-18c50d097bb7bdc.png', '2016-10-17', '2016-10-17 15:25:16', '', 1, 0, 1),
(84, 'Instituto Paulo Nassif', 'Emanuelle Pedroso', 'emanuellepedroso@hotmail.com', 'emanuellepedroso@hotmail.com', 'cliente-1500497872.png', 'cliente-full-2b450472dcaede1.png', '2016-10-18', '2016-10-18 15:52:49', '', 1, 0, 1),
(85, 'Emobiliario Brasileiro', 'Pedro Pamplona', 'pedro@emobiliariobrasileiro.com.br', 'pedro@emobiliariobrasileiro.com.br', 'cliente-1500302673.png', 'cliente-full-d8c88b3e7f657c2.png', '2016-03-01', '2016-10-19 13:51:49', '', 2, 0, 1),
(86, 'FunBook', 'Juliano Peixoto', 'juliano@funbook.com.br', 'juliano@funbook.com.br', 'cliente-1500497934.png', 'cliente-full-33963591c6e3fae.png', '2016-10-19', '2016-10-19 18:17:32', '', 2, 0, 1),
(87, 'Clinica Canto', 'Geraldo Canto', 'geraldocanto@yahoo.com.br', 'geraldocanto@yahoo.com.br', 'cliente-1500054961.png', 'cliente-full-2a801510117ead1.png', '2016-01-01', '2016-10-20 17:34:09', '', 1, 0, 1),
(88, 'Zax Store', 'Bruna Trauer', 'bruna@nerdweb.com.br', '41-3042-1818', 'default.png', 'default.png', '2016-10-21', '2016-10-21 14:18:36', '', 2, 0, 1),
(89, 'Ace Coworking', 'Tiago Hilbert', 'tiago@acecoworking.com.br', 'tiago@acecoworking.com.br', 'cliente-1500056490.png', 'cliente-full-45f8ddb3edb8bab.png', '2016-10-25', '2016-10-21 14:22:04', '', 1, 0, 1),
(90, 'Radiola Records', 'Marcio', '	marcio@radiolarecords.com', '	marcio@radiolarecords.com', 'cliente-1500497694.png', 'cliente-full-58b33a5b3ac9706.png', '2016-10-25', '2016-10-21 14:22:49', '', 1, 0, 1),
(91, 'O Forninho', 'HOmero Meyer', 'homero.meyer@gmail.com', 'homero.meyer@gmail.com', 'cliente-1500056501.png', 'cliente-full-db8d61b022bd50d.png', '2016-11-03', '2016-11-03 17:46:43', '', 3, 0, 1),
(92, 'Audax Soluções Financeiras', 'Vivian Penteado', 'vivian.penteado@audaxsf.com.br', '', 'default.png', 'default.png', '2017-05-08', '2016-11-11 10:45:26', '', 2, 0, 1),
(93, 'Unipar EAD', 'Ricardo', 'ricardozambrano@unipar.br', '', 'cliente-1502302103.png', 'cliente-full-6227d5ebb5b0ed9.png', '2016-11-10', '2016-11-23 17:27:15', '', 1, 0, 1),
(94, 'Britânia', 'Britânia', 'giovane.ferreira@nerdweb.com.br', '', 'default.png', 'default.png', '2016-11-24', '2016-11-24 14:36:42', '', 1, 0, 1),
(95, 'Espaço Rocha Odontologia', 'Diego Rocha', 'drdiego@espacorochaodontologia.com.br', 'drdiego@espacorochaodontologia.com.br', 'cliente-1502311948.png', 'cliente-full-4a7d824a16f0359.jpg', '2015-03-18', '2016-11-29 11:02:06', '', 1, 0, 1),
(96, 'EMR Soluções Financeiras', 'Vivian Penteado', 'vivian.penteado@audaxsf.com.br', 'vivian.penteado@audaxsf.com.br', 'default.png', 'default.png', '2011-06-08', '2016-12-06 11:38:56', '', 3, 503, 1),
(97, 'Mob - Fun & Fresh Food', 'Gustavo', 'gustavo@mobfood.com.br ', '', 'default.png', 'default.png', '2016-12-08', '2016-12-08 12:45:57', '', 2, 0, 1),
(98, 'Consultoria Confianza', 'Eder e Ederson', 'eder@consultoriaconfianza.com.br', 'eder@consultoriaconfianza.com.br', 'cliente-1500302631.png', 'cliente-full-2436cb22259ea5a.png', '2016-12-01', '2016-12-22 17:35:03', '', 1, 0, 1),
(99, 'Icetech', 'Muriel', 'muriel@icetech.com.br', 'muriel@icetech.com.br', 'cliente-1502313927.png', 'cliente-full-037f93962f6f493.jpg', '2015-07-15', '2017-01-04 17:24:07', '', 1, 0, 1),
(100, 'BB Advogados', 'Alef', 'atendimento@nerdweb.com.br', 'atendimento@nerdweb.com.br', 'cliente-1500325614.png', 'cliente-full-3a44e0588aed3ad.png', '2016-01-01', '2017-01-17 14:21:59', '', 2, 0, 1),
(101, 'Pokt.it', 'Thiago Lorusso', 'thiagolorusso@gmail.com', '', 'default.png', 'default.png', '2017-01-17', '2017-01-17 15:10:15', '', 2, 0, 1),
(102, 'Homeopatia e CIA', '', 'homeopatia-cia@uol.com.br>', 'homeopatia-cia@uol.com.br>', 'cliente-1500302874.png', 'cliente-full-ab2710dba0d6542.png', '2015-12-01', '2017-02-07 14:48:55', '', 1, 0, 1),
(103, 'Radioenge', 'Tiago', 'tiago@radioenge.com.br', 'tiago@radioenge.com.br', 'cliente-1502313810.png', 'cliente-full-d817362e772fe6a.png', '2017-02-07', '2017-02-07 17:38:47', '', 1, 0, 1),
(104, 'Drytech', 'Muriel', 'muriel@icetech.com.br', 'Muriel', 'default.png', 'default.png', '2017-02-14', '2017-02-14 16:57:40', '', 1, 0, 1),
(105, 'Bossa Mobiliário', 'Guilherme Assis', 'info@bossamobiliario.com.br', 'info@bossamobiliario.com.br', 'cliente-1500498153.png', 'cliente-full-b808b00b758b743.png', '2017-02-22', '2017-02-22 16:35:05', '', 2, 0, 1),
(106, 'Tahine', 'Calil', 'ck@ckcomercial.com.br', 'ck@ckcomercial.com.br', 'cliente-1500303201.png', 'cliente-full-c9f4440d50399d3.png', '2015-08-12', '2017-03-10 11:02:27', '', 1, 0, 1),
(107, 'Ecoteto', 'Miltinho', 'milton@ecoteto.com', 'milton@ecoteto.com', 'cliente-1500497260.png', 'cliente-full-3bfe44d5a0aca8a.png', '2017-03-13', '2017-03-15 16:55:46', '', 1, 0, 1),
(108, 'Evisa - Armadi', 'Renato', 'renato@evisa.com.br', 'renato@evisa.com.br', 'default.png', 'default.png', '2017-03-15', '2017-03-16 09:49:53', '', 1, 0, 1),
(109, 'Marcos Slaviero', 'Marcos Slaviero', 'marcos_slaviero@me.com', '', 'default.png', 'default.png', '2017-03-17', '2017-03-20 08:18:54', '', 1, 0, 1),
(110, 'Subway Itararé', 'Francis', 'francis@nerdweb.com.br', 'francis@nerdweb.com.br', 'cliente-1500499032.png', 'cliente-full-b8fa0cbc18bf1f3.gif', '2017-03-30', '2017-03-30 12:10:30', '', 2, 0, 1),
(111, 'Brasil Importex', 'João Benato', 'joao.benato@brasilimportex.com.br', 'joao.benato@brasilimportex.com.br', 'cliente-1500055541.png', 'cliente-full-df8a21a4282b8a2.png', '2017-04-01', '2017-03-31 16:30:31', '', 1, 0, 1),
(112, 'Baggio Schiavon Arquitetura', 'Flavio Schiavon', 'flavio@bpes.com.br', 'flavio@bpes.com.br', 'cliente-1500499198.png', 'cliente-full-7cdb51983d66387.jpg', '2022-10-08', '2017-04-17 16:10:19', '', 1, 0, 1),
(113, 'CT Barcelos', 'Ricardo Fortunato', 'ricardo@fortunatobarcelos.com.br', 'ricardo@fortunatobarcelos.com.br', 'cliente-1500672389.png', 'cliente-full-c38946da062affd.png', '2017-04-19', '2017-04-19 11:34:52', '', 2, 0, 1),
(114, 'Casa Bavoso', 'Gustavo', 'gustavobavoso@yahoo.com.br', '', 'default.png', 'default.png', '2017-04-19', '2017-04-19 15:38:01', '', 2, 0, 1),
(115, 'Clínica Ópera', 'Silvia Maria', 'silvia84maria@yahoo.com.br', 'Thiago Pope - tbpope@yahoo.com.br', 'default.png', 'default.png', '2017-04-27', '2017-04-27 17:51:38', '', 1, 0, 1),
(116, 'ParkHaus', 'Luiz Campanher', 'luiz@firmeimoveis.com.br', 'luiz@firmeimoveis.com.br', 'cliente-1510940207.png', 'cliente-full-7f24af503a14e36.png', '2017-04-28', '2017-05-08 13:19:04', '', 2, 0, 1),
(117, 'Editora Moderna', 'carol', 'carol@fortun.com.br', 'Carol', 'default.png', 'default.png', '2017-05-10', '2017-05-10 15:59:40', '', 2, 0, 1),
(118, 'Wrap Express', 'Andre Pereira', 'andreg8@gmail.com', 'andreg8@gmail.com', 'cliente-1500499264.png', 'cliente-full-69bdb15441ef7b6.png', '2017-05-24', '2017-05-24 14:53:15', '', 2, 0, 1),
(119, 'Professora Sandra', 'Sandra', 'sandraquirino.s@gmail.com', 'Sandra Professora', 'default.png', 'default.png', '2017-05-26', '2017-05-26 16:11:47', '', 2, 0, 1),
(120, 'MYWAY', 'HENRIQUE CAMPELO JUSTUS', 'contato@mywayft.com.br', 'contato@mywayft.com.br', 'cliente-1500499345.png', 'cliente-full-5f68b4f82e5abdf.png', '2017-05-26', '2017-05-26 17:35:23', '', 2, 0, 1),
(121, 'GETDECOR', 'Paula e Samara', 'paula@casaonze.arq.br e samara@samarabarbosa.com.b', '9997-0060 - Paula', 'default.png', 'default.png', '2017-05-26', '2017-05-26 17:36:40', '', 2, 0, 1),
(122, 'EDEA BRASIL', 'Felipe', ' felipe@edeabrasil.com.br', ' 41 3282-7801 |*', 'default.png', 'default.png', '2017-05-26', '2017-05-26 17:37:20', '', 1, 0, 1),
(123, 'EDEA', 'José Cesar Felipe', 'felipe@edeabrasil.com.br', '', 'default.png', 'default.png', '2017-06-02', '2017-06-02 17:02:56', '', 2, 0, 1),
(124, 'Rivello', 'Rodrigo Florenzano', 'rodrigo@grupoflorenzano.com.br', 'rodrigo@grupoflorenzano.com.br', 'cliente-1500497466.png', 'cliente-full-06abbf6e6e7ab4e.png', '2017-06-05', '2017-06-05 10:56:05', '', 2, 0, 1),
(125, 'Ponto do Cabeleireiro', 'Marilene', 'mw-llima@hotmail.com', 'mw-llima@hotmail.com', 'cliente-1500499075.png', 'cliente-full-d33b73ce4876bbd.png', '2017-06-06', '2017-06-07 10:25:30', '', 1, 0, 1),
(126, 'Fusion Prime', 'Lauricio', 'fisionprime@hotmail.com', '41-3289-2826', 'default.png', 'default.png', '2017-06-12', '2017-06-12 09:13:09', '', 2, 0, 1),
(127, 'Grupo Woods', 'Wendrius e Ligia', 'w.viana@grupowds.com', 'w.viana@grupowds.com', 'cliente-1500497747.png', 'cliente-full-1837ca8ec632931.png', '2017-06-12', '2017-06-12 18:42:31', '', 2, 0, 1),
(128, 'Wedding Season', 'Fred Kendi', 'fotografo@fredkendi.com.br', 'fotografo@fredkendi.com.br', 'cliente-1500055697.png', 'cliente-full-098589a541a0755.png', '2017-06-14', '2017-06-14 11:35:55', '', 2, 0, 1),
(129, 'Elias Filho Advogados', 'Rofis', 'ref@eliasfilho.adv.br', 'ref@eliasfilho.adv.br', 'cliente-1500497796.png', 'cliente-full-a8617775f7940f6.jpg', '2017-06-01', '2017-06-19 17:27:44', '', 1, 0, 1),
(130, 'G.Baraldi', 'Adriana', 'adri.gbaraldi@gmail.com', 'adri.gbaraldi@gmail.com', 'cliente-1502313859.png', 'cliente-full-e974e6adb0fc984.png', '2017-06-20', '2017-06-20 11:07:29', '', 1, 0, 1),
(131, 'Grupo WDS', 'Wendrius', 'w.viana@grupowds.com', 'w.viana@grupowds.com', 'cliente-1500497734.png', 'cliente-full-14141c4013aa142.png', '2017-06-28', '2017-06-28 14:44:05', '', 2, 0, 1),
(132, 'Energia Leve', 'Yuri', 'yuri@energialeve.com', '', 'default.png', 'default.png', '2017-06-28', '2017-06-28 14:58:52', '', 1, 0, 1),
(133, 'Street 444', 'Henrique Campelo Justus', 'contato@mywayft.com.br', 'contato@mywayft.com.br', 'cliente-1500499414.png', 'cliente-full-11210927085a775.jpg', '2017-07-11', '2017-07-11 15:25:15', '', 2, 0, 1),
(134, 'SINPOLOCAL', 'Paulo Sergio', 'sinpolocal@hotmail.com', 'sinpolocal@hotmail.com', 'cliente-1500303184.png', 'cliente-full-7049c2c66714074.png', '2018-03-07', '2017-07-14 15:26:48', '', 2, 0, 1),
(135, 'Smag Inset', 'Jorge', 'smaginset@smaginset.com.br', 'smaginset@smaginset.com.br', 'cliente-1501598943.png', 'cliente-full-2ba50cc27cc3d4a.png', '2023-01-07', '2017-07-17 17:50:24', '', 2, 0, 1),
(136, 'Faculdade São Braz', 'Luciana Mizokosho', 'luciana@saobraz.edu.br', '(41) 3123-9000', '', '', '2017-07-20', '2017-07-20 10:54:26', '', 1, 0, 1),
(137, 'Pure4U', 'Jonatas', 'adm@listoss.com.br', '', '', '', '2017-07-20', '2017-07-20 11:09:58', '', 1, 0, 1),
(138, '2GET SALE', 'Milena', 'financeiro@2getsale.com', 'financeiro@2getsale.com', 'cliente-1521746484.png', 'cliente-full-d9b943de57a9d2b.png', '2017-07-21', '2017-07-21 09:37:44', '', 1, 0, 1),
(139, 'EI TOOLS', 'Cesar Cantarella', 'cesar@eitools.com.br', '', 'cliente-1501169519.png', 'cliente-full-9b1c2bffa699e6c.png', '2017-07-26', '2017-07-27 12:31:59', '', 2, 0, 1),
(140, 'Legacy Partners', 'Henrique', 'htarasiuk@legacypartners.com.br', 'htarasiuk@legacypartners.com.br', 'cliente-1502311771.png', 'cliente-full-39e0b3e1d1405bf.png', '2017-07-31', '2017-07-31 09:56:51', '', 1, 0, 1),
(141, 'WoodsFM', 'Ligia', 'ligia.vitorio@grupowds.com', '', 'cliente-1501597506.png', '', '2017-08-01', '2017-08-01 11:25:06', '', 2, 0, 1),
(142, 'UFO', 'Admar Gevaerd', 'ajgevaerd@gmail.com', 'ajgevaerd@gmail.com', 'cliente-1503624970.png', 'cliente-full-28f40becd140bd0.jpg', '2017-08-03', '2017-08-03 11:15:23', '', 1, 0, 1),
(143, 'Desapegar', 'Márcia', 'marciaribas2@gmail.com', '', '', '', '2017-08-03', '2017-08-03 11:15:49', '', 2, 0, 1),
(144, 'Maxifarma', 'Alexandra', 'marketing@maxifarma.com.br', '', 'cliente-1502473513.png', 'cliente-full-fa7c6ece5fa2c83.png', '2017-08-10', '2017-08-11 14:45:13', '', 1, 0, 1),
(145, 'IDEE', 'Fernanda', 'marketing@nomaa.com.br', '', '', '', '2017-08-14', '2017-08-15 14:43:17', '', 2, 0, 1),
(146, 'Arezzo', 'Maria Fernanda', 'mariafernanda@mfbl.com.br', 'mariafernanda@mfbl.com.br', '', '', '2021-02-06', '2017-08-17 10:07:21', '', 1, 0, 1),
(147, 'IDEE Incorporadora', 'Fernanda Couto', 'contato@ideeincorporadora.com.br', '', 'cliente-1503328378.png', 'cliente-full-f2ef5a965e62ef7.png', '2017-08-16', '2017-08-21 12:12:58', '', 1, 0, 1),
(148, 'AMW Engenharia', 'William', 'william@rjempreiteira.com.br', '', '', '', '2017-08-22', '2017-08-22 09:16:01', '', 2, 0, 1),
(149, 'Gastronomiq', 'Mariana e Diogo', 'diogofber@gmail.com', 'diagofber@gmail.com', '', '', '2017-08-24', '2017-08-24 18:26:38', '', 2, 0, 1),
(150, 'Nomaa Hotel', 'Fernanda', 'marketing@nomaa.com.br', '', '', '', '2017-08-28', '2017-08-28 15:09:39', '', 1, 0, 1),
(151, 'Medprev', 'Leandro', 'leandro@marketingexperts.com.br', 'leandro@marketingexperts.com.br', '', '', '2017-08-29', '2017-08-30 11:17:10', '', 1, 0, 1),
(152, 'Mundo Carolita', 'Carol Moroz ', 'carolina.moroz@hotmail.com', '', 'cliente-1504619642.png', 'cliente-full-a74b301292e357f.png', '2017-09-05', '2017-09-05 10:54:02', '', 2, 0, 1),
(153, 'Britania', 'Mariana Therezio', 'mariana.therezio@britania.com.br', 'mariana.therezio@britania.com.br', 'cliente-1504643514.png', 'cliente-full-476e126b3dfb9d3.jpg', '2017-09-05', '2017-09-05 17:30:45', 'Gerente de Ecommerce\r\nSamanta P. Dal Farra\r\nsamanta.farra@britania.com.br\r\n', 1, 0, 0),
(154, 'Philco', 'Mariana Therezio', 'mariana.therezio@britania.com.br', 'Assistente', 'cliente-1504643572.png', 'cliente-full-62c030776b90389.jpg', '2017-09-05', '2017-09-05 17:32:52', 'Samanta del Farra\r\nsamanta.farra@britania.com.br', 1, 0, 1),
(155, 'Gastronomiq', 'Mariana', 'marifurtado_mf6@hotmail.com', '', '', '', '2017-09-13', '2017-09-13 10:58:37', '', 2, 0, 1),
(156, 'Turfgrass', 'Matheus', 'matheus@turfgrass.com.br', '', '', '', '2017-09-06', '2017-09-18 14:11:15', '', 2, 0, 1),
(157, 'A Tenda de Mercúrio', 'Antonio', 'tonydissenha@gmail.com', '', '', '', '2017-09-18', '2017-09-19 11:37:38', '', 2, 0, 1),
(158, 'Kinkan Sweet & Co', 'Hilton Nakano', 'hilton.nakano@kinkan.com.br', '', 'cliente-1507227248.png', 'cliente-full-04ccd4b27b261ae.png', '2017-10-04', '2017-10-05 15:14:08', '', 1, 0, 1),
(159, 'L Arte di Gelato', 'Fabricio', 'contato@lartedigelato.com.br', '', 'cliente-1507314981.png', 'cliente-full-d2eff415dbe210a.jpg', '2017-10-02', '2017-10-06 15:36:21', '', 2, 0, 1),
(160, 'L Arte di Gelato', 'Fabricio', 'contato@lartedigelato.com.br', 'contato@lartedigelato.com.br', 'cliente-1507315912.png', 'cliente-full-113971680aa64ff.jpg', '2017-10-02', '2017-10-06 15:36:27', '', 2, 0, 1),
(161, 'Gisele Busmayer & Carolina Reis', 'Gisele Busmayer', 'studio@giselebusmayer.com.br', '', 'cliente-1507554877.png', 'cliente-full-9aad9014b6cf52e.png', '2017-10-09', '2017-10-09 10:14:37', '', 1, 0, 1),
(162, 'Allegro Curitiba', 'Laura', 'allegrofestaseeventos@gmail.com', 'allegrofestaseeventos@gmail.com', 'cliente-1511572252.png', 'cliente-full-936666c84263e61.jpg', '2017-10-11', '2017-10-11 14:42:56', '', 1, 0, 1),
(163, 'CBL Tech', 'Romildo', 'romildo@cbltech.com.br', '', '', '', '2017-10-05', '2017-10-16 14:51:03', '', 1, 0, 1),
(164, 'Welcome Trips', 'Eduardo', 'eduardo@welcometrips.com.br', '', '', '', '2017-10-18', '2017-10-17 14:12:22', '', 2, 0, 1),
(165, 'Au - Au', 'Cesar e Larissa', 'marketing@auau.com.br', '', '', '', '2017-10-18', '2017-10-19 10:14:28', '', 1, 0, 1),
(166, 'Construtora Pacific', 'Rubens', 'jrubens@construtorapacific.com.br', 'jrubens@construtorapacific.com.br', 'cliente-1511572106.png', 'cliente-full-604d89f8592e7bf.png', '2017-10-20', '2017-10-20 10:09:21', '', 2, 0, 1),
(167, 'Equatorial', 'Silvia', 'silvia@equatorialcwb.com.br', '', '', '', '2017-10-30', '2017-10-30 14:47:36', '', 1, 0, 1),
(168, 'Prospecção Novo Cliente', 'Eduardo Lobo', 'eduardo.lobo@nerdweb.com.br', 'Cliente para criação de proposta comercial', '', '', '2017-11-08', '2017-11-08 16:17:46', '', 1, 0, 1),
(169, 'FMB Arquitetura', 'Fernanda Teixeira Moura Borio', 'fernanda@fmbarquitetura.com.br', '98848-0974', '', '', '2017-11-07', '2017-11-09 18:32:07', '', 1, 0, 1),
(170, 'POSITIVO', 'Karola Furutani', 'kfurutani@positivo.com.br', '', '', '', '2017-11-13', '2017-11-13 18:14:09', '', 1, 0, 1),
(171, 'Codiflex', 'Adriano', 'adriano@codiflex.com.br', '', '', '', '2017-11-09', '2017-11-17 11:41:14', '', 1, 0, 1),
(172, 'Vida Saudável Curitiba', 'Kristiane Lopes', 'kristiane_lopes@hotmail.com', 'kristiane_lopes@hotmail.com', 'cliente-1511267302.png', '', '2017-11-20', '2017-11-20 16:29:46', '', 2, 0, 1),
(173, 'Riso Odontologia', 'Gabriel Picoly', 'gabriel_dentista@hotmail.com', '41-3556-1998', '', '', '2017-11-20', '2017-11-29 16:11:08', '', 1, 0, 1),
(174, 'Zoes', 'Magda', 'rasera@zoes.com.br', '', '', '', '2017-12-12', '2017-12-13 15:17:24', '', 1, 0, 1),
(175, 'Linea Fitness', 'Camila', 'contato@lineafitness.com.br', '', '', '', '2017-12-15', '2017-12-15 16:59:39', '', 2, 0, 1),
(176, 'RL Eventos', 'Bebel', 'rleventosespeciais@gmail.com', '', '', '', '2017-12-18', '2017-12-19 09:52:08', '', 1, 0, 1),
(177, 'Ortodontia Curitba', 'Marcio', 'curyortodontia@hotmail.com', '', '', '', '2017-12-13', '2017-12-22 10:18:43', '', 1, 0, 1),
(178, 'RiseApp', 'Carlos', 'carlosz@solyos1.onmicrosoft.com', 'Carlos ou Jefferson', '', '', '2017-12-22', '2017-12-28 09:31:17', '', 1, 0, 1),
(179, 'Funfit', 'Adrielli', 'contato@funfitfazbem.com.br', 'contato@funfitfazbem.com.br', '', '', '2018-01-02', '2018-01-02 15:34:22', '', 1, 0, 1),
(180, 'Extramed', 'Miriã', 'miria@extramed.com.br', '', '', '', '2018-01-17', '2018-01-17 16:18:51', '', 1, 0, 1),
(181, 'Esalink', 'Edson', 'edson@esalink.com.br', '', '', '', '2018-01-24', '2018-01-24 15:32:39', '', 1, 0, 1),
(182, 'IBEXO', 'Ademar Gevaerd', 'ajgevaerd@gmail.com', '', '', '', '2018-01-30', '2018-01-30 15:33:42', '', 1, 0, 1),
(183, 'Stoll Alimentos', 'Patricia', 'patricia@stollalimentos.com.br', '', '', '', '2018-02-09', '2018-02-14 17:50:05', '', 2, 0, 1),
(184, 'Montenegro Investimentos', 'Eduardo', 'eduardo@montenegroinvest.com.br', '', '', '', '2018-02-21', '2018-02-21 16:10:36', '', 1, 0, 1),
(185, 'CBPDV', 'A. J. Gevaerd', 'ajgevaerd@gmail.com ', '', '', '', '2018-03-02', '2018-03-05 09:31:27', '', 1, 0, 1),
(186, 'Alphaquest Games', 'Nikolas', 'alphaquestgames@gmail.com', 'alphaquestgames@gmail.com', '', '', '2018-03-05', '2018-03-05 11:20:21', '', 2, 0, 1),
(187, 'Lecheta e Armim', 'Junior', 'junior@lechetacontabilidade.com.br', '', '', '', '2018-03-02', '2018-03-08 14:48:38', '', 2, 0, 1),
(188, 'Rodoxisto', 'Eduardo', 'eduardo@rodoxisto.com.br', '', '', '', '2018-03-19', '2018-03-19 11:42:19', '', 1, 0, 1),
(189, 'Multipisos', 'Giovane', 'marketing@logpiso.com', '', '', '', '2018-03-20', '2018-03-20 11:09:41', '', 2, 0, 1),
(190, 'Al-Kimiya', 'Leonardo', 'leorisafor@hotmail.com', '', '', '', '2018-04-03', '2018-04-03 11:56:11', '', 1, 0, 1),
(191, 'Brunetto Sorrisos', 'Daniel', 'daniel_brunetto@hotmail.com', '', '', '', '2018-04-04', '2018-04-05 16:26:07', '', 1, 0, 1),
(192, 'Grupo Triunfante', 'Dietmar', 'dietmar@triunfante.com.br', '', 'cliente-1522969974.png', 'Captura de Tela 2018-04-05 às 20-a3f42a63e924f5a.png', '2018-04-05', '2018-04-05 20:12:54', '', 1, 0, 1),
(193, 'Aba Alimentos', 'Dietmar', 'dietmar@triunfante.com.br', '', 'cliente-1522970037.png', 'Captura de Tela 2018-04-05 às 20-05c13d0f9750a84.png', '2018-04-05', '2018-04-05 20:13:57', '', 1, 0, 1),
(194, 'Grupo Milênio', 'Dietmar', 'dietmar@triunfante.com.br', '', 'cliente-1522970069.png', 'Captura de Tela 2018-04-05 às 20-b88fca9962d7e48.png', '2018-04-05', '2018-04-05 20:14:29', '', 1, 0, 1),
(195, 'Grupo Arrojito', 'Dietmar', 'dietmar@triunfante.com.br', '', 'cliente-1522970115.png', 'Captura de Tela 2018-04-05 às 20-95900ceb9bca651.png', '2018-04-05', '2018-04-05 20:15:15', '', 1, 0, 1),
(196, 'Maison Marsalli', 'Morgana', 'morganafabris@hotmail.com', '', '', '', '2018-04-09', '2018-04-09 11:14:02', '', 1, 0, 1),
(197, 'Mobile Sys', 'Dietmar', 'dietmar@triunfante.com.br', '', '', '', '2018-04-05', '2018-04-11 10:32:07', '', 2, 0, 1),
(198, 'Mobile Promoter', 'Dietmar', 'dietmar@triunfante.com.br', '', '', '', '2018-04-05', '2018-04-11 10:32:21', '', 2, 0, 1),
(199, 'O que fazer Curitiba', 'Mariah', 'contato@oquefazercuritiba.com.br', '', '', '', '2018-04-12', '2018-04-13 11:17:39', '', 2, 0, 1),
(200, 'ABECO', 'Isabela', 'isagalarda@gmail.com', '', '', '', '2018-04-13', '2018-04-13 11:47:34', '', 1, 0, 1),
(201, 'Wobbi', 'Eduardo', 'eduardos@solyos.com.br', '', '', '', '2018-04-17', '2018-04-17 13:20:15', '', 1, 0, 1),
(202, 'I9 Crossfit', 'Eriston', 'eriston@panoramico.com.br', '', '', '', '2018-04-17', '2018-04-17 13:21:14', '', 2, 0, 1),
(203, 'Performance Net', 'Leonardo', 'leonardo@performancenet.com.br', 'leonardo@performancenet.com.br', '', '', '2018-04-25', '2018-04-25 16:28:53', '', 1, 0, 1),
(204, 'Caldo Bom', 'Leonardo', 'Leonardo.DalFarra@stival.com.br', '', 'cliente-1525698894.png', 'Captura de Tela 2018-05-07 às 10-f0f42217ce3b5c1.png', '2018-05-07', '2018-05-07 10:14:54', '', 1, 0, 1),
(205, 'Stock Box ', 'Luiz', 'luiz@stockboxcuritiba.com.br', '', '', '', '2018-05-04', '2018-05-07 15:11:30', '', 1, 0, 1),
(206, 'Raquel Rennó Lisboa', 'Raquel', 'rarenno@hotmail.com', '', '', '', '2018-05-07', '2018-05-07 15:20:25', '', 1, 0, 1),
(207, 'Solyos', 'Tatiane', 'tatianehfp@solyos.com.br', '', '', '', '2018-05-07', '2018-05-08 15:04:26', '', 2, 0, 1),
(208, 'Destak Locações', 'Ademir', 'destaklocacoes@gmail.com', '', '', '', '2018-05-09', '2018-05-09 15:46:53', '', 1, 0, 1),
(209, 'VAIO', '', '', '', '', '', '2018-05-08', '2018-05-09 17:29:22', '', 1, 0, 1),
(210, 'MHB Advogados', 'Bernardo', 'bernardo@hbadvogados.com.br ', '', '', '', '2018-04-11', '2018-05-22 15:30:50', '', 1, 0, 1),
(211, 'NK Arquitetura', 'Nicolle', 'nicolle@nkarquitetura.com.br', '', '', '', '2018-05-23', '2018-05-23 11:58:22', '', 2, 0, 1),
(212, 'DSENHO', 'Bianca Gugelmin', 'bianca@gugelmin.com.br', '', 'cliente-1527271850.png', 'Captura de Tela 2018-05-25 às 15-48c344e764587a9.png', '2018-05-24', '2018-05-25 15:10:50', '', 1, 0, 1),
(213, 'Frigosantos', 'Bianca Toaldo', 'biancatoaldo@hotmail.com', '', '', '', '2018-06-13', '2018-06-13 11:17:57', '', 1, 0, 1),
(214, 'Tresur', 'Thabata', 'thabata@tresur.com.br', '', '', '', '2018-06-06', '2018-06-20 11:45:07', '', 2, 0, 1),
(215, 'Panorama Positivo', 'Karin Osachlo', 'karinj@positivo.com.br', '', '', '', '2018-06-01', '2018-07-02 15:02:01', '', 1, 0, 1),
(216, 'Cowmeia', 'Tatiane', 'tatianehfp@solyos.com.br', '', '', '', '2018-07-05', '2018-07-05 10:40:18', '', 1, 0, 1),
(217, 'Zanco Odontologia', 'Suelen', 'suelengz@gmail.com', '', '', '', '2018-07-11', '2018-07-11 15:59:49', '', 1, 0, 1),
(218, 'Let\'s Log Cargo', 'Mauro', 'mauro@letslogcargo.com.br', '', 'cliente-1531858392.png', 'Captura de Tela 2018-07-17 às 17-a20376683297374.png', '2018-07-17', '2018-07-17 17:13:12', '', 2, 0, 1),
(219, 'Eike Trip', 'Marcos Slaviero', 'marcos_slaviero@me.com', '', '', '', '2018-07-24', '2018-07-24 16:12:12', '', 2, 0, 1),
(220, 'Go to Glow', 'Raphaela', 'rafita_gulin@hotmail.com', '', '', '', '2018-07-24', '2018-07-24 17:32:14', '', 1, 0, 1),
(221, 'Rodrigues e Bettega', '', 'pzd@rodriguesebettega.com.br', '', '', '', '2018-07-26', '2018-07-26 11:19:22', '', 1, 0, 1),
(222, 'Positivo Tec Educ', 'Aline Caron Moroz', 'acaron@positivo.com.br', 'Analista - gerente Bianca', 'cliente-1532954568.png', '30727355_2526160890943067_5416106442277169066_n-7f3afff016f3bf8.png', '2018-07-26', '2018-07-30 09:42:48', '', 1, 0, 1),
(223, 'TX Fiber', 'Frank', 'frank@txfiber.com.br', '', '', '', '2018-07-31', '2018-07-31 14:42:02', '', 1, 0, 1),
(224, 'Akrobatas', 'Ramos ', 'ramos@akrobatas.com.br', '', '', '', '2018-08-13', '2018-08-13 17:40:57', '', 2, 0, 1),
(225, 'Casa Blanca Uniformes', 'Edmilson', 'casablancauniformes2017@gmail.com', '', '', '', '2018-08-24', '2018-08-27 15:18:26', '', 2, 0, 1),
(226, 'Restaurante Victor', 'Ligia', 'ligia.vitorio@pierdovictor.com.br', '', 'cliente-1535660942.png', 'Captura de Tela 2018-08-30 às 17-c817d626d33b1b8.png', '2018-08-30', '2018-08-30 17:29:02', '', 1, 0, 1),
(227, '2 A.M.', '', 'evictorino@positivo.com.br', '', '', '', '2018-09-10', '2018-09-13 10:44:47', '', 1, 0, 1),
(228, 'ieme Comunicação', 'Tais Mainardes', 'tais@iemecomunicacao.com.br', 'tais@iemecomunicacao.com.br', 'cliente-1537810334.png', 'ieme-333c3d490f0570d.png', '2018-09-24', '2018-09-24 14:29:50', '', 2, 0, 1),
(229, 'Parada de Natal', '', 'braz2413@gmail.com', '', '', '', '2018-09-24', '2018-09-26 13:11:37', '', 2, 0, 1),
(230, 'Casa Conexão', 'Elizabeth', 'elizabeth@casaconexao.com.br', '', '', '', '2018-09-26', '2018-09-26 15:06:32', '', 1, 0, 1),
(231, 'Winter Feminina', 'Marisa', 'winterfeminina@gmail.com', '', '', '', '2018-10-09', '2018-10-10 16:03:57', '', 2, 0, 1),
(232, 'R2m Fertilizantes', 'Marcelo ou Rodrigo', 'marcelo@r2mfertilizantes.com.br', '', '', '', '2018-10-10', '2018-10-11 11:05:25', '', 1, 0, 1),
(233, 'Hard Clean', 'Euler', 'euler@hardclean.com.br', '', '', '', '2018-10-23', '2018-10-23 15:53:02', '', 1, 0, 1),
(234, 'Lonas Alvorada', 'Cesar', 'cesar@lonasalvorada.com.br', '', '', '', '2018-10-23', '2018-10-31 11:46:32', '', 1, 0, 1),
(235, 'Clínica Dr. Ulisses', 'Dani Trito', 'danitrito@icloud.com', '', '', '', '2018-11-13', '2018-11-14 10:16:32', '', 1, 0, 1),
(236, 'Nunca Usei', 'Carolina', 'carolina.vanassi@nuncausei.com', '', '', '', '2018-11-14', '2018-11-14 12:49:49', '', 2, 0, 1),
(237, 'Conaudi', 'Angela', 'angela@conaudi.com.br', '', '', '', '2018-11-23', '2018-11-23 14:39:37', '', 1, 0, 1),
(238, 'Smart One', 'Junior', 'junior@smart01.com.br', '', '', '', '2018-11-23', '2018-11-28 11:35:05', '', 1, 0, 1),
(239, 'GD9', 'Gustavo', 'gustavo.pereira@gd9rh.com.br', '', '', '', '2018-11-23', '2018-11-28 11:35:54', '', 1, 0, 1),
(240, 'Elbrus Capital', 'Alexandre Jung', 'alexandre@elbruscapital.com.br', '', '', '', '2018-12-10', '2018-12-13 18:31:58', '', 1, 0, 1),
(241, 'Vanessa Taques', 'Vanessa Taques', 'taquesvalentina@gmail.com', '', '', '', '2018-12-14', '2018-12-19 10:23:10', '', 1, 0, 1),
(242, 'Santa Night', 'Zilka', 'zilka.andretta@hotmail.com', '', '', '', '2019-01-07', '2019-01-07 17:35:07', '', 1, 0, 1),
(243, 'Ampersand', 'Aline', 'aline@ampersandcuritiba.com.br', '', '', '', '2019-01-07', '2019-01-08 15:26:21', '', 1, 0, 1),
(244, 'Oficina 021', 'Renata', 'contato@oficina021.com', '', '', '', '2019-01-11', '2019-01-14 13:17:49', '', 1, 0, 1),
(245, 'PDAON', 'Orisvaldo', 'orisvaldo@pda-on.com', '', 'cliente-1547745667.png', 'logo PDA-95a3f93db74d854.jpg', '2019-01-15', '2019-01-17 15:21:07', '', 1, 0, 1),
(246, 'Play Pet', '', 'paulo@playpet.io', '', '', '', '2019-01-18', '2019-01-18 16:09:35', '', 1, 0, 1),
(247, 'Prodeg', 'Luiz', 'luiz@prodeg.com.br', '', 'cliente-1548699972.png', 'Captura de Tela 2019-01-28 às 16-4fbe5b9c804ef6b.png', '2019-01-28', '2019-01-28 16:26:12', '', 1, 0, 1),
(248, 'Airsofttech', 'Ruben', 'contato@airsofttech.com.br', 'contato@airsofttech.com.br', '', '', '2019-02-07', '2019-02-07 14:50:06', '', 1, 0, 1),
(249, 'Steel Group', 'Peterson', 'peterson@steelgroup.com.br', '', '', '', '2019-02-14', '2019-02-14 17:10:28', '', 1, 0, 1),
(250, 'Oneer', 'Pamella Faresin', 'pamella_faresin@hotmail.com', '', '', '', '2019-02-20', '2019-02-20 11:40:02', '', 1, 0, 1),
(251, 'Lonas Kone', 'Bruno Cézar', 'admin@lonaskone.com.br', '', 'cliente-1551214631.png', 'Captura de Tela 2019-02-26 às 17-3229d1e040cec56.png', '2019-02-26', '2019-02-26 17:57:11', '', 1, 0, 1),
(252, 'Venda Recorrente', 'Adriano', 'adriano@vendarecorrente.com.br', '', '', '', '2019-03-06', '2019-03-07 10:39:47', '', 1, 0, 1),
(253, 'Law Vision', 'Christian', 'christian.majczak@go4.com.br', '', '', '', '2019-03-12', '2019-03-12 16:12:01', '', 1, 0, 1),
(254, 'AutoGestor', 'Lucas', 'lucas@autogestor.net', '', 'cliente-1552489442.png', 'Captura de Tela 2019-03-13 às 12-4bde5.png', '2019-03-12', '2019-03-13 12:04:02', '', 1, 0, 1),
(255, 'Grupo POSITIVO', '', 'atendimento@nerdweb.com.br', '', '', '', '2019-03-18', '2019-03-18 19:36:54', '', 1, 0, 1),
(256, 'Superbe', 'Rafaela Andretta', 'andrettarafaela@gmail.com', '', '', '', '2019-04-04', '2019-04-09 12:24:59', '', 1, 0, 1),
(257, 'RAC Engenharia', 'Ana', 'rh1@raceng.com.br', '', '', '', '2019-04-11', '2019-04-15 11:22:51', '', 1, 0, 1),
(258, 'Bar do Urso', 'Antonio', '99800834@ambev.com.br', '', '', '', '2019-04-16', '2019-04-16 17:20:32', '', 1, 0, 1),
(259, 'FURUKAWA', '', 'furukawa@nerdweb.com.br', '', '', '', '2019-04-01', '2019-04-16 18:46:27', '', 1, 0, 1),
(260, 'Trugg Hub', 'Jeferson', 'jeferson@cowmeia.com.br', '', '', '', '2019-04-26', '2019-04-26 10:06:40', '', 1, 0, 1),
(261, 'Retibam', 'Luciano', 'retibam@retibam.com.br ', 'R. Maj. Fabriciano do Rego Barros, 1522.\r\nHauer - Curitiba - PR\r\n(41) 3039-5842 | (41) 3039-5893 | (41) 3206-0663', '', '', '2019-05-06', '2019-05-06 18:29:05', 'R. Maj. Fabriciano do Rego Barros, 1522.\r\nHauer - Curitiba - PR\r\n(41) 3039-5842 | (41) 3039-5893 | (41) 3206-0663', 1, 0, 1),
(262, 'Oral Sin', 'Bruno Strazzi', 'bruno.strazzi@oralsin.com.br', '', 'cliente-1557240210.png', 'Captura de Tela 2019-05-07 às 11-77da0.png', '2019-05-06', '2019-05-07 11:43:30', '', 1, 0, 1),
(263, 'Hightork', 'Wodson', 'wodson.heleno@gmail.com', '', '', '', '2019-05-08', '2019-05-08 17:31:22', '', 1, 0, 1),
(264, 'FURUKAWA LATAM', 'Luanda', '', '', '', '', '2019-05-01', '2019-06-13 14:43:35', '', 1, 0, 1),
(265, 'Ababaya', 'Matheus Stival', 'matheus@stival.com.br', '', 'cliente-1560777328.png', '56161835_1208677339300006_2976405223662157824_n-0962f.jpg', '2019-06-17', '2019-06-17 10:15:28', '', 1, 0, 1),
(266, 'Valemam Perfis Metálicos LTDA', 'Vanessa', 'vanessa@valemam.com.br', 'vanessa@valemam.com.br', '', '', '2019-08-06', '2019-08-06 16:43:52', '', 1, 0, 1),
(267, 'Protege Medicina e Segurança do Trabalho', 'Brenda', 'administrativo@protege.med.br', '', '', '', '2019-08-16', '2019-08-16 16:37:50', '', 1, 0, 1),
(268, 'Loja Corr', 'Ana Clara', 'ana.clara@lojacorr.com.br', '', '', '', '2019-08-21', '2019-08-22 16:18:27', '', 1, 0, 1),
(269, 'Multicom Internet', 'André', 'gerencia01@administracaocuritiba.com.br', 'gerencia01@administracaocuritiba.com.br', '', '', '2019-08-28', '2019-08-28 11:41:43', '', 1, 0, 1),
(270, 'Jasmine', 'MIchelle', 'mmartins@gv-grupo.com', '', '', '', '2019-09-02', '2019-09-04 19:21:38', '', 1, 0, 1),
(271, 'Shop B', 'Gabriel', 'gabriel@shopb.com.br', '', '', '', '2019-09-05', '2019-09-06 10:32:39', '', 1, 0, 1),
(272, 'Meu Game Usado', 'Gabriel', 'gabriel@shopb.com.br', '', '', '', '2019-09-05', '2019-09-06 10:37:45', '', 1, 0, 1),
(273, 'Clube Games', 'Gabriel', 'gabriel@shopb.com.br', '', '', '', '2019-09-05', '2019-09-06 10:38:42', '', 1, 0, 1),
(274, 'Primo Amore', 'Melvin', 'mkohane@mps.com.br', '', '', '', '2019-09-05', '2019-09-06 10:51:37', '', 1, 0, 1),
(275, 'ZAX STORE', 'Bruna', 'bruna@nerdweb.com.br', '', '', '', '2019-09-26', '2019-09-26 11:32:43', '', 1, 0, 1),
(276, 'Meu Game Usado', 'Gabriel', 'gabriel@shopb.com.br', '', '', '', '2019-09-01', '2019-10-18 11:15:29', '', 1, 0, 1),
(277, 'Club Games', 'Gabriel Bolico', 'gabriel@shopb.com.br', 'Mesmo grupo de ShopB e meu game usado', '', '', '2019-09-01', '2019-10-18 11:16:07', '', 1, 0, 1),
(278, 'GM Info', 'Giancarlo', 'vendas@gminfo.com.br', '', '', '', '2019-11-01', '2019-11-04 10:26:48', '', 1, 0, 1),
(279, 'CSMA', 'Tarso Mastella ', '', '', '', '', '2019-11-06', '2019-11-11 14:13:29', 'Era a Acterra, agora passou para a Holding AGUA VERDE  ', 1, 634, 1),
(280, 'Nutrilinda', 'Ermelinda', 'ermelinda_vela@hotmail.com', '', 'cliente-1573564470.png', '68508025_1390443647778690_7470715596980617216_o-7fd66.jpg', '2019-11-08', '2019-11-12 10:14:30', '', 1, 0, 1),
(281, 'Construction Service CSMA', 'Tarso', 'tm@arcterra.com>', '', 'cliente-1573678187.png', 'ConstructionService_Peter Maldonado-2d2a4.jpg', '2019-11-08', '2019-11-13 17:49:47', '', 1, 0, 1),
(282, 'Província Marista Sul', 'Raquel Leite', ' leite.raquel@marista.org.br', ' leite.raquel@marista.org.br', '', '', '2019-11-13', '2019-11-14 10:01:17', '', 1, 635, 1),
(283, ' Roca', 'Laura', 'laura.torres@incepa.com.br', '', '', '', '2019-12-05', '2019-12-09 15:03:56', '', 1, 637, 1),
(284, 'INCEPA', 'Laura Torres', 'laura.torres@incepa.com.br', '', '', '', '2019-12-05', '2019-12-09 15:05:08', '', 1, 637, 1),
(285, 'JS Studio de Dança', 'Tim Trauer', 'timtrauer@gmail.com', '', '', '', '2019-12-09', '2019-12-09 17:34:10', '', 1, 0, 1),
(286, 'Robson Souza', 'Robson Souza', 'joaodasilva@joaodasilva.com.br', '', '', '', '2020-01-10', '2020-01-13 09:46:51', '', 1, 639, 1),
(287, 'Frota G', 'lirian ', ' lirian.gross@gestran.com.br', '', '', '', '2020-01-13', '2020-01-16 16:28:36', '', 1, 640, 1),
(288, 'Capital Realty', 'Mariana', 'mariana.magalhaes@capitalrealty.com.br', '', '', '', '2020-01-20', '2020-01-20 15:47:50', '', 1, 0, 1),
(289, 'Aprende Brasil', 'Tatiana', 'trzaskos@positivo.com.br', '', '', '', '2020-02-04', '2020-02-05 18:15:13', '', 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `back_Servico`
--

CREATE TABLE `back_Servico` (
  `servico` int(11) NOT NULL,
  `nome` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `descricao` text CHARACTER SET utf8 DEFAULT NULL,
  `whServiceId` int(11) DEFAULT NULL,
  `isUsed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `back_Servico`
--

INSERT INTO `back_Servico` (`servico`, `nome`, `descricao`, `whServiceId`, `isUsed`) VALUES
(1, 'Facebook', 'Facebook', 1, 1),
(2, 'Website', 'Website\r\n', 2, 1),
(3, 'Material Gráfico', 'Material Gráfico', 3, 1),
(4, 'Wallpaper', 'Material Gráfico', 3, 1),
(5, 'POP Flow', 'Produto Social Monster', 2, 1),
(6, 'Folder 2 Dobras', '', 3, 1),
(7, 'Email', '', 3, 1),
(8, 'Apresentação', '', 3, 1),
(9, 'SEO', '', 2, 1),
(10, 'Assinaturas de Email', '', 3, 1),
(11, 'Texto Blog', '', 3, 1),
(12, 'Identidade Visual', '', 3, 1),
(13, 'Banner Site', '', 2, 1),
(14, 'Midia', '', 3, 1),
(15, 'Estudo Performance', '', 3, 1),
(16, 'Video', '', 3, 1),
(17, 'Cartão de Visita', '', 3, 1),
(18, 'Paginas Adicional Site', '', 3, 1),
(19, 'Publicidade', '', 3, 1),
(20, 'Papelaria', '', 3, 1),
(21, 'Email Marketing', '', 3, 1),
(22, 'Report', '', 2, 1),
(23, 'Papel Timbrado', '', 2, 1),
(24, 'Analise no Relatório Facebook', '', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `back_ServicoCliente`
--

CREATE TABLE `back_ServicoCliente` (
  `servicoCliente` int(11) NOT NULL,
  `cliente` int(11) NOT NULL,
  `servico` int(11) NOT NULL,
  `periodicidade` int(11) DEFAULT NULL,
  `dataAssinatura` datetime DEFAULT NULL,
  `dataAtualizacao` datetime DEFAULT NULL,
  `observacao` text CHARACTER SET utf8 DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pop_AdmAreaXelementoTipo`
--

CREATE TABLE `pop_AdmAreaXelementoTipo` (
  `area` int(11) NOT NULL,
  `elementoTipo` int(11) NOT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_AdmAreaXelementoTipo`
--

INSERT INTO `pop_AdmAreaXelementoTipo` (`area`, `elementoTipo`, `isUsed`) VALUES
(6, 37, 1),
(6, 38, 1),
(6, 39, 1),
(2, 59, 1),
(4, 60, 1),
(17, 61, 1),
(17, 62, 1),
(17, 63, 1),
(8, 64, 1),
(17, 65, 1),
(8, 66, 1),
(3, 67, 1),
(3, 68, 1),
(3, 69, 1),
(7, 70, 1),
(3, 71, 1),
(3, 72, 1),
(3, 73, 1),
(3, 74, 1),
(2, 75, 1),
(17, 76, 1),
(3, 77, 1),
(17, 78, 1),
(8, 79, 1),
(17, 80, 1),
(10, 88, 1),
(11, 89, 1),
(11, 90, 1),
(10, 91, 1),
(10, 92, 1),
(4, 94, 1),
(13, 95, 1),
(7, 96, 1),
(10, 97, 1),
(10, 98, 1),
(4, 99, 1),
(11, 100, 1),
(11, 101, 1),
(4, 102, 1),
(5, 104, 1),
(6, 106, 1),
(4, 107, 1),
(1, 111, 1),
(4, 120, 1),
(4, 125, 1),
(4, 126, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_Alerta`
--

CREATE TABLE `pop_Alerta` (
  `alerta` int(11) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `dataCriacao` datetime DEFAULT NULL,
  `dataExpiracao` datetime DEFAULT NULL,
  `projeto` int(11) DEFAULT NULL,
  `elemento` int(11) DEFAULT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pop_ColunasBase`
--

CREATE TABLE `pop_ColunasBase` (
  `colunaBase` int(11) NOT NULL,
  `nome` varchar(256) CHARACTER SET utf8 NOT NULL,
  `isUsed` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_ColunasBase`
--

INSERT INTO `pop_ColunasBase` (`colunaBase`, `nome`, `isUsed`) VALUES
(1, 'Tarefas', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_ColunasBaseNomeTipo`
--

CREATE TABLE `pop_ColunasBaseNomeTipo` (
  `colunaBase` int(11) NOT NULL,
  `nome` varchar(128) CHARACTER SET utf8 NOT NULL,
  `nomeExibicao` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `tipo` varchar(128) CHARACTER SET utf8 NOT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_ColunasBaseNomeTipo`
--

INSERT INTO `pop_ColunasBaseNomeTipo` (`colunaBase`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
(1, 'area', 'Área Responsável', 'area', 1),
(1, 'dtFim', 'Data Fim', 'date', 1),
(1, 'dtInicio', 'Data Início', 'date', 1),
(1, 'prazo', 'Prazo', 'date', 1),
(1, 'prioridade', 'Prioridade', 'prioridade', 1),
(1, 'responsavel', 'Responsável', 'responsavel', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_ColunasBaseXelementoTipo`
--

CREATE TABLE `pop_ColunasBaseXelementoTipo` (
  `elementoTipo` int(11) NOT NULL,
  `colunasBase` int(11) NOT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_ColunasBaseXelementoTipo`
--

INSERT INTO `pop_ColunasBaseXelementoTipo` (`elementoTipo`, `colunasBase`, `isUsed`) VALUES
(37, 1, 1),
(38, 1, 1),
(39, 1, 1),
(59, 1, 1),
(60, 1, 1),
(61, 1, 1),
(62, 1, 1),
(63, 1, 1),
(64, 1, 1),
(65, 1, 1),
(66, 1, 1),
(67, 1, 1),
(68, 1, 1),
(69, 1, 1),
(70, 1, 1),
(71, 1, 1),
(72, 1, 1),
(73, 1, 1),
(74, 1, 1),
(75, 1, 1),
(76, 1, 1),
(77, 1, 1),
(78, 1, 1),
(79, 1, 1),
(80, 1, 1),
(81, 1, 1),
(82, 1, 1),
(88, 1, 1),
(89, 1, 1),
(90, 1, 1),
(91, 1, 1),
(92, 1, 1),
(94, 1, 1),
(95, 1, 1),
(96, 1, 1),
(97, 1, 1),
(98, 1, 1),
(99, 1, 1),
(100, 1, 1),
(101, 1, 1),
(102, 1, 1),
(104, 1, 1),
(105, 1, 1),
(106, 1, 1),
(107, 1, 1),
(111, 1, 1),
(120, 1, 1),
(125, 1, 1),
(126, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_Elemento`
--

CREATE TABLE `pop_Elemento` (
  `elemento` int(11) NOT NULL,
  `dataCriacao` datetime DEFAULT NULL,
  `dataAtualizacao` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `elementoTipo` int(11) NOT NULL,
  `elementoStatus` int(11) NOT NULL,
  `projeto` int(11) NOT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_Elemento`
--

INSERT INTO `pop_Elemento` (`elemento`, `dataCriacao`, `dataAtualizacao`, `elementoTipo`, `elementoStatus`, `projeto`, `isUsed`) VALUES
(2, '2020-06-15 18:50:49', '2020-06-15 21:50:50', 82, 2, 13, 1),
(3, '2020-06-15 18:52:16', '2020-06-15 21:52:16', 37, 1, 14, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_ElementoAnterior`
--

CREATE TABLE `pop_ElementoAnterior` (
  `projetoTipo` int(11) NOT NULL,
  `elementoTipo` int(11) NOT NULL,
  `anterior` int(11) NOT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_ElementoAnterior`
--

INSERT INTO `pop_ElementoAnterior` (`projetoTipo`, `elementoTipo`, `anterior`, `isUsed`) VALUES
(10, 70, 71, 1),
(10, 74, 70, 1),
(10, 77, 76, 1),
(10, 78, 76, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_ElementoCategoria`
--

CREATE TABLE `pop_ElementoCategoria` (
  `categoria` int(11) NOT NULL,
  `cliente` int(11) DEFAULT NULL,
  `nome` varchar(256) CHARACTER SET utf8 NOT NULL,
  `icone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT 'fa-circle',
  `cor` varchar(7) CHARACTER SET utf8 NOT NULL DEFAULT '#ffffff',
  `descricao` text CHARACTER SET utf8 DEFAULT NULL,
  `identifier` varchar(256) CHARACTER SET utf8 DEFAULT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_ElementoCategoria`
--

INSERT INTO `pop_ElementoCategoria` (`categoria`, `cliente`, `nome`, `icone`, `cor`, `descricao`, `identifier`, `isUsed`) VALUES
(1, NULL, 'Produto', 'fa-circle', '#366cd6', NULL, NULL, 1),
(2, NULL, 'Conceito', 'fa-circle', '#a5f963', 'Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.', NULL, 1),
(3, NULL, 'Institucional', 'fa-circle', '#bc8771', NULL, NULL, 1),
(4, NULL, 'Frases', 'fa-circle', '#abe0d3', NULL, NULL, 1),
(5, NULL, 'Dicas', 'fa-circle', '#ccc1ac', 'Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.', NULL, 1),
(6, NULL, 'Curiosidades', 'fa-circle', '#18be26', NULL, NULL, 1),
(7, NULL, 'Carnes', 'fa-circle', '#e82222', NULL, NULL, 1),
(8, NULL, 'Massas', 'fa-circle', '#f2f716', 'Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.', NULL, 1),
(9, NULL, 'Imagens', 'fa-circle', '#c12596', NULL, NULL, 1),
(10, NULL, 'Repost', 'fa-circle', '#9b8d9e', NULL, NULL, 1),
(11, NULL, 'Links sobre Tatoo', 'fa-circle', '#5b5f7f', NULL, NULL, 1),
(12, NULL, 'Bem Estar', 'fa-circle', '#46583d', NULL, NULL, 1),
(13, NULL, 'Cuidados', 'fa-circle', '#c4471f', NULL, NULL, 1),
(14, NULL, 'Prevenção', 'fa-circle', '#379594', NULL, NULL, 1),
(15, NULL, 'Tratamento', 'fa-circle', '#d29c9c', NULL, NULL, 1),
(16, NULL, 'Doenças Bucais', 'fa-circle', '#106055', NULL, NULL, 1),
(17, NULL, 'Eventos', 'fa-circle', '#8211c6', NULL, NULL, 1),
(18, NULL, 'Feijoada', 'fa-circle', '#dfec25', NULL, NULL, 1),
(19, NULL, 'Destaque do dia', 'fa-circle', '#1c1616', NULL, NULL, 1),
(20, NULL, 'Feriado', 'fa-circle', '#e2abfc', NULL, NULL, 1),
(21, NULL, 'Sobremesas', 'fa-circle', '#f218dc', NULL, NULL, 1),
(22, NULL, 'FETICHE', 'fa-circle', '#f2aeae', NULL, NULL, 1),
(23, NULL, 'SENSUALIDADE', 'fa-circle', '#dadd39', NULL, NULL, 1),
(24, NULL, 'CURIOSIDADES SOBRE SEXO', 'fa-circle', '#76965e', NULL, NULL, 1),
(25, NULL, 'NOTÍCIAS RELACIONADAS AO SEXO', 'fa-circle', '#82cc7c', NULL, NULL, 1),
(26, NULL, 'Links sobre Tecnologia', 'fa-circle', '#17737f', NULL, NULL, 1),
(27, NULL, 'Próteses', 'fa-circle', '#1c76c9', NULL, NULL, 1),
(28, NULL, 'Cursos', 'fa-circle', '#b0bc25', NULL, NULL, 1),
(29, NULL, 'Tendência', 'fa-circle', '#d3d3d3', NULL, NULL, 1),
(30, NULL, 'Engajamento', 'fa-circle', '#8590ba', NULL, NULL, 1),
(31, NULL, 'Promoção', 'fa-circle', '#ff810c', NULL, NULL, 1),
(32, NULL, 'SEM CHAMADA', 'fa-circle', '#cddaea', '', NULL, 1),
(33, NULL, 'POTINHO SORVETE', 'fa-circle', '#f2dd25', '', NULL, 1),
(34, NULL, 'Single', 'fa-circle', '#f4efd2', 'solteiros, que geralmente moram sozinhos. Procuram opções de alimentação bastante práticas, saborosas e saudáveis/naturais.', NULL, 1),
(35, NULL, 'High Fitness', 'fa-circle', '#f9a61b', 'praticantes de exercícios físicos, preocupados em manter uma alimentação saudável/natural, que contribua para sua performance.', NULL, 1),
(36, NULL, 'Dona de Casa Moderna', 'fa-circle', '#c74229', 'cozinhar faz parte da sua rotina e querem fazer dessa tarefa algo prazeroso, sem abrir mão da qualidade da alimentação. Por isso, buscam um alimento natural aliado à praticidade pro dia-a- dia corrido, mas que ao mesmo tempo gere reconhecimento do preparo.', NULL, 1),
(37, NULL, 'Iniciante Gourmet', 'fa-circle', '#533a5d', 'adoram cozinhar, mas valorizam a praticidade. Fazem questão de dar seu toque às receitas (o seu próprio tempero).', NULL, 1),
(38, NULL, 'Vida Saudável', 'fa-circle', '#340014', 'Se preocupa com sua alimentação, vegetarianos, orgânicos, slowfood, natureba,', NULL, 1),
(39, NULL, 'humor', 'fa-circle', '#f9ff00', '', NULL, 1),
(41, NULL, 'datas comemorativas', 'fa-circle', '#637765', '', NULL, 1),
(42, NULL, 'Saudabilidade', 'fa-circle', '#d8fffa', 'Falar sobre a saúde aliada a alimentação natural e como isso reflete na saúde física e mental do consumidor.', NULL, 1),
(43, NULL, 'LINK BLOG', 'fa-circle', '#b992b6', '', NULL, 1),
(44, NULL, 'Lifestyle', 'fa-circle', '#FF7B0D', 'Lifestyle', NULL, 1),
(45, NULL, 'Conversão', 'fa-circle', '#2d7093', '', NULL, 1),
(46, NULL, 'Informativo', 'fa-circle', '#a6b500', '', NULL, 1),
(47, NULL, 'Acessórios', 'fa-circle', '', 'MyMob', NULL, 1),
(48, NULL, 'Assistência Técnica', 'fa-circle', '#f72c2c', 'MyMob', NULL, 1),
(49, NULL, 'Vídeos Acessórios', 'fa-circle', '#000000', '', NULL, 1),
(50, NULL, 'Vídeos Assistência', 'fa-circle', '#990000', '', NULL, 1),
(51, NULL, 'Post Blog', 'fa-circle', '#378e0e', '', NULL, 1),
(52, NULL, 'Black Friday', 'fa-circle', '#000000', 'Posts promocionais do maior evento comercial do ano', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_ElementoChat`
--

CREATE TABLE `pop_ElementoChat` (
  `elemento` int(11) NOT NULL,
  `responsavel` int(11) NOT NULL,
  `sistema` tinyint(4) NOT NULL DEFAULT 0,
  `conteudo` text NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp(),
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pop_ElementoConteudo`
--

CREATE TABLE `pop_ElementoConteudo` (
  `elemento` int(11) NOT NULL,
  `chave` varchar(128) CHARACTER SET utf8 NOT NULL,
  `valor` text DEFAULT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_ElementoConteudo`
--

INSERT INTO `pop_ElementoConteudo` (`elemento`, `chave`, `valor`, `isUsed`) VALUES
(2, 'Aprovar', NULL, 1),
(2, 'area', '12', 1),
(2, 'arquivos', NULL, 1),
(2, 'Cliente', 'Nerdweb', 1),
(2, 'Descricao', 'Teste', 1),
(2, 'De_Area', '2', 1),
(2, 'dtFim', NULL, 1),
(2, 'dtInicio', NULL, 1),
(2, 'Etapa', '68', 1),
(2, 'Finalizado', NULL, 1),
(2, 'Nome', 'Teste', 1),
(2, 'Observacoes', NULL, 1),
(2, 'Para_Area', '12', 1),
(2, 'prazo', '2020-06-16', 1),
(2, 'prioridade', '102', 1),
(2, 'Produto', 'popflow', 1),
(2, 'responsavel', '78', 1),
(2, 'responsavel_de', '6', 1),
(2, 'responsavel_para', '78', 1),
(3, 'area', '6', 1),
(3, 'dtFim', NULL, 1),
(3, 'dtInicio', NULL, 1),
(3, 'Etapa', NULL, 1),
(3, 'prazo', NULL, 1),
(3, 'prioridade', '102', 1),
(3, 'responsavel', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_ElementoConteudoJSON`
--

CREATE TABLE `pop_ElementoConteudoJSON` (
  `elemento` int(11) NOT NULL,
  `conteudo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `isUsed` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pop_ElementoHistorico`
--

CREATE TABLE `pop_ElementoHistorico` (
  `usuario` int(11) NOT NULL,
  `elemento` int(11) NOT NULL,
  `acao` tinyint(4) NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp(),
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pop_ElementoHistoricoAcao`
--

CREATE TABLE `pop_ElementoHistoricoAcao` (
  `acao` tinyint(4) NOT NULL,
  `nome` varchar(256) CHARACTER SET utf8 NOT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_ElementoHistoricoAcao`
--

INSERT INTO `pop_ElementoHistoricoAcao` (`acao`, `nome`, `isUsed`) VALUES
(1, 'Iniciar', 1),
(2, 'Pausar', 1),
(3, 'Retomar', 1),
(4, 'Finalizar', 1),
(5, 'Cancelar', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_ElementoJSON`
--

CREATE TABLE `pop_ElementoJSON` (
  `elemento` int(11) NOT NULL,
  `dataCriacao` datetime DEFAULT NULL,
  `dataAtualizacao` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `elementoTipo` int(11) NOT NULL,
  `elementoStatus` int(11) NOT NULL,
  `projeto` int(11) NOT NULL,
  `campos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pop_ElementoPrimeiro`
--

CREATE TABLE `pop_ElementoPrimeiro` (
  `projetoTipo` int(11) NOT NULL,
  `elementoTipo` int(11) NOT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_ElementoPrimeiro`
--

INSERT INTO `pop_ElementoPrimeiro` (`projetoTipo`, `elementoTipo`, `isUsed`) VALUES
(2, 37, 1),
(9, 59, 1),
(10, 61, 1),
(10, 62, 1),
(11, 88, 1),
(12, 94, 1),
(13, 99, 1),
(14, 106, 1),
(16, 120, 1),
(20, 111, 1),
(21, 125, 1),
(24, 126, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_ElementoProximo`
--

CREATE TABLE `pop_ElementoProximo` (
  `projetoTipo` int(11) NOT NULL,
  `elementoTipo` int(11) NOT NULL,
  `proximo` int(11) NOT NULL,
  `isUsed` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_ElementoProximo`
--

INSERT INTO `pop_ElementoProximo` (`projetoTipo`, `elementoTipo`, `proximo`, `isUsed`) VALUES
(2, 37, 38, 0),
(2, 38, 39, 0),
(9, 59, 60, 1),
(10, 61, 63, 0),
(10, 61, 64, 0),
(10, 61, 65, 1),
(10, 61, 66, 0),
(10, 61, 67, 1),
(10, 67, 68, 0),
(10, 67, 71, 0),
(10, 67, 73, 0),
(10, 68, 69, 1),
(10, 68, 80, 1),
(10, 69, 70, 1),
(10, 70, 74, 1),
(10, 74, 75, 1),
(10, 75, 76, 1),
(10, 76, 77, 1),
(10, 77, 78, 1),
(10, 78, 79, 1),
(10, 81, 70, 1),
(11, 88, 89, 1),
(11, 89, 90, 1),
(11, 90, 91, 1),
(11, 91, 92, 1),
(12, 94, 95, 1),
(12, 95, 96, 1),
(12, 96, 97, 1),
(12, 97, 98, 1),
(13, 99, 100, 1),
(13, 100, 101, 1),
(13, 101, 102, 1),
(13, 102, 104, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_ElementoStatus`
--

CREATE TABLE `pop_ElementoStatus` (
  `elementoStatus` int(11) NOT NULL,
  `nome` varchar(256) CHARACTER SET utf8 DEFAULT NULL,
  `descricao` text CHARACTER SET utf8 DEFAULT NULL,
  `cor` varchar(7) CHARACTER SET utf8 DEFAULT NULL,
  `identifier` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'string que identifica o status\nex: \nSTATUS_AGUARDANDO_RESPONSAVEL\nSTATUS_EM_ANDAMENTO',
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_ElementoStatus`
--

INSERT INTO `pop_ElementoStatus` (`elementoStatus`, `nome`, `descricao`, `cor`, `identifier`, `isUsed`) VALUES
(0, 'Sem Status', NULL, '#000000', 'POP_TAREFA_SEMSTATUS', 1),
(1, 'Aguardando Responsável', NULL, '#FDAB3D', 'POP_TAREFA_AGUARDANDORESPONSAVEL', 1),
(2, 'Aguardando Início', NULL, '#FDAB3D', 'POP_TAREFA_AGUARDANDOINICIO', 1),
(3, 'Aguardando Cliente', NULL, '#34495e', 'POP_TAREFA_AGUARDANDOCLIENTE', 1),
(4, 'Em Andamento', NULL, '#0086C0', 'POP_TAREFA_EMANDAMENTO', 1),
(5, 'Pausado', NULL, '#666666', 'POP_TAREFA_PAUSADO', 1),
(6, 'Parado', NULL, '#444444', 'POP_TAREFA_PARADO', 1),
(7, 'Problema', NULL, '#E2445C', 'POP_TAREFA_PROBLEMA', 1),
(8, 'Finalizado', NULL, '#00C875', 'POP_TAREFA_FINALIZADO', 1),
(9, 'Finalizado', NULL, '#00C875', 'POP_TAREFA_FINALIZADO_PROCESSADO', 1),
(10, 'Voltar Etapa', NULL, '#00C875', 'POP_TAREFA_VOLTA_ETAPA', 1),
(11, 'Etapa Reprovada', NULL, '#00C875', 'POP_TAREFA_VOLTA_ETAPA_PROCESSADO', 1),
(12, 'Finalizado Etapa', NULL, '#00C875', 'POP_TAREFA_SUBETAPA_FINALIZADA', 1),
(13, 'Finalizado Etapa', NULL, '#00C875', 'POP_TAREFA_SUBETAPA_FINALIZADA_PROCESSADO', 1),
(14, 'Tarefa Concluída', NULL, '#00C875', 'POP_TAREFA_SUBETAPA_AVANCA', 1),
(15, 'Tarefa Reprovada', NULL, '#00C875', 'POP_TAREFA_SUBETAPA_VOLTA', 1),
(16, 'Pedido Arquivado', NULL, '#ff0000', 'POP_PEDIDO_ARQUIVADO', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_ElementoTipo`
--

CREATE TABLE `pop_ElementoTipo` (
  `elementoTipo` int(11) NOT NULL,
  `nome` varchar(256) CHARACTER SET utf8 DEFAULT NULL,
  `prazo` int(11) NOT NULL DEFAULT 0,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_ElementoTipo`
--

INSERT INTO `pop_ElementoTipo` (`elementoTipo`, `nome`, `prazo`, `isUsed`) VALUES
(37, 'Criar Planejamento Calendário', 0, 1),
(38, 'Post', 0, 1),
(39, 'Finalização da Campanha', 0, 1),
(59, 'Pedido', 0, 1),
(60, 'Fim', 0, 1),
(61, 'Coletar Briefing', 0, 1),
(62, 'Coletar Identidade Visual', 0, 1),
(63, 'Pegar Dados do Domínio', 0, 1),
(64, 'Criar Infraestrutura INTERNA', 0, 1),
(65, 'Criar Infraestrutura EXTERNA', 0, 1),
(66, 'Criar Contas de Email', 0, 1),
(67, 'Criar Wireframe', 0, 1),
(68, 'Criar Lista de Conteúdo', 0, 1),
(69, 'Recortar Layout', 0, 1),
(70, 'Montar HTML/CSS', 0, 1),
(71, 'Definir Número de Páginas Internas', 0, 1),
(72, 'Criar Página', 0, 1),
(73, 'Criar Lista de Módulos', 0, 1),
(74, 'Aprovar Responsivo no Site Todo', 0, 1),
(75, 'Inserir Módulos', 0, 1),
(76, 'Inserir Conteúdo', 0, 1),
(77, 'Revisar Site para Publicação', 0, 1),
(78, 'Aprovar com Cliente', 0, 1),
(79, 'Publicar Site', 0, 1),
(80, 'Coletar Conteúdo Página', 0, 1),
(81, 'Alterar Pagina(s)', 0, 1),
(82, 'Pedido-V2', 0, 1),
(88, 'Cadastrar Briefing', 0, 1),
(89, 'Aprovar Conceito', 0, 1),
(90, 'Aprovar Desdobramento de Conceito', 0, 1),
(91, 'Publicar Campanha', 0, 1),
(92, 'Revisar Publicação de Campanha', 0, 1),
(94, 'Cadastrar Briefing', 0, 1),
(95, 'Aprovar Template', 0, 1),
(96, 'Aprovar Montar Template', 0, 1),
(97, 'Publicar E-Mail Marketing', 0, 1),
(98, 'Revisar e Disparar E-mail Marketing', 0, 1),
(99, 'Cadastrar Briefing', 0, 1),
(100, 'Aprovar Conceito', 0, 1),
(101, 'Aprovar Material Finalizado', 0, 1),
(102, 'Entregar Material Finalizado', 0, 1),
(104, 'Faturar Material Grafico', 0, 1),
(105, 'Pedido-V3', 0, 1),
(106, 'Cadastrar e Criar Pautas', 0, 1),
(107, 'Criacao de texto do Post', 0, 1),
(108, 'Cadastro do Briefing', 0, 1),
(111, 'Descricao de Horas Trabalhadas', 0, 1),
(120, 'Cadastro do Briefing/Pedido', 0, 1),
(125, 'Cadastro do Briefing/Pedido', 0, 1),
(126, 'Cadastro do Briefing/Pedido', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_ElementoTipoNomeTipo`
--

CREATE TABLE `pop_ElementoTipoNomeTipo` (
  `elementoTipo` int(11) NOT NULL,
  `nome` varchar(128) CHARACTER SET utf8 NOT NULL,
  `nomeExibicao` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `tipo` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT 'text',
  `isUsed` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_ElementoTipoNomeTipo`
--

INSERT INTO `pop_ElementoTipoNomeTipo` (`elementoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
(37, 'Etapa', 'Tarefa', 'etapa', 1),
(38, 'Categoria', 'Categoria', 'categoria', 1),
(38, 'Dia', 'Data do Post', 'date', 1),
(38, 'Etapa', 'Tarefa', 'etapa', 1),
(38, 'image', 'Imagem do Post', 'img', 1),
(38, 'Observacoes', 'Observações', 'textarea', 1),
(38, 'referencia', 'Referência', 'img', 1),
(38, 'Texto', 'Texto do Post', 'textarea', 1),
(38, 'textoImagem', 'Texto da Imagem', 'textarea', 1),
(59, 'Aprovar', NULL, 'boolean', 1),
(59, 'arquivos', 'Material Extra', 'file', 1),
(59, 'Cliente', 'Nome Cliente', 'text', 1),
(59, 'De_Area', NULL, 'area', 1),
(59, 'Etapa', 'Etapa', 'etapa', 1),
(59, 'Finalizado', NULL, 'bool', 1),
(59, 'Nome', NULL, 'text', 1),
(59, 'Observacoes', 'Observações', 'textarea', 1),
(59, 'Para_Area', NULL, 'area', 1),
(59, 'Produto', 'Produto', 'text', 1),
(59, 'responsavel_de', 'Responsável', 'responsavel', 1),
(59, 'responsavel_para', 'Responsável', 'responsavel', 1),
(61, 'arquivos', 'Material Extra', 'file', 1),
(61, 'briefing', 'Briefing', 'editor', 1),
(61, 'extras', 'Material Extra', 'img', 1),
(61, 'Observacoes', 'Observações', 'textarea', 1),
(62, 'arquivos', 'Id Visual', 'gdrive', 1),
(62, 'conteudo', 'Conteúdo', 'editor', 1),
(62, 'Observacoes', 'Observações', 'textarea', 1),
(63, 'Observacoes', 'Observações', 'textarea', 1),
(64, 'dominio', 'Domínio', 'textarea', 1),
(64, 'Etapa', 'Tarefa', 'etapa', 1),
(64, 'link', 'URL', 'text', 1),
(64, 'Observacoes', 'Observações', 'textarea', 1),
(65, 'dominio', 'Domínios', 'textarea', 1),
(65, 'emails', 'E-Mails', 'textarea', 1),
(65, 'Etapa', 'Tarefa', 'etapa', 1),
(65, 'Observacoes', 'Observações', 'textarea', 1),
(66, 'lista', 'Lista de Emails', 'textarea', 1),
(66, 'Observacoes', 'Observações', 'textarea', 1),
(67, 'Etapa', 'Tarefa', 'etapa', 1),
(67, 'layout', 'Layout', 'img', 1),
(67, 'link', 'URL', 'text', 1),
(67, 'link_2', 'ID Visual', 'gdrive', 1),
(67, 'Observacoes', 'Observações', 'textarea', 1),
(67, 'wireframe', 'Wireframe', 'img', 1),
(68, 'conteudo', 'Conteúdo', 'editor', 1),
(68, 'Observacoes', 'Observações', 'textarea', 1),
(69, 'link', 'URL', 'gdrive', 1),
(69, 'Observacoes', 'Observações', 'textarea', 1),
(70, 'link', 'Arquivos Recortados', 'gdrive', 1),
(70, 'link_2', 'URL De Testes', 'link', 1),
(70, 'Observacoes', 'Observações', 'textarea', 1),
(71, 'Numero de Paginas Aproximado', 'Número de Páginas', 'number', 1),
(71, 'Observacoes', 'Observações', 'textarea', 1),
(71, 'paginas', NULL, 'textarea', 1),
(72, 'Conteudo', 'Conteúdo', 'textarea', 1),
(72, 'Etapa', 'Tarefa', 'etapa', 1),
(72, 'layout', 'Layout', 'img', 1),
(72, 'link', 'URL', 'text', 1),
(72, 'link_2', 'Id Visual', 'gdrive', 1),
(72, 'Nome', 'Nome da Página', 'text', 1),
(72, 'Observacoes', 'Observações', 'textarea', 1),
(73, 'Etapa', 'Tarefa', 'etapa', 1),
(73, 'lista', 'Lista de Módulos', 'editor', 1),
(73, 'Observacoes', 'Observações', 'textarea', 1),
(74, 'Observacoes', 'Observações', 'textarea', 1),
(74, 'url', 'URL de Teste', 'link', 1),
(75, 'Observacoes', 'Observações', 'textarea', 1),
(76, 'Observacoes', 'Observações', 'textarea', 1),
(77, 'Observacoes', 'Observações', 'textarea', 1),
(78, 'Observacoes', 'Observações', 'textarea', 1),
(79, 'Observacoes', 'Observações', 'textarea', 1),
(80, 'link', 'URL', 'text', 1),
(80, 'Observacoes', 'Observações', 'textarea', 1),
(81, 'Conteudo', 'Conteúdo', 'textarea', 1),
(81, 'Etapa', 'Tarefa', 'etapa', 1),
(81, 'layout', 'Layout', 'img', 1),
(81, 'link', 'URL', 'text', 1),
(81, 'link_2', 'Id Visual', 'gdrive', 1),
(81, 'Nome', 'Nome da Página', 'text', 1),
(81, 'Observacoes', 'Observações', 'textarea', 1),
(82, 'Aprovar', NULL, 'boolean', 1),
(82, 'arquivos', 'Material Extra', 'file', 1),
(82, 'Cliente', 'Nome Cliente', 'text', 1),
(82, 'Descricao', 'Descricao', 'Text', 1),
(82, 'De_Area', NULL, 'area', 1),
(82, 'Etapa', 'Etapa', 'etapa', 1),
(82, 'Finalizado', NULL, 'bool', 1),
(82, 'Nome', NULL, 'text', 1),
(82, 'Observacoes', 'Observações', 'textarea', 1),
(82, 'Para_Area', NULL, 'area', 1),
(82, 'Produto', 'Produto', 'text', 1),
(82, 'responsavel_de', 'Responsável', 'responsavel', 1),
(82, 'responsavel_para', 'Responsável', 'responsavel', 1),
(88, 'arquivos', 'Material Extra', 'img', 1),
(88, 'briefing', 'Briefing', 'editor', 1),
(88, 'Etapa', 'Tarefa', 'etapa', 1),
(88, 'Link', 'Link Pasta Campanha', 'gdrive', 1),
(88, 'Observacoes', 'Observações', 'textarea', 1),
(89, 'Etapa', 'Tarefa', 'etapa', 1),
(89, 'Observacoes', 'Observações', 'textarea', 1),
(90, 'Etapa', 'Tarefa', 'etapa', 1),
(90, 'Observacoes', 'Observações', 'textarea', 1),
(91, 'Observacoes', 'Observações', 'textarea', 1),
(92, 'Observacoes', 'Observações', 'textarea', 1),
(94, 'arquivos', 'Link do Drive', 'gdrive', 1),
(94, 'briefing', 'Briefing', 'editor', 1),
(94, 'Etapa', 'Tarefa', 'etapa', 1),
(94, 'Observacoes', 'Observações', 'textarea', 1),
(95, 'Etapa', 'Tarefa', 'etapa', 1),
(95, 'Observacoes', 'Observações', 'textarea', 1),
(96, 'Etapa', 'Tarefa', 'etapa', 1),
(96, 'Observacoes', 'Observações', 'textarea', 1),
(97, 'Observacoes', 'Observações', 'textarea', 1),
(98, 'Observacoes', 'Observações', 'textarea', 1),
(99, 'arquivos', 'Material Extra', 'gdrive', 1),
(99, 'briefing', 'Briefing', 'editor', 1),
(99, 'Etapa', 'Tarefa', 'etapa', 1),
(99, 'Link', 'Link', 'text', 1),
(99, 'Observacoes', 'Observações', 'text', 1),
(100, 'Etapa', 'Tarefa', 'etapa', 1),
(100, 'Observacoes', 'Observações', 'text', 1),
(101, 'Etapa', 'Tarefa', 'etapa', 1),
(101, 'Observacoes', 'Observações', 'text', 1),
(102, 'Observacoes', 'Observações', 'text', 1),
(104, 'Observacoes', 'Observações', 'text', 1),
(105, 'Aprovar', NULL, 'boolean', 1),
(105, 'arquivos', 'Material Extra', 'file', 1),
(105, 'Cliente', 'Nome Cliente', 'text', 1),
(105, 'Descricao', 'Descricao', 'Text', 1),
(105, 'De_Area', NULL, 'area', 1),
(105, 'Etapa', 'Etapa', 'etapa', 1),
(105, 'Finalizado', NULL, 'bool', 1),
(105, 'Nome', NULL, 'text', 1),
(105, 'Observacoes', 'Observações', 'textarea', 1),
(105, 'Para_Area', NULL, 'area', 1),
(105, 'Produto', 'Produto', 'text', 1),
(105, 'responsavel_de', 'Responsável', 'responsavel', 1),
(105, 'responsavel_para', 'Responsável', 'responsavel', 1),
(106, 'Observacoes', 'Observações', 'text', 1),
(107, 'conteudoPost', 'Conteudo', 'textarea', 1),
(107, 'dataPublicacao', 'Data da Publicacao', 'dateTimeNW', 1),
(107, 'editoria', 'Editoria', 'text', 1),
(107, 'Etapa', 'Tarefa', 'etapa', 1),
(107, 'imagemPost', 'Imagem de Capa', 'img', 1),
(107, 'impulsionarPost', 'Impulsionar Post?', 'boolean', 1),
(107, 'Observacoes', 'Observações', 'text', 1),
(107, 'referencias', 'Referencias', 'text', 1),
(107, 'slugPost', 'Slug', 'text', 1),
(107, 'temFacebookPost', 'Publicar no Facebook?', 'boolean', 1),
(107, 'tituloPost', 'Titulo', 'text', 1),
(107, 'verbaPost', 'Verba para Impulsionamento', 'textarea', 1),
(111, 'area', 'Area', 'area', 1),
(111, 'arquivos', 'Arquivos', 'file', 1),
(111, 'cliente', 'Nome Do Cliente', 'text', 1),
(111, 'descricao', 'Descricao', 'editor', 1),
(111, 'Observacoes', 'Observações', 'textarea', 1),
(111, 'tempo', 'Tempo Trabalhado', 'int', 1),
(111, 'titulo', 'Titulo', 'text', 1),
(120, 'assunto', 'Assunto', 'text', 1),
(120, 'briefing', 'Briefing', 'editor', 1),
(120, 'conteudo', 'Conteudo', 'editor', 1),
(120, 'dataPublicacao', 'Data da Publicacao', 'dateTimeNW', 1),
(120, 'enviarInbound', 'Vai ser disparado aqui? ', 'boolean', 1),
(120, 'Etapa', 'Tarefa', 'etapa', 1),
(120, 'gdrive', 'Link da Arte\r\n', 'gdrive', 1),
(120, 'geraHtml', 'Gerar html ?', 'boolean', 1),
(120, 'Observacoes', 'Observações', 'text', 1),
(120, 'referencia', 'referencia', 'arquivo', 1),
(120, 'url', 'Link do HTML\r\n', 'text', 1),
(125, 'AreaTipo', 'Area Tipo', 'area', 1),
(125, 'assunto', 'Opçoes de Assunto', 'text', 1),
(125, 'briefing', 'Briefing', 'editor', 1),
(125, 'conteudo', 'Conteudo', 'editor', 1),
(125, 'dataPublicacao', 'Data da Publicacao', 'dateTimeNW', 1),
(125, 'enviarInbound', 'Vai ser disparado aqui?', 'boolean', 1),
(125, 'Etapa', 'Tarefa', 'etapa', 1),
(125, 'EtapaReprovacao', 'Reprovacao', 'etapa', 1),
(125, 'gdrive', 'Link da Arte', 'gdrive', 1),
(125, 'geraHtml', 'Gerar html ?', 'boolean', 1),
(125, 'Observacoes', 'Observações', 'text', 1),
(125, 'referencia', 'referencia', 'arquivo', 1),
(125, 'Texto', 'Texto Simples', 'text', 1),
(125, 'textoApoio', 'Opçoes de Apoio', 'textarea', 1),
(125, 'url', 'Link do HTML', 'text', 1),
(126, 'briefing', 'Briefing', 'editor', 1),
(126, 'conteudo', 'Conteúdo das Peças', 'editor', 1),
(126, 'dataPublicacao', 'Data da Publicacao', 'dateTimeNW', 1),
(126, 'desdobrarPeca', 'Vai ter desdobramento?', 'boolean', 1),
(126, 'Etapa', 'Tarefa', 'etapa', 1),
(126, 'EtapaReprovacao', 'Reprovacao', 'etapa', 1),
(126, 'gdrive', 'Link do Drive', 'gdrive', 1),
(126, 'midia', 'Plano de Midia', 'text', 1),
(126, 'Observacoes', 'Observações', 'text', 1),
(126, 'referencia', 'Referencias', 'editor', 1),
(126, 'url', 'Formatos / Proporções & URLs', 'editor', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_ElementoTipoSubEtapa`
--

CREATE TABLE `pop_ElementoTipoSubEtapa` (
  `etapa` int(11) NOT NULL,
  `elementoTipo` int(11) NOT NULL,
  `nome` varchar(256) CHARACTER SET utf8 NOT NULL,
  `area` int(11) DEFAULT NULL,
  `proximo` int(11) DEFAULT NULL,
  `anterior` int(11) DEFAULT NULL,
  `responsavel` int(11) DEFAULT NULL,
  `prazo` int(11) DEFAULT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_ElementoTipoSubEtapa`
--

INSERT INTO `pop_ElementoTipoSubEtapa` (`etapa`, `elementoTipo`, `nome`, `area`, `proximo`, `anterior`, `responsavel`, `prazo`, `isUsed`) VALUES
(1, 38, 'Criar Texto', 6, 2, NULL, NULL, NULL, 1),
(2, 38, 'Revisar Texto', 12, 4, 1, NULL, NULL, 1),
(4, 38, 'Criar Imagem', 9, 5, 2, NULL, NULL, 1),
(5, 38, 'Revisar Imagem', 11, 11, 4, NULL, NULL, 1),
(6, 38, 'Programar Publicação ', 6, NULL, 11, NULL, NULL, 1),
(11, 38, 'Revisar Publicação', 12, 12, 4, NULL, NULL, 1),
(12, 38, 'Aprovar Publicação ', 4, 6, 11, NULL, NULL, 1),
(17, 59, '1) Pedido', NULL, 18, NULL, NULL, NULL, 1),
(18, 59, '2) Pedido', NULL, 19, 17, NULL, NULL, 1),
(19, 59, '3) Aprovação Pedido', NULL, 57, 18, NULL, NULL, 1),
(31, 65, 'Pegar Dados de Domínio', 17, 60, NULL, NULL, NULL, 1),
(32, 65, 'Criar Lista de E-mails', 17, 59, 60, NULL, NULL, 1),
(33, 65, 'Instalar Nerdpress Externo', 8, NULL, NULL, NULL, NULL, 1),
(39, 64, 'Criar Virtual Host Interno', 8, NULL, NULL, NULL, NULL, 1),
(40, 64, 'Instalar Nerdpress Interno', 8, NULL, NULL, NULL, NULL, 1),
(41, 67, 'Criar Wireframe', 3, 42, NULL, NULL, NULL, 1),
(42, 67, 'Aprovar Wireframe', 13, 43, 41, NULL, NULL, 1),
(43, 67, 'Criar Home', 3, 44, NULL, NULL, NULL, 1),
(44, 67, 'Aprovar Home', 13, 45, 43, NULL, NULL, 1),
(45, 67, 'Aprovar Home', 17, 46, 43, NULL, NULL, 1),
(46, 67, 'Definir Número de Páginas Internas', 3, NULL, NULL, NULL, NULL, 1),
(47, 72, 'Criar Página ', 3, 48, NULL, NULL, NULL, 1),
(48, 72, 'Aprovar Página', 13, 49, 47, NULL, NULL, 1),
(49, 72, 'Aprovar Página', 17, NULL, 47, NULL, NULL, 1),
(50, 73, 'Criar Lista de Módulos', 3, 51, NULL, NULL, NULL, 1),
(51, 73, 'Aprovar Lista de Módulos', 17, 52, 50, NULL, NULL, 1),
(52, 73, 'Desenvolver Módulos', 2, NULL, 50, NULL, NULL, 1),
(54, 37, 'Definir Categorias e Calendário', 6, 55, NULL, NULL, NULL, 1),
(55, 37, 'Criar Publicações ', 6, NULL, 54, NULL, NULL, 1),
(57, 59, '4) Aprovação Pedido', NULL, NULL, 17, NULL, NULL, 1),
(58, 65, 'Criar Virtual Host ( Todos ) ', 8, NULL, NULL, NULL, NULL, 1),
(59, 65, 'Criar Contas de E-mail', 8, 58, 32, NULL, NULL, 1),
(60, 65, 'Criar Entrada Route53', 8, 32, 31, NULL, NULL, 1),
(61, 81, 'Alterar Paginas', 3, 62, NULL, NULL, NULL, 1),
(62, 81, 'Aprovar Paginas', 3, NULL, 61, NULL, NULL, 1),
(67, 82, 'Pedido Reprovado Pela Área Destino, reveja os dados incluídos para reiniciar o fluxo.', NULL, 68, NULL, NULL, NULL, 1),
(68, 82, 'Pedido Em Análise Pela Área Destino ou Em Execução', NULL, 69, 67, NULL, NULL, 1),
(69, 82, 'Esperando aprovação do gestor da área', NULL, 70, 68, NULL, NULL, 1),
(70, 82, 'Pedido Concluído, aguardando aprovação de quem fez o pedido', NULL, NULL, 68, NULL, NULL, 1),
(72, 89, 'Aprovar Conceito', 11, 75, 73, NULL, NULL, 1),
(73, 89, 'Alterar Conceito', 9, 74, NULL, NULL, NULL, 1),
(74, 89, 'Aprovar Alteração de Conceito', 11, 75, 74, NULL, NULL, 1),
(75, 89, 'Aprovar Conceito', 10, 76, 77, NULL, NULL, 1),
(76, 89, 'Aprovar Conceito com Cliente', 4, 124, 77, NULL, NULL, 1),
(77, 89, 'Alterar Conceito', 9, 78, NULL, NULL, NULL, 1),
(78, 89, 'Aprovar Alteração do Conceito ', 11, 79, 77, NULL, NULL, 1),
(79, 89, 'Aprova Alteração do Conceito', 10, 76, 77, NULL, NULL, 1),
(80, 90, 'Desdobrar Conceito', 9, 81, NULL, NULL, NULL, 1),
(81, 90, 'Aprovar Desdobramento do Conceito', 11, 82, 83, NULL, NULL, 1),
(82, 90, 'Aprovar Desdobramento do Conceito', 10, NULL, 83, NULL, NULL, 1),
(83, 90, 'Alterar Desdobramento do Conceito', 9, 84, NULL, NULL, NULL, 1),
(84, 90, 'Aprovar Alteração do Desdobramento', 11, 82, 83, NULL, NULL, 1),
(87, 95, 'Aprovar Template', 13, 88, 89, NULL, NULL, 1),
(88, 95, 'Aprovar Template', 10, 91, 92, NULL, NULL, 1),
(89, 95, 'Alterar Template', 3, 90, NULL, NULL, NULL, 1),
(90, 95, 'Aprovar Alteração do Template', 13, 88, 89, NULL, NULL, 1),
(91, 95, 'Aprovar Template com Cliente', 4, 122, 92, NULL, NULL, 1),
(92, 95, 'Alterar Template', 3, 93, NULL, NULL, NULL, 1),
(93, 95, 'Aprovar Alteração do Template ', 13, 94, 93, NULL, NULL, 1),
(94, 95, 'Aprova Alteração do Template', 10, 91, 92, NULL, NULL, 1),
(96, 96, 'Aprovar Template', 7, 99, 97, NULL, NULL, 1),
(97, 96, 'Alterar Template', 7, 98, NULL, NULL, NULL, 1),
(98, 96, 'Aprovar Alteração do Template', 7, 99, 97, NULL, NULL, 1),
(99, 96, 'Aprovar Template', 10, NULL, 97, NULL, NULL, 1),
(113, 88, 'Cadastrar Briefing', 10, 114, NULL, NULL, NULL, 1),
(114, 88, 'Validar Briefing', 15, 123, 115, NULL, NULL, 1),
(115, 88, 'Alterar Briefing', 10, 116, NULL, NULL, NULL, 1),
(116, 88, 'Validar Briefing Alterado', 15, 123, 115, NULL, NULL, 1),
(117, 94, 'Cadastrar Briefing', 4, 121, NULL, NULL, NULL, 1),
(118, 94, 'Validar Briefing', 15, 121, 119, NULL, NULL, 1),
(119, 94, 'Alterar Briefing', 4, 121, NULL, NULL, NULL, 1),
(120, 94, 'Validar Briefing Alterado', 15, 121, 119, NULL, NULL, 1),
(121, 94, 'Criar Template', 3, NULL, 119, NULL, NULL, 1),
(122, 95, 'Montar Template', 7, NULL, 92, NULL, NULL, 1),
(123, 88, 'Criar Conceito', 9, NULL, 115, NULL, NULL, 1),
(124, 89, 'Desdobrar Conceito', 9, NULL, 77, NULL, NULL, 1),
(138, 99, 'Cadastrar Briefing', 4, 139, NULL, NULL, NULL, 1),
(139, 99, 'Validar Briefing', 15, 140, 141, NULL, NULL, 1),
(140, 99, 'Criar Conceito', 9, NULL, 141, NULL, NULL, 1),
(141, 99, 'Alterar Briefing', 4, 142, NULL, NULL, NULL, 1),
(142, 99, 'Validar Briefing Alterado', 15, 140, 141, NULL, NULL, 1),
(143, 100, 'Aprovar Conceito', 11, 144, 146, NULL, NULL, 1),
(144, 100, 'Aprovar Conceito Com Cliente', 4, 145, 148, NULL, NULL, 1),
(145, 100, 'Finalizar Material', 9, NULL, 148, NULL, NULL, 1),
(146, 100, 'Alterar Conceito', 9, 147, NULL, NULL, NULL, 1),
(147, 100, 'Aprovar Alteração do Conceito', 11, 144, NULL, NULL, NULL, 1),
(148, 100, 'Alterar Conceito', 9, 149, NULL, NULL, NULL, 1),
(149, 100, 'Aprovar Alteração do Conceito', 11, 150, 148, NULL, NULL, 1),
(150, 100, 'Aprova Alteração do Conceito com Cliente', 4, 145, 148, NULL, NULL, 1),
(151, 101, 'Aprovar Material Finalizado', 11, 152, NULL, NULL, NULL, 1),
(152, 101, 'Aprovar Material Finalizado', 4, NULL, 153, NULL, NULL, 1),
(153, 101, 'Alterar Material Gráfico', 9, 154, NULL, NULL, NULL, 1),
(154, 101, 'Aprovar Alteração do Material', 11, 152, 153, NULL, NULL, 1),
(155, 105, 'Etapa 1: Pedido reprovado pela area destino', NULL, 156, NULL, NULL, NULL, 1),
(156, 105, 'Etapa 2: Pedido recebido na area destino', NULL, 157, 155, NULL, NULL, 1),
(157, 105, 'Etapa 3: Pedido para ser aprovado pelo gestor', NULL, 158, 156, NULL, NULL, 1),
(158, 105, 'Etapa 4: Pedido pra ser aprovado por quem pediu', NULL, NULL, 156, NULL, NULL, 1),
(159, 107, 'Aprovar Pautas com Cliente', 4, 162, 160, NULL, NULL, 1),
(160, 107, 'Alterar Pautas', 6, 161, NULL, NULL, NULL, 1),
(161, 107, 'Aprovar Pautas Alteradas com Cliente', 4, 162, 160, NULL, NULL, 1),
(162, 107, 'Produzir Texto', 6, 164, NULL, NULL, NULL, 1),
(163, 107, 'Revisar Texto', 12, 164, 165, NULL, NULL, 1),
(164, 107, 'Aprovar Texto com cliente', 4, 169, 165, NULL, NULL, 1),
(165, 107, 'Alterar Texto', 6, 167, NULL, NULL, NULL, 1),
(166, 107, 'Revisar Texto Alterado', 12, 167, 165, NULL, NULL, 1),
(167, 107, 'Aprovar Texto Alterado com cliente', 4, 169, 165, NULL, NULL, 1),
(168, 107, 'Definir data de Publicacao', 6, 169, NULL, NULL, NULL, 1),
(169, 107, 'Publicar Texto', 6, 171, NULL, NULL, NULL, 1),
(170, 107, 'Revisar publicacao no Blog', 12, 171, NULL, NULL, NULL, 1),
(171, 107, 'Decisao 1, Facebook/impulsionamento', 22, NULL, NULL, NULL, NULL, 1),
(172, 107, 'Publicar no Facebook', 6, 173, NULL, NULL, NULL, 1),
(173, 107, 'Decisao 2, Impulsionamento', 22, NULL, NULL, NULL, NULL, 1),
(174, 107, 'Impulsionar Post do Blog', 10, NULL, NULL, NULL, NULL, 1),
(175, 107, 'Revisar Impulsionamento do Post', 10, NULL, 174, NULL, NULL, 1),
(200, 120, 'Cadastro do Briefing', 4, 201, NULL, NULL, NULL, 1),
(201, 120, 'Criar Conteúdo do E-mail Marketing', 6, 202, NULL, NULL, NULL, 1),
(202, 120, 'Aprovar Conteúdo do E-mail Marketing', 12, 205, 203, NULL, NULL, 1),
(203, 120, 'Alterar Conteúdo do E-mail Marketing', 6, 204, NULL, NULL, NULL, 1),
(204, 120, 'Revisar Conteúdo Alterado do E-mail Marketing', 12, 205, NULL, NULL, NULL, 1),
(205, 120, 'Aprovar Conteúdo E-mail Marketing', 4, 206, 203, NULL, NULL, 1),
(206, 120, 'Decisao 1, Qual Area ?', 22, NULL, NULL, NULL, NULL, 1),
(207, 120, 'Criar Arte do e-mail', 9, 208, NULL, NULL, NULL, 1),
(208, 120, 'Revisar Arte do e-mail', 11, 211, 209, NULL, NULL, 1),
(209, 120, 'Alterar Arte do e-mail', 9, 210, NULL, NULL, NULL, 1),
(210, 120, 'Revisar Alteração da Arte do e-mail', 11, 211, 209, NULL, NULL, 1),
(211, 120, 'Aprovar Arte do e-mail', 4, 217, 209, NULL, NULL, 1),
(212, 120, 'Criar Arte do e-mail', 3, 213, NULL, NULL, NULL, 1),
(213, 120, 'Revisar Arte do e-mail', 13, 215, 214, NULL, NULL, 1),
(214, 120, 'Alterar Arte do e-mail', 3, 215, NULL, NULL, NULL, 1),
(215, 120, 'Revisar Alteração da Arte do e-mail', 13, 216, 214, NULL, NULL, 1),
(216, 120, 'Aprovar Arte do e-mail', 4, 217, 208, NULL, NULL, 1),
(217, 120, 'Decisao 2, Gera HTML ?', 22, NULL, NULL, NULL, NULL, 1),
(218, 120, 'Montar HTML do e-mail marketing', 7, 219, NULL, NULL, NULL, 1),
(219, 120, 'Entregar HTML do e-mail marketing', 4, 222, 220, NULL, NULL, 1),
(220, 120, 'Alterar HTML do e-mail marketing', 7, 221, NULL, NULL, NULL, 1),
(221, 120, 'Entregar HTML do e-mail marketing Alterado', 4, 222, 220, NULL, NULL, 1),
(222, 120, 'Decisao 3, Enviar pra inbound ?', 22, NULL, NULL, NULL, NULL, 1),
(223, 120, 'Publicar / Disparar e-mail marketing', 25, 224, NULL, NULL, NULL, 1),
(224, 120, 'Email Programado', 4, NULL, NULL, NULL, NULL, 1),
(300, 126, 'Solicitação de Peças de Mídia', 4, 301, NULL, NULL, NULL, 1),
(301, 126, 'Cadastro do Briefing ', 10, 303, 300, NULL, NULL, 1),
(302, 126, 'Alterar Briefing ', 10, 303, 300, NULL, NULL, 1),
(303, 126, 'Criar Conteúdo das Peças de Mídia', 6, 304, 302, NULL, NULL, 1),
(304, 126, 'Revisar Conteúdo das Peças de Mídia', 12, 325, 305, NULL, NULL, 1),
(305, 126, 'Alterar Conteúdo das Peças de Mídia', 6, 306, NULL, NULL, NULL, 1),
(306, 126, 'Aprovar Conteúdo Alterado das Peças de Mídia', 12, 325, 305, NULL, NULL, 1),
(307, 126, 'Aprovar Conteúdo das Peças de Mídia', 4, 308, 305, NULL, NULL, 1),
(308, 126, 'Criar Peça Conceito', 9, 309, 305, NULL, NULL, 1),
(309, 126, 'Aprovar Peça Conceito', 11, 312, 310, NULL, NULL, 1),
(310, 126, 'Alterar Peça Conceito', 9, 311, NULL, NULL, NULL, 1),
(311, 126, 'Aprovar Peça Conceito Alterada', 11, 312, 310, NULL, NULL, 1),
(312, 126, 'Aprovar Peças Conceito', 12, 313, 310, NULL, NULL, 1),
(313, 126, 'Aprovar Peças Conceito', 10, 314, 310, NULL, NULL, 1),
(314, 126, 'Aprovar Peças Conceito', 4, 316, 315, NULL, NULL, 1),
(315, 126, 'Decisao 1, Reprovacao Condicional 6 / 14 ', 22, NULL, NULL, NULL, NULL, 1),
(316, 126, 'Decisao 2 , Desdobrar?', 22, NULL, NULL, NULL, NULL, 1),
(317, 126, 'Desdobrar Peças', 9, 318, 326, NULL, NULL, 1),
(318, 126, 'Revisar Peças', 11, 321, 319, NULL, NULL, 1),
(319, 126, 'Alterar Peças', 9, 320, NULL, NULL, NULL, 1),
(320, 126, 'Revisar Peças Alteradas', 11, 321, 319, NULL, NULL, 1),
(321, 126, 'Aprovar Pecas ', 12, 322, 319, NULL, NULL, 1),
(322, 126, 'Aprovar Peças de Mídia', 4, 323, 319, NULL, NULL, 1),
(323, 126, 'Aprovar & Publicar Campanha', 10, 324, 319, NULL, NULL, 1),
(324, 126, 'Campanha Programada', 4, NULL, 323, NULL, NULL, 1),
(325, 126, 'Revisar Conteúdo', 10, 307, 305, NULL, NULL, 1),
(326, 126, 'Completar Informações para Desdobramento', 4, 317, NULL, NULL, NULL, 1),
(349, 125, 'Cadastro do Briefing', 4, 351, NULL, NULL, NULL, 1),
(350, 125, 'Alterar Briefing', 4, 351, NULL, NULL, NULL, 1),
(351, 125, 'Criar Conteúdo do E-mail Marketing', 6, 352, 350, NULL, NULL, 1),
(352, 125, 'Aprovar Conteúdo do E-mail Marketing', 12, 355, 353, NULL, NULL, 1),
(353, 125, 'Alterar Conteúdo do E-mail Marketing', 6, 354, NULL, NULL, NULL, 1),
(354, 125, 'Revisar Conteúdo Alterado do E-mail Marketing', 12, 355, 353, NULL, NULL, 1),
(355, 125, 'Aprovar Conteúdo E-mail Marketing', 4, 356, 353, NULL, NULL, 1),
(356, 125, 'Decisao 1, Qual Area ?', 22, NULL, NULL, NULL, NULL, 1),
(357, 125, 'Criar Arte do Email', 9, 358, 353, NULL, NULL, 1),
(358, 125, 'Aprovar Arte do Email Marketing', 11, 359, 360, NULL, NULL, 1),
(359, 125, 'Validar Arte do Email Marketing', 12, 363, 360, NULL, NULL, 1),
(360, 125, 'Alterar Arte do Email Marketing', 9, 361, NULL, NULL, NULL, 1),
(361, 125, 'Aprovar Arte do Email Marketing Alterado', 11, 362, 360, NULL, NULL, 1),
(362, 125, 'Validar Arte do Email Marketing Alterado', 12, 363, 360, NULL, NULL, 1),
(363, 125, 'Aprovar Email Marketing', 4, 371, 379, NULL, NULL, 1),
(364, 125, 'Criar Arte do Email', 3, 365, 353, NULL, NULL, 1),
(365, 125, 'Aprovar Arte do Email Marketing', 13, 366, 367, NULL, NULL, 1),
(366, 125, 'Validar Arte do Email Marketing', 12, 370, 367, NULL, NULL, 1),
(367, 125, 'Alterar Arte do Email Marketing', 3, 368, NULL, NULL, NULL, 1),
(368, 125, 'Aprovar Arte do Email Marketing Alterado', 13, 369, 367, NULL, NULL, 1),
(369, 125, 'Validar Arte do Email Marketing Alterado', 12, 370, 367, NULL, NULL, 1),
(370, 125, 'Aprovar Email Marketing', 4, 371, 379, NULL, NULL, 1),
(371, 125, 'Decisao 2, Gera HTML ?', 22, NULL, NULL, NULL, NULL, 1),
(372, 125, 'Montar HTML do e-mail marketing', 7, 373, 380, NULL, NULL, 1),
(373, 125, 'Entregar HTML do e-mail marketing', 4, 376, 374, NULL, NULL, 1),
(374, 125, 'Alterar HTML do e-mail marketing', 7, 375, NULL, NULL, NULL, 1),
(375, 125, 'Entregar HTML do e-mail marketing Alterado', 4, 376, 374, NULL, NULL, 1),
(376, 125, 'Decisao 3, Enviar pra inbound ?', 22, NULL, NULL, NULL, NULL, 1),
(377, 125, 'Agendar Email Marketing', 25, 378, 374, NULL, NULL, 1),
(378, 125, 'Email Programado', 4, NULL, 377, NULL, NULL, 1),
(379, 125, 'Decisao 4, Reprovacao Condicional', 22, NULL, NULL, NULL, NULL, 1),
(380, 125, 'Decisao 5, Reprovacao Interface/Criacao', 22, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_Notificacao`
--

CREATE TABLE `pop_Notificacao` (
  `notificacao` int(11) NOT NULL,
  `texto` text DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `icone` varchar(255) DEFAULT NULL,
  `cor` varchar(7) DEFAULT NULL,
  `prazo` date DEFAULT NULL,
  `dataCriacao` datetime DEFAULT NULL,
  `dataLeitura` datetime DEFAULT NULL,
  `projeto` int(11) DEFAULT NULL,
  `elementoTipo` int(11) DEFAULT NULL,
  `subetapa` int(11) DEFAULT NULL,
  `cliente` int(11) DEFAULT NULL,
  `area` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pop_Prioridade`
--

CREATE TABLE `pop_Prioridade` (
  `prioridade` tinyint(4) NOT NULL,
  `nome` varchar(256) CHARACTER SET utf8 NOT NULL,
  `icone` varchar(256) CHARACTER SET utf8 NOT NULL,
  `cor` varchar(10) CHARACTER SET utf8 NOT NULL,
  `cor_linha` varchar(10) CHARACTER SET utf8 NOT NULL,
  `identifier` varchar(256) CHARACTER SET utf8 NOT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_Prioridade`
--

INSERT INTO `pop_Prioridade` (`prioridade`, `nome`, `icone`, `cor`, `cor_linha`, `identifier`, `isUsed`) VALUES
(100, 'Super Alta', '', '#FF0000', '', 'POP_PRIORIDADE_SUPER_ALTA', 0),
(101, 'Alta', '', '#E2445C', '#FFCCCC', 'POP_PRIORIDADE_ALTA', 1),
(102, 'Normal', '', '#FDAB3D', '', 'POP_PRIORIDADE_NORMAL', 1),
(103, 'Baixa', '', '#0086C0', '', 'POP_PRIORIDADE_BAIXA', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_Projeto`
--

CREATE TABLE `pop_Projeto` (
  `projeto` int(11) NOT NULL,
  `nome` varchar(512) CHARACTER SET utf8 NOT NULL,
  `dataEntrada` date DEFAULT NULL,
  `prazo` date DEFAULT NULL,
  `prazoEstimado` date DEFAULT NULL,
  `finalizado` tinyint(1) NOT NULL DEFAULT 0,
  `projetoTipo` int(11) NOT NULL,
  `cliente` int(11) DEFAULT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_Projeto`
--

INSERT INTO `pop_Projeto` (`projeto`, `nome`, `dataEntrada`, `prazo`, `prazoEstimado`, `finalizado`, `projetoTipo`, `cliente`, `isUsed`) VALUES
(13, 'Pedidos', NULL, NULL, NULL, 0, 9, NULL, 1),
(14, 'teste criacao projeto - Outubro - 2020', '2020-06-15', '2020-06-16', '2020-06-30', 0, 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_ProjetoChat`
--

CREATE TABLE `pop_ProjetoChat` (
  `projeto` int(11) NOT NULL,
  `elemento` int(11) DEFAULT NULL,
  `responsavel` int(11) NOT NULL,
  `sistema` tinyint(4) NOT NULL DEFAULT 0,
  `conteudo` text NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp(),
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pop_ProjetoConteudo`
--

CREATE TABLE `pop_ProjetoConteudo` (
  `projeto` int(11) NOT NULL,
  `chave` varchar(128) NOT NULL,
  `valor` blob DEFAULT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_ProjetoConteudo`
--

INSERT INTO `pop_ProjetoConteudo` (`projeto`, `chave`, `valor`, `isUsed`) VALUES
(14, 'mes', 0x31302f32303230, 1),
(14, 'planejamento', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_ProjetoDadosTemporarios`
--

CREATE TABLE `pop_ProjetoDadosTemporarios` (
  `projeto` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `conteudo` text CHARACTER SET utf8 DEFAULT NULL,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pop_ProjetoTipo`
--

CREATE TABLE `pop_ProjetoTipo` (
  `projetoTipo` int(11) NOT NULL,
  `nome` varchar(256) CHARACTER SET utf8 NOT NULL,
  `descricao` text CHARACTER SET utf8 DEFAULT NULL,
  `diasPrazo` int(11) DEFAULT NULL,
  `cor` varchar(7) CHARACTER SET utf8 DEFAULT NULL,
  `icone` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `identifier` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'string que identifica o tipo de projeto\nex:\nPROJETO_FACEBOOK\nPROJETO_WEBSITE',
  `escondido` tinyint(4) NOT NULL DEFAULT 0,
  `isUsed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_ProjetoTipo`
--

INSERT INTO `pop_ProjetoTipo` (`projetoTipo`, `nome`, `descricao`, `diasPrazo`, `cor`, `icone`, `identifier`, `escondido`, `isUsed`) VALUES
(2, 'Redes Sociais ( Campanha Facebook ) ', 'Projeto de criacão de campanhas de facebook e postagens, fluxo de trabalho nerdweb', NULL, '#425F9C', 'fa-facebook', 'POP_PROJETO_CAMPANHA_FACEBOOK', 0, 1),
(9, 'Pedidos', 'Projeto Feito Pra Pendurar pedidos', 0, '#000000', 'fa-certificate', 'POP_PROJETO_PEDIDOS', 1, 1),
(10, 'Website', 'Projeto de criação de websites, fluxo de trabalho Nerdweb', 0, '#8653CD', 'fa-desktop', 'POP_PROJETO_CRIACAO_DE_WEBSITES', 0, 1),
(11, 'Campanha Display', '', 0, '#FD8340', 'fa-picture-o', 'POP_PROJETO_FLUXO_DE_MEDIA', 0, 1),
(12, 'Email Marketing', '', 0, '#FD8340', 'fa-envelope-o', 'POP_PROJETO_EMAIL_MARKETING', 1, 1),
(13, 'Material Gráfico', '', 0, '#CD7CB1', 'fa-file-image-o', 'POP_PROJETO_MATERIAL_GRAFICO', 1, 1),
(14, 'Producao Blog', 'Producao e revisao de textos para blogs e outras medias', 1, '#36d209', 'fa-file-word', 'POP_PROJETO_PRODUCAO_BLOG', 0, 1),
(16, 'E-mail via Criação', 'Producao de email Marketing', 1, '#FD8340', 'fa-envelope-o', 'POP_PROJETO_EMAIL_MARKETING_V2', 1, 1),
(20, 'Apontamento De Horas', 'Projeto de uso interno para apontamento de horas trabalhada', 1, '#CD7CB1', 'fa-user', 'POP_PROJETO_HORAS', 1, 1),
(21, 'E-mail via Criação', 'Producao de email Marketing', 1, '#FD8340', 'fa-envelope-o', 'POP_PROJETO_EMAIL_MARKETING_V3', 0, 1),
(23, 'E-mail via Interface', 'Produção de e-mail Marketing', 1, '#A25DDC', 'fa-envelope-o', '_POP_PROJETO_EMAIL_MARKETING_V3', 0, 1),
(24, 'Pecas de Midia - V3', 'Producao de peças de midia', 1, '#CD7CB1', 'fa-picture-o', 'POP_PROJETO_FLUXO_DE_MIDIA_V3', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_ProjetoTipoNomeTipo`
--

CREATE TABLE `pop_ProjetoTipoNomeTipo` (
  `projetoTipo` int(11) NOT NULL,
  `nome` varchar(128) CHARACTER SET utf8 NOT NULL,
  `nomeExibicao` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `tipo` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT 'text',
  `isUsed` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pop_ProjetoTipoNomeTipo`
--

INSERT INTO `pop_ProjetoTipoNomeTipo` (`projetoTipo`, `nome`, `nomeExibicao`, `tipo`, `isUsed`) VALUES
(2, 'mes', 'Mês', 'date_monthyear', 1),
(2, 'planejamento', 'Planejamento', 'json', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions_v2`
--

CREATE TABLE `sessions_v2` (
  `id` varchar(32) NOT NULL,
  `access` int(10) UNSIGNED DEFAULT NULL,
  `data` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `session_v2`
--

CREATE TABLE `session_v2` (
  `id` varchar(32) NOT NULL,
  `access` int(10) UNSIGNED DEFAULT NULL,
  `data` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `session_v2`
--

INSERT INTO `session_v2` (`id`, `access`, `data`) VALUES
('jo7s0igm4dfv78e9c2lpjivjsd', 1592257968, 'hashedValues|s:64:\"d4bb3dc422d08459e8a92dd52cb80431d2fd518ed755e8c4bf3fb20c07f15f5a\";adm_usuario|s:1:\"6\";adm_nome|s:14:\"Rafael Rotelok\";adm_email|s:22:\"rotelok@nerdweb.com.br\";adm_imagem|s:47:\"/backoffice/uploads/usuarios/rafael-rotelok.jpg\";adm_is_admin|s:1:\"1\";adm_ip|s:9:\"10.0.10.2\";NUM_TAREFAS|i:0;'),
('laarcead836hbi86etgtil1mnt', 1592257875, ''),
('v2c51g5ica0nn3mclcfp79r22m', 1592257875, 'redirect_url|s:38:\"/backoffice/pop/pop-minhas-tarefas.php\";');

-- --------------------------------------------------------

--
-- Stand-in structure for view `ViewElementoComplexo`
-- (See below for the actual view)
--
CREATE TABLE `ViewElementoComplexo` (
`elemento` int(11)
,`dataCriacao` datetime
,`dataAtualizacao` timestamp
,`elementoTipo` int(11)
,`elementoStatus` int(11)
,`projeto` int(11)
,`projetoTipo` int(11)
,`chave` varchar(128)
,`valor` text
,`isUsed` tinyint(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `ViewProjetoAnterior`
-- (See below for the actual view)
--
CREATE TABLE `ViewProjetoAnterior` (
`idProjetoTipo` int(11)
,`projetoTipoNome` varchar(256)
,`idPai` int(11)
,`nomePai` varchar(256)
,`idFilho` int(11)
,`nomeFilho` varchar(256)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `ViewProjetoPrimeiros`
-- (See below for the actual view)
--
CREATE TABLE `ViewProjetoPrimeiros` (
`idTipoProjeto` int(11)
,`projetoTipoNome` varchar(256)
,`idTipoElemento` int(11)
,`nomeTipoElemento` varchar(256)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `ViewProjetoProximos`
-- (See below for the actual view)
--
CREATE TABLE `ViewProjetoProximos` (
`idProjetoTipo` int(11)
,`projetoTipoNome` varchar(256)
,`idPai` int(11)
,`nomePai` varchar(256)
,`idFilho` int(11)
,`nomeFilho` varchar(256)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `ViewUsuarioArea`
-- (See below for the actual view)
--
CREATE TABLE `ViewUsuarioArea` (
`idArea` int(11)
,`ligacao` int(11)
,`nomeArea` varchar(50)
,`idUsuario` int(11)
,`nomeUsuario` varchar(80)
);

-- --------------------------------------------------------

--
-- Structure for view `ViewElementoComplexo`
--
DROP TABLE IF EXISTS `ViewElementoComplexo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ViewElementoComplexo`  AS  select `p`.`elemento` AS `elemento`,`p`.`dataCriacao` AS `dataCriacao`,`p`.`dataAtualizacao` AS `dataAtualizacao`,`p`.`elementoTipo` AS `elementoTipo`,`p`.`elementoStatus` AS `elementoStatus`,`p`.`projeto` AS `projeto`,`pr`.`projetoTipo` AS `projetoTipo`,`c`.`chave` AS `chave`,`c`.`valor` AS `valor`,`p`.`isUsed` AS `isUsed` from ((`pop_Elemento` `p` join `pop_ElementoConteudo` `c` on(`p`.`elemento` = `c`.`elemento` and `c`.`isUsed` = 1)) join `pop_Projeto` `pr` on(`p`.`projeto` = `pr`.`projeto` and `pr`.`isUsed` = 1)) where `p`.`isUsed` = 1 and (`c`.`chave` = 'area' or `c`.`chave` = 'Etapa' or `c`.`chave` = 'Responsavel') order by `p`.`elemento` ;

-- --------------------------------------------------------

--
-- Structure for view `ViewProjetoAnterior`
--
DROP TABLE IF EXISTS `ViewProjetoAnterior`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ViewProjetoAnterior`  AS  select `p`.`projetoTipo` AS `idProjetoTipo`,`p`.`nome` AS `projetoTipoNome`,`et`.`elementoTipo` AS `idPai`,`et`.`nome` AS `nomePai`,`et2`.`elementoTipo` AS `idFilho`,`et2`.`nome` AS `nomeFilho` from (((`pop_ProjetoTipo` `p` join `pop_ElementoAnterior` `e`) join `pop_ElementoTipo` `et`) join `pop_ElementoTipo` `et2`) where `p`.`projetoTipo` = `e`.`projetoTipo` and `e`.`elementoTipo` = `et`.`elementoTipo` and `e`.`anterior` = `et2`.`elementoTipo` ;

-- --------------------------------------------------------

--
-- Structure for view `ViewProjetoPrimeiros`
--
DROP TABLE IF EXISTS `ViewProjetoPrimeiros`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ViewProjetoPrimeiros`  AS  select `p`.`projetoTipo` AS `idTipoProjeto`,`p`.`nome` AS `projetoTipoNome`,`et`.`elementoTipo` AS `idTipoElemento`,`et`.`nome` AS `nomeTipoElemento` from ((`pop_ProjetoTipo` `p` join `pop_ElementoPrimeiro` `e`) join `pop_ElementoTipo` `et`) where `p`.`projetoTipo` = `e`.`projetoTipo` and `e`.`elementoTipo` = `et`.`elementoTipo` ;

-- --------------------------------------------------------

--
-- Structure for view `ViewProjetoProximos`
--
DROP TABLE IF EXISTS `ViewProjetoProximos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ViewProjetoProximos`  AS  select `p`.`projetoTipo` AS `idProjetoTipo`,`p`.`nome` AS `projetoTipoNome`,`et`.`elementoTipo` AS `idPai`,`et`.`nome` AS `nomePai`,`et2`.`elementoTipo` AS `idFilho`,`et2`.`nome` AS `nomeFilho` from (((`pop_ProjetoTipo` `p` join `pop_ElementoProximo` `e`) join `pop_ElementoTipo` `et`) join `pop_ElementoTipo` `et2`) where `p`.`projetoTipo` = `e`.`projetoTipo` and `e`.`elementoTipo` = `et`.`elementoTipo` and `e`.`proximo` = `et2`.`elementoTipo` ;

-- --------------------------------------------------------

--
-- Structure for view `ViewUsuarioArea`
--
DROP TABLE IF EXISTS `ViewUsuarioArea`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ViewUsuarioArea`  AS  select `admArea`.`area` AS `idArea`,`admAreaUsuario`.`id` AS `ligacao`,`admArea`.`nome` AS `nomeArea`,`admUsuario`.`usuarioNerdweb` AS `idUsuario`,`admUsuario`.`nome` AS `nomeUsuario` from ((`back_AdmArea` `admArea` join `back_AdmAreaUsuario` `admAreaUsuario`) join `back_AdmUsuario` `admUsuario`) where `admArea`.`area` = `admAreaUsuario`.`area` and `admAreaUsuario`.`usuarioNerdweb` = `admUsuario`.`usuarioNerdweb` and `admArea`.`isUsed` = 1 and `admAreaUsuario`.`isUsed` = 1 and `admUsuario`.`isUsed` = 1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `back_AdmArea`
--
ALTER TABLE `back_AdmArea`
  ADD PRIMARY KEY (`area`);

--
-- Indexes for table `back_AdmAreaUsuario`
--
ALTER TABLE `back_AdmAreaUsuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_back_AdmArea_has_back_AdmUsuario_back_AdmUsuario1_idx` (`usuarioNerdweb`),
  ADD KEY `fk_back_AdmArea_has_back_AdmUsuario_back_AdmArea1_idx` (`area`);

--
-- Indexes for table `back_AdmGrupo`
--
ALTER TABLE `back_AdmGrupo`
  ADD PRIMARY KEY (`grupo`);

--
-- Indexes for table `back_AdmGrupoUsuario`
--
ALTER TABLE `back_AdmGrupoUsuario`
  ADD PRIMARY KEY (`grupoUsuario`),
  ADD KEY `fk_admUser_has_admGrupo_admGrupo1_idx` (`grupo`),
  ADD KEY `fk_admUser_has_admGrupo_admUser1_idx` (`usuarioNerdweb`);

--
-- Indexes for table `back_AdmPermissao`
--
ALTER TABLE `back_AdmPermissao`
  ADD PRIMARY KEY (`permissao`);

--
-- Indexes for table `back_AdmPermissaoGrupo`
--
ALTER TABLE `back_AdmPermissaoGrupo`
  ADD PRIMARY KEY (`permissaoGrupo`),
  ADD KEY `fk_admPermissao_has_admGrupo_admGrupo1_idx` (`grupo`),
  ADD KEY `fk_admPermissao_has_admGrupo_admPermissao_idx` (`permissao`);

--
-- Indexes for table `back_AdmUsuario`
--
ALTER TABLE `back_AdmUsuario`
  ADD PRIMARY KEY (`usuarioNerdweb`);

--
-- Indexes for table `back_Cliente`
--
ALTER TABLE `back_Cliente`
  ADD PRIMARY KEY (`cliente`);

--
-- Indexes for table `back_Servico`
--
ALTER TABLE `back_Servico`
  ADD PRIMARY KEY (`servico`);

--
-- Indexes for table `back_ServicoCliente`
--
ALTER TABLE `back_ServicoCliente`
  ADD PRIMARY KEY (`servicoCliente`),
  ADD KEY `fk_back_Cliente_has_back_Servico_back_Servico1_idx` (`servico`),
  ADD KEY `fk_back_Cliente_has_back_Servico_back_Cliente1_idx` (`cliente`);

--
-- Indexes for table `pop_AdmAreaXelementoTipo`
--
ALTER TABLE `pop_AdmAreaXelementoTipo`
  ADD UNIQUE KEY `elementoTipo` (`elementoTipo`),
  ADD KEY `area` (`area`);

--
-- Indexes for table `pop_Alerta`
--
ALTER TABLE `pop_Alerta`
  ADD PRIMARY KEY (`alerta`),
  ADD KEY `fk_projeto` (`projeto`),
  ADD KEY `fk_elemento` (`elemento`);

--
-- Indexes for table `pop_ColunasBase`
--
ALTER TABLE `pop_ColunasBase`
  ADD PRIMARY KEY (`colunaBase`);

--
-- Indexes for table `pop_ColunasBaseNomeTipo`
--
ALTER TABLE `pop_ColunasBaseNomeTipo`
  ADD UNIQUE KEY `colunaBase_2` (`colunaBase`,`nome`),
  ADD KEY `colunaBase` (`colunaBase`);

--
-- Indexes for table `pop_ColunasBaseXelementoTipo`
--
ALTER TABLE `pop_ColunasBaseXelementoTipo`
  ADD UNIQUE KEY `elementoTipo_2` (`elementoTipo`,`colunasBase`),
  ADD KEY `elementoTipo` (`elementoTipo`),
  ADD KEY `elementoBase` (`colunasBase`);

--
-- Indexes for table `pop_Elemento`
--
ALTER TABLE `pop_Elemento`
  ADD PRIMARY KEY (`elemento`),
  ADD KEY `fk_pop_Elemento_pop_Projeto1_idx` (`projeto`),
  ADD KEY `fk_pop_Elemento_pop_ElementoStatus1_idx` (`elementoStatus`),
  ADD KEY `fk_pop_Elemento_pop_ElementoTipo1_idx` (`elementoTipo`);

--
-- Indexes for table `pop_ElementoAnterior`
--
ALTER TABLE `pop_ElementoAnterior`
  ADD UNIQUE KEY `projetoTipo` (`projetoTipo`,`elementoTipo`,`anterior`),
  ADD KEY `fk_pop_ElementoAnterior_pop_ProjetoTipo1_idx` (`projetoTipo`),
  ADD KEY `fk_pop_ElementoAnterior_pop_ElementoTipo1_idx` (`elementoTipo`),
  ADD KEY `fk_pop_ElementoAnterior_pop_ElementoTipo2_idx` (`anterior`);

--
-- Indexes for table `pop_ElementoCategoria`
--
ALTER TABLE `pop_ElementoCategoria`
  ADD PRIMARY KEY (`categoria`);

--
-- Indexes for table `pop_ElementoChat`
--
ALTER TABLE `pop_ElementoChat`
  ADD KEY `elemento` (`elemento`),
  ADD KEY `responsavel` (`responsavel`);

--
-- Indexes for table `pop_ElementoConteudo`
--
ALTER TABLE `pop_ElementoConteudo`
  ADD UNIQUE KEY `elemento` (`elemento`,`chave`),
  ADD KEY `fk_pop_ElementoConteudo_pop_Elemento1_idx` (`elemento`),
  ADD KEY `chave` (`chave`),
  ADD KEY `elemento_2` (`elemento`);

--
-- Indexes for table `pop_ElementoConteudoJSON`
--
ALTER TABLE `pop_ElementoConteudoJSON`
  ADD UNIQUE KEY `elemento` (`elemento`);

--
-- Indexes for table `pop_ElementoHistorico`
--
ALTER TABLE `pop_ElementoHistorico`
  ADD UNIQUE KEY `usuario_2` (`usuario`,`elemento`,`acao`,`data`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `elemento` (`elemento`),
  ADD KEY `acao` (`acao`);

--
-- Indexes for table `pop_ElementoHistoricoAcao`
--
ALTER TABLE `pop_ElementoHistoricoAcao`
  ADD PRIMARY KEY (`acao`);

--
-- Indexes for table `pop_ElementoJSON`
--
ALTER TABLE `pop_ElementoJSON`
  ADD PRIMARY KEY (`elemento`),
  ADD KEY `fk_pop_Elemento_pop_Projeto1_idx` (`projeto`),
  ADD KEY `fk_pop_Elemento_pop_ElementoStatus1_idx` (`elementoStatus`),
  ADD KEY `fk_pop_Elemento_pop_ElementoTipo1_idx` (`elementoTipo`);

--
-- Indexes for table `pop_ElementoPrimeiro`
--
ALTER TABLE `pop_ElementoPrimeiro`
  ADD UNIQUE KEY `projetoTipo` (`projetoTipo`,`elementoTipo`),
  ADD KEY `fk_pop_ElementoPrimeiro_pop_ProjetoTipo1_idx` (`projetoTipo`),
  ADD KEY `fk_pop_ElementoPrimeiro_pop_ElementoTipo1_idx` (`elementoTipo`);

--
-- Indexes for table `pop_ElementoProximo`
--
ALTER TABLE `pop_ElementoProximo`
  ADD UNIQUE KEY `projetoTipo` (`projetoTipo`,`elementoTipo`,`proximo`),
  ADD KEY `fk_pop_ElementoProximo_pop_ProjetoTipo1_idx` (`projetoTipo`),
  ADD KEY `fk_pop_ElementoProximo_pop_ElementoTipo1_idx` (`elementoTipo`),
  ADD KEY `fk_pop_ElementoProximo_pop_ElementoTipo2_idx` (`proximo`);

--
-- Indexes for table `pop_ElementoStatus`
--
ALTER TABLE `pop_ElementoStatus`
  ADD PRIMARY KEY (`elementoStatus`),
  ADD UNIQUE KEY `identifier_UNIQUE` (`identifier`);

--
-- Indexes for table `pop_ElementoTipo`
--
ALTER TABLE `pop_ElementoTipo`
  ADD PRIMARY KEY (`elementoTipo`);

--
-- Indexes for table `pop_ElementoTipoNomeTipo`
--
ALTER TABLE `pop_ElementoTipoNomeTipo`
  ADD UNIQUE KEY `elementoTipo` (`elementoTipo`,`nome`),
  ADD KEY `id` (`elementoTipo`);

--
-- Indexes for table `pop_ElementoTipoSubEtapa`
--
ALTER TABLE `pop_ElementoTipoSubEtapa`
  ADD UNIQUE KEY `etapa` (`etapa`),
  ADD KEY `elementoTipo` (`elementoTipo`),
  ADD KEY `area` (`area`),
  ADD KEY `responsavel` (`responsavel`),
  ADD KEY `etapa_2` (`etapa`,`elementoTipo`),
  ADD KEY `proximo` (`proximo`),
  ADD KEY `anterior` (`anterior`);

--
-- Indexes for table `pop_Notificacao`
--
ALTER TABLE `pop_Notificacao`
  ADD PRIMARY KEY (`notificacao`),
  ADD KEY `fk_usuario` (`usuario`),
  ADD KEY `fk_area` (`area`) USING BTREE,
  ADD KEY `fk_cliente` (`cliente`);

--
-- Indexes for table `pop_Prioridade`
--
ALTER TABLE `pop_Prioridade`
  ADD PRIMARY KEY (`prioridade`);

--
-- Indexes for table `pop_Projeto`
--
ALTER TABLE `pop_Projeto`
  ADD PRIMARY KEY (`projeto`),
  ADD KEY `fk_pop_Projeto_pop_ProjetoTipo1_idx` (`projetoTipo`),
  ADD KEY `cliente` (`cliente`);

--
-- Indexes for table `pop_ProjetoChat`
--
ALTER TABLE `pop_ProjetoChat`
  ADD KEY `elemento` (`projeto`),
  ADD KEY `responsavel` (`responsavel`),
  ADD KEY `data` (`data`),
  ADD KEY `fk_elemento` (`elemento`);

--
-- Indexes for table `pop_ProjetoConteudo`
--
ALTER TABLE `pop_ProjetoConteudo`
  ADD UNIQUE KEY `projeto` (`projeto`,`chave`),
  ADD KEY `fk_pop_ProjetoConteudo_pop_Projeto_idx` (`projeto`);

--
-- Indexes for table `pop_ProjetoDadosTemporarios`
--
ALTER TABLE `pop_ProjetoDadosTemporarios`
  ADD UNIQUE KEY `projeto` (`projeto`,`user`);

--
-- Indexes for table `pop_ProjetoTipo`
--
ALTER TABLE `pop_ProjetoTipo`
  ADD PRIMARY KEY (`projetoTipo`),
  ADD UNIQUE KEY `identifier_UNIQUE` (`identifier`);

--
-- Indexes for table `pop_ProjetoTipoNomeTipo`
--
ALTER TABLE `pop_ProjetoTipoNomeTipo`
  ADD UNIQUE KEY `projetoTipo` (`projetoTipo`,`nome`),
  ADD KEY `id` (`projetoTipo`);

--
-- Indexes for table `sessions_v2`
--
ALTER TABLE `sessions_v2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `session_v2`
--
ALTER TABLE `session_v2`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `back_AdmArea`
--
ALTER TABLE `back_AdmArea`
  MODIFY `area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `back_AdmAreaUsuario`
--
ALTER TABLE `back_AdmAreaUsuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1387;

--
-- AUTO_INCREMENT for table `back_AdmGrupo`
--
ALTER TABLE `back_AdmGrupo`
  MODIFY `grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `back_AdmGrupoUsuario`
--
ALTER TABLE `back_AdmGrupoUsuario`
  MODIFY `grupoUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `back_AdmPermissao`
--
ALTER TABLE `back_AdmPermissao`
  MODIFY `permissao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `back_AdmPermissaoGrupo`
--
ALTER TABLE `back_AdmPermissaoGrupo`
  MODIFY `permissaoGrupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `back_AdmUsuario`
--
ALTER TABLE `back_AdmUsuario`
  MODIFY `usuarioNerdweb` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `back_Cliente`
--
ALTER TABLE `back_Cliente`
  MODIFY `cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=290;

--
-- AUTO_INCREMENT for table `back_Servico`
--
ALTER TABLE `back_Servico`
  MODIFY `servico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `back_ServicoCliente`
--
ALTER TABLE `back_ServicoCliente`
  MODIFY `servicoCliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pop_Alerta`
--
ALTER TABLE `pop_Alerta`
  MODIFY `alerta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pop_ColunasBase`
--
ALTER TABLE `pop_ColunasBase`
  MODIFY `colunaBase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pop_Elemento`
--
ALTER TABLE `pop_Elemento`
  MODIFY `elemento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pop_ElementoCategoria`
--
ALTER TABLE `pop_ElementoCategoria`
  MODIFY `categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `pop_ElementoHistoricoAcao`
--
ALTER TABLE `pop_ElementoHistoricoAcao`
  MODIFY `acao` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pop_ElementoJSON`
--
ALTER TABLE `pop_ElementoJSON`
  MODIFY `elemento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pop_ElementoStatus`
--
ALTER TABLE `pop_ElementoStatus`
  MODIFY `elementoStatus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pop_ElementoTipo`
--
ALTER TABLE `pop_ElementoTipo`
  MODIFY `elementoTipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `pop_ElementoTipoSubEtapa`
--
ALTER TABLE `pop_ElementoTipoSubEtapa`
  MODIFY `etapa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=381;

--
-- AUTO_INCREMENT for table `pop_Notificacao`
--
ALTER TABLE `pop_Notificacao`
  MODIFY `notificacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pop_Prioridade`
--
ALTER TABLE `pop_Prioridade`
  MODIFY `prioridade` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `pop_Projeto`
--
ALTER TABLE `pop_Projeto`
  MODIFY `projeto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `pop_ProjetoTipo`
--
ALTER TABLE `pop_ProjetoTipo`
  MODIFY `projetoTipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `back_AdmAreaUsuario`
--
ALTER TABLE `back_AdmAreaUsuario`
  ADD CONSTRAINT `back_AdmAreaUsuario_ibfk_1` FOREIGN KEY (`area`) REFERENCES `back_AdmArea` (`area`),
  ADD CONSTRAINT `back_AdmAreaUsuario_ibfk_2` FOREIGN KEY (`usuarioNerdweb`) REFERENCES `back_AdmUsuario` (`usuarioNerdweb`);

--
-- Constraints for table `back_AdmGrupoUsuario`
--
ALTER TABLE `back_AdmGrupoUsuario`
  ADD CONSTRAINT `back_AdmGrupoUsuario_ibfk_1` FOREIGN KEY (`usuarioNerdweb`) REFERENCES `back_AdmUsuario` (`usuarioNerdweb`),
  ADD CONSTRAINT `back_AdmGrupoUsuario_ibfk_2` FOREIGN KEY (`grupo`) REFERENCES `back_AdmGrupo` (`grupo`);

--
-- Constraints for table `back_AdmPermissaoGrupo`
--
ALTER TABLE `back_AdmPermissaoGrupo`
  ADD CONSTRAINT `back_AdmPermissaoGrupo_ibfk_1` FOREIGN KEY (`permissao`) REFERENCES `back_AdmPermissao` (`permissao`),
  ADD CONSTRAINT `back_AdmPermissaoGrupo_ibfk_2` FOREIGN KEY (`grupo`) REFERENCES `back_AdmGrupo` (`grupo`);

--
-- Constraints for table `back_ServicoCliente`
--
ALTER TABLE `back_ServicoCliente`
  ADD CONSTRAINT `back_ServicoCliente_ibfk_1` FOREIGN KEY (`cliente`) REFERENCES `back_Cliente` (`cliente`),
  ADD CONSTRAINT `back_ServicoCliente_ibfk_2` FOREIGN KEY (`servico`) REFERENCES `back_Servico` (`servico`);

--
-- Constraints for table `pop_AdmAreaXelementoTipo`
--
ALTER TABLE `pop_AdmAreaXelementoTipo`
  ADD CONSTRAINT `pop_AdmAreaXelementoTipo_ibfk_1` FOREIGN KEY (`area`) REFERENCES `back_AdmArea` (`area`),
  ADD CONSTRAINT `pop_AdmAreaXelementoTipo_ibfk_2` FOREIGN KEY (`elementoTipo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`);

--
-- Constraints for table `pop_ColunasBaseNomeTipo`
--
ALTER TABLE `pop_ColunasBaseNomeTipo`
  ADD CONSTRAINT `pop_ColunasBaseNomeTipo_ibfk_1` FOREIGN KEY (`colunaBase`) REFERENCES `pop_ColunasBase` (`colunaBase`);

--
-- Constraints for table `pop_ColunasBaseXelementoTipo`
--
ALTER TABLE `pop_ColunasBaseXelementoTipo`
  ADD CONSTRAINT `pop_ColunasBaseXelementoTipo_ibfk_1` FOREIGN KEY (`elementoTipo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`),
  ADD CONSTRAINT `pop_ColunasBaseXelementoTipo_ibfk_2` FOREIGN KEY (`colunasBase`) REFERENCES `pop_ColunasBase` (`colunaBase`);

--
-- Constraints for table `pop_Elemento`
--
ALTER TABLE `pop_Elemento`
  ADD CONSTRAINT `pop_Elemento_ibfk_1` FOREIGN KEY (`elementoTipo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`),
  ADD CONSTRAINT `pop_Elemento_ibfk_2` FOREIGN KEY (`elementoStatus`) REFERENCES `pop_ElementoStatus` (`elementoStatus`),
  ADD CONSTRAINT `pop_Elemento_ibfk_3` FOREIGN KEY (`projeto`) REFERENCES `pop_Projeto` (`projeto`);

--
-- Constraints for table `pop_ElementoAnterior`
--
ALTER TABLE `pop_ElementoAnterior`
  ADD CONSTRAINT `pop_ElementoAnterior_ibfk_1` FOREIGN KEY (`projetoTipo`) REFERENCES `pop_ProjetoTipo` (`projetoTipo`),
  ADD CONSTRAINT `pop_ElementoAnterior_ibfk_2` FOREIGN KEY (`elementoTipo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`),
  ADD CONSTRAINT `pop_ElementoAnterior_ibfk_3` FOREIGN KEY (`anterior`) REFERENCES `pop_ElementoTipo` (`elementoTipo`);

--
-- Constraints for table `pop_ElementoChat`
--
ALTER TABLE `pop_ElementoChat`
  ADD CONSTRAINT `pop_ElementoChat_ibfk_1` FOREIGN KEY (`responsavel`) REFERENCES `back_AdmUsuario` (`usuarioNerdweb`),
  ADD CONSTRAINT `pop_ElementoChat_ibfk_2` FOREIGN KEY (`elemento`) REFERENCES `pop_Elemento` (`elemento`);

--
-- Constraints for table `pop_ElementoConteudo`
--
ALTER TABLE `pop_ElementoConteudo`
  ADD CONSTRAINT `pop_ElementoConteudo_ibfk_1` FOREIGN KEY (`elemento`) REFERENCES `pop_Elemento` (`elemento`);

--
-- Constraints for table `pop_ElementoHistorico`
--
ALTER TABLE `pop_ElementoHistorico`
  ADD CONSTRAINT `pop_ElementoHistorico_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `back_AdmUsuario` (`usuarioNerdweb`),
  ADD CONSTRAINT `pop_ElementoHistorico_ibfk_2` FOREIGN KEY (`elemento`) REFERENCES `pop_Elemento` (`elemento`),
  ADD CONSTRAINT `pop_ElementoHistorico_ibfk_3` FOREIGN KEY (`acao`) REFERENCES `pop_ElementoHistoricoAcao` (`acao`);

--
-- Constraints for table `pop_ElementoPrimeiro`
--
ALTER TABLE `pop_ElementoPrimeiro`
  ADD CONSTRAINT `pop_ElementoPrimeiro_ibfk_1` FOREIGN KEY (`projetoTipo`) REFERENCES `pop_ProjetoTipo` (`projetoTipo`),
  ADD CONSTRAINT `pop_ElementoPrimeiro_ibfk_2` FOREIGN KEY (`elementoTipo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`);

--
-- Constraints for table `pop_ElementoProximo`
--
ALTER TABLE `pop_ElementoProximo`
  ADD CONSTRAINT `pop_ElementoProximo_ibfk_1` FOREIGN KEY (`projetoTipo`) REFERENCES `pop_ProjetoTipo` (`projetoTipo`),
  ADD CONSTRAINT `pop_ElementoProximo_ibfk_2` FOREIGN KEY (`elementoTipo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`),
  ADD CONSTRAINT `pop_ElementoProximo_ibfk_3` FOREIGN KEY (`proximo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`);

--
-- Constraints for table `pop_ElementoTipoNomeTipo`
--
ALTER TABLE `pop_ElementoTipoNomeTipo`
  ADD CONSTRAINT `pop_ElementoTipoNomeTipo_ibfk_1` FOREIGN KEY (`elementoTipo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`);

--
-- Constraints for table `pop_ElementoTipoSubEtapa`
--
ALTER TABLE `pop_ElementoTipoSubEtapa`
  ADD CONSTRAINT `pop_ElementoTipoSubEtapa_ibfk_1` FOREIGN KEY (`elementoTipo`) REFERENCES `pop_ElementoTipo` (`elementoTipo`),
  ADD CONSTRAINT `pop_ElementoTipoSubEtapa_ibfk_2` FOREIGN KEY (`area`) REFERENCES `back_AdmArea` (`area`),
  ADD CONSTRAINT `pop_ElementoTipoSubEtapa_ibfk_3` FOREIGN KEY (`responsavel`) REFERENCES `back_AdmAreaUsuario` (`id`),
  ADD CONSTRAINT `pop_ElementoTipoSubEtapa_ibfk_4` FOREIGN KEY (`proximo`) REFERENCES `pop_ElementoTipoSubEtapa` (`etapa`),
  ADD CONSTRAINT `pop_ElementoTipoSubEtapa_ibfk_5` FOREIGN KEY (`anterior`) REFERENCES `pop_ElementoTipoSubEtapa` (`etapa`);

--
-- Constraints for table `pop_Notificacao`
--
ALTER TABLE `pop_Notificacao`
  ADD CONSTRAINT `fk_area` FOREIGN KEY (`area`) REFERENCES `back_AdmArea` (`area`),
  ADD CONSTRAINT `fk_cliente` FOREIGN KEY (`cliente`) REFERENCES `back_Cliente` (`cliente`),
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`usuario`) REFERENCES `back_AdmUsuario` (`usuarioNerdweb`);

--
-- Constraints for table `pop_Projeto`
--
ALTER TABLE `pop_Projeto`
  ADD CONSTRAINT `pop_Projeto_ibfk_1` FOREIGN KEY (`projetoTipo`) REFERENCES `pop_ProjetoTipo` (`projetoTipo`),
  ADD CONSTRAINT `pop_Projeto_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `back_Cliente` (`cliente`);

--
-- Constraints for table `pop_ProjetoChat`
--
ALTER TABLE `pop_ProjetoChat`
  ADD CONSTRAINT `fk_elemento` FOREIGN KEY (`elemento`) REFERENCES `pop_Elemento` (`elemento`),
  ADD CONSTRAINT `pop_ProjetoChat_ibfk_1` FOREIGN KEY (`projeto`) REFERENCES `pop_Projeto` (`projeto`),
  ADD CONSTRAINT `pop_ProjetoChat_ibfk_2` FOREIGN KEY (`responsavel`) REFERENCES `back_AdmUsuario` (`usuarioNerdweb`);

--
-- Constraints for table `pop_ProjetoConteudo`
--
ALTER TABLE `pop_ProjetoConteudo`
  ADD CONSTRAINT `pop_ProjetoConteudo_ibfk_1` FOREIGN KEY (`projeto`) REFERENCES `pop_Projeto` (`projeto`);

--
-- Constraints for table `pop_ProjetoDadosTemporarios`
--
ALTER TABLE `pop_ProjetoDadosTemporarios`
  ADD CONSTRAINT `pop_ProjetoDadosTemporarios_ibfk_1` FOREIGN KEY (`projeto`) REFERENCES `pop_Projeto` (`projeto`);

--
-- Constraints for table `pop_ProjetoTipoNomeTipo`
--
ALTER TABLE `pop_ProjetoTipoNomeTipo`
  ADD CONSTRAINT `pop_ProjetoTipoNomeTipo_ibfk_1` FOREIGN KEY (`projetoTipo`) REFERENCES `pop_ProjetoTipo` (`projetoTipo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
