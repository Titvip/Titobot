<?php

header('Content-Type: application/json', false);
error_reporting(-1); // تعطيل التحذيرات
date_default_timezone_set('Asia/Baghdad');



/**
 installing redis :
git clone https://www.github.com/phpredis/phpredis.git
cd phpredis
phpize && ./configure && make && sudo make install

Add extension=redis.so in your in /etc/php/8.0/apache2/php.ini

to make a password :
go to :

/etc/redis

click on : redis.conf

search for : 

    # requirepass foobared

    change foobared to your password !
    Make sure you choose something pretty long, 32 characters or so would probably be good, it's easy for an outside user to guess upwards of 150k passwords a second, as the notes in the config file mention.

    To shut down redis... check in your config file for the pidfile setting, it will probably be:
        pidfile /var/run/redis/redis-server.pid
        From the command line, run:
        cat /var/run/redis/redis-server.pid

        That will give you the process id of the running server, then just kill the process using that pid:
        kill 3832

 */





$redis = new Redis(); //اتصل بالريدز
$redis->connect('127.0.0.1', 6379);
#print_r($redis->get("gifts:1259819993"));
#$redis->flushDB();
#print_r($redis->get("2008718282"));
#print_r($redis->get("turbo:1259819993"));
#$allKeys = $redis->keys('*');
#print_r($allKeys); // nothing here
#

/**
 * يحتوي على التوكن
 * يحتوي بيانات و اتصال الداتا بيس
 * يحتوي ايديات المطورين
 */

// CHANGE THIS:
const DB_USERNAME = "Titvip"; // يوزر قاعده البيانات
const DB_PASSWORD = "sweetvipymod1234ali"; // رمز
const DB_NAME = "Titobot"; // اسم قاعده البيانات
const BROADCASTPATH = "/var/www/html/broadcast";


$settings = [
    'bot_info' => [
        'token' => '5110181145:AAE7adRS3OJS_XgT19iFxDu0o8CxT5qs6KE', // bot token
    ],
    'db' => [
        'dbType' =>
        [
            'user' => DB_USERNAME,
            'password' => DB_PASSWORD,
            'database' => DB_NAME
        ]
    ],
    'sudo_info' => [
        0 => 1259819993, // ايدي المطور الاول
        1 => 2008718282, // ايدي المطور الثاني
    ]
];



$TOKEN = $settings['bot_info']['token']; // $settings <= نجلب التوكن من متغير

/**
 * نحمل المكتبه
 */
if (!file_exists('Telegram.php')) {
    copy("https://mohammed-api.com/Telegram/library.php", 'Telegram.php');
}


try {
    // نجلب بيانات الاتصال 
    $PDO = new PDO("mysql:host=localhost", $settings['db']['dbType']['user'], $settings['db']['dbType']['password'], array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ));
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
// نقوم بصناعه التيبلات

$dbname = "`" . str_replace("`", "``", DB_NAME) . "`";
$PDO->query("CREATE DATABASE IF NOT EXISTS $dbname");
$PDO->query("use $dbname");

