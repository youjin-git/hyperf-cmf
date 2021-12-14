<?php


namespace Yj\Tool;


use Hyperf\Config\Annotation\Value;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Collection;

class Page
{

    protected $page = 1;

    protected $count = null;

    protected $data = [];

    /**
     * @return Collection
     */
    public function __invoke($data, int $page = null)
    {

        $this->setCurrentPage($page);
        $this->setData($data);
        return $this->Paginator();
    }

    public function setData($data)
    {
        $data = _Collect($data);
        $this->count = $data->count();
        $this->data = $data;
    }

    public function setCurrentPage($page)
    {
        $this->page = is_null($page) ? di()->get(RequestInterface::class)->input('page', 1) : $page;
    }

    /**
     * @Value("page.pageSize")
     */
    protected $pageSize;

    public function getPageSize()
    {
        return di()->get(RequestInterface::class)->input('pageSize', $this->pageSize);
    }


    /**
     * @Value("page.pre_page")
     */
    protected $pre_page;


    /**
     * @return Collection
     */
    public function Paginator()
    {
        return make(LengthAwarePaginatorInterface::class,
            [
                $this->data->forPage($this->page, $this->getPageSize())->values(),
                $this->count,
                $this->getPageSize(),
                $this->page
            ]);
    }
}