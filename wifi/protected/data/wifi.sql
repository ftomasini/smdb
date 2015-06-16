
CREATE TABLE configuracao (
	         id_configuracao SERIAL,
             nome TEXT NOT NULL,
             valor TEXT NOT NULL,
             idioma TEXT NOT NULL DEFAULT 'pt_br',
             descricao TEXT
);

CREATE TABLE usuario (
             id_usuario SERIAL,
             nome TEXT NOT NULL,
             senha TEXT NOT NULL,
             email TEXT NOT NULL,
             documento TEXT NOT NULL,
             momento TIMESTAMP WITHOUT TIME ZONE DEFAULT (now()::TIMESTAMP(0))::TIMESTAMP WITHOUT TIME ZONE,
             numero_celular TEXT NOT NULL,
             ativado BOOLEAN DEFAULT FALSE,
             bloqueado BOOLEAN DEFAULT FALSE,
             PRIMARY KEY(id_usuario)
);

CREATE TABLE log_sistema (
             acao TEXT NOT NULL,
             ip INET NOT NULL,
             momento TIMESTAMP WITHOUT TIME ZONE DEFAULT (now()::TIMESTAMP(0))::TIMESTAMP WITHOUT TIME ZONE,
             informacao_adicional TEXT NULL,
             id_usuario INTEGER REFERENCES usuario(id_usuario)

);
