-- Use this sql file to alter database from 1.0beta1 to 1.0beta2.
-- This allows you to keep former database and structures, and it alters the neccessary things.
-- Although it is comfortable it is recommended to recreate the database,
-- because there were inaccuracies, and former data is corrupted about indexes.

ALTER TABLE raw_data.t_database_size DROP CONSTRAINT t_database_size_node_id_fkey;
ALTER TABLE raw_data.t_stat_activity DROP CONSTRAINT t_stat_activity_node_fkey;
ALTER TABLE raw_data.t_stat_all_indexes_day DROP CONSTRAINT t_stat_all_indexes_day_node_id_fkey;
ALTER TABLE raw_data.t_stat_all_indexes DROP CONSTRAINT t_stat_all_indexes_node_fkey;
ALTER TABLE raw_data.t_stat_all_tables_day DROP CONSTRAINT t_stat_all_tables_day_node_id_fkey;
ALTER TABLE raw_data.t_stat_all_tables DROP CONSTRAINT t_stat_all_tables_node_fkey;
ALTER TABLE raw_data.t_stat_bgwriter DROP CONSTRAINT t_stat_bgwriter_node_fkey;
ALTER TABLE raw_data.t_stat_database DROP CONSTRAINT t_stat_database_node_fkey;
ALTER TABLE raw_data.t_stat_user_functions DROP CONSTRAINT t_stat_user_functions_node_fkey;
ALTER TABLE raw_data.t_statio_all_indexes_day DROP CONSTRAINT t_statio_all_indexes_day_node_fkey;
ALTER TABLE raw_data.t_statio_all_indexes DROP CONSTRAINT t_statio_all_indexes_node_fkey;
ALTER TABLE raw_data.t_statio_all_tables_day DROP CONSTRAINT t_statio_all_tables_day_node_fkey;
ALTER TABLE raw_data.t_statio_all_tables DROP CONSTRAINT t_statio_all_tables_node_fkey;

ALTER TABLE raw_data.t_database_size DROP CONSTRAINT t_database_size_database_id_fkey;
ALTER TABLE raw_data.t_stat_activity DROP CONSTRAINT t_stat_activity_db_fkey;
ALTER TABLE raw_data.t_stat_all_indexes_day DROP CONSTRAINT t_stat_all_indexes_day_database_id_fkey;
ALTER TABLE raw_data.t_stat_all_indexes DROP CONSTRAINT t_stat_all_indexes_db_fkey;
ALTER TABLE raw_data.t_stat_all_tables_day DROP CONSTRAINT t_stat_all_tables_day_database_id_fkey;
ALTER TABLE raw_data.t_stat_all_tables DROP CONSTRAINT t_stat_all_tables_db_fkey;
ALTER TABLE raw_data.t_stat_bgwriter DROP CONSTRAINT t_stat_bgwriter_db_fkey;
ALTER TABLE raw_data.t_stat_database DROP CONSTRAINT t_stat_database_db_fkey;
ALTER TABLE raw_data.t_stat_user_functions DROP CONSTRAINT t_stat_user_functions_db_fkey;
ALTER TABLE raw_data.t_statio_all_indexes_day DROP CONSTRAINT t_statio_all_indexes_day_db_fkey;
ALTER TABLE raw_data.t_statio_all_indexes DROP CONSTRAINT t_statio_all_indexes_db_fkey;
ALTER TABLE raw_data.t_statio_all_tables_day DROP CONSTRAINT t_statio_all_tables_day_db_fkey;
ALTER TABLE raw_data.t_statio_all_tables DROP CONSTRAINT t_statio_all_tables_db_fkey;

