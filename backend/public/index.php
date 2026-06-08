<?php
$config = require __DIR__ . '/../config/config.php';

header('Content-Type: application/json');
$origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
$allowed = array_map('trim', explode(',', $config['cors_origins']));
header('Access-Control-Allow-Origin: ' . ($config['cors_origins'] === '*' || in_array($origin, $allowed) ? $origin : $allowed[0]));
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

function db() {
    static $pdo;
    global $config;
    if (!$pdo) {
        if (($config['db_driver'] ?? 'mysql') === 'sqlite') {
            $dsn = "sqlite:" . $config['sqlite_path'];
            $pdo = new PDO($dsn, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            init_sqlite($pdo);
            return $pdo;
        }
        $dsn = "mysql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']};charset=utf8mb4";
        $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}
function init_sqlite($pdo) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (id TEXT PRIMARY KEY,email TEXT NOT NULL UNIQUE,password TEXT NOT NULL,name TEXT NOT NULL,is_admin INTEGER NOT NULL DEFAULT 0,created_at TEXT NOT NULL)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (id TEXT PRIMARY KEY,name TEXT NOT NULL,description TEXT NOT NULL,price REAL NOT NULL,image_url TEXT NOT NULL,category TEXT NOT NULL DEFAULT 'wig-tape',stock INTEGER NOT NULL DEFAULT 100,features TEXT NULL,created_at TEXT NOT NULL)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS carts (id TEXT PRIMARY KEY,user_id TEXT NOT NULL UNIQUE,items TEXT NOT NULL,updated_at TEXT NOT NULL)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (id TEXT PRIMARY KEY,user_id TEXT NOT NULL,items TEXT NOT NULL,subtotal REAL NULL,discount REAL NOT NULL DEFAULT 0,coupon_code TEXT NULL,total_amount REAL NOT NULL,status TEXT NOT NULL DEFAULT 'pending',payment_id TEXT NULL,shipping_address TEXT NOT NULL,created_at TEXT NOT NULL)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS coupons (id TEXT PRIMARY KEY,code TEXT NOT NULL UNIQUE,discount_type TEXT NOT NULL,discount_value REAL NOT NULL,min_purchase REAL NOT NULL DEFAULT 0,max_discount REAL NULL,usage_limit INTEGER NULL,used_count INTEGER NOT NULL DEFAULT 0,valid_from TEXT NOT NULL,valid_until TEXT NOT NULL,is_active INTEGER NOT NULL DEFAULT 1,created_at TEXT NOT NULL)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS blogs (id TEXT PRIMARY KEY,title TEXT NOT NULL,content TEXT NOT NULL,author_id TEXT NOT NULL,author_name TEXT NOT NULL,created_at TEXT NOT NULL)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS contact_forms (id TEXT PRIMARY KEY,name TEXT NOT NULL,email TEXT NOT NULL,phone TEXT NOT NULL,address TEXT NOT NULL,feedback TEXT NOT NULL,status TEXT NOT NULL DEFAULT 'pending',admin_reply TEXT NULL,created_at TEXT NOT NULL,replied_at TEXT NULL)");
    $count = (int)$pdo->query("SELECT COUNT(*) AS c FROM users")->fetch()['c'];
    if ($count > 0) return;
    $adminPass = '$2y$10$I8T.Z.QMie3xfB6Xg2GD2eAiWWJDnmee9zaoRJcBVqLdH.zJhm0Dm';
    $pdo->prepare("INSERT INTO users (id,email,password,name,is_admin,created_at) VALUES (?,?,?,?,1,?)")->execute(['11111111-1111-4111-8111-111111111111','admin@wigzotape.com',$adminPass,'Admin','2026-01-01T00:00:00+00:00']);
    $products = [
        ['22222222-2222-4222-8222-222222222201','Ultra Hold Wig Tape - 36 Pieces','Professional-grade double-sided tape for secure wig application. Waterproof and sweat-resistant formula provides up to 4-6 weeks of hold. Perfect for daily wear and active lifestyles.',599.00,'https://images.unsplash.com/photo-1522839206838-1cfecf32cc72?crop=entropy&cs=srgb&fm=jpg&ixlib=rb-4.1.0&q=85',100,["Medical-grade adhesive","4-6 weeks hold","Waterproof & sweat-resistant","36 pre-cut pieces"]],
        ['22222222-2222-4222-8222-222222222202','Sensitive Skin Wig Tape - 24 Pieces','Hypoallergenic tape specially formulated for sensitive skin. Gentle yet secure hold for 2-3 weeks. Dermatologist tested and recommended for first-time wig users.',499.00,'https://images.unsplash.com/photo-1612538498488-226257115cc4?crop=entropy&cs=srgb&fm=jpg&ixlib=rb-4.1.0&q=85',100,["Hypoallergenic formula","2-3 weeks hold","Dermatologist tested","24 pre-cut pieces"]],
        ['22222222-2222-4222-8222-222222222203','Professional Tape Roll - 3 Yards','Continuous roll of professional-grade wig tape for custom cutting. Ideal for salons and professional stylists. Premium adhesive with maximum flexibility.',899.00,'https://images.unsplash.com/photo-1522839206838-1cfecf32cc72?crop=entropy&cs=srgb&fm=jpg&ixlib=rb-4.1.0&q=85',50,["3 yards continuous roll","Professional grade","Custom cutting","Salon quality"]],
        ['22222222-2222-4222-8222-222222222204','Extra Strong Hold Tape - 48 Pieces','Maximum strength adhesive for extended wear. Perfect for athletes and high-activity individuals. Provides secure hold for up to 6-8 weeks in all conditions.',799.00,'https://images.unsplash.com/photo-1612538498488-226257115cc4?crop=entropy&cs=srgb&fm=jpg&ixlib=rb-4.1.0&q=85',80,["Maximum strength","6-8 weeks hold","Ideal for sports","48 pre-cut pieces"]],
        ['22222222-2222-4222-8222-222222222205','Mini Tape Strips - 72 Pieces','Small precision strips for frontal lace and detailed work. Perfect for natural hairline application. Easy to apply and remove.',449.00,'https://images.unsplash.com/photo-1522839206838-1cfecf32cc72?crop=entropy&cs=srgb&fm=jpg&ixlib=rb-4.1.0&q=85',120,["Mini precision strips","Perfect for lace frontals","Natural hairline","72 pieces"]],
        ['22222222-2222-4222-8222-222222222206','Starter Kit - 12 Pieces + Remover','Perfect starter kit for beginners. Includes 12 tape pieces and gentle adhesive remover. Complete instructions included for easy application.',399.00,'https://images.unsplash.com/photo-1612538498488-226257115cc4?crop=entropy&cs=srgb&fm=jpg&ixlib=rb-4.1.0&q=85',150,["Beginner friendly","Includes remover","Instructions included","12 tape pieces"]],
    ];
    $stmt = $pdo->prepare("INSERT INTO products (id,name,description,price,image_url,category,stock,features,created_at) VALUES (?,?,?,?,?,'wig-tape',?,?,?)");
    foreach ($products as $p) $stmt->execute([$p[0],$p[1],$p[2],$p[3],$p[4],$p[5],json_encode($p[6]),'2026-01-01T00:00:00+00:00']);
    $pdo->prepare("INSERT INTO coupons (id,code,discount_type,discount_value,min_purchase,max_discount,usage_limit,used_count,valid_from,valid_until,is_active,created_at) VALUES (?,?,?,?,?,NULL,NULL,0,?,?,1,?)")->execute(['33333333-3333-4333-8333-333333333333','FIRSTTIME','percentage',20.00,0.00,'2026-01-01T00:00:00+00:00','2030-01-01T00:00:00+00:00','2026-01-01T00:00:00+00:00']);
}
function json_body() { return json_decode(file_get_contents('php://input'), true) ?: []; }
function out($data, $code = 200) { http_response_code($code); echo json_encode($data, JSON_UNESCAPED_SLASHES); exit; }
function fail($message, $code = 400) { out(['detail' => $message], $code); }
function uuidv4() { return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0,65535), mt_rand(0,65535), mt_rand(0,65535), mt_rand(0,4095)|0x4000, mt_rand(0,16383)|0x8000, mt_rand(0,65535), mt_rand(0,65535), mt_rand(0,65535)); }
function now_iso() { return gmdate('c'); }
function b64url($s) { return rtrim(strtr(base64_encode($s), '+/', '-_'), '='); }
function b64url_decode_str($s) { return base64_decode(strtr($s . str_repeat('=', (4 - strlen($s) % 4) % 4), '-_', '+/')); }
function make_token($user_id, $is_admin) {
    global $config;
    $payload = ['user_id'=>$user_id, 'is_admin'=>(bool)$is_admin, 'exp'=>time() + 86400];
    $body = b64url(json_encode($payload));
    return $body . '.' . b64url(hash_hmac('sha256', $body, $config['jwt_secret'], true));
}
function read_token() {
    global $config;
    $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (!preg_match('/Bearer\s+(.*)$/i', $auth, $m)) fail('Not authenticated', 401);
    $parts = explode('.', $m[1]);
    if (count($parts) !== 2) fail('Invalid token', 401);
    $expected = b64url(hash_hmac('sha256', $parts[0], $config['jwt_secret'], true));
    if (!hash_equals($expected, $parts[1])) fail('Invalid token', 401);
    $payload = json_decode(b64url_decode_str($parts[0]), true);
    if (!$payload || ($payload['exp'] ?? 0) < time()) fail('Token expired', 401);
    return $payload;
}
function current_user() {
    $payload = read_token();
    $stmt = db()->prepare('SELECT id,email,name,is_admin,created_at FROM users WHERE id=?');
    $stmt->execute([$payload['user_id']]);
    $user = $stmt->fetch();
    if (!$user) fail('User not found', 404);
    $user['is_admin'] = (bool)$user['is_admin'];
    return $user;
}
function admin_user() { $u = current_user(); if (!$u['is_admin']) fail('Admin access required', 403); return $u; }
function decode_row($row) {
    if (!$row) return $row;
    foreach (['features','items','shipping_address'] as $k) if (isset($row[$k]) && is_string($row[$k])) $row[$k] = json_decode($row[$k], true) ?: [];
    foreach (['price','subtotal','discount','total_amount','discount_value','min_purchase','max_discount'] as $k) if (array_key_exists($k, $row) && $row[$k] !== null) $row[$k] = (float)$row[$k];
    foreach (['stock','used_count','usage_limit'] as $k) if (array_key_exists($k, $row) && $row[$k] !== null) $row[$k] = (int)$row[$k];
    if (array_key_exists('is_active', $row)) $row['is_active'] = (bool)$row['is_active'];
    if (array_key_exists('is_admin', $row)) $row['is_admin'] = (bool)$row['is_admin'];
    return $row;
}
function all($sql, $params = []) { $s = db()->prepare($sql); $s->execute($params); return array_map('decode_row', $s->fetchAll()); }
function one($sql, $params = []) { $s = db()->prepare($sql); $s->execute($params); return decode_row($s->fetch()); }
function send_email_log($to, $subject, $body) { error_log("EMAIL TO: $to | $subject | $body"); }

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = preg_replace('#^.*?/api#', '/api', $path);
$path = rtrim($path, '/') ?: '/api';

