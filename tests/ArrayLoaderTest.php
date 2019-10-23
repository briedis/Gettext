<?php

namespace Gettext\Tests;

use Gettext\Translation;
use Gettext\Translations;
use Gettext\Loader\ArrayLoader;
use PHPUnit\Framework\TestCase;

class ArrayLoaderTest extends TestCase
{
    public function testArrayLoader()
    {
        $loader = new ArrayLoader();
        $array = [
            'domain' => 'testingdomain',
            'plural-forms' => 'nplurals=3; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2);',
            'messages' => [
                '' => [
                    '' => [
                        'Content-Transfer-Encoding: 8bit
Content-Type: text/plain; charset=UTF-8
Language: bs
Language-Team: 
Last-Translator: 
MIME-Version: 1.0
PO-Revision-Date: 
POT-Creation-Date: 
Plural-Forms: nplurals=3; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2);
Project-Id-Version: gettext generator test
Report-Msgid-Bugs-To: 
X-Domain: testingdomain
X-Generator: Poedit 1.6.5',
                    ],
                    'Ensure this value has at least %(limit_value)d character (it has %sd).' => [''],
                    'Ensure this value has at most %(limit_value)d character (it has %sd).' => [''],
                    '%ss must be unique for %ss %ss.' => ['%ss mora da bude jedinstven za %ss %ss.'],
                    'and' => ['i'],
                    'Value %sr is not a valid choice.' => [''],
                    'This field cannot be null.' => ['Ovo polje ne može ostati prazno.'],
                    'This field cannot be blank.' => ['Ovo polje ne može biti prazno.'],
                    'Field of type: %ss' => ['Polje tipa: %ss'],
                    'Integer' => ['Cijeo broj'],
                    '{test1}' => "test1\n<div>\ntest2\n</div>\ntest3",
                    '{test2}' => ["test1\n<div>\ntest2\n</div>\ntest3"],
                ],
                'other-context' => [
                    'Multibyte test' => ['日本人は日本で話される言語です！'],
                    'Tabulation test' => ['FIELD	FIELD']
                ],
            ],
        ];

        $translations = $loader->loadArray($array);
        
        $this->assertCount(13, $translations->getHeaders());
        $this->assertSame('1.0', $translations->getHeaders()->get('MIME-Version'));
        $this->assertCount(13, $translations);

        $translation = $translations->find(null, 'Integer');

        $this->assertNotNull($translation);
        $this->assertSame('Cijeo broj', $translation->getTranslation());
        $this->assertCount(0, $translation->getPluralTranslations());
    }
}