$PDO->query("CREATE TABLE IF NOT EXISTS `users` (
    `id` int NOT NULL AUTO_INCREMENT,
    `ids` VARCHAR(20) NOT NULL,
    `coin` VARCHAR(20) DEFAULT 0,
    `convert` int DEFAULT 0, #howmuch convert he did.
    `chusub` int DEFAULT 0, # channels or supergroups he joined .
    `gifts` int DEFAULT 0, # gift collect .
    `reqmem` int DEFAULT 0, # howmuch participant he requested .
    `shares` varchar(100) DEFAULT 0, # how much time he shares invite link
    #`lang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=360 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

$PDO->query("CREATE TABLE IF NOT EXISTS `channels` (
    `id` int NOT NULL AUTO_INCREMENT,
    `chid` VARCHAR(25) NOT NULL, #chat id of his (supergroup/channel).
    `hcount` int NULL, # how much subs the users want.
    `wcount` int NULL, # how much the channel was.
    `investid` int NULL, # id of user who want this investment.
    `attainment` int NULL, # how much members he got from bot to this channel ?.
    `status` TEXT NULL, # (on/off) this channel ?.
    `link` VARCHAR(100) NOT NULL, #link of his (supergroup/channel).
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=360 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");




// نصنع ملف الاعدادات



require_once 'Telegram.php'; // نستدعي المكتبه




if (isset($botid)) {
    define('SETTINGFILE',  __DIR__ . "/info/settings_$botid.json"); // SETTING FILE
    define('TXTFILE',  __DIR__ . "/info/txt_$botid.txt"); // TXT FILE
}

if (defined('TXTFILE') && !empty(@file_get_contents(TXTFILE))) {
    $_MSG = "\n\n• ملاحظه مهمه جداً قبل التعديل لاتقم بحذف هذه العلامات ^ ولاتقم بأضافه اي منها !\n• بالنسبه الى ؛ نقاط رابط الدعوه و جمع نقاط ، و نقاط الهدايه فهي مترتبه بالتسلسل. \n\n• اول رقم 5 يرمز لـ نقاط رابط الدعوه.\n\n• الثاني  50 يرمز لـ جمع النقاط اثناء الاشتراك في المجموعات او القنوات . \n\n• الثالث ؛ 2 يرمز لنقاط الهديه\n\nيرجى مراعات الامر عند تغير الارقام هذه .\n ~ Mohammed Sami (@xx0bb)";
    $_prefix = "\r\n(^^^^^^^^)\n\r";
    if (isset($name_tag)) {
        $REPLACE = str_replace("$_MSG", '', str_replace("name_tag", $name_tag, file_get_contents(TXTFILE)));
    } else {
        $REPLACE = str_replace("$_MSG", '', file_get_contents(TXTFILE));
    }
    $_MSGS_DEFINED = array_values(array_filter(explode($_prefix, $REPLACE)));
    unset($_MSGS_DEFINED[0]);
    $_MSGS_DEFINED = array_values($_MSGS_DEFINED);
}



// msgs
if (isset($_MSGS_DEFINED) && is_array($_MSGS_DEFINED) && file_exists(TXTFILE) && defined('TXTFILE')) {

    // COINS 

    define('INVITE', $_MSGS_DEFINED[7]); // عدد الزياده اثناء الانضمام من خلال رابط الدعوه
    define('COLLECT', $_MSGS_DEFINED[8]); // عدد الزياده اثناء الانضمام الى القنوات او الكروبات
    define('GIFTS', $_MSGS_DEFINED[9]); // عدد نقاط الهديه اليوميه

    // SUDO MSGs
    define('SUDOMSG', $_MSGS_DEFINED[0]);
    define('SUDOMSG2', $_MSGS_DEFINED[1]);
    define('SUDOMSG3', $_MSGS_DEFINED[2]);
    define('SUDOMSG4', $_MSGS_DEFINED[3]);
    define('SUDOMSG5', $_MSGS_DEFINED[4]);

    // bot msgs
    define('START', $_MSGS_DEFINED[5]);
    define('COINMSG', $_MSGS_DEFINED[6]);
} else {

    // COINS 

    define('INVITE', 5); // عدد الزياده اثناء الانضمام من خلال رابط الدعوه
    define('COLLECT', 10); // عدد الزياده اثناء الانضمام الى القنوات او الكروبات
    define('GIFTS', 2); // عدد نقاط الهديه اليوميه

    // SUDO MSGs
    define('SUDOMSG', "*• اهلا بالمطور ؛ 🛐. \n\n• تستطيع هنا التحكم في هذَا البوت ؛🤖.\n\n• اذا واجهت مشاكل يرجى ارسالها بصورة، الى ؛ @xx0bb ، 🕯.*");
    define('SUDOMSG2', "*• ارسل بهذه الصورة؛ \nالايدي:عدد النقاط التي سترسلها او تخصمها . \n\nعلى سبيل المثال ؛ \n929293919:10 \nسيتم اضافه 10 نقاط له. \n\n200302002:-10\n\nسيتم خصم 10 نقاط منه ؛ 💸.\n- يمكنك قي اي وقت استعمال هذه الطريقه*");
    define('SUDOMSG3', "*• اولاً ؛ قم برفع البوت ادمن في قناة معينه ،🖤'.\n\nملحوظه؛ يجب ان تكون لديه صلاحيات دعوة مستخدمين من خلال رابط الدعوة ،🛐'.\n\n• ثانياً ؛ ارسل توجيه من اي رساله في القناة الى هنا ،🕯'.*");
    define('SUDOMSG4', "*• تمت اضافه قناة ڪَ قناة اساسيه للروبوت ، ⚠️'\n\n• تستعمل هذه القناة لنشر الاشعارات، عند اكتمال قناة معينه من التمويل ، ☑️'\n\n• كذلك تستعمل هذه القناة ، ڪَ تحقق من الشخص عند انضمامه من خلال رابط الدعوة ، 🤖' \n\n• ملحوظه مهمه، اذا تم حذف هذه القناة او ربما تم تنزيل البوت ادمن منها لأي سبب كان، فسيتم الرجوع الى الوضع الطبيعي وهو ، ان يضغط الشخص زر تحقق وحسب ، 💗'*");
    define('SUDOMSG5', "*• في هذا القسم يمكنك الدخول الى الرسائل او النقاط الخاصه بهذا الروبوت؛ 🤖.\n\n• قم بتعديل ماترغب به ثم ارسل الي هذا الملف . \n\n• يجب ان يكون امتداده ؛ ( txt ) حصراً . \n\n• انتباه مهم جداً ! \n\n• لاتقم بحذف علامات (^) داخل الفايل ، ولاتقم بأضافه اكثر من 8 علامات (^)\n• بالنسبه الى ؛ نقاط رابط الدعوه و جمع نقاط ، و نقاط الهدايه فهي مترتبه بالتسلسل. \n\n• اول رقم 5 يرمز لـ نقاط رابط الدعوه.\n\n• الثاني  10 يرمز لـ جمع النقاط اثناء الاشتراك في المجموعات او القنوات . \n\n• الثالث ؛ 2 يرمز لنقاط الهديه\n\nيرجى مراعات الامر عند تغير الارقام هذه .*");

    // bot msgs
    $name_tag = isset($name_tag) ? $name_tag : "name_tag";
    define('START', "*• اهلأ بك عزيزي *" . $name_tag . "* 👋🏼 .\n\n• البوت مخصص لتمويل القنوات او المجموعات عن طريق تجميع النقاط .\n\n• قم بأختيار القسم الذي تريده من الكيبورد 👇🏽.*");
    define('COINMSG', "*مرحبا بك في قسم تجميع النقاط 📥 .\n\n• يمكنك الحصول على نقاط بطريقتين :\n\n1 - عن طريق الاشتراك في القنوات او المجموعات\n\n2 - عن طريق مشاركة رابط الدعوة الى اصدقائك و سوف تحصل على " . INVITE . " نقطه عند دخول اي شخص الى الرابط الخاص بك\n\n\n~ اذ كانت طريقه التجميع صعبه راسل المطور لشراء النقاط 💰 .*");
}




//KEYBOARD

define(
    'SUDOKEYBOARD',
    [
        'inline_keyboard' => [
            [
                ['text' => "• اذاعـهَ ؛ 📆'", 'callback_data' => 'broadcast']
            ],
            [
                ['text' => "• الاشتراك الاجباري ، 💗'", 'callback_data' => 'channels_']
            ],
            [
                ['text' => '• اعادة تنظيم التيبلات ؛ ⚠️', 'callback_data' => 're-arrage']
            ],
            [
                ['text' => '• اضف / حذف نقاط ؛ 💸', 'callback_data' => 'send-del-coin']
            ],
            [
                ['text' => '• ضع قناة الروبوت ؛ 🤖', 'callback_data' => 'setrobot']
            ],
            [
                ['text' => '• تعديل الكلايش والنقاط ؛ 📑', 'callback_data' => 'changetxt']
            ],
        ]
    ]

);

define('DASHBORD', [
    'inline_keyboard' => [
        [
            ['text' => "➕| اضف قناة ، 🛂", 'callback_data' => "addch1"], ['text' => "➖| حذف قـناة ، ⛔️", 'callback_data' => "delch1"],
        ],
        [
            ['text' => "🚮 | حذف القـنوات ، ♻️", 'callback_data' => "delchss"], ['text' => "📶 | عرض القـنوات ، 📈", 'callback_data' => "seechs"],
        ],
        [
            ['text' => '✖️ | الرجوع الى اوامر المطور ؛ 💗', 'callback_data' => 'backsudo']
        ],
    ]
]);


define('BACKSUDOKEYBOARD', ['inline_keyboard' => [[['text' => '✖️ | الرجوع الى اوامر المطور ؛ 💗', 'callback_data' => 'backsudo']],]]);

if (isset($from_id)) {
    $coin = $PDO->query("SELECT coin FROM `users` WHERE ids = $from_id")->fetchall(PDO::FETCH_ASSOC)[0]['coin'] ?? 0;
    if (isset($data) && $data === 'aquery') {
        $content = "- \"هذا الكيبورد لعرض نقاطـك وقيمتها ؛ $coin فقط لاغير ، 🕎\".";
        return AnswerCallbackQuery($callback_query_id, $content, true);
    }
    define(
        'STARTKEYBOARD',
        [
            'inline_keyboard' => [
                [
                    ['text' => "• عـَدد نقاطك ؛ $coin 💰", 'callback_data' => 'aquery']
                ],
                [
                    ['text' => '• تمـويل قناة/كروب ؛ 💗', 'callback_data' => 'investment']
                ],
                [
                    ['text' => '• تـَجميع نقاط؛ ➕', 'callback_data' => 'grouping']
                ],
                [
                    ['text' => '• التمـوَيـلاَت الجـّارية ؛ 📊', 'callback_data' => 'Currentfunds'], ['text' => '• مَعلومـات حسابك ؛ ☦️', 'callback_data' => 'accountinfo']
                ],
                [
                    ['text' => '• تحويـَل نقاط ؛ 💸', 'callback_data' => 'convertCoin']
                ],
                [
                    ['text' => '• الهَـديه اليـّومية ؛ 🛎', 'callback_data' => 'gifts']
                ]
            ]
        ]
    ); // نقوم بأنشاء الكيبورد);
}

define('MAINBACK', ['inline_keyboard' => [[['text' => "✖️| رجوع ؛ 💗", 'callback_data' => 'backmain']]]]);

// FILES 

if ((defined('SETTINGFILE') && !file_exists(SETTINGFILE)) || (defined('TXTFILE') && empty(@file_get_contents(TXTFILE)))) {
    @mkdir(__DIR__ . '/info');
    file_put_contents(SETTINGFILE, null);
    $_MSG = "\n\n• ملاحظه مهمه جداً قبل التعديل لاتقم بحذف هذه العلامات ^ ولاتقم بأضافه اي منها !\n• بالنسبه الى ؛ نقاط رابط الدعوه و جمع نقاط ، و نقاط الهدايه فهي مترتبه بالتسلسل. \n\n• اول رقم 5 يرمز لـ نقاط رابط الدعوه.\n\n• الثاني  50 يرمز لـ جمع النقاط اثناء الاشتراك في المجموعات او القنوات . \n\n• الثالث ؛ 2 يرمز لنقاط الهديه\n\nيرجى مراعات الامر عند تغير الارقام هذه .\n ~ Mohammed Sami (@xx0bb)";
    $_prefix = "\r\n(^^^^^^^^)\n\r";
    $_content_ = SUDOMSG . "$_prefix" . SUDOMSG2 . "$_prefix" . SUDOMSG3 . "$_prefix" . SUDOMSG4 . "$_prefix" . SUDOMSG5 . "$_prefix" . str_replace($name_tag, 'name_tag', START) . "$_prefix" . COINMSG;
    $_content_ .=  "$_prefix" . INVITE . "$_prefix" . COLLECT . "$_prefix" . GIFTS;
    file_put_contents(TXTFILE, "{$_MSG}\r$_prefix\r" . $_content_);
}

try { // نبدأ البرمجه...


    function report($text) // هذا الفنكشن يرسل للمطور مشاكل الخ...
    {
        global $chat_id;
        SendMessage($chat_id, json_encode($text, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), NULL, true, null);
    }

    function checker($next = null, $try = 0, $times = null) // الجيكير  
    {

        if (file_exists(__DIR__ . '/i')) {
            unlink(__DIR__ . '/i');
            die;
        }

        global $PDO;
        global $redis;
        global $message_id;
        global $chat_id;
        global $from_id;
        global $botid;
        global $botusername;
        global $settings;

        $getMyRedis = json_decode($redis->get($from_id), true); // getRedis
        if (!isset($next) && isset($getMyRedis["!stopping:$from_id"]) && $times != 0) {
            $next = $getMyRedis["!stopping:$from_id"];
        }

        if ($try >= 6) {
            $bot_API_markup = json_encode(['inline_keyboard' => [
                [
                    ['text' => "- استعمال رابط الدعوة ؛ 🤎", 'callback_data' => 'invite']
                ],
                [
                    ['text' => "- رجوع ،💗", 'callback_data' => 'backmain']
                ]
            ]]); // نقوم بأنشاء الكيبورد
            $content = "*- يبدو انه لاتوجد قنوات في الوقت الحالي؛🗓.\n\n- يمكنك مشاركة رابط الدعوه للحصول على النقاط 🛐'.*";
            EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);
            die;
        }

        if (!isset($next)) {
            $_sqlId = $PDO->query("SELECT id FROM channels ORDER BY RAND() LIMIT 1;")->fetchall(PDO::FETCH_ASSOC)[0]['id'] ?? 0;
            $query = $PDO->query("SELECT * FROM `channels` WHERE id = {$_sqlId} LIMIT 1")->fetchall(PDO::FETCH_ASSOC)[0];
            if (!isset($query['id'])) {
                $try = 6;
                $next = null;
                checker($next, $try);
            } else {
                $next = $query['id'];
            }
        } elseif (is_numeric($next)) {
            $query = $PDO->query("SELECT * FROM `channels` WHERE id = $next LIMIT 1")->fetchall(PDO::FETCH_ASSOC)[0];
        }


        if (!isset($query) || $times === 0) {

            $key = "turbo:$from_id";
            $MyRedis = json_decode($redis->get($key), true); // getRedis
            if (isset($MyRedis[$key])) {
                $c = '';
                foreach ($MyRedis[$key] as $value) {
                    $keyboard['inline_keyboard'][] = $value['keyboard'];
                    $c = $c . "{$value['chid']}!";
                }
                $keyboard['inline_keyboard'][] = [['text' => "- التالي ؛ ⬅️", 'callback_data' => "nt!$next!$c"]];
                $keyboard['inline_keyboard'][] = [['text' => '~ رجوع ~', 'callback_data' => 'backmain']];
                if (isset($keyboard)) {
                    $content = "*- اشترك في القنوات بالأسفل ، 🕯\n- ثم اضغط على التالي ؛ ⬅️\n\n- ڪَل قناة تشترك فيها تحصل على ؛ " . COLLECT . " نقطه 🛐. \n\n- في حال لم تعجبك قناة ، يمكنك اختيار ابلاغ 📛 ، ولن تظهر لك مجدداً ؛\n\n- ملاحظه؛ لاتنسى جمع الهديه اليوميه 😉.*";
                    EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $keyboard);
                    $redis->del($key);
                    die;
                }
            } else { // timeout
                $try = 6;
                $next = null;
                checker($next, $try);
            }
        }


        if ($query['attainment'] >= $query['hcount']) { // القناة خلصت تمويل !
            #report(['ended : ' . $query['link']]);
            $next = $next + 1;
            $getjson = json_decode(file_get_contents(SETTINGFILE), true); // نقوم بأنشاء فايل لعمل الاوامر ثم تصفيره
            if (isset($getjson['robotchannel'])) {
                $__id = $getjson['robotchannel'][0];
                $chat = GetChat($query['chid'])->result;
                if (isset($chat->title)) {
                    $content = "*• تم ✅ اكتمل تمويل القناة ؛ *[{$chat->title}]({$query['link']})* \n\n• بدأت في تاريخ ؛ \n- {$query['created_at']} ؛ ⏳\n\n• الممول ؛ *[{$query['investid']}](tg://user?id={$query['investid']})* ؛ 💗.\n\n• العدد المطلوب عند بدء تمويل هذه القناة هو ؛ {$query['hcount']} ، 🛐\n\n• عدد القناة قبل التمويل ؛ {$query['wcount']} ، 📊\n\n• هذا التمويل تم بواسطه ؛ \n• @$botusername 🤖.*";
                    $s = SendMessage($__id, $content, "markdown", true);
                    SendMessage($query['investid'], $content, "markdown", true);
                    if ($s->description == "Bad Request: need administrator rights in the channel chat") {
                        array_walk($settings['sudo_info'], function ($sudo) {
                            extract($GLOBALS);
                            $__url = $getjson['robotchannel'][1];
                            $content = "*• انتباه ؛ ⚠️\n• يرجى اعطاء صلاحيات ارسال الرسائل في قناة البوت للروبوت هذا ، لا استطيع ان ارسل في القناة الخاصه بك رسائل . \n\n*[• قناة البوت •]($__url)";
                            SendMessage($sudo, $content, "MARKDOWN", true, null);
                        });
                    }
                }
                // deleting 
                $PDO->query("DELETE FROM `channels` WHERE chid = '{$query['chid']}'");
                $PDO->query("SET @autoid :=0;\nUPDATE channels set id = @autoid := (@autoid+1);\nALTER TABLE channels AUTO_INCREMENT = 1;");
            }
            if (is_numeric($times)) {
                checker($next, $try, $times);
            } else {
                checker($next, $try);
            }
        }

        if (isset($getMyRedis["$from_id:{$query['chid']}"])) { // already joined ...
            #report(['already joined : ' . $query['link']]);
            $next = $next + 1;
            if (is_numeric($times)) {
                checker($next, $try, $times);
            } else {
                checker($next, $try);
            }
        } elseif (isset($getMyRedis["report:$from_id:{$query['chid']}"])) { // from report

            $next = $next + 1;
            if (is_numeric($times)) {
                checker($next, $try, $times);
            } else {
                checker($next, $try);
            }
        }

        if (isset($query['status']) && $query['status'] != 'on') { // status is off then try again !
            $next = $next + 1;
            if (is_numeric($times)) {
                checker($next, $try, $times);
            } else {
                checker($next, $try);
            }
        }



        $Bot_Admin = json_decode(json_encode(GetChatMember($query['chid'], $botid)), True)['result']['status'];
        if ($Bot_Admin != "administrator") { // bot is not admin
            $PDO->query("DELETE FROM `channels` WHERE chid = '{$query['chid']}'");
            $PDO->query("SET @autoid :=0;
            UPDATE channels set id = @autoid := (@autoid+1);
            ALTER TABLE channels AUTO_INCREMENT = 1;");

            $next = $next + 1;
            if (is_numeric($times)) {
                checker($next, $try, $times);
            } else {
                checker($next, $try);
            }
        }
        $me = json_decode(json_encode(GetChatMember($query['chid'], $from_id)), True)['result']['status'];
        usleep(30000);
        if ($me == "member" || $me == "administrator" || $me == "creator") { // if he joined 
            # report(['joined : ' . $query['link']]);

            $next = $next + 1;
            if (is_numeric($times)) {
                checker($next, $try, $times);
            } else {
                checker($next, $try);
            }
        }

        // if he left .


        if (isset($times) && $times > 0) {
            if ($me == 'left') {
                #report(['saved : ' . $query['link']]);
                $hcount = $query['hcount'];
                $investid = $query['investid'];
                $att = $query['attainment'] ?? 0;
                $link = $query['link'];
                $chat = GetChat($query['chid'])->result;
                $keyboard =
                    [
                        ['text' => mb_substr($chat->title, 0, 9, 'utf-8') . " [ $hcount/$att ] ", 'url' => $link], ['text' => "- ابلاغ ؛📛", 'callback_data' => "rtu!$next!{$query['chid']}"]
                    ]; // نقوم بأنشاء الكيبورد


                $key = "turbo:$from_id";
                $MyRedis = json_decode($redis->get($key), true); // getRedis

                if (isset($MyRedis[$key])) {
                    $MyRedis[$key][] = [
                        'keyboard' => $keyboard,
                        'chid' => $query['chid']
                    ];
                    $redis->set($key, json_encode($MyRedis, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
                    $redis->expire($key, 60);
                } else {
                    $MyRedis[$key][] = [
                        'keyboard' => $keyboard,
                        'chid' => $query['chid']
                    ];
                    $redis->set($key, json_encode($MyRedis, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
                    $redis->expire($key, 60);
                }
                $times -= 1;
                $try += 1;
                $next = $next + 1;
                checker($next, $try, $times);
            }
        }

        if ($me == 'left' && $times == null) {
            $next = $next + 1;
            $hcount = $query['hcount'];
            $wcount = $query['wcount'];
            $investid = $query['investid'];
            $att = $query['attainment'] ?? 0;
            $link = $query['link'];
            $chat = GetChat($query['chid'])->result;
            $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => mb_substr($chat->title, 0, 14, 'utf-8') . " [ $hcount/$att ] ", 'url' => $link]], [['text' => "- التالي ؛ ⬅️", 'callback_data' => "next!$next!{$query['chid']}"], ['text' => "- تخطي ؛ ✖️", 'callback_data' => "skip!$next"]], [['text' => "- ابلاغ ؛📛", 'callback_data' => "report!$next!{$query['chid']}"]], [['text' => "- رجوع ،💗", 'callback_data' => 'backmain']]]]); // نقوم بأنشاء الكيبورد
            $coin = $PDO->query("SELECT ids, coin FROM `users` WHERE ids = $from_id")->fetchall(PDO::FETCH_ASSOC)[0]['coin'] ?? 0;
            $content = "*- الممول : *[$investid](tg://user?id=$investid)* \n- عدد القناة اثناء بدء التمويل ؛ {$wcount} . \n- العدد المطلوب لهذه القناة ؛ {$hcount}\n\n- تم اكتمال عدد ؛ {$att} من المطلوب . \n- المتبقي ؛ " . $hcount - $att . " . ⏳'\n\n- اشترك فيها للحصول للحصول على " . COLLECT . " . نقطة؛💰.\n\n- ثم اضغط على التالي؛💸.\n\n- اضغط على ابلاغ للتبليغ عن القناة( اختياري سيتم تخطي القناة عند الضغط عليه ) 🗑.\n\n- اختر تخطي لتخطي القناة بدون الاشتراك ( لن تحصل على نقاط ) ؛🕯'\n- نقاطك : $coin*";
            EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);
            die;
        }
    } // end checker

    if (isset($update->my_chat_member) && $update->my_chat_member->new_chat_member->status == "kicked") { // شخص حظر البوت
        array_walk($settings['sudo_info'], function ($sudo) use ($update, $PDO) {
            $PDO->exec("DELETE FROM `users` WHERE ids = {$update->my_chat_member->chat->id}"); // delete user from db
            $content = "*• الاسم ؛ *[{$update->my_chat_member->chat->first_name}](tg://user?id={$update->my_chat_member->chat->id})*\n• الايدي ؛ {$update->my_chat_member->chat->id}\n• قام بحظر البوت ، تم حذفه من قواعد البيانات ، 💗*";
            SendMessage($sudo, $content, 'markdown');
        });
    }


    if (isset($message) && $itprivate) { // الاشتراك الاجباري .
        $getjson = json_decode(file_get_contents(SETTINGFILE), true);
        if (isset($getjson['channel'])) {
            $msg = "";
            foreach ($getjson['channel'] as $channel) {
                $ex            = explode(":@", $channel);
                $id            = $ex[0];
                $link          = $ex[1];
                $getchat       = GetChat($id);
                $GetChatMember = GetChatMember($id, $from_id);
                $title         = json_decode(json_encode($getchat), True)["result"]["title"];
                $left          = json_decode(json_encode($GetChatMember), True)['result']['status'] == "left";
                if ($left) {
                    $chboard['inline_keyboard'][] = [['text' => (string)$title, 'url' => $link]];
                    $msg       = $msg . "*- 𖡹 : * [$title]($link)\n\n";
                }
            }
            if ($chboard != null) {
                if (preg_match_all('#/start (.*)#', $text, $js)) { // من ينضم من رابط الدعوه
                    SendMessage($chat_id, "*⏳| ‏↓ اشترك بالأسفل . ‏\n\n- *$msg*\n📽| ثم اضغط\n- https://t.me/$botusername?start={$js[1][0]}*", "MARKDOWN", true, $message_id, json_encode($chboard));
                } else {
                    SendMessage($chat_id, "*⏳| ‏↓ اشترك بالأسفل . ‏\n\n- *$msg*\n📽| ثم ارسل\n- $text*", "MARKDOWN", true, $message_id, json_encode($chboard));
                }
                return false;
            }
        }
    }

    // check user if left ... 
    if (isset($update)) {
        if (!$itsupergroup and isset($data) || isset($message)) { // check user if left ... 
            $getMyRedis = $redis->get($from_id);
            if ($getMyRedis != false) {
                $getMyRedis = json_decode($getMyRedis, true);
            }
            if (isset($getMyRedis["!$from_id"])) {
                unset($getMyRedis["!stopping:$from_id"]);
                foreach ($getMyRedis["!$from_id"] as $key => $mychannels) {
                    $csv = GetChat($mychannels); // Bad Request: chat not found
                    if ($csv->description == "Bad Request: chat not found") {
                        continue;
                    }
                    $chat = $csv->result;
                    $me = json_decode(json_encode(GetChatMember($mychannels, $from_id)), True)['result']['status'];
                    if ($me != "member" && $me != "administrator"  && $me != "creator") {
                        unset($getMyRedis["$from_id:$mychannels"]);
                        unset($getMyRedis["!$from_id"][$key]);
                        $redis->set($from_id, json_encode($getMyRedis, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

                        $coin = $PDO->query("SELECT ids, coin FROM `users` WHERE ids = $from_id")->fetchall(PDO::FETCH_ASSOC)[0]['coin'] ?? 0;
                        $PDO->query("UPDATE `users` SET coin = " . $coin - COLLECT . " WHERE ids = '$from_id'");
                        $query = $PDO->query("SELECT * FROM `channels` WHERE chid = '$mychannels' LIMIT 1")->fetchall(PDO::FETCH_ASSOC)[0];
                        $next = $query['id'] + 1;
                        $hcount = $query['hcount'];
                        $wcount = $query['wcount'];
                        $investid = $query['investid'];
                        $att = $query['attainment'] ?? 0;
                        $link = $query['link'];
                        $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => mb_substr($chat->title, 0, 14, 'utf-8') . " [ $hcount/$att ] ", 'url' => $link]], [['text' => "- التالي ؛ ⬅️", 'callback_data' => "next!$next!{$query['chid']}"], ['text' => "- تخطي ؛ ✖️", 'callback_data' => "skip!$next"]], [['text' => "- ابلاغ ؛📛", 'callback_data' => "report!$next!" . $query['chid']]], [['text' => "- رجوع ،💗", 'callback_data' => 'backmain']]]]); // نقوم بأنشاء الكيبورد
                        $content = "*- لقد قمت بمغادرة  ؛ *[" . $chat->title . "]($link)* \n\n- تم خصم " . COLLECT . " من نقاطك ؛ ☦️. \n\n- يمكنك استرجاع نقاطك، من خلال الاشتراك الان ، وضغط التالي ؛ 💗.*\n نقاطك : " . $coin - COLLECT;
                        SendMessage($chat_id, $content, "MARKDOWN", true, null, $bot_API_markup);
                    }
                } // end foreach
            }
        }
    }


    // NewMesaage

    if (isset($text)) {
        $getjson = json_decode(file_get_contents(SETTINGFILE), true); // نقوم بأنشاء فايل لعمل الاوامر ثم تصفيره
        $coin = $PDO->query("SELECT ids, coin FROM `users` WHERE ids = $from_id")->fetchall(PDO::FETCH_ASSOC)[0]['coin'] ?? 0;

        if (preg_match_all('#/start (.*)#', $text, $js)) { // من ينضم من رابط الدعوه
            if ($from_id == $js[1][0]) {
                $bot_API_markup = STARTKEYBOARD;
                $content = START;
                return SendMessage($chat_id, $content, "MARKDOWN", true, null, $bot_API_markup);
            }
            $from_id = $js[1][0];
            if (isset($getjson['robotchannel']) && isset($getjson['robotchannel'][0]) && isset($getjson['robotchannel'][1])) {
                $__id = $getjson['robotchannel'][0];
                $__url = $getjson['robotchannel'][1];
                $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => "- تحقق انك لست روبوت 🤖.", 'callback_data' => "check!$from_id!$__id"]]]]); // نقوم بأنشاء الكيبورد
                $content = "*- \"اهلاً وسهلاً : *$name_tag* \".\n\n- يرجى الاشتراك في القناة بالاسفل ، ثم ضغط زر تحقق ! ✅.\n\n*[• اضغط هنا للاشتراك •]($__url)";
                return SendMessage($chat_id, $content, "MARKDOWN", true, null, $bot_API_markup);
            }
            $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => "- تحقق انك لست روبوت 🤖.", 'callback_data' => "check!$from_id"]]]]); // نقوم بأنشاء الكيبورد
            $content = "*- \"اهلاً وسهلاً : *$name_tag* \".\n\n- \"يرجى الضغط على الزر بالاسفل للتحقق انك لست روبوت ✅\".*";
            SendMessage($chat_id, $content, "MARKDOWN", true, null, $bot_API_markup);
        }

        if ($text == "/start") {
            $sql = "SELECT ids, coin FROM `users` WHERE ids = $from_id";
            $query = $PDO->query($sql)->fetchall(PDO::FETCH_ASSOC);
            if (empty($query)) {

                $insert_query = $PDO->exec("INSERT INTO `users` (ids) VALUES ('$from_id')");
            }
            $UsersCount = count($PDO->query("SELECT ids FROM `users`")->fetchall(PDO::FETCH_ASSOC));
            $coin = isset($query[0]['coin']) ? $query[0]['coin'] : 0;
            $bot_API_markup = STARTKEYBOARD;
            $content = START;
            SendMessage($chat_id, $content, "MARKDOWN", true, null, $bot_API_markup);
        }

        if (isset($getjson[$from_id]) && $getjson[$from_id] === 'investment') {
            unset($getjson[$from_id]);
            file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => "رجوع", 'callback_data' => 'backmain']],]]); // نقوم بأنشاء الكيبورد

            if (preg_match('#[1-9]#', $text)) {
                $x = (int) $text / 2;
                $coin = $PDO->query("SELECT ids, coin FROM `users` WHERE ids = $from_id")->fetchall(PDO::FETCH_ASSOC)[0]['coin'] ?? 0;
                if ($x > $coin) {
                    $content = "\"- لايجوز ان تمويل اعضاء يفوقون عدد نقاطك\".\n - كل عضو ب 2 من النقاط\n- عدد نقاطك : $coin";
                    return SendMessage($chat_id, $content, "MARKDOWN", true, null, $bot_API_markup);
                } else {
                    $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => '- "تمويل قناة عامة."', 'callback_data' => "public!$text"]], [['text' => '- "تمويل قناة خاصه."', 'callback_data' => "private!$text"]], [['text' => '- "تمويل مجموعه ."', 'callback_data' => "gp!$text"]], [['text' => '- "رجوع ."', 'callback_data' => 'backmain']]]]); // نقوم بأنشاء الكيبورد
                    $content = "*- \"تم اختيار تمويل $text اعضاء\" .\n- حدد طريقه تمويلك ;*";
                    SendMessage($chat_id, $content, "MARKDOWN", true, null, $bot_API_markup);
                }
            } else {
                $content = "\"- يمكنك ارسال ارقام فقط عند تمويل الاعضاء\".";
                SendMessage($chat_id, $content, "MARKDOWN", true, null, $bot_API_markup);
            }
        } // end investment

        if (isset($getjson[$from_id]) && $getjson[$from_id] === 'convertCoin') {
            unset($getjson[$from_id]);
            file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => "رجوع", 'callback_data' => 'backmain']],]]); // نقوم بأنشاء الكيبورد

            if (preg_match('#[0-9]#', $text) && strlen($text) >= 9) {
                if ($from_id == $text) {
                    $content = "*~ لايمكنك التحويل الى نفسك*";
                    return SendMessage($chat_id, $content, "MARKDOWN", true, null, $bot_API_markup);
                }
                $query = $PDO->query("SELECT ids FROM `users` WHERE ids = $text")->fetchall(PDO::FETCH_ASSOC);
                if (empty($query)) {
                    $content = "*~ هذا المستخدم لم ينضم الى البوت*";
                    return SendMessage($chat_id, $content, "MARKDOWN", true, null, $bot_API_markup);
                } else {
                    $getjson[$from_id] = ['sendcoin', $text];
                    file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
                    $uinfo = GetChat($text)->result;
                    unset($uinfo->photo);
                    $uinfo = json_encode($uinfo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    $content = "*~ تم ايجاده ! \n~ معلوماته: \n" . $uinfo . "\n\nارسل عدد النقاط الذي تريد تحويلها اليه بشرط ان لاتزود عن عدد نقاطك الحاليه .*";
                    return SendMessage($chat_id, $content, "MARKDOWN", true, null, $bot_API_markup);
                }
            } else {
                $content = "*• الايدي الذي ارسلته خطأ*";
                SendMessage($chat_id, $content, "MARKDOWN", true, null, $bot_API_markup);
            }
        } // end convertCoin

        if (isset($getjson[$from_id]) && $getjson[$from_id][0] === 'sendcoin') {
            $to = $getjson[$from_id][1];
            unset($getjson[$from_id]);
            file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => "رجوع", 'callback_data' => 'backmain']],]]); // نقوم بأنشاء الكيبورد

            if (preg_match('#[1-9]#', $text)) {

                $coin = $PDO->query("SELECT ids, coin FROM `users` WHERE ids = $from_id")->fetchall(PDO::FETCH_ASSOC)[0]['coin'] ?? 0;
                if ($text > $coin) {
                    $content = "*\"- لايمكنك ارسال نقاط اكثر من ما تمتلك, نقاطك الحاليه : $coin\".*";
                    return SendMessage($chat_id, $content, "MARKDOWN", true, null, $bot_API_markup);
                } elseif ($coin > 20) { // عمليه التحويل
                    $Targetcoin = $PDO->query("SELECT ids, coin FROM `users` WHERE ids = $to")->fetchall(PDO::FETCH_ASSOC)[0]['coin'] ?? 0;
                    $fee = $coin - $text;
                    $benefit = $Targetcoin + $text;
                    $myconvertion = 1 + $PDO->query("SELECT `convert` FROM `users` WHERE ids = $from_id")->fetchall(PDO::FETCH_ASSOC)[0]['convert'] ?? 0;
                    $PDO->query("UPDATE `users` SET coin = $benefit WHERE ids = $to");
                    $PDO->query("UPDATE `users` SET coin = $fee, `convert` = $myconvertion WHERE ids = $from_id");
                    $content = "*- \"تم تحويل عدد $text نقاط ، الى صاحب الايدي : $to \". \n- \"تم خصم $text من نقاطك\".\n- \"عدد نقاطك المتبقيه هي: $fee \".\n- \"تم ارسال تبليغ لصديقك \".*";
                    SendMessage($chat_id, $content, "MARKDOWN", true, null, $bot_API_markup);
                    SendMessage($to, "*- \"تم استلام المبلغ التالي $text\".\n- المرسل : *$name_tag* . \n- المستفيد : $to . \n- عدد عملاتك : \"$benefit\".\n- /start\n- شكراً لأستعمال خدماتنا.🖤*", "MARKDOWN", true, null, $bot_API_markup);
                } else {
                    $content = "\"- حدث خطأ ما\".";
                    SendMessage($chat_id, $content, "MARKDOWN", true, null, $bot_API_markup);
                }
            } else {
                $content = "\"- يمكنك ارسال ارقام فقط عند تحويل النقاط\".";
                SendMessage($chat_id, $content, "MARKDOWN", true, null, $bot_API_markup);
            }
        } // end sendcoin

        if ($itsupergroup && $text === 'تمويل الكروب') { // هنا امر يخص بس الكروب من العضو يموله
            if (isset($getjson[$from_id]) && $getjson[$from_id][0] === 'gp') {
                $howmuch = (int) $getjson[$from_id][1];
                unset($getjson[$from_id]);
                file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));

                $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => "~ الغاء التمويل ~", 'callback_data' => "cancelinvest!$chat_id"]],]]); // نقوم بأنشاء الكيبورد
                $coin = $PDO->query("SELECT ids, coin FROM `users` WHERE ids = $from_id")->fetchall(PDO::FETCH_ASSOC)[0]['coin'] ?? 0;
                $fee = (int) $howmuch / 2;
                $totalfee = (int) $coin - $fee;

                $cH_count = (int) GetChatMembersCount($chat_id)->result;
                $link = ExportChatInviteLink($chat_id)->result;

                $PDO->query("UPDATE `users` SET coin = $totalfee WHERE ids = $from_id");
                $myquery = $PDO->query("SELECT * FROM `channels` WHERE chid = '$chat_id'")->fetchAll(PDO::FETCH_ASSOC);

                $reqmem = $howmuch + $PDO->query("SELECT `reqmem` FROM `users` WHERE ids = $from_id")->fetchall(PDO::FETCH_ASSOC)[0]['reqmem'] ?? 0;
                $PDO->query("UPDATE `users` SET reqmem = $reqmem WHERE ids = '$from_id'");

                if (!isset($myquery[0])) {
                    $PDO->exec("INSERT INTO `channels` (chid, hcount, wcount, investid, link) VALUES ('$chat_id', '$howmuch', '$cH_count', '$from_id', '$link')");
                    $last_id = $PDO->lastInsertId();
                } else {
                    $hcount = $howmuch + $myquery[0]['hcount'];
                    $PDO->query("UPDATE `channels` SET hcount = $hcount, link = '$link' WHERE chid = '$chat_id'");
                }
                $content = "*- \" تم خصم ( $fee ) نقاط \".\n- \"وبدء تمويل الكروب  $howmuch مشتركين 🚸\".\n\n- يرجى الانتباه :: اذا قمت بطرد البوت من المجموعه او تنزيله من الادمنيه اثناء التمويل سيتم ستبعاد مجموعتك من التمويل !!!*";
                SendMessage($chat_id, $content, "MARKDOWN", true, null);
                SendMessage($from_id, $content, "MARKDOWN", true, null);
                array_walk($settings['sudo_info'], function ($sudo) {
                    extract($GLOBALS);
                    $content = "*- \"تم بدء تمويل كروب $howmuch مشتركين 🚸\".\n\n- المستفيد : *$name_tag*\nايدي المستخدم : $from_id \n- ايدي الكروب : $chat_id\n- عملاته : $totalfee\n\n- الكروب : \n~ *[$group_title]($link)";
                    SendMessage($sudo, $content, "MARKDOWN", true, null, $bot_API_markup);
                });
            }
        } // end investment

        // اذا جان اكو توجيه وهذا التوجيه يخص القنوات
        if (isset($getjson[$from_id]) && $getjson[$from_id][0] === 'private' || $getjson[$from_id][0] === 'public') {
            if ($chat_forward) {
                $chat_id = $chat_forward_id;
                $me = json_decode(json_encode(GetChatMember($chat_id, $botid)), True)['result']['status'];
                if ($me != "administrator") {
                    $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => "- الغاء ! (ستخسر نقاطك)", 'callback_data' => "backmain"]],]]); // نقوم بأنشاء الكيبورد
                    $content = "*- قم برفع البوت ادمن ثم ارسل التوجيه مجددا*";
                    return SendMessage($from_id, $content, "MARKDOWN", true, null, $bot_API_markup);
                }

                $howmuch = (int) $getjson[$from_id][1];
                unset($getjson[$from_id]);
                file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
                $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => "~ الغاء التمويل ~", 'callback_data' => "cancelinvest!$chat_id"]],]]); // نقوم بأنشاء الكيبورد
                $coin = $PDO->query("SELECT ids, coin FROM `users` WHERE ids = $from_id")->fetchall(PDO::FETCH_ASSOC)[0]['coin'] ?? 0;
                $fee = (int) $howmuch / 2;
                $totalfee = (int) $coin - $fee;
                $cH_count = (int) GetChatMembersCount($chat_id)->result;

                if (isset($chat_forward_username)) {
                    $link = "https://t.me/{$chat_forward_username}";
                } else {
                    $link = ExportChatInviteLink($chat_id)->result;
                }
                $PDO->query("UPDATE `users` SET coin = $totalfee WHERE ids = $from_id");
                $myquery = $PDO->query("SELECT * FROM `channels` WHERE chid = '$chat_id'")->fetchAll(PDO::FETCH_ASSOC);

                $reqmem = $howmuch + $PDO->query("SELECT `reqmem` FROM `users` WHERE ids = $from_id")->fetchall(PDO::FETCH_ASSOC)[0]['reqmem'] ?? 0;
                $PDO->query("UPDATE `users` SET reqmem = $reqmem WHERE ids = '$from_id'");


                if (!isset($myquery[0])) {
                    $PDO->exec("INSERT INTO `channels` (chid, hcount, wcount, investid, link) VALUES ('$chat_id', '$howmuch', '$cH_count', '$from_id', '$link')");
                } else {
                    $hcount = $howmuch + $myquery[0]['hcount'];
                    $PDO->query("UPDATE `channels` SET hcount = $hcount, link = '$link' WHERE chid = '$chat_id'");
                }
                $content = "*- \" تم خصم ( $fee ) نقاط \".\n- \"وبدء تمويل القناة  $howmuch مشتركين 🚸\".\n\n- يرجى الانتباه :: اذا قمت بطرد البوت من القناة او تنزيله من الادمنيه اثناء التمويل سيتم استبعاد قناتك من التمويل !!!*";
                SendMessage($from_id, $content, "MARKDOWN", true, null, json_encode(['inline_keyboard' => [[['text' => "رجوع", 'callback_data' => 'backmain']],]]));
                array_walk($settings['sudo_info'], function ($sudo) {
                    extract($GLOBALS);
                    $content = "*- \"تم بدء تمويل القناة $howmuch مشتركين 🚸\".\n\n- المستفيد : *$name_tag*\nايدي المستخدم : $from_id \n- ايدي القناة : $chat_id\n- عملاته : $totalfee\n\n- القناة : \n~ *[$chat_forward_title]($link)";
                    SendMessage($sudo, $content, "MARKDOWN", true, null, $bot_API_markup);
                });
            }
        }
    } // end of text


    // data ///
    if (isset($data)) {
        $getjson = json_decode(file_get_contents(SETTINGFILE), true); // نقوم بأنشاء فايل لعمل الاوامر ثم تصفيره
        $coin = $PDO->query("SELECT ids, coin FROM `users` WHERE ids = $from_id")->fetchall(PDO::FETCH_ASSOC)[0]['coin'] ?? 0;

        // الهديه اليوميه
        if ($data === 'gifts') {
            $key = "gifts:$from_id";
            $JsonForRedis = $redis->get($key);
            if ($JsonForRedis != false) {
                $JsonForRedis = json_decode($JsonForRedis, true);
                if (isset($JsonForRedis[$key])) { // already get his gift
                    $content = "- لقَد حصلت على هديتك اليوميه لهذا اليوم حاول مجدداً غداً ؛ ⏳";
                    return AnswerCallbackQuery($callback_query_id, $content, true);
                } else {
                    // حصل نقاطه
                    $PDO->query("UPDATE `users` SET coin = " . $coin + GIFTS . " WHERE ids = '$from_id'");
                    $content = "- مَبروك حصولـك على " . GIFTS . " نقطه؛ 🕯\n- نقاطك الان ؛ " . $coin + GIFTS . " 💗'";
                    AnswerCallbackQuery($callback_query_id, $content, true);
                    $bot_API_markup = STARTKEYBOARD;
                    $bot_API_markup['inline_keyboard'][0] = [['text' => "• عـَدد نقاطك ؛ " . $coin + GIFTS . " 💰", 'callback_data' => 'aquery']];
                    $content = START;
                    EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);
                }

                $redis->del($key);
                $JsonForRedis[$key] = true; // save if he get gift or not.
                $redis->set($key, json_encode($JsonForRedis, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
                $newDate = strtotime('+1 day', time());
                $redis->expireAt($key, $newDate); // after 1 day
            } else {
                $PDO->query("UPDATE `users` SET gifts = gifts + 1,  coin = coin + " . GIFTS . " WHERE ids = '$from_id';");
                $content = "- مَبروك حصولـك على " . GIFTS . " نقطه؛ 🕯\n- نقاطك الان ؛ " . $coin + GIFTS . " 💗'";
                AnswerCallbackQuery($callback_query_id, $content, true);
                $bot_API_markup = STARTKEYBOARD;
                $bot_API_markup['inline_keyboard'][0] = [['text' => "• عـَدد نقاطك ؛ " . $coin + GIFTS . " 💰", 'callback_data' => 'aquery']];
                $content = START;
                EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);

                $JsonForRedis[$key] = true; // save if he get gift or not.
                $redis->set($key, json_encode($JsonForRedis, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
                $newDate = strtotime('+1 day', time());
                $redis->expireAt($key, $newDate); // after 1 day

            }
        }

        if ($data === 'accountinfo') { // معلومات حسابي


            $channels_query = $PDO->query("SELECT * FROM `channels` WHERE investid = $from_id")->fetchall(PDO::FETCH_ASSOC);
            $self_query = $PDO->query("SELECT * FROM `users` WHERE ids = $from_id")->fetchall(PDO::FETCH_ASSOC)[0];
            if (empty($channels_query)) {
                $countchannels = 0;
            } else {
                $countchannels = count($channels_query);
            }
            $myconvertion  = $self_query['convert'] ?? 0;
            $chusub  = $self_query['chusub'] ?? 0;
            $gifts   = $self_query['gifts'] ?? 0;
            $reqmem  = $self_query['reqmem'] ?? 0;
            $shares  = $self_query['shares'] ?? 0;



            $bot_API_markup['inline_keyboard'][] = [['text' => "~ رجوع ~", 'callback_data' => 'backmain']];
            $content = "*• مرحبا بك في معلومات حسابك في بوت التمويل 🌀\n\n- عدد القنوات او المجموعات الجاري تمويلها : $countchannels\n- عدد نقاط حسابك : $coin\n\n- عدد عمليات التحويل التي قمت بها : $myconvertion\n- عدد القنوات التي شتركت بها : $chusub\n- عدد الهدايا اليومية التي جمعتها : $gifts\n- عدد الاعضاء الذي قمت بطلبهم في عمليات التمويل : $reqmem\n\n- عدد مشاركاتك لرابط الدعوة : $shares*";
            EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, json_encode($bot_API_markup));
        }


        if ($data === 'Currentfunds') { // التمويلات التجاريه

            $query = $PDO->query("SELECT * FROM `channels` WHERE investid = $from_id")->fetchall(PDO::FETCH_ASSOC);
            if (empty($query)) {
                $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => "~ رجوع ~", 'callback_data' => 'backmain']]]]); // نقوم بأنشاء الكيبورد
                $content = "*- \"ليس لديك عمليات تجاريه.\"📊*";
                EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);
            } else {
                foreach ($query as $funds) {
                    $chat = GetChat($funds['chid'])->result;
                    $bot_API_markup['inline_keyboard'][] = [['text' => "ايدي الممول : {$funds['investid']}", 'callback_data' => 'cc']];
                    $att = isset($funds['attainment']) ? $funds['attainment'] : 0;
                    $bot_API_markup['inline_keyboard'][] = [['text' => $chat->title, 'url' => $chat->invite_link], ['text' => "{$funds['wcount']} : " . $funds['wcount'] + $funds['hcount'] . " => {$funds['hcount']} / $att", 'callback_data' => 'cc']];
                }
                $bot_API_markup['inline_keyboard'][] = [['text' => "~ رجوع ~", 'callback_data' => 'backmain']];
                $content = "*- \"اليـكَ عمليات التمويل الجارية.\"📊*";
                EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, json_encode($bot_API_markup));
            }
        }


        if ($data === 'invite') { // رابط لدعوه
            $c = "";
            $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => "~ رجوع ~", 'callback_data' => 'backmain']]]]); // نقوم بأنشاء الكيبورد
            $shareme =  $PDO->query("SELECT shares FROM `users` WHERE ids = $from_id")->fetchall(PDO::FETCH_ASSOC)[0]['shares'] ?? 0;
            $query = array_reverse($PDO->query("SELECT ids, shares FROM users ORDER BY CAST(shares AS unsigned)")->fetchall(PDO::FETCH_ASSOC));
            for ($i = 0; $i < 3; $i++) {

                $tag = "[{$query[$i]['ids']}](tg://user?id={$query[$i]['ids']})";
                $shares = $query[$i]['shares'];
                if ($i === 0) {
                    $smile = "🥇-> ";
                }
                if ($i === 1) {
                    $smile = "🥈-> ";
                }
                if ($i === 2) {
                    $smile = "🥉-> ";
                }
                $c = $c . "{$smile}{$tag} ($shares)\n\n";
            }


            $content = "*انسخ الرابط ثم قم بمشاركته مع اصدقائك 📥 .\n\n- كل شخص يقوم بالدخول ستحصل على " . INVITE . " نقطه 📊 .\n\n- بإمكانك عمل اعلان خاص برابط الدعوة الخاص بك 📬 .\n\n~ رابط الدعوة :\n\nhttps://t.me/$botusername?start=$from_id\n\n- مشاركتك للرابط : $shareme 🌀\n- المستخدمين الاكثر مشاركة لرابط الدعوى : \n\n*$c";
            EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);
        }


        if ($data === 'investment') {


            if ($coin <= 20) {
                $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => "تجميع نقاط", 'callback_data' => 'grouping']], [['text' => "رجوع", 'callback_data' => 'backmain']],]]); // نقوم بأنشاء الكيبورد
                $content = "*\"- عملاتك لاتكفي للتمويل\".\n\"- يجب ان تقوم بتجميع اكثر من 20 نقطه على الاقل.\"\n\"عملاتك الحاليه : $coin.\"*";
                EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);
            } else {
                $getjson[$from_id] = $data;
                file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));

                $bot_API_markup = json_encode([
                    'inline_keyboard' => [[['text' => '- "تمويل جميع نقاطك."', 'callback_data' => 'investall']],    [['text' => '- "تمويل 10 اعضاء ."', 'callback_data' => 'invest10']], [['text' => '- "رجوع ."', 'callback_data' => 'backmain']]]
                ]); // نقوم بأنشاء الكيبورد

                if ($coin >= 101) {
                    $bot_API_markup = json_encode([
                        'inline_keyboard' => [[['text' => '- "تمويل جميع نقاطك."', 'callback_data' => 'investall']],    [['text' => '- "تمويل 10 اعضاء ."', 'callback_data' => 'invest10']],    [['text' => '- "تمويل 100 عضو ."', 'callback_data' => 'invest100']], [['text' => '- "رجوع ."', 'callback_data' => 'backmain']]]
                    ]); // نقوم بأنشاء الكيبورد
                }
                if ($coin >= 1001) {
                    $bot_API_markup = json_encode([
                        'inline_keyboard' => [[['text' => '- "تمويل جميع نقاطك."', 'callback_data' => 'investall']],    [['text' => '- "تمويل 10 اعضاء ."', 'callback_data' => 'invest10']],    [['text' => '- "تمويل 100 عضو ."', 'callback_data' => 'invest100']],    [['text' => '- "تمويل 1000 عضو ."', 'callback_data' => 'invest1000']],    [['text' => '- "رجوع ."', 'callback_data' => 'backmain']]]
                    ]); // نقوم بأنشاء الكيبورد
                }
                if ($coin >= 10001) {
                    $bot_API_markup = json_encode([
                        'inline_keyboard' => [[['text' => '- "تمويل جميع نقاطك."', 'callback_data' => 'investall']],    [['text' => '- "تمويل 10 اعضاء ."', 'callback_data' => 'invest10']],    [['text' => '- "تمويل 100 عضو ."', 'callback_data' => 'invest100']],    [['text' => '- "تمويل 1000 عضو ."', 'callback_data' => 'invest1000']],    [['text' => '- "تمويل 10000 عضو ."', 'callback_data' => 'invest10000']],    [['text' => '- "رجوع ."', 'callback_data' => 'backmain']]]
                    ]); // نقوم بأنشاء الكيبورد
                }

                $content = "*• ارسل عدد الاعضاء المراد تمويلهم او يمكنك الاختيار من الازرار 🌐\n\n- ملاحضة : كل 1 عضو يساوي 2 نقطه \n\n- عدد نقاطك : $coin.\n- يمكنك تمويل *" . $coin + $coin . " عضو بالمجمل";
                EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);
            }
        }
        if ($data === 'investall') {
            unset($getjson[$from_id]);
            file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            $x = $coin + $coin;
            $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => '- "تمويل قناة عامة."', 'callback_data' => "public!$x"]], [['text' => '- "تمويل قناة خاصه."', 'callback_data' => "private!$x"]], [['text' => '- "تمويل مجموعه ."', 'callback_data' => "gp!$x"]], [['text' => '- "رجوع ."', 'callback_data' => 'backmain']]]]); // نقوم بأنشاء الكيبورد
            $content = "*- \"تم تحديد تمويل $x اعضاء\" .\n- حدد طريقه تمويلك ;*";
            EditMessageText($chat_id, $message_id, $content, '', "MARKDOWN", TRUE, $bot_API_markup);
        } elseif ($data === 'invest10') {
            unset($getjson[$from_id]);
            file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            $x = (int) str_replace('invest', '', $data);
            $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => '- "تمويل قناة عامة."', 'callback_data' => "public!$x"]], [['text' => '- "تمويل قناة خاصه."', 'callback_data' => "private!$x"]], [['text' => '- "تمويل مجموعه ."', 'callback_data' => "gp!$x"]], [['text' => '- "رجوع ."', 'callback_data' => 'backmain']]]]); // نقوم بأنشاء الكيبورد
            $content = "*- \"تم اختيار تمويل $x اعضاء\" .\n- حدد طريقه تمويلك ;*";
            EditMessageText($chat_id, $message_id, $content, '', "MARKDOWN", TRUE, $bot_API_markup);
        } elseif ($data === 'invest100') {
            unset($getjson[$from_id]);
            file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            $x = (int) str_replace('invest', '', $data);
            $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => '- "تمويل قناة عامة."', 'callback_data' => "public!$x"]], [['text' => '- "تمويل قناة خاصه."', 'callback_data' => "private!$x"]], [['text' => '- "تمويل مجموعه ."', 'callback_data' => "gp!$x"]], [['text' => '- "رجوع ."', 'callback_data' => 'backmain']]]]); // نقوم بأنشاء الكيبورد
            $content = "*- \"تم اختيار تمويل $x اعضاء\" .\n- حدد طريقه تمويلك ;*";
            EditMessageText($chat_id, $message_id, $content, '', "MARKDOWN", TRUE, $bot_API_markup);
        } elseif ($data === 'invest1000') {
            unset($getjson[$from_id]);
            file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            $x = (int) str_replace('invest', '', $data);
            $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => '- "تمويل قناة عامة."', 'callback_data' => "public!$x"]], [['text' => '- "تمويل قناة خاصه."', 'callback_data' => "private!$x"]], [['text' => '- "تمويل مجموعه ."', 'callback_data' => "gp!$x"]], [['text' => '- "رجوع ."', 'callback_data' => 'backmain']]]]); // نقوم بأنشاء الكيبورد
            $content = "*- \"تم اختيار تمويل $x اعضاء\" .\n- حدد طريقه تمويلك ;*";
            EditMessageText($chat_id, $message_id, $content, '', "MARKDOWN", TRUE, $bot_API_markup);
        } elseif ($data === 'invest10000') {
            unset($getjson[$from_id]);
            file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            $x = (int) str_replace('invest', '', $data);
            $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => '- "تمويل قناة عامة."', 'callback_data' => "public!$x"]], [['text' => '- "تمويل قناة خاصه."', 'callback_data' => "private!$x"]], [['text' => '- "تمويل مجموعه ."', 'callback_data' => "gp!$x"]], [['text' => '- "رجوع ."', 'callback_data' => 'backmain']]]]); // نقوم بأنشاء الكيبورد
            $content = "*- \"تم اختيار تمويل $x اعضاء\" .\n- حدد طريقه تمويلك ;*";
            EditMessageText($chat_id, $message_id, $content, '', "MARKDOWN", TRUE, $bot_API_markup);
        }

        $ex = array_filter(explode('!', $data));
        if (isset($ex[0])) {
            $data = $ex[0];
            $pdata = $ex[1];
        }

        // collect 

        if ($data === 'collect') {
            $key = "$from_id";
            $getMyRedis = $redis->get($key);
            if ($getMyRedis != false) {
                $getMyRedis = json_decode($getMyRedis, true);
                $next = $getMyRedis[$key]["!stopping:$from_id"];
            } else {
                $next = null;
            }
            checker($next);
        }

        // TurboCollect
        if ($data === 'TurboCollect') {
            $content = "- يرجىَ الانتظار بينما يتم جلب القنوات؛ ⏳";
            AnswerCallbackQuery($callback_query_id, $content, true);
            $key = "!stopping:$from_id";
            $getMyRedis = $redis->get($key);
            if ($getMyRedis != false) {
                $getMyRedis = json_decode($getMyRedis, true);
                $next = $getMyRedis[$key];
            } else {
                $next = null;
            }
            checker($next, 0, 3);
        }

        // next 

        if ($data === 'next') {
            $me = json_decode(json_encode(GetChatMember($ex[2], $from_id)), True)['result']['status'];
            $next = $pdata;

            if ($me != "member" && $me != "administrator"  && $me != "creator") {
                $content = "- اشترك في القناة اولأ ; 🖤.";
                return AnswerCallbackQuery($callback_query_id, $content, true);
            } else {
                $q = $PDO->query("SELECT chusub FROM `users` WHERE ids = '$from_id'")->fetchall(PDO::FETCH_ASSOC)[0];
                $chusub = 1 + $q['chusub'] ?? 0;
                $PDO->query("UPDATE `users` SET coin = " . $coin + COLLECT . ", chusub = $chusub WHERE ids = '$from_id'");

                $att = $PDO->query("SELECT attainment FROM `channels` WHERE chid = '$ex[2]'")->fetchall(PDO::FETCH_ASSOC)[0]['attainment'] ?? 0;
                $PDO->query("UPDATE `channels` SET attainment = " . $att + 1 . " WHERE chid = '$ex[2]'");

                $JsonForRedis = $redis->get($from_id);
                if ($JsonForRedis != false) {
                    $redis->del($from_id);
                    $JsonForRedis = json_decode($JsonForRedis, true);

                    $JsonForRedis["!stopping:$from_id"] = $next; // where did he stopped !

                    $JsonForRedis["$from_id:$ex[2]"] = $ex[2]; // save if he joined or not.
                    $JsonForRedis["!$from_id"][] = $ex[2]; // save if he joined or not.
                    $redis->set($from_id, json_encode($JsonForRedis, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
                    $newDate = strtotime('+2 month', time());
                    $redis->expireAt($from_id, $newDate); // after 2 month
                } else {

                    $JsonForRedis["!stopping:$from_id"] = $next; // where did he stopped !


                    $JsonForRedis["$from_id:$ex[2]"] = $ex[2]; // save if he joined or not.
                    $JsonForRedis["!$from_id"][] = $ex[2]; // save if he joined or not.
                    $redis->set($from_id, json_encode($JsonForRedis, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
                    $newDate = strtotime('+2 month', time());
                    $redis->expireAt($from_id, $newDate); // after 2 month
                }

                $content = "- مبروك تم الحصول على ؛ " . COLLECT . " نقطة !💞.";
                AnswerCallbackQuery($callback_query_id, $content, true);
                checker($next);
            }
        }

        // TurboNext
        if ($data === 'nt') {
            $next = $pdata;
            unset($ex[0]);
            unset($ex[1]);
            $i = 0;
            foreach ($ex as $channels_id) {
                $me = json_decode(json_encode(GetChatMember($channels_id, $from_id)), True)['result']['status'];

                if ($me == "member" || $me == "administrator" || $me == "creator") { // اشترك بيها
                    $i++;
                    $ex[2] = $channels_id;
                    $att = $PDO->query("SELECT attainment FROM `channels` WHERE chid = '$ex[2]'")->fetchall(PDO::FETCH_ASSOC)[0]['attainment'] ?? 0;
                    $PDO->query("UPDATE `channels` SET attainment = " . $att + 1 . " WHERE chid = '$ex[2]'");

                    $JsonForRedis = $redis->get($from_id);
                    if ($JsonForRedis != false) {
                        $redis->del($from_id);
                        $JsonForRedis = json_decode($JsonForRedis, true);

                        #$JsonForRedis["!stopping:$from_id"] = $next; // where did he stopped !

                        $JsonForRedis["$from_id:$ex[2]"] = $ex[2]; // save if he joined or not.
                        $JsonForRedis["!$from_id"][] = $ex[2]; // save if he joined or not.
                        $redis->set($from_id, json_encode($JsonForRedis, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
                        $newDate = strtotime('+2 month', time());
                        $redis->expireAt($from_id, $newDate); // after 2 month
                    } else {

                        #$JsonForRedis["!stopping:$from_id"] = $next; // where did he stopped !

                        $JsonForRedis["$from_id:$ex[2]"] = $ex[2]; // save if he joined or not.
                        $JsonForRedis["!$from_id"][] = $ex[2]; // save if he joined or not.
                        $redis->set($from_id, json_encode($JsonForRedis, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
                        $newDate = strtotime('+2 month', time());
                        $redis->expireAt($from_id, $newDate); // after 2 month
                    }
                } // end of check
            }

            if ($i === 0) {
                $content = "- يرجى الاشتراك في قناة واحدة على الاقل ، او اختر رجوع ؛ 🛎'";
                AnswerCallbackQuery($callback_query_id, $content, true);
            } else {
                $q = $PDO->query("SELECT chusub FROM `users` WHERE ids = '$from_id'")->fetchall(PDO::FETCH_ASSOC)[0];
                $chusub = $i * 1 + $q['chusub'] ?? 0;
                $PDO->query("UPDATE `users` SET coin = " . $coin + COLLECT * $i . ", chusub = $chusub WHERE ids = '$from_id'");
                $content = "- لَقد قمت بالاشتراك بـ $i قناة ، وحصلت على استحقاقك ، " . $i * COLLECT . " نقطه ؛ ✅ \n\n- جاري نقلك الى التيربو التاَلي..⏳.";
                AnswerCallbackQuery($callback_query_id, $content, true);
                checker($pdata, 0, 3);
            }
        }


        // skip 

        if ($data === 'skip') {
            $content = "- جـاـرَـي التخـطي ؛ ↖️.'";
            AnswerCallbackQuery($callback_query_id, $content, true);
            checker($pdata);
        }

        // report 

        if ($data === 'report' || $data === 'rtu') {

            array_walk($settings['sudo_info'], function ($sudo) {
                extract($GLOBALS);
                $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => 'توقيف القناة', 'callback_data' => "ys!$ex[2]"]], [['text' => '"الغاء."', 'callback_data' => 'backmain']],]]); // نقوم بأنشاء الكيبورد
                $chat = GetChat($ex[2])->result;
                $query = $PDO->query("SELECT * FROM `channels` WHERE chid = $ex[2]")->fetchall(PDO::FETCH_ASSOC)[0] ?? null;
                $hcount = $query['hcount'];
                $wcount = $query['wcount'];
                $att = $query['attainment'] ?? 0;
                $investid = $query['investid'];
                $content = "*• تبليغ حول قناة/سوبر كروب ؛ 📛\n\n• الايدي ؛ $ex[2] ؛ 🆔.\n\n• القناة/الكروب  ؛ *[$chat->title]($chat->invite_link)* ؛💸.\n\n• ملاحظه مفيده، اذا لم تتمكن من الدخول الى القناة، فهذه القناة غالباً محذوفه، من الافضل توقيفها ؛🕯.\n\n• يفضل مراسله صاحبها للتحقق منه ؛ 💗.\nللدخول الى صاحب القناة؛ ⏳\n• *[$investid](tg://user?id=$investid)*\n\n• عدد القناة اثناء بدء التمويل؛ $wcount . \n- المطلوب : $hcount\n\nالمتبقي ؛ " . $hcount - $att . "\n~ $att / $hcount*";
                SendMessage($sudo, $content, "MARKDOWN", true, null, $bot_API_markup);
            });
            // redis
            $getMyRedis = $redis->get($from_id);
            if ($getMyRedis != false) {
                $getMyRedis = json_decode($getMyRedis, true);
                $getMyRedis["report:$from_id:$ex[2]"] = true;
                $redis->set($from_id, json_encode($getMyRedis, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
                $newDate = strtotime('+2 month', time());
                $redis->expireAt($from_id, $newDate); // after 2 month
            } else {
                $getMyRedis["report:$from_id:$ex[2]"] = true;
                $redis->set($from_id, json_encode($getMyRedis, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
                $newDate = strtotime('+2 month', time());
                $redis->expireAt($from_id, $newDate); // after 2 month
            }

            $content = "- تم التبليـغ لـن تظهـَر لـك هذه القـّناة مجدداً ؛ 🛐'.";
            AnswerCallbackQuery($callback_query_id, $content, true);
            if ($data === 'rtu') {
                checker($pdata, 0, 3);
            } else {
                checker($pdata);
            }
        }


        // check robot
        if ($data === 'check') {
            $myquery = $PDO->query("SELECT ids FROM `users` WHERE ids = '$from_id'")->fetchAll(PDO::FETCH_ASSOC);
            if (empty($myquery)) {
                if (isset($ex[2])) { // with channel
                    $Bot_Admin = json_decode(json_encode(GetChatMember($ex[2], $botid)), True)['result']['status'];
                    if ($Bot_Admin == "administrator") { // bot is admin
                        $me = json_decode(json_encode(GetChatMember($ex[2], $from_id)), True)['result']['status'];
                        if ($me == 'left') {
                            $content = "~ قم بالاشتراك في قناة البوت اولا ،⚠️'";
                            return AnswerCallbackQuery($callback_query_id, $content, true);
                        }
                    }
                }
                $PDO->exec("INSERT INTO `users` (ids) VALUES ('$from_id')");

                $query = $PDO->query("SELECT coin, shares FROM `users` WHERE ids = '$pdata'")->fetchall(PDO::FETCH_ASSOC);
                $plus_pdata = INVITE + $query[0]['coin'] ?? 0;
                $plus_shares = 1 + $query[0]['shares'] ?? 0;

                $PDO->query("UPDATE `users` SET coin = coin + " . INVITE . ", shares = shares + 1 WHERE ids = '$pdata'");

                $coinN = $PDO->query("SELECT ids, coin FROM `users` WHERE ids = $from_id")->fetchall(PDO::FETCH_ASSOC)[0]['coin'] ?? 0;
                $plus = INVITE + $coinN;
                $PDO->query("UPDATE `users` SET coin = $plus WHERE ids = '$from_id'");
                $content = "*• قام احد العملاء بالانضمام الى الروبوت من خلال رابط الدعوة ! 🔺 \n\n• لقد اكتسبت " . INVITE . " نقاط خلال هذه العملية ! ☑️.\n\n• عدد نقاطك : $plus_pdata\n• عدد مرات مشاركه الرابط : $plus_shares . \n\n• بيانات الذي استعمل رابط الدعوة: \n\n~ اسم العميل : *$name_tag* . \n\n~ ايدي العميل : $from_id . \n\n~ عدد نقاطه : $plus .*";
                SendMessage($pdata, $content, "MARKDOWN", true);
            }
            $coin = $PDO->query("SELECT ids, coin FROM `users` WHERE ids = $from_id")->fetchall(PDO::FETCH_ASSOC)[0]['coin'] ?? 0;
            $bot_API_markup = STARTKEYBOARD;
            $bot_API_markup['inline_keyboard'][0] = [['text' => "• عـَدد نقاطك ؛ $coin 💰", 'callback_data' => 'aquery']];
            $content = START;
            EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);
        }

        // invest for groups
        if ($data === 'gp') { // من يدوس تمويل الكروب
            $getjson[$from_id] = [$data, $pdata];
            file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => "رجوع", 'callback_data' => 'backmain']],]]); // نقوم بأنشاء الكيبورد
            $content = "*- \"اتبع ما سأقوله\" :\n- \"اضفني الى مجموعتك ( يجب ان تكون مجموعتك خارقه )\".\n\n- \"لتحويل مجموعتك الى خارقه توجه الى الاعدادات -> اعدادات المجموعه الخاصه بك -> سجل المحادثه للأعضاء الجدد اجعله ''ظاهر'' حصرا\".\n\n- \"توجه الى اعدادات المجموعه -> اضف عضو -> ضع معرف البوت : @$botusername -> اضفه الى مجموعتك\" .\n\n- \"توجه الى اعدادات المجموعه -> اضف مشرف -> ضع معرف البوت : @$botusername -> قم بترقيته مشرف مع اعطاء صلاحيات ( الدعوه بأستخدام رابط الدعوة)\" .\n\nارسل في مجموعتك : تمويل الكروب*";
            EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);
        }

        if ($data === 'private') { // من يدوس تمويل قناة خاصه
            $getjson[$from_id] = [$data, $pdata];
            file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => "رجوع", 'callback_data' => 'backmain']],]]); // نقوم بأنشاء الكيبورد
            $content = "*- \"اتبع ما سأقوله\" :\n\n- \"توجه الى اعدادات القناة \n-> اختر اضف مشرف \n-> ضع معرف البوت : @botusername \n-> قم بترقيته مشرف مع اعطاء صلاحيات ( الدعوه بأستخدام رابط الدعوة)\" .\n\nارسل توجيه من قناتك الى هنا .*";
            EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);
        }

        if ($data === 'public') { // من يدوس تمويل قناة عامه
            $getjson[$from_id] = [$data, $pdata];
            file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => "رجوع", 'callback_data' => 'backmain']],]]); // نقوم بأنشاء الكيبورد
            $content = "*- \"اتبع ما سأقوله\" :\n\n- \"توجه الى اعدادات القناة \n-> اختر اضف مشرف \n-> ضع معرف البوت : @botusername \n-> قم بترقيته مشرف مع اعطاء صلاحيات ( الدعوه بأستخدام رابط الدعوة)\" .\n\nارسل توجيه من قناتك الى هنا .*";
            EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);
        }

        // cancel the invest
        if ($data === 'cancelinvest') {
            $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => "نعم", 'callback_data' => "ys!$pdata"]], [['text' => "لا", 'callback_data' => 'backmain']],]]); // نقوم بأنشاء الكيبورد
            $content = "• هل انت متأكد من الغاء التمويل !!!!";
            EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);
        }

        if ($data === 'ys') { // الغاء التمويل من يدوس نعم
            $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => '"رجوع."', 'callback_data' => 'backmain']],]]); // نقوم بأنشاء الكيبورد
            $PDO->query("DELETE FROM `channels` WHERE chid = '$pdata'");
            $PDO->query("SET @autoid :=0;
            UPDATE channels set id = @autoid := (@autoid+1);
            ALTER TABLE channels AUTO_INCREMENT = 1;");
            $content = "- تم توقيف التمويل ؛ 🕯.";
            EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);
        }

        if ($data === 'grouping') {
            $bot_API_markup = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => '- "الاشتراك في القنوات او المجموعات".', 'callback_data' => 'collect']
                    ],
                    [
                        ['text' => '- "اشتراك قنواَت التيربو !".', 'callback_data' => 'TurboCollect']
                    ],
                    [
                        ['text' => '- "رابط الدعوه".', 'callback_data' => 'invite']
                    ],
                    [
                        ['text' => '- "شراء نقاط".', 'url' => 'tg://user?id=' . $settings['sudo_info'][0]]
                    ],
                    [
                        ['text' => "~ رجوع ~", 'callback_data' => 'backmain']
                    ],
                ]
            ]); // نقوم بأنشاء الكيبورد
            $content = COINMSG;
            EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);
        } // END grouping


        if ($data === 'convertCoin') {
            $sql = "SELECT ids, coin FROM `users` WHERE ids = $from_id";
            $query = $PDO->query($sql)->fetchall(PDO::FETCH_ASSOC);
            $coin = isset($query[0]['coin']) ? $query[0]['coin'] : 0;
            if ($coin <= 20) {
                $bot_API_markup = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "تجميع النقاط", 'callback_data' => 'collect']
                        ],
                        [
                            ['text' => "رجوع", 'callback_data' => 'backmain']
                        ],
                    ]
                ]); // نقوم بأنشاء الكيبورد
                $content = "*• عليك تجميع نقاط اكثر من 20 نقطه !*";
                EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);
            } else {
                $getjson[$from_id] = $data;
                file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
                $bot_API_markup = json_encode(['inline_keyboard' => [[['text' => "رجوع", 'callback_data' => 'backmain']],]]); // نقوم بأنشاء الكيبورد
                $content = "*• ارسل ايدي الشخص الذي تريد تحويل النقاط اليه !*";
                EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);
            }
        } // end convertCoin


        if ($data === 'backmain') {
            if (isset($getjson[$from_id])) {
                unset($getjson[$from_id]);
                file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            }
            $bot_API_markup = STARTKEYBOARD;
            $content = START;
            EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, $bot_API_markup);
        } // end backmain

    } // end data



    function save($data = [], $path) // USED TO SAVE IN SETTINGFILE
    {
        file_put_contents($path,  json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }



    /**
     * @param admin
     */

    if (isset($from_id) && in_array($from_id, $settings['sudo_info']) && !isset($data)) {

        $getjson = json_decode(file_get_contents(SETTINGFILE), true); // نقوم بأنشاء فايل لعمل الاوامر ثم تصفيره
        if ($text === '/start' || $text === '/panel') {
            $bot_API_markup = SUDOKEYBOARD;
            SendMessage($chat_id, SUDOMSG, "MARKDOWN", TRUE, NULL, $bot_API_markup);
        } // end start

        if (isset($getjson[$from_id]) && $getjson[$from_id] === 'broadcast') {
            unset($getjson[$from_id]);
            file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            $command = "php /var/www/html/broadcast/broadcast.php '" . $from_id . "' '" . json_encode($update, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "' '" . json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "'";
            shell_exec($command);
        }

        // send-del coins
        if (preg_match('#:#', $text) && !isset($getjson[$from_id])) {

            $ex = explode(":", $text);
            $_id = isset($ex[0]) ? $ex[0] : null;
            $num = isset($ex[1]) ? $ex[1] : 0;
            if ($_id === null) {
                return SendMessage($chat_id, 'ادخال خاطىء', "MARKDOWN", TRUE, NULL, BACKSUDOKEYBOARD);
            }
            $coin = $PDO->query("SELECT coin FROM `users` WHERE ids = $_id")->fetchall(PDO::FETCH_ASSOC)[0]['coin'] ?? 0;
            if ($coin === 0) {
                return SendMessage($chat_id, 'العضو غير موجود في قواعد البيانات', "MARKDOWN", TRUE, NULL, BACKSUDOKEYBOARD);
            }
            $benefit = $num + $coin;
            $PDO->query("UPDATE `users` SET coin = $benefit WHERE ids = $_id");
            SendMessage($chat_id, 'تمت العمليه بنجاح وتم اشعار صديقك بالامر .', "MARKDOWN", TRUE, NULL, BACKSUDOKEYBOARD);
            if (preg_match('#-#', $text)) {
                SendMessage($_id, "*📛 | تم خصَم $num نقاط منك ، بواسطه المطور ؛ *$name_tag* .\n🕯| عدد نقاطك الحالية ؛ $benefit .*", "MARKDOWN", TRUE, NULL, MAINBACK);
            } else {
                SendMessage($_id, "*- \"تم استلام المبلغ التالي $num\".\n- المرسل : *$name_tag* . \n- المستفيد : $_id . \n- عدد عملاتك : \"$benefit\".\n- /start\n- شكراً لأستعمال خدماتنا.🖤*", "MARKDOWN", TRUE, NULL, MAINBACK);
            }
        }
        // setrobot check 
        if (isset($getjson[$from_id]) && $getjson[$from_id] === 'setrobot') {
            unset($getjson[$from_id]);
            if (isset($chat_forward)) {
                $Bot_Admin = json_decode(json_encode(GetChatMember($chat_forward_id, $botid)), True)['result']['status'];
                if ($Bot_Admin != "administrator") { // bot is not admin
                    $content = "*✖️| البوت هذا ليس ادمن في هذه القناة، تم الغاء العملية ، ولم تتم اضافة اي قناة ، ڪَ  قناة الروبوت ،🕯'*";
                    return SendMessage($chat_id, $content, "MARKDOWN", true, null, BACKSUDOKEYBOARD);
                } else {
                    $getjson['robotchannel'] = [$chat_forward_id, ExportChatInviteLink($chat_forward_id)->result];
                    SendMessage($chat_id, SUDOMSG4, "MARKDOWN", true, null, BACKSUDOKEYBOARD);
                }
            }
            file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
        }

        // edit txt file
        if (isset($getjson[$from_id]) && $getjson[$from_id] === 'changetxt') {
            unset($getjson[$from_id]);
            file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            if (isset($document)) {
                $GetFile = GetFile($document_file_id)->result;
                if (preg_match('#.txt#', $document_name)) {
                    $File_path = File_path($GetFile->file_path);
                    if (file_exists(TXTFILE)) {
                        unlink(TXTFILE);
                    }
                    file_put_contents(TXTFILE, $File_path);
                    $BACKSUDOKEYBOARD = BACKSUDOKEYBOARD;
                    $BACKSUDOKEYBOARD['inline_keyboard'][] = [['text' => "✖️| لقَد قمت بخطأ ، اعادة التعين ؛ ⚠️", 'callback_data' => 'resetTxtFile']];
                    SendMessage($chat_id, "• تم تغير ملف الرسائل والنقاط بنجاح ، 🔱'", "MARKDOWN", true, null, $BACKSUDOKEYBOARD);
                }
            }
        }

        switch ($getjson[$from_id]) {
            case "addmych";
                if ($chat_forward) {
                    $text = $chat_forward_id;
                    $chat = GetChat($text)->result->id;
                    $id = $chat;
                } elseif (preg_match('/^(@)(.*)/s', $text)) {
                    $chat = GetChat($text)->result->id;
                    $id = $chat;
                } elseif (strlen($text) == 14) {
                    $chat = GetChat($text)->result->id;
                    $id = $chat;
                }

                if ($id) {
                    $getjson['chid'][0] = $id;
                    $getjson[$from_id] = "setlink";
                    save($getjson, SETTINGFILE);
                    SendMessage($chat_id, "• ارسل الرابط ، الخاص او العام.", null, true);
                } else {
                    SendMessage($chat_id, 'error', null, true);
                }
                return false;
                break;
            case "setlink";
                if (preg_match('/^(.*)([Hh]ttp|[Hh]ttps|t.me)(.*)|([Hh]ttp|[Hh]ttps|t.me)(.*)|(.*)([Hh]ttp|[Hh]ttps|t.me)|(.*)[Tt]elegram.me(.*)|[Tt]elegram.me(.*)|(.*)[Tt]elegram.me|(.*)[Tt].me(.*)|[Tt].me(.*)|(.*)[Tt].me/', $text)) {
                    $getjson['channel'][$getjson['chid'][0]] = $getjson['chid'][0] . ":@" . $text;
                    unset($getjson[$from_id]);
                    unset($getjson['chid']);
                    save($getjson, SETTINGFILE);
                    SendMessage($chat_id, 'okay', null, true, null, SUDOKEYBOARD);
                } else {
                    SendMessage($chat_id, 'error link', null, true);
                }
                return false;
                break;

            case "delmych";
                if ($chat_forward) {
                    $text = $chat_forward_id;
                    $chat = GetChat($text)->result->id;
                    $id = $chat;
                } elseif (preg_match('/^(@)(.*)/s', $text)) {
                    $chat = GetChat($text)->result->id;
                    $id = $chat;
                } elseif (strlen($text) == 14) {
                    $chat = GetChat($text)->result->id;
                    $id = $chat;
                }
                if ($id && $getjson['channel'][$id]) {
                    unset($getjson[$from_id]);
                    unset($getjson['channel'][$id]);
                    save($getjson, SETTINGFILE);
                    SendMessage($chat_id, "• تم حذف قناتك .", null, true, null, SUDOKEYBOARD);
                } else {
                    unset($getjson[$from_id]);
                    save($getjson, SETTINGFILE);
                    SendMessage($chat_id, '• القناة غير موجوده ولكن حسناً سأقوم بحذف التخزين كله ! هاها امزح القناة غير موجوده.', null, true, null, SUDOKEYBOARD);
                }
                return false;
                break;
        }
    } // end admin MESSAGE


    if (isset($data) && in_array($from_id, $settings['sudo_info'])) { // admin commands 



        $getjson = json_decode(file_get_contents(SETTINGFILE), true); // نقوم بأنشاء فايل لعمل الاوامر ثم تصفيره

        if ($data === 're-arrage') { // re arrage tables
            $PDO->query("SET @autoid :=0;
            UPDATE channels set id = @autoid := (@autoid+1);
            ALTER TABLE channels AUTO_INCREMENT = 1;");
            $bot_API_markup = BACKSUDOKEYBOARD;
            $content = "- تم ☑️ تنظيم جَميع التيبلات ؛ 🕯";
            EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, BACKSUDOKEYBOARD);
        }

        if ($data === 'backsudo') { // back
            if (isset($getjson[$from_id])) {
                unset($getjson[$from_id]);
                file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            }
            if (isset($getjson['chid'])) {
                unset($getjson['chid']);
                file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            }
            $bot_API_markup = SUDOKEYBOARD;
            EditMessageText($chat_id, $message_id, SUDOMSG, null, "MARKDOWN", TRUE, $bot_API_markup);
        }

        if ($data === 'send-del-coin') { // send/delete coins
            EditMessageText($chat_id, $message_id, SUDOMSG2, null, "MARKDOWN", TRUE, BACKSUDOKEYBOARD);
        }

        if ($data === 'setrobot') { // setrobot
            $getjson[$from_id] = $data;
            file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            EditMessageText($chat_id, $message_id, SUDOMSG3, null, "MARKDOWN", TRUE, BACKSUDOKEYBOARD);
        }

        if ($data === 'changetxt') { // changetxt
            $getjson[$from_id] = $data;
            file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            EditMessageText($chat_id, $message_id, SUDOMSG5, null, "MARKDOWN", TRUE, BACKSUDOKEYBOARD);
            SendDocument($chat_id, new CURLFile(TXTFILE));
        }

        if ($data === 'resetTxtFile') { // resetTxtFile
            if (file_exists(TXTFILE)) {
                unlink(TXTFILE);
            }
            EditMessageText($chat_id, $message_id, "- حسناً 🤖.", null, "MARKDOWN", TRUE, BACKSUDOKEYBOARD);
        }

        if ($data === 'channels_') { // setrobot
            EditMessageText($chat_id, $message_id, "اهلاً بك في قسم الاشتراك الاجباري ؛ ✅", null, "MARKDOWN", TRUE, DASHBORD);
        }

        if ($data === 'broadcast') { // broadcast
            $getjson[$from_id] = $data;
            file_put_contents(SETTINGFILE, json_encode($getjson, JSON_PRETTY_PRINT));
            $content = "*🏷 | ارسل الاذاعه الخاصه بك. \nيمكنك ارسال توجيه لعمل توجيه للرساله. \nيمكنك ارسال رساله عاديه للأذاعه. \n\n📤 | الاذاعه تدعم جميع انواع الميديا.*";
            EditMessageText($chat_id, $message_id, $content, null, "MARKDOWN", TRUE, BACKSUDOKEYBOARD);
        }

        if ($data === 'cancelBroadcast') { // cancelBroadcast
            file_put_contents(BROADCASTPATH . '/stop' . $TOKEN, null);
            EditMessageText($chat_id, $message_id, "ok ;)", null, "MARKDOWN", TRUE, BACKSUDOKEYBOARD);
        }

        switch ($data) {
            case "addch1";
                $getjson[$from_id] = "addmych";
                save($getjson, SETTINGFILE);
                $msg = "• ارسل معرف ، توجيه ، ايدي ، قناتك.";
                EditMessageText($chat_id, $message_id, $msg, null, null, true, BACKSUDOKEYBOARD);
                break;
            case "delch1";
                $getjson[$from_id] = "delmych";
                save($getjson, SETTINGFILE);
                $msg = "• ارسل معرف ، توجيه ، ايدي ، قناتك.";
                EditMessageText($chat_id, $message_id, $msg, null, null, true, BACKSUDOKEYBOARD);
                break;
            case "delchss";
                $msg = "- jsget['channel']\n- تم حذفه.\n- تم تصفير القنوات.";
                unset($getjson['channel']);
                save($getjson, SETTINGFILE);
                EditMessageText($chat_id, $message_id, $msg, null, null, true, BACKSUDOKEYBOARD);
                break;
            case "seechs";
                if (!isset($getjson['channel'])) {
                    EditMessageText($chat_id, $message_id, "لايوجد !", null, null, true, BACKSUDOKEYBOARD);
                    break;
                }
                $msg = "";
                $i = 0;
                foreach ($getjson['channel'] as $channel) {
                    $ex      = explode(":@", $channel);
                    $id      = $ex[0];
                    $link    = $ex[1];
                    $getchat = GetChat($id);
                    $title   = json_decode(json_encode($getchat), True)["result"]["title"];
                    $msg     = $msg . "*- 𖡹 : * [$title]($link)\n\n";
                    $i++;
                }
                EditMessageText($chat_id, $message_id, "*see you : *" . count($getjson['channel']) . "\n\n" . $msg, null, "MARKDOWN", true, BACKSUDOKEYBOARD);
                break;
            case "back";
                $msg = "welcome Again";
                EditMessageText($chat_id, $message_id, $msg, null, null, true, SUDOKEYBOARD);
                break;
        }
    } // end admin DATA



    $PDO = NULL; // close connection.

} catch (Exception $e) {
    echo 'Exception : ' . $e->getMessage() . ' On line : ' . $e->getLine();
}
