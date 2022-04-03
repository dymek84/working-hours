<?php

namespace App\Timei;

class Timei
{
    private  $firstDateTime;
    private  $secondDateTime;

    public function Timei(int $start, int $end)
    {
        $firstDateTime = date("Y-m-d", strtotime($start));
        $secondDateTime = date("Y-m-d", strtotime($end));
    }

    public function Timedi()
    {
        $this->current->subtract($this->period->getDateInterval());
    }

    public function current()
    {
        return clone $this->current;
    }

    public function key()
    {
        return $this->current->diff($this->period->getStartDate());
    }

    public function next(): void
    {
        $this->current->add($this->period->getDateInterval());
    }

    /**
     * valid
     *
     * @return void
     */
    public function valid(): bool
    {
        return $this->current < $this->endDate;
    }

    public function extend()
    {
        $this->endDate->add($this->period->getDateInterval());
    }

    public function isSaturday()
    {
        return $this->current->format('N') == 6;
    }

    public function isSunday()
    {
        return $this->current->format('N') == 7;
    }

    public function isWeekend()
    {
        return ($this->isSunday() || $this->isSaturday());
    }
}
