CREATE TABLE smbd_usuario (
 usuario TEXT NOT NULL PRIMARY KEY,
 nome VARCHAR(50) NOT NULL,
 senha VARCHAR(40),
 email VARCHAR(100),
 ativo BOOLEAN,
 cadastro TIMESTAMP NOT NULL DEFAULT NOW()
);




CREATE TABLE stat_base_de_dados (
 id SERIAL NOT NULL PRIMARY KEY,
 usuario TEXT NOT NULL REFERENCES smbd_usuario (usuario),
 data_coleta TIMESTAMP NOT NULL,
 datid TEXT,
 datname TEXT,
 numbackends TEXT,
 xact_commit TEXT,
 xact_rollback TEXT,
 blks_read TEXT,
 blks_hit TEXT,
 tup_returned TEXT,
 tup_fetched TEXT,
 tup_inserted TEXT,
 tup_updated TEXT,
 tup_deleted TEXT,
 conflicts TEXT,
 stats_reset TEXT,
 confl_tablespace TEXT,
 confl_lock TEXT,
 confl_snapshot TEXT,
 confl_bufferpin TEXT,
 confl_deadlock TEXT,
 hit_ratio TEXT,
 tamanho_base_de_dados_formatado TEXT,
 tamanho_base_de_dados TEXT
);




CREATE TABLE stat_bloqueios (
 id SERIAL NOT NULL PRIMARY KEY,
 usuario TEXT NOT NULL REFERENCES smbd_usuario (usuario),
 data_coleta TIMESTAMP NOT NULL,
 datname TEXT,
 usename TEXT,
 pid TEXT,
 priority TEXT,
 memoria TEXT,
 state TEXT,
 query TEXT,
 inicio_processo TEXT,
 hora_coleta TEXT,
 tempo_execussao TEXT,
 mode TEXT,
 granted TEXT
);




CREATE TABLE stat_configuracao_base_de_dados (
 id SERIAL NOT NULL PRIMARY KEY,
 usuario TEXT NOT NULL REFERENCES smbd_usuario (usuario),
 data_coleta TIMESTAMP NOT NULL,
 name TEXT,
 setting TEXT,
 unit TEXT,
 category TEXT,
 short_desc TEXT,
 extra_desc TEXT,
 context TEXT,
 vartype TEXT,
 source TEXT,
 min_val TEXT,
 max_val TEXT,
 enumvals TEXT,
 boot_val TEXT,
 reset_val TEXT,
 sourcefile TEXT,
 sourceline TEXT
);




CREATE TABLE stat_indice (
 id SERIAL NOT NULL PRIMARY KEY,
 usuario TEXT NOT NULL REFERENCES smbd_usuario (usuario),
 data_coleta TIMESTAMP NOT NULL,
 relid TEXT,
 indexrelid TEXT,
 schemaname TEXT,
 relname TEXT,
 indexrelname TEXT,
 idx_scan TEXT,
 idx_tup_read TEXT,
 idx_tup_fetch TEXT,
 utilizado TEXT,
 tamanho TEXT,
 tamanho_com_indices TEXT,
 tamanho_formatado TEXT,
 tamanho_com_indices_formatado TEXT
);




CREATE TABLE stat_loadavg (
 id SERIAL NOT NULL PRIMARY KEY,
 usuario TEXT NOT NULL REFERENCES smbd_usuario (usuario),
 data_coleta TIMESTAMP NOT NULL,
 load_ultimo_minuto TEXT,
 load_ultimos_5_minutos TEXT,
 load_ultimos_15_minutos TEXT
);




CREATE TABLE stat_memoria (
 id SERIAL NOT NULL PRIMARY KEY,
 usuario TEXT NOT NULL REFERENCES smbd_usuario (usuario),
 data_coleta TIMESTAMP NOT NULL,
 format_memused TEXT,
 format_memfree TEXT,
 format_memshared TEXT,
 format_membuffers TEXT,
 format_memcached TEXT,
 format_swapused TEXT,
 format_swapfree TEXT,
 format_swapcached TEXT,
 memused TEXT,
 memfree TEXT,
 memshared TEXT,
 membuffers TEXT,
 memcached TEXT,
 swapused TEXT,
 swapfree TEXT,
 swapcached TEXT
);




CREATE TABLE stat_processos (
 id SERIAL NOT NULL PRIMARY KEY,
 usuario TEXT NOT NULL REFERENCES smbd_usuario (usuario),
 data_coleta TIMESTAMP NOT NULL,
 datname TEXT,
 usename TEXT,
 pid TEXT,
 priority TEXT,
 memoria TEXT,
 state TEXT,
 query TEXT,
 inicio_processo TEXT,
 hora_coleta TEXT,
 tempo_execussao TEXT,
 explain TEXT
);




CREATE TABLE stat_sgbd_versao (
 id SERIAL NOT NULL PRIMARY KEY,
 usuario TEXT NOT NULL REFERENCES smbd_usuario (usuario),
 data_coleta TIMESTAMP NOT NULL,
 versao TEXT
);




CREATE TABLE stat_tabela (
 id SERIAL NOT NULL PRIMARY KEY,
 usuario TEXT NOT NULL REFERENCES smbd_usuario (usuario),
 data_coleta TIMESTAMP NOT NULL,
 relid TEXT,
 schemaname TEXT,
 relname TEXT,
 seq_scan TEXT,
 seq_tup_read TEXT,
 idx_scan TEXT,
 idx_tup_fetch TEXT,
 n_tup_ins TEXT,
 n_tup_upd TEXT,
 n_tup_del TEXT,
 n_tup_hot_upd TEXT,
 n_live_tup TEXT,
 n_dead_tup TEXT,
 last_vacuum TEXT,
 last_autovacuum TEXT,
 last_analyze TEXT,
 last_autoanalyze TEXT,
 vacuum_count TEXT,
 autovacuum_count TEXT,
 analyze_count TEXT,
 autoanalyze_count TEXT,
 tamanho TEXT,
 tamanho_com_indices TEXT,
 tamanho_formatado TEXT,
 tamanho_com_indices_formatado TEXT,
 hit_ratio TEXT,
 aproveitamento_cache TEXT
);




CREATE TABLE smbd_principal (
 usuario TEXT NOT NULL REFERENCES smbd_usuario (usuario),
 metodo TEXT
);