ALTER TABLE raw_data.t_database_size ADD CONSTRAINT t_database_size_node_fkey FOREIGN KEY (node_id) REFERENCES config.t_node(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_stat_activity ADD CONSTRAINT t_stat_activity_node_fkey FOREIGN KEY (node_id) REFERENCES config.t_node(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_stat_all_indexes_day ADD CONSTRAINT t_stat_all_indexes_day_node_fkey FOREIGN KEY (node_id) REFERENCES config.t_node(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_stat_all_indexes ADD CONSTRAINT t_stat_all_indexes_node_fkey FOREIGN KEY (node_id) REFERENCES config.t_node(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_stat_all_tables_day ADD CONSTRAINT t_stat_all_tables_day_node_fkey FOREIGN KEY (node_id) REFERENCES config.t_node(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_stat_all_tables ADD CONSTRAINT t_stat_all_tables_node_fkey FOREIGN KEY (node_id) REFERENCES config.t_node(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_stat_bgwriter ADD CONSTRAINT t_stat_bgwriter_node_fkey FOREIGN KEY (node_id) REFERENCES config.t_node(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_stat_database ADD CONSTRAINT t_stat_database_node_fkey FOREIGN KEY (node_id) REFERENCES config.t_node(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_stat_user_functions ADD CONSTRAINT t_stat_user_functions_node_fkey FOREIGN KEY (node_id) REFERENCES config.t_node(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_statio_all_indexes_day ADD CONSTRAINT t_statio_all_indexes_day_node_fkey FOREIGN KEY (node_id) REFERENCES config.t_node(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_statio_all_indexes ADD CONSTRAINT t_statio_all_indexes_node_fkey FOREIGN KEY (node_id) REFERENCES config.t_node(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_statio_all_tables_day ADD CONSTRAINT t_statio_all_tables_day_node_fkey FOREIGN KEY (node_id) REFERENCES config.t_node(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_statio_all_tables ADD CONSTRAINT t_statio_all_tables_node_fkey FOREIGN KEY (node_id) REFERENCES config.t_node(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE raw_data.t_database_size ADD CONSTRAINT t_database_size_db_fkey FOREIGN KEY (database_id) REFERENCES config.t_database(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_stat_activity ADD CONSTRAINT t_stat_activity_db_fkey FOREIGN KEY (database_id) REFERENCES config.t_database(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_stat_all_indexes_day ADD CONSTRAINT t_stat_all_indexes_day_db_fkey FOREIGN KEY (database_id) REFERENCES config.t_database(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_stat_all_indexes ADD CONSTRAINT t_stat_all_indexes_db_fkey FOREIGN KEY (database_id) REFERENCES config.t_database(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_stat_all_tables_day ADD CONSTRAINT t_stat_all_tables_day_db_fkey FOREIGN KEY (database_id) REFERENCES config.t_database(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_stat_all_tables ADD CONSTRAINT t_stat_all_tables_db_fkey FOREIGN KEY (database_id) REFERENCES config.t_database(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_stat_bgwriter ADD CONSTRAINT t_stat_bgwriter_db_fkey FOREIGN KEY (database_id) REFERENCES config.t_database(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_stat_database ADD CONSTRAINT t_stat_database_db_fkey FOREIGN KEY (database_id) REFERENCES config.t_database(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_stat_user_functions ADD CONSTRAINT t_stat_user_functions_db_fkey FOREIGN KEY (database_id) REFERENCES config.t_database(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_statio_all_indexes_day ADD CONSTRAINT t_statio_all_indexes_day_db_fkey FOREIGN KEY (database_id) REFERENCES config.t_database(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_statio_all_indexes ADD CONSTRAINT t_statio_all_indexes_db_fkey FOREIGN KEY (database_id) REFERENCES config.t_database(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_statio_all_tables_day ADD CONSTRAINT t_statio_all_tables_day_db_fkey FOREIGN KEY (database_id) REFERENCES config.t_database(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE raw_data.t_statio_all_tables ADD CONSTRAINT t_statio_all_tables_db_fkey FOREIGN KEY (database_id) REFERENCES config.t_database(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE users.t_dashboard DROP CONSTRAINT t_dashboard_user_id_fkey;
ALTER TABLE users.t_dashboard ADD CONSTRAINT t_dashboard_user_id_fkey FOREIGN KEY (user_id) REFERENCES t_user(id) ON DELETE CASCADE ON UPDATE CASCADE ON DELETE CASCADE;
    