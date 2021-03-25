<?php

namespace App\View\Components\Table;

use Illuminate\View\Component;

class SortableField extends Component
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $field;

    /**
     * @var string
     */
    public $order;

    /**
     * @var string
     */
    public $sort;

    /**
     * Create a new component instance.
     *
     * @param $title
     * @param $field
     */
    public function __construct($title, $field)
    {
        $this->title = $title;
        $this->field = $field;

        $this->sort = request('sort', 'created_at');
        $this->order = request('order', 'desc');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.table.sortable-field');
    }

    public function url(): string
    {
        $params = array_merge(
            request()->except('page'),
            [
                'sort' => $this->field,
                'order' => $this->sort == $this->field && $this->order == 'desc' ? 'asc' : 'desc'
            ]
        );

        return url()->current() . '?' . http_build_query($params);
    }
}
