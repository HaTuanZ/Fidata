<?php

namespace Botble\Coin\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Coin\Http\Requests\CoinRequest;
use Botble\Coin\Repositories\Interfaces\CoinInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Coin\Tables\CoinTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Coin\Forms\CoinForm;
use Botble\Base\Forms\FormBuilder;
use Theme;
use Botble\Coin\Models\CoindarTags;
use Botble\Coin\Models\CoindarCoins;

class CoinController extends BaseController
{
    /**
     * @var CoinInterface
     */
    protected $coinRepository;
    protected $access_token;

    /**
     * @param CoinInterface $coinRepository
     */
    public function __construct(CoinInterface $coinRepository)
    {
        $this->coinRepository = $coinRepository;
        $this->access_token = "69722:NP0wARbTLxqwSMYRRFJ";
    }

    /**
     * @param CoinTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(CoinTable $table)
    {
        page_title()->setTitle(trans('plugins/coin::coin.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/coin::coin.create'));

        return $formBuilder->create(CoinForm::class)->renderForm();
    }

    /**
     * @param CoinRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(CoinRequest $request, BaseHttpResponse $response)
    {
        $coin = $this->coinRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(COIN_MODULE_SCREEN_NAME, $request, $coin));

        return $response
            ->setPreviousUrl(route('coin.index'))
            ->setNextUrl(route('coin.edit', $coin->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $coin = $this->coinRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $coin));

        page_title()->setTitle(trans('plugins/coin::coin.edit') . ' "' . $coin->name . '"');

        return $formBuilder->create(CoinForm::class, ['model' => $coin])->renderForm();
    }

    /**
     * @param int $id
     * @param CoinRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, CoinRequest $request, BaseHttpResponse $response)
    {
        $coin = $this->coinRepository->findOrFail($id);

        $coin->fill($request->input());

        $coin = $this->coinRepository->createOrUpdate($coin);

        event(new UpdatedContentEvent(COIN_MODULE_SCREEN_NAME, $request, $coin));

        return $response
            ->setPreviousUrl(route('coin.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $coin = $this->coinRepository->findOrFail($id);

            $this->coinRepository->delete($coin);

            event(new DeletedContentEvent(COIN_MODULE_SCREEN_NAME, $request, $coin));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $coin = $this->coinRepository->findOrFail($id);
            $this->coinRepository->delete($coin);
            event(new DeletedContentEvent(COIN_MODULE_SCREEN_NAME, $request, $coin));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function test() {
        die;
        $coins = $this->coinRepository->all();
        foreach ($coins as $coin) {
            $id = $coin->id;
            $coin_id = $coin->coin_id;
            //echo "$id - $coin_id".'<br>';
            $messari_coin_id = $coin->symbol;
            if($coin_id == "bitcoin") {
                $messari_coin_id = $coin_id;
            }

            $messari_coin_uri = "https://data.messari.io/api/v2/assets/".$messari_coin_id."/profile?x-messari-api-key=df6545ea-787b-437a-b152-5a71b4d1e09b";
            $messari_coin = curl_get($messari_coin_uri);
            $technology = isset($messari_coin->data->profile->technology->overview->technology_details) ? $messari_coin->data->profile->technology->overview->technology_details : null;
            $individuals = isset($messari_coin->data->profile->contributors->individuals) ? $messari_coin->data->profile->contributors->individuals : null;
            $roadmap = isset($messari_coin->data->profile->general->roadmap) ? $messari_coin->data->profile->general->roadmap : null;
            $orientation = isset($messari_coin->data->profile->general->background->background_details) ? $messari_coin->data->profile->general->background->background_details : null;
            $assets = isset($messari_coin->data->profile->ecosystem->assets) ? $messari_coin->data->profile->ecosystem->assets : null;
            $organizations = isset($messari_coin->data->profile->ecosystem->organizations) ? $messari_coin->data->profile->ecosystem->organizations : null;
            $community_data = isset($coin->community_data) ? $coin->community_data : null;
            $fundraising = isset($messari_coin->data->profile->economics->launch->fundraising) ? $messari_coin->data->profile->economics->launch->fundraising : null;
            $launch_style = isset($messari_coin->data->profile->economics->launch->general->launch_style) ? $messari_coin->data->profile->economics->launch->general->launch_style : null;
            $launch_details = isset($messari_coin->data->profile->economics->launch->general->launch_details) ? $messari_coin->data->profile->economics->launch->general->launch_details : null;
            $initial_distribution = isset($messari_coin->data->profile->economics->launch->initial_distribution) ? $messari_coin->data->profile->economics->launch->initial_distribution : null;
            $sector = isset($messari_coin->data->profile->general->overview->sector) ? $messari_coin->data->profile->general->overview->sector : null;
            $consensus = isset($messari_coin->data->profile->economics->consensus_and_emission->consensus) ? $messari_coin->data->profile->economics->consensus_and_emission->consensus : null;
            $investors = isset($messari_coin->data->profile->investors) ? $messari_coin->data->profile->investors : null;
            $supply = isset($messari_coin->data->profile->economics->consensus_and_emission->supply) ? $messari_coin->data->profile->economics->consensus_and_emission->supply : null;

            /*
            $messari_coin_uri = "https://data.messari.io/api/v1/assets/".$messari_coin_id."/metrics?x-messari-api-key=df6545ea-787b-437a-b152-5a71b4d1e09b";
            $messari_coin = curl_get($messari_coin_uri);
            $annual_inflation_percent = isset($messari_coin->data->supply->annual_inflation_percent) ? $messari_coin->data->supply->annual_inflation_percent : 0;

            if($max_supply > 0) {
                $sum = $max_supply;
            } else {
                $sum = $total_supply;
            }
            $total_supply_pt = $total_supply * 100 / $sum;
            $circulating_supply_pt = $circulating_supply * 100 / $sum;
            $stake_pt = $total_stake * 100 / $sum;
            */
            $fund_raising = array();
            if($fundraising) {
                $sales_rounds = $fundraising->sales_rounds;
                foreach ($sales_rounds as $row) {
                    $fund_raising[] = array(
                        array("key" => "title", "value" => $row->title),
                        array("key" => "date", "value" => date("Y-m-d", strtotime($row->start_date))),
                        array("key" => "price", "value" => $row->equivalent_price_per_token_in_usd),
                        array("key" => "raised", "value" => $row->amount_collected_in_usd)
                    );
                }
            }
            $ecosystem = array();
            if($assets) {
                foreach ($assets as $row) {
                    $ecosystem[] = array(
                        array("key" => "image", "value" => ""),
                        array("key" => "text", "value" => $row->name),
                        array("key" => "address", "value" => $row->id)
                    );
                }
            }
            if($organizations) {
                foreach ($organizations as $row) {
                    $ecosystem[] = array(
                        array("key" => "image", "value" => $row->logo),
                        array("key" => "text", "value" => $row->name),
                        array("key" => "address", "value" => $row->description)
                    );
                }
            }

