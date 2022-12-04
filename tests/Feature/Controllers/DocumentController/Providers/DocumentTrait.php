<?php

namespace Tests\Feature\Controllers\DocumentController\Providers;

trait DocumentTrait
{
    public function requestOptions()
    {
        return [
            'É enviado uma lista com os ids dos votantes sem paginacao' => [
                'input' => [
                    'type' => 'successfulRequest',
                    'event_id' => 7747,
                    'params' => [
                        'voter_ids' => [
                            5444527,
                            5444852,
                            5445172,
                            5445307,
                        ],
                    ],
                ],
                'expected_output' => [
                    'status_code' => 200,
                    'payload_structure' => [
                        'success',
                        'actual_page',
                        'total_pages',
                        'data' => [
                            '*' => [
                                'name',
                                'document_url'
                            ],
                        ],
                    ],
                ],
            ],
            'É enviado uma lista com os ids dos votantes com paginacao' => [
                'input' => [
                    'type' => 'successfulRequest',
                    'event_id' => 7747,
                    'params' => [
                        'voter_ids' => [
                            5444527,
                            5444852,
                            5445172,
                            5445307,
                        ],
                        'page' => 1,
                        'per_page' => 1
                    ],
                ],
                'expected_output' => [
                    'status_code' => 200,
                    'payload_structure' => [
                        'success',
                        'actual_page',
                        'total_pages',
                        'data' => [
                            '*' => [
                                'name',
                                'document_url'
                            ],
                        ],
                    ],
                ],
            ],
            'É enviado uma lista com ids invalidos dos votantes com paginacao' => [
                'input' => [
                    'type' => 'successfulRequest',
                    'event_id' => 7747,
                    'params' => [
                        'voter_ids' => [
                            123,
                        ],
                        'page' => 1,
                        'per_page' => 1
                    ],
                ],
                'expected_output' => [
                    'status_code' => 200,
                    'payload_structure' => [
                        'success',
                        'actual_page',
                        'total_pages',
                        'data',
                    ],
                ],
            ],
            'É enviado uma lista vazia com os ids dos votantes sem paginacao' => [
                'input' => [
                    'type' => 'successfulRequest',
                    'event_id' => 7747,
                    'params' => [
                        'voter_ids' => [],
                    ],
                ],
                'expected_output' => [
                    'status_code' => 400,
                    'payload_structure' => [
                        'success',
                        'errors'
                    ],
                ],
            ],
            'É enviado uma lista de ids dos votantes com paginacao invalida' => [
                'input' => [
                    'type' => 'successfulRequest',
                    'event_id' => 7747,
                    'params' => [
                        'voter_ids' => [
                            123456,
                            1234567,
                            1234354,
                        ],
                        'page' => -1,
                        'per_page' => -1
                    ],
                ],
                'expected_output' => [
                    'status_code' => 400,
                    'payload_structure' => [
                        'success',
                        'errors'
                    ],
                ],
            ],
        ];
    }

    public function downloadDocument()
    {
        return [
            'É retornado um arquivo para download' => [
                'input' => [
                    'event_id' => 7747,
                    'voter_id' => 5444527,
                    'data' => [
                        'nome' => 'Nome Ficticio'
                    ],
                ],
                'expected_output' => [
                    'status_code' => 200,
                    'filename' => "5444527-7747.pdf"
                ],
            ],
            'Não é retornado um arquivo para download pois está faltando um dado' => [
                'input' => [
                    'event_id' => 7747,
                    'voter_id' => 5444527,
                ],
                'expected_output' => [
                    'status_code' => 400,
                ],
            ],
        ];
    }
}
