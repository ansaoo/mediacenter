<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 12/04/18
 * Time: 23:08
 */

namespace App\Controller;


use Elasticsearch\ClientBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EsController extends Controller
{
    public function health(Request $request)
    {
        $client = ClientBuilder::create()
            ->setHosts(array($this->getParameter('es_url')))
            ->build();
        $params = array(
            'index' => '_cat',
            'type' => 'health',
            'body' => array()
        );
        $response = $client->search($params);
//        if ($response) {
//            $response = explode(' ', $response);
//        }
        return $response;
    }

    public function index(Request $request)
    {
        $client = ClientBuilder::create()
            ->setHosts(array($this->getParameter('es_url')))
            ->build();
        $body = [
            "size" => 0,
            "query"=> [
                "match"=> [
                    "attr"=> "fuel"
                ]
            ],
            "aggs" => [
                "groupBy_voiture" => [
                    "terms" => [
                        "field" => "voiture.keyword"
                    ],
                    "aggs" => [
                        "kilometre" => [
                            "sum" => [
                                "field" => "kilometre"
                            ]
                        ],
                        "compteur" => [
                            "max" => [
                                "field" => "compteur"
                            ]
                        ],
                        "litre" => [
                            "sum" => [
                                "field" => "litre"
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $params = array(
            'index' => 'car',
            'type' => '_doc',
            'body' => $body
        );
        $response = $client->search($params);
        return $this->json($response['aggregations']['groupBy_voiture']['buckets']);
    }

    public function histo(Request $request)
    {
        $client = ClientBuilder::create()
            ->setHosts(array($this->getParameter('es_url')))
            ->build();
        $body = [
            "size"=> 0,
            "query"=> [
                "match"=> [
                    "attr"=> "fuel"
                ]
            ],
            "aggs"=> [
                "groupBy_date"=> [
                    "date_histogram"=> [
                        "field"=> "eventDate",
                        "interval"=> "month"
                    ],
                    "aggs"=> [
                        "groupBy_voiture"=> [
                            "terms"=> [
                                "field"=> "voiture.keyword"
                            ],
                            "aggs"=> [
                                "kilometer"=> [
                                    "sum"=> [
                                        "field"=> "kilometre"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $params = array(
            'index' => 'car',
            'type' => '_doc',
            'body' => $body
        );
        $response = $client->search($params);
        $result = array();
        foreach ($response['aggregations']['groupBy_date']['buckets'] as $value) {
            $temp = array(
                'date' => substr($value['key_as_string'],0,10),
                'celica' => 0,
                'laguna' => 0
            );
            $temp[$value['groupBy_voiture']['buckets'][0]['key']] =
                $value['groupBy_voiture']['buckets'][0]['kilometer']['value'];
            if (isset($value['groupBy_voiture']['buckets'][1]['key'])) {
                $temp[$value['groupBy_voiture']['buckets'][1]['key']] =
                    $value['groupBy_voiture']['buckets'][1]['kilometer']['value'];
            }
            $result[] = $temp;
        }
        return $this->json($result);
    }

    public function recent(Request $request, $index)
    {
        $client = ClientBuilder::create()
            ->setHosts(array($this->getParameter('es_url')))
            ->build();
        $body = [
            "size"=> 5,
            "query"=> [
                "range"=> [
                    "eventDate"=> [
                        "lt"=> "now"
                    ]
                ]
            ],
            "sort" => [
                [
                    "eventDate" => [
                        "order" => "desc"
                    ]
                ]
            ]
        ];
        $params = array(
            'index' => $index,
            'type' => '_doc',
            'body' => $body
        );
        $response = $client->search($params);
        $result = array();
        foreach ($response['hits']['hits'] ?? array() as $doc) {
            if ($doc['_source']['status'] ?? false) {
                $result[] = array(
                    'src'=> $doc['_source']['fileName']. '_thumb.jpg',
                    'srct'=> $doc['_source']['fileName']. '_thumb.jpg',
                    'title'=> $doc['_source']['fileName'],
                );
            }
        }
        return $this->json($result);
    }

    public function search(Request $request, $index)
    {
        $client = ClientBuilder::create()
            ->setHosts(array($this->getParameter('es_url')))
            ->build();
        $body = [
            "size"=> 500,
            "query"=> [
                "range"=> [
                    "eventDate"=> [
                        "gte"=> $request->query->get('gte') ?? 'now',
                        "lt"=> $request->query->get('lt') ?? 'now'
                    ]
                ]
            ],
            "sort" => [
                [
                    "eventDate" => [
                        "order" => "asc"
                    ]
                ]
            ]
        ];
        $params = array(
            'index' => $index,
            'type' => '_doc',
            'body' => $body
        );
        $response = $client->search($params);
        $result = array();
        foreach ($response['hits']['hits'] ?? array() as $doc) {
            if ($doc['_source']['status'] ?? false) {
                $result[] = array(
                    'src'=> $doc['_source']['fileName']. '_thumb.jpg',
                    'srct'=> $doc['_source']['fileName']. '_thumb.jpg',
                    'title'=> $doc['_source']['fileName'],
                );
            }
        }
        return $this->json($result);
    }

    public function data(Request $request, $index, $_type)
    {
        $client = ClientBuilder::create()
            ->setHosts(array($this->getParameter('es_url')))
            ->build();
        $body = [
            "size" => 1000,
            "query"=> [
                "match"=> [
                    "attr"=> $_type
                ]
            ],
            "sort" => [
                [
                    "eventDate" => [
                        "order" => "desc"
                    ]
                ]
            ]
        ];
        $params = array(
            'index' => $index,
            'type' => '_doc',
            'body' => $body
        );
        $response = $client->search($params);
        return $this->json($response['hits']['hits'] ?? null);
    }

    public function delete(Request $request, $index, $_id)
    {
        $client = ClientBuilder::create()
            ->setHosts(array($this->getParameter('es_url')))
            ->build();
        $params = array(
            'index' => $index,
            'type' => '_doc',
            'id' => md5("jpeg$_id"),
            'body' => [
                "doc"=> [
                    "status"=> false,
                    "removedBy"=> $request->headers->get('profile') ?? 'test'
                ]
            ]
        );
        $response = $client->update($params);
        if ($response['result'] == 'updated') {
            return $this->json('Updated successfully');
        }
        return $this->json('No update');
    }

    public function load(Request $request, $index, $filename)
    {
        $client = ClientBuilder::create()
            ->setHosts(array($this->getParameter('es_url')))
            ->build();
        $params = array(
            'index' => $index,
            'type' => '_doc',
            'id' => md5("jpeg$filename"),
            'body' => [
                "doc"=> [
                    "status"=> false,
                    "removedBy"=> $request->headers->get('profile') ?? 'test'
                ]
            ]
        );
        $response = $client->index($params);
        return $this->json('No update');
    }

    public function size(Request $request, $index)
    {
        $client = ClientBuilder::create()
            ->setHosts(array($this->getParameter('es_url')))
            ->build();
        $params = array(
            'index' => $index,
            'type' => '_doc',
            'body' => [
                "size"=> 0,
                "query"=> [
                    "match"=> [
                        "status"=> true
                    ]
                ],
                "aggs"=> [
                    "total_size"=> [
                        "sum"=> [
                            "field"=> "fileSize"
                        ]
                    ]
                ]
            ]
        );
        $response = $client->search($params);
        $tot = $response['hits']['total'] ?? 0;
        $size = $response['aggregations']['total_size']['value'] ?? 0;
        return $this->json(array(
            'total'=> $tot,
            'size'=> round($size/1024/1024, 1)
        ));
    }
}