<?php

namespace Botble\Analysis\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Coin\Models\CoindarTags;
use Botble\Coin\Models\CoindarCoins;
use SeoHelper;
use Theme;
use Botble\Analysis\Models\GemBalances;
use Botble\Analysis\Models\GemCollects;
use Botble\Analysis\Models\GemBalanceLogs;
use Botble\Member\Models\Member;
use DateTime;
use Botble\Analysis\Models\Affiliation;
use Botble\Analysis\Models\EarningLogs;
use Botble\Analysis\Models\GemRedeem;
use Botble\Analysis\Models\CurrencySymbols;
use Botble\Analysis\Models\Wallets;
use Botble\Analysis\Models\Deposit;
use Botble\Analysis\Models\Balance;
use Botble\Analysis\Models\BalanceLog;

class PublicController extends Controller
{

    protected $access_token;

    public function __construct()
    {
        $this->access_token = "69722:NP0wARbTLxqwSMYRRFJ";
    }

	public function index() {
        //$tags = curl_get("https://coindar.org/api/v2/tags?access_token=".$this->access_token);
        $tags = CoindarTags::get();
        Theme::set('tags', json_encode($tags));

        //$coins = curl_get("https://coindar.org/api/v2/coins?access_token=".$this->access_token);
        $coins = CoindarCoins::get();
        Theme::set('coins', json_encode($coins));

		$theme = Theme::uses('unitok')->layout('vuejs');
		return $theme->scope('analysis.index')->render();
	}

    public function project() {
        $example = "123";
        $theme = Theme::uses('unitok')->layout('project');
        return $theme->scope('projects.project', compact('example'))->render();
    }

    public function accountMyGems() {
        SeoHelper::setTitle("My GEMs");
        $theme = Theme::uses('unitok')->layout('account');
        return $theme->scope('account.my_gems')->render();
    }
    public function accountActivity() {
        SeoHelper::setTitle("Account Activity");
        $theme = Theme::uses('unitok')->layout('account');
        return $theme->scope('account.my_referral')->render();
    }
    public function getGemRewards() {
        SeoHelper::setTitle("GEM Rewards");
        $theme = Theme::uses('unitok')->layout('account');
        return $theme->scope('account.gem_rewards')->render();
    }
    public function getDeposit() {
        SeoHelper::setTitle("Deposit");
        $theme = Theme::uses('unitok')->layout('account');
        return $theme->scope('account.deposit')->render();
    }
    public function getBalance() {
        SeoHelper::setTitle("Balance");
        $theme = Theme::uses('unitok')->layout('account');
        return $theme->scope('account.balance')->render();
    }

    public function getCurrentUser(Request $request, BaseHttpResponse $response) {
        $user = auth('member')->user();

        $user_id = $user->affiliate_id;

        $balance = Balance::where([['user_id','=',$user_id],['currency_symbol','=','USDT']])->first();
        $user->balance = $balance ? $balance->balance : 0;

        $user_gems = GemBalances::where('user_id', $user_id)->sum('balance');
        $user->gems = $user_gems;

        $gem_collect = GemCollects::where("user_id", $user_id)->first();
        $need_update_collect_date = false;
        if($gem_collect) {
            $collect_date_start = $gem_collect->collect_date_start;
            $today = time();
            $diff = (int) (($today - strtotime($collect_date_start))/86400);
            if($diff <= 0 || $diff > 6) {
                $collect_date_start = date("Y-m-d");
                $code = "day_1";
                $need_update_collect_date = true;
            } else {
                $code = "day_".($diff+1);
            }
        } else {
            $collect_date_start = date("Y-m-d");
            $code = "day_1";
            $need_update_collect_date = true;
            $diff = 0;
        }
        if($need_update_collect_date) {
            if($gem_collect) {
                GemCollects::where('id', $gem_collect->id)->update(array("collect_date_start" => $collect_date_start));
            } else {
                GemCollects::create(array("user_id" => $user_id, "collect_date_start" => $collect_date_start));
            }
        }
        $user->diff = $diff+1;

        $user->collected_day_1 = $this->get_collected_gem($collect_date_start);
        $user->collected_day_2 = $this->get_collected_gem(date("Y-m-d", strtotime($collect_date_start." +1 day")));
        $user->collected_day_3 = $this->get_collected_gem(date("Y-m-d", strtotime($collect_date_start." +2 day")));
        $user->collected_day_4 = $this->get_collected_gem(date("Y-m-d", strtotime($collect_date_start." +3 day")));
        $user->collected_day_5 = $this->get_collected_gem(date("Y-m-d", strtotime($collect_date_start." +4 day")));
        $user->collected_day_6 = $this->get_collected_gem(date("Y-m-d", strtotime($collect_date_start." +5 day")));
        $user->collected_day_7 = $this->get_collected_gem(date("Y-m-d", strtotime($collect_date_start." +6 day")));



        return response()->json($user);
    }

