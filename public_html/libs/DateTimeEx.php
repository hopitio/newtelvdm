<?php

class DateTimeEx extends DateTime
{

    /** @return string Y-m-d H:i */
    function toIsoString()
    {
        return $this->format('Y-m-d H:i');
    }

    /** @return string d/m/Y */
    function toShortdateString()
    {
        return $this->format('d/m/Y');
    }

    /** @return string H:i */
    function toTimeString()
    {
        return $this->format('H:i');
    }

    /** @return string d/m/Y */
    function toFulldateFtring()
    {
        return $this->format('d/m/Y H:i');
    }

    /**
     * Cộng/trừ ngày
     * @param int $int
     * @return static
     */
    function addDay($int)
    {
        $int = (int) $int;
        if ($int > 0)
        {
            return $this->add(new DateInterval("P{$int}D"));
        }
        else
        {
            $int = -$int;
            return $this->sub(new DateInterval("P{$int}D"));
        }
    }

    /**
     * Cộng/trừ tháng
     * @param int $int
     * @return static
     */
    function addMonth($int)
    {
        $int = (int) $int;
        if ($int > 0)
        {
            return $this->add(new DateInterval("P{$int}M"));
        }
        else
        {
            $int = -$int;
            return $this->sub(new DateInterval("P{$int}M"));
        }
    }

    /**
     * Cộng/trừ năm
     * @param int $int
     * @return static
     */
    function addYear($int)
    {
        $int = (int) $int;
        if ($int > 0)
        {
            return $this->add(new DateInterval("P{$int}Y"));
        }
        else
        {
            $int = -$int;
            return $this->sub(new DateInterval("P{$int}Y"));
        }
    }

    /**
     * Cộng/trừ giây
     * @param int $int
     * @return static
     */
    function addSecond($int)
    {
        $int = (int) $int;
        if ($int > 0)
        {
            return $this->add(new DateInterval("PT{$int}S"));
        }
        else
        {
            $int = -$int;
            return $this->sub(new DateInterval("PT{$int}S"));
        }
    }

    /**
     * Cộng/trừ giờ
     * @param int $int
     * @return static
     */
    function addHour($int)
    {
        $int = (int) $int;
        if ($int > 0)
        {
            return $this->add(new DateInterval("PT{$int}H"));
        }
        else
        {
            $int = -$int;
            return $this->sub(new DateInterval("PT{$int}H"));
        }
    }

    /**
     * Cộng/trừ phút
     * @param int $int
     * @return static
     */
    function addMinute($int)
    {
        $int = (int) $int;
        if ($int > 0)
        {
            return $this->add(new DateInterval("PT{$int}M"));
        }
        else
        {
            $int = -$int;
            return $this->sub(new DateInterval("PT{$int}M"));
        }
    }

    /**
     * 
     * @param type $format
     * @param type $time
     * @param type $object
     * @return static
     */
    static function createFromFormat($format, $time, $object = null)
    {
        $object = $object ? $object : new DateTimeZone(date_default_timezone_get());
        $date = parent::createFromFormat($format, $time, $object);
        return $date ? new static($date->format('Y-m-d H:i')) : false;
    }

    /**
     * 
     * @param string $time d/m/Y | d-m-Y
     * @return static
     */
    static function createFrom_dmY($time)
    {
        $time = str_replace('-', '/', $time);
        $date = parent::createFromFormat('d/m/Y', $time);
        return $date ? new static($date->format('Y-m-d H:i')) : false;
    }

    /**
     * 
     * @param string $time d/m/Y H:i | d-m-Y H:i
     * @return static
     */
    static function createFrom_dmY_Hi($time)
    {
        $time = str_replace('-', '/', $time);
        $date = parent::createFromFormat('d/m/Y H:i', $time);
        return $date ? new static($date->format('Y-m-d H:i')) : false;
    }

    /**
     * 
     * @param string $time
     * @return static
     */
    static function create($time = null)
    {
        $time = $time == '0000-00-00 00:00:00' ? null : $time;
        return new static($time);
    }

}
