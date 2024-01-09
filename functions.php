<?php

function generate_short_url() {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $short_url = '';
    $length = 6; // 设置短网址长度

    for ($i = 0; $i < $length; $i++) {
        $random_index = rand(0, strlen($characters) - 1);
        $short_url .= $characters[$random_index];
    }

    return $short_url;
}

function get_database_connection() {
    $config = parse_ini_file('config.ini');

    $servername = $config['servername'];
    $username = $config['username'];
    $password = $config['password'];
    $dbname = $config['dbname'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("数据库连接失败: " . $conn->connect_error);
    }

    return $conn;
}


function create_special_url($long_url, $username) {
    $db = get_database_connection();
    $stmt = $db->prepare("SELECT short_url FROM urls WHERE long_url = ? AND username = ?");
    $stmt->bind_param("ss", $long_url, $username);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        $short_url = $result['short_url'];
    } else {
        $short_url = generate_short_url();

        $stmt = $db->prepare("INSERT INTO urls (long_url, short_url, username) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $long_url, $short_url, $username);
        $stmt->execute();
    }

    $stmt->close();
    $db->close();

    return $short_url;
}

function create_short_url($long_url) {
    $db = get_database_connection();
    $stmt = $db->prepare("SELECT short_url FROM urls WHERE long_url = ? ORDER BY id ASC LIMIT 1");
    $stmt->bind_param("s", $long_url);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        $short_url = $result['short_url'];
    } else {
        $short_url = generate_short_url();

        $stmt = $db->prepare("INSERT INTO urls (long_url, short_url) VALUES (?, ?)");
        $stmt->bind_param("ss", $long_url, $short_url);
        $stmt->execute();
    }

    $stmt->close();
    $db->close();

    return $short_url;
}

function redirect_to_long_url($short_url) {
    $db = get_database_connection();
    $stmt = $db->prepare("SELECT long_url FROM urls WHERE short_url = ?");
    $stmt->bind_param("s", $short_url);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        $long_url = $result['long_url'];

        $current_time = date('Y-m-d H:i:s');
        $stmt = $db->prepare("INSERT INTO opentimes (short_url, response_count, first_response_time) VALUES (?, 1, ?) ON DUPLICATE KEY UPDATE response_count = response_count + 1");
        $stmt->bind_param("ss", $short_url, $current_time);
        $stmt->execute();

        $stmt->close();
        $db->close();

        return $long_url;
    } else {
        error_log("Invalid short URL: $short_url");
        return null;
    }
}

function delete_link($short_url) {
    $db = get_database_connection();
    $stmt = $db->prepare("DELETE FROM urls WHERE short_url = ?");
    $stmt->bind_param("s", $short_url);
    $stmt->execute();

    $stmt->close();
    $db->close();

    return true;
}

function get_link_info() {
    $db = get_database_connection();
    $stmt = $db->prepare("SELECT created_at, short_url, long_url FROM urls");
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $db->close();

    return $result;
}