    private function get_collected_gem($collect_date_start) {
        $user = auth('member')->user();
        $user_id = $user->affiliate_id;
        $ref_id = "login".date("Ymd", strtotime($collect_date_start));
        $collected_row = GemBalanceLogs::where([['ref_id','=',$ref_id],['user_id','=',$user_id]])->first();
        return $collected_row ? true : false;
    }
    public function setGemCollect(Request $request, BaseHttpResponse $response) {
        $user = auth('member')->user();
        $user_id = $user->affiliate_id;

        $gem_collect = GemCollects::where("user_id", $user_id)->first();
        $need_update_collect_date = false;
        if($gem_collect) {
            $collect_date_start = $gem_collect->collect_date_start;
            $today = time();
            $diff = (int) (($today - strtotime($collect_date_start))/86400);
            if($diff <= 0 || $diff > 6) {
                $collect_date_start = date("Y-m-d");
                $code = "day_1";
                $need_update_collect_date = true;
            } else {
                $code = "day_".($diff+1);
            }
        } else {
            $collect_date_start = date("Y-m-d");
            $code = "day_1";
            $need_update_collect_date = true;
        }
        if($need_update_collect_date) {
            if($gem_collect) {
                GemCollects::where('id', $gem_collect->id)->update(array("collect_date_start" => $collect_date_start));
            } else {
                GemCollects::create(array("user_id" => $user_id, "collect_date_start" => $collect_date_start));
            }
        }

        $error = 0;
        $msg = "";
        $ref_id = "login".date("Ymd");
        $check_daily_reward = GemBalanceLogs::where([['ref_id','=',$ref_id],['user_id','=',$user_id]])->first();
        if($check_daily_reward) {
            $error = $error + 1;

            $now = new DateTime();
            $new_date = date("Y-m-d 00:00:01", strtotime("+1 day"));
            $future_date = new DateTime($new_date);
            $interval = $future_date->diff($now);
            $next_date = $interval->format("%h hours, %i minutes, %s seconds");
            $msg = "You come back tomorrow to get more GEM. ($next_date UTC+0)";
        }

        if(!$error) {
            switch ($code) {
                case 'day_2':
                    $amount = 20;
                    break;
                case 'day_3':
                    $amount = 30;
                    break;
                case 'day_4':
                    $amount = 40;
                    break;
                case 'day_5':
                    $amount = 50;
                    break;
                case 'day_6':
                    $amount = 60;
                    break;
                case 'day_7':
                    $amount = 110;
                    break;
                default:
                    $amount = 10;
                    break;
            }
            if($amount) {
                // Set balance
                $operator = "+";
                $currency_symbol = "USDT";
                $note = "Daily Reward";

                $balance_row = GemBalances::where([['user_id','=',$user_id],['currency_symbol','=',$currency_symbol]])->first();
                if($balance_row) {
                    $current_balance = $balance_row->balance;
                    $prev_balance = $current_balance;
                    if($operator === "-") {
                        $new_balance = $current_balance - $amount;
                    } else {
                        $new_balance = $current_balance + $amount;
                    }
                    GemBalances::where('id', $balance_row->id)->update(array("balance" => $new_balance, "updated_at" => date("Y-m-d H:i:s")));
                    $balance_id = $balance_row->id;
                } else {
                    $prev_balance = 0;
                    $inserted = GemBalances::create(array("user_id" => $user_id, "currency_symbol" => $currency_symbol, "balance" => $amount, "updated_at" => date("Y-m-d H:i:s")));
                    $balance_id = $inserted->id;
                }
                $data_log = array(
                    "ref_id"             => $ref_id,
                    "balance_id"         => $balance_id,
                    "user_id"            => $user_id,
                    "balance"            => $amount,
                    "prev_balance"       => $prev_balance,
                    "currency_symbol"    => $currency_symbol,
                    "operator"           => $operator,
                    "note"               => $note,
                    "ip_address"         => $_SERVER['REMOTE_ADDR'],
                    "updated_at"         => date("Y-m-d H:i:s")
                );
                GemBalanceLogs::create($data_log);

                $msg = "Nice! You collected Gem today!";
            }
        }

        $data['error'] = $error;
        $data['msg'] = $msg;
        $data['balance'] = GemBalances::where('user_id', $user_id)->sum('balance');

        return response()->json($data);
    }

