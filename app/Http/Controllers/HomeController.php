<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HomeController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('csv-form');
    }

    /**
     * @param string $filename
     * @param string $delimiter
     * @return array|false
     */
    function csvToArray(string $filename = '', string $delimiter = ','): bool|array
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = [];
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $row[5] = 'error';
                    $header = $row;
                } else {
                    $row[5] = 0;
                    // check error in email, first, last (col)
                    if ($row[0] == '' || $row[2] == '' || $row[3] == '') {
                        $row[5] = 1;
                    }

                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }

    /**
     * @return void
     */
    public function getEventStreamServerSentEvent(): void
    {
        $file = public_path('../dump/user_import.csv');
        $users = $this->csvToArray($file);

        $response = new StreamedResponse();
        $response->setCallback(function () use ($users) {
            $this->pushRecordAsEvent($users);
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cach-Control', 'no-cache');
        $response->send();
    }

    /**
     * @param $records
     * @return void
     */
    public function pushRecordAsEvent($records): void
    {
        foreach ($records as $key => $user) {
            echo 'data: ' . json_encode($user) . "\n\n";
            ob_flush();
            flush();
            usleep(500000);
        }
    }

}
