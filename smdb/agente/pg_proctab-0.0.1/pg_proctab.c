/*
 * Copyright (C) 2008 Mark Wong
 */

#include "postgres.h"
#include <string.h>
#include "fmgr.h"
#include "funcapi.h"
#include <sys/vfs.h>
#include <unistd.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <sys/param.h>
#include <executor/spi.h>
#include "pg_common.h"

#define FULLCOMM_LEN 1024

#define GET_PIDS \
		"SELECT procpid " \
		"FROM pg_stat_activity"

enum proctab {i_pid, i_comm, i_fullcomm, i_state, i_ppid, i_pgrp, i_session,
		i_tty_nr, i_tpgid, i_flags, i_minflt, i_cminflt, i_majflt, i_cmajflt,
		i_utime, i_stime, i_cutime, i_cstime, i_priority, i_nice,
		i_num_threads, i_itrealvalue, i_starttime, i_vsize, i_rss,
		i_exit_signal, i_processor, i_rt_priority, i_policy,
		i_delayacct_blkio_ticks, i_uid, i_username};

int get_proctab(FuncCallContext *, char **);

Datum pg_proctab(PG_FUNCTION_ARGS);

PG_FUNCTION_INFO_V1(pg_proctab);

Datum pg_proctab(PG_FUNCTION_ARGS)
{
	FuncCallContext *funcctx;
	int call_cntr;
	int max_calls;
	TupleDesc tupdesc;
	AttInMetadata *attinmeta;

	elog(DEBUG5, "pg_proctab: Entering stored function.");

	/* stuff done only on the first call of the function */
	if (SRF_IS_FIRSTCALL())
	{
		MemoryContext oldcontext;

		int ret;

		/* create a function context for cross-call persistence */
		funcctx = SRF_FIRSTCALL_INIT();

		/* switch to memory context appropriate for multiple function calls */
		oldcontext = MemoryContextSwitchTo(funcctx->multi_call_memory_ctx);

		/* Build a tuple descriptor for our result type */
		if (get_call_result_type(fcinfo, NULL, &tupdesc) != TYPEFUNC_COMPOSITE)
			ereport(ERROR,
					(errcode(ERRCODE_FEATURE_NOT_SUPPORTED),
					errmsg("function returning record called in context "
							"that cannot accept type record")));

		/*
		 * generate attribute metadata needed later to produce tuples from raw
		 * C strings
		 */
		attinmeta = TupleDescGetAttInMetadata(tupdesc);
		funcctx->attinmeta = attinmeta;

		/* Get pid of all client connections. */

		SPI_connect();
		elog(DEBUG5, "pg_proctab: SPI connected.");

		ret = SPI_exec(GET_PIDS, 0);
		if (ret == SPI_OK_SELECT)
		{
			int32 *ppid;

			int i;
			TupleDesc tupdesc;
			SPITupleTable *tuptable;
			HeapTuple tuple;

			/* total number of tuples to be returned */
			funcctx->max_calls = SPI_processed;
			elog(DEBUG5, "pg_proctab: %d process(es) in pg_stat_activity.",
					funcctx->max_calls);
			funcctx->user_fctx = MemoryContextAlloc(
					funcctx->multi_call_memory_ctx, sizeof(int32) *
					funcctx->max_calls);
			ppid = (int32 *) funcctx->user_fctx;

			tupdesc = SPI_tuptable->tupdesc;
			tuptable = SPI_tuptable;

			for (i = 0; i < funcctx->max_calls; i++)
			{
				tuple = tuptable->vals[i];
				ppid[i] = atoi(SPI_getvalue(tuple, tupdesc, 1));
				elog(DEBUG5, "pg_proctab: saving pid %d.", ppid[i]);
			}
		}
		else
		{
			/* total number of tuples to be returned */
			funcctx->max_calls = 0;
			elog(WARNING, "unable to get procpids from pg_stat_activity");
		}

		SPI_finish();

		MemoryContextSwitchTo(oldcontext);
	}

	/* stuff done on every call of the function */
	funcctx = SRF_PERCALL_SETUP();

	call_cntr = funcctx->call_cntr;
	max_calls = funcctx->max_calls;
	attinmeta = funcctx->attinmeta;

	if (call_cntr < max_calls) /* do when there is more left to send */
	{
		HeapTuple tuple;
		Datum result;

		char **values = NULL;

		values = (char **) palloc(32 * sizeof(char *));
		values[i_pid] = (char *) palloc((INTEGER_LEN + 1) * sizeof(char));
		values[i_comm] = (char *) palloc(1024 * sizeof(char));
		values[i_state] = (char *) palloc(2 * sizeof(char));
		values[i_ppid] = (char *) palloc((INTEGER_LEN + 1) * sizeof(char));
		values[i_pgrp] = (char *) palloc((INTEGER_LEN + 1) * sizeof(char));
		values[i_session] = (char *) palloc((INTEGER_LEN + 1) * sizeof(char));
		values[i_tty_nr] = (char *) palloc((INTEGER_LEN + 1) * sizeof(char));
		values[i_tpgid] = (char *) palloc((INTEGER_LEN + 1) * sizeof(char));
		values[i_flags] = (char *) palloc((INTEGER_LEN + 1) * sizeof(char));
		values[i_minflt] = (char *) palloc((BIGINT_LEN + 1) * sizeof(char));
		values[i_cminflt] = (char *) palloc((BIGINT_LEN + 1) * sizeof(char));
		values[i_majflt] = (char *) palloc((BIGINT_LEN + 1) * sizeof(char));
		values[i_cmajflt] = (char *) palloc((BIGINT_LEN + 1) * sizeof(char));

		/* FIXME: Need to figure out correct length to hold a C double type. */
		values[i_utime] = (char *) palloc(32 * sizeof(char));
		values[i_stime] = (char *) palloc(32 * sizeof(char));

		values[i_cutime] = (char *) palloc((BIGINT_LEN + 1) * sizeof(char));
		values[i_cstime] = (char *) palloc((BIGINT_LEN + 1) * sizeof(char));
		values[i_priority] = (char *) palloc((BIGINT_LEN + 1) * sizeof(char));
		values[i_nice] = (char *) palloc((BIGINT_LEN + 1) * sizeof(char));
		values[i_num_threads] =
				(char *) palloc((BIGINT_LEN + 1) * sizeof(char));
		values[i_itrealvalue] =
				(char *) palloc((BIGINT_LEN + 1) * sizeof(char));
		values[i_starttime] = (char *) palloc((BIGINT_LEN + 1) * sizeof(char));
		values[i_vsize] = (char *) palloc((BIGINT_LEN + 1) * sizeof(char));
		values[i_rss] = (char *) palloc((BIGINT_LEN + 1) * sizeof(char));
		values[i_exit_signal] =
				(char *) palloc((INTEGER_LEN + 1) * sizeof(char));
		values[i_processor] = (char *) palloc((INTEGER_LEN + 1) * sizeof(char));
		values[i_rt_priority] =
				(char *) palloc((BIGINT_LEN + 1) * sizeof(char));
		values[i_policy] = (char *) palloc((BIGINT_LEN + 1) * sizeof(char));
		values[i_delayacct_blkio_ticks] =
				(char *) palloc((BIGINT_LEN + 1) * sizeof(char));
		values[i_uid] = (char *) palloc((INTEGER_LEN + 1) * sizeof(char));

		if (get_proctab(funcctx, values) == 0)
			SRF_RETURN_DONE(funcctx);

		/* build a tuple */
		tuple = BuildTupleFromCStrings(attinmeta, values);

		/* make the tuple into a datum */
		result = HeapTupleGetDatum(tuple);

		SRF_RETURN_NEXT(funcctx, result);
	}
	else /* do when there is no more left */
	{
		SRF_RETURN_DONE(funcctx);
	}
}