    public function getGemsHistory(Request $request, BaseHttpResponse $response) {
        $page = $request->input("page");
        $limit = 12;

        $order_by = $request->input("order_by") ? $request->input("order_by") : "created_at";
        $order = $request->input("order") ? $request->input("order") : "desc";

        $logs = new GemBalanceLogs;
        $logs = $logs::orderBy($order_by, $order);

        $logs = $logs->offset($page)->paginate($limit);
        return response()->json($logs);
    }

    public function getCurrentUserReferral(Request $request, BaseHttpResponse $response) {
        $user = auth('member')->user();

        $user_id = $user->affiliate_id;
        $users = Member::where("referred_by", $user_id)->get();
        foreach ($users as $user) {
            $friend_arr = array();
            $data['users'][] = (object) array(
                'user_id' => $user->affiliate_id,
                //'level'   => $this->Affiliation_model->get_row(array( "level_id" => 1, "status" => 1)),
                'level' => '1',
                'friends' => $this->get_recursive_friends($user->affiliate_id, $friend_arr),
            );
            $users_level_2 = Member::where("referred_by", $user->affiliate_id)->get();
            if($users_level_2) {
                $friend2_arr = array();
                foreach ($users_level_2 as $user2) {
                    $data['users'][] = (object) array(
                        'user_id' => $user2->affiliate_id,
                        //'level'   => $this->Affiliation_model->get_row(array( "level_id" => 2, "status" => 1)),
                        'level' => 1,
                        'friends' => $this->get_recursive_friends($user2->affiliate_id, $friend2_arr),
                    );
                    $users_level_3 = Member::where("referred_by", $user2->affiliate_id)->get();
                    if($users_level_3) {
                        $friend3_arr = array();
                        foreach ($users_level_3 as $user3) {
                            $data['users'][] = (object) array(
                                'user_id' => $user3->affiliate_id,
                                //'level'   => $this->Affiliation_model->get_row(array( "level_id" => 3, "status" => 1)),
                                'level' => 1,
                                'friends' => $this->get_recursive_friends($user3->affiliate_id, $friend3_arr),
                            );
                        }
                    }
                }
            }
        }
        $friends = $this->get_recursive_friends($user_id, array());
        $user->friends = count($friends);
        $user->users = $data['users'];

        return response()->json($user);
    }
    public function getUsers(Request $request, BaseHttpResponse $response) {
        $user = auth('member')->user();

        $user_id = $user->affiliate_id;
        $users = new Member;
        $users = $users::where("referred_by", $user_id)->get();

        foreach ($users as $user) {
            $friend_arr = array();
            $data['users'][] = (object) array(
                'user_id' => $user->affiliate_id,
                'level'   => Affiliation::where([['level_id','=',1],['status','=',1]])->first(),
                'friends' => $this->get_recursive_friends($user->affiliate_id, $friend_arr),
            );
            $users_level_2 = Member::where("referred_by", $user->affiliate_id)->get();
            if($users_level_2) {
                $friend2_arr = array();
                foreach ($users_level_2 as $user2) {
                    $data['users'][] = (object) array(
                        'user_id' => $user2->affiliate_id,
                        'level'   => Affiliation::where([['level_id','=',2],['status','=',1]])->first(),
                        'friends' => $this->get_recursive_friends($user2->affiliate_id, $friend2_arr),
                    );
                    $users_level_3 = Member::where("referred_by", $user2->affiliate_id)->get();
                    if($users_level_3) {
                        $friend3_arr = array();
                        foreach ($users_level_3 as $user3) {
                            $data['users'][] = (object) array(
                                'user_id' => $user3->affiliate_id,
                                'level'   => Affiliation::where([['level_id','=',3],['status','=',1]])->first(),
                                'friends' => $this->get_recursive_friends($user3->affiliate_id, $friend3_arr),
                            );
                        }
                    }
                }
            }
        }
        return response()->json($data);
    }
    private function get_recursive_friends($user_id, $user_arr) {
        $users = Member::where('referred_by', $user_id)->get();
        if($users->count()) {
            foreach($users as $user) {
                $user_arr[] = $user->affiliate_id;
                $this->get_recursive_friends($user->affiliate_id, $user_arr);
            }
        }
        return $user_arr;
    }

