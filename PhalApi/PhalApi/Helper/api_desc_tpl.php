<?php
echo <<<EOT
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{$service} - 在线接口文档</title>

    <link rel="stylesheet" href="https://staticfile.qnssl.com/semantic-ui/2.1.6/semantic.min.css">
    <link rel="stylesheet" href="https://staticfile.qnssl.com/semantic-ui/2.1.6/components/table.min.css">
    <link rel="stylesheet" href="https://staticfile.qnssl.com/semantic-ui/2.1.6/components/container.min.css">
    <link rel="stylesheet" href="https://staticfile.qnssl.com/semantic-ui/2.1.6/components/message.min.css">
    <link rel="stylesheet" href="https://staticfile.qnssl.com/semantic-ui/2.1.6/components/label.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="  https://code.it919.cn/favicon.ico" />
    <style>
    html,body{font-size:13px}
    a{display:block;}
    #tab{width:100%;border-collapse: collapse;border:1px solid #999;padding:5px;box-shadow: 0 0 0 1px rgba(34,36,38,.22) inset, 0 0 0 0 transparent;}
     #tab td{padding:5px 0;padding-left:30px;font-size:14px;}
     #tab tr{background: #F8F8F9;}
    </style>

</head>

<body>

<br /> 

    <div class="ui text container" style="max-width: none !important;">
        <div class="ui floating message">

EOT;

echo "<h2 class='ui header'>$description ：$service</h2><br/> <span class='ui teal tag label'>$service</span>";

/**
 * 接口说明 & 接口参数
 */
echo <<<EOT
 <div class="ui raised segment">
        <table id='tab'  border='1'>
              
                <tbody>
                    <tr>
                        <td width='150'>URL地址</td>
                         <td width=''><a href="$urlAddress" target="_blank">$urlAddress</a></td>
                    </tr>
                    <tr>
                        <td>请求方式</td>
                         <td>$methods</td>
                    </tr>
                </tbody>
            </table>
    </div>
          
            <div class="ui raised segment">
                <span class="ui red ribbon label">接口说明</span>
                <div class="ui message">
                    <p>{$descComment}</p>
                </div>
            </div>
            <h3>接口参数</h3>
            <table class="ui red celled striped table" >
                <thead>
                    <tr><th>参数名字</th><th>类型</th><th>是否必须</th><th>默认值</th><th>其他</th><th>说明</th></tr>
                </thead>
                <tbody>
EOT;

foreach ($rules as $key => $rule) {
    $name = $rule['name'];
    if (!isset($rule['type'])) {
        $rule['type'] = 'string';
    }
    $type = isset($typeMaps[$rule['type']]) ? $typeMaps[$rule['type']] : $rule['type'];
    $require = isset($rule['require']) && $rule['require'] ? '<font color="red">必须</font>' : '可选';
    $default = isset($rule['default']) ? $rule['default'] : '';
    if ($default === NULL) {
        $default = 'NULL';
    } else if (is_array($default)) {
        $default = json_encode($default);
    } else if (!is_string($default)) {
        $default = var_export($default, true);
    }

    $other = '';
    if (isset($rule['min'])) {
        $other .= ' 最小：' . $rule['min'];
    }
    if (isset($rule['max'])) {
        $other .= ' 最大：' . $rule['max'];
    }
    if (isset($rule['range'])) {
        $other .= ' 范围：' . implode('/', $rule['range']);
    }
    $desc = isset($rule['desc']) ? trim($rule['desc']) : '';

    echo "<tr><td>$name</td><td>$type</td><td>$require</td><td>$default</td><td>$other</td><td>$desc</td></tr>\n";
}

/**
 * 返回结果
 */
echo <<<EOT
                </tbody>
            </table>
            <h3>返回结果</h3>
            <table class="ui green celled striped table" >
                <thead>
                    <tr><th>返回字段</th><th>类型</th><th>说明</th></tr>
                </thead>
                <tbody>
EOT;

foreach ($returns as $item) {
    $name = $item[1];
    $type = isset($typeMaps[$item[0]]) ? $typeMaps[$item[0]] : $item[0];
    $detail = $item[2];

    echo "<tr><td>$name</td><td>$type</td><td>$detail</td></tr>";
}

echo <<<EOT
            </tbody>
        </table>
EOT;

/**
 * 异常情况
 */
if (!empty($exceptions)) {
    echo <<<EOT
            <h3>异常情况</h3>
            <table class="ui red celled striped table" >
                <thead>
                    <tr><th>错误码</th><th>错误描述信息</th>
                </thead>
                <tbody>
EOT;

    foreach ($exceptions as $exItem) {
        $exCode = $exItem[0];
        $exMsg = isset($exItem[1]) ? $exItem[1] : '';
        echo "<tr><td>$exCode</td><td>$exMsg</td></tr>";
    }

    echo <<<EOT
            </tbody>
        </table>
EOT;
}

/**
 * 底部
 */
$version = PHALAPI_VERSION;
echo <<<EOT
        <div class="ui  ">
        </div>
       <p>&copy; Powered  By <a href="http://code.it919.cn/" target="_blank">IT开发者 {$version}</a> <p>
        </div>
    </div>
</body>
</html>
EOT;




