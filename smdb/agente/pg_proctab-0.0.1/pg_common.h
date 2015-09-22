/*
 * Copyright (C) 2008 Mark Wong
 */

#ifndef _PG_COMMON_H_
#define _PG_COMMON_H_

#ifdef PG_MODULE_MAGIC
PG_MODULE_MAGIC;
#endif

#define BIGINT_LEN 20
#define FLOAT_LEN 20
#define INTEGER_LEN 10

#ifdef __linux__
#include <ctype.h>
#include <linux/magic.h>

#define PROCFS "/proc"

#define GET_NEXT_VALUE(p, q, value, length, msg, delim) \
        if ((q = strchr(p, delim)) == NULL) \
        { \
            elog(ERROR, msg); \
            return 0; \
        } \
        length = q - p; \
        strncpy(value, p, length); \
        value[length] = '\0'; \
        p = q + 1;

#define SKIP_TOKEN(p) \
		/* Skipping leading white space. */ \
		while (isspace(*p)) \
			p++; \
		/* Skip token. */ \
		while (*p && !isspace(*p)) \
			p++; \
		/* Skipping trailing white space. */ \
		while (isspace(*p)) \
			p++;
#endif /* __linux__ */

#endif /* _PG_COMMON_H_ */
