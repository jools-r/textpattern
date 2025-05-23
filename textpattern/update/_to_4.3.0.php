<?php

/*
 * Textpattern Content Management System
 * https://textpattern.com/
 *
 * Copyright (C) 2025 The Textpattern Development Team
 *
 * This file is part of Textpattern.
 *
 * Textpattern is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation, version 2.
 *
 * Textpattern is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Textpattern. If not, see <https://www.gnu.org/licenses/>.
 */

if (!defined('TXP_UPDATE')) {
    exit("Nothing here. You can't access this file directly.");
}

$rs = getRows("SELECT name, css FROM `" . PFX . "txp_css`");
foreach ($rs as $row) {
    if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $row['css'])) {
        // Data is still base64 encoded.
        safe_update('txp_css', "css = '" . doSlash(base64_decode($row['css'])) . "'", "name = '" . doSlash($row['name']) . "'");
    }
}

// Add column for file title.
$cols = getThings("DESCRIBE `" . PFX . "txp_file`");

if (!in_array('title', $cols)) {
    safe_alter('txp_file', "ADD title VARCHAR(255) NULL AFTER filename");
}
