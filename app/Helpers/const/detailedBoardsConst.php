<?php
const BOARD_HARD_OK = 'OK';
const BOARD_HARD_UNKNOWN = 'Unknown';
const BOARD_HARD_NG = 'NG';

const BOARD_ADJUST_OK = 'OK';
const BOARD_ADJUST_NONE = 'None';
const BOARD_ADJUST_UNKNOWN = 'Unknown';
const BOARD_ADJUST_NG = 'NG';

const BOARD_SHIP_YET = 'Yet';
const BOARD_SHIP_USE = 'Use';
const BOARD_SHIP_DONE = 'Done';

const BOARD_HARD_STATE_ARRAY = array(
    BOARD_HARD_OK  => 'OK',
    BOARD_HARD_UNKNOWN => '不明',
    BOARD_HARD_NG => 'NG',
);

const BOARD_ADJUST_STATE_ARRAY = array(
    BOARD_ADJUST_OK  => 'OK',
    BOARD_ADJUST_NONE  => 'なし',
    BOARD_ADJUST_UNKNOWN => '不明',
    BOARD_ADJUST_NG => 'NG',
);

const BOARD_SHIP_STATE_ARRAY = array(
    BOARD_SHIP_YET  => 'していない',
    BOARD_SHIP_USE => '社内用',
    BOARD_SHIP_DONE => 'した',
);