    public function getCommissionHistory(Request $request, BaseHttpResponse $response) {
        $user = auth('member')->user();
        $user_id = $user->affiliate_id;

        $page = $request->input("page");
        $limit = 12;

        $order_by = $request->input("order_by") ? $request->input("order_by") : "created_at";
        $order = $request->input("order") ? $request->input("order") : "desc";

        $logs = new EarningLogs;
        $logs = $logs::where('user_id', $user_id);
        $logs = $logs->orderBy($order_by, $order);

        $logs = $logs->offset($page)->paginate($limit);
        return response()->json($logs);
    }

    public function setApplyCoupon(Request $request, BaseHttpResponse $response) {
        $user = auth('member')->user();
        $user_id = $user->affiliate_id;

        $error = 0;
        $msg = "";

        $coupon_code = $request->input("coupon_code");
        $package_id = $request->input("package_id");

        $package_json = curl_get(url("/api/package/".$package_id));
        $package_data = isset($package_json->data) ? $package_json->data : array();
        $gem = $package_data->price;

        $request_url = url("/api/coupon/$coupon_code");
        $coupon_json = curl_get($request_url);
        $coupon_data = isset($coupon_json->data) ? $coupon_json->data : NULL;

        if($coupon_data) {
            $usage_limit_per_user = $coupon_data->usage_limit_per_user;
            if($usage_limit_per_user > 0) {
                $redeem_list = GemRedeem::where([['user_id','=',$user_id],['coupon_code','=',$coupon_code]])->get();
                $redeem_total = $redeem_list->count();
                if($redeem_total >= $usage_limit_per_user) {
                    $error = $error + 1;
                    $msg = "$redeem_total/$usage_limit_per_user time(s) this coupon can be used.";
                }
                $expiry_date = $coupon_data->expiry_date;
                if(time() > strtotime($expiry_date." 23:59:59")) {
                    $error = $error + 1;
                    $msg = "Coupon code $coupon_code expired.";
                }
            }
            if(!$error) {
                $discount = 0;
                $discount_type = $coupon_data->discount_type;
                $coupon_amount = $coupon_data->coupon_amount;
                if($discount_type == "fixed_product") {
                    $discount = $gem - $coupon_amount;
                } else {
                    $discount = $gem - ($gem * $coupon_amount / 100);
                }

                $request->session()->put('coupon_data', $coupon_data);
                $msg = "Voucher applied successfully. Please refresh browser and redeem now.";

                $data['discount'] = $discount;
                $data['discount_text'] = sprintf("Coupon %s - %s", $coupon_code, $discount);
            } else {
                $request->session()->forget('coupon_data');
            }
        } else {
            $error = $error + 1;
            $msg = "Invalid coupon code.";
        }

        $data['error'] = $error;
        $data['msg'] = $msg;
        $data['coupon_data'] = $request->session()->get('coupon_data');

        return response()->json($data);
    }

