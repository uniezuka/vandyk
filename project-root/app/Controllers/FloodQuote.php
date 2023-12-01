<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class FloodQuote extends BaseController
{
    protected $pager;
    protected $floodQuoteService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->floodQuoteService = service('floodQuoteService');
    }

    public function index()
    {
        helper('form');

        $page  = (int) ($this->request->getGet('page') ?? 1);
        $quotes = $this->floodQuoteService->getPaged($page);
        $pager_links = $this->pager->makeLinks($page, $quotes->limit, $quotes->total, 'bootstrap_full');

        $data['flood_quotes'] = $quotes->data;
        $data['title'] = "Flood Quotes";
        $data['pager_links'] = $pager_links;
        return view('FloodQuote/index_view', ['data' => $data]);
    }

    public function create()
    {
        $data['title'] = "Create Flood Quote";
        return view('FloodQuote/create_view', ['data' => $data]);
    }

    public function update()
    {
        $data['title'] = "Update Flood Quote";
        return view('FloodQuote/update_view', ['data' => $data]);
    }
}
