<?php

namespace Next\DB\Query;

/**
 * Query Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Query {

    // SQL Statement Reserved Keywords

    /**
     * Wildcard
     *
     * @var string
     */
    const SQL_WILDCARD      = '*';

    /**
     * Alias
     *
     * @var string
     */
    const SQL_AS            = 'AS';

    /**
     * Distinct Clause
     *
     * @var string
     */
    const SQL_DISTINCT      = 'DISTINCT';

    /**
     * WHERE Clause
     *
     * @var string
     */
    const SQL_WHERE         = 'WHERE';

    /**
     * AND Clause Connector
     *
     * @var string
     */
    const SQL_AND           = 'AND';

    /**
     * OR Clause Connector
     *
     * @var string
     */
    const SQL_OR            = 'OR';

    /**
     * GROUP BY Clause
     *
     * @var string
     */
    const SQL_GROUP_BY      = 'GROUP BY';

    /**
     * ORDER BY Clause
     *
     * @var string
     */
    const SQL_ORDER_BY      = 'ORDER BY';

    /**
     * Ascending Order
     *
     * @var string
     */
    const SQL_ORDER_ASC     = 'ASC';

    /**
     * Descending Order
     *
     * @var string
     */
    const SQL_ORDER_DESC    = 'DESC';

    /**
     * LIMIT Clause
     *
     * @var string
     */
    const SQL_LIMIT         = 'LIMIT';

    /**
     * HAVING Clause
     *
     * @var string
     */
    const SQL_HAVING        = 'HAVING';

    /**
     * UNION Clause
     *
     * @var string
     */
    const SQL_UNION         = 'UNION';
}
