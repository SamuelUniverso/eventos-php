BEGIN;

    CREATE TABLE IF NOT EXISTS evento (
        id BIGINT NOT NULL UNIQUE,
        nome TEXT NOT NULL,
        datahora TIMESTAMP,
        PRIMARY KEY (id)
    );

    CREATE TABLE IF NOT EXISTS pessoa (
        id BIGINT NOT NULL UNIQUE,
        nome TEXT NOT NULL,
        CPF TEXT NOT NULL,
        PRIMARY KEY (id)
    );

    CREATE TABLE IF NOT EXISTS inscricao (
        id BIGINT NOT NULL UNIQUE,
        fk_evento BIGINT NOT NULL,
        fk_pessoa BIGINT NOT NULL,
        presenca BOOLEAN DEFAULT FALSE NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (fk_evento) REFERENCES evento(id),
        FOREIGN KEY (fk_pessoa) REFERENCES pessoa(id)
    );
    ALTER TABLE inscricao ADD CONSTRAINT uq_fk_evento_fk_pessoa UNIQUE(fk_evento, fk_pessoa);

    CREATE TABLE IF NOT EXISTS usuario (
        id BIGINT NOT NULL UNIQUE,
        usuario TEXT NOT NULL,
        senha TEXT NOT NULL
    );

COMMIT;