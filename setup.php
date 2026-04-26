<?php
require_once __DIR__ . '/config.php';

// Connect without database first to create it
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
if ($conn->connect_error) {
    die('<h2>MySQL Connection Failed:</h2><p>' . $conn->connect_error . '</p>
    <p>Please check your MySQL credentials in <strong>config.php</strong>.</p>');
}
$conn->set_charset('utf8mb4');

// Create database
$conn->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db(DB_NAME);

// Create tables
$conn->query("DROP TABLE IF EXISTS breaking_news");
$conn->query("DROP TABLE IF EXISTS articles");
$conn->query("DROP TABLE IF EXISTS categories");
$conn->query("DROP TABLE IF EXISTS admin_users");

$conn->query("CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    color VARCHAR(20) DEFAULT '#c0392b',
    sort_order INT DEFAULT 0,
    parent_id INT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$conn->query("CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(500) NOT NULL,
    slug VARCHAR(500) NOT NULL UNIQUE,
    content LONGTEXT NOT NULL,
    excerpt TEXT,
    category_id INT NOT NULL,
    image VARCHAR(255),
    author VARCHAR(100) DEFAULT 'सम्पादक',
    views INT DEFAULT 0,
    is_featured TINYINT(1) DEFAULT 0,
    is_breaking TINYINT(1) DEFAULT 0,
    status ENUM('published','draft') DEFAULT 'published',
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$conn->query("CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$conn->query("CREATE TABLE breaking_news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text TEXT NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Insert admin user (password: admin123)
$pass = password_hash('admin123', PASSWORD_DEFAULT);
$conn->query("INSERT INTO admin_users (username, password, email, full_name) VALUES ('admin', '$pass', 'admin@newsportal.com', 'प्रधान सम्पादक')");

// Insert categories
$categories = [
    ['राजनीति',    'rajniti',        '#c0392b', 1],
    ['देश',         'desh',           '#2980b9', 2],
    ['अन्तर्राष्ट्रिय','antarrashtriya','#27ae60', 3],
    ['अर्थ/वाणिज्य', 'artha',         '#e67e22', 4],
    ['खेलकुद',      'khelkud',        '#8e44ad', 5],
    ['स्वास्थ्य',   'swasthya',       '#16a085', 6],
    ['प्रविधि',     'prabidhi',       '#2c3e50', 7],
    ['मनोरञ्जन',   'manoranjan',     '#e91e63', 8],
    ['शिक्षा',      'shiksha',        '#795548', 9],
    ['प्रदेश',      'pradesh',        '#607d8b', 10],
];

foreach ($categories as $cat) {
    $stmt = $conn->prepare("INSERT INTO categories (name, slug, color, sort_order) VALUES (?,?,?,?)");
    $stmt->bind_param("sssi", $cat[0], $cat[1], $cat[2], $cat[3]);
    $stmt->execute();
}

// Sample Nepali articles with picsum images
$articles_data = [
    // Politics
    ['प्रधानमन्त्रीले राष्ट्रिय एकता दिवसको अवसरमा देशवासीलाई सम्बोधन गरे', 'pm-rashtriya-ekta',
     'प्रधानमन्त्रीले आज राष्ट्रिय एकता दिवसको अवसरमा देशवासीलाई सम्बोधन गर्दै देशको एकता र अखण्डता कायम राख्न सबैलाई आह्वान गरेका छन्।

उनले भने, "नेपाल एक समृद्ध राष्ट्र बन्नका लागि सबै नागरिकले मिलेर काम गर्नु आवश्यक छ। हाम्रो विविधता नै हाम्रो शक्ति हो।"

प्रधानमन्त्रीले थपे कि आगामी आर्थिक वर्षमा देशको पूर्वाधार विकासमा ठूलो लगानी गरिनेछ। सडक, विद्युत र खानेपानीका क्षेत्रमा अर्बौं रुपैयाँ खर्च गर्ने योजना रहेको उनले जानकारी दिए।

सरकारले गरिबी निवारण, रोजगार सिर्जना र युवा उद्यमशीलता प्रवर्धनमा विशेष ध्यान दिने भएको पनि उनले बताए।',
     'प्रधानमन्त्रीले राष्ट्रिय एकता दिवसमा देशवासीलाई सम्बोधन गर्दै एकता र विकासको आह्वान गरे।',
     1, 'https://picsum.photos/seed/pm1/800/500', 'राजनीतिक संवाददाता', 1245, 1, 1],

    ['संसद्मा बजेट अधिवेशन सुरु: मुख्य बुँदाहरू के के छन्?', 'bhajet-adhiveshan',
     'संघीय संसद्को बजेट अधिवेशन आज औपचारिक रूपमा सुरु भएको छ। यस अधिवेशनमा आगामी आर्थिक वर्षको बजेट प्रस्तुत गरिनेछ।

अर्थमन्त्रीले संसद्मा बजेट प्रस्तुत गर्दै भने कि यो बजेट जनताको आकाँक्षा पूरा गर्नेखालको हुनेछ।

बजेटका मुख्य बुँदाहरूमा शिक्षामा बजेट वृद्धि, स्वास्थ्य सेवाको विस्तार र कृषि क्षेत्रलाई प्राथमिकता दिनु रहेको छ।',
     'संसद्मा बजेट अधिवेशन सुरु भई अर्थमन्त्रीले मुख्य योजनाहरू सार्वजनिक गरे।',
     1, 'https://picsum.photos/seed/bj2/800/500', 'संसद् संवाददाता', 892, 0, 0],

    // Desh
    ['काठमाडौंमा नयाँ मेट्रो रेल योजना अगाडि बढ्यो', 'kathmandu-metro',
     'काठमाडौं उपत्यकामा नयाँ मेट्रो रेल सञ्जाल निर्माणको योजना थप अगाडि बढेको छ। सरकारले यस परियोजनाका लागि विदेशी लगानी आकर्षण गर्ने प्रयास तेज गरेको छ।

यो परियोजना पूरा भएपछि काठमाडौं, ललितपुर र भक्तपुरका नागरिकहरूलाई सुगम यातायात सुविधा मिल्नेछ।

अधिकारीहरूका अनुसार निर्माण कार्य आगामी वर्षभित्र सुरु हुने सम्भावना छ।',
     'काठमाडौंमा मेट्रो रेल परियोजना अगाडि बढ्दै, सरकारले विदेशी लगानी खोज्दै।',
     2, 'https://picsum.photos/seed/metro3/800/500', 'नगर संवाददाता', 2341, 1, 0],

    ['नेपालमा विद्युत उत्पादन नयाँ कीर्तिमान: ३ हजार मेगावाट नाघ्यो', 'bijuli-record',
     'नेपालको विद्युत उत्पादन पहिलो पटक ३ हजार मेगावाट नाघेको छ। यो ऐतिहासिक उपलब्धि नेपालको ऊर्जा क्षेत्रका लागि मील पत्थर मानिएको छ।

नेपाल विद्युत प्राधिकरणका अनुसार यस उपलब्धिले भारत र बंगलादेशमा विद्युत निर्यातको ढोका थप खुल्नेछ।

विशेषज्ञहरू भन्छन् कि यदि यो गतिले विद्युत उत्पादन जारी रह्यो भने नेपाल आगामी दशकमा ऊर्जा निर्यातमुखी देश बन्न सक्छ।',
     'नेपालको विद्युत उत्पादन ३ हजार मेगावाट नाघी ऐतिहासिक कीर्तिमान स्थापित।',
     2, 'https://picsum.photos/seed/bj4/800/500', 'ऊर्जा संवाददाता', 1876, 1, 1],

    // International
    ['भारतसँगको व्यापार सम्झौतामा नयाँ प्रावधानहरू थपिए', 'india-trade',
     'नेपाल र भारतबीचको व्यापार तथा पारवहन सन्धिमा नयाँ प्रावधानहरू थपिएका छन्।

यी प्रावधानले नेपाली निर्यातकर्ताहरूलाई भारतीय बजारमा पहुँच सजिलो बनाउनेछ। विशेष गरी कृषि उत्पादन र हस्तकला वस्तुहरूलाई फाइदा हुनेछ।

दुई देशबीचको सम्बन्ध थप सुदृढ गर्ने उद्देश्यले यो सम्झौता महत्त्वपूर्ण मानिएको छ।',
     'नेपाल-भारत व्यापार सम्झौतामा नयाँ प्रावधान थपिई नेपाली निर्यातकर्तालाई फाइदा।',
     3, 'https://picsum.photos/seed/ind5/800/500', 'परराष्ट्र संवाददाता', 654, 0, 0],

    // Economy
    ['शेयर बजारमा तेजी: नेप्से ३ हजार बिन्दु नजिक', 'nepse-teji',
     'नेपाल शेयर बजार (नेप्से) आज ३ हजार बिन्दु नजिक पुगेको छ। यो गत एक वर्षमा नेप्सेको उच्चतम विन्दु हो।

लगानीकर्ताहरूको बढ्दो विश्वास र बैंकिङ क्षेत्रको राम्रो प्रदर्शनले बजारलाई माथि उठाएको विश्लेषकहरू बताउँछन्।

सेबोनका अनुसार आज कारोबार रकम २ अर्ब नाघेको छ जुन गत महिनाभन्दा ३५ प्रतिशत बढी हो।',
     'नेप्से ३ हजार बिन्दु नजिक पुग्दा लगानीकर्ताहरूमा उत्साह छाएको।',
     4, 'https://picsum.photos/seed/nepse6/800/500', 'आर्थिक संवाददाता', 3214, 1, 0],

    ['पर्यटन क्षेत्रमा रेकर्ड वृद्धि: ११ लाख पर्यटक नेपाल भित्रिए', 'tourism-record',
     'यस वर्ष नेपालमा ११ लाखभन्दा बढी विदेशी पर्यटक आएका छन् जुन नेपालको इतिहासमा सर्वाधिक हो।

पर्यटन बोर्डका अनुसार सगरमाथा आधार शिविरमा यस वर्ष ५० हजारभन्दा बढी पर्यटक पुगेका छन्।

पर्यटन क्षेत्रबाट यस वर्ष करिब ३ अर्ब अमेरिकी डलर आम्दानी हुने अनुमान गरिएको छ।',
     'नेपालमा ११ लाख पर्यटक आई रेकर्ड स्थापित, ३ अर्ब डलर आम्दानी अनुमान।',
     4, 'https://picsum.photos/seed/tour7/800/500', 'पर्यटन संवाददाता', 4521, 1, 1],

    // Sports
    ['नेपाली क्रिकेट टोलीले एसिया कपमा ऐतिहासिक जित हात पार्‍यो', 'cricket-asia-cup',
     'नेपाली क्रिकेट टोलीले एसिया कपको महत्त्वपूर्ण खेलमा शानदार जित हात पारेको छ।

रोहित पौडेलको अगुवाइमा नेपाली टोलीले लक्ष्य पछ्याउँदै सफलता पाएको हो। उनले ७५ रनको महत्त्वपूर्ण पारी खेले।

यो जित नेपाली क्रिकेटको इतिहासमा मील पत्थर मानिएको छ। सम्पूर्ण देशमा खुसीको लहर छाएको छ।',
     'नेपाली क्रिकेट टोलीले एसिया कपमा ऐतिहासिक जित दर्ता गर्‍यो।',
     5, 'https://picsum.photos/seed/cricket8/800/500', 'खेल संवाददाता', 7832, 1, 1],

    ['साग खेलकुदमा नेपालले सुनको पदक जित्यो', 'sag-gold',
     'दक्षिण एसियाली खेलकुद (साग) प्रतियोगितामा नेपाली खेलाडीले सुनको पदक जितेका छन्।

कराते र वुसुमा नेपालले थप पदक थप्दै पदक तालिकामा राम्रो स्थान कायम गरेको छ।

खेलकुद मन्त्रीले नेपाली खेलाडीहरूको प्रदर्शनप्रति सन्तोष व्यक्त गर्दै भने कि खेलकुद विकासमा थप लगानी गरिनेछ।',
     'साग खेलकुदमा नेपाली खेलाडीले सुनको पदक जित्दै राम्रो प्रदर्शन।',
     5, 'https://picsum.photos/seed/sag9/800/500', 'खेल संवाददाता', 2134, 0, 0],

    // Health
    ['स्वास्थ्य मन्त्रालयले नयाँ टीकाकरण अभियान सुरु गर्‍यो', 'tikakaran',
     'स्वास्थ्य तथा जनसंख्या मन्त्रालयले देशव्यापी टीकाकरण अभियान सुरु गरेको छ।

यस अभियान अन्तर्गत ५ वर्षमुनिका बच्चाहरूलाई निःशुल्क टीकाकरण गरिनेछ।

मन्त्रालयका अनुसार यस पटकको अभियानमा ३० लाखभन्दा बढी बच्चाहरूलाई टीका लगाउने लक्ष्य राखिएको छ।',
     'स्वास्थ्य मन्त्रालयले ३० लाख बच्चालाई लक्षित गरी देशव्यापी टीकाकरण अभियान सुरु।',
     6, 'https://picsum.photos/seed/health10/800/500', 'स्वास्थ्य संवाददाता', 1123, 0, 0],

    // Technology
    ['नेपालमा ५जी इन्टरनेट सेवा सुरु हुने तयारी', '5g-nepal',
     'नेपाल दूरसञ्चार प्राधिकरणले देशमा ५जी इन्टरनेट सेवा सुरु गर्ने तयारी गरिरहेको छ।

आगामी वर्षभित्र काठमाडौं उपत्यकामा परीक्षण सुरु हुने अधिकारीहरूले जानकारी दिएका छन्।

५जी सेवाले इन्टरनेट गति अहिलेभन्दा दश गुणा बढाउने अपेक्षा गरिएको छ।',
     'नेपालमा ५जी इन्टरनेट सेवाको तयारी सुरु, काठमाडौंबाट परीक्षण हुने।',
     7, 'https://picsum.photos/seed/5g11/800/500', 'प्रविधि संवाददाता', 3456, 0, 0],

    // Education
    ['एसईई परीक्षाफल प्रकाशन: कति विद्यार्थी उत्तीर्ण?', 'see-result',
     'राष्ट्रिय परीक्षा बोर्डले माध्यमिक शिक्षा परीक्षा (एसईई) को परीक्षाफल प्रकाशन गरेको छ।

यस पटक कुल उत्तीर्ण प्रतिशत ७८ रहेको छ जुन गत वर्षभन्दा ५ प्रतिशत बढी हो।

जिल्लागत रूपमा काठमाडौंको नतिजा सर्वोत्तम रहेको बोर्डले जानकारी दिएको छ।',
     'एसईई परीक्षाफल प्रकाशन, ७८ प्रतिशत उत्तीर्ण भई गत वर्षभन्दा सुधार।',
     9, 'https://picsum.photos/seed/see12/800/500', 'शिक्षा संवाददाता', 8921, 1, 0],
];

foreach ($articles_data as $art) {
    $stmt = $conn->prepare("INSERT INTO articles (title, slug, content, excerpt, category_id, image, author, views, is_featured, is_breaking, published_at) VALUES (?,?,?,?,?,?,?,?,?,?,DATE_SUB(NOW(), INTERVAL FLOOR(RAND()*30) DAY))");
    $stmt->bind_param("ssssissiii", $art[0], $art[1], $art[2], $art[3], $art[4], $art[5], $art[6], $art[7], $art[8], $art[9]);
    $stmt->execute();
}

// Insert breaking news
$breaking = [
    'काठमाडौंमा आज बिहान ठूलो भूकम्प गयो, तत्काल सुरक्षित स्थानमा जानुहोस्',
    'प्रधानमन्त्रीले आजदेखि नयाँ आर्थिक नीति लागू गर्ने घोषणा गरे',
    'नेपाली क्रिकेट टोलीले एसिया कप जित्यो - देशभर खुसीको लहर',
    'नेप्से ३ हजार बिन्दु नाघ्यो, शेयर बजारमा ठूलो तेजी',
    'नेपालमा ५जी परीक्षण सुरु हुने, काठमाडौंवासीलाई छिटो इन्टरनेट',
];
foreach ($breaking as $b) {
    $stmt = $conn->prepare("INSERT INTO breaking_news (text) VALUES (?)");
    $stmt->bind_param("s", $b);
    $stmt->execute();
}

// Create uploads .htaccess
file_put_contents(__DIR__ . '/uploads/.htaccess', "php_flag engine off\n");

echo '<!DOCTYPE html><html lang="ne"><head><meta charset="UTF-8">
<title>Setup Complete</title>
<style>
  body { font-family: "Noto Sans Devanagari", sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
  .box { background: white; border-radius: 12px; padding: 40px; max-width: 520px; width: 100%; box-shadow: 0 8px 30px rgba(0,0,0,0.1); text-align: center; }
  h1 { color: #27ae60; font-size: 28px; margin-bottom: 8px; }
  p { color: #555; line-height: 1.7; margin: 6px 0; }
  .btn { display: inline-block; margin: 8px; padding: 12px 24px; border-radius: 6px; font-size: 15px; font-weight: 700; text-decoration: none; }
  .btn-site { background: #c0392b; color: white; }
  .btn-admin { background: #2c3e50; color: white; }
  .creds { background: #f8f8f8; border: 1px solid #e0e0e0; border-radius: 8px; padding: 16px; margin: 16px 0; text-align: left; font-size: 14px; }
  .creds strong { color: #c0392b; }
  .warn { background: #fef9e7; border: 1px solid #f9ca24; border-radius: 6px; padding: 10px; font-size: 13px; color: #856404; margin-top: 14px; }
</style></head><body>
<div class="box">
  <h1>✓ सेटअप सफल भयो!</h1>
  <p>डेटाबेस र सम्पूर्ण तालिकाहरू बनाइयो।</p>
  <p>नमूना समाचारहरू थपिए।</p>
  <div class="creds">
    <p><strong>Admin Login:</strong></p>
    <p>URL: <strong>/news/admin/login.php</strong></p>
    <p>Username: <strong>admin</strong></p>
    <p>Password: <strong>admin123</strong></p>
  </div>
  <a href="/news/" class="btn btn-site">वेबसाइट हेर्नुहोस्</a>
  <a href="/news/admin/login.php" class="btn btn-admin">Admin Panel</a>
  <div class="warn">⚠️ सेटअप पूरा भएपछि <strong>setup.php</strong> फाइल हटाउनुहोस् वा rename गर्नुहोस्।</div>
</div></body></html>';
