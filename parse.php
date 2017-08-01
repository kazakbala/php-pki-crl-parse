<?php

require(__DIR__ . '/vendor/autoload.php');

use FG\ASN1\TemplateParser;
$template = [
FG\ASN1\Identifier::SEQUENCE => [
    FG\ASN1\Identifier::SEQUENCE => [
            FG\ASN1\Identifier::INTEGER,
            FG\ASN1\Identifier::SEQUENCE => [
                FG\ASN1\Identifier::OBJECT_IDENTIFIER,
                FG\ASN1\Identifier::NULL,
            ]
        ]
    ]
];

$parser = new TemplateParser();
$object = $parser->parseBinary(file_get_contents(__DIR__."/d_rsa.crl"), $template);
echo "Список отозванных сертификатов с Delta на ";
foreach($object->getChildren() as $child) {
    foreach ($child as $key=>$obj) {
        if ($key==3) {
            echo $obj->getContent()->format('Y-m-d\TH:i:s.u'); 
            echo '<hr>';
        } elseif ($key==5) {
            foreach($obj->getChildren() as $cert) {
                
               echo bcdechex($cert[0]->getContent())."<br>";
            }
        }
    }
    
}
function bcdechex($dec) {
    $last = bcmod($dec, 16);
    $remain = bcdiv(bcsub($dec, $last), 16);
    if($remain == 0) {
        return dechex($last);
    } else {
        return bcdechex($remain).dechex($last);
    }
}
