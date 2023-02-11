<?php

namespace Botble\MarcoEvent\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\MarcoEvent\Http\Resources\MarcoEventResource;
use Botble\MarcoEvent\Repositories\Interfaces\MarcoEventInterface;
use Illuminate\Http\Request;
use Botble\MarcoEvent\Models\MarcoEvent;
use RvMedia;

class MarcoEventController extends Controller
{
    protected $marcoeventRepository;

    public function __construct(MarcoEventInterface $marcoeventRepository)
    {
        $this->marcoeventRepository = $marcoeventRepository;
    }

    public function findByEvents(Request $request, BaseHttpResponse $response) {
        $date_range = $request->input("date_range");
        $condition = ['status' => BaseStatusEnum::PUBLISHED];
        if($date_range) {
            $date_range_arr = explode(" to ", $date_range);
            $date_from = $date_range_arr[0];
            $date_to = $date_range_arr[1];
            $condition[] = ['event_date', '>=', $date_from];
            $condition[] = ['event_date', '<=', $date_to];
        }
        $rows = $this->marcoeventRepository
            ->advancedGet([
                'condition' => $condition,
                'paginate'  => [
                    'per_page'      => 9999,
                ],
                'order_by'  => ['event_date' => 'desc'],
            ]);
        $data = array();
        foreach ($rows as $row) {
            $image = $row->image ? RvMedia::url($row->image) : null;
            $text = $row->name.'<br>';
            if($image) $text .= '<img src="'.$image.'" /><br>';
            $text .= 'Actual: '.$row->actual.$row->type."<br>";
            $text .= 'Forecast: '.$row->forecast.$row->type."<br>";
            $text .= 'Previous: '.$row->previous.$row->type."<br>";

            $data[] = array(
                'id'        => $row->id,
                'date'      => $row->event_date,
                'time'      => date("H:i", strtotime($row->event_date." ".$row->event_time)),
                'timestamp' => strtotime($row->event_date." ".$row->event_time)*1000,
                'name'      => $row->name,
                'actual'    => $row->actual,
                'forecast'  => $row->forecast,
                'previous'  => $row->previous,
                'type'      => $row->type,
                'image'     => $image,
                'y'         => date("Y", strtotime($row->event_date." ".$row->event_time)),
                'm'         => date("m", strtotime($row->event_date." ".$row->event_time)),
                'd'         => date("d", strtotime($row->event_date." ".$row->event_time)),
                'text'      => $text,
                'color'     => $row->color
            );
        }
        return response()->json($data);
    }

}
