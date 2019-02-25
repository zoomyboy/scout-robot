<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Utilities\Parser;

class ParseHtmlContentTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->parser = new Parser();
    }

    /** @test */
    public function an_empty_string_is_converted_into_a_normal_empty_line()
    {
        $this->assertEquals(
            [
                (object) ['type' => 'p', 'text' => '']
            ],
            $this->parser->parseHtmlContent('')
        );
    }

    /** @test */
    public function a_string_without_html_tags_is_converted_to_a_normal_line_with_that_text() {
        $this->assertEquals(
            [
                (object) ['type' => 'p', 'text' => 'Hello doe']
            ],
            $this->parser->parseHtmlContent('Hello doe')
        );
    }

    /** @test */
    public function a_string_with_one_strong_element_is_converted_into_one_line_of_type_strong() {
        $this->assertEquals(
            [
                (object)['text' => 'Hello doe', 'type' => 'strong']
            ],
            $this->parser->parseHtmlContent('<strong>Hello doe</strong>')
        );
    }

    /** @test */
    public function a_string_with_tags_and_normal_text_before_it_is_converted_into_p_and_strong() {
        $this->assertEquals(
            [
                (object)['text' => 'Hello doe', 'type' => 'strong'],
                (object)['text' => 'Ciao', 'type' => 'p']
            ],
            $this->parser->parseHtmlContent('<strong>Hello doe</strong>Ciao')
        );
    }

    /** @test */
    public function it_parses_utf8_characters_correctly() {
        $this->assertEquals(
            [
                (object)['text' => '€Ü', 'type' => 'p']
            ],
            $this->parser->parseHtmlContent('€Ü')
        );
    }

    /** @test */
    public function it_parses_a_date() {
        $this->assertEquals(
            [
                (object)['text' => 'Ciao', 'type' => 'p'],
                (object)['text' => 'Hello doe', 'type' => 'strong'],
            ],
            $this->parser->parseHtmlContent('Ciao<strong>Hello doe</strong>')
        );
    }
}
