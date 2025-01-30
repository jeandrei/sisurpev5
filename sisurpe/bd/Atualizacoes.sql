
ALTER TABLE users
MODIFY COLUMN type varchar(10) DEFAULT 'externo';

UPDATE users SET type='externo'

/*GRUPOS DE USUÁRIOS EX: ADMINISTRADOR, SECRETÁRIO, USUÁRIO*/
CREATE TABLE IF NOT EXISTS `grupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,  
  `grupo` varchar(255) NOT NULL   
) auto_increment=0,
  ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*GRUPO DE ADMINISTRADORES*/
INSERT INTO `grupos` (`id`, `grupo`) VALUES
(1, 'ConfigInicial');

/*USUÁRIOS QUE PERTENCEM A UM GRUPO*/
CREATE TABLE IF NOT EXISTS `usersGrupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userId` int(11) NOT NULL,
  `grupoId` int(11) NOT NULL,
  FOREIGN KEY (`userId`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`grupoId`) REFERENCES `grupos`(`id`) ON DELETE CASCADE
) auto_increment=0,
  ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*ADICIONA OS USUÁRIOS DO GRUPO DE ADMINISTRADORES*/
INSERT INTO `usersGrupos` (`id`, `userId`,`grupoId`) VALUES
(1, 1, 1);

/*O QUE UM GRUPO PODE FAZER EX: ATRIBUTO */
CREATE TABLE IF NOT EXISTS `grupoAcaoTabela` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `grupoId` int(11) NOT NULL,
  `tabela` varchar(255) NOT NULL ,
  `ler` char(1) NOT NULL DEFAULT 'n',
  `editar` char(1) NOT NULL DEFAULT 'n',
  `criar` char(1) NOT NULL DEFAULT 'n',
  `apagar` char(1) NOT NULL DEFAULT 'n',  
  FOREIGN KEY (`grupoId`) REFERENCES `grupos`(`id`) ON DELETE CASCADE
) auto_increment=0,
  ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*permição de ler, editar, criar, e apagar para o grupo de administradores*/
INSERT INTO `grupoAcaoTabela` (`grupoId`,`tabela`,`ler`,`editar`,`criar`,`apagar`) VALUES
(1, 'users','s','s','s','s'),
(1, 'grupos','s','s','s','s'),
(1, 'usersGrupos','s','s','s','s');



