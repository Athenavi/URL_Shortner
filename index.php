<?php

session_start();
date_default_timezone_set('Asia/Shanghai');

// 导入工具函数文件
require_once 'functions.php';

// 获取配置文件中的domain值
$config = parse_ini_file('config.ini');
$domain = $config['domain'];

$short_url = '';

// 根据路由请求转换为短链请求

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['shorturl']))
{
    $short_url = $_GET['shorturl'];

    // 确保短网址为6个字符长度
    if (strlen($short_url) === 6) {
        $long_url = redirect_to_long_url($short_url);

        if ($long_url) {
            // 确保在调用header()函数前没有输出
            ob_clean(); // 清除缓冲区
            header("Location: $long_url");
            exit();
        } else {
            http_response_code(404);
            echo "该短网址无效";
            exit();
        }
    } else {
        http_response_code(404);
        echo "该短网址无效";
        exit();
    }
}



// 处理生成专属短网址的逻辑
if (isset($_POST['generate-special-url'])) {
    // 获取长网址
    $long_url = $_POST['url'];

    // 生成专属短网址的逻辑
    $short_url = create_special_url($long_url, $domain);
} else {
    $long_url = '';
    $short_url = '';
}

// 处理API请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取请求内容类型
    $content_type = $_SERVER["CONTENT_TYPE"];
    
    // 获取请求参数
    $request_data = array();

    if (strpos($content_type, "application/x-www-form-urlencoded") !== false) {
        // 处理 x-www-form-urlencoded 格式
        $request_data = $_POST;
    } elseif (strpos($content_type, "application/json") !== false) {
        // 处理 raw 格式
        $request_body = file_get_contents('php://input');
        $request_data = json_decode($request_body, true);
    } elseif (strpos($content_type, "multipart/form-data") !== false) {
        // 处理 form-data 格式
        $request_data = $_POST;
    }

    // 检查是否存在 l 参数
    if (isset($request_data['l'])) {
        // 获取长网址
        $long_url = $request_data['l'];

        // 处理 URL 参数编码问题
        if (strpos($long_url, '%') !== false) {
            $long_url = urldecode($long_url);
        }

        // 生成专属短网址的逻辑
        $short_url = create_special_url($long_url, $domain);

        // 返回短网址作为API响应
        echo $domain . '/' . $short_url;
        exit();
    } else {
        // 抛出警告
        echo("当前页面版本过低，建议使用API，使用方法见新站<a href='https://7trees.cn'>七棵树</a>菜单栏，点击<a href='mailto:support@7trees.cn'>此处</a>联系我们的支持团队。或者举报违法链接");
    }
}


?>

<html>
<head>
    <title>URL Shortener</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    var domain = '<?php echo $domain; ?>';

    function copyToClipboard(shortUrl) {
        var fullUrl = domain + "/" + shortUrl;
        const textarea = document.createElement('textarea');
        textarea.value = fullUrl;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        alert('已复制到剪切板');
    }

    function shareLink() {
        // TODO: 实现分享功能
        alert('该程序仅用于交流学习，请不要用于违法用途！！！');
    }

</script>
</head>
<body>
    <div class="container">
        <div class="content">
            <h1>URL Shortener</h1>
            <form method="POST" action="/" enctype="multipart/form-data">
                <p>本站为旧站，建议访问新站: <a href="http://237127.xyz">七棵树短链</a></p>
                <input type="text" name="url" placeholder="输入长网址（须包含https/http）" required>
                <div id="imageModal">
                <div id="imageContent">
                    <button id="shareBtn" onclick="shareLink()">说明·须知</button>
                </div>
            </div>
                <br>
                <button type="submit" name="generate-special-url">生成专属短网址</button>
            </form>

            <?php if ($long_url && $short_url): ?>
            <div class="url-info">
                <p>原始网址: <a href="<?php echo $long_url; ?>"><?php echo $long_url; ?></a></p>
                <p>短网址: <a href="<?php echo $short_url; ?>"><?php echo $short_url; ?></a><button onclick="copyToClipboard('<?php echo $short_url; ?>')">复制</button></p>
                <br><p>当前二维码<br><img src="http://api.7trees.cn/qrcode?data=<?php echo "http://" . $domain ."/". $short_url; ?>" width="175" height="175"/></p>
            </div>
            </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    </div>
</div>
</div>
</body>
</html>