            $team_backers = array();
            if($individuals) {
                foreach ($individuals as $row) {
                    $team_backers[] = array(
                        array("key" => "image", "value" => $row->avatar_url),
                        array("key" => "name", "value" => $row->last_name),
                        array("key" => "position", "value" => $row->title)
                    );
                }
            }

            $investors_arr = array();
            if($investors) {
                $individuals = $investors->individuals;
                foreach ($individuals as $row) {
                    $investors_arr[] = array(
                        array("key" => "image", "value" => $row->avatar_url),
                        array("key" => "name", "value" => $row->last_name),
                        array("key" => "description", "value" => $row->description)
                    );
                }
                $organizations = $investors->organizations;
                foreach ($organizations as $row) {
                    $investors_arr[] = array(
                        array("key" => "image", "value" => $row->logo),
                        array("key" => "name", "value" => $row->name),
                        array("key" => "description", "value" => $row->description)
                    );
                }
            }

            $roadmap_arr = array();
            if($roadmap) {
                foreach ($roadmap as $row) {
                    $roadmap_arr[] = array(
                        array("key" => "title", "value" => $row->title),
                        array("key" => "date", "value" => $row->date),
                        array("key" => "type", "value" => $row->type),
                        array("key" => "details", "value" => $row->details)
                    );
                }
            }

            $condition['id'] = $id;
            $postdata = array(
                'fund_raising' => $fund_raising,
                'launch_style' => $launch_style,
                'launch_details' => nl2br($launch_details),
                'technology' => nl2br($technology),
                'ecosystem' => $ecosystem,
                'team_backers' => $team_backers,
                'investors' => $investors_arr,
                'roadmap' => $roadmap_arr,
                'orientation' => nl2br($orientation),
                'community_data' => $community_data ? json_encode($community_data) : NULL
            );
            $this->coinRepository->update($condition, $postdata);
        }
        die;
	    //return \Theme::layout('unitok')->scope('index', ['data' => 123])->render();
    }

    public function getEvents() {
        $theme = Theme::uses('unitok')->layout('events');

        $tags = curl_get("https://coindar.org/api/v2/tags?access_token=".$this->access_token);
        Theme::set('tags', json_encode($tags));

        $coins = curl_get("https://coindar.org/api/v2/coins?access_token=".$this->access_token);
        Theme::set('coins', json_encode($coins));

        return $theme->scope('events.index')->render();
    }

    public function getCoins($coin_id, Request $request) {
        if (!$coin_id) {
            abort(404);
        }

        $tags = CoindarTags::get();
        Theme::set('tags', json_encode($tags));

        $coins = CoindarCoins::get();
        Theme::set('coins', json_encode($coins));

        $theme = Theme::uses('unitok')->layout('dashboard');
        return $theme->scope('dashboard')->render();
    }
}