    public function setSubmitPackage(Request $request, BaseHttpResponse $response) {
        $user = auth('member')->user();
        $user_id = $user->affiliate_id;

        $error = 0;
        $msg = "";

        $package_id = $request->input("package_id");
        $package_json = curl_get(url("/api/package/".$package_id));
        $package_data = isset($package_json->data) ? $package_json->data : array();
        $gem = $package_data->price;

        $discount = 0;
        $note = "Subscription for Package ".$package_data->name;
        if($request->session()->has('coupon_data')) {
            $coupon_data = $request->session()->get('coupon_data');
            $expiry_date = $coupon_data->expiry_date;
            if(time() <= strtotime($expiry_date." 23:59:59")) {
                $discount_type = $coupon_data->discount_type;
                $coupon_amount = $coupon_data->coupon_amount;
                if($discount_type == "fixed_product") {
                    $discount = $coupon_amount;
                } else {
                    $discount = $gem * $coupon_amount / 100;
                }
                $note .= " ".sprintf("(Applied coupon code $coupon_data->code)");
                $gem = $gem - $discount;
            }
        }

        /*
        $balance_gem = GemBalances::where('user_id', $user_id)->sum('balance');
        if($balance_gem < $gem) {
            $error = $error + 1;
            $msg = "You don't have enough GEMS. Keep collecting GEM everyday when you login to CoinGen.net";

            $remain_gem = $gem - $balance_gem;
            $msg = sprintf("You need %s more gems to redeem.", $remain_gem);
        }
        */

        $balance_usdt = Balance::where([['user_id','=',$user_id],['currency_symbol','=','USDT']])->sum('balance');
        if($balance_usdt < $gem) {
            $error = $error + 1;
            $msg = "You don't have enough USDT. Keep collecting USDT everyday when you login to CoinGen.net";

            $remain_gem = $gem - $balance_usdt;
            $msg = sprintf("You need %s more gems to redeem.", $remain_gem);
        }

        $current_redeem_row = GemRedeem::where([['user_id','=',$user_id],['post_type','=','package']])->first();
        if($current_redeem_row) {
            if($package_data->access_length == "specific") {
                $current_package_id = $current_redeem_row->post_id;
                if($current_package_id == $package_data->id) {
                    $expired = $current_redeem_row->expired;
                    if(strtotime($expired) > time()) {
                        $new_expired = date("Y-m-d", strtotime($expired."+".$package_data->access_length_amount." ".$package_data->access_length_period));
                    } else {
                        $new_expired = date("Y-m-d", strtotime("+".$package_data->access_length_amount." ".$package_data->access_length_period));
                    }
                } else {
                    $expired = $current_redeem_row->expired;
                    if(strtotime($expired) > time()) {
                        $new_expired = date("Y-m-d", strtotime($expired."+".$package_data->access_length_amount." ".$package_data->access_length_period));
                    } else {
                        $new_expired = date("Y-m-d", strtotime("+".$package_data->access_length_amount." ".$package_data->access_length_period));
                    }
                    $old_package_json = curl_get(url("/api/package/".$current_package_id));
                    $old_package_data = isset($old_package_json->data) ? $old_package_json->data : array();
                    if($old_package_data->access_length == "specific") {
                        $old_gem = $old_package_data->price;
                        $spent = ceil(abs(strtotime(date("Y-m-d")) - strtotime($current_redeem_row->last_updated)) / 86400);
                        $access_length_period = $old_package_data->access_length_period;
                        switch ($access_length_period) {
                            case "weeks":
                                $ratio = 7;
                                break;
                            case "months":
                                $ratio = 30;
                                break;
                            case "years":
                                $ratio = 365;
                                break;
                            default:
                                $ratio = 1;
                                break;
                        }

                        $lost_gem = ($old_gem/$ratio)*$spent;

                        // Set balance
                        $operator = "-";
                        $currency_symbol = "USDT";
                        $ref_id = $old_package_data->id;
                        $note = "Use package {$old_package_data->name} in $spent day(s).";

                        //$balance_row = GemBalances::where([['user_id','=',$user_id],['currency_symbol','=',$currency_symbol]])->first();
                        $balance_row = Balance::where([['user_id','=',$user_id],['currency_symbol','=',$currency_symbol]])->first();
                        if($balance_row) {
                            $current_balance = $balance_row->balance;
                            $prev_balance = $current_balance;
                            if($operator === "-") {
                                $new_balance = $current_balance - $lost_gem;
                            } else {
                                $new_balance = $current_balance + $lost_gem;
                            }
                            //GemBalances::where('id', $balance_row->id)->update(array("balance" => $new_balance, "updated_at" => date("Y-m-d H:i:s")));
                            Balance::where('id', $balance_row->id)->update(array("balance" => $new_balance, "updated_at" => date("Y-m-d H:i:s")));
                            $balance_id = $balance_row->id;
                        } else {
                            $prev_balance = 0;
                            //$inserted = GemBalances::create(array("user_id" => $user_id, "currency_symbol" => $currency_symbol, "balance" => $lost_gem, "updated_at" => date("Y-m-d H:i:s")));
                            $inserted = Balance::create(array("user_id" => $user_id, "currency_symbol" => $currency_symbol, "balance" => $lost_gem, "updated_at" => date("Y-m-d H:i:s")));
                            $balance_id = $inserted->id;
                        }
                        $data_log = array(
                            "ref_id"             => $ref_id,
                            "balance_id"         => $balance_id,
                            "user_id"            => $user_id,
                            "balance"            => $lost_gem,
                            "prev_balance"       => $prev_balance,
                            "currency_symbol"    => $currency_symbol,
                            "operator"           => $operator,
                            "transaction_type"   => "REDEEM",
                            "transaction_amount" => $lost_gem,
                            "transaction_fees"   => 0,
                            "note"               => $note,
                            "ip_address"         => $_SERVER['REMOTE_ADDR'],
                            "updated_at"         => date("Y-m-d H:i:s")
                        );
                        BalanceLog::create($data_log);
                        // END SET BALANCE

                        $gem = $gem - ($old_package_data->price - $lost_gem);
                    }
                }
            }
        } else {
            $new_expired = date("Y-m-d", strtotime("+".$package_data->access_length_amount." ".$package_data->access_length_period));
        }
        $note .= " (expired: ".format_datetime($new_expired).")";

        if(!$error) {
            $post_data = array(
                "user_id"     => $user_id,
                "post_id"     => $package_id,
                "post_type"   => "package",
                "gem"         => $gem,
                "discount"    => $discount,
                "coupon_code" => isset($coupon_data->code) ? $coupon_data->code : NULL,
                "expired"     => $new_expired,
            );
            if($current_redeem_row) {
                //$post_data['id'] = $current_redeem_row->id;
                $redeemed = GemRedeem::where('id', $current_redeem_row->id)->update($post_data);
            } else {
                $redeemed = GemRedeem::create($post_data);
            }

            if($redeemed) {
                // Set balance
                $operator = "-";
                $currency_symbol = "USDT";
                $ref_id = $package_id;

                //$balance_row = GemBalances::where([['user_id','=',$user_id],['currency_symbol','=',$currency_symbol]])->first();
                $balance_row = Balance::where([['user_id','=',$user_id],['currency_symbol','=',$currency_symbol]])->first();
                if($balance_row) {
                    $current_balance = $balance_row->balance;
                    $prev_balance = $current_balance;
                    if($operator === "-") {
                        $new_balance = $current_balance - $gem;
                    } else {
                        $new_balance = $current_balance + $gem;
                    }
                    //GemBalances::where('id', $balance_row->id)->update(array("balance" => $new_balance, "updated_at" => date("Y-m-d H:i:s")));
                    Balance::where('id', $balance_row->id)->update(array("balance" => $new_balance, "updated_at" => date("Y-m-d H:i:s")));
                    $balance_id = $balance_row->id;
                } else {
                    $prev_balance = 0;
                    //$inserted = GemBalances::create(array("user_id" => $user_id, "currency_symbol" => $currency_symbol, "balance" => $gem, "updated_at" => date("Y-m-d H:i:s")));
                    $inserted = Balance::create(array("user_id" => $user_id, "currency_symbol" => $currency_symbol, "balance" => $gem, "updated_at" => date("Y-m-d H:i:s")));
                    $balance_id = $inserted->id;
                }
                $data_log = array(
                    "ref_id"             => $ref_id,
                    "balance_id"         => $balance_id,
                    "user_id"            => $user_id,
                    "balance"            => $gem,
                    "prev_balance"       => $prev_balance,
                    "currency_symbol"    => $currency_symbol,
                    "operator"           => $operator,
                    "transaction_type"   => "REDEEM",
                    "transaction_amount" => $gem,
                    "transaction_fees"   => 0,
                    "note"               => $note,
                    "ip_address"         => $_SERVER['REMOTE_ADDR'],
                    "updated_at"         => date("Y-m-d H:i:s")
                );
                BalanceLog::create($data_log);
                // END SET BALANCE

                $data['redeemed'] = $gem;

                $msg = "Congratulations on your successful redemption. Enjoy now!";
                $msg .= " ".$note;

                $request->session()->forget('coupon_data');
            }
        }

        $data['error'] = $error;
        $data['msg'] = $msg;

        return response()->json($data);
    }

