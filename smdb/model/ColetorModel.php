<?php


class ColetorModel extends DbConection
{

    public function __construct()
    {
    }

    public function insert_stat_sgbd_versao( $data )
    {
        $this->openDb();
        $result = pg_query("INSERT INTO stat_sgbd_versao
                                        (usuario,
                                         data_coleta,
                                         versao)
                   VALUES ({$this->bdValor($data->usuario)},
                           {$this->bdValor($data->data_coleta)},
                           {$this->bdValor($data->versao)})");
        $this->closeDb();

        if ( !$result )
        {
            $error = pg_last_error($this->dbcon);
            throw new Exception($error);
        }

        return $result;
    }


    public function insert_stat_base_de_dados( $data )
    {
        $this->openDb();
        $result = pg_query("INSERT INTO stat_base_de_dados
                                        (usuario,
                                         data_coleta,
                                         datid,
                                         datname,
                                         numbackends,
                                         xact_commit,
                                         xact_rollback,
                                         blks_read,
                                         blks_hit,
                                         tup_returned,
                                         tup_fetched,
                                         tup_inserted,
                                         tup_updated,
                                         tup_deleted,
                                         conflicts,
                                         stats_reset,
                                         confl_tablespace,
                                         confl_lock,
                                         confl_snapshot,
                                         confl_bufferpin,
                                         confl_deadlock,
                                         hit_ratio,
                                         tamanho_base_de_dados_formatado,
                                         tamanho_base_de_dados)
                                  VALUES ({$this->bdValor($data->usuario)},
                                          {$this->bdValor($data->data_coleta)},
                                          {$this->bdValor($data->datid)},
                                          {$this->bdValor($data->datname)},
                                          {$this->bdValor($data->numbackends)},
                                          {$this->bdValor($data->xact_commit)},
                                          {$this->bdValor($data->xact_rollback)},
                                          {$this->bdValor($data->blks_read)},
                                          {$this->bdValor($data->blks_hit)},
                                          {$this->bdValor($data->tup_returned)},
                                          {$this->bdValor($data->tup_fetched)},
                                          {$this->bdValor($data->tup_inserted)},
                                          {$this->bdValor($data->tup_updated)},
                                          {$this->bdValor($data->tup_deleted)},
                                          {$this->bdValor($data->conflicts)},
                                          {$this->bdValor($data->stats_reset)},
                                          {$this->bdValor($data->confl_tablespace)},
                                          {$this->bdValor($data->confl_lock)},
                                          {$this->bdValor($data->confl_snapshot)},
                                          {$this->bdValor($data->confl_bufferpin)},
                                          {$this->bdValor($data->confl_deadlock)},
                                          {$this->bdValor($data->hit_ratio)},
                                          {$this->bdValor($data->tamanho_base_de_dados_formatado)},
                                          {$this->bdValor($data->tamanho_base_de_dados)}
                                          )");

        $this->closeDb();

        if ( !$result )
        {
            $error = pg_last_error($this->dbcon);
            throw new Exception($error);
        }

        return $result;
    }


    public function insert_stat_tabela( $data )
    {
        $this->openDb();
        $result = pg_query("INSERT INTO stat_tabela
                                        (data_coleta,
                                         usuario,
                                         relid,
                                         schemaname,
                                         relname,
                                         seq_scan,
                                         seq_tup_read,
                                         idx_scan,
                                         idx_tup_fetch,
                                         n_tup_ins,
                                         n_tup_upd,
                                         n_tup_del,
                                         n_tup_hot_upd,
                                         n_live_tup,
                                         n_dead_tup,
                                         last_vacuum,
                                         last_autovacuum,
                                         last_analyze,
                                         last_autoanalyze,
                                         vacuum_count,
                                         autovacuum_count,
                                         analyze_count,
                                         autoanalyze_count,
                                         tamanho,
                                         tamanho_com_indices,
                                         tamanho_formatado,
                                         tamanho_com_indices_formatado,
                                         hit_ratio,
                                         aproveitamento_cache)
                                  VALUES ({$this->bdValor($data->data_coleta)},
                                          {$this->bdValor($data->usuario)},
                                          {$this->bdValor($data->relid)},
                                          {$this->bdValor($data->schemaname)},
                                          {$this->bdValor($data->relname)},
                                          {$this->bdValor($data->seq_scan)},
                                          {$this->bdValor($data->seq_tup_read)},
                                          {$this->bdValor($data->idx_scan)},
                                          {$this->bdValor($data->idx_tup_fetch)},
                                          {$this->bdValor($data->n_tup_ins)},
                                          {$this->bdValor($data->n_tup_upd)},
                                          {$this->bdValor($data->n_tup_del)},
                                          {$this->bdValor($data->n_tup_hot_upd)},
                                          {$this->bdValor($data->n_live_tup)},
                                          {$this->bdValor($data->n_dead_tup)},
                                          {$this->bdValor($data->last_vacuum)},
                                          {$this->bdValor($data->last_autovacuum)},
                                          {$this->bdValor($data->last_analyze)},
                                          {$this->bdValor($data->last_autoanalyze)},
                                          {$this->bdValor($data->vacuum_count)},
                                          {$this->bdValor($data->autovacuum_count)},
                                          {$this->bdValor($data->analyze_count)},
                                          {$this->bdValor($data->autoanalyze_count)},
                                          {$this->bdValor($data->tamanho)},
                                          {$this->bdValor($data->tamanho_com_indices)},
                                          {$this->bdValor($data->tamanho_formatado)},
                                          {$this->bdValor($data->tamanho_com_indices_formatado)},
                                          {$this->bdValor($data->hit_ratio)},
                                          {$this->bdValor($data->aproveitamento_cache)})");
        $this->closeDb();

        if ( !$result )
        {
            $error = pg_last_error($this->dbcon);
            throw new Exception($error);
        }

        return $result;
    }

    public function insert_stat_indice( $data )
    {
        $this->openDb();
        $result = pg_query("INSERT INTO stat_indice
                                        (data_coleta,
                                         usuario,
                                         relid,
                                         indexrelid,
                                         schemaname,
                                         relname,
                                         indexrelname,
                                         idx_scan,
                                         idx_tup_read,
                                         idx_tup_fetch,
                                         utilizado,
                                         tamanho,
                                         tamanho_com_indices,
                                         tamanho_formatado,
                                         tamanho_com_indices_formatado)
                                  VALUES ({$this->bdValor($data->data_coleta)},
                                          {$this->bdValor($data->usuario)},
                                          {$this->bdValor($data->relid)},
                                          {$this->bdValor($data->indexrelid)},
                                          {$this->bdValor($data->schemaname)},
                                          {$this->bdValor($data->relname)},
                                          {$this->bdValor($data->indexrelname)},
                                          {$this->bdValor($data->idx_scan)},
                                          {$this->bdValor($data->idx_tup_read)},
                                          {$this->bdValor($data->idx_tup_fetch)},
                                          {$this->bdValor($data->utilizado)},
                                          {$this->bdValor($data->tamanho)},
                                          {$this->bdValor($data->tamanho_com_indices)},
                                          {$this->bdValor($data->tamanho_formatado)},
                                          {$this->bdValor($data->tamanho_com_indices_formatado)})");
        $this->closeDb();

        if ( !$result )
        {
            $error = pg_last_error($this->dbcon);
            throw new Exception($error);
        }

        return $result;
    }


    public function insert_stat_configuracao_base_de_dados( $data )
    {
        $this->openDb();
        $result = pg_query("INSERT INTO stat_configuracao_base_de_dados
                                        (data_coleta,
                                         usuario,
                                         name,
                                         setting,
                                         unit,
                                         category,
                                         short_desc,
                                         extra_desc,
                                         context,
                                         vartype,
                                         source,
                                         min_val,
                                         max_val,
                                         enumvals,
                                         boot_val,
                                         reset_val,
                                         sourcefile,
                                         sourceline)
                                  VALUES ({$this->bdValor($data->data_coleta)},
                                          {$this->bdValor($data->usuario)},
                                          {$this->bdValor($data->name)},
                                          {$this->bdValor($data->setting)},
                                          {$this->bdValor($data->unit)},
                                          {$this->bdValor($data->category)},
                                          {$this->bdValor($data->short_desc)},
                                          {$this->bdValor($data->extra_desc)},
                                          {$this->bdValor($data->context)},
                                          {$this->bdValor($data->vartype)},
                                          {$this->bdValor($data->source)},
                                          {$this->bdValor($data->min_val)},
                                          {$this->bdValor($data->max_val)},
                                          {$this->bdValor($data->enumvals)},
                                          {$this->bdValor($data->boot_val)},
                                          {$this->bdValor($data->reset_val)},
                                          {$this->bdValor($data->sourcefile)},
                                          {$this->bdValor($data->sourceline)})");
        $this->closeDb();

        if ( !$result )
        {
            $error = pg_last_error($this->dbcon);
            throw new Exception($error);
        }

        return $result;
    }

    public function insert_stat_loadavg( $data )
    {
        $this->openDb();
        $result = pg_query("INSERT INTO stat_loadavg
                                        (data_coleta,
                                         usuario,
                                         load_ultimo_minuto,
                                         load_ultimos_5_minutos,
                                         load_ultimos_15_minutos)
                                  VALUES ({$this->bdValor($data->data_coleta)},
                                          {$this->bdValor($data->usuario)},
                                          {$this->bdValor($data->load_ultimo_minuto)},
                                          {$this->bdValor($data->load_ultimos_5_minutos)},
                                          {$this->bdValor($data->load_ultimos_15_minutos)})");
        $this->closeDb();

        if ( !$result )
        {
            $error = pg_last_error($this->dbcon);
            throw new Exception($error);
        }

        return $result;
    }

    public function insert_stat_memoria( $data )
    {
        $this->openDb();
        $result = pg_query("INSERT INTO stat_memoria
                                        (data_coleta,
                                        usuario,
                                        format_memused,
                                        format_memfree,
                                        format_memshared,
                                        format_membuffers,
                                        format_memcached,
                                        format_swapused,
                                        format_swapfree,
                                        format_swapcached,
                                        memused,
                                        memfree,
                                        memshared,
                                        membuffers,
                                        memcached,
                                        swapused,
                                        swapfree,
                                        swapcached)
                                  VALUES ({$this->bdValor($data->data_coleta)},
                                          {$this->bdValor($data->usuario)},
                                          {$this->bdValor($data->format_memused)},
                                          {$this->bdValor($data->format_memfree)},
                                          {$this->bdValor($data->format_memshared)},
                                          {$this->bdValor($data->format_membuffers)},
                                          {$this->bdValor($data->format_memcached)},
                                          {$this->bdValor($data->format_swapused)},
                                          {$this->bdValor($data->format_swapfree)},
                                          {$this->bdValor($data->format_swapcached)},
                                          {$this->bdValor($data->memused)},
                                          {$this->bdValor($data->memfree)},
                                          {$this->bdValor($data->memshared)},
                                          {$this->bdValor($data->membuffers)},
                                          {$this->bdValor($data->memcached)},
                                          {$this->bdValor($data->swapused)},
                                          {$this->bdValor($data->swapfree)},
                                          {$this->bdValor($data->swapcached)})");
        $this->closeDb();

        if ( !$result )
        {
            $error = pg_last_error($this->dbcon);
            throw new Exception($error);
        }

        return $result;
    }


    public function insert_stat_processos( $data )
    {
        $this->openDb();
        $result = pg_query("INSERT INTO stat_processos
                                        (data_coleta,
                                         usuario,
                                         datname,
                                         usename,
                                         pid,
                                         priority,
                                         memoria,
                                         state,
                                         query,
                                         inicio_processo,
                                         hora_coleta,
                                         tempo_execussao,
                                         explain)
                                  VALUES ({$this->bdValor($data->data_coleta)},
                                          {$this->bdValor($data->usuario)},
                                          {$this->bdValor($data->datname)},
                                          {$this->bdValor($data->usename)},
                                          {$this->bdValor($data->pid)},
                                          {$this->bdValor($data->priority)},
                                          {$this->bdValor($data->memoria)},
                                          {$this->bdValor($data->state)},
                                          {$this->bdValor($data->query)},
                                          {$this->bdValor($data->inicio_processo)},
                                          {$this->bdValor($data->hora_coleta)},
                                          {$this->bdValor($data->tempo_execussao)},
                                          {$this->bdValor($data->explain)})");
        $this->closeDb();

        if ( !$result )
        {
            $error = pg_last_error($this->dbcon);
            throw new Exception($error);
        }

        return $result;
    }

    public function insert_stat_bloqueios( $data )
    {
        $this->openDb();
        $result = pg_query("INSERT INTO stat_bloqueios
                                        (data_coleta,
                                         usuario,
                                         datname,
                                         usename,
                                         pid,
                                         priority,
                                         memoria,
                                         state,
                                         query,
                                         inicio_processo,
                                         hora_coleta,
                                         tempo_execussao,
                                         mode,
                                         granted)
                                  VALUES ({$this->bdValor($data->data_coleta)},
                                          {$this->bdValor($data->usuario)},
                                          {$this->bdValor($data->datname)},
                                          {$this->bdValor($data->usename)},
                                          {$this->bdValor($data->pid)},
                                          {$this->bdValor($data->priority)},
                                          {$this->bdValor($data->memoria)},
                                          {$this->bdValor($data->state)},
                                          {$this->bdValor($data->query)},
                                          {$this->bdValor($data->inicio_processo)},
                                          {$this->bdValor($data->hora_coleta)},
                                          {$this->bdValor($data->tempo_execussao)},
                                          {$this->bdValor($data->mode)},
                                          {$this->bdValor($data->granted)}");
        $this->closeDb();

        if ( !$result )
        {
            $error = pg_last_error($this->dbcon);
            throw new Exception($error);
        }

        return $result;
    }

    public function bdValor($valor)
    {
        $result = ($valor != NULL)?"'".pg_escape_string($valor)."'":'NULL';
        return $result;
    }
}

?>
