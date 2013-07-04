<?php

/**
 * Szukane słowo w parametrze "s"
 */

function extractDescriptions(SimpleXMLElement $feed, $search = null)
{
    // Pierwsza myśl, ale nie działa dla zapytań z cudzysłowiami i bierze pod uwagę wielkość znaków
    // $items = $feed->xpath(sprintf('//item/description[text()[contains(., "%s")]]', $search));

    $items = array();

    foreach ($feed->channel->item as $item)
    {
        if (! $search OR stripos((string)$item->description, $search) !== false)
        {
            $items[] = (string)$item->description;
        }
    }

    return $items;
}


$ch = curl_init('http://xlab.pl/feed/');

curl_setopt($ch, CURLOPT_RETURNTRANSFER,    true);
curl_setopt($ch, CURLOPT_TIMEOUT,           10);

$data = curl_exec($ch);

if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200)
{
    try 
    {
        $feed = new SimpleXMLElement($data);

        $nodes = extractDescriptions($feed, $_GET['s']);

        foreach ($nodes as $node)
        {
            echo $node.'<hr>';
        }
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
}
else
{
    echo 'Błąd połączenia';
}

curl_close($ch);