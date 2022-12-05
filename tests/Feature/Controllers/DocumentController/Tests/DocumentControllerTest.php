<?php

namespace Tests\Feature\Controllers\DocumentController\Tests;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Consumers\PanagoraConsumer;
use Illuminate\Support\Facades\Crypt;
use Mockery;
use Tests\Feature\Controllers\DocumentController\Providers\DocumentTrait;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use DocumentTrait;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test if the function can return a document list or a error response
     *
     * @dataProvider requestOptions
     */
    public function test_if_can_return_a_document_list($input, $expected_output)
    {
        /*
            This my be a little confusing to see what is happening,
            This line just calls a function based on it's type
            E.g. If it is a success test, his type is called successfulRequest
            So, the private function successfulRequest is called passing the desired params
        */
        $mock_function_type = (string) $input['type'];
        $this->$mock_function_type($input['params']['voter_ids']);

        $event_id = $input['event_id'];

        $response = $this->call('POST', "api/events/$event_id/documents/", $input['params']);

        $response->assertStatus($expected_output['status_code']);
        $response->assertJsonStructure($expected_output['payload_structure']);
    }

    /**
     * @dataProvider downloadDocument
     */
    public function test_if_can_successfully_download_a_content($input, $expected_output)
    {
        $event_id = $input['event_id'];
        $voter_id = $input['voter_id'];

        $encrypted_data = null;
        if (isset($input['data'])) {
            $encrypted_data = '?data=' . Crypt::encrypt($input['data']);
        }

        $response = $this->call('GET', "api/events/$event_id/documents/$voter_id/pdf" . $encrypted_data);
        $response->assertStatus($expected_output['status_code']);
        if ($expected_output['status_code'] == 200) {
            $response->assertDownload($expected_output['filename']);
        }
    }

    private function successfulRequest($voter_ids)
    {
        $desired_response = [];
        foreach ($voter_ids as $id) {
            if ($id != 123) {
                $desired_response[$id][] = (object)[
                    'nome' => 'Mocked name',
                    'id' => $id
                ];
            }
        }

        return $this->mockPanagoraConsumer($desired_response);
    }

    private function mockPanagoraConsumer($expected_response)
    {
        $mock = Mockery::mock(PanagoraConsumer::class);
        $mock->shouldReceive('makeConcurrentRequests')
            ->andReturnValues($expected_response);

        $this->app->instance(PanagoraConsumer::class, $mock);
    }

}
