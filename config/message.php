<?php
/**
 * Message配置文件
 * @author Norton
 * @datetime 2016-6-21 16:37:32
 */
return [
    'attachmentPath' => 'public/uploads/message/attachments/',
    'attachmentSrcPath' => 'uploads/message/attachments/',
    'statusText' => [
        'UNREAD' => '未读',
        'PROCESS' => '待处理',
        'COMPLETE' => '已处理'
    ],

    'aliexpress' => [
        'issueType' => [
            'WAIT_SELLER_CONFIRM_REFUND'    => '买家提起纠纷',
            'SELLER_REFUSE_REFUND'          => '卖家拒绝纠纷',
            'ACCEPTISSUE'                   => '卖家接受纠纷',
            'WAIT_BUYER_SEND_GOODS'         => '等待买家发货',
            'WAIT_SELLER_RECEIVE_GOODS'     => '买家发货，等待卖家收货',
            'ARBITRATING'                   => '仲裁中',
            'SELLER_RESPONSE_ISSUE_TIMEOUT' => '卖家响应纠纷超时',
        ]
    ],

    'reply' => [
        'status' => [
            'NEW' => '等待发送',
            'SENT' => '已发送',
            'FAIL' => '发送失败',
        ],
    ],

    'wish' => [
        'country' => [
            'AE' => 'United Arab Emirates',
            'AR' => 'Argentina',
            'AT' => 'Austria',
            'AU' => 'Australia',
            'BE' => 'Belgium',
            'BG' => 'Bulgaria',
            'BR' => 'Brazil',
            'BY' => 'Belarus',
            'CA' => 'Canada',
            'CH' => 'Switzerland',
            'CL' => 'Chile',
            'CO' => 'Colombia',
            'CR' => 'Costa Rica',
            'CZ' => 'Czech Republic',
            'DE' => 'Germany',
            'DK' => 'Denmark',
            'EC' => 'Ecuador',
            'EE' => 'Estonia',
            'EG' => 'Egypt',
            'ES' => 'Spain',
            'FI' => 'Finland',
            'FR' => 'France',
            'GB' => 'United Kingdom (Great Britain)',
            'GR' => 'Greece',
            'HK' => 'Hong Kong',
            'HR' => 'Croatia',
            'HU' => 'Hungary',
            'ID' => 'Indonesia',
            'IE' => 'Ireland',
            'IL' => 'Israel',
            'IN' => 'India',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JO' => 'Jordan',
            'JP' => 'Japan',
            'KR' => 'South Korea',
            'KW' => 'Kuwait',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'LV' => 'Latvia',
            'MA' => 'Morocco',
            'MC' => 'Monaco',
            'MX' => 'Mexico',
            'MY' => 'Malaysia',
            'NL' => 'Netherlands',
            'NO' => 'Norway',
            'NZ' => 'New Zealand',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PK' => 'Pakistan',
            'PL' => 'Poland',
            'PR' => 'Puerto Rico',
            'PT' => 'Portugal',
            'RO' => 'Romania',
            'RU' => 'Russia',
            'SA' => 'Saudi Arabia',
            'SE' => 'Sweden',
            'SG' => 'Singapore',
            'SI' => 'Slovenia',
            'SK' => 'Slovakia',
            'TH' => 'Thailand',
            'TR' => 'Turkey',
            'TW' => 'Taiwan',
            'UA' => 'Ukraine',
            'US' => 'United States',
            'VE' => 'Venezuela',
            'VG' => 'Virgin Islands, British',
            'VI' => 'Virgin Islands, U.S.',
            'VN' => 'Vietnam',
            'ZA' => 'South Africa',
        ],
    ],
];

