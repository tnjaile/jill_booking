<?php
namespace jill_calendar;

/**
 *
 * @authors Your Name (you@example.org)
 * @date    2015-07-14 16:18:40
 * @version $Id$
 */

class weekCalendar
{
    public function __construct($date_string = null)
    {
        $this->setDate($date_string);
    }
    /**
     * Sets the date for the calendar
     *
     * @param null|string $date_string Date string parsed by strtotime for the calendar date. If null set to current timestamp.
     */
    public function setDate($date_string = null)
    {

        if ($this->is_date() == 1) {
            $this->now = getdate(strtotime($date_string));
        } else {
            $this->now = getdate();
        }
    }
    //檢查日期格式
    public function is_date($date)
    {
        if (preg_match("/^[12][0-9]{3}-([0][1-9])|([1][12])-([0][1-9])|([12][0-9])|([3][01])$/", $date)) {
            return 1;
        } else {
            return 0;
        }
    }

}
