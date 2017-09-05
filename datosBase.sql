INSERT INTO `Usuario` (`idUsuario`, `Usuario`, `Clave`, `Estado`, `Paterno`, `Materno`, `Nombres`, `DNI`, `FotoPerfil`, `email`, `movil`, `CreditoSMS`, `CreditoMail`, `Registrador`, `code`) VALUES
(1, 'admin', md5('admin'), 'Activo', '', '', 'Administrador', '', 'avatar03.png', 'admin@alecomled.com', NULL, 10, 10, 1, NULL),

INSERT INTO `Permiso` (`Codigo`, `Modulo`, `idUsuario`) VALUES
('admin', 'admin', 1),
('add', 'admin', 1),
('edit', 'admin', 1),
('level', 'admin', 1),
('remove', 'admin', 1),
('view', 'admin', 1),
('search', 'admin', 1),
('filter', 'admin', 1);
