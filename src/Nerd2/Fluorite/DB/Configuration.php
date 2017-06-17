<?php

namespace Nerd2\Fluorite\DB;


interface Configuration
{
    /**
     * The Data Source Name, or DSN, contains the information required to connect to the database.
     *
     * @return string
     */
    public function getDsn();

    /**
     * The user name for the DSN string.
     *
     * @return string|null
     */
    public function getUsername();

    /**
     * The password for the DSN string.
     *
     * @return string|null
     */
    public function getPassword();

    /**
     * Array of driver-specific connection options.
     *
     * @return array
     */
    public function getOptions();
}