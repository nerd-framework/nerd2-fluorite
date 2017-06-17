<?php
/**
 * @author Roman Gemini <roman_gemini@ukr.net>
 * @date 25.03.2016
 * @time 16:59
 */

namespace Nerd2\Fluorite;

class QueryCloak
{
    /**
     * @var string
     */
    private $sql;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @var int
     */
    private $nextId = 0;

    /**
     * @var string
     */
    private $parameterPrefix;

    /**
     * QueryCloak constructor.
     * @param string $sql
     * @param array $attributes
     * @param string $parameterPrefix
     */
    public function __construct($sql = "", array $attributes = [], $parameterPrefix = 'prm')
    {
        $this->sql = $sql;
        $this->attributes = $attributes;
        $this->parameterPrefix = $parameterPrefix;
    }

    /**
     * @return string
     */
    public function getNextParameterName()
    {
        return sprintf(":%s%d", $this->parameterPrefix, $this->nextId);
    }

    /**
     * @param string $sql
     * @return QueryCloak
     */
    public function setSql($sql)
    {
        $this->sql = $sql;

        return $this;
    }

    /**
     * @param string $sql
     * @return $this
     */
    public function appendSql($sql)
    {
        $this->sql .= $sql;

        return $this;
    }

    /**
     * @param array $attributes
     * @return QueryCloak
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @param mixed $value
     * @param null|string $key
     * @return $this
     */
    public function putValue($value, $key = null)
    {
        if (is_null($key)) {
            $this->attributes[] = $value;
        } else {
            $this->attributes[$key] = $value;
        }

        $this->nextId ++;

        return $this;
    }

    /**
     * @return string
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return $this
     */
    public function reset()
    {
        $this->sql = "";
        $this->attributes = [];

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) sprintf(
            "QueryCloak(query=%s, data=%s)",
            $this->sql,
            json_encode($this->attributes, JSON_UNESCAPED_UNICODE)
        );
    }
}
