<?php

namespace Next\Validate\HTTP\Headers;

use Next\Validate\Validatable;     # Validatable Interface
use Next\Validate\HTTP\HTTP;       # HTTP Protocol Interface

/**
 * HTTP Headers Validation Interface
 *
 * @author        Bruno Augusto
 *
 * @copyright     Copyright (c) 2010 Next Studios
 * @license       http://creativecommons.org/licenses/by/3.0/   Attribution 3.0 Unported
 */
interface Headers extends Validatable, HTTP {

    /**
     * Quality Factor Regular Expression
     *
     * @var string
     */
    const FLOAT = '(?:1(?:\.0)?|0                  # 1 (or 1.0) or 0
                        (?:\.                      # Literal dot
                          (?:[0-9]{1,2}[1-9]|      # One or Two Zeroes followed by 1...9                    -OR-
                           [0-9][1-9][0-9]?|       # Zero followed by 1...9, followed by an optional 0...9  -OR-
                           [1-9]                   # 1...9 followed by:
                              (?:[0-9]{0,2}|       # None and up to Two 0...9                               -OR-
                               [0-9][1-9]|         # 0...9 and then followed by 1...9                       -OR-
                               [1-9]{1,2}          # 1...9 once or twice
                              )
                          )
                      )?                           # All this is optional, and refers to float range
                   )';

    /**
     * Tokens
     *
     * <p>
     *     There aren't rules for them, so we'll accept a generic format
     *     with all Graphical Characters as shown in RFC 2616 Section 3,
     *     Sub-section 3.8
     * </p>
     *
     * <p>
     *     But we will deny the '=' (equal sign) because this is the character
     *     which separates the token name from its value and ';' (semi-colon),
     *     because is the character which separates multiples tokens in a
     *     string
     * </p>
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec3.html#sec3.8
     *
     * @var string
     */
    const TOKEN = '[^ =;\t\n\r\f\v]+';

    /**
     * Absolute URI Definition
     *
     * <p>
     *     HTTP or FTP Protocols, with or without SSL Character followed by
     *     <strong>://</strong> (everything optional).
     * </p>
     *
     * <p>
     *     Then followed by one or more alphanumeric characters and/or
     *     special chars
     * </p>
     *
     * @var string
     */
    const ABSOLUTE_URI = '(?<absolute>(?:(?:(?:http|ftp)s?):\/\/)?[\w\#:.?+=&%@!\/-]+)';

    /**
     * Relative URI Definition
     *
     * <p>
     *     An optional slash followed by one or more alphanumeric characters
     *     and/or sepcial chars
     * </p>
     *
     * <p>Special Chars are: :.?+=&%@!\/-</p>
     *
     * @var string
     */
    const RELATIVE_URI = '(?<relative>\/[\w\#!:.?+=&%@\/-]+)';
}
