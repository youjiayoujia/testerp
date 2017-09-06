<?php
/**
 * Created by PhpStorm.
 * User: norton
 * Date: 2017/1/3
 * Time: 上午9:38
 */
return [
    //ebay平台
    'ebay' => [
        'feedback' => [
            'Positive' => '好评',
            'Neutral' => '中评',
            'Negative' => '差评',
        ],
        'case' =>[
            'type' => [
                'EBP_INR' => 'EBP_INR',
                'EBP_SNAD' => 'EBP_SNAD',
                'RETURN' => 'RETURN',
            ],
            'status' => [
                'CLOSED' => 'CLOSED',
                'MY_RESPONSE_DUE' => 'MY_RESPONSE_DUE',
                'OPEN' => 'OPEN',
                'OTHER' => 'OTHER',
                'OTHER_PARTY_RESPONSE_DUE' => 'OTHER_PARTY_RESPONSE_DUE',
            ],
        ],
    ],

    //wish平台
    'wish' => [
        'refund' => [
            'reason_code' => [
                '-1'  => '其他',
                '18'  => '误下单了',
                '20'  => '配送时间过长',
                '22'  => '商品不合适',
                '23'  => '收到错误商品',
                '24'  => '商品为假冒伪劣品',
                '25'  => '商品已损坏',
                '26'  => '商品与描述不符',
                '27'  => '商品与清单不符',
                '30'  => '产品被配送至错误的地址',
                '31'  => '用户提供了错误的地址',
                '32'  => '商品退还至发货人',
                '33'  => 'Incomplete Order',
                '34'  => '店铺无法履行订单',
                '1001'  => 'Received the wrong color',
                '1002'  => 'Item is of poor quality',
                '1004'  => 'Product listing is missing information',
                '1005'  => 'Item did not meet expectations',
                '1006'  => 'Package was empty'
            ],
            'lang_prompts' => [
                'English' => 'Apologies for this inconvenience. We have refunded this transaction for you. Please allow 5-7 days for your refund to be processed back to your original payment method.',
                'Czech' => 'Omlouváme se za případné nejasnosti. Za tuto transakci ti budou vráceny peníze. Vyřízení vrácení platby způsobem, který byl použit k placení, trvá asi 5-7 dnů.',
                'French' => 'Nous sommes désolés pour le dérangement. Nous venons de rembourser cette transaction pour vous. Veuillez compter 5 à 7 jours ouvrables pour recevoir le remboursement sur le compte ayant servi au paiement initial.',
                'Italian' => 'Ci scusiamo per questo inconveniente. Abbiamo provveduto a rimborsare questa transazione. Attendere dai 5 ai 7 giorni per permettere alla banca di elaborare la richiesta di rimborso attraverso il metodo di pagamento originale.',
                'Spain' => 'Te ofrecemos una disculpa por este inconveniente. Ya te reembolsamos esta transacción. Deja pasar de 5 a 7 días para que tu reembolso se realice mediante la forma de pago que utilizaste previamente. ',
                'Portuguese' => 'Desculpe-nos pela inconveniência. Nós reembolsamos essa transação para você. Por favor, aguarde entre 5 e 7 dias para que o reembolso seja processado usando o mesmo método de pagamento usado na compra.',
                'German' => 'Wir entschuldigen uns für diese Unannehmlichkeiten. Wir haben dir diese Transaktion zurückerstattet. Bitte gib uns 5 - 7 Tage Zeit deinen Betrag zurück auf dein ursprüngliches Zahlungsmittel zu buchen.',
                'Danish' => 'Vi undskylder ulejligheden. Vi har tilbagebetalt transaktionen til dig. Der kan gå op til 5-7 dage, før din tilbagebetaling går ind via din oprindelige betalingsmåde.',
                'Belarusian' => 'Просім прабачэння за нязручнасці. Мы вярнулі вам грошы па гэтай транзакцыі. Калі ласка, звярніце ўвагу, што спатрэбіцца 5-7 дзён, пакуль вашыя грошы будуць вернуты тым жа шляхам, што быў выкарыстаны для аплаты. ',
                'Greek' => 'Ζητούμε συγγνώμη για την αναστάτωση. Τα χρήματα αυτής της συναλλαγής έχουν επιστραφεί και σε 5-7 ημέρες θα έχει ολοκληρωθεί η όλη διαδικασία.',
                'Finnish' => 'Pahoittelemme tästä aiheutunutta haittaa. Tapahtumaan liittyvä maksu on palautettu sinulle. Palautus tehdään alkuperäiseen maksutapaan, ja sen käsitteleminen saattaa kestää 5-7 päivää.',
                'Estonian' => 'Vabandame tekkinud ebamugavuste pärast. Oleme selle ostusumma teile tagastanud. Raha laekub teie algse makseviisi kontole 5-7 päeva jooksul.',
                'Dutch' => 'Onze excuses voor het ongemak. We hebben je het bedrag voor deze transactie terugbetaald. Houd rekening met 5 -7 dagen voordat je het geld terugkrijgt op dezelfde betaalwijze als waarmee je hebt betaald.',
                'Hungarian' => 'Elnézést kérünk a kellemetlenségért. A tranzakció összegét visszafizettük. A visszafizetés 5-7 munkanapon belül érkezik meg az eredetileg használt fizetési módra',
                'Indonesian' => 'Mohon maaf atas ketidaknyamanan ini. Kami telah mengembalikan dana transaksi ini kepada Anda. Silakan tunggu 5-7 hari untuk pemrosesan pengembalian dana ke metode pembayaran Anda yang asli.',
                'Japamese' => 'ご連絡頂きありがとうございます。このお取引に関しましては払い戻しのお手続きをしています。元の決済方法に払い戻しのお手続きが完了するまで5〜7日ほどかかりますので、ご了承ください。ご不便をおかけしてお詫び申し上げます。',
                'Korean' => 'Apologies for this inconvenience. We have refunded this transaction for you. Please allow 5-7 days for your refund to be processed back to your original payment method.',
                'lithuanian' => 'Atsiprašome už šiuos nepatogumus. Mes grąžinome jums pinigus. Palaukite 5–7 dienas, kol grąžinama suma bus apdorota naudojant jūsų pradinį mokėjimo būdą.',
                'Romanian' => 'Ne cerem scuze pentru aceste inconveniente. Am rambursat această tranzacție pentru tine. Te rugăm să aștepți 5-7 zile pentru ca rambursarea să fie procesată înapoi la metoda de plată inițială. ',
                'Russian' => 'Приносим вам извинения за доставленные неудобства. Мы возместили вам данный платеж. Поступление возмещенных средств на исходный источник платежа займет от 5 до 7 дней.',
                'Slovak' => 'Za nepríjemnosti sa ospravedlňujeme. Za túto transkaciu sme ti vrátili peniaze. Spracovanie vrátenia peňazí a ich vyplatenie pôvodným spôsobom platby trvá 5-7 dní. ',
                'Polish' => 'Przepraszamy za te niedogodności. Zwróciliśmy już Tobie pieniądze za tę transakcję. Proszę poczekać 5-7 dni zanim pieniądze wrócą na Twój rachunek, zgodnie z pierwotną formą płatności.',
                'Croatian' => 'Ispričavamo se zbog neugodnosti. Refundirali smo vam ovu transakciju. Molimo dopustite 5-7 dana za obradu povrata natrag na vaš izvorni način plaćanja.',
                'Arabic' => 'نعتذر على الإزعاج. لقد قمنا بإعادة أموال هذه المعاملة إليك. يُرجى الانتظار لمدة 5 - 7 أيام حتى تتمكن من الحصول على المبلغ الخاص بك حسب طريقة الدفع الأصلية التي استخدمتها.',
                'Vietnamese' => 'Thành thật xin lỗi về sự bất tiện này. Chúng tôi đã hoàn trả tiền giao dịch này cho bạn. Vui lòng đợi từ 5 đến 7 ngày để giao dịch hoàn tiền được gửi vào tài khoản mà bạn đã dùng để mua hàng.',
                'Turkish' => 'Rahatsızlıktan dolayı özür dileriz. Bu işlemin bedelini size iade ettik. Lütfen ilk ödeme yöntemizine geri ödeme için 5-7 gün zaman tanıyın.',
                'Thai' => 'ขออภัยในความไม่สะดวก เราได้คืนเงินธุรกรรมนี้ให้คุณแล้ว การคืนเงินด้วยวิธีการชำระเงินเดิมอาจใช้เวลา 5-7 วัน',
                'Swedish' => 'Vi ber om ursäkt för besväret. Vi har gjort en återbetalning av den här transaktionen till dig. Var vänlig räkna med 5 - 7 dagar för att utföra återbetalningen tillbaka via ditt ursprungliga betalningssätt.',
                'Slovenian' => 'Opravičujemo se za to nevšečnost. To transakcijo smo vam povrnili. Prosimo, počakajte 5-7 dni, da se povračilo obravnava in nakaže na izvorno sredstvo vašega plačila.',
            ],
            'locale' => [
                "be" => "Belarusian",
                "cs" => "Czech",
                "da" => "Danish",
                "nl" => "Dutch",
                "en" => "English",
                "et" => "Estonian",
                "fi" => "Finnish",
                "de" => "German",
                "el" => "Greek",
                "hu" => "Hungarian",
                "id" => "Indonesian",
                "ja" => "Japanese",
                "ko" => "Korean",
                "lt" => "Lithuanian",
                "nb" => "Norwegian",
                "pt" => "Portuguese",
                "ro" => "Romanian",
                "ru" => "Russian",
                "sk" => "Slovak",
                "sl" => "Slovenian",
                "es" => "Spanish",
                "fr" => "French",
                "it" => "Italian",
                "sv" => "Swedish",
                "th" => "Thai",
                "tr" => "Turkish",
                "vi" => "Vietnamese",
                "ar" => "Arabic",
                "hr" => "Croatian"
            ],

        ]
    ],

    //aliexpress平台
];