try {
    if ($method === 'POST' && $path === '/api/auth/register') {
        $d = json_body();
        if (empty($d['email']) || empty($d['password']) || empty($d['name'])) fail('Name, email and password are required');
        if (one('SELECT id FROM users WHERE email=?', [$d['email']])) fail('Email already registered');
        $id = uuidv4();
        db()->prepare('INSERT INTO users (id,email,password,name,is_admin,created_at) VALUES (?,?,?,?,0,?)')->execute([$id, $d['email'], password_hash($d['password'], PASSWORD_BCRYPT), $d['name'], now_iso()]);
        out(['token'=>make_token($id, false), 'user'=>['id'=>$id,'email'=>$d['email'],'name'=>$d['name'],'is_admin'=>false]]);
    }
    if ($method === 'POST' && $path === '/api/auth/login') {
        $d = json_body();
        $u = one('SELECT * FROM users WHERE email=?', [$d['email'] ?? '']);
        if (!$u || !password_verify($d['password'] ?? '', $u['password'])) fail('Invalid credentials', 401);
        send_email_log($u['email'], 'Welcome Back to Wigzo Tape!', 'Welcome back ' . $u['name']);
        out(['token'=>make_token($u['id'], $u['is_admin']), 'user'=>['id'=>$u['id'],'email'=>$u['email'],'name'=>$u['name'],'is_admin'=>(bool)$u['is_admin']]]);
    }
    if ($method === 'GET' && $path === '/api/auth/me') out(current_user());

    if ($method === 'GET' && $path === '/api/products') out(all('SELECT * FROM products ORDER BY created_at DESC'));
    if ($method === 'GET' && preg_match('#^/api/products/([^/]+)$#', $path, $m)) { $p = one('SELECT * FROM products WHERE id=?', [$m[1]]); if (!$p) fail('Product not found', 404); out($p); }
    if ($method === 'POST' && $path === '/api/products/upload-image') { admin_user(); if (empty($_FILES['file'])) fail('No file uploaded'); $f=$_FILES['file']; out(['image_url'=>'data:' . mime_content_type($f['tmp_name']) . ';base64,' . base64_encode(file_get_contents($f['tmp_name']))]); }
    if ($method === 'POST' && $path === '/api/products') { admin_user(); $d=json_body(); $id=uuidv4(); db()->prepare('INSERT INTO products (id,name,description,price,image_url,category,stock,features,created_at) VALUES (?,?,?,?,?,?,?,?,?)')->execute([$id,$d['name'],$d['description'],(float)$d['price'],$d['image_url'],$d['category'] ?? 'wig-tape',(int)($d['stock'] ?? 100),json_encode($d['features'] ?? []),now_iso()]); out(one('SELECT * FROM products WHERE id=?',[$id])); }
    if ($method === 'PUT' && preg_match('#^/api/products/([^/]+)$#', $path, $m)) { admin_user(); $d=json_body(); $fields=[];$params=[]; foreach(['name','description','price','image_url','category','stock','features'] as $k){ if(array_key_exists($k,$d)){ $fields[]="$k=?"; $params[]=$k==='features'?json_encode($d[$k]):$d[$k]; }} if($fields){$params[]=$m[1]; db()->prepare('UPDATE products SET '.implode(',',$fields).' WHERE id=?')->execute($params);} $p=one('SELECT * FROM products WHERE id=?',[$m[1]]); if(!$p) fail('Product not found',404); out($p); }
    if ($method === 'DELETE' && preg_match('#^/api/products/([^/]+)$#', $path, $m)) { admin_user(); db()->prepare('DELETE FROM products WHERE id=?')->execute([$m[1]]); out(['message'=>'Product deleted']); }

    if ($method === 'GET' && $path === '/api/cart') { $u=current_user(); $c=one('SELECT * FROM carts WHERE user_id=?',[$u['id']]); if(!$c){$id=uuidv4(); db()->prepare('INSERT INTO carts (id,user_id,items,updated_at) VALUES (?,?,?,?)')->execute([$id,$u['id'],'[]',now_iso()]); $c=one('SELECT * FROM carts WHERE id=?',[$id]);} out($c); }
    if ($method === 'POST' && $path === '/api/cart/add') { $u=current_user(); $d=json_body(); if(!one('SELECT id FROM products WHERE id=?',[$d['product_id']])) fail('Product not found',404); $c=one('SELECT * FROM carts WHERE user_id=?',[$u['id']]); $items=$c?$c['items']:[]; $found=false; foreach($items as &$it){ if($it['product_id']===$d['product_id']){$it['quantity']+=(int)$d['quantity'];$found=true;}} if(!$found)$items[]=['product_id'=>$d['product_id'],'quantity'=>(int)$d['quantity']]; if($c) db()->prepare('UPDATE carts SET items=?,updated_at=? WHERE user_id=?')->execute([json_encode($items),now_iso(),$u['id']]); else db()->prepare('INSERT INTO carts (id,user_id,items,updated_at) VALUES (?,?,?,?)')->execute([uuidv4(),$u['id'],json_encode($items),now_iso()]); out(['message'=>'Item added to cart']); }
    if ($method === 'PUT' && $path === '/api/cart/update') { $u=current_user(); $d=json_body(); $c=one('SELECT * FROM carts WHERE user_id=?',[$u['id']]); if(!$c) fail('Cart not found',404); $items=[]; foreach($c['items'] as $it){ if($it['product_id']===$d['product_id']){ if((int)$d['quantity']>0)$items[]=['product_id'=>$it['product_id'],'quantity'=>(int)$d['quantity']]; } else $items[]=$it; } db()->prepare('UPDATE carts SET items=?,updated_at=? WHERE user_id=?')->execute([json_encode($items),now_iso(),$u['id']]); out(['message'=>'Cart updated']); }
    if ($method === 'DELETE' && $path === '/api/cart/clear') { $u=current_user(); db()->prepare('UPDATE carts SET items=?,updated_at=? WHERE user_id=?')->execute(['[]',now_iso(),$u['id']]); out(['message'=>'Cart cleared']); }

    if ($method === 'POST' && $path === '/api/coupons/validate') {
        $d=json_body(); $code=strtoupper($d['code'] ?? ''); $amount=(float)($d['order_amount'] ?? 0); $c=one('SELECT * FROM coupons WHERE code=?',[$code]);
        if(!$c) fail('Invalid coupon code',404); if(!$c['is_active']) fail('Coupon is no longer active'); if(strtotime($c['valid_from'])>time() || strtotime($c['valid_until'])<time()) fail('Coupon has expired or not yet valid'); if($c['usage_limit'] && $c['used_count'] >= $c['usage_limit']) fail('Coupon usage limit reached'); if($amount < $c['min_purchase']) fail('Minimum purchase of Rs.'.$c['min_purchase'].' required');
        $discount = $c['discount_type']==='percentage' ? ($amount*$c['discount_value']/100) : $c['discount_value']; if($c['max_discount'] !== null) $discount=min($discount,$c['max_discount']); $discount=min($discount,$amount);
        out(['valid'=>true,'discount'=>$discount,'final_amount'=>$amount-$discount,'coupon_details'=>['code'=>$c['code'],'discount_type'=>$c['discount_type'],'discount_value'=>$c['discount_value']]]);
    }
    if ($method === 'GET' && $path === '/api/coupons/public') out(all('SELECT * FROM coupons WHERE is_active=1 AND valid_from<=? AND valid_until>=?', [now_iso(), now_iso()]));

    if ($method === 'POST' && $path === '/api/orders/create') { $u=current_user(); $d=json_body(); $discount=0;$coupon=null;$subtotal=(float)$d['total_amount']; if(!empty($d['coupon_code'])){ $code=strtoupper($d['coupon_code']); $c=one('SELECT * FROM coupons WHERE code=?',[$code]); if(!$c) fail('Invalid coupon code',404); $discount=$c['discount_type']==='percentage'?($subtotal*$c['discount_value']/100):$c['discount_value']; if($c['max_discount']!==null)$discount=min($discount,$c['max_discount']); $discount=min($discount,$subtotal); $coupon=$code; db()->prepare('UPDATE coupons SET used_count=used_count+1 WHERE code=?')->execute([$code]); } $id=uuidv4(); $ship=$d['shipping_address'] ?? []; db()->prepare('INSERT INTO orders (id,user_id,items,subtotal,discount,coupon_code,total_amount,status,payment_id,shipping_address,created_at) VALUES (?,?,?,?,?,?,?,"pending",NULL,?,?)')->execute([$id,$u['id'],json_encode($d['items'] ?? []),$subtotal,$discount,$coupon,$subtotal-$discount,json_encode($ship),now_iso()]); db()->prepare('UPDATE carts SET items=? WHERE user_id=?')->execute(['[]',$u['id']]); out(one('SELECT * FROM orders WHERE id=?',[$id])); }
    if ($method === 'POST' && $path === '/api/payments/create-order') { $d=json_body(); out(['id'=>'order_'.bin2hex(random_bytes(8)),'amount'=>(int)(((float)($d['amount'] ?? 0))*100),'currency'=>'INR','receipt'=>'receipt_'.bin2hex(random_bytes(4))]); }
    if ($method === 'POST' && $path === '/api/payments/verify') { current_user(); out(['success'=>true,'message'=>'Payment verified']); }
    if ($method === 'POST' && preg_match('#^/api/orders/([^/]+)/payment$#',$path,$m)) { $u=current_user(); $d=json_body(); $pay=$d['razorpay_payment_id'] ?? ('pay_dummy_'.bin2hex(random_bytes(6))); db()->prepare('UPDATE orders SET status="paid", payment_id=? WHERE id=? AND user_id=?')->execute([$pay,$m[1],$u['id']]); out(['message'=>'Payment successful','payment_id'=>$pay]); }
    if ($method === 'GET' && $path === '/api/orders') { $u=current_user(); out(all('SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC',[$u['id']])); }
    if ($method === 'GET' && preg_match('#^/api/orders/([^/]+)$#',$path,$m)) { $u=current_user(); $o=one('SELECT * FROM orders WHERE id=? AND user_id=?',[$m[1],$u['id']]); if(!$o) fail('Order not found',404); out($o); }

    if ($method === 'GET' && $path === '/api/blogs') out(all('SELECT * FROM blogs ORDER BY created_at DESC'));
    if ($method === 'POST' && $path === '/api/blogs/create') { $u=current_user(); $d=json_body(); $id=uuidv4(); db()->prepare('INSERT INTO blogs (id,title,content,author_id,author_name,created_at) VALUES (?,?,?,?,?,?)')->execute([$id,$d['title'],$d['content'],$u['id'],$u['name'],now_iso()]); out(one('SELECT * FROM blogs WHERE id=?',[$id])); }
    if ($method === 'GET' && preg_match('#^/api/blogs/([^/]+)$#',$path,$m)) { $b=one('SELECT * FROM blogs WHERE id=?',[$m[1]]); if(!$b) fail('Blog not found',404); out($b); }
    if ($method === 'DELETE' && preg_match('#^/api/admin/blogs/([^/]+)$#',$path,$m)) { admin_user(); db()->prepare('DELETE FROM blogs WHERE id=?')->execute([$m[1]]); out(['message'=>'Blog deleted successfully']); }

    if ($method === 'POST' && $path === '/api/contact/submit') { $d=json_body(); $id=uuidv4(); db()->prepare('INSERT INTO contact_forms (id,name,email,phone,address,feedback,status,admin_reply,created_at,replied_at) VALUES (?,?,?,?,?,? ,"pending",NULL,?,NULL)')->execute([$id,$d['name'],$d['email'],$d['phone'],$d['address'],$d['feedback'],now_iso()]); out(['message'=>'Contact form submitted successfully','id'=>$id]); }
    if ($method === 'GET' && $path === '/api/admin/contact-forms') { admin_user(); out(all('SELECT * FROM contact_forms ORDER BY created_at DESC')); }
    if ($method === 'POST' && preg_match('#^/api/admin/contact-forms/([^/]+)/reply$#',$path,$m)) { admin_user(); $d=json_body(); db()->prepare('UPDATE contact_forms SET admin_reply=?,status="replied",replied_at=? WHERE id=?')->execute([$d['reply'],now_iso(),$m[1]]); out(['message'=>'Reply sent successfully']); }
    if ($method === 'DELETE' && preg_match('#^/api/admin/contact-forms/([^/]+)$#',$path,$m)) { admin_user(); db()->prepare('DELETE FROM contact_forms WHERE id=?')->execute([$m[1]]); out(['message'=>'Contact form deleted']); }

    if ($method === 'GET' && $path === '/api/admin/users') { admin_user(); $users=all('SELECT id,email,name,is_admin,created_at,password FROM users ORDER BY created_at DESC'); foreach($users as &$u){$u['password']='........'.substr($u['password'],-8);} out($users); }
    if ($method === 'DELETE' && preg_match('#^/api/admin/users/([^/]+)$#',$path,$m)) { $a=admin_user(); if($m[1]===$a['id']) fail('Cannot delete your own admin account'); db()->prepare('DELETE FROM users WHERE id=?')->execute([$m[1]]); db()->prepare('DELETE FROM carts WHERE user_id=?')->execute([$m[1]]); out(['message'=>'User deleted successfully']); }
    if ($method === 'GET' && $path === '/api/admin/orders') { admin_user(); out(all('SELECT * FROM orders ORDER BY created_at DESC')); }
    if ($method === 'GET' && preg_match('#^/api/admin/orders/([^/]+)$#',$path,$m)) { admin_user(); $o=one('SELECT * FROM orders WHERE id=?',[$m[1]]); if(!$o) fail('Order not found',404); out($o); }
    if ($method === 'PUT' && preg_match('#^/api/admin/orders/([^/]+)/status$#',$path,$m)) { admin_user(); $d=json_body(); db()->prepare('UPDATE orders SET status=? WHERE id=?')->execute([$d['status'],$m[1]]); out(['message'=>'Order status updated']); }
    if ($method === 'PUT' && preg_match('#^/api/admin/orders/([^/]+)$#',$path,$m)) { admin_user(); $d=json_body(); $fields=[];$params=[]; foreach(['shipping_address','status','items','total_amount'] as $k){ if(array_key_exists($k,$d)){ $fields[]="$k=?"; $params[]=in_array($k,['items','shipping_address'])?json_encode($d[$k]):$d[$k]; }} if($fields){$params[]=$m[1]; db()->prepare('UPDATE orders SET '.implode(',',$fields).' WHERE id=?')->execute($params);} out(one('SELECT * FROM orders WHERE id=?',[$m[1]])); }
    if ($method === 'GET' && $path === '/api/admin/coupons') { admin_user(); out(all('SELECT * FROM coupons ORDER BY created_at DESC')); }
    if ($method === 'POST' && $path === '/api/admin/coupons/generate') { admin_user(); out(['code'=>substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'),0,8)]); }
    if ($method === 'POST' && $path === '/api/admin/coupons') { admin_user(); $d=json_body(); $id=uuidv4(); db()->prepare('INSERT INTO coupons (id,code,discount_type,discount_value,min_purchase,max_discount,usage_limit,used_count,valid_from,valid_until,is_active,created_at) VALUES (?,?,?,?,?,?,?,0,?,?,?,?)')->execute([$id,strtoupper($d['code']),$d['discount_type'],(float)$d['discount_value'],(float)($d['min_purchase'] ?? 0),isset($d['max_discount'])&&$d['max_discount']!==''?(float)$d['max_discount']:null,isset($d['usage_limit'])&&$d['usage_limit']!==''?(int)$d['usage_limit']:null,$d['valid_from'],$d['valid_until'],!empty($d['is_active'])?1:0,now_iso()]); out(one('SELECT * FROM coupons WHERE id=?',[$id])); }
    if ($method === 'PUT' && preg_match('#^/api/admin/coupons/([^/]+)$#',$path,$m)) { admin_user(); $d=json_body(); $fields=[];$params=[]; foreach(['is_active','usage_limit','valid_until'] as $k){ if(array_key_exists($k,$d)){ $fields[]="$k=?"; $params[]=$d[$k]; }} if($fields){$params[]=$m[1]; db()->prepare('UPDATE coupons SET '.implode(',',$fields).' WHERE id=?')->execute($params);} out(one('SELECT * FROM coupons WHERE id=?',[$m[1]])); }
    if ($method === 'DELETE' && preg_match('#^/api/admin/coupons/([^/]+)$#',$path,$m)) { admin_user(); db()->prepare('DELETE FROM coupons WHERE id=?')->execute([$m[1]]); out(['message'=>'Coupon deleted successfully']); }
    if ($method === 'GET' && $path === '/api/admin/stats') { admin_user(); $stats=['total_users'=>(int)one('SELECT COUNT(*) c FROM users')['c'],'total_orders'=>(int)one('SELECT COUNT(*) c FROM orders')['c'],'total_products'=>(int)one('SELECT COUNT(*) c FROM products')['c'],'total_revenue'=>(float)one('SELECT COALESCE(SUM(total_amount),0) c FROM orders WHERE status IN ("paid","processing","shipped","delivered")')['c'],'recent_orders'=>all('SELECT * FROM orders ORDER BY created_at DESC LIMIT 5')]; out($stats); }

    fail('Endpoint not found: ' . $path, 404);
} catch (PDOException $e) { fail('Database error: ' . $e->getMessage(), 500); } catch (Throwable $e) { fail($e->getMessage(), 500); }
