
db stat monitor

!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

Ferramenta

configuração da base de dados












configuração de alertas (com alertas pre-definidos)
    disparar alerta sempre que resulatado da qery for diferente do programado


    Nome do alerta [______________]
    
    Query a ser executada [______________]

    Operador a ser utilizado () = () != (s) > 

    Valor a ser testado [______________]
    
    Mensagem de alerta: [______________]

    Email: [______________]



configuracao do agente
    
    - -----|--- +
 
    tempo de refresh para coleta atividades em execucao
    tempo de refresh para execução de testes de alerta
    tempo de refresh para coleta de estatística tabelas com poucas pesquisas por indices, valor em percentual
    tempo de refresh para coleta de estatística tamando de tabela em disco
    tempo de refresh para coleta de estatística tamando de indices em disco
    tempo de refresh para coleta de estatística tamanho da tabela em disco levendo em conta tabela + indices
    tempo de refresh para coleta de estatística tamanho da base de dados
    tempo de refresh para coleta de estatística aproveitamento do cache por tabela
    tempo de refresh para coleta de estatística aproveitamento do cache do banco todo

    tempo de refresh para execucao de comandos



Tarefas agendadas


Tarefas em execução


Painel de estatísticas

    visualização de estatísticas pré-determinadas

monitoramento de disponibilidade do sgbd



!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

Módulo postgres

    dispara alertas configurados
    executa comandos
    coleta informacoes sobre os processos que estão rodando interage com os processo (cancelamento, analisar tabelas envolvidas, pausar)

    coleta w armazenamento de informações estatísticas do banco de dados
        tabelas com poucas pesquisas por indices, valor em percentual
       -tamando de tabela em disco
        tamando de indices em disco
        tamanho da tabela em disco levendo em conta tabela + indices
        tamanho da base de dados
        aproveitamento do cache por tabela
        aproveitamento do cache do banco todo
        coleta de configuracao de parametros
        coleta e armazenamento melhor dia e horário para manutenções
        coleta de objetos obsoletos (ex: índices)


--CAIXA DE FERRAMENTAS DBA
--Verifica se o monitoramento esta ativado 


Configuração da ferramenta
Gerenciamento de usuários
Visualização de estatísticas
Configuração de estatísticas
Agentamento de tarefas
Configuração de alertas
Monitoramento de processos em execução



SHOW track_counts;

--Versão do SGBD
SELECT version();

-- indices nao utilizados
 SELECT schemaname, 
        relname, 
        indexrelname
   FROM pg_stat_user_indexes 
  WHERE idx_scan = 0;

 -- tabelas com poucas pesquisas por indices, valor em percentual
  SELECT relname,
         idx_scan::numeric / (seq_scan + idx_scan)::numeric * 100 as idx_percent
    FROM pg_stat_user_tables
   WHERE seq_scan + idx_scan > 0
ORDER BY idx_percent, seq_scan DESC;

--tamando de tabela em disco

SELECT pg_size_pretty(pg_relation_size('nome_tabela'));

--tamanho da tabela em disco levendo em conta tabela + indices

SELECT pg_size_pretty(pg_total_relation_size('nome_tabela'));

--tamanho da base de dados
SELECT pg_size_pretty(pg_database_size('nome_banco_de_dados'));

--aproveitamento do cache por tabela (hit ratio),
--para cada tabela do banco mostra um percentual que 
--significa quantas leituras em disco foram poupadas 
--usando o cache do banco em memória, quanto mais alto 
--o percentual mais cache estamos usando e menos disco estamos usando
SELECT relname,
       round(
       (heap_blks_hit + COALESCE(toast_blks_hit, 0))::numeric /
       (heap_blks_read + COALESCE(toast_blks_read, 0) +
        heap_blks_hit + COALESCE(toast_blks_hit,0))::numeric * 100, 3) as hit_ratio
  FROM pg_statio_user_tables
 WHERE (heap_blks_read + COALESCE(toast_blks_read, 0)) + heap_blks_hit + COALESCE(toast_blks_hit) > 0
 ORDER BY 2 DESC ; 

--aproveitamento do cache por tabela (hit ratio),
--para cada tabela do banco mostra um percentual que 
--significa quantas leituras em disco foram poupadas 
--usando o cache do banco em memória, quanto mais alto 
--o percentual mais cache estamos usando e menos disco estamos usando DO BANCO TODO
--CASO O HIT RATIO ESTEJA BAIXO ISSO SIGNIFICA QUE TEMOS POUCO shared_buffers disponivel 
SELECT round(
       AVG(
       (heap_blks_hit + COALESCE(toast_blks_hit, 0))::numeric /
       (heap_blks_read + COALESCE(toast_blks_read, 0) +
        heap_blks_hit + COALESCE(toast_blks_hit,0))::numeric * 100), 3) as hit_ratio
  FROM pg_statio_user_tables
 WHERE (heap_blks_read + COALESCE(toast_blks_read, 0)) + heap_blks_hit + COALESCE(toast_blks_hit) > 0;
 


--A função pg_stat_get_backend_idset fornece uma maneira 
--conveniente de gerar uma linha para cada processo servidor ativo. 
--Por exemplo, para mostrar o PID e o comando corrente de todos os processos servidor:

SELECT pg_stat_get_backend_pid(s.backendid) AS procpid,
       pg_stat_get_backend_activity(s.backendid) AS current_query    
  FROM (SELECT pg_stat_get_backend_idset() AS backendid) AS s;



