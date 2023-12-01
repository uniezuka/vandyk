<?php
function getFloodQuoteMetas($flood_quote_id)
{
    $service = service('floodQuoteService');

    return $service->getFloodQuoteMetas($flood_quote_id);
}

function getFloodQuoteMetaValue($flood_quote_id, $meta_key)
{
    $service = service('floodQuoteService');

    return $service->getFloodQuoteMetaValue($flood_quote_id, $meta_key);
}

function getBatchedFloodQuoteMetas($flood_quote_ids)
{
    $service = service('floodQuoteService');

    return $service->getBatchedFloodQuoteMetas($flood_quote_ids);
}