    public function setSubmitDeposit(Request $request, BaseHttpResponse $response) {
        $user = auth('member')->user();
        $user_id = $user->affiliate_id;

        $error = 0;
        $msg = "";

        $deposit_amount = $request->input("amount");
        if($deposit_amount < 0) {
            $error = $error + 1;
            $msg = "Amount must greater than 0.";
        }

        $currency_symbol = $request->input('currency_symbol');
        if(empty($currency_symbol)) {
            $error = $error + 1;
            $msg = "Invalid currency symbol.";
        }

        if(!$error) {
            $method_id = 8; // Wallet
            $wallet_id = $request->input("wallet_id");
            $wallet = Wallets::where('id', $wallet_id)->first();

            $currency_symbol_row = CurrencySymbols::where([['id','=', $wallet->currency_symbol_id],['status','=',1],['deposit','=',1]])->first();

            $order_no = random_string(8, 2);

            $post_data = array(
                'order_no'         => $order_no,
                'user_id'          => $user_id,
                'method_id'        => $method_id,
                'wallet_id'        => $wallet_id,
                'address'          => $currency_symbol_row->wallet_id,
                'network'          => $wallet->name,
                'amount'           => $deposit_amount,
                'currency_symbol'  => $currency_symbol,
                'fees'             => 0,
                'last_updated'     => date("Y-m-d H:i:s"),
                'email_account'    => $request->input("email"),
                'note'             => $request->input("note"),
                'created_at'       => date("Y-m-d H:i:s"),
                'deposit_ip'       => $request->ip(),
                'gem_id'           => 0,
                'status'           => 2 // Pending
            );
            $created = Deposit::create($post_data);
            if($created) {
                $msg = "Deposit successfully";
            } else {
                $error = $error + 1;
                $msg = "Please try again";
            }
        }

        $data['error'] = $error;
        $data['msg'] = $msg;

        return response()->json($data);
    }
    public function getBalances(Request $request, BaseHttpResponse $response) {
        $user = auth('member')->user();

        $user_id = $user->affiliate_id;

        $balance = new Balance;
        $balance = $balance::where("user_id", $user_id)->get();

        return response()->json($balance);
    }

    public function getBalanceLogs(Request $request, BaseHttpResponse $response) {
        $user = auth('member')->user();
        $user_id = $user->affiliate_id;

        $page = $request->input("page");
        $limit = 12;

        $order_by = $request->input("order_by") ? $request->input("order_by") : "created_at";
        $order = $request->input("order") ? $request->input("order") : "desc";

        $logs = new BalanceLog;
        $logs = $logs::where('user_id', $user_id);
        $logs = $logs->orderBy($order_by, $order);

        $logs = $logs->offset($page)->paginate($limit);
        return response()->json($logs);
    }

    public function getDeposits(Request $request, BaseHttpResponse $response) {
        $user = auth('member')->user();
        $user_id = $user->affiliate_id;

        $page = $request->input("page");
        $limit = 12;

        $order_by = $request->input("order_by") ? $request->input("order_by") : "created_at";
        $order = $request->input("order") ? $request->input("order") : "desc";

        $logs = new Deposit;
        $logs = $logs::where('user_id', $user_id);
        $logs = $logs->orderBy($order_by, $order);

        $logs = $logs->offset($page)->paginate($limit);
        return response()->json($logs);
    }
}
