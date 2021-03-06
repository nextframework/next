<?php

/**
 * Database Query Interface | DB\Query\Query.php
 *
 * @author       Bruno Augusto
 *
 * @copyright    Copyright (c) 2017 Next Studios
 * @license      http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License 3.0
 */
namespace Next\DB\Query;

/**
 * The Query Interface provides some immutable SQL-related keywords to be used
 * instead of have the developer to manually enter them resulting in
 * possible comparison failures
 *
 * @package    Next\DB
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
