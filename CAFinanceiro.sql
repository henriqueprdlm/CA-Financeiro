CREATE DATABASE IF NOT EXISTS `CAFinanceiro` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `CAFinanceiro`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Tabela categorias
CREATE TABLE `categorias` (
  `idCategoria` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` TEXT NOT NULL,
  `descricao` TEXT NOT NULL,
  `saldo` float NOT NULL,
  PRIMARY KEY (`idCategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabela lancamentos
CREATE TABLE `lancamentos` (
  `idLancamento` int(11) NOT NULL AUTO_INCREMENT,
  `idCategoria` int(11) NOT NULL,
  `valor` float NOT NULL,
  `descricao` TEXT NOT NULL,
  `data` date NOT NULL,
  PRIMARY KEY (`idLancamento`),
  KEY `idx_categoria` (`idCategoria`),
  CONSTRAINT `fk_lancamentos_categoria` FOREIGN KEY (`idCategoria`) REFERENCES `categorias` (`idCategoria`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
