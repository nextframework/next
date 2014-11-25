<?php

namespace Next\File\Tools\INI;

interface Descriptor {

    const COMMENT_INDEX                   = '__comments';

    const COMMENT_SYMBOL_SHARP            = '#';
    const COMMENT_SYMBOL_SEMICOLON        = ';';

    const SECTION_START                   = '[';
    const SECTION_END                     = ']';

    const HIERARCHICAL_ENTRY_DOT          = '.';
    const HIERARCHICAL_ENTRY_BACKSLASH    = '\\';
}