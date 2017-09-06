<?php
/**
 * 物流分配规则模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/4/14
 * Time: 下午2:52
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;
use App\Models\Channel\AccountModel;
use App\Models\CountriesModel;
use App\Models\CatalogModel;
use App\Models\Logistics\Rule\CatalogModel as RuleCatalogModel;
use App\Models\Logistics\Rule\ChannelModel as RuleChannelModel;
use App\Models\Logistics\Rule\CountryModel as RuleCountryModel;
use App\Models\Logistics\Rule\LimitModel as RuleLimitModel;
use App\Models\Logistics\LimitsModel;

class RuleModel extends BaseModel
{
    public $table = 'logistics_rules';

    public $searchFields = [];

    public $fillable = [
        'name',
        'weight_from',
        'weight_to',
        'order_amount_from',
        'order_amount_to',
        'is_clearance',
        'type_id',
        'weight_section', 
        'order_amount_section', 
        'catalog_section', 
        'channel_section', 
        'country_section', 
        'limit_section',
        'account_section',
        'transport_section',
    ];

    public $rules = [
        'create' => [
            'is_clearance' => 'required',
            'type_id' => 'required',
            'name' => 'required',
        ],
        'update' => [
            'is_clearance' => 'required',
            'type_id' => 'required',
            'name' => 'required',
        ],
    ];

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => ['logistics' => ['code', 'name']],
            'filterFields' => [],
            'filterSelects' => [],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
        ];
    }

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'type_id', 'id');
    }

    public function rule_catalogs()
    {
        return $this->hasMany('App\Models\Logistics\Rule\CatalogModel', 'logistics_rule_id', 'id');
    }

    public function rule_catalogs_through()
    {
        return $this->belongsToMany('App\Models\CatalogModel', 'logistics_rule_catalogs', 'logistics_rule_id', 'catalog_id');
    }

    public function rule_channels()
    {
        return $this->hasMany('App\Models\Logistics\Rule\ChannelModel', 'logistics_rule_id', 'id');
    }

    public function rule_channels_through()
    {
        return $this->belongsToMany('App\Models\ChannelModel', 'logistics_rule_channels', 'logistics_rule_id', 'channel_id');
    }

    public function rule_accounts()
    {
        return $this->hasMany('App\Models\Logistics\Rule\AccountModel', 'logistics_rule_id', 'id');
    }

    public function rule_accounts_through()
    {
        return $this->belongsToMany('App\Models\Channel\AccountModel', 'logistics_rule_accounts', 'logistics_rule_id', 'account_id');
    }

    public function rule_transports()
    {
        return $this->hasMany('App\Models\Logistics\Rule\TransportModel', 'logistics_rule_id', 'id');
    }

    public function rule_transports_through()
    {
        return $this->belongsToMany('App\Models\Logistics\TransportModel', 'logistics_rule_transports', 'logistics_rule_id', 'transport_id');
    }

    public function rule_countries()
    {
        return $this->hasMany('App\Models\Logistics\Rule\CountryModel', 'logistics_rule_id', 'id');
    }

    public function rule_countries_through()
    {
        return $this->belongsToMany('App\Models\CountriesModel', 'logistics_rule_countries', 'logistics_rule_id', 'country_id');
    }

    public function rule_limits()
    {
        return $this->hasMany('App\Models\Logistics\Rule\LimitModel', 'logistics_rule_id', 'id');
    }

    public function rule_limits_through()
    {
        return $this->belongsToMany('App\Models\Logistics\LimitsModel', 'logistics_rule_limits', 'logistics_rule_id', 'logistics_limit_id')->withPivot('type');
    }

    public function getCountrysNameAttribute()
    {
        $countrys = $this->countrys;
        $arr = explode(',', $countrys);
        $str = '';
        foreach($arr as $key => $value) {
            $country = CountriesModel::find($value);
            if($key == 0) {
                $str = $country->cn_name;
                continue;
            }
            $str .=','.$country->cn_name;
        }

        return $str;
    }

    public function getChannelsNameAttribute()
    {
        $channels = $this->channels;
        $arr = explode(',', $channels);
        $str = '';
        foreach($arr as $key => $value) {
            $channel = ChannelModel::find($value);
            if($key == 0) {
                $str = $channel->name;
                continue;
            }
            $str .=','.$channel->name;
        }

        return $str;
    }

    public function getAccountsNameAttribute()
    {
        $accounts = $this->accounts;
        $arr = explode(',', $accounts);
        $str = '';
        foreach($arr as $key => $value) {
            $account = AccountModel::find($value);
            if($key == 0) {
                $str = $account->account;
                continue;
            }
            $str .=','.$account->account;
        }

        return $str;
    }

    public function getTransportsNameAttribute()
    {
        $transports = $this->transports;
        $arr = explode(',', $transports);
        $str = '';
        foreach($arr as $key => $value) {
            $transport = TransportModel::find($value);
            if($key == 0) {
                $str = $transport->name;
                continue;
            }
            $str .=','.$transport->name;
        }

        return $str;
    }

    public function getCatalogsNameAttribute()
    {
        $catalogs = $this->catalogs;
        $arr = explode(',', $catalogs);
        $str = '';
        foreach($arr as $key => $value) {
            $catalog = CatalogModel::find($value);
            if($key == 0) {
                $str = $catalog->name;
                continue;
            }
            $str .=','.$catalog->name;
        }

        return $str;
    }

    public function createAll($arr)
    {
        if(array_key_exists('catalog_section', $arr) && array_key_exists('catalogs', $arr)) {
            $this->rule_catalogs_through()->attach($arr['catalogs']);
        }
        if(array_key_exists('channel_section', $arr) && array_key_exists('channels', $arr)) {
            $this->rule_channels_through()->attach($arr['channels']);
        }
        if(array_key_exists('country_section', $arr) && array_key_exists('countrys', $arr)) {
            $this->rule_countries_through()->attach($arr['countrys']);
        }
        if(array_key_exists('limit_section', $arr) && array_key_exists('limits', $arr)) {
            foreach($arr['limits'] as $key => $value) {
                $this->rule_limits_through()->attach([$key => ['type' => $value]]);
            }
        }
        if(array_key_exists('account_section', $arr) && array_key_exists('accounts', $arr)) {
            $this->rule_accounts_through()->attach($arr['accounts']);
        }
        if(array_key_exists('transport_section', $arr) && array_key_exists('transports', $arr)) {
            $this->rule_transports_through()->attach($arr['transports']);
        }
    }

    public function innerType($type1, $id, $type = NULL)
    {
        switch($type1) {
            case 'catalog':
                $catalogs = $this->rule_catalogs_through;
                foreach($catalogs as $catalog) {
                    if($catalog->pivot->catalog_id == $id) {
                        return true;
                    }
                }
                return false;
                break;

            case 'channel':
                $channels = $this->rule_channels_through;
                foreach($channels as $channel) {
                    if($channel->pivot->channel_id == $id) {
                        return true;
                    }
                }
                return false;
                break;

            case 'country':
                $countries = $this->rule_countries_through;
                foreach($countries as $country) {
                    if($country->pivot->country_id == $id) {
                        return true;
                    }
                }
                return false;
                break;

            case 'limit':
                $limits = $this->rule_limits_through;
                foreach($limits as $limit) {
                    if($limit->pivot->logistics_limit_id == $id && $limit->pivot->type == $type) {
                        return true;
                    }
                }
                return false;
                break;

            case 'account':
                $accounts = $this->rule_accounts_through;
                foreach($accounts as $account) {
                    if($account->pivot->account_id == $id) {
                        return true;
                    }
                }
                return false;
                break;

            case 'transport':
                $transports = $this->rule_transports_through;
                foreach($transports as $transport) {
                    if($transport->pivot->transport_id == $id) {
                        return true;
                    }
                }
                return false;
                break;
        }
    }

    public function updateAll($arr)
    {
        $this->update($arr);
        if(!array_key_exists('weight_section', $arr)) {
            $this->update(['weight_section' => '0', 'weight_from' => '0', 'weight_to' => '0']);
        }
        if(!array_key_exists('order_amount_section', $arr)) {
            $this->update(['order_amount_section' => '0', 'order_amount_section' => '0', 'order_amount_to' => '0']);
        }
        if(array_key_exists('catalog_section', $arr) && array_key_exists('catalogs', $arr)) {
            $this->rule_catalogs_through()->sync($arr['catalogs']);
        } else {
            $this->update(['catalog_section' => '0']);
            $this->rule_catalogs_through()->sync([]);
        }
        if(array_key_exists('channel_section', $arr) && array_key_exists('channels', $arr)) {
            $this->rule_channels_through()->sync($arr['channels']);
        } else {
            $this->update(['channel_section' => '0']);
            $this->rule_channels_through()->sync([]);
        }
        if(array_key_exists('account_section', $arr) && array_key_exists('accounts', $arr)) {
            $this->rule_accounts_through()->sync($arr['accounts']);
        } else {
            $this->update(['account_section' => '0']);
            $this->rule_accounts_through()->sync([]);
        }
        if(array_key_exists('transport_section', $arr) && array_key_exists('transports', $arr)) {
            $this->rule_transports_through()->sync($arr['transports']);
        } else {
            $this->update(['transport_section' => '0']);
            $this->rule_transports_through()->sync([]);
        }
        if(array_key_exists('country_section', $arr) && array_key_exists('countrys', $arr)) {
            $this->rule_countries_through()->sync($arr['countrys']);
        } else {
            $this->update(['country_section' => '0']);
            $this->rule_countries_through()->sync([]);
        }
        if(array_key_exists('limit_section', $arr) && array_key_exists('limits', $arr)) {
            $tmp = [];
            foreach($arr['limits'] as $key => $value) {
                $tmp[$key] = ['type' => $value]; 
            }
            $this->rule_limits_through()->sync($tmp);
        } else {
            $this->update(['limit_section' => '0']);
            $this->rule_limits_through()->sync([]);
        }
    }
}