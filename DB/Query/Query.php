<?php

/**
 * Database Query Interface | DB\Query\Query.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      https://creativecommons.org/licenses/by-sa/4.0 Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 */
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

    const SELECT = 'SELECT';

    // SQL Statement Reserved Keywords

    /**
     * Wildcard
     *
     * @var string
     */
    const WILDCARD = '*';

    /**
     * Alias
     *
     * @var string
     */
    const ALIAS = 'AS';

    /**
     * Distinct Clause
     *
     * @var string
     */
    const DISTINCT = 'DISTINCT';

    /**
     * WHERE Clause
     *
     * @var string
     */
    const WHERE = 'WHERE';

    /**
     * AND Clause Connector
     *
     * @var string
     */
    const SQL_AND = 'AND';

    /**
     * OR Clause Connector
     *
     * @var string
     */
    const SQL_OR = 'OR';

    /**
     * GROUP BY Clause
     *
     * @var string
     */
    const GROUP_BY = 'GROUP BY';

    /**
     * ORDER BY Clause
     *
     * @var string
     */
    const ORDER_BY = 'ORDER BY';

    /**
     * Ascending Order
     *
     * @var string
     */
    const ORDER_ASCENDING = 'ASC';

    /**
     * Descending Order
     *
     * @var string
     */
    const ORDER_DESCENDING = 'DESC';

    /**
     * LIMIT Clause
     *
     * @var string
     */
    const LIMIT = 'LIMIT';

    /**
     * HAVING Clause
     *
     * @var string
     */
    const HAVING = 'HAVING';

    /**
     * UNION Clause
     *
     * @var string
     */
    const UNION = 'UNION';

    /**
     * JOIN Types
     *
     * @var string
     */
    const INNER_JOIN = 'INNER JOIN';
    const LEFT_OUTER_JOIN = 'LEFT OUTER JOIN';
    const RIGHT_OUTER_JOIN = 'RIGHT OUTER JOIN';
}
