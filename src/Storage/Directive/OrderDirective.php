<?php

declare(strict_types=1);

namespace Bolt\Storage\Directive;

use Bolt\Storage\QueryInterface;

/**
 *  Directive to alter query based on 'order' parameter.
 *
 *  eg: 'pages', ['order'=>'-publishedAt']
 */
class OrderDirective
{
    public function __invoke(QueryInterface $query, string $order): void
    {
        if ($order === '') {
            return;
        }

        // remove default order
        $query->getQueryBuilder()->resetDQLPart('orderBy');

        $separatedOrders = $this->getOrderBys($order);

        foreach ($separatedOrders as $order) {
            [ $order, $direction ] = $this->createSortBy($order);

            if (in_array($order, $query->getCoreFields(), true)) {
                $query->getQueryBuilder()->addOrderBy('content.' . $order, $direction);
            } elseif ($order === 'author') {
                $query
                    ->getQueryBuilder()
                    ->leftJoin('content.author', 'user')
                    ->addOrderBy('user.username', $direction);
            } else {
                if (! $this->isActualField($query, $order)) {
                    dump("A query with ordering on a Field (`${order}`) that's not defined, will yield unexpected results. Update your `{% setcontent %}`-statement");
                }
                $fieldsAlias = 'fields_order_' .  $query->getIndex();
                $fieldAlias = 'order_' . $query->getIndex();
                $translationsAlias = 'translations_order_' . $query->getIndex();
                $query
                    ->getQueryBuilder()
                    ->leftJoin('content.fields', $fieldsAlias)
                    ->leftJoin($fieldsAlias . '.translations', $translationsAlias)
                    ->andWhere($fieldsAlias . '.name = :' . $fieldAlias)
                    ->addOrderBy($translationsAlias . '.value', $direction)
                    ->setParameter($fieldAlias, $order);

                $query->incrementIndex();
            }
        }
    }

    /**
     * Cobble together the sorting order, and whether or not it's a column in `content` or `fields`.
     */
    private function createSortBy(string $order): array
    {
        if (mb_strpos($order, '-') === 0) {
            $direction = 'DESC';
            $order = mb_substr($order, 1);
        } elseif (mb_strpos($order, ' DESC') !== false) {
            $direction = 'DESC';
            $order = str_replace(' DESC', '', $order);
        } else {
            $order = str_replace(' ASC', '', $order);
            $direction = 'ASC';
        }

        return [$order, $direction];
    }

    protected function getOrderBys(string $order): array
    {
        $separatedOrders = [$order];

        if ($this->isMultiOrderQuery($order)) {
            $separatedOrders = explode(',', $order);
        }

        return $separatedOrders;
    }

    protected function isMultiOrderQuery(string $order): bool
    {
        return mb_strpos($order, ',') !== false;
    }

    protected function isActualField(QueryInterface $query, string $name): bool
    {
        $contentType = $query->getConfig()->get('contenttypes/' . $query->getContentType());

        return in_array($name, $contentType->get('fields')->keys()->all(), true);
    }
}
