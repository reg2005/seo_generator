<?php
require_once __DIR__.'/vendor/autoload.php';

require_once('seo_generator.php');

$text = 'Самым известным «рыбным» текстом является знаменитый Lorem ipsum. Считается, что впервые его применили в книгопечатании еще в XVI веке. Своим появлением Lorem ipsum обязан древнеримскому философу Цицерону, ведь именно из его трактата «О пределах добра и зла» средневековый книгопечатник вырвал отдельные фразы и слова, получив текст-«рыбу», широко используемый и по сей день. Конечно, возникают некоторые вопросы, связанные с использованием Lorem ipsum на сайтах и проектах, ориентированных на кириллический контент – написание символов на латыни и на кириллице значительно различается.';


$seo = new Seo_generator\Seo();

$result['keywords'] = $seo->get_keywords($text, TRUE, TRUE);

$result['description'] = $seo->get_description($text);

print_r($result);
