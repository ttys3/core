<?php

namespace Bolt\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginatorExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('create_paginator', [$this, 'getPaginator']),
        ];
    }

    public function getPaginator(Paginator $paginator, int $currentPage, int $pageSize = 10): array
    {
        $hasPreviousPage = $currentPage > 1;
        $hasNextPage = ($currentPage * $pageSize) < $paginator->count();
//        die();
        return [
            'results' => $paginator,
            'currentPage' => $currentPage,
            'hasPreviousPage' => $hasPreviousPage,
            'hasNextPage' => $hasNextPage,
            'previousPage' => $hasPreviousPage ? $currentPage - 1 : null,
            'nextPage' => $hasNextPage ? $currentPage + 1 : null,
            'numPages' => (int) ceil($paginator->count() / $pageSize),
            'haveToPaginate' => $paginator->count() > $pageSize,
        ];
    }

}