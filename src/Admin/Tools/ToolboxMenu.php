<?php

namespace Arbory\Base\Admin\Tools;

use Arbory\Base\Admin\Tools\ToolboxMenuItem;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Toolbox
 * @package Arbory\Base\Admin\Form\Fields
 */
class ToolboxMenu implements Renderable
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $items;

    /**
     * Toolbox constructor.
     * @param Model $model
     */
    public function __construct( Model $model )
    {
        $this->model = $model;
        $this->items = new Collection();
    }

    /**
     * @return Model
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * @return Collection
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * @param string $name
     * @param string $url
     * @return ToolboxMenuItem
     */
    public function add( $name, $url )
    {
        $item = new ToolboxMenuItem( $name, $url );

        $this->items()->push( $item );

        return $item;
    }

    /**
     * @return string
     */
    public function render()
    {
        $content = new Content();

        foreach( $this->items() as $item )
        {
            $link = Html::link( $item->getTitle() )
                ->addClass( 'button ' . $item->getClass() )
                ->addAttributes( [
                    'href' => $item->getUrl(),
                    'title' => $item->getTitle(),
                ] );

            $content->push( Html::li( $link ) );
        }

        return (string) $content;
    }
}
