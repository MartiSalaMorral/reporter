<?php

namespace BadChoice\Reports;

use BadChoice\Reports\Exporters\FakeExporter;
use BadChoice\Reports\Exporters\HtmlExporter;
use BadChoice\Reports\Exporters\CsvExporter;
use BadChoice\Reports\Exporters\XlsExporter;
use BadChoice\Reports\Filters\Filters;

abstract class ReportExporter
{
    protected $filters;

    abstract protected function getFields();

    public function __construct($filters = null)
    {
        $this->setFilters($filters);
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
        return $this;
    }

    public function getFilter($key)
    {
        if (! $this->filters || ! isset($this->filters->filters()[$key])) {
            return Filters::find($key);
        }
        return $this->filters->filters()[$key];
    }

    public function toHtml($collection)
    {
        return (new HtmlExporter($this->fields(), $collection))->export()->print();
    }

    public function toXls($collection)
    {
        return (new XlsExporter($this->fields(), $collection))->export();
    }

    public function toCsv($collection)
    {
        return (new CsvExporter($this->fields(), $collection))->export();
    }

    public function toFake($collection)
    {
        return (new FakeExporter($this->fields(), $collection))->export();
    }

    protected function fields()
    {
        return $this->getFields();
    }
}
