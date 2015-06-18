CREATE TABLE usuarios (
  id SERIAL NOT NULL PRIMARY KEY,
  nome varchar(50) NOT NULL,
  usuario varchar(25) NOT NULL,
  senha varchar(40) NOT NULL,
  email varchar(100) NOT NULL,
  nivel int(1),
  ativo boolean,
  cadastro datetime NOT NULL);

INSERT INTO usuarios (id, nome, usuario, senha, email, nivel, ativo, cadastro) VALUES
(1, 'Usuário Teste', 'demo', '89e495e7941cf9e40e6980d14a16bf023ccd4c91', 'usuario@demo.com.br', 1, 1, '2009-07-24 08:32:53'),
(2, 'Administrador Teste', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin@demo.com.br', 2, 1, '2009-07-24 08:40:40');