int
get_proctab(FuncCallContext *funcctx, char **values)
{
#ifdef __linux__
	/*
 	* For details on the Linux process table, see the description of
 	* /proc/PID/stat in Documentation/filesystems/proc.txt in the Linux source
 	* code.
 	*/

	int32 *ppid;
	int32 pid;
	int length;

	struct stat stat_struct;

	struct statfs sb;
	int fd;
	int len;
	char buffer[4096];
	char *p;
	char *q;

	/* Check if /proc is mounted. */
	if (statfs(PROCFS, &sb) < 0 || sb.f_type != PROC_SUPER_MAGIC)
	{
		elog(ERROR, "proc filesystem not mounted on " PROCFS "\n");
		return 0;
	}
	chdir(PROCFS);

	/* Read the stat info for the pid. */

	ppid = (int32 *) funcctx->user_fctx;
	pid = ppid[funcctx->call_cntr];
	elog(DEBUG5, "pg_proctab: accessing process table for pid[%d] %d.",
				funcctx->call_cntr, pid);

	/* Get the full command line information. */
	sprintf(buffer, "%s/%d/cmdline", PROCFS, pid);
	fd = open(buffer, O_RDONLY);
	if (fd == -1)
	{
		elog(ERROR, "'%s' not found", buffer);
		values[i_fullcomm] = NULL;
	}
	else
	{
		values[i_fullcomm] =
				(char *) palloc((FULLCOMM_LEN + 1) * sizeof(char));
		len = read(fd, values[i_fullcomm], FULLCOMM_LEN);
		close(fd);
		values[i_fullcomm][len] = '\0';
	}
	elog(DEBUG5, "pg_proctab: %s %s", buffer, values[i_fullcomm]);

	/* Get the uid and username of the pid's owner. */
	sprintf(buffer, "%s/%d", PROCFS, pid);
	if (stat(buffer, &stat_struct) < 0)
	{
		elog(ERROR, "'%s' not found", buffer);
		strcpy(values[i_uid], "-1");
		values[i_username] = NULL;
	}
	else
	{
		struct passwd *pwd;

		sprintf(values[i_uid], "%d", stat_struct.st_uid);
		pwd = getpwuid(stat_struct.st_uid);
		if (pwd == NULL)
			values[i_username] = NULL;
		else
		{
			values[i_username] = (char *) palloc((strlen(pwd->pw_name) +
					1) * sizeof(char));
			strcpy(values[i_username], pwd->pw_name);
		}
	}

	/* Get the process table information for the pid. */
	sprintf(buffer, "%d/stat", pid);
	fd = open(buffer, O_RDONLY);
	if (fd == -1)
	{
		elog(ERROR, "%d/stat not found", pid);
		return 0;
	}
	len = read(fd, buffer, sizeof(buffer) - 1);
	close(fd);
	buffer[len] = '\0';
	elog(DEBUG5, "pg_proctab: %s", buffer);

	p = buffer;

	/* pid */
	GET_NEXT_VALUE(p, q, values[i_pid], length, "pid not found", ' ');

	/* comm */
	++p;
	if ((q = strchr(p, ')')) == NULL)
	{
		elog(ERROR, "pg_proctab: comm not found");
		return 0;
	}
	length = q - p;
	strncpy(values[i_comm], p, length);
	values[i_comm][length] = '\0';
	p = q + 2;

	/* state */
	values[i_state][0] = *p;
	values[i_state][1] = '\0';
	p = p + 2;

	/* ppid */
	GET_NEXT_VALUE(p, q, values[i_ppid], length, "ppid not found", ' ');

	/* pgrp */
	GET_NEXT_VALUE(p, q, values[i_pgrp], length, "pgrp not found", ' ');

	/* session */
	GET_NEXT_VALUE(p, q, values[i_session], length, "session not found", ' ');

	/* tty_nr */
	GET_NEXT_VALUE(p, q, values[i_tty_nr], length, "tty_nr not found", ' ');

	/* tpgid */
	GET_NEXT_VALUE(p, q, values[i_tpgid], length, "tpgid not found", ' ');

	/* flags */
	GET_NEXT_VALUE(p, q, values[i_flags], length, "flags not found", ' ');

	/* minflt */
	GET_NEXT_VALUE(p, q, values[i_minflt], length, "minflt not found", ' ');

	/* cminflt */
	GET_NEXT_VALUE(p, q, values[i_cminflt], length, "cminflt not found", ' ');

	/* majflt */
	GET_NEXT_VALUE(p, q, values[i_majflt], length, "majflt not found", ' ');

	/* cmajflt */
	GET_NEXT_VALUE(p, q, values[i_cmajflt], length, "cmajflt not found", ' ');

		/* utime */
	GET_NEXT_VALUE(p, q, values[i_utime], length, "utime not found", ' ');

	/* stime */
	GET_NEXT_VALUE(p, q, values[i_stime], length, "stime not found", ' ');

	/* cutime */
	GET_NEXT_VALUE(p, q, values[i_cutime], length, "cutime not found", ' ');

	/* cstime */
	GET_NEXT_VALUE(p, q, values[i_cstime], length, "cstime not found", ' ');

	/* priority */
	GET_NEXT_VALUE(p, q, values[i_priority], length, "priority not found", ' ');

	/* nice */
	GET_NEXT_VALUE(p, q, values[i_nice], length, "nice not found", ' ');

	/* num_threads */
	GET_NEXT_VALUE(p, q, values[i_num_threads], length,
				"num_threads not found", ' ');

	/* itrealvalue */
	GET_NEXT_VALUE(p, q, values[i_itrealvalue], length,
			"itrealvalue not found", ' ');

	/* starttime */
	GET_NEXT_VALUE(p, q, values[i_starttime], length, "starttime not found",
			' ');

	/* vsize */
	GET_NEXT_VALUE(p, q, values[i_vsize], length, "vsize not found", ' ');

	/* rss */
	GET_NEXT_VALUE(p, q, values[i_rss], length, "rss not found", ' ');

	SKIP_TOKEN(p);			/* skip rlim */
	SKIP_TOKEN(p);			/* skip startcode */
	SKIP_TOKEN(p);			/* skip endcode */
	SKIP_TOKEN(p);			/* skip startstack */
	SKIP_TOKEN(p);			/* skip kstkesp */
	SKIP_TOKEN(p);			/* skip kstkeip */
	SKIP_TOKEN(p);			/* skip signal (obsolete) */
	SKIP_TOKEN(p);			/* skip blocked (obsolete) */
	SKIP_TOKEN(p);			/* skip sigignore (obsolete) */
	SKIP_TOKEN(p);			/* skip sigcatch (obsolete) */
	SKIP_TOKEN(p);			/* skip wchan */
	SKIP_TOKEN(p);			/* skip nswap (place holder) */
	SKIP_TOKEN(p);			/* skip cnswap (place holder) */

	/* exit_signal */
	GET_NEXT_VALUE(p, q, values[i_exit_signal], length,
			"exit_signal not found", ' ');

	/* processor */
	GET_NEXT_VALUE(p, q, values[i_processor], length, "processor not found",
			' ');

	/* rt_priority */
	GET_NEXT_VALUE(p, q, values[i_rt_priority], length,
			"rt_priority not found", ' ');

	/* policy */
	GET_NEXT_VALUE(p, q, values[i_policy], length, "policy not found", ' ');

	/* delayacct_blkio_ticks */
	/*
	 * It appears sometimes this is the last item in /proc/PID/stat and
	 * sometimes it's not, depending on the version of the kernel and
	 * possibly the architecture.  So first test if it is the last item
	 * before determining how to deliminate it.
	 */
	if (strchr(p, ' ') == NULL)
	{
		GET_NEXT_VALUE(p, q, values[i_delayacct_blkio_ticks], length,
				"delayacct_blkio_ticks not found", '\n');
	}
	else
	{
		GET_NEXT_VALUE(p, q, values[i_delayacct_blkio_ticks], length,
				"delayacct_blkio_ticks not found", ' ');
	}
#endif /* __linux__ */

	elog(DEBUG5, "pg_proctab: uid %s", values[i_uid]);
	elog(DEBUG5, "pg_proctab: username %s", values[i_username]);
	elog(DEBUG5, "pg_proctab: pid = %s", values[i_pid]);
	elog(DEBUG5, "pg_proctab: comm = %s", values[i_comm]);
	elog(DEBUG5, "pg_proctab: state = %s", values[i_state]);
	elog(DEBUG5, "pg_proctab: ppid = %s", values[i_ppid]);
	elog(DEBUG5, "pg_proctab: pgrp = %s", values[i_pgrp]);
	elog(DEBUG5, "pg_proctab: session = %s", values[i_session]);
	elog(DEBUG5, "pg_proctab: tty_nr = %s", values[i_tty_nr]);
	elog(DEBUG5, "pg_proctab: tpgid = %s", values[i_tpgid]);
	elog(DEBUG5, "pg_proctab: flags = %s", values[i_flags]);
	elog(DEBUG5, "pg_proctab: minflt = %s", values[i_minflt]);
	elog(DEBUG5, "pg_proctab: cminflt = %s", values[i_cminflt]);
	elog(DEBUG5, "pg_proctab: majflt = %s", values[i_majflt]);
	elog(DEBUG5, "pg_proctab: cmajflt = %s", values[i_cmajflt]);
	elog(DEBUG5, "pg_proctab: utime = %s", values[i_utime]);
	elog(DEBUG5, "pg_proctab: stime = %s", values[i_stime]);
	elog(DEBUG5, "pg_proctab: cutime = %s", values[i_cutime]);
	elog(DEBUG5, "pg_proctab: cstime = %s", values[i_cstime]);
	elog(DEBUG5, "pg_proctab: priority = %s", values[i_priority]);
	elog(DEBUG5, "pg_proctab: nice = %s", values[i_nice]);
	elog(DEBUG5, "pg_proctab: num_threads = %s", values[i_num_threads]);
	elog(DEBUG5, "pg_proctab: itrealvalue = %s", values[i_itrealvalue]);
	elog(DEBUG5, "pg_proctab: starttime = %s", values[i_starttime]);
	elog(DEBUG5, "pg_proctab: vsize = %s", values[i_vsize]);
	elog(DEBUG5, "pg_proctab: rss = %s", values[i_rss]);
	elog(DEBUG5, "pg_proctab: exit_signal = %s", values[i_exit_signal]);
	elog(DEBUG5, "pg_proctab: processor = %s", values[i_processor]);
	elog(DEBUG5, "pg_proctab: rt_priority = %s", values[i_rt_priority]);
	elog(DEBUG5, "pg_proctab: policy = %s", values[i_policy]);
	elog(DEBUG5, "pg_proctab: delayacct_blkio_ticks = %s",
			values[i_delayacct_blkio_ticks]);

	return 1